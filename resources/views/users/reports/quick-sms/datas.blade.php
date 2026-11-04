@php
    $runningBalance = (float)auth()->user()->balance;
@endphp
@foreach($messages as $message)
    @php
        $charge = (float)($message->charge ?? 0);
        $totalSms = max(1, (int)($message->total_sms ?? 1));
        $content = $message->contents ?? '';
        $charCount = mb_strlen($content, 'UTF-8');
        $isUnicode = $message->is_unicode || didITypeUnicode($content);
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
        <td>{{ optional($message->senderid)->sender ?? 'System' }}</td>
        <td>{{ $message->number }}</td>
        <td>
            <div class="d-flex align-items-center gap-2">
                <template class="sms-content-template">{{ $content }}</template>
                <span class="view-full-sms-content text-primary" role="button"
                    data-sender="{{ optional($message->senderid)->sender ?? 'System' }}"
                    data-number="{{ $message->number }}"
                    data-time="{{ $message->created_at ? $message->created_at->format('d M, Y h:i:s A') : '-' }}"
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
                    data-sender="{{ optional($message->senderid)->sender ?? 'System' }}"
                    data-number="{{ $message->number }}"
                    data-time="{{ $message->created_at ? $message->created_at->format('d M, Y h:i:s A') : '-' }}"
                    data-parts="{{ $totalSms }} {{ __('SMS') }}"
                    data-charge="{{ $chargeFmt }}"
                    data-old-balance="{{ $oldBalFmt }}"
                    data-new-balance="{{ $newBalFmt }}"
                    data-chars-type="{{ $charTypeDesc }}"
                    title="{{ __('View details') }}"
                    style="font-size: 11px;">
                    <i class="far fa-eye"></i>
                </button>
            </div>
        </td>
        <td class="text-center">
            <span class="badge bg-secondary">{{ $totalSms }} {{ __('SMS') }}</span>
        </td>
        <td class="fw-semibold text-danger">
            {{ $chargeFmt }}
        </td>
        <td class="text-nowrap">
            <span class="fw-semibold text-dark">{{ $message->created_at ? $message->created_at->format('d M, Y') : '-' }}</span>
            <br>
            <small class="text-muted"><i class="far fa-clock me-1"></i>{{ $message->created_at ? $message->created_at->format('h:i:s A') : '' }}</small>
        </td>
        <td>
            @if ($message->status == 'pending')
                <div class="badge bg-warning">
                    {{ ucfirst($message->status) }}
                </div>
            @elseif ($message->status == 'success')
                <div class="badge bg-success">
                    {{ ucfirst($message->status) }}
                </div>
            @else
                <div class="badge bg-danger">
                    {{ ucfirst($message->status) }}
                </div>
            @endif
        </td>
    </tr>
@endforeach
