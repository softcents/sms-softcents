@foreach($templates as $template)
    <tr>
        <td class="checkbox text-start">
            <input type="checkbox" name="ids[]" class="checkbox-item" value="{{ $template->id }}" data-url="{{ route('users.templates.delete-all') }}">
        </td>
        <td>{{ ($templates->perPage() * ($templates->currentPage() - 1)) + $loop->iteration }}</td>
        <td>{{ $template->name }}</td>
        <td>{{ $template->text }}</td>
        <td>{{ ($template->created_at)->format('d M Y') }}</td>
        <td class="text-center w-150">
            <div class="badge bg-{{ $template->status == 1 ? 'success' : 'danger' }}">
                {{ $template->status == 1 ? 'Active' : 'Deactive' }}
            </div>
        </td>
        <td>
            <div class="dropdown table-action">
                <button type="button" data-bs-toggle="dropdown">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a href="javascript:void(0)" class="edit-template" data-status="{{ $template->status }}" data-name="{{ $template->name }}" data-text="{{ $template->text }}" data-url="{{ route('users.templates.update', $template->id) }}">
                            <i class="fal fa-pencil-alt"></i>
                            {{ __('Edit') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('users.templates.destroy', $template->id) }}" class="confirm-action" data-method="DELETE">
                            <i class="fal fa-trash-alt"></i>
                            {{ __('Delete') }}
                        </a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
@endforeach
