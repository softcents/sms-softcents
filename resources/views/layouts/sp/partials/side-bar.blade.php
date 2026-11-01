<div class="side-bar">
    <div class="side-bar-logo">
        <a href="{{ route('sp.dashboard.index') }}">
            <img src="{{ asset(get_option('general')['logo'] ?? 'assets/images/logo.png') }}" alt="{{ get_option('general')['title'] ?? 'SP Panel' }}" style="max-height: 45px; object-fit: contain;">
        </a>
        <div class="close-mobile-menu"><i class="fal fa-times"></i></div>
    </div>
    <div class="side-bar-manu">
        <ul>
            <li class="{{ Route::is('sp.dashboard.*') ? 'active' : '' }}">
                <a href="{{ route('sp.dashboard.index') }}">
                    <span class="sidebar-icon"><i class="fas fa-th-large"></i></span>
                    {{ __('SP Dashboard') }}
                </a>
            </li>

            <li class="{{ Route::is('sp.ssps.*') ? 'active' : '' }}">
                <a href="{{ route('sp.ssps.index') }}">
                    <span class="sidebar-icon"><i class="fas fa-sitemap"></i></span>
                    {{ __('My Sub Resellers (SSP)') }}
                </a>
            </li>

            <li class="{{ Route::is('sp.customers.*') ? 'active' : '' }}">
                <a href="{{ route('sp.customers.index') }}">
                    <span class="sidebar-icon"><i class="fas fa-users"></i></span>
                    {{ __('My Customers') }}
                </a>
            </li>

            <li class="{{ Route::is('sp.recharges.*') ? 'active' : '' }}">
                <a href="{{ route('sp.recharges.index') }}">
                    <span class="sidebar-icon"><i class="fas fa-money-bill-wave"></i></span>
                    {{ __('Recharge Requests') }}
                </a>
            </li>

            <li class="{{ Route::is('sp.gateways.*') ? 'active' : '' }}">
                <a href="{{ route('sp.gateways.index') }}">
                    <span class="sidebar-icon"><i class="fas fa-credit-card"></i></span>
                    {{ __('Payment Gateways') }}
                </a>
            </li>

            <li class="{{ Route::is('sp.my-recharge*') ? 'active' : '' }}">
                <a href="{{ route('sp.my-recharge') }}">
                    <span class="sidebar-icon"><i class="fas fa-wallet"></i></span>
                    {{ __('Recharge My Account') }}
                </a>
            </li>

            <li class="{{ Route::is('sp.reports.profit-loss*') ? 'active' : '' }}">
                <a href="{{ route('sp.reports.profit-loss') }}">
                    <span class="sidebar-icon"><i class="fas fa-hand-holding-usd text-success"></i></span>
                    <strong>{{ __('Profit & Loss (লাভ-লোকসান)') }}</strong>
                </a>
            </li>

            <li class="{{ Route::is('sp.reports.index*') ? 'active' : '' }}">
                <a href="{{ route('sp.reports.index') }}">
                    <span class="sidebar-icon"><i class="fas fa-chart-line"></i></span>
                    {{ __('SMS Traffic Reports') }}
                </a>
            </li>

            <li class="{{ Route::is('sp.white-label.*') ? 'active' : '' }}">
                <a href="{{ route('sp.white-label.index') }}">
                    <span class="sidebar-icon"><i class="fas fa-paint-brush"></i></span>
                    {{ __('White Label Branding') }}
                </a>
            </li>

            <li class="{{ Route::is('sp.mail-settings.*') ? 'active' : '' }}">
                <a href="{{ route('sp.mail-settings.index') }}">
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
