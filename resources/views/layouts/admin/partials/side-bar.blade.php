<nav class="side-bar">
    <div class="side-bar-logo">
        <a href="javascript:void(0)">
            <img src="{{ asset(get_option('general')['logo'] ?? 'assets/images/logo/backend_logo.png') }}" alt="Logo">
        </a>
        <button class="close-btn"><i class="fal fa-times"></i></button>
    </div>
    <div class="side-bar-manu">
        <ul>
            @can(['dashboard-read'])
                <li class="{{ Request::routeIs('admin.dashboard.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard.index') }}" class="active">
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="16" width="18"
                                viewBox="0 0 576 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.-->
                                <path
                                    d="M288 32C128.9 32 0 160.9 0 320c0 52.8 14.3 102.3 39.1 144.8 5.6 9.6 16.3 15.2 27.4 15.2h443c11.1 0 21.8-5.6 27.4-15.2C561.8 422.3 576 372.8 576 320c0-159.1-128.9-288-288-288zm0 64c14.7 0 26.6 10.1 30.3 23.7-1.1 2.3-2.6 4.2-3.5 6.7l-9.2 27.7c-5.1 3.5-11 6-17.6 6-17.7 0-32-14.3-32-32S270.3 96 288 96zM96 384c-17.7 0-32-14.3-32-32s14.3-32 32-32 32 14.3 32 32-14.3 32-32 32zm48-160c-17.7 0-32-14.3-32-32s14.3-32 32-32 32 14.3 32 32-14.3 32-32 32zm246.8-72.4l-61.3 184C343.1 347.3 352 364.5 352 384c0 11.7-3.4 22.6-8.9 32H232.9c-5.5-9.5-8.9-20.3-8.9-32 0-33.9 26.5-61.4 59.9-63.6l61.3-184c4.2-12.6 17.7-19.5 30.4-15.2 12.6 4.2 19.4 17.8 15.2 30.4zm14.7 57.2l15.5-46.6c3.5-1.3 7.1-2.2 11.1-2.2 17.7 0 32 14.3 32 32s-14.3 32-32 32c-11.4 0-20.9-6.3-26.6-15.2zM480 384c-17.7 0-32-14.3-32-32s14.3-32 32-32 32 14.3 32 32-14.3 32-32 32z" />
                            </svg>
                        </span>
                        {{ __('Dashboard') }}
                    </a>
                </li>
            @endcan

            @can('orders-read')
            <li class="{{ Request::routeIs('admin.subscribers.index', 'admin.subscribers.show') ? 'active' : ''}}">
                <a href="{{ route('admin.subscribers.index') }}" class="active">
                    <span class="sidebar-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M107.3 36.7a16 16 0 0 0 -22.6 0l-80 96C-5.4 142.7 1.8 160 16 160h48v304a16 16 0 0 0 16 16h32a16 16 0 0 0 16-16V160h48c14.2 0 21.4-17.2 11.3-27.3zM400 416h-16V304a16 16 0 0 0 -16-16h-48a16 16 0 0 0 -14.3 8.8l-16 32A16 16 0 0 0 304 352h16v64h-16a16 16 0 0 0 -16 16v32a16 16 0 0 0 16 16h96a16 16 0 0 0 16-16v-32a16 16 0 0 0 -16-16zM330.2 34.9a79 79 0 0 0 -55 54.2c-14.3 51.1 21.2 97.8 68.8 102.5a84.1 84.1 0 0 1 -20.9 12.9c-7.6 3.4-10.8 12.5-8.2 20.3l9.9 20c2.9 8.6 12.5 13.5 20.9 9.9 58-24.8 86.3-61.6 86.3-132V112c0-51.2-48.4-91.3-101.9-77.1zM352 132a20 20 0 1 1 20-20 20 20 0 0 1 -20 20z"/></svg>
                    </span>
                    {{ __('Subscription Orders') }}
                </a>
            </li>
            @endcan

            @can('recharges-read')
            <li class="{{ Request::routeIs('admin.recharges.index', 'admin.recharges.show') ? 'active' : ''}}">
                <a href="{{ route('admin.recharges.index') }}" class="active">
                    <span class="sidebar-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" height="16" width="12" viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.--><path d="M377 105L279.1 7c-4.5-4.5-10.6-7-17-7H256v128h128v-6.1c0-6.3-2.5-12.4-7-16.9zm-153 31V0H24C10.7 0 0 10.7 0 24v464c0 13.3 10.7 24 24 24h336c13.3 0 24-10.7 24-24V160H248c-13.2 0-24-10.8-24-24zM64 72c0-4.4 3.6-8 8-8h80c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H72c-4.4 0-8-3.6-8-8V72zm0 80v-16c0-4.4 3.6-8 8-8h80c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H72c-4.4 0-8-3.6-8-8zm144 263.9V440c0 4.4-3.6 8-8 8h-16c-4.4 0-8-3.6-8-8v-24.3c-11.3-.6-22.3-4.5-31.4-11.4-3.9-2.9-4.1-8.8-.6-12.1l11.8-11.2c2.8-2.6 6.9-2.8 10.1-.7 3.9 2.4 8.3 3.7 12.8 3.7h28.1c6.5 0 11.8-5.9 11.8-13.2 0-6-3.6-11.2-8.8-12.7l-45-13.5c-18.6-5.6-31.6-23.4-31.6-43.4 0-24.5 19.1-44.4 42.7-45.1V232c0-4.4 3.6-8 8-8h16c4.4 0 8 3.6 8 8v24.3c11.3 .6 22.3 4.5 31.4 11.4 3.9 2.9 4.1 8.8 .6 12.1l-11.8 11.2c-2.8 2.6-6.9 2.8-10.1 .7-3.9-2.4-8.3-3.7-12.8-3.7h-28.1c-6.5 0-11.8 5.9-11.8 13.2 0 6 3.6 11.2 8.8 12.7l45 13.5c18.6 5.6 31.6 23.4 31.6 43.4 0 24.5-19.1 44.4-42.7 45.1z"/></svg>
                    </span>
                    {{ __('Recharge Payments') }}
                </a>
            </li>
            @endcan

            @canany(['plans-read', 'plans-create'])
                <li
                    class="dropdown {{ Route::is('admin.plans.index', 'admin.plans.create', 'admin.plans.edit') ? 'active' : '' }}">
                    <a href="#">
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="16" width="10"
                                viewBox="0 0 320 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.-->
                                <path
                                    d="M208 96c26.5 0 48-21.5 48-48S234.5 0 208 0s-48 21.5-48 48 21.5 48 48 48zm94.5 149.1l-23.3-11.8-9.7-29.4c-14.7-44.6-55.7-75.8-102.2-75.9-36-.1-55.9 10.1-93.3 25.2-21.6 8.7-39.3 25.2-49.7 46.2L17.6 213c-7.8 15.8-1.5 35 14.2 42.9 15.6 7.9 34.6 1.5 42.5-14.3L81 228c3.5-7 9.3-12.5 16.5-15.4l26.8-10.8-15.2 60.7c-5.2 20.8 .4 42.9 14.9 58.8l59.9 65.4c7.2 7.9 12.3 17.4 14.9 27.7l18.3 73.3c4.3 17.1 21.7 27.6 38.8 23.3 17.1-4.3 27.6-21.7 23.3-38.8l-22.2-89c-2.6-10.3-7.7-19.9-14.9-27.7l-45.5-49.7 17.2-68.7 5.5 16.5c5.3 16.1 16.7 29.4 31.7 37l23.3 11.8c15.6 7.9 34.6 1.5 42.5-14.3 7.7-15.7 1.4-35.1-14.3-43zM73.6 385.8c-3.2 8.1-8 15.4-14.2 21.5l-50 50.1c-12.5 12.5-12.5 32.8 0 45.3s32.7 12.5 45.2 0l59.4-59.4c6.1-6.1 10.9-13.4 14.2-21.5l13.5-33.8c-55.3-60.3-38.7-41.8-47.4-53.7l-20.7 51.5z" />
                            </svg>
                        </span>
                        {{ __('Manage Plans') }}
                    </a>
                    <ul>
                        @can('plans-create')
                            <li><a class="{{ Route::is('admin.plans.create') ? 'active' : '' }}" href="{{ route('admin.plans.create') }}">{{ __('Create Plan') }}</a></li>
                        @endcan
                        @can('plans-read')
                            <li><a class="{{ Route::is('admin.plans.index', 'admin.plans.edit') ? 'active' : '' }}" href="{{ route('admin.plans.index') }}">{{ __('Manage Plans') }}</a></li>
                        @endcan
                    </ul>
                </li>
            @endcanany

            @can(['users-read'])
                <li class="dropdown {{ request('users') == 'user' ? 'active' : '' }}">
                    <a href="#"><span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="16" width="20"
                                viewBox="0 0 640 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.-->
                                <path d="M96 224c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm448 0c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm32 32h-64c-17.6 0-33.5 7.1-45.1 18.6 40.3 22.1 68.9 62 75.1 109.4h66c17.7 0 32-14.3 32-32v-32c0-35.3-28.7-64-64-64zm-256 0c61.9 0 112-50.1 112-112S381.9 32 320 32 208 82.1 208 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C179.6 288 128 339.6 128 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zm-223.7-13.4C161.5 263.1 145.6 256 128 256H64c-35.3 0-64 28.7-64 64v32c0 17.7 14.3 32 32 32h65.9c6.3-47.4 34.9-87.3 75.2-109.4z" />
                            </svg>
                        </span>
                        {{ __('Manage Customers') }} </a>
                    <ul>
                        <li>
                            <a class="{{ Route::is('admin.users.create') && request('users') == 'user' ? 'active' : '' }}" href="{{ route('admin.users.create', ['users' => 'user']) }}">{{ __('Create Customer') }}</a>
                        </li>
                        <li>
                            <a class="{{ Route::is('admin.users.index', 'admin.users.edit') && request('users') == 'user' ? 'active' : '' }}" href="{{ route('admin.users.index', ['users' => 'user']) }}">{{ __('Manage Customers') }}</a>
                        </li>
                    </ul>
                </li>
            @endcan

            @can(['senderids-read'])
                <li class="dropdown {{ Route::is('admin.senderids.index', 'admin.senderids.create', 'admin.senderids.edit') ? 'active' : '' }}">
                    <a href="#">
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="16" width="12" viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.--><path d="M372 0h-79c-10.7 0-16 12.9-8.5 20.5l16.9 16.9-80.7 80.7C198.5 104.1 172.2 96 144 96 64.5 96 0 160.5 0 240c0 68.5 47.9 125.9 112 140.4V408H76c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h36v28c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12v-28h36c6.6 0 12-5.4 12-12v-40c0-6.6-5.4-12-12-12h-36v-27.6c64.1-14.6 112-71.9 112-140.4 0-28.2-8.1-54.5-22.1-76.7l80.7-80.7 16.9 16.9c7.6 7.6 20.5 2.2 20.5-8.5V12c0-6.6-5.4-12-12-12zM144 320c-44.1 0-80-35.9-80-80s35.9-80 80-80 80 35.9 80 80-35.9 80-80 80z"/></svg>
                        </span>
                        {{ __('Sender Ids') }}
                    </a>
                    <ul>
                        @can('senderids-read')
                        <li><a class="{{ Route::is('admin.senderids.create') ? 'active' : '' }}" href="{{ route('admin.senderids.create') }}">{{__('Create Senderid')}}</a></li>
                        @endcan
                        @can('senderids-create')
                        <li><a class="{{ Route::is('admin.senderids.index', 'admin.senderids.edit') ? 'active' : '' }}" href="{{ route('admin.senderids.index') }}">{{__('Manage Senderids')}}</a></li>
                        @endcan
                    </ul>
                </li>
            @endcan

            @can(['campaigns-read'])
                <li class="dropdown {{ Route::is('admin.campaigns.index', 'admin.campaigns.create', 'admin.campaigns.edit', 'admin.campaigns_sms.index') ? 'active' : '' }}">
                    <a href="#">
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="16" width="15.5" viewBox="0 0 496 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.--><path d="M247.6 8C389.4 8 496 118.1 496 256c0 147.1-118.5 248-248.4 248C113.6 504 0 394.5 0 256 0 123.1 104.7 8 247.6 8zm.8 44.7C130.2 52.7 44.7 150.6 44.7 256c0 109.8 91.2 202.8 203.7 202.8 103.2 0 202.8-81.1 202.8-202.8 .1-113.8-90.2-203.3-202.8-203.3zm107 205.6c-4.7 0-9 2.8-10.7 7.2l-4 9.5-11-92.8c-1.7-13.9-22-13.4-23.1 .4l-4.3 51.4-5.2-68.8c-1.1-14.3-22.1-14.2-23.2 0l-3.5 44.9-5.9-94.3c-.9-14.5-22.3-14.4-23.2 0l-5.1 83.7-4.3-66.3c-.9-14.4-22.2-14.4-23.2 0l-5.3 80.2-4.1-57c-1.1-14.3-22-14.3-23.2-.2l-7.7 89.8-1.8-12.2c-1.7-11.4-17.1-13.6-22-3.3l-13.2 27.7H87.5v23.2h51.3c4.4 0 8.4-2.5 10.4-6.4l10.7 73.1c2 13.5 21.9 13 23.1-.7l3.8-43.6 5.7 78.3c1.1 14.4 22.3 14.2 23.2-.1l4.6-70.4 4.8 73.3c.9 14.4 22.3 14.4 23.2-.1l4.9-80.5 4.5 71.8c.9 14.3 22.1 14.5 23.2 .2l4.6-58.6 4.9 64.4c1.1 14.3 22 14.2 23.1 .1l6.8-83 2.7 22.3c1.4 11.8 17.7 14.1 22.3 3.1l18-43.4h50.5V258l-58.4 .3zm-78 5.2h-21.9v21.9c0 4.1-3.3 7.5-7.5 7.5-4.1 0-7.5-3.3-7.5-7.5v-21.9h-21.9c-4.1 0-7.5-3.3-7.5-7.5 0-4.1 3.4-7.5 7.5-7.5h21.9v-21.9c0-4.1 3.4-7.5 7.5-7.5s7.5 3.3 7.5 7.5v21.9h21.9c4.1 0 7.5 3.3 7.5 7.5 0 4.1-3.4 7.5-7.5 7.5z"/></svg>
                        </span>
                        {{ __('Campaigns') }}
                    </a>
                    <ul>
                        @can('campaigns-create')
                        <li><a class="{{ Route::is('admin.campaigns.create') ? 'active' : '' }}" href="{{ route('admin.campaigns.create') }}">{{ __('Create Campaign') }}</a></li>
                        @endcan

                        @can('campaigns-read')
                        <li><a class="{{ Route::is('admin.campaigns.index', 'admin.campaigns.edit') ? 'active' : '' }}" href="{{ route('admin.campaigns.index') }}">{{__('Campaign Groups')}}</a></li>
                        @endcan

                        @can('campaigns_sms-read')
                        <li><a class="{{ Route::is('admin.campaigns_sms.index') ? 'active' : '' }}" href="{{ route('admin.campaigns_sms.index') }}">{{__('Campaign Requests')}}</a></li>
                        @endcan
                    </ul>
                </li>
            @endcan

            @canany(['smsgateways-read', 'smsgateways-create'])

            @endcanany
            <li class="dropdown {{ Route::is('admin.sms-gateways.index', 'admin.sms-gateways.create', 'admin.sms-gateways.edit', 'admin.gateways-types.index', 'admin.gateways-types.edit') ? 'active' : '' }}">
                <a href="#">
                    <span class="sidebar-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.--><path d="M256 32C114.6 32 0 125.1 0 240c0 49.6 21.4 95 57 130.7C44.5 421.1 2.7 466 2.2 466.5c-2.2 2.3-2.8 5.7-1.5 8.7 1.3 3 4.1 4.8 7.3 4.8 66.3 0 116-31.8 140.6-51.4 32.7 12.3 69 19.4 107.4 19.4 141.4 0 256-93.1 256-208S397.4 32 256 32zM128.2 304H116c-4.4 0-8-3.6-8-8v-16c0-4.4 3.6-8 8-8h12.3c6 0 10.4-3.5 10.4-6.6 0-1.3-.8-2.7-2.1-3.8l-21.9-18.8c-8.5-7.2-13.3-17.5-13.3-28.1 0-21.3 19-38.6 42.4-38.6H156c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8h-12.3c-6 0-10.4 3.5-10.4 6.6 0 1.3 .8 2.7 2.1 3.8l21.9 18.8c8.5 7.2 13.3 17.5 13.3 28.1 .1 21.3-19 38.6-42.4 38.6zm191.8-8c0 4.4-3.6 8-8 8h-16c-4.4 0-8-3.6-8-8v-68.2l-24.8 55.8c-2.9 5.9-11.4 5.9-14.3 0L224 227.8V296c0 4.4-3.6 8-8 8h-16c-4.4 0-8-3.6-8-8V192c0-8.8 7.2-16 16-16h16c6.1 0 11.6 3.4 14.3 8.8l17.7 35.4 17.7-35.4c2.7-5.4 8.3-8.8 14.3-8.8h16c8.8 0 16 7.2 16 16v104zm48.3 8H356c-4.4 0-8-3.6-8-8v-16c0-4.4 3.6-8 8-8h12.3c6 0 10.4-3.5 10.4-6.6 0-1.3-.8-2.7-2.1-3.8l-21.9-18.8c-8.5-7.2-13.3-17.5-13.3-28.1 0-21.3 19-38.6 42.4-38.6H396c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8h-12.3c-6 0-10.4 3.5-10.4 6.6 0 1.3 .8 2.7 2.1 3.8l21.9 18.8c8.5 7.2 13.3 17.5 13.3 28.1 .1 21.3-18.9 38.6-42.3 38.6z"/></svg>
                    </span>
                    {{ __('SMS Gateways') }}
                </a>
                <ul>
                    @can('smsgateways-create')
                    <li><a class="{{ Route::is('admin.sms-gateways.create') ? 'active' : '' }}" href="{{ route('admin.sms-gateways.create') }}">{{ __('Add New') }}</a></li>
                    @endcan
                    @can('smsgateways-read')
                    <li><a class="{{ Route::is('admin.sms-gateways.index', 'admin.sms-gateways.edit') ? 'active' : '' }}" href="{{ route('admin.sms-gateways.index') }}">{{ __('View List') }}</a></li>
                    @endcan
                </ul>
            </li>

            @can(['users-read'])
                <li class="dropdown {{ request('users') == 'admin' ? 'active' : '' }}">
                    <a href="#"><span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="16" width="20"
                                viewBox="0 0 640 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.-->
                                <path d="M610.5 341.3c2.6-14.1 2.6-28.5 0-42.6l25.8-14.9c3-1.7 4.3-5.2 3.3-8.5-6.7-21.6-18.2-41.2-33.2-57.4-2.3-2.5-6-3.1-9-1.4l-25.8 14.9c-10.9-9.3-23.4-16.5-36.9-21.3v-29.8c0-3.4-2.4-6.4-5.7-7.1-22.3-5-45-4.8-66.2 0-3.3 .7-5.7 3.7-5.7 7.1v29.8c-13.5 4.8-26 12-36.9 21.3l-25.8-14.9c-2.9-1.7-6.7-1.1-9 1.4-15 16.2-26.5 35.8-33.2 57.4-1 3.3 .4 6.8 3.3 8.5l25.8 14.9c-2.6 14.1-2.6 28.5 0 42.6l-25.8 14.9c-3 1.7-4.3 5.2-3.3 8.5 6.7 21.6 18.2 41.1 33.2 57.4 2.3 2.5 6 3.1 9 1.4l25.8-14.9c10.9 9.3 23.4 16.5 36.9 21.3v29.8c0 3.4 2.4 6.4 5.7 7.1 22.3 5 45 4.8 66.2 0 3.3-.7 5.7-3.7 5.7-7.1v-29.8c13.5-4.8 26-12 36.9-21.3l25.8 14.9c2.9 1.7 6.7 1.1 9-1.4 15-16.2 26.5-35.8 33.2-57.4 1-3.3-.4-6.8-3.3-8.5l-25.8-14.9zM496 368.5c-26.8 0-48.5-21.8-48.5-48.5s21.8-48.5 48.5-48.5 48.5 21.8 48.5 48.5-21.7 48.5-48.5 48.5zM96 224c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm224 32c1.9 0 3.7-.5 5.6-.6 8.3-21.7 20.5-42.1 36.3-59.2 7.4-8 17.9-12.6 28.9-12.6 6.9 0 13.7 1.8 19.6 5.3l7.9 4.6c.8-.5 1.6-.9 2.4-1.4 7-14.6 11.2-30.8 11.2-48 0-61.9-50.1-112-112-112S208 82.1 208 144c0 61.9 50.1 112 112 112zm105.2 194.5c-2.3-1.2-4.6-2.6-6.8-3.9-8.2 4.8-15.3 9.8-27.5 9.8-10.9 0-21.4-4.6-28.9-12.6-18.3-19.8-32.3-43.9-40.2-69.6-10.7-34.5 24.9-49.7 25.8-50.3-.1-2.6-.1-5.2 0-7.8l-7.9-4.6c-3.8-2.2-7-5-9.8-8.1-3.3 .2-6.5 .6-9.8 .6-24.6 0-47.6-6-68.5-16h-8.3C179.6 288 128 339.6 128 403.2V432c0 26.5 21.5 48 48 48h255.4c-3.7-6-6.2-12.8-6.2-20.3v-9.2zM173.1 274.6C161.5 263.1 145.6 256 128 256H64c-35.3 0-64 28.7-64 64v32c0 17.7 14.3 32 32 32h65.9c6.3-47.4 34.9-87.3 75.2-109.4z" />
                            </svg>
                        </span>
                        {{ __('Manage Staffs') }} </a>
                    <ul>
                        <li><a class="{{ Route::is('admin.users.create') && request('users') == 'admin' ? 'active' : '' }}"
                                href="{{ route('admin.users.create', ['users' => 'admin']) }}">{{ __('Create Staff') }}</a>
                        </li>
                        <li><a class="{{ Route::is('admin.users.index', 'admin.users.edit') && request('users') == 'admin' ? 'active' : '' }}"
                                href="{{ route('admin.users.index', ['users' => 'admin']) }}">{{ __('Manage Staffs') }}</a>
                        </li>
                    </ul>
                </li>
            @endcan

            @can(['reports-read'])
                <li
                    class="dropdown {{ Route::is('admin.reports.quick-sms', 'admin.reports.campaigns-sms', 'admin.reports.subscriptions', 'admin.reports.recharges', 'admin.reports.transactions', 'admin.reports.group-sms') ? 'active' : '' }}">
                    <a href="#">
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="16" width="14"
                                viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.-->
                                <path
                                    d="M48 24C48 10.7 37.3 0 24 0S0 10.7 0 24V64 350.5 400v88c0 13.3 10.7 24 24 24s24-10.7 24-24V388l80.3-20.1c41.1-10.3 84.6-5.5 122.5 13.4c44.2 22.1 95.5 24.8 141.7 7.4l34.7-13c12.5-4.7 20.8-16.6 20.8-30V66.1c0-23-24.2-38-44.8-27.7l-9.6 4.8c-46.3 23.2-100.8 23.2-147.1 0c-35.1-17.6-75.4-22-113.5-12.5L48 52V24zm0 77.5l96.6-24.2c27-6.7 55.5-3.6 80.4 8.8c54.9 27.4 118.7 29.7 175 6.8V334.7l-24.4 9.1c-33.7 12.6-71.2 10.7-103.4-5.4c-48.2-24.1-103.3-30.1-155.6-17.1L48 338.5v-237z" />
                            </svg>
                        </span>
                        {{ __('Reports') }}
                    </a>
                    <ul>
                        <li><a class="{{ Route::is('admin.reports.quick-sms') ? 'active' : '' }}" href="{{ route('admin.reports.quick-sms') }}">{{ __('Quick Sms') }}</a></li>

                        <li><a class="{{ Route::is('admin.reports.group-sms') ? 'active' : '' }}" href="{{ route('admin.reports.group-sms') }}">{{ __('Group Sms') }}</a></li>

                        <li><a class="{{ Route::is('admin.reports.campaigns-sms') ? 'active' : '' }}" href="{{ route('admin.reports.campaigns-sms') }}">{{ __('Campaigns Sms') }}</a></li>

                        <li><a class="{{ Route::is('admin.reports.subscriptions') ? 'active' : '' }}" href="{{ route('admin.reports.subscriptions') }}">{{ __('Subscriptions') }}</a></li>

                        <li><a class="{{ Route::is('admin.reports.recharges') ? 'active' : '' }}" href="{{ route('admin.reports.recharges') }}">{{ __('Recharges') }}</a></li>

                        <li><a class="{{ Route::is('admin.reports.transactions') ? 'active' : '' }}" href="{{ route('admin.reports.transactions') }}">{{ __('Transactions') }}</a></li>
                    </ul>
                </li>
            @endcan

            @canany(['settings-read', 'notifications-read', 'currencies-read', 'sms-read', 'services-read', 'blogs-read', 'newsletters-read'])
                <li class="dropdown {{ Request::routeIs('admin.website-settings.index','admin.faqs.create','admin.faqs.destroy','admin.faqs.edit','admin.faqs.index', 'admin.testimonials.index', 'admin.testimonials.create', 'admin.testimonials.edit', 'admin.services.index', 'admin.services.create', 'admin.services.edit','admin.blogs.index', 'admin.blogs.create', 'admin.blogs.edit','admin.blogs.filter.comment', 'admin.newsletters.index') ? 'active' : '' }}">
                    <a href="#">
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="16" width="20"
                                viewBox="0 0 640 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.-->
                                <path d="M512.1 191l-8.2 14.3c-3 5.3-9.4 7.5-15.1 5.4-11.8-4.4-22.6-10.7-32.1-18.6-4.6-3.8-5.8-10.5-2.8-15.7l8.2-14.3c-6.9-8-12.3-17.3-15.9-27.4h-16.5c-6 0-11.2-4.3-12.2-10.3-2-12-2.1-24.6 0-37.1 1-6 6.2-10.4 12.2-10.4h16.5c3.6-10.1 9-19.4 15.9-27.4l-8.2-14.3c-3-5.2-1.9-11.9 2.8-15.7 9.5-7.9 20.4-14.2 32.1-18.6 5.7-2.1 12.1 .1 15.1 5.4l8.2 14.3c10.5-1.9 21.2-1.9 31.7 0L552 6.3c3-5.3 9.4-7.5 15.1-5.4 11.8 4.4 22.6 10.7 32.1 18.6 4.6 3.8 5.8 10.5 2.8 15.7l-8.2 14.3c6.9 8 12.3 17.3 15.9 27.4h16.5c6 0 11.2 4.3 12.2 10.3 2 12 2.1 24.6 0 37.1-1 6-6.2 10.4-12.2 10.4h-16.5c-3.6 10.1-9 19.4-15.9 27.4l8.2 14.3c3 5.2 1.9 11.9-2.8 15.7-9.5 7.9-20.4 14.2-32.1 18.6-5.7 2.1-12.1-.1-15.1-5.4l-8.2-14.3c-10.4 1.9-21.2 1.9-31.7 0zm-10.5-58.8c38.5 29.6 82.4-14.3 52.8-52.8-38.5-29.7-82.4 14.3-52.8 52.8zM386.3 286.1l33.7 16.8c10.1 5.8 14.5 18.1 10.5 29.1-8.9 24.2-26.4 46.4-42.6 65.8-7.4 8.9-20.2 11.1-30.3 5.3l-29.1-16.8c-16 13.7-34.6 24.6-54.9 31.7v33.6c0 11.6-8.3 21.6-19.7 23.6-24.6 4.2-50.4 4.4-75.9 0-11.5-2-20-11.9-20-23.6V418c-20.3-7.2-38.9-18-54.9-31.7L74 403c-10 5.8-22.9 3.6-30.3-5.3-16.2-19.4-33.3-41.6-42.2-65.7-4-10.9 .4-23.2 10.5-29.1l33.3-16.8c-3.9-20.9-3.9-42.4 0-63.4L12 205.8c-10.1-5.8-14.6-18.1-10.5-29 8.9-24.2 26-46.4 42.2-65.8 7.4-8.9 20.2-11.1 30.3-5.3l29.1 16.8c16-13.7 34.6-24.6 54.9-31.7V57.1c0-11.5 8.2-21.5 19.6-23.5 24.6-4.2 50.5-4.4 76-.1 11.5 2 20 11.9 20 23.6v33.6c20.3 7.2 38.9 18 54.9 31.7l29.1-16.8c10-5.8 22.9-3.6 30.3 5.3 16.2 19.4 33.2 41.6 42.1 65.8 4 10.9 .1 23.2-10 29.1l-33.7 16.8c3.9 21 3.9 42.5 0 63.5zm-117.6 21.1c59.2-77-28.7-164.9-105.7-105.7-59.2 77 28.7 164.9 105.7 105.7zm243.4 182.7l-8.2 14.3c-3 5.3-9.4 7.5-15.1 5.4-11.8-4.4-22.6-10.7-32.1-18.6-4.6-3.8-5.8-10.5-2.8-15.7l8.2-14.3c-6.9-8-12.3-17.3-15.9-27.4h-16.5c-6 0-11.2-4.3-12.2-10.3-2-12-2.1-24.6 0-37.1 1-6 6.2-10.4 12.2-10.4h16.5c3.6-10.1 9-19.4 15.9-27.4l-8.2-14.3c-3-5.2-1.9-11.9 2.8-15.7 9.5-7.9 20.4-14.2 32.1-18.6 5.7-2.1 12.1 .1 15.1 5.4l8.2 14.3c10.5-1.9 21.2-1.9 31.7 0l8.2-14.3c3-5.3 9.4-7.5 15.1-5.4 11.8 4.4 22.6 10.7 32.1 18.6 4.6 3.8 5.8 10.5 2.8 15.7l-8.2 14.3c6.9 8 12.3 17.3 15.9 27.4h16.5c6 0 11.2 4.3 12.2 10.3 2 12 2.1 24.6 0 37.1-1 6-6.2 10.4-12.2 10.4h-16.5c-3.6 10.1-9 19.4-15.9 27.4l8.2 14.3c3 5.2 1.9 11.9-2.8 15.7-9.5 7.9-20.4 14.2-32.1 18.6-5.7 2.1-12.1-.1-15.1-5.4l-8.2-14.3c-10.4 1.9-21.2 1.9-31.7 0zM501.6 431c38.5 29.6 82.4-14.3 52.8-52.8-38.5-29.6-82.4 14.3-52.8 52.8z" />
                            </svg>
                        </span>
                        {{ __('Website Settings') }}
                    </a>
                    <ul>
                        @can('settings-read')
                        <li>
                            <a class="{{ Request::routeIs('admin.website-settings.index') ? 'active' : '' }}" href="{{ route('admin.website-settings.index') }}">{{ __('Manage Pages') }}</a>
                        </li>
                        @endcan

                        @can('faqs-read')
                        <li>
                            <a class="{{ Request::routeIs('admin.faqs.create', 'admin.faqs.destroy', 'admin.faqs.edit','admin.faqs.index') ? 'active' : '' }}" href="{{ route('admin.faqs.index') }}">{{ __('Manage FAQs') }}</a>
                        </li>
                        @endcan

                        @can('testimonials-read')
                        <li>
                            <a class="{{ Request::routeIs('admin.testimonials.index', 'admin.testimonials.create', 'admin.testimonials.edit') ? 'active' : '' }}" href="{{ route('admin.testimonials.index') }}">{{ __('Testimonials') }}</a>
                        </li>
                        @endcan

                        @can('services-read')
                        <li>
                            <a class="{{ Request::routeIs('admin.services.index', 'admin.services.create', 'admin.services.edit') ? 'active' : '' }}" href="{{ route('admin.services.index') }}">{{ __('Services') }}</a>
                        </li>
                        @endcan
                        @can('blogs-read')
                        <li>
                            <a class="{{ Request::routeIs('admin.blogs.index', 'admin.blogs.create', 'admin.blogs.edit', 'admin.blogs.filter.comment') ? 'active' : '' }}" href="{{ route('admin.blogs.index') }}">{{__('Manage Blogs')}}</a>
                        </li>
                        @endcan
                        @can('newsletters-read')
                            <li>
                                <a class="{{ Request::routeIs('admin.newsletters.index') ? 'active' : '' }}" href="{{ route('admin.newsletters.index') }}">{{__('Newsletters')}}</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany

            @canany(['roles-read', 'permissions-read'])
                <li
                    class="dropdown {{ Request::routeIs('admin.roles.index', 'admin.roles.create', 'admin.roles.edit', 'admin.permissions.index') ? 'active' : '' }}">
                    <a href="#">
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="16" width="20"
                                viewBox="0 0 640 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.-->
                                <path d="M630.6 364.9l-90.3-90.2c-12-12-28.3-18.7-45.3-18.7h-79.3c-17.7 0-32 14.3-32 32v79.2c0 17 6.7 33.2 18.7 45.2l90.3 90.2c12.5 12.5 32.8 12.5 45.3 0l92.5-92.5c12.6-12.5 12.6-32.7 .1-45.2zm-182.8-21c-13.3 0-24-10.7-24-24s10.7-24 24-24 24 10.7 24 24c0 13.2-10.7 24-24 24zm-223.8-88c70.7 0 128-57.3 128-128C352 57.3 294.7 0 224 0S96 57.3 96 128c0 70.6 57.3 127.9 128 127.9zm127.8 111.2V294c-12.2-3.6-24.9-6.2-38.2-6.2h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7C60.2 287.9 0 348.1 0 422.3v41.6c0 26.5 21.5 48 48 48h352c15.5 0 29.1-7.5 37.9-18.9l-58-58c-18.1-18.1-28.1-42.2-28.1-67.9z" />
                            </svg>
                        </span>
                        {{ __('Roles & Permissions') }}
                    </a>
                    <ul>
                        @can('roles-read')
                            <li>
                                <a class="{{ Request::routeIs('admin.roles.index', 'admin.roles.create', 'admin.roles.edit') ? 'active' : '' }}"
                                    href="{{ route('admin.roles.index') }}">
                                    {{ __('Roles') }}
                                </a>
                            </li>
                        @endcan

                        @can('permissions-read')
                            <li>
                                <a class="{{ Request::routeIs('admin.permissions.index') ? 'active' : '' }}"
                                    href="{{ route('admin.permissions.index') }}">
                                    {{ __('Permissions') }}
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany

            @canany(['settings-read', 'notifications-read', 'currencies-read', 'sms-read'])
                <li class="dropdown {{ Request::routeIs('admin.settings.index', 'admin.notifications.index', 'admin.system-settings.index', 'admin.currencies.index', 'admin.sms-settings.index', 'admin.currencies.create', 'admin.currencies.edit', 'admin.gateways.index', 'admin.cron-jobs.index', 'admin.notice.index') ? 'active' : '' }}">
                    <a href="#">
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M487.4 315.7l-42.6-24.6c4.3-23.2 4.3-47 0-70.2l42.6-24.6c4.9-2.8 7.1-8.6 5.5-14-11.1-35.6-30-67.8-54.7-94.6-3.8-4.1-10-5.1-14.8-2.3L380.8 110c-17.9-15.4-38.5-27.3-60.8-35.1V25.8c0-5.6-3.9-10.5-9.4-11.7-36.7-8.2-74.3-7.8-109.2 0-5.5 1.2-9.4 6.1-9.4 11.7V75c-22.2 7.9-42.8 19.8-60.8 35.1L88.7 85.5c-4.9-2.8-11-1.9-14.8 2.3-24.7 26.7-43.6 58.9-54.7 94.6-1.7 5.4 .6 11.2 5.5 14L67.3 221c-4.3 23.2-4.3 47 0 70.2l-42.6 24.6c-4.9 2.8-7.1 8.6-5.5 14 11.1 35.6 30 67.8 54.7 94.6 3.8 4.1 10 5.1 14.8 2.3l42.6-24.6c17.9 15.4 38.5 27.3 60.8 35.1v49.2c0 5.6 3.9 10.5 9.4 11.7 36.7 8.2 74.3 7.8 109.2 0 5.5-1.2 9.4-6.1 9.4-11.7v-49.2c22.2-7.9 42.8-19.8 60.8-35.1l42.6 24.6c4.9 2.8 11 1.9 14.8-2.3 24.7-26.7 43.6-58.9 54.7-94.6 1.5-5.5-.7-11.3-5.6-14.1zM256 336c-44.1 0-80-35.9-80-80s35.9-80 80-80 80 35.9 80 80-35.9 80-80 80z"/></svg>
                        </span>
                        {{ __('Settings') }}
                    </a>
                    <ul>
                        @can('currencies-read')
                            <li><a class="{{ Request::routeIs('admin.currencies.index', 'admin.currencies.create', 'admin.currencies.edit') ? 'active' : '' }}" href="{{ route('admin.currencies.index') }}">{{ __('Currency') }}</a></li>
                        @endcan

                        @can('cron-jobs-read')
                            <li><a class="{{ Request::routeIs('admin.cron-jobs.index') ? 'active' : '' }}" href="{{ route('admin.cron-jobs.index') }}">{{ __('Cron Jobs') }}</a></li>
                        @endcan

                        @can('notice-read')
                            <li><a class="{{ Request::routeIs('admin.notice.index') ? 'active' : '' }}" href="{{ route('admin.notice.index') }}">{{ __('Notice Alert') }}</a></li>
                        @endcan

                        @can('sms-settings-read')
                            <li>
                                <a class="{{ Request::routeIs('admin.sms-settings.index') ? 'active' : '' }}" href="{{ route('admin.sms-settings.index') }}">{{ __('Sms Settings') }}</a>
                            </li>
                        @endcan

                        @can('notifications-read')
                            <li>
                                <a class="{{ Request::routeIs('admin.notifications.index') ? 'active' : '' }}"
                                    href="{{ route('admin.notifications.index') }}">
                                    {{ __('Notifications') }}
                                </a>
                            </li>
                        @endcan

                        @can('gateways-read')
                            <li>
                                <a class="{{ Request::routeIs('admin.gateways.index') ? 'active' : '' }}"
                                    href="{{ route('admin.gateways.index') }}">
                                    {{ __('Payment Gateways') }}
                                </a>
                            </li>
                        @endcan

                        @can('settings-read')
                            <li>
                                <a class="{{ Request::routeIs('admin.system-settings.index') ? 'active' : '' }}"
                                    href="{{ route('admin.system-settings.index') }}">{{ __('System Settings') }}</a>
                            </li>
                            <li>
                                <a class="{{ Request::routeIs('admin.settings.index') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">{{ __('General Settings') }}</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany
        </ul>
    </div>
</nav>
