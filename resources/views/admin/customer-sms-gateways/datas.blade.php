@foreach($senderids as $senderid)
    <tr>
        @can('senderids-delete')
        <td class="checkbox text-start">
            <input type="checkbox" name="ids[]" class="checkbox-item" value="{{ $senderid->id }}" data-url="{{ route('admin.senderids.delete-all') }}">
        </td>
        @endcan
        <td>{{ $loop->iteration }}</td>
        <td>{{ optional($senderid->user)->name ?? 'N/A' }}</td>
        <td>{{ optional($senderid->user)->phone ?? 'N/A' }}</td>
        <td>{{ $senderid->sender }}</td>
        <td>
            <div class="badge bg-{{ $senderid->type == 'Non-Masking' ? 'success' : 'danger' }}">
                {{ $senderid->type }}
            </div>
        </td>
        <td>
            <div class="badge bg-{{ $senderid->is_default == 1 ? 'success' : 'warning' }}">
                {{ $senderid->is_default == 1 ? 'Yes' : 'No' }}
            </div>
        </td>
        <td class="text-center w-150">
            @can('senderids-update')
                <label class="switch">
                    <input type="checkbox" {{ $senderid->status == 1 ? 'checked' : '' }} class="status" data-url="{{ route('admin.senderids.status', $senderid->id)}}">
                    <span class="slider round"></span>
                </label>
            @else
                <div class="badge bg-{{ $senderid->status == 1 ? 'success' : 'danger' }}">
                    {{ $senderid->status == 1 ? 'Active' : 'Deactive' }}
                </div>
            @endcan
        </td>
        <td>
            <div class="dropdown table-action">
                <button type="button" data-bs-toggle="dropdown">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    @can('senderids-update')
                    <li>
                        <a href="{{ route('admin.senderids.edit', $senderid->id) }}">
                            <i class="fal fa-pencil-alt"></i>
                            {{ __('Edit') }}
                        </a>
                    </li>
                    @endcan
                    @can('senderids-delete')
                    <li>
                        <a href="{{ route('admin.senderids.destroy', $senderid->id) }}" class="confirm-action" data-method="DELETE">
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
