@forelse($subscribers as $subscriber)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td><span class="font-monospace text-dark">{{ $subscriber->invoice_no }}</span></td>
        <td>
            @if($subscriber->user)
                <a href="{{ route('admin.users.show', $subscriber->user->id) }}" class="text-decoration-none" title="{{ __('View Customer Details') }}">
                    <div class="fw-bold text-dark hover-primary">{{ $subscriber->user->name }}</div>
                    <div class="text-primary font-monospace small"><i class="fas fa-at text-muted me-0"></i><span>{{ $subscriber->user->username ?: ('user' . $subscriber->user->id) }}</span></div>
                </a>
                @if(!empty($subscriber->user->phone))
                    <small class="text-muted"><i class="fas fa-phone-alt me-1"></i>{{ $subscriber->user->phone }}</small>
                @endif
            @else
                <span class="text-muted">{{ __('User Deleted') }}</span>
            @endif
        </td>
        <td><span class="fw-semibold text-primary">{{ optional($subscriber->plan)->title ?? 'N/A' }}</span></td>
        <td>{{ $subscriber->gateway->name ?? 'N/A' }}</td>
        <td class="fw-bold text-dark">{{ $subscriber->price }}</td>
        <td class="fw-bold text-dark">{{ $subscriber->total_sms }}</td>
        <td>{{ $subscriber->will_expire }}</td>
        <td>{{ ($subscriber->created_at)->format('d M Y') }}</td>
        <td>
            @if ($subscriber->status == 'pending')
                <div class="badge bg-warning">
                    {{ ucfirst($subscriber->status) }}
                </div>
            @elseif ($subscriber->status == 'approved')
                <div class="badge bg-success">
                    {{ ucfirst($subscriber->status) }}
                </div>
            @else
                <div class="badge bg-danger">
                    {{ ucfirst($subscriber->status) }}
                </div>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="10" class="text-center text-muted py-4">
            <i class="fas fa-calendar-alt fa-2x mb-2 text-secondary opacity-50 d-block"></i>
            {{ __('No subscriptions found.') }}
        </td>
    </tr>
@endforelse
