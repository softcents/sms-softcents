@forelse($campaign_sms as $campaign)
    @php
        $user = $campaign->user;
        $operator = $campaign->operator ?: \App\Services\ReportAnalyticsService::detectOperator($campaign->number ?? '');
        $charge = (float)($campaign->charge ?? 0);
        $totalSms = max(1, (int)($campaign->total_sms ?? 1));
        $tariff = $totalSms > 0 ? ($charge / $totalSms) : $charge;
        $userBalance = (float)($user->balance ?? 0);
        $prevBalance = $userBalance + $charge;
        $nextBalance = $userBalance;
        $requestId = $campaign->request_id ?: ('REQ-' . str_pad($campaign->id, 7, '0', STR_PAD_LEFT));
        $serverTxnId = 'TXN-' . str_pad($campaign->id, 8, '0', STR_PAD_LEFT);
        $gatewayTxnId = $campaign->mno_txnid ?: ($campaign->message_id ?: ($campaign->gateway_txnid ?: ('GW-' . str_pad($campaign->id, 8, '0', STR_PAD_LEFT))));
        $gatewayResponseCode = $campaign->gateway_response_code ?: ($campaign->status == 'success' ? '200 (Success)' : ($campaign->status == 'pending' ? '102 (Queued)' : '400 (Failed)'));
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

        {{-- 2. Campaign --}}
        <td>
            <span class="badge bg-light text-primary border">{{ optional($campaign->campaign)->name ?? ('Campaign #' . ($campaign->campaign_id ?? 'N/A')) }}</span>
        </td>

        {{-- 3. Recipient Number --}}
        <td>
            <span class="font-monospace fw-semibold">{{ $campaign->number }}</span>
        </td>

        {{-- 4. Content --}}
        <td>
            <div class="d-flex align-items-center gap-2">
                <span title="{{ $campaign->contents }}">{{ Str::limit($campaign->contents, 28) }}</span>
                @if(mb_strlen($campaign->contents ?? '') > 28)
                    <button type="button" class="btn btn-sm btn-outline-primary py-0 px-1 view-full-sms-content" data-content="{{ $campaign->contents }}" title="{{ __('View full message') }}" style="font-size: 11px;">
                        <i class="fal fa-eye"></i>
                    </button>
                @endif
            </div>
        </td>

        {{-- 5. Caller ID --}}
        <td>
            <span class="badge bg-light text-dark border">{{ optional($campaign->senderid)->sender ?? 'System' }}</span>
        </td>

        {{-- 6. SMS Type --}}
        <td>
            <span class="badge bg-light text-secondary border">
                {{ optional($campaign->senderid)->type ?? ($campaign->is_unicode ? 'Unicode' : 'Non-Masking') }}
            </span>
        </td>

        {{-- 7. SMS Count --}}
        <td class="text-center">
            <span class="badge bg-secondary">{{ $campaign->total_sms ?? 1 }}</span>
        </td>

        {{-- 8. Cost --}}
        <td class="fw-bold text-danger">
            {{ currency_format($charge) }}
        </td>

        {{-- 9. Previous Balance --}}
        <td class="text-secondary">
            {{ currency_format($prevBalance) }}
        </td>

        {{-- 10. Next Balance --}}
        <td class="fw-semibold text-success">
            {{ currency_format($nextBalance) }}
        </td>

        {{-- 11. Status --}}
        <td>
            @if ($campaign->status == 'pending')
                <span class="badge bg-warning text-dark">{{ ucfirst($campaign->status) }}</span>
            @elseif ($campaign->status == 'success')
                <span class="badge bg-success">{{ ucfirst($campaign->status) }}</span>
            @else
                <span class="badge bg-danger">{{ ucfirst($campaign->status) }}</span>
            @endif
        </td>

        {{-- 12. Failure Reason --}}
        <td>
            @if($campaign->notes)
                <span class="text-danger small" title="{{ $campaign->notes }}">{{ Str::limit($campaign->notes, 20) }}</span>
            @elseif($campaign->status == 'canceled')
                <span class="text-danger small">{{ __('Failed / Gateway Error') }}</span>
            @else
                <span class="text-muted">-</span>
            @endif
        </td>

        {{-- 13. Operator --}}
        <td>
            {!! operator_badge($operator) !!}
        </td>

        {{-- 14. Channel --}}
        <td>
            @php
                $gw = $campaign->smsgateway ?: optional($campaign->senderid)->gateway;
                $isMask = (stripos(optional($campaign->senderid)->type ?? '', 'masking') !== false && stripos(optional($campaign->senderid)->type ?? '', 'non') === false);
                $gwRate = $gw ? ($isMask ? ($gw->masking_rate ?: 0.45) : ($gw->non_masking_rate ?: 0.24)) : ($isMask ? 0.45 : 0.24);
            @endphp
            <div class="d-flex flex-column">
                <span class="fw-semibold">{{ $gw->name ?? 'Direct Gateway' }}</span>
                <small class="text-muted" title="{{ __('Gateway Purchase Rate') }}">
                    <i class="fas fa-shopping-cart fa-xs me-1 text-primary"></i>৳{{ number_format((float)$gwRate, 4) }}
                </small>
            </div>
        </td>

        {{-- 15. Submit Time --}}
        <td class="text-nowrap small">
            {{ $campaign->created_at ? $campaign->created_at->format('Y-m-d H:i:s') : 'N/A' }}
        </td>

        {{-- 16. Delivery Time --}}
        <td class="text-nowrap small">
            @if($campaign->status == 'success')
                {{ $campaign->updated_at ? $campaign->updated_at->format('Y-m-d H:i:s') : ($campaign->created_at ? $campaign->created_at->format('Y-m-d H:i:s') : 'N/A') }}
            @else
                <span class="text-muted">-</span>
            @endif
        </td>

        {{-- 17. Tariff --}}
        <td class="small">
            {{ currency_format($tariff) }}
        </td>

        {{-- 18. API User --}}
        <td>
            @if($user && $user->allow_api)
                <span class="badge bg-info text-dark">API ({{ $user->username ?? 'User' }})</span>
            @else
                <span class="badge bg-light text-dark border">Web Portal</span>
            @endif
        </td>

        {{-- 19. Source IP --}}
        <td>
            <code class="text-muted small">{{ $campaign->ip_address ?? '127.0.0.1' }}</code>
        </td>

        {{-- 20. Request ID --}}
        <td>
            <span class="font-monospace small">{{ $requestId }}</span>
        </td>

        {{-- 21. Server TxnId --}}
        <td>
            <span class="font-monospace small text-muted">{{ $serverTxnId }}</span>
        </td>

        {{-- 22. Gateway Response Code --}}
        <td>
            @if(str_contains($gatewayResponseCode, 'Success') || str_contains($gatewayResponseCode, '200') || str_contains($gatewayResponseCode, '1000'))
                <span class="badge bg-success">{{ $gatewayResponseCode }}</span>
            @elseif(str_contains($gatewayResponseCode, 'Queued') || str_contains($gatewayResponseCode, '102') || str_contains($gatewayResponseCode, 'Processing'))
                <span class="badge bg-warning text-dark">{{ $gatewayResponseCode }}</span>
            @else
                <span class="badge bg-danger">{{ $gatewayResponseCode }}</span>
            @endif
        </td>

        {{-- 23. Infozillion Response --}}
        <td>
            @php
                $infozResp = $campaign->infozillion_response;
                if (!$infozResp) {
                    $infozResp = ($campaign->status == 'success') ? '9000 (Accepted by Central Platform)' : (($campaign->status == 'pending') ? 'Processing' : 'Failed / Gateway Error');
                }
            @endphp
            <span class="small {{ (str_contains($infozResp, 'Accepted') || str_contains($infozResp, '9000') || str_contains(strtolower($infozResp), 'success')) ? 'text-success fw-semibold' : 'text-danger' }}" title="{{ $infozResp }}">
                {{ Str::limit($infozResp, 35) }}
            </span>
        </td>

        {{-- 24. Gateway TxnId --}}
        <td>
            <span class="font-monospace small text-dark fw-semibold">{{ $gatewayTxnId }}</span>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="25" class="text-center py-4 text-muted">
            {{ __('No records found') }}
        </td>
    </tr>
@endforelse
