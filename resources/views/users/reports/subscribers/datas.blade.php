@foreach($subscribers as $subscriber)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $subscriber->invoice_no }}</td>
        <td>{{ optional($subscriber->user)->name }}</td>
        <td>{{ optional($subscriber->plan)->title }}</td>
        <td class="fw-bold text-dark">{{ $subscriber->price }}</td>
        <td class="fw-bold text-dark">{{ $subscriber->total_sms }}</td>
        <td class="fw-bold text-dark">{{ $subscriber->transaction_id }}</td>
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
@endforeach
