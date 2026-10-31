@foreach($campaigns as $campaign)
    <tr>
        @can('campaigns-delete')
        <td class="checkbox text-start">
            <input type="checkbox" name="ids[]" class="checkbox-item" value="{{ $campaign->id }}" data-url="{{ route('admin.campaigns.delete-all') }}">
        </td>
        @endcan
        <td>{{ $loop->iteration }}</td>
        <td>{{ $campaign->name }}</td>

        <td><a data-numbers="{{ $campaign->numbers }}" class="badge btn-custom-warning view-numbers" href="#"><i class="fa fa-eye" aria-hidden="true"></i> {{ __('View Numbers') }}</a></td>

        <td class="fw-bold text-dark">{{ $campaign->total_numbers }}</td>

        <td>{{ $campaign->description }}</td>

        <td class="text-center w-150">
            @can('campaigns-update')
                <label class="switch">
                    <input type="checkbox" {{ $campaign->status == 1 ? 'checked' : '' }} class="status" data-url="{{ route('admin.campaigns.status', $campaign->id)}}">
                    <span class="slider round"></span>
                </label>
            @else
                <div class="badge bg-{{ $campaign->status == 1 ? 'success' : 'danger' }}">
                    {{ $campaign->status == 1 ? 'Active' : 'Deactive' }}
                </div>
            @endcan
        </td>

        <td>
            <div class="dropdown table-action">
                <button type="button" data-bs-toggle="dropdown">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    @can('campaigns-update')
                    <li>
                        <a href="{{ route('admin.campaigns.edit', $campaign->id) }}">
                            <i class="fal fa-pencil-alt"></i>
                            {{ __('Edit') }}
                        </a>
                    </li>
                    @endcan
                    @can('campaigns-delete')
                    <li>
                        <a href="{{ route('admin.campaigns.destroy', $campaign->id) }}" class="confirm-action" data-method="DELETE">
                            <i class="fal fa-trash-alt"></i>
                            {{ __('Delete') }}
                        </a>
                    </li>
                    @endcan
                </ul>
            </div>
        </td>
    </tr>
@endforeach
