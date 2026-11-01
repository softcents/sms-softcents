<div class="user-btn">
    @php
        $roleName = match(request('users')) {
            'sp' => __('Reseller (SP)'),
            'ssp' => __('Sub Reseller (SSP)'),
            'admin' => __('Staff'),
            default => __('Customer'),
        };
    @endphp
    @if (request('users') == 'user')
    <a href="{{ route('admin.users.index', ['users' => request('users'), 'low_balance' => 'low_balance']) }}" class="theme-btn print-btn text-light {{ request('low_balance') ? '' : 'active' }}">
        <i class="fas fa-list me-1"></i>
        {{ __("Low Balance Customers") }}
    </a>
    @endif
    @can('users-read')
    <a href="{{ route('admin.users.index', ['users' => request('users', 'user')]) }}" class="theme-btn print-btn text-light {{ Route::is('admin.users.index') && !request('low_balance') ? '' : 'active' }}">
        <i class="fas fa-list me-1"></i>
        {{ $roleName }} {{ __('List') }}
    </a>
    @endcan
    @can('users-create')
    <a href="{{ route('admin.users.create', ['users' => request('users', 'user')]) }}" class="theme-btn print-btn text-light {{ Route::is('admin.users.create') ? '' : 'active' }}">
        <i class="fas fa-plus-circle me-1"></i>
        {{ __('Add New') }} {{ $roleName }}
    </a>
    @endcan
</div>
