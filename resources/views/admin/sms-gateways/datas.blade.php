@foreach($gateways as $gateway)
    <tr>
        @can('smsgateways-delete')
            <td class="checkbox text-start">
                <input type="checkbox" name="ids[]" class="checkbox-item" value="{{ $gateway->id }}" data-url="{{ route('admin.sms-gateways.delete-all') }}">
            </td>
        @endcan
        <td>{{ $loop->iteration }}</td>
        <td><strong>{{ $gateway->name }}</strong></td>
        <td><span class="badge bg-light text-dark border">{{ $gateway->type->name ?? $gateway->driver ?? '' }}</span></td>
        <td>{{ $gateway->priority }}</td>
        <td>
            <span class="badge bg-light text-primary border" title="Non-Masking Purchase Rate">NM: ৳{{ number_format((float)($gateway->non_masking_rate ?? 0.24), 4) }}</span>
            <span class="badge bg-light text-info border" title="Masking Purchase Rate">M: ৳{{ number_format((float)($gateway->masking_rate ?? 0.45), 4) }}</span>
        </td>
        <td class="text-center gateway-balance-cell" data-id="{{ $gateway->id }}">
            <div class="d-inline-flex align-items-center gap-2">
                <span class="badge bg-secondary balance-badge-{{ $gateway->id }}" style="font-size: 13px; font-weight: 600; padding: 6px 10px;" title="Click refresh icon to check balance">
                    <i class="fas fa-wallet me-1"></i> <span class="balance-text">—</span>
                </span>
                <button type="button" class="btn btn-sm btn-outline-primary check-single-balance" style="padding: 2px 6px; border-radius: 4px;" data-id="{{ $gateway->id }}" data-url="{{ route('admin.sms-gateways.balance', $gateway->id) }}" title="{{ __('Refresh Balance') }}">
                    <i class="fas fa-sync-alt fa-xs"></i>
                </button>
            </div>
        </td>
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
