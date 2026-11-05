@forelse($messages as $message)
    @php
        $user = $message->user;
        $operator = $message->operator ?: \App\Services\ReportAnalyticsService::detectOperator($message->number ?? '');
        $charge = (float)($message->charge ?? 0);
        $totalSms = max(1, (int)($message->total_sms ?? 1));
        $tariff = $totalSms > 0 ? ($charge / $totalSms) : $charge;
        $userBalance = (float)($user->balance ?? 0);
        $prevBalance = $userBalance + $charge;
        $nextBalance = $userBalance;
        $requestId = $message->request_id ?: ('REQ-' . str_pad($message->id, 7, '0', STR_PAD_LEFT));
        $serverTxnId = 'TXN-' . str_pad($message->id, 8, '0', STR_PAD_LEFT);
        $gatewayTxnId = $message->mno_txnid ?: ($message->message_id ?: ($message->gateway_txnid ?: ('GW-' . str_pad($message->id, 8, '0', STR_PAD_LEFT))));
        $gatewayResponseCode = $message->gateway_response_code ?: ($message->status == 'success' ? '200 (Success)' : ($message->status == 'pending' ? '102 (Queued)' : '400 (Failed)'));
    @endphp
    <tr>
        <td>{{ $loop->iteration }}</td>

        {{-- 1. User Name --}}
        <td>
            <div class="d-flex flex-column">
                @if($user)
                    <a href="{{ route('admin.users.show', $user->id) }}" class="text-decoration-none" title="{{ __('View Customer Details') }}">
                        <span class="fw-semibold text-dark hover-primary">{{ $user->name }}</span>
                        @if($user->username)
                            <br><small class="text-primary font-monospace">@<span>{{ $user->username }}</span></small>
                        @endif
                    </a>
                @else
                    <span class="fw-semibold text-muted">N/A</span>
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
            {!! operator_badge($operator) !!}
        </td>

        {{-- 13. Channel --}}
        <td>
            @php
                $gw = $message->smsgateway ?: optional($message->senderid)->gateway;
                $isMask = (stripos(optional($message->senderid)->type ?? '', 'masking') !== false && stripos(optional($message->senderid)->type ?? '', 'non') === false);
                $gwRate = $gw ? ($isMask ? ($gw->masking_rate ?: 0.45) : ($gw->non_masking_rate ?: 0.24)) : ($isMask ? 0.45 : 0.24);
            @endphp
            <div class="d-flex flex-column">
                <span class="fw-semibold">{{ $gw->name ?? 'Direct Gateway' }}</span>
                <small class="text-muted" title="{{ __('Gateway Purchase Rate') }}">
                    <i class="fas fa-shopping-cart fa-xs me-1 text-primary"></i>৳{{ number_format((float)$gwRate, 4) }}
                </small>
            </div>
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
            @if(strtolower($message->source ?? '') === 'api')
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
            <span class="font-monospace small">{{ $requestId }}</span>
        </td>

        {{-- 20. Server TxnId --}}
        <td>
            <span class="font-monospace small text-muted">{{ $serverTxnId }}</span>
        </td>

        {{-- 21. Gateway Response Code --}}
        <td>
            @if(str_contains($gatewayResponseCode, 'Success') || str_contains($gatewayResponseCode, '200') || str_contains($gatewayResponseCode, '1000'))
                <span class="badge bg-success">{{ $gatewayResponseCode }}</span>
            @elseif(str_contains($gatewayResponseCode, 'Queued') || str_contains($gatewayResponseCode, '102') || str_contains($gatewayResponseCode, 'Processing'))
                <span class="badge bg-warning text-dark">{{ $gatewayResponseCode }}</span>
            @else
                <span class="badge bg-danger">{{ $gatewayResponseCode }}</span>
            @endif
        </td>

        {{-- 22. Infozillion Response --}}
        <td>
            @php
                $infozResp = $message->infozillion_response;
                if (!$infozResp) {
                    $infozResp = ($message->status == 'success') ? '9000 (Accepted by Central Platform)' : (($message->status == 'pending') ? 'Processing' : 'Failed / Gateway Error');
                }
            @endphp
            <span class="small {{ (str_contains($infozResp, 'Accepted') || str_contains($infozResp, '9000') || str_contains(strtolower($infozResp), 'success')) ? 'text-success fw-semibold' : 'text-danger' }}" title="{{ $infozResp }}">
                {{ Str::limit($infozResp, 35) }}
            </span>
        </td>

        {{-- 23. Gateway TxnId --}}
        <td>
            <span class="font-monospace small text-dark fw-semibold">{{ $gatewayTxnId }}</span>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="24" class="text-center py-4 text-muted">
            {{ __('No records found') }}
        </td>
    </tr>
@endforelse
