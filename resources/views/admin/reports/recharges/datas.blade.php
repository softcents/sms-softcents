@forelse($recharges as $recharge)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>
            @if($recharge->user)
                <a href="{{ route('admin.users.show', $recharge->user->id) }}" class="text-decoration-none" title="{{ __('View User Details') }}">
                    <div class="fw-bold text-dark hover-primary">{{ $recharge->user->name }}</div>
                    <div class="text-primary font-monospace small"><i class="fas fa-at text-muted me-0"></i><span>{{ $recharge->user->username ?: ('user' . $recharge->user->id) }}</span></div>
                </a>
                @if(!empty($recharge->user->phone))
                    <small class="text-muted"><i class="fas fa-phone-alt me-1"></i>{{ $recharge->user->phone }}</small>
                @endif
            @else
                <span class="text-muted">{{ __('User Deleted') }}</span>
            @endif
        </td>
        <td><span class="font-monospace text-dark">{{ $recharge->invoice_no }}</span></td>
        <td>{{ $recharge->gateway->name ?? 'N/A' }}</td>
        <td>{{ ($recharge->created_at)->format('d M Y') }}</td>
        <td class="fw-bold text-dark">{{ currency_format($recharge->amount) }}</td>
        <td>
            @if ($recharge->status == 'pending')
                <div class="badge bg-warning">
                    {{ ucfirst($recharge->status) }}
                </div>
            @elseif ($recharge->status == 'approved')
                <div class="badge bg-success">
                    {{ ucfirst($recharge->status) }}
                </div>
            @else
                <div class="badge bg-danger">
                    {{ ucfirst($recharge->status) }}
                </div>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center text-muted py-4">
            <i class="fas fa-wallet fa-2x mb-2 text-secondary opacity-50 d-block"></i>
            {{ __('No recharges found.') }}
        </td>
    </tr>
@endforelse
