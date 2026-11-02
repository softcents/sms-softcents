<nav class="side-bar">
    <div class="side-bar-logo">
        <a href="{{ route('ssp.dashboard.index') }}">
            <img src="{{ asset(get_option('general')['logo'] ?? 'assets/images/logo/backend_logo.png') }}" alt="{{ get_option('general')['title'] ?? 'SSP Panel' }}" style="max-height: 45px; object-fit: contain;">
        </a>
        <button class="close-btn"><i class="fal fa-times"></i></button>
    </div>
    <div class="side-bar-manu">
        <ul>
            <li class="{{ Route::is('ssp.dashboard.*') ? 'active' : '' }}">
                <a href="{{ route('ssp.dashboard.index') }}">
                    <span class="sidebar-icon"><i class="fas fa-th-large"></i></span>
                    {{ __('Dashboard') }}
                </a>
            </li>

            <li class="dropdown {{ Route::is('ssp.customers.*') ? 'active' : '' }}">
                <a href="#">
                    <span class="sidebar-icon"><i class="fas fa-users"></i></span>
                    {{ __('Manage Customers') }}
                </a>
                <ul>
                    <li><a class="{{ Route::is('ssp.customers.create') ? 'active' : '' }}" href="{{ route('ssp.customers.create') }}">{{ __('Create Customer') }}</a></li>
                    <li><a class="{{ Route::is('ssp.customers.index', 'ssp.customers.edit') ? 'active' : '' }}" href="{{ route('ssp.customers.index') }}">{{ __('Manage Customers') }}</a></li>
                </ul>
            </li>

            <li class="dropdown {{ Route::is('ssp.recharges.*', 'ssp.my-recharge*') ? 'active' : '' }}">
                <a href="#">
                    <span class="sidebar-icon"><i class="fas fa-money-bill-wave"></i></span>
                    {{ __('Recharge Payments') }}
                </a>
                <ul>
                    <li><a class="{{ Route::is('ssp.recharges.index') ? 'active' : '' }}" href="{{ route('ssp.recharges.index') }}">{{ __('Recharge Requests') }}</a></li>
                    <li><a class="{{ Route::is('ssp.my-recharge*') ? 'active' : '' }}" href="{{ route('ssp.my-recharge') }}">{{ __('Recharge My Account') }}</a></li>
                </ul>
            </li>

            <li class="dropdown {{ Route::is('ssp.gateways.*') ? 'active' : '' }}">
                <a href="#">
                    <span class="sidebar-icon"><i class="fas fa-credit-card"></i></span>
                    {{ __('Payment Gateways') }}
                </a>
                <ul>
                    <li><a class="{{ Route::is('ssp.gateways.create') ? 'active' : '' }}" href="{{ route('ssp.gateways.create') }}">{{ __('Add Gateway') }}</a></li>
                    <li><a class="{{ Route::is('ssp.gateways.index', 'ssp.gateways.edit') ? 'active' : '' }}" href="{{ route('ssp.gateways.index') }}">{{ __('Manage Gateways') }}</a></li>
                </ul>
            </li>

            <li class="dropdown {{ Route::is('ssp.reports.*') ? 'active' : '' }}">
                <a href="#">
                    <span class="sidebar-icon"><i class="fas fa-chart-line"></i></span>
                    {{ __('Reports') }}
                </a>
                <ul>
                    <li><a class="{{ Route::is('ssp.reports.profit-loss*') ? 'active' : '' }}" href="{{ route('ssp.reports.profit-loss') }}">{{ __('Profit & Loss') }}</a></li>
                    <li><a class="{{ Route::is('ssp.reports.index*') ? 'active' : '' }}" href="{{ route('ssp.reports.index') }}">{{ __('SMS Traffic Reports') }}</a></li>
                </ul>
            </li>

            <li class="dropdown {{ Route::is('ssp.white-label.*', 'ssp.mail-settings.*') ? 'active' : '' }}">
                <a href="#">
                    <span class="sidebar-icon"><i class="fas fa-cog"></i></span>
                    {{ __('Settings') }}
                </a>
                <ul>
                    <li><a class="{{ Route::is('ssp.white-label.*') ? 'active' : '' }}" href="{{ route('ssp.white-label.index') }}">{{ __('White Label Branding') }}</a></li>
                    <li><a class="{{ Route::is('ssp.mail-settings.*') ? 'active' : '' }}" href="{{ route('ssp.mail-settings.index') }}">{{ __('Mail / SMTP Settings') }}</a></li>
                </ul>
            </li>

            <li>
                <a href="{{ route('users.dashboard.index') }}" target="_blank">
                    <span class="sidebar-icon"><i class="fas fa-paper-plane"></i></span>
                    {{ __('Send SMS (User Portal)') }}
                </a>
            </li>
        </ul>
    </div>
</nav>
