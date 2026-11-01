<div class="side-bar">
    <div class="side-bar-logo">
        <a href="{{ route('ssp.dashboard.index') }}">
            <img src="{{ asset(get_option('general')['logo'] ?? 'assets/images/logo.png') }}" alt="{{ get_option('general')['title'] ?? 'SSP Panel' }}" style="max-height: 45px; object-fit: contain;">
        </a>
        <div class="close-mobile-menu"><i class="fal fa-times"></i></div>
    </div>
    <div class="side-bar-manu">
        <ul>
            <li class="{{ Route::is('ssp.dashboard.*') ? 'active' : '' }}">
                <a href="{{ route('ssp.dashboard.index') }}">
                    <span class="sidebar-icon"><i class="fas fa-th-large"></i></span>
                    {{ __('SSP Dashboard') }}
                </a>
            </li>

            <li class="{{ Route::is('ssp.customers.*') ? 'active' : '' }}">
                <a href="{{ route('ssp.customers.index') }}">
                    <span class="sidebar-icon"><i class="fas fa-users"></i></span>
                    {{ __('My Customers') }}
                </a>
            </li>

            <li class="{{ Route::is('ssp.recharges.*') ? 'active' : '' }}">
                <a href="{{ route('ssp.recharges.index') }}">
                    <span class="sidebar-icon"><i class="fas fa-money-bill-wave"></i></span>
                    {{ __('Recharge Requests') }}
                </a>
            </li>

            <li class="{{ Route::is('ssp.gateways.*') ? 'active' : '' }}">
                <a href="{{ route('ssp.gateways.index') }}">
                    <span class="sidebar-icon"><i class="fas fa-credit-card"></i></span>
                    {{ __('Payment Gateways') }}
                </a>
            </li>

            <li class="{{ Route::is('ssp.my-recharge*') ? 'active' : '' }}">
                <a href="{{ route('ssp.my-recharge') }}">
                    <span class="sidebar-icon"><i class="fas fa-wallet"></i></span>
                    {{ __('Recharge My Account') }}
                </a>
            </li>

            <li class="{{ Route::is('ssp.reports.profit-loss*') ? 'active' : '' }}">
                <a href="{{ route('ssp.reports.profit-loss') }}">
                    <span class="sidebar-icon"><i class="fas fa-hand-holding-usd text-success"></i></span>
                    <strong>{{ __('Profit & Loss (লাভ-লোকসান)') }}</strong>
                </a>
            </li>

            <li class="{{ Route::is('ssp.reports.index*') ? 'active' : '' }}">
                <a href="{{ route('ssp.reports.index') }}">
                    <span class="sidebar-icon"><i class="fas fa-chart-line"></i></span>
                    {{ __('SMS Traffic Reports') }}
                </a>
            </li>

            <li class="{{ Route::is('ssp.white-label.*') ? 'active' : '' }}">
                <a href="{{ route('ssp.white-label.index') }}">
                    <span class="sidebar-icon"><i class="fas fa-paint-brush"></i></span>
                    {{ __('White Label Branding') }}
                </a>
            </li>

            <li class="{{ Route::is('ssp.mail-settings.*') ? 'active' : '' }}">
                <a href="{{ route('ssp.mail-settings.index') }}">
                    <span class="sidebar-icon"><i class="fas fa-envelope-open-text"></i></span>
                    {{ __('Mail / SMTP Settings') }}
                </a>
            </li>

            <li>
                <a href="{{ route('users.dashboard.index') }}" target="_blank">
                    <span class="sidebar-icon"><i class="fas fa-paper-plane"></i></span>
                    {{ __('Send SMS (User Portal)') }}
                </a>
            </li>
        </ul>
    </div>
</div>
