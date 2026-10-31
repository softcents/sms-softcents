@foreach($groups as $group)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ optional($group->user)->name }}</td>
        <td>{{ optional($group->senderid)->sender }}</td>
        <td>{{ $group->number }}</td>
        <td>{{ Str::limit($group->contents, 150, '...') }}</td>
        <td>{{ formatted_date($group->created_at) }}</td>
        <td>{{ $group->smsgateway->name ?? 'N/A' }}</td>
        <td>
            @if ($group->status == 'pending')
                <div class="badge bg-warning">
                    {{ ucfirst($group->status) }}
                </div>
            @elseif ($group->status == 'success')
                <div class="badge bg-success">
                    {{ ucfirst($group->status) }}
                </div>
            @else
                <div class="badge bg-danger">
                    {{ ucfirst($group->status) }}
                </div>
            @endif
        </td>
    </tr>
@endforeach
