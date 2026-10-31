@foreach($contacts as $contact)
    <tr>
        <td class="checkbox text-start">
            <input type="checkbox" name="ids[]" class="checkbox-item" value="{{ $contact->id }}" data-url="{{ route('users.contacts.delete-all') }}">
        </td>
        <td>{{ $loop->iteration }}</td>
        <td>{{ optional($contact->group)->name }}</td>
        <td>{{ $contact->number }}</td>
        <td>{{ $contact->name }}</td>
        <td>{{ $contact->email }}</td>
        <td class="text-center w-150">
            <div class="badge bg-{{ $contact->status == 1 ? 'success' : 'danger' }}">
                {{ $contact->status == 1 ? 'Active' : 'Deactive' }}
            </div>
        </td>
        <td>
            <div class="dropdown table-action">
                <button type="button" data-bs-toggle="dropdown">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{ route('users.contacts.edit', $contact->id) }}">
                            <i class="fal fa-pencil-alt"></i>
                            {{ __('Edit') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('users.contacts.destroy', $contact->id) }}" class="confirm-action" data-method="DELETE">
                            <i class="fal fa-trash-alt"></i>
                            {{ __('Delete') }}
                        </a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
@endforeach
