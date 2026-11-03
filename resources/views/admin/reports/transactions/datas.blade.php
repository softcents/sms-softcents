@forelse($transactions as $transaction)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>
            @if($transaction->user)
                <div class="fw-bold text-dark">{{ $transaction->user->name }}</div>
                <div class="text-primary font-monospace small"><i class="fas fa-at text-muted me-0"></i><span>{{ $transaction->user->username ?: ('user' . $transaction->user->id) }}</span></div>
                @if(!empty($transaction->user->phone))
                    <small class="text-muted"><i class="fas fa-phone-alt me-1"></i>{{ $transaction->user->phone }}</small>
                @endif
            @else
                <span class="text-muted">{{ __('User Deleted') }}</span>
            @endif
        </td>
        <td><span class="font-monospace text-dark">{{ $transaction->invoice_no }}</span></td>
        <td>{{ $transaction->gateway->name ?? 'N/A' }}</td>
        <td>{{ $transaction->reason }}</td>
        <td>{{ ($transaction->created_at)->format('d M Y') }}</td>
        <td class="fw-bold text-dark">{{ currency_format($transaction->amount) }}</td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center text-muted py-4">
            <i class="fas fa-exchange-alt fa-2x mb-2 text-secondary opacity-50 d-block"></i>
            {{ __('No transactions found.') }}
        </td>
    </tr>
@endforelse
