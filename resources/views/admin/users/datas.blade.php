@forelse($users as $user)
    <tr class="user-row clickable-user-row" data-href="{{ route('admin.users.show', $user->id) }}" style="cursor: pointer;">
        @can('users-delete')
            <td class="checkbox text-start">
                <input type="checkbox" name="ids[]" class="checkbox-item" value="{{ $user->id }}" data-url="{{ route('admin.users.delete-all') }}">
            </td>
        @endcan
        <td>
            <a href="{{ route('admin.users.show', $user->id) }}" class="text-decoration-none text-dark fw-semibold">
                {{ ($users instanceof \Illuminate\Pagination\LengthAwarePaginator ? $users->firstItem() + $loop->index : $loop->index + 1) }}
            </a>
            <i class="{{ request('id') == $user->id ? 'fas fa-bell text-red' : '' }}"></i>
        </td>
        <td>
            <a href="{{ route('admin.users.show', $user->id) }}" title="{{ __('View User Details') }}">
                <img height="45" width="45" class="rounded-circle border-1 shadow-sm" src="{{ asset($user->image ?? 'assets/images/profile/avatar.jpg') }}" alt="{{ $user->name }}">
            </a>
        </td>
        <td>
            <a href="{{ route('admin.users.show', $user->id) }}" class="text-dark fw-bold text-decoration-none hover-primary" title="{{ __('View Details') }}">
                {{ $user->name }}
            </a>
            <br>
            <a href="{{ route('admin.users.show', $user->id) }}" class="text-decoration-none text-muted">
                <small><i class="fas fa-at"></i> {{ $user->username }}</small>
            </a>
        </td>
        <td>
            <a href="{{ route('admin.users.show', $user->id) }}" class="text-decoration-none text-dark">
                {{ $user->phone ?: '—' }}
            </a>
        </td>
        <td>
            <a href="{{ route('admin.users.show', $user->id) }}" class="text-decoration-none text-dark">
                {{ $user->email }}
            </a>
        </td>
        @if (request('users', 'user') != 'admin')
        <td class="user-plan-cell-{{ $user->id }}">
            <a href="{{ route('admin.users.show', $user->id) }}" class="text-decoration-none">
                @if ($user->role == 'user' && $user->plan)
                    <span class="badge bg-info text-dark">{{ $user->plan->title }}</span>
                @else
                    <span class="badge bg-secondary">{{ strtoupper($user->role) }}</span>
                @endif
                @if ($user->parent)
                    <br><small class="text-muted"><i class="fas fa-level-up-alt"></i> {{ $user->parent->name }}</small>
                @endif
            </a>
        </td>
        <td class="user-balance-cell-{{ $user->id }}">
            <a href="{{ route('admin.users.show', $user->id) }}" class="text-decoration-none fw-bold {{ $user->low_blnc_alrt >= $user->balance ? 'text-danger' : 'text-success' }}">
                {{ currency_format($user->balance) }}
            </a>
        </td>
        <td>
            <a href="{{ route('admin.users.show', $user->id) }}" class="text-decoration-none text-dark">
                {{ formatted_date($user->created_at) }}
            </a>
        </td>
        @endif
        <td class="print-d-none">
            <div class="d-flex align-items-center justify-content-center gap-2">
                @if (in_array($user->role, ['user', 'sp', 'ssp']))
                <button type="button" class="btn btn-sm btn-outline-success d-inline-flex align-items-center py-1 px-2 btn-open-recharge-modal"
                    data-id="{{ $user->id }}"
                    data-name="{{ $user->name }}"
                    data-username="{{ $user->username }}"
                    data-role="{{ strtoupper($user->role) }}"
                    data-balance="{{ (float)$user->balance }}"
                    data-balance-formatted="{{ currency_format($user->balance) }}"
                    title="{{ __('Recharge / Adjust Balance') }}"
                    style="font-size: 12px; font-weight: 500; white-space: nowrap;">
                    <i class="fas fa-wallet me-1"></i> {{ __('Recharge') }}
                </button>
                @if ($user->role == 'user')
                <button type="button" class="btn btn-sm btn-outline-info d-inline-flex align-items-center py-1 px-2 btn-open-assign-plan-modal"
                    data-id="{{ $user->id }}"
                    data-name="{{ $user->name }}"
                    data-username="{{ $user->username }}"
                    data-plan="{{ $user->plan ? $user->plan->title : 'None' }}"
                    data-plan-id="{{ $user->plan_id ?? '' }}"
                    title="{{ __('Assign / Change Plan') }}"
                    style="font-size: 12px; font-weight: 500; white-space: nowrap;">
                    <i class="fas fa-layer-group me-1"></i> {{ __('Plan') }}
                </button>
                @endif
                <a href="{{ route('admin.users.login-as', $user->id) }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center py-1 px-2" title="{{ __('Login As ') . strtoupper($user->role) }}" style="font-size: 12px; font-weight: 500; white-space: nowrap;">
                    <i class="fas fa-sign-in-alt me-1"></i> {{ __('Login As') }}
                </a>
                @endif
                <div class="dropdown table-action">
                    <button type="button" data-bs-toggle="dropdown">
                        <i class="far fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="{{ route('admin.users.show', $user->id) }}">
                                <i class="fal fa-eye text-primary"></i>
                                {{ __('View Details') }}
                            </a>
                        </li>
                        @if (in_array($user->role, ['user', 'sp', 'ssp']))
                        <li>
                            <a href="javascript:void(0)" class="btn-open-recharge-modal"
                                data-id="{{ $user->id }}"
                                data-name="{{ $user->name }}"
                                data-username="{{ $user->username }}"
                                data-role="{{ strtoupper($user->role) }}"
                                data-balance="{{ (float)$user->balance }}"
                                data-balance-formatted="{{ currency_format($user->balance) }}">
                                <i class="fal fa-wallet text-success"></i>
                                {{ __('Recharge / Adjust Balance') }}
                            </a>
                        </li>
                        @if ($user->role == 'user')
                        <li>
                            <a href="javascript:void(0)" class="btn-open-assign-plan-modal"
                                data-id="{{ $user->id }}"
                                data-name="{{ $user->name }}"
                                data-username="{{ $user->username }}"
                                data-plan="{{ $user->plan ? $user->plan->title : 'None' }}"
                                data-plan-id="{{ $user->plan_id ?? '' }}">
                                <i class="fal fa-layer-group text-info"></i>
                                {{ __('Assign / Change Plan') }}
                            </a>
                        </li>
                        @endif
                        <li>
                            <a href="{{ route('admin.users.login-as', $user->id) }}">
                                <i class="fal fa-sign-in-alt text-primary"></i>
                                {{ __('Login As ') . strtoupper($user->role) }}
                            </a>
                        </li>
                        @endif
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
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="10" class="text-center py-4 text-muted">
            <i class="fas fa-search me-1"></i> {{ __('No users found') }}
        </td>
    </tr>
@endforelse
