@forelse($groups as $group)
    @php
        $user = $group->user;
        $operator = $group->operator ?: \App\Services\ReportAnalyticsService::detectOperator($group->number ?? '');
        $charge = (float)($group->charge ?? 0);
        $totalSms = max(1, (int)($group->total_sms ?? 1));
        $tariff = $totalSms > 0 ? ($charge / $totalSms) : $charge;
        $userBalance = (float)($user->balance ?? 0);
        $prevBalance = $userBalance + $charge;
        $nextBalance = $userBalance;
        $requestId = $group->request_id ?: ('REQ-' . str_pad($group->id, 7, '0', STR_PAD_LEFT));
        $serverTxnId = 'TXN-' . str_pad($group->id, 8, '0', STR_PAD_LEFT);
        $gatewayTxnId = $group->mno_txnid ?: ($group->message_id ?: ($group->gateway_txnid ?: ('GW-' . str_pad($group->id, 8, '0', STR_PAD_LEFT))));
        $gatewayResponseCode = $group->gateway_response_code ?: ($group->status == 'success' ? '200 (Success)' : ($group->status == 'pending' ? '102 (Queued)' : '400 (Failed)'));
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

        {{-- 2. Group Name --}}
        <td>
            <span class="badge bg-light text-primary border">{{ optional($group->group)->name ?? ('Group #' . ($group->group_id ?? 'N/A')) }}</span>
        </td>

        {{-- 3. Recipient Number --}}
        <td>
            <span class="font-monospace fw-semibold">{{ $group->number }}</span>
        </td>

        {{-- 4. Content --}}
        <td>
            <div class="d-flex align-items-center gap-2">
                <span title="{{ $group->contents }}">{{ Str::limit($group->contents, 28) }}</span>
                @if(mb_strlen($group->contents ?? '') > 28)
                    <button type="button" class="btn btn-sm btn-outline-primary py-0 px-1 view-full-sms-content" data-content="{{ $group->contents }}" title="{{ __('View full message') }}" style="font-size: 11px;">
                        <i class="fal fa-eye"></i>
                    </button>
                @endif
            </div>
        </td>

        {{-- 5. Caller ID --}}
        <td>
            <span class="badge bg-light text-dark border">{{ optional($group->senderid)->sender ?? 'System' }}</span>
        </td>

        {{-- 6. SMS Type --}}
        <td>
            <span class="badge bg-light text-secondary border">
                {{ optional($group->senderid)->type ?? ($group->is_unicode ? 'Unicode' : 'Non-Masking') }}
            </span>
        </td>

        {{-- 7. SMS Count --}}
        <td class="text-center">
            <span class="badge bg-secondary">{{ $group->total_sms ?? 1 }}</span>
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
            @if ($group->status == 'pending')
                <span class="badge bg-warning text-dark">{{ ucfirst($group->status) }}</span>
            @elseif ($group->status == 'success')
                <span class="badge bg-success">{{ ucfirst($group->status) }}</span>
            @else
                <span class="badge bg-danger">{{ ucfirst($group->status) }}</span>
            @endif
        </td>

        {{-- 12. Failure Reason --}}
        <td>
            @if($group->notes)
                <span class="text-danger small" title="{{ $group->notes }}">{{ Str::limit($group->notes, 20) }}</span>
            @elseif($group->status == 'canceled')
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
                $gw = $group->smsgateway ?: optional($group->senderid)->gateway;
                $isMask = (stripos(optional($group->senderid)->type ?? '', 'masking') !== false && stripos(optional($group->senderid)->type ?? '', 'non') === false);
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
            {{ $group->created_at ? $group->created_at->format('Y-m-d H:i:s') : 'N/A' }}
        </td>

        {{-- 16. Delivery Time --}}
        <td class="text-nowrap small">
            @if($group->status == 'success')
                {{ $group->updated_at ? $group->updated_at->format('Y-m-d H:i:s') : ($group->created_at ? $group->created_at->format('Y-m-d H:i:s') : 'N/A') }}
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
            <code class="text-muted small">{{ $group->ip_address ?? '127.0.0.1' }}</code>
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
                $infozResp = $group->infozillion_response;
                if (!$infozResp) {
                    $infozResp = ($group->status == 'success') ? '9000 (Accepted by Central Platform)' : (($group->status == 'pending') ? 'Processing' : 'Failed / Gateway Error');
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
