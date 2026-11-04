@foreach($messages as $message)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ optional($message->senderid)->sender ?? 'System' }}</td>
        <td>{{ $message->number }}</td>
        <td>
            <div class="d-flex align-items-center gap-2">
                <span class="view-full-sms-content text-primary" role="button"
                    data-content="{{ $message->contents }}"
                    data-sender="{{ optional($message->senderid)->sender ?? 'System' }}"
                    data-number="{{ $message->number }}"
                    data-time="{{ $message->created_at ? $message->created_at->format('d M, Y h:i:s A') : '-' }}"
                    data-parts="{{ $message->total_sms ?? 1 }}"
                    data-charge="{{ currency_format($message->charge) }}"
                    data-unicode="{{ $message->is_unicode ? 1 : 0 }}"
                    title="{{ __('Click to view full content') }}"
                    style="cursor: pointer; text-decoration: underline;">
                    {{ Str::limit($message->contents, 30, '...') }}
                </span>
                <button type="button" class="btn btn-sm btn-outline-primary py-0 px-1 view-full-sms-content"
                    data-content="{{ $message->contents }}"
                    data-sender="{{ optional($message->senderid)->sender ?? 'System' }}"
                    data-number="{{ $message->number }}"
                    data-time="{{ $message->created_at ? $message->created_at->format('d M, Y h:i:s A') : '-' }}"
                    data-parts="{{ $message->total_sms ?? 1 }}"
                    data-charge="{{ currency_format($message->charge) }}"
                    data-unicode="{{ $message->is_unicode ? 1 : 0 }}"
                    title="{{ __('View full message') }}"
                    style="font-size: 11px;">
                    <i class="far fa-eye"></i>
                </button>
            </div>
        </td>
        <td class="text-center">
            <span class="badge bg-secondary">{{ $message->total_sms ?? 1 }} {{ __('SMS') }}</span>
        </td>
        <td class="fw-semibold text-danger">
            {{ currency_format($message->charge) }}
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
