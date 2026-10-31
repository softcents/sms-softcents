@foreach($messages as $message)
    <tr>
        <td>{{ $loop->index + 1 }}</td>
        <td>{{ optional($message->senderid)->sender }}</td>
        <td>{{ $message->number }}</td>
        <td>{{ Str::limit($message->contents, 25, '...') }}</td>
        <td>{{ formatted_date($message->created_at) }}</td>
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
