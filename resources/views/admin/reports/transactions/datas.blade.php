@foreach($transactions as $transaction)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ optional($transaction->user)->name }}</td>
        <td>{{ $transaction->invoice_no }}</td>
        <td>{{ $transaction->gateway->name ?? '' }}</td>
        <td>{{ $transaction->reason }}</td>
        <td>{{ ($transaction->created_at)->format('d M Y') }}</td>
        <td class="fw-bold text-dark">{{ currency_format($transaction->amount) }}</td>
    </tr>
@endforeach
