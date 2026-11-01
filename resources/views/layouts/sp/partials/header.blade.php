<header class="main-header-section">
    <div class="header-wrapper">
        <div class="header-left">
            <div class="sidebar-opner"><i class="fal fa-bars" aria-hidden="true"></i></div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary text-white px-2 py-1" style="font-size: 12px;">SERVICE PROVIDER (SP)</span>
                <h5 class="mb-0 ms-2">{{ __('My Balance') }}: <b class="text-success">{{ currency_format(auth()->user()->balance) }}</b></h5>
            </div>
        </div>
        <div class="header-middle"></div>
        <div class="header-right d-flex align-items-center gap-3">
            <div class="language-change">
                <div class="dropdown">
                    <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ languages()[app()->getLocale()] ?? 'English' }}
                    </button>
                    <ul class="dropdown-menu">
                        @foreach (languages() as $key => $language)
                        <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['lang' => $key]) }}">{{ $language }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="profile-info dropdown">
                <a href="#" data-bs-toggle="dropdown" class="d-flex align-items-center gap-2 text-decoration-none">
                    <img src="{{ asset(auth()->user()->image ?? 'assets/images/profile/avatar.jpg') }}" alt="Profile" style="width: 38px; height: 38px; border-radius: 50%;">
                    <span class="d-none d-md-inline fw-semibold text-dark">{{ auth()->user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li class="px-3 py-2 border-bottom">
                        <strong>{{ auth()->user()->name }}</strong><br>
                        <small class="text-muted"><i class="fas fa-at"></i> {{ auth()->user()->username }}</small>
                    </li>
                    @php $banner = \App\Services\ImpersonationService::getBannerInfo(); @endphp
                    @if ($banner)
                    <li>
                        <a href="{{ $banner['switch_back_url'] }}" class="dropdown-item text-danger fw-bold">
                            <i class="far fa-arrow-left text-danger me-2"></i> {{ __('Switch Back to ') . $banner['impersonator_role_label'] }}
                        </a>
                    </li>
                    @endif
                    <li>
                        <a class="dropdown-item" href="{{ route('sp.white-label.index') }}">
                            <i class="fal fa-paint-brush me-2"></i> {{ __('White Label') }}
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" class="dropdown-item logoutButton">
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
