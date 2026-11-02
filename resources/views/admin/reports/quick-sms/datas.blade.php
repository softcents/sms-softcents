@forelse($messages as $message)
    @php
        $user = $message->user;
        $operator = \App\Services\ReportAnalyticsService::detectOperator($message->number ?? '');
        $charge = (float)($message->charge ?? 0);
        $totalSms = max(1, (int)($message->total_sms ?? 1));
        $tariff = $totalSms > 0 ? ($charge / $totalSms) : $charge;
        $userBalance = (float)($user->balance ?? 0);
        $prevBalance = $userBalance + $charge;
        $nextBalance = $userBalance;
    @endphp
    <tr>
        <td>{{ $loop->iteration }}</td>

        {{-- 1. User Name --}}
        <td>
            <div class="d-flex flex-column">
                <span class="fw-semibold text-dark">{{ $user->name ?? 'N/A' }}</span>
                @if($user && $user->username)
                    <small class="text-muted">@<span>{{ $user->username }}</span></small>
                @endif
            </div>
        </td>

        {{-- 2. Recipient Number --}}
        <td>
            <span class="font-monospace fw-semibold">{{ $message->number }}</span>
        </td>

        {{-- 3. Content --}}
        <td>
            <div class="d-flex align-items-center gap-2">
                <span title="{{ $message->contents }}">{{ Str::limit($message->contents, 28) }}</span>
                @if(mb_strlen($message->contents ?? '') > 28)
                    <button type="button" class="btn btn-sm btn-outline-primary py-0 px-1 view-full-sms-content" data-content="{{ $message->contents }}" title="{{ __('View full message') }}" style="font-size: 11px;">
                        <i class="fal fa-eye"></i>
                    </button>
                @endif
            </div>
        </td>

        {{-- 4. Caller ID --}}
        <td>
            <span class="badge bg-light text-dark border">{{ optional($message->senderid)->sender ?? 'System' }}</span>
        </td>

        {{-- 5. SMS Type --}}
        <td>
            <span class="badge bg-light text-secondary border">
                {{ optional($message->senderid)->type ?? ($message->is_unicode ? 'Unicode' : 'Non-Masking') }}
            </span>
        </td>

        {{-- 6. SMS Count --}}
        <td class="text-center">
            <span class="badge bg-secondary">{{ $message->total_sms ?? 1 }}</span>
        </td>

        {{-- 7. Cost --}}
        <td class="fw-bold text-danger">
            {{ currency_format($charge) }}
        </td>

        {{-- 8. Previous Balance --}}
        <td class="text-secondary">
            {{ currency_format($prevBalance) }}
        </td>

        {{-- 9. Next Balance --}}
        <td class="fw-semibold text-success">
            {{ currency_format($nextBalance) }}
        </td>

        {{-- 10. Status --}}
        <td>
            @if ($message->status == 'pending')
                <span class="badge bg-warning text-dark">{{ ucfirst($message->status) }}</span>
            @elseif ($message->status == 'success')
                <span class="badge bg-success">{{ ucfirst($message->status) }}</span>
            @else
                <span class="badge bg-danger">{{ ucfirst($message->status) }}</span>
            @endif
        </td>

        {{-- 11. Failure Reason --}}
        <td>
            @if($message->notes)
                <span class="text-danger small" title="{{ $message->notes }}">{{ Str::limit($message->notes, 20) }}</span>
            @elseif($message->status == 'canceled')
                <span class="text-danger small">{{ __('Failed / Gateway Error') }}</span>
            @else
                <span class="text-muted">-</span>
            @endif
        </td>

        {{-- 12. Operator --}}
        <td>
            @if($operator == 'GP')
                <span class="badge bg-primary text-white">GP</span>
            @elseif($operator == 'BL')
                <span class="badge bg-warning text-dark">BL</span>
            @elseif($operator == 'Robi')
                <span class="badge bg-danger text-white">Robi</span>
            @elseif($operator == 'Teletalk')
                <span class="badge bg-success text-white">Teletalk</span>
            @else
                <span class="badge bg-secondary text-white">{{ $operator }}</span>
            @endif
        </td>

        {{-- 13. Channel --}}
        <td>
            <span class="fw-semibold">{{ optional($message->smsgateway)->name ?? 'Direct Gateway' }}</span>
        </td>

        {{-- 14. Submit Time --}}
        <td class="text-nowrap small">
            {{ $message->created_at ? $message->created_at->format('Y-m-d H:i:s') : 'N/A' }}
        </td>

        {{-- 15. Delivery Time --}}
        <td class="text-nowrap small">
            @if($message->status == 'success')
                {{ $message->updated_at ? $message->updated_at->format('Y-m-d H:i:s') : ($message->created_at ? $message->created_at->format('Y-m-d H:i:s') : 'N/A') }}
            @else
                <span class="text-muted">-</span>
            @endif
        </td>

        {{-- 16. Tariff --}}
        <td class="small">
            {{ currency_format($tariff) }}
        </td>

        {{-- 17. API User --}}
        <td>
            @if($user && $user->allow_api)
                <span class="badge bg-info text-dark">API ({{ $user->username ?? 'User' }})</span>
            @else
                <span class="badge bg-light text-dark border">Web Portal</span>
            @endif
        </td>

        {{-- 18. Source IP --}}
        <td>
            <code class="text-muted small">{{ $message->ip_address ?? '127.0.0.1' }}</code>
        </td>

        {{-- 19. Request ID --}}
        <td>
            <span class="font-monospace small">REQ-{{ str_pad($message->id, 7, '0', STR_PAD_LEFT) }}</span>
        </td>

        {{-- 20. Server TxnId --}}
        <td>
            <span class="font-monospace small">TXN-{{ str_pad($message->id, 8, '0', STR_PAD_LEFT) }}</span>
        </td>

        {{-- 21. ServerResponseCode --}}
        <td>
            @if($message->status == 'success')
                <span class="badge bg-success">200 (Success)</span>
            @elseif($message->status == 'pending')
                <span class="badge bg-warning text-dark">102 (Queued)</span>
            @else
                <span class="badge bg-danger">400 (Failed)</span>
            @endif
        </td>

        {{-- 22. MNO TxnId --}}
        <td>
            <span class="font-monospace small text-muted">{{ $message->message_id ?? ('MNO-' . str_pad($message->id, 8, '0', STR_PAD_LEFT)) }}</span>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="23" class="text-center py-4 text-muted">
            {{ __('No records found') }}
        </td>
    </tr>
@endforelse
