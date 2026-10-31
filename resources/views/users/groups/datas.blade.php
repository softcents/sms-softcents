@foreach($groups as $group)
    <tr>
        <td class="checkbox text-start">
            <input type="checkbox" name="ids[]" class="checkbox-item" value="{{ $group->id }}" data-url="{{ route('users.groups.delete-all') }}">
        </td>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $group->name }}</td>
        <td>{{ ($group->created_at)->format('d M Y') }}</td>
        <td class="text-center w-150">
            <div class="badge bg-{{ $group->status == 1 ? 'success' : 'danger' }}">
                {{ $group->status == 1 ? 'Active' : 'Deactive' }}
            </div>
        </td>
        <td>
            <div class="dropdown table-action">
                <button type="button" data-bs-toggle="dropdown">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{ route('users.groups.edit', $group->id) }}">
                            <i class="fal fa-pencil-alt"></i>
                            {{ __('Edit') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('users.groups.destroy', $group->id) }}" class="confirm-action" data-method="DELETE">
                            <i class="fal fa-trash-alt"></i>
                            {{ __('Delete') }}
                        </a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
@endforeach
