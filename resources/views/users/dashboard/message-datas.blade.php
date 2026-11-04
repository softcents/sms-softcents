@forelse($messages as $message)
    <tr>
        <td>{{ $loop->index + 1 }}</td>
        <td>{{ optional($message->senderid)->sender ?? 'System' }}</td>
        <td>{{ $message->number }}</td>
        <td>
            @if(strtolower($message->source ?? 'web') === 'api')
                <span class="badge bg-info text-dark fw-semibold" style="font-size: 11px; padding: 4px 8px; border-radius: 4px;">
                    <i class="fas fa-code me-1"></i>{{ __('API') }}
                </span>
            @else
                <span class="badge bg-primary text-white fw-semibold" style="font-size: 11px; padding: 4px 8px; border-radius: 4px;">
                    <i class="fas fa-globe me-1"></i>{{ __('Web') }}
                </span>
            @endif
        </td>
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
@empty
    <tr>
        <td colspan="7" class="text-center text-muted py-3">
            {{ __('No recent messages found') }}
        </td>
    </tr>
@endforelse
