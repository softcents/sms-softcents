@foreach($types as $type)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $type->name }}</td>

        <td class="text-center w-150">
            <div class="badge bg-{{ $type->status == 1 ? 'success' : 'danger' }}">
                {{ $type->status == 1 ? 'Active' : 'Deactive' }}
            </div>
        </td>
        <td>{{ formatted_date($type->created_at) }}</td>

        <td>
            <div class="dropdown table-action">
                <button type="button" data-bs-toggle="dropdown">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    @can('gateways-types-update')
                    <li>
                        <a href="{{ route('admin.gateways-types.edit', $type->id) }}">
                            <i class="fal fa-pencil-alt"></i>
                            {{ __('Edit') }}
                        </a>
                    </li>
                    @endcan
                    @can('gateways-types-delete')
                    <li>
                        <a href="{{ route('admin.gateways-types.destroy', $type->id) }}" class="confirm-action" data-method="DELETE">
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
