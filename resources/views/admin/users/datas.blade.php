@foreach($users as $user)
    <tr>
        @can('users-delete')
            <td class="checkbox text-start">
                <input type="checkbox" name="ids[]" class="checkbox-item" value="{{ $user->id }}" data-url="{{ route('admin.users.delete-all') }}">
            </td>
        @endcan
        <td>{{ $loop->index + 1 }} <i class="{{ request('id') == $user->id ? 'fas fa-bell text-red' : '' }}"></i></td>
        <td><img height="45" width="45" class="rounded-circle border-1" src="{{ asset($user->image ?? 'assets/images/profile/avatar.jpg') }}" alt=""></td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->phone }}</td>
        <td>{{ $user->email }}</td>
        @if (request('users') == 'user')
        <td>{{ $user->plan->title ?? 'N/A' }}</td>
        <td class="fw-bold {{ $user->low_blnc_alrt >= $user->balance ? 'text-danger' : 'text-success' }}">{{ currency_format($user->balance) }}</td>
        <td>{{ formatted_date($user->created_at) }}</td>
        @endif
        <td class="print-d-none">
            <div class="dropdown table-action">
                <button type="button" data-bs-toggle="dropdown">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    @can('users-update')
                    <li>
                        <a href="{{ route('admin.users.edit',[$user->id, 'users' => $user->role]) }}">
                            <i class="fal fa-pencil-alt"></i>
                            {{ __('Edit') }}
                        </a>
                    </li>
                    @endcan
                    @can('users-delete')
                    <li>
                        <a href="{{ route('admin.users.destroy', $user->id) }}" class="confirm-action" data-method="DELETE">
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
