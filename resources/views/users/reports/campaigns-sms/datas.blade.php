@foreach($campaigns as $campaign)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ optional($campaign->campaign)->name }}</td>
        <td>{{ optional($campaign->senderid)->sender }}</td>
        <td>{{ $campaign->number }}</td>
        <td>{{ $campaign->contents }}</td>
        <td>{{ formatted_date($campaign->created_at) }}</td>
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
