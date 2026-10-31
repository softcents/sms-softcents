@foreach($gateways as $gateway)
    <tr>
        @can('smsgateways-delete')
            <td class="checkbox text-start">
                <input type="checkbox" name="ids[]" class="checkbox-item" value="{{ $gateway->id }}" data-url="{{ route('admin.sms-gateways.delete-all') }}">
            </td>
        @endcan
        <td>{{ $loop->iteration }}</td>
        <td>{{ $gateway->name }}</td>
        <td>{{ $gateway->type->name ?? '' }}</td>
        <td>{{ $gateway->priority }}</td>
        <td class="text-center w-150">
            @can('smsgateways-update')
                <label class="switch">
                    <input type="checkbox" {{ $gateway->status == 1 ? 'checked' : '' }} class="status" data-url="{{ route('admin.sms-gateways.show', $gateway->id) }}" data-method="GET">
                    <span class="slider round"></span>
                </label>
            @else
                <div class="badge bg-{{ $gateway->status == 1 ? 'success' : 'danger' }}">
                    {{ $gateway->status == 1 ? 'Active' : 'Deactive' }}
                </div>
            @endcan
        </td>
        <td>
            <div class="dropdown table-action">
                <button type="button" data-bs-toggle="dropdown">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    @can('smsgateways-update')
                    <li>
                        <a href="{{ route('admin.sms-gateways.edit', $gateway->id) }}">
                            <i class="fal fa-pencil-alt"></i>
                            {{ __('Edit') }}
                        </a>
                    </li>
                    @endcan
                    @can('smsgateways-delete')
                    <li>
                        <a href="{{ route('admin.sms-gateways.destroy', $gateway->id) }}" class="confirm-action" data-method="DELETE">
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
