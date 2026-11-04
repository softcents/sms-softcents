<header class="main-header-section py-2 px-3 bg-white border-bottom shadow-xs">
    <div class="header-wrapper d-flex justify-content-between align-items-center">
        {{-- Left: Sidebar Opener + Modern Wallet Card --}}
        <div class="header-left d-flex align-items-center gap-2 gap-md-3">
            <div class="sidebar-opner cursor-pointer d-flex align-items-center justify-content-center rounded-2 border text-muted" style="width: 34px; height: 34px; background: #f8fafc; font-size: 14px;">
                <i class="fal fa-bars" aria-hidden="true"></i>
            </div>
            
            {{-- Modern Wallet Card --}}
            <a href="{{ route('users.recharges.index') }}" class="modern-header-wallet text-decoration-none d-flex align-items-center gap-2 px-2.5 py-1 rounded-3" style="background: #f1f5f9; border: 1px solid #e2e8f0; transition: all 0.2s ease;">
                <div class="d-flex align-items-center justify-content-center rounded-2 text-white" style="width: 26px; height: 26px; font-size: 11px; background: linear-gradient(135deg, #4f46e5, #4338ca);">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="d-flex flex-column" style="line-height: 1.15;">
                    <span class="text-muted fw-semibold d-none d-sm-inline" style="font-size: 9.5px; text-transform: uppercase; letter-spacing: 0.3px;">{{ __('Balance') }}</span>
                    <span class="fw-bold text-dark" style="font-size: 13px;">{{ currency_format(auth()->user()->balance) }}</span>
                </div>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle d-none d-md-inline-flex align-items-center py-0.5 px-1.5" style="font-size: 10px; border-radius: 4px;">
                    <i class="fas fa-plus me-1" style="font-size: 8px;"></i>{{ __('Top up') }}
                </span>
            </a>
        </div>

        <div class="header-middle"></div>

        {{-- Right: Language + Notifications + Profile --}}
        <div class="header-right d-flex align-items-center gap-2 gap-sm-3">
            <div class="language-change d-none d-sm-block">
                <div class="dropdown">
                    <button class="btn btn-sm btn-light border dropdown-toggle py-1 px-2 text-dark" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 12px; height: 32px;">
                        <i class="fas fa-globe text-muted me-1"></i>{{ languages()[app()->getLocale()] }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" style="font-size: 12px;">
                        @foreach (languages() as $key => $language)
                        <li><a class="dropdown-item py-1.5" href="{{ request()->fullUrlWithQuery(['lang' => $key]) }}">{{ $language }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>

            @if (auth()->user()->role == 'superadmin')
            <div class="notifications dropdown">
                <a href="#" class="dropdown-toggleer position-relative d-flex align-items-center justify-content-center rounded-2 border text-muted" data-bs-toggle="dropdown" style="width: 32px; height: 32px; background: #f8fafc;">
                    <i><img src="{{ asset('assets/images/icons/bel.svg') }}" alt="" style="width: 14px; height: 14px;"></i>
                    @if (auth()->user()->unreadNotifications->count() > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 9px; padding: 2px 5px;">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-end shadow border-0" style="min-width: 260px; font-size: 12px;">
                    <div class="notification-header d-flex justify-content-between align-items-center p-2 border-bottom">
                        <span class="small fw-bold">{{ __('Notifications') }} ({{ auth()->user()->unreadNotifications->count() }})</span>
                        <a href="{{ route('admin.notifications.mtReadAll') }}" class="text-danger small text-decoration-none">{{ __('Mark read') }}</a>
                    </div>
                    <ul class="list-unstyled mb-0" style="max-height: 200px; overflow-y: auto;">
                        @forelse (auth()->user()->unreadNotifications as $notification)
                        <li class="border-bottom">
                            <a href="{{ route('admin.notifications.mtView', $notification->id) }}" class="dropdown-item py-2 text-wrap">
                                <strong>{{ __($notification->data['message'] ?? '') }}</strong>
                                <small class="text-muted d-block" style="font-size: 10px;">{{ $notification->created_at->diffForHumans() }}</small>
                            </a>
                        </li>
                        @empty
                        <li class="p-3 text-center text-muted small">{{ __('No new notifications') }}</li>
                        @endforelse
                    </ul>
                    <div class="notification-footer p-1.5 text-center border-top">
                        <a class="text-primary small text-decoration-none" href="{{ route('admin.notifications.index') }}">{{ __('View all') }}</a>
                    </div>
                </div>
            </div>
            @endif

            <div class="profile-info dropdown">
                <a href="#" class="d-flex align-items-center gap-1.5 text-decoration-none p-1 rounded-3 hover-bg-light" data-bs-toggle="dropdown">
                    <img src="{{ asset(Auth::user()->image ?? 'assets/images/icons/default-user.png') }}" alt="Profile" class="rounded-circle border" style="width: 32px; height: 32px; object-fit: cover;">
                    <span class="d-none d-md-inline fw-semibold text-dark small">{{ Auth::user()->name }}</span>
                    <i class="fas fa-chevron-down text-muted d-none d-md-inline" style="font-size: 10px;"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-1" style="min-width: 200px; font-size: 12.5px; border-radius: 8px;">
                    <li class="px-3 py-2 border-bottom bg-light rounded-top">
                        <div class="fw-bold text-dark">{{ Auth::user()->name }}</div>
                        <small class="text-primary font-monospace">@<span>{{ Auth::user()->username }}</span></small>
                    </li>
                    @php $banner = \App\Services\ImpersonationService::getBannerInfo(); @endphp
                    @if ($banner)
                    <li>
                        <a href="{{ $banner['switch_back_url'] }}" class="dropdown-item text-danger fw-bold py-2">
                            <i class="far fa-arrow-left text-danger me-2"></i> {{ __('Return to Admin') }}
                        </a>
                    </li>
                    @endif
                    <li><a class="dropdown-item py-2" href="{{ route('users.profiles.index') }}"><i class="fal fa-user me-2 text-muted"></i> {{ __('My Profile') }}</a></li>
                    <li><a class="dropdown-item py-2" href="{{ route('users.recharges.index') }}"><i class="fal fa-wallet me-2 text-muted"></i> {{ __('Billing & Top Up') }}</a></li>
                    <li class="border-top">
                        <a href="javascript:void(0)" class="dropdown-item py-2 text-danger logoutButton">
                            <i class="far fa-sign-out me-2"></i> {{ __('Logout') }}
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
