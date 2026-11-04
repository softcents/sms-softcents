@foreach($groups as $group)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ optional($group->senderid)->sender }}</td>
        <td>{{ $group->number }}</td>
        <td>{{ Str::limit($group->contents, 25, '...') }}</td>
        <td class="text-nowrap">
            <span class="fw-semibold text-dark">{{ $group->created_at ? $group->created_at->format('d M, Y') : '-' }}</span>
            <br>
            <small class="text-muted"><i class="far fa-clock me-1"></i>{{ $group->created_at ? $group->created_at->format('h:i:s A') : '' }}</small>
        </td>
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
