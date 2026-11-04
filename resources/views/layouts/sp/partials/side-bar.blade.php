<nav class="side-bar">
    <div class="side-bar-logo">
        <a href="{{ route('sp.dashboard.index') }}">
            <img src="{{ asset(get_option('general')['logo'] ?? 'assets/images/logo/backend_logo.png') }}" alt="{{ get_option('general')['title'] ?? 'SP Panel' }}" style="max-height: 45px; object-fit: contain;">
        </a>
        <button class="close-btn"><i class="fal fa-times"></i></button>
    </div>
    <div class="side-bar-manu">
        <ul>
            <li class="{{ Route::is('sp.dashboard.*') ? 'active' : '' }}">
                <a href="{{ route('sp.dashboard.index') }}">
                    <span class="sidebar-icon"><i class="fas fa-th-large"></i></span>
                    {{ __('Dashboard') }}
                </a>
            </li>

            <li class="dropdown {{ Route::is('sp.ssps.*') ? 'active' : '' }}">
                <a href="#">
                    <span class="sidebar-icon"><i class="fas fa-sitemap"></i></span>
                    {{ __('Sub Resellers (SSP)') }}
                </a>
                <ul>
                    <li><a class="{{ Route::is('sp.ssps.create') ? 'active' : '' }}" href="{{ route('sp.ssps.create') }}">{{ __('Create Sub Reseller') }}</a></li>
                    <li><a class="{{ Route::is('sp.ssps.index', 'sp.ssps.edit') ? 'active' : '' }}" href="{{ route('sp.ssps.index') }}">{{ __('Manage Sub Resellers') }}</a></li>
                </ul>
            </li>

            <li class="dropdown {{ Route::is('sp.customers.*') ? 'active' : '' }}">
                <a href="#">
                    <span class="sidebar-icon"><i class="fas fa-users"></i></span>
                    {{ __('Manage Customers') }}
                </a>
                <ul>
                    <li><a class="{{ Route::is('sp.customers.create') ? 'active' : '' }}" href="{{ route('sp.customers.create') }}">{{ __('Create Customer') }}</a></li>
                    <li><a class="{{ Route::is('sp.customers.index', 'sp.customers.edit') ? 'active' : '' }}" href="{{ route('sp.customers.index') }}">{{ __('Manage Customers') }}</a></li>
                </ul>
            </li>

            <li class="{{ Route::is('sp.tariffs.*') ? 'active' : '' }}">
                <a href="{{ route('sp.tariffs.index') }}">
                    <span class="sidebar-icon"><i class="fas fa-tags"></i></span>
                    {{ __('Tariff Management') }}
                </a>
            </li>

            <li class="dropdown {{ Route::is('sp.recharges.*', 'sp.my-recharge*') ? 'active' : '' }}">
                <a href="#">
                    <span class="sidebar-icon"><i class="fas fa-money-bill-wave"></i></span>
                    {{ __('Recharge Payments') }}
                </a>
                <ul>
                    <li><a class="{{ Route::is('sp.recharges.index') ? 'active' : '' }}" href="{{ route('sp.recharges.index') }}">{{ __('Recharge Requests') }}</a></li>
                    <li><a class="{{ Route::is('sp.my-recharge*') ? 'active' : '' }}" href="{{ route('sp.my-recharge') }}">{{ __('Recharge My Account') }}</a></li>
                </ul>
            </li>

            <li class="dropdown {{ Route::is('sp.gateways.*') ? 'active' : '' }}">
                <a href="#">
                    <span class="sidebar-icon"><i class="fas fa-credit-card"></i></span>
                    {{ __('Payment Gateways') }}
                </a>
                <ul>
                    <li><a class="{{ Route::is('sp.gateways.create') ? 'active' : '' }}" href="{{ route('sp.gateways.create') }}">{{ __('Add Gateway') }}</a></li>
                    <li><a class="{{ Route::is('sp.gateways.index', 'sp.gateways.edit') ? 'active' : '' }}" href="{{ route('sp.gateways.index') }}">{{ __('Manage Gateways') }}</a></li>
                </ul>
            </li>

            <li class="{{ Route::is('sp.queue-monitor.*') ? 'active' : '' }}">
                <a href="{{ route('sp.queue-monitor.index') }}">
                    <span class="sidebar-icon"><i class="fas fa-satellite-dish text-success"></i></span>
                    {{ __('Live SMS Monitoring') }}
                    <span class="badge bg-success text-white ms-auto" style="font-size: 9px; padding: 2px 6px; border-radius: 4px;">LIVE</span>
                </a>
            </li>

            <li class="dropdown {{ Route::is('sp.reports.*') ? 'active' : '' }}">
                <a href="#">
                    <span class="sidebar-icon"><i class="fas fa-chart-line"></i></span>
                    {{ __('Reports') }}
                </a>
                <ul>
                    <li><a class="{{ Route::is('sp.reports.profit-loss*') ? 'active' : '' }}" href="{{ route('sp.reports.profit-loss') }}">{{ __('Profit & Loss') }}</a></li>
                    <li><a class="{{ Route::is('sp.reports.index*') ? 'active' : '' }}" href="{{ route('sp.reports.index') }}">{{ __('SMS Traffic Reports') }}</a></li>
                </ul>
            </li>

            <li class="dropdown {{ Route::is('sp.white-label.*', 'sp.mail-settings.*') ? 'active' : '' }}">
                <a href="#">
                    <span class="sidebar-icon"><i class="fas fa-cog"></i></span>
                    {{ __('Settings') }}
                </a>
                <ul>
                    <li><a class="{{ Route::is('sp.white-label.*') ? 'active' : '' }}" href="{{ route('sp.white-label.index') }}">{{ __('White Label Branding') }}</a></li>
                    <li><a class="{{ Route::is('sp.mail-settings.*') ? 'active' : '' }}" href="{{ route('sp.mail-settings.index') }}">{{ __('Mail / SMTP Settings') }}</a></li>
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
