<div class="user-btn">
    @if (request('users') == 'user')
    <a href="{{ route('admin.users.index', ['users' => request('users'), 'low_balance' => 'low_balance']) }}" class="theme-btn print-btn text-light {{ request('low_balance') ? '' : 'active' }}">
        <i class="fas fa-list me-1"></i>
        {{ __("Low Balance Customers") }}
    </a>
    @endif
    @can('users-read')
    <a href="{{ route('admin.users.index', ['users' => request('users')]) }}" class="theme-btn print-btn text-light {{ Route::is('admin.users.index') && !request('low_balance') ? '' : 'active' }}">
        <i class="fas fa-list me-1"></i>
        {{ __(request('users') == 'admin' ? __('Manage Users') : __('Customers'). ' List') }}
    </a>
    @endcan
    @can('users-create')
    <a href="{{ route('admin.users.create', ['users' => request('users')]) }}" class="theme-btn print-btn text-light {{ Route::is('admin.users.create') ? '' : 'active' }}">
        <i class="fas fa-plus-circle me-1"></i>
        {{ __('Add New') }}
        {{ request('users') == 'admin' ? __('Admin') : __('Customer') }}
    </a>
    @endcan
</div>
