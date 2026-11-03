<?php

namespace App\Services;

use App\Models\Sms;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class QueueMonitorService
{
    protected string $host;
    protected int $port;
    protected string $user;
    protected string $pass;
    protected string $vhost;
    protected string $defaultQueue;

    public function __construct()
    {
        $this->host = env('RABBITMQ_HOST', '127.0.0.1');
        $this->port = (int) env('RABBITMQ_MGMT_PORT', 15672);
        $this->user = env('RABBITMQ_USER', 'sms_softcents');
        $this->pass = env('RABBITMQ_PASSWORD', 'sms_softcents');
        $this->vhost = env('RABBITMQ_VHOST', '/');
        $this->defaultQueue = env('RABBITMQ_QUEUE', 'sms');
    }

    /**
     * Send HTTP request to RabbitMQ Management API.
     */
    protected function rabbitApi(string $endpoint, string $method = 'GET')
    {
        try {
            $url = sprintf('http://%s:%d/api/%s', $this->host, $this->port, ltrim($endpoint, '/'));
            $request = Http::withBasicAuth($this->user, $this->pass)->timeout(3);

            $response = match (strtoupper($method)) {
                'DELETE' => $request->delete($url),
                'POST'   => $request->post($url),
                default  => $request->get($url),
            };

            if ($response->successful()) {
                return $response->json();
            }

            Log::warning("RabbitMQ API {$endpoint} responded with status {$response->status()}");
            return null;
        } catch (\Throwable $e) {
            Log::warning("RabbitMQ API {$endpoint} connection failed: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get overall RabbitMQ Broker & Cluster Health.
     */
    public function getBrokerOverview(): array
    {
        $data = $this->rabbitApi('overview');

        if (!$data) {
            return [
                'status'        => 'offline',
                'version'       => 'Unknown',
                'node'          => 'Unavailable',
                'total_queues'  => 0,
                'total_messages'=> 0,
                'ready_messages'=> 0,
                'unack_messages'=> 0,
                'publish_rate'  => 0.0,
                'deliver_rate'  => 0.0,
                'ack_rate'      => 0.0,
            ];
        }

        $queueTotals = $data['queue_totals'] ?? [];
        $messageStats = $data['message_stats'] ?? [];

        return [
            'status'         => 'online',
            'version'        => $data['rabbitmq_version'] ?? '3.12.x',
            'cluster_name'   => $data['cluster_name'] ?? 'rabbit@vsms',
            'total_messages' => (int) ($queueTotals['messages'] ?? 0),
            'ready_messages' => (int) ($queueTotals['messages_ready'] ?? 0),
            'unack_messages' => (int) ($queueTotals['messages_unacknowledged'] ?? 0),
            'publish_rate'   => (float) ($messageStats['publish_details']['rate'] ?? 0.0),
            'deliver_rate'   => (float) ($messageStats['deliver_get_details']['rate'] ?? 0.0),
            'ack_rate'       => (float) ($messageStats['ack_details']['rate'] ?? 0.0),
        ];
    }

    /**
     * Get real-time stats for all active queues.
     */
    public function getQueues(): array
    {
        $queues = $this->rabbitApi('queues');

        if (!is_array($queues)) {
            return [];
        }

        $list = [];
        foreach ($queues as $q) {
            $stats = $q['message_stats'] ?? [];
            $bytes = (int) ($q['memory'] ?? 0);

            $list[] = [
                'name'         => $q['name'] ?? '',
                'vhost'        => $q['vhost'] ?? '/',
                'state'        => $q['state'] ?? 'running',
                'messages'     => (int) ($q['messages'] ?? 0),
                'ready'        => (int) ($q['messages_ready'] ?? 0),
                'unack'        => (int) ($q['messages_unacknowledged'] ?? 0),
                'consumers'    => (int) ($q['consumers'] ?? 0),
                'memory_human' => $this->formatBytes($bytes),
                'publish_rate' => (float) ($stats['publish_details']['rate'] ?? 0.0),
                'deliver_rate' => (float) ($stats['deliver_get_details']['rate'] ?? 0.0),
                'ack_rate'     => (float) ($stats['ack_details']['rate'] ?? 0.0),
            ];
        }

        return $list;
    }

    /**
     * Get Worker Pool Health and active worker connections.
     */
    public function getWorkerPoolStatus(): array
    {
        $connections = $this->rabbitApi('connections');

        if (!is_array($connections)) {
            return [
                'status'           => 'unknown',
                'active_workers'   => 0,
                'expected_workers' => 8,
                'workers'          => [],
            ];
        }

        // Filter connections created by our SMS application worker pool
        $workers = [];
        foreach ($connections as $conn) {
            $user = $conn['user'] ?? '';
            if ($user === $this->user) {
                $workers[] = [
                    'name'         => $conn['name'] ?? 'Worker',
                    'peer_host'    => $conn['peer_host'] ?? '127.0.0.1',
                    'peer_port'    => $conn['peer_port'] ?? '',
                    'state'        => $conn['state'] ?? 'running',
                    'channels'     => (int) ($conn['channels'] ?? 0),
                    'connected_at' => isset($conn['connected_at']) ? date('H:i:s', (int) ($conn['connected_at'] / 1000)) : 'Active',
                ];
            }
        }

        $count = count($workers);
        $expected = 8;

        $status = 'healthy';
        if ($count === 0) {
            $status = 'critical';
        } elseif ($count < $expected) {
            $status = 'degraded';
        }

        return [
            'status'           => $status,
            'active_workers'   => $count,
            'expected_workers' => $expected,
            'workers'          => $workers,
        ];
    }

    /**
     * Get real-time queue and dispatch summary for all Resellers and Sub-Resellers.
     */
    public function getResellerHierarchySummary(): array
    {
        $resellers = User::whereIn('role', ['sp', 'ssp'])->orderBy('role')->orderBy('name')->get();
        $summary = [];

        foreach ($resellers as $r) {
            if ($r->role === 'sp') {
                $sspIds = User::where('parent_id', $r->id)->where('role', 'ssp')->pluck('id')->toArray();
                $downline = User::where(function ($q) use ($r, $sspIds) {
                    $q->where('parent_id', $r->id);
                    if (!empty($sspIds)) {
                        $q->orWhereIn('parent_id', $sspIds);
                    }
                })->pluck('id')->toArray();
                $allIds = array_unique(array_merge([$r->id], $sspIds, $downline));
            } else {
                $downline = User::where('parent_id', $r->id)->pluck('id')->toArray();
                $allIds = array_unique(array_merge([$r->id], $downline));
            }

            $pending = Sms::whereIn('user_id', $allIds)->where('status', 'pending')->count();
            $today = Sms::whereIn('user_id', $allIds)->whereDate('created_at', today())->count();
            $todaySuccess = Sms::whereIn('user_id', $allIds)->whereDate('created_at', today())->where('status', 'success')->count();
            $rate = $today > 0 ? round(($todaySuccess / $today) * 100, 1) : 100.0;

            $summary[] = [
                'id'            => $r->id,
                'name'          => $r->name,
                'username'      => $r->username ?? $r->name,
                'role'          => $r->role,
                'role_label'    => $r->role === 'sp' ? 'Reseller (SP)' : 'Sub-Reseller (SSP)',
                'customers'     => count($downline),
                'pending_queue' => $pending,
                'today_total'   => $today,
                'today_success' => $todaySuccess,
                'delivery_rate' => $rate,
                'balance'       => round((float) ($r->balance ?? 0), 2),
            ];
        }

        return $summary;
    }

    /**
     * Strict backend verification that an actor is authorized to inspect a target account's queue.
     */
    public function validateAccountAccess(?User $actor, ?int $targetUserId): bool
    {
        if (!$targetUserId) {
            return true;
        }

        if (!$actor) {
            return false;
        }

        if (in_array($actor->role, ['admin', 'superadmin'])) {
            return true;
        }

        $allowedIds = $this->getScopedUserIds($actor);
        return $allowedIds !== null && in_array($targetUserId, $allowedIds, true);
    }

    /**
     * Get Scoped User IDs according to multi-tenant hierarchy.
     */
    public function getScopedUserIds(?User $user): ?array
    {
        if (!$user || in_array($user->role, ['admin', 'superadmin'])) {
            return null; // Global - all users
        }

        if ($user->role === 'sp') {
            $sspIds = User::where('parent_id', $user->id)->where('role', 'ssp')->pluck('id')->toArray();
            $customerIds = User::where(function ($q) use ($user, $sspIds) {
                $q->where('parent_id', $user->id);
                if (!empty($sspIds)) {
                    $q->orWhereIn('parent_id', $sspIds);
                }
            })->pluck('id')->toArray();

            return array_values(array_unique(array_merge([$user->id], $sspIds, $customerIds)));
        }

        if ($user->role === 'ssp') {
            $customerIds = User::where('parent_id', $user->id)->pluck('id')->toArray();
            return array_values(array_unique(array_merge([$user->id], $customerIds)));
        }

        // End user
        return [$user->id];
    }

    /**
     * Get list of downline accounts available for queue drill-down filtering.
     */
    public function getFilterableDownline(?User $user): array
    {
        if (!$user || in_array($user->role, ['admin', 'superadmin'])) {
            return User::whereIn('role', ['sp', 'ssp', 'user'])
                ->select('id', 'name', 'username', 'role')
                ->orderBy('role')
                ->orderBy('name')
                ->get()
                ->toArray();
        }

        if ($user->role === 'sp') {
            $sspIds = User::where('parent_id', $user->id)->where('role', 'ssp')->pluck('id')->toArray();
            return User::where(function ($q) use ($user, $sspIds) {
                $q->where('parent_id', $user->id);
                if (!empty($sspIds)) {
                    $q->orWhereIn('parent_id', $sspIds);
                }
            })
            ->select('id', 'name', 'username', 'role')
            ->orderBy('role')
            ->orderBy('name')
            ->get()
            ->toArray();
        }

        if ($user->role === 'ssp') {
            return User::where('parent_id', $user->id)
                ->where('role', 'user')
                ->select('id', 'name', 'username', 'role')
                ->orderBy('name')
                ->get()
                ->toArray();
        }

        return [];
    }

    /**
     * Get Live Scoped CDR and Queue Metrics.
     */
    public function getScopedLiveMetrics(?User $user, ?int $filterUserId = null): array
    {
        $scopedIds = $this->getScopedUserIds($user);

        // Apply drill-down filter if provided and strictly validate backend authorization
        if ($filterUserId) {
            if (!$this->validateAccountAccess($user, $filterUserId)) {
                abort(403, 'Unauthorized access: You do not have permission to monitor this account queue.');
            }
            $scopedIds = [$filterUserId];
        }

        // 1. Pending & Processing Queue in DB
        $pendingQuery = Sms::query();
        if ($scopedIds !== null) {
            $pendingQuery->whereIn('user_id', $scopedIds);
        }
        $pendingCount = (clone $pendingQuery)->where('status', 'pending')->count();
        $processingCount = (clone $pendingQuery)->whereIn('status', ['processing', 'sending', 'in_progress', 'queued'])->count();

        // If global NOC view, incorporate active RabbitMQ unacknowledged worker messages
        if ($scopedIds === null) {
            $broker = $this->getBrokerOverview();
            $processingCount = max($processingCount, (int) ($broker['unack_messages'] ?? 0));
        }

        // 2. Submitted SMS (Dispatched to Gateway) & Today's Volume
        $baseSmsQuery = Sms::query();
        if ($scopedIds !== null) {
            $baseSmsQuery->whereIn('user_id', $scopedIds);
        }
        $submittedCount = (clone $baseSmsQuery)->whereIn('status', ['success', 'submitted', 'sent'])->count();

        $todayQuery = (clone $baseSmsQuery)->whereDate('created_at', today());
        $todayTotal = (clone $todayQuery)->count();
        $todaySubmitted = (clone $todayQuery)->whereIn('status', ['success', 'submitted', 'sent'])->count();
        $todaySuccess = (clone $todayQuery)->where('status', 'success')->count();
        $todayFailed = (clone $todayQuery)->where('status', 'failed')->count();
        $deliveryRate = $todayTotal > 0 ? round(($todaySuccess / $todayTotal) * 100, 1) : 100.0;

        // 3. Real-time Throughput (TPS in last 60s, last 5m, last 1h)
        $tpsQuery = Sms::query()->where('created_at', '>=', now()->subMinute());
        if ($scopedIds !== null) {
            $tpsQuery->whereIn('user_id', $scopedIds);
        }
        $last1MinCount = (clone $tpsQuery)->count();
        $currentTps = round($last1MinCount / 60, 2);

        $last5MinQuery = Sms::query()->where('created_at', '>=', now()->subMinutes(5));
        if ($scopedIds !== null) {
            $last5MinQuery->whereIn('user_id', $scopedIds);
        }
        $last5MinCount = (clone $last5MinQuery)->count();

        $last1HourQuery = Sms::query()->where('created_at', '>=', now()->subHour());
        if ($scopedIds !== null) {
            $last1HourQuery->whereIn('user_id', $scopedIds);
        }
        $last1HourCount = (clone $last1HourQuery)->count();

        // 4. Operator Distribution (Last 24 Hours)
        $opQuery = Sms::query()
            ->select('operator', DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subDay());
        if ($scopedIds !== null) {
            $opQuery->whereIn('user_id', $scopedIds);
        }
        $operatorRaw = $opQuery->groupBy('operator')->get();

        $operators = [
            'GP'       => 0,
            'BL'       => 0,
            'Robi'     => 0,
            'Teletalk' => 0,
            'Other'    => 0,
        ];
        foreach ($operatorRaw as $row) {
            $op = trim((string) $row->operator);
            if (isset($operators[$op])) {
                $operators[$op] += (int) $row->count;
            } else {
                $operators['Other'] += (int) $row->count;
            }
        }

        // 5. Top Active Traffic Senders (Last 24 Hours)
        $topSenders = [];
        if (!$user || in_array($user->role, ['admin', 'superadmin', 'sp', 'ssp'])) {
            $sendersQuery = Sms::query()
                ->select('user_id', DB::raw('count(*) as total_sms'))
                ->where('created_at', '>=', now()->subDay());
            if ($scopedIds !== null) {
                $sendersQuery->whereIn('user_id', $scopedIds);
            }
            $topRows = $sendersQuery->groupBy('user_id')
                ->orderByDesc('total_sms')
                ->limit(5)
                ->with('user:id,name,username,role')
                ->get();

            foreach ($topRows as $row) {
                $u = $row->user;
                $topSenders[] = [
                    'user_id'   => $row->user_id,
                    'username'  => $u->username ?? $u->name ?? "User #{$row->user_id}",
                    'name'      => $u->name ?? 'User',
                    'role'      => strtoupper($u->role ?? 'USER'),
                    'total_sms' => (int) $row->total_sms,
                ];
            }
        }

        // 6. Real-Time Dispatched Feed (Last 15 records)
        $streamQuery = Sms::query()->with([
            'user:id,name,username,role',
            'senderid:id,sender',
            'smsgateway:id,name',
        ]);
        if ($scopedIds !== null) {
            $streamQuery->whereIn('user_id', $scopedIds);
        }
        $recentRecords = $streamQuery->latest('id')->limit(15)->get();

        $liveStream = [];
        foreach ($recentRecords as $sms) {
            $num = (string) $sms->number;
            $maskedNum = strlen($num) > 7
                ? substr($num, 0, 5) . '****' . substr($num, -2)
                : $num;

            $u = $sms->user;
            $liveStream[] = [
                'id'            => $sms->id,
                'time'          => $sms->created_at ? $sms->created_at->format('H:i:s') : '',
                'date'          => $sms->created_at ? $sms->created_at->format('Y-m-d') : '',
                'username'      => $u ? ($u->username ?? $u->name) : 'API/System',
                'user_role'     => $u ? strtoupper($u->role) : 'SYSTEM',
                'recipient'     => $maskedNum,
                'sender'        => $sms->senderid->sender ?? 'N/A',
                'operator'      => $sms->operator ?: 'Other',
                'gateway'       => $sms->smsgateway->name ?? 'Default',
                'status'        => $sms->status,
                'response_code' => $sms->gateway_response_code ?? ($sms->status === 'success' ? '200 (Success)' : '102 (Queued)'),
            ];
        }

        // 7. Active Campaign Progress & Queue ETA
        $campaignsData = [];
        $recentCampaignIds = Sms::query()
            ->whereNotNull('campaign_id')
            ->where('created_at', '>=', now()->subDays(7));
        if ($scopedIds !== null) {
            $recentCampaignIds->whereIn('user_id', $scopedIds);
        }
        $campaignIds = $recentCampaignIds->distinct()->pluck('campaign_id')->take(5)->toArray();

        if (!empty($campaignIds)) {
            $campaignModels = \App\Models\Campaign::whereIn('id', $campaignIds)->get()->keyBy('id');
            foreach ($campaignIds as $cId) {
                $cQuery = Sms::where('campaign_id', $cId);
                if ($scopedIds !== null) {
                    $cQuery->whereIn('user_id', $scopedIds);
                }
                $cTotal = (clone $cQuery)->count();
                $cSuccess = (clone $cQuery)->where('status', 'success')->count();
                $cPending = (clone $cQuery)->where('status', 'pending')->count();
                $cFailed = (clone $cQuery)->where('status', 'failed')->count();
                $cProgress = $cTotal > 0 ? round((($cSuccess + $cFailed) / $cTotal) * 100, 1) : 100.0;

                $etaSeconds = ($currentTps > 0 && $cPending > 0) ? ceil($cPending / $currentTps) : 0;
                $etaHuman = $cPending === 0 ? 'Completed' : ($etaSeconds > 0 ? ($etaSeconds < 60 ? "{$etaSeconds}s" : ceil($etaSeconds / 60) . ' mins') : 'Calculating...');

                $campaignsData[] = [
                    'id'               => $cId,
                    'name'             => $campaignModels[$cId]->name ?? "Campaign #{$cId}",
                    'total'            => $cTotal,
                    'success'          => $cSuccess,
                    'pending'          => $cPending,
                    'failed'           => $cFailed,
                    'progress_percent' => $cProgress,
                    'eta'              => $etaHuman,
                ];
            }
        }

        return [
            'pending_count'        => $pendingCount,
            'processing_count'     => $processingCount,
            'submitted_count'      => $submittedCount,
            'today_submitted'      => $todaySubmitted,
            'today_total'          => $todayTotal,
            'today_success'        => $todaySuccess,
            'today_failed'         => $todayFailed,
            'delivery_rate'        => $deliveryRate,
            'current_tps'          => $currentTps,
            'last_1_min_volume'    => $last1MinCount,
            'last_5_min_volume'    => $last5MinCount,
            'last_1_hour_volume'   => $last1HourCount,
            'operators'            => $operators,
            'top_senders'          => $topSenders,
            'live_stream'          => $liveStream,
            'active_campaigns'     => $campaignsData,
        ];
    }

    /**
     * Get Failed Jobs from Laravel failed_jobs table.
     */
    public function getFailedJobs(int $limit = 10): array
    {
        try {
            $rows = DB::table('failed_jobs')->latest('failed_at')->limit($limit)->get();

            $list = [];
            foreach ($rows as $row) {
                $payload = json_decode($row->payload ?? '{}', true);
                $commandName = $payload['displayName'] ?? ($payload['data']['commandName'] ?? 'Job');

                // Extract recipient number if available in serialized command
                $recipient = null;
                if (!empty($payload['data']['command'])) {
                    if (preg_match('/8801\d{9}/', $payload['data']['command'], $m)) {
                        $recipient = $m[0];
                    }
                }

                $list[] = [
                    'id'         => $row->id,
                    'uuid'       => $row->uuid ?? '',
                    'queue'      => $row->queue ?? 'sms',
                    'job_name'   => class_basename($commandName),
                    'recipient'  => $recipient,
                    'exception'  => \Illuminate\Support\Str::limit($row->exception ?? 'Unknown error', 180),
                    'failed_at'  => $row->failed_at ?? '',
                ];
            }

            return $list;
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Retry a specific failed job.
     */
    public function retryFailedJob(string|int $id): bool
    {
        try {
            Artisan::call('queue:retry', ['id' => [$id]]);
            return true;
        } catch (\Throwable $e) {
            Log::error("Failed to retry job {$id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Retry all failed jobs.
     */
    public function retryAllFailedJobs(): bool
    {
        try {
            Artisan::call('queue:retry', ['id' => ['all']]);
            return true;
        } catch (\Throwable $e) {
            Log::error("Failed to retry all jobs: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Flush all failed jobs.
     */
    public function flushFailedJobs(): bool
    {
        try {
            Artisan::call('queue:flush');
            return true;
        } catch (\Throwable $e) {
            Log::error("Failed to flush jobs: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Purge messages from a RabbitMQ queue.
     */
    public function purgeQueue(string $queueName): bool
    {
        $vhostEncoded = urlencode($this->vhost);
        $res = $this->rabbitApi("queues/{$vhostEncoded}/{$queueName}/contents", 'DELETE');
        return $res !== null;
    }

    /**
     * Format bytes into human readable format.
     */
    protected function formatBytes(int $bytes, int $precision = 2): string
    {
        if ($bytes <= 0) return '0 B';
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $power = floor(log($bytes, 1024));
        return round($bytes / pow(1024, $power), $precision) . ' ' . ($units[$power] ?? 'B');
    }
}
