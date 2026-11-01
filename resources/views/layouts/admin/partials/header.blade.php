<header class="main-header-section">
    <div class="header-wrapper">
        <div class="header-left">
            <div class="sidebar-opner"><i class="fal fa-bars" aria-hidden="true"></i></div>
            <a target="_blank" class="text-custom-primary" href="{{ route('home.index') }}" class="logo">
                <i class="fas fa-globe me-1"></i>
                {{ __('View Website') }}
            </a>
        </div>
        <div class="header-middle">
            <a href="{{ route('admin.sms-gateways.index') }}" class="d-none d-md-inline-flex align-items-center gap-2 text-decoration-none px-3 py-1 rounded-pill shadow-xs" style="background: #eef2ff; border: 1px solid #c7d2fe;" title="{{ __('Click to view SMS Gateways & Live Balances') }}">
                <span class="badge bg-success rounded-pill px-2 py-1" style="font-size: 11px;">WhiteInfo SMS</span>
                <span class="fw-bold text-dark" style="font-size: 13px;" id="headerWhiteInfoBalance">৳6,408.44</span>
                <i class="fas fa-wallet text-primary ms-1" style="font-size: 12px;"></i>
            </a>
        </div>
        <div class="header-right">
            <div class="language-change">
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ languages()[app()->getLocale()] }}
                    </button>
                    <ul class="dropdown-menu">
                        @foreach (languages() as $key => $language)
                        <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['lang' => $key]) }}">{{ $language }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @if (auth()->user()->role == 'superadmin')
            <div class="notifications dropdown">
                <a href="#" class="dropdown-toggleer mt-1 me-3" data-bs-toggle="dropdown">
                    <i><img src="{{ asset('assets/images/icons/bel.svg') }}" alt=""></i>
                    <span class="bg-red">{{ auth()->user()->unreadNotifications ->count() }}</span>
                </a>
                <div class="dropdown-menu">
                    <div class="notification-header">
                        <p>{{ __('You Have') }} <strong>{{ auth()->user()->unreadNotifications->count() }}</strong> {{ __('new Notifications') }}</p>
                        <a href="{{ route('admin.notifications.mtReadAll') }}" class="text-red">{{ __('Mark all Read') }}</a>
                    </div>
                    <ul>
                        @foreach (auth()->user()->unreadNotifications  as $notification)
                        <li>
                            <a href="{{ route('admin.notifications.mtView', $notification->id) }}">
                                <strong>{{ __($notification->data['message'] ?? '') }}</strong>
                                <span>{{ $notification->created_at->diffForHumans() }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    <div class="notification-footer">
                        <a class="text-red" href="{{ route('admin.notifications.index') }}">{{ __('View all notifications') }}</a>
                    </div>
                </div>
            </div>
            @endif
            <div class="profile-info dropdown">
                <a href="#"  data-bs-toggle="dropdown">
                    <img src="{{ asset(Auth::user()->image ?? 'assets/images/icons/default-user.png') }}" alt="Profile">
                </a>
                <ul class="dropdown-menu">
                    <li> <a href="{{ url('clear-all') }}"> <i class="far fa-undo"></i> {{ __('Clear cache') }}</a></li>
                    <li> <a href="{{ route('admin.profiles.index') }}"> <i class="fal fa-user"></i> {{ __('My Profile') }}</a></li>
                    <li>
                        <a href="javascript:void(0)" class="logoutButton">
                            <i class="far fa-sign-out"></i> {{ __('Logout') }}
                            <form action="{{ route('logout') }}" method="post" id="logoutForm">
                                @csrf
                            </form>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
