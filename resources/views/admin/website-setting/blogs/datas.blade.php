@foreach ($blogs as $blog)
    <tr>
        <td class="checkbox text-start">
            <input type="checkbox" name="ids[]" class="checkbox-item" value="{{ $blog->id }}" data-url="{{ route('admin.blogs.delete-all') }}">
        </td>
        <td>
            {{ $loop->index + 1 }} <i class="{{ request('id') == $blog->id ? 'fas fa-bell text-red' : '' }}"></i>
        </td>
        <td>
            <img height="45" width="45" class="rounded-circle border-1"
                src="{{ asset($blog->image ?? 'assets/images/profile/avatar.jpg') }}" alt="">
        </td>
        <td>{{ Str::limit($blog->title, 25, '...') }}</td>
        <td class="text-center w-150">
            <label class="switch">
                <input type="checkbox" {{ $blog->status == 1 ? 'checked' : '' }} class="status"
                    data-url="{{ route('admin.blogs.status', $blog->id) }}">
                <span class="slider round"></span>
            </label>
        </td>
        <td class="print-d-none">
            <div class="dropdown table-action">
                <button type="button" data-bs-toggle="dropdown">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{ route('admin.blogs.edit', $blog->id) }}">
                            <i class="fal fa-pencil-alt"></i>
                            {{ __('Edit') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.blogs.destroy', $blog->id) }}" class="confirm-action"
                            data-method="DELETE">
                            <i class="fal fa-trash-alt"></i>
                            {{ __('Delete') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.blogs.filter.comment',$blog->id) }}">
                            <i class='fas fa-comment'></i>
                            {{ __('Comment') }}
                        </a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
@endforeach
