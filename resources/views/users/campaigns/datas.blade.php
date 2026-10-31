@foreach($campaigns as $campaign)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ optional($campaign->campaign)->name }}</td>
        <td>{{ count(explode(',', $campaign->numbers)) }}</td>
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
        <td>
            @if ($campaign->status == 'pending')
                <a class="btn btn-primary btn-sm text-light confirm-action" href="{{ route('users.campaigns.show', $campaign->id) }}" data-method="GET" data-title="Do you want to send again?">{{ __('Send Again') }} <i class="fas fa-paper-plane"></i></a>
            @else
                {{ $campaign->notes }}
            @endif
        </td>
        <td>
            @if ($campaign->status == 'pending')
            <div class="dropdown table-action">
                <button type="button" data-bs-toggle="dropdown">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{ route('users.campaigns.edit', $campaign->id) }}">
                            <i class="fal fa-pencil-alt"></i>
                            {{ __('Edit') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('users.campaigns.destroy', $campaign->id) }}" class="confirm-action" data-method="DELETE">
                            <i class="fal fa-trash-alt"></i>
                            {{ __('Delete') }}
                        </a>
                    </li>
                </ul>
            </div>
            @else
            <b>-</b>
            @endif
        </td>
    </tr>
@endforeach
