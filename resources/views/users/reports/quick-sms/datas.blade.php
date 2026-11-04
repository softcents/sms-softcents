@foreach($messages as $message)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ optional($message->senderid)->sender }}</td>
        <td>{{ $message->number }}</td>
        <td>{{ Str::limit($message->contents, 25, '...') }}</td>
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
