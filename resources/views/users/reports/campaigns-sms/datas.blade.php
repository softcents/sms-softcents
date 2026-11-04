@foreach($campaigns as $campaign)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ optional($campaign->campaign)->name }}</td>
        <td>{{ optional($campaign->senderid)->sender }}</td>
        <td>{{ $campaign->number }}</td>
        <td>{{ $campaign->contents }}</td>
        <td class="text-nowrap">
            <span class="fw-semibold text-dark">{{ $campaign->created_at ? $campaign->created_at->format('d M, Y') : '-' }}</span>
            <br>
            <small class="text-muted"><i class="far fa-clock me-1"></i>{{ $campaign->created_at ? $campaign->created_at->format('h:i:s A') : '' }}</small>
        </td>
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
