@foreach ($services as $service)
<tr>
    @can('services-delete')
        <td class="checkbox text-start">
            <input type="checkbox" name="ids[]" class="checkbox-item" value="{{ $service->id }}" data-url="{{ route('admin.services.delete-all') }}">
        </td>
    @endcan
    <td>{{ $loop->index + 1 }}</td>
    <td>
        <img class="table-img" src="{{ asset($service->image) }}" alt="img">
    </td>
    <td>{{ $service->title }}</td>
    <td>{{ Str::limit($service->description, 200, '...') }}</td>
    <td class="text-center w-150">
        @can('services-update')
            <label class="switch">
                <input type="checkbox" @checked($service->status) class="status" data-url="{{ route('admin.services.status', $service->id) }}">
                <span class="slider round"></span>
            </label>
        @else
            <div class="badge bg-{{ $service->status == 0 ? 'success' : 'danger' }}">
                {{ $service->status == 0 ? 'Active' : 'Deactive' }}
            </div>
        @endcan
    </td>
    <td class="print-d-none">
        <div class="dropdown table-action">
            <button type="button" data-bs-toggle="dropdown">
                <i class="far fa-ellipsis-v"></i>
            </button>
            <ul class="dropdown-menu">
                @can('services-update')
                <li>
                    <a href="{{ route('admin.services.edit', $service->id) }}">
                        <i class="fal fa-pencil-alt"></i>
                        {{ __('Edit') }}
                    </a>
                </li>
                @endcan
                @can('services-delete')
                <li>
                    <a href="{{ route('admin.services.destroy', $service->id) }}" class="confirm-action" data-method="DELETE">
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
