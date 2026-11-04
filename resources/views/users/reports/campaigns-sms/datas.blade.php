@php
    $runningBalance = (float)auth()->user()->balance;
@endphp
@foreach($campaigns as $campaign)
    @php
        $charge = (float)($campaign->charge ?? 0);
        $totalSms = max(1, (int)($campaign->total_sms ?? 1));
        $content = $campaign->contents ?? '';
        $charCount = mb_strlen($content, 'UTF-8');
        $isUnicode = $campaign->is_unicode || didITypeUnicode($content);
        $charTypeDesc = $charCount . ' Chars (' . ($isUnicode ? 'বাংলা / Unicode' : 'Text / English') . ')';

        $newBal = $runningBalance;
        $oldBal = $runningBalance + $charge;
        $runningBalance = $oldBal;

        $oldBalFmt = '৳' . number_format($oldBal, 2);
        $newBalFmt = '৳' . number_format($newBal, 2);
        $chargeFmt = '৳' . number_format($charge, 2);
    @endphp
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ optional($campaign->campaign)->name }}</td>
        <td>{{ optional($campaign->senderid)->sender ?? 'System' }}</td>
        <td>{{ $campaign->number }}</td>
        <td>
            <span class="badge bg-light text-dark border fw-semibold">
                {{ $totalSms }} {{ __('SMS') }}
            </span>
        </td>
        <td>
            <span class="fw-semibold text-danger">
                {{ $chargeFmt }}
            </span>
        </td>
        <td>
            <div class="d-flex align-items-center gap-2">
                <template class="sms-content-template">{{ $content }}</template>
                <span class="view-full-sms-content text-primary" role="button"
                    data-sender="{{ optional($campaign->senderid)->sender ?? 'System' }}"
                    data-number="{{ $campaign->number }}"
                    data-time="{{ $campaign->created_at ? $campaign->created_at->format('d M, Y h:i:s A') : '-' }}"
                    data-parts="{{ $totalSms }} {{ __('SMS') }}"
                    data-charge="{{ $chargeFmt }}"
                    data-old-balance="{{ $oldBalFmt }}"
                    data-new-balance="{{ $newBalFmt }}"
                    data-chars-type="{{ $charTypeDesc }}"
                    title="{{ __('Click to view details') }}"
                    style="cursor: pointer; text-decoration: underline;">
                    {{ Str::limit($content, 30, '...') }}
                </span>
                <button type="button" class="btn btn-sm btn-outline-primary py-0 px-1 view-full-sms-content"
                    data-sender="{{ optional($campaign->senderid)->sender ?? 'System' }}"
                    data-number="{{ $campaign->number }}"
                    data-time="{{ $campaign->created_at ? $campaign->created_at->format('d M, Y h:i:s A') : '-' }}"
                    data-parts="{{ $totalSms }} {{ __('SMS') }}"
                    data-charge="{{ $chargeFmt }}"
                    data-old-balance="{{ $oldBalFmt }}"
                    data-new-balance="{{ $newBalFmt }}"
                    data-chars-type="{{ $charTypeDesc }}"
                    title="{{ __('View details') }}"
                    style="font-size: 11px;">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </td>
        <td class="text-nowrap">
            <span class="fw-semibold text-dark">{{ $campaign->created_at ? $campaign->created_at->format('d M, Y') : '-' }}</span>
            <br>
            <small class="text-muted"><i class="far fa-clock me-1"></i>{{ $campaign->created_at ? $campaign->created_at->format('h:i:s A') : '' }}</small>
        </td>
        <td>
            @if ($campaign->status == 'pending')
                <div class="badge bg-warning">
                    {{ ucfirst($campaign->status) }}
                </div>
            @elseif ($campaign->status == 'success')
                <div class="badge bg-success">
                    {{ ucfirst($campaign->status) }}
                </div>
            @else
                <div class="badge bg-danger">
                    {{ ucfirst($campaign->status) }}
                </div>
            @endif
        </td>
    </tr>
@endforeach
