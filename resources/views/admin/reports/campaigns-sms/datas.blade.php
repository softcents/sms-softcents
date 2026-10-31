@foreach($campaign_sms as $campaign)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ optional($campaign->user)->name }}</td>
        <td>{{ optional($campaign->campaign)->name }}</td>
        <td>{{ optional($campaign->senderid)->sender }}</td>
        <td>{{ $campaign->number }}</td>
        <td class="fw-bold text-dark">{{ currency_format($campaign->charge) }}</td>
        <td>{{ Str::limit($campaign->contents, 25, '...') }}</td>
        <td>{{ formatted_date($campaign->created_at) }}</td>
        <td>{{ $campaign->smsgateway->name ?? 'N/A' }}</td>
        <td>
            @if ($campaign->status == 'pending')
                <div class="badge bg-warning">
                    {{ ucfirst($campaign->status) }}
                </div>
            @elseif ($campaign->status == 'success')
                <div class="badge bg-success">
                    {{ ucfirst($campaign->status) }}
                </div>
            @else
                <div class="badge bg-danger">
                    {{ ucfirst($campaign->status) }}
                </div>
            @endif
        </td>
    </tr>
@endforeach
