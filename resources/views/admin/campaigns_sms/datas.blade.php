@foreach($campaign_sms as $campaign)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ optional($campaign->user)->name }}</td>
        <td>{{ optional($campaign->campaign)->name }}</td>
        <td>{{ optional($campaign->senderid)->sender }}</td>
        <td><a data-numbers="{{ $campaign->numbers }}" class="badge btn-custom-warning view-numbers" href="#"><i class="fa fa-eye" aria-hidden="true"></i> {{ __('View Numbers') }}</a></td>
        <td>{{ Str::limit($campaign->contents, 25, '...') }}</td>
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
            <div class="dropdown table-action">
                <button type="button" data-bs-toggle="dropdown">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    <li>

                        <a href="javascript:void(0)" class="approve-reject" data-url="{{ route('admin.campaigns_sms.store', ['id' => $campaign->id, 'status' => 'success']) }}">
                            <i class="fas fa-check"></i>
                            {{ __('Approve') }}
                        </a>
                        <a href="javascript:void(0)" class="approve-reject" data-url="{{ route('admin.campaigns_sms.store', ['id' => $campaign->id, 'status' => 'canceled']) }}">
                            <i class="far fa-window-close"></i>
                            {{ __('Cancel') }}
                        </a>
                    </li>
                </ul>
            </div>
            @else
                <span class="text-dark">-</span>
            @endif
        </td>
    </tr>
@endforeach
