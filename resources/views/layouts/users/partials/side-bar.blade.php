<nav class="side-bar">
    <div class="side-bar-logo">
        <a href="javascript:void(0)"><img src="{{ asset( get_option('general')['logo'] ?? 'assets/images/logo/backend_logo.png') }}" alt="Logo"></a>
        <button class="close-btn"><i class="fal fa-times"></i></button>
    </div>
    <div class="side-bar-manu">
        <ul>
            <li class="{{ Request::routeIs('users.dashboard.index') ? 'active' : ''}}">
                <a href="{{ route('users.dashboard.index') }}" class="active">
                    <span class="sidebar-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" height="16" width="18" viewBox="0 0 576 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.--><path d="M288 32C128.9 32 0 160.9 0 320c0 52.8 14.3 102.3 39.1 144.8 5.6 9.6 16.3 15.2 27.4 15.2h443c11.1 0 21.8-5.6 27.4-15.2C561.8 422.3 576 372.8 576 320c0-159.1-128.9-288-288-288zm0 64c14.7 0 26.6 10.1 30.3 23.7-1.1 2.3-2.6 4.2-3.5 6.7l-9.2 27.7c-5.1 3.5-11 6-17.6 6-17.7 0-32-14.3-32-32S270.3 96 288 96zM96 384c-17.7 0-32-14.3-32-32s14.3-32 32-32 32 14.3 32 32-14.3 32-32 32zm48-160c-17.7 0-32-14.3-32-32s14.3-32 32-32 32 14.3 32 32-14.3 32-32 32zm246.8-72.4l-61.3 184C343.1 347.3 352 364.5 352 384c0 11.7-3.4 22.6-8.9 32H232.9c-5.5-9.5-8.9-20.3-8.9-32 0-33.9 26.5-61.4 59.9-63.6l61.3-184c4.2-12.6 17.7-19.5 30.4-15.2 12.6 4.2 19.4 17.8 15.2 30.4zm14.7 57.2l15.5-46.6c3.5-1.3 7.1-2.2 11.1-2.2 17.7 0 32 14.3 32 32s-14.3 32-32 32c-11.4 0-20.9-6.3-26.6-15.2zM480 384c-17.7 0-32-14.3-32-32s14.3-32 32-32 32 14.3 32 32-14.3 32-32 32z"/></svg>
                    </span>
                    {{ __('Dashboard') }}
                </a>
            </li>

            <li class="{{ Request::routeIs('users.quick-sms.index') ? 'active' : ''}}">
                <a href="{{ route('users.quick-sms.index') }}" class="active">
                    <span class="sidebar-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" height="16" width="20" viewBox="0 0 640 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.--><path d="M544 224l-128-16-48-16h-24L227.2 44h39.5C278.3 44 288 41.4 288 38s-9.7-6-21.3-6H152v12h16v164h-48l-66.7-80H18.7L8 138.7V208h8v16h48v2.7l-64 8v42.7l64 8V288H16v16H8v69.3L18.7 384h34.7L120 304h48v164h-16v12h114.7c11.7 0 21.3-2.6 21.3-6s-9.7-6-21.3-6h-39.5L344 320h24l48-16 128-16c96-21.3 96-26.6 96-32 0-5.4 0-10.7-96-32z"/></svg>
                    </span>

                    {{ __('Quick SMS') }}
                </a>
            </li>

            <li class="{{ Request::routeIs('users.group-sms.index') ? 'active' : ''}}">
                <a href="{{ route('users.group-sms.index') }}" class="active">
                    <span class="sidebar-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" height="16" width="20" viewBox="0 0 640 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.--><path d="M96 224c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm448 0c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm32 32h-64c-17.6 0-33.5 7.1-45.1 18.6 40.3 22.1 68.9 62 75.1 109.4h66c17.7 0 32-14.3 32-32v-32c0-35.3-28.7-64-64-64zm-256 0c61.9 0 112-50.1 112-112S381.9 32 320 32 208 82.1 208 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C179.6 288 128 339.6 128 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zm-223.7-13.4C161.5 263.1 145.6 256 128 256H64c-35.3 0-64 28.7-64 64v32c0 17.7 14.3 32 32 32h65.9c6.3-47.4 34.9-87.3 75.2-109.4z"/></svg>
                    </span>
                    {{ __('Group SMS') }}
                </a>
            </li>

            <li class="{{ Request::routeIs('users.bulk-sms.index') ? 'active' : '' }}">
                <a href="{{ route('users.bulk-sms.index') }}" class="active">
                    <span class="sidebar-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M128 0C110.3 0 96 14.3 96 32l0 192 96 0 0-32c0-35.3 28.7-64 64-64l224 0 0-96c0-17.7-14.3-32-32-32L128 0zM256 160c-17.7 0-32 14.3-32 32l0 32 96 0c35.3 0 64 28.7 64 64l0 128 192 0c17.7 0 32-14.3 32-32l0-192c0-17.7-14.3-32-32-32l-320 0zm240 64l32 0c8.8 0 16 7.2 16 16l0 32c0 8.8-7.2 16-16 16l-32 0c-8.8 0-16-7.2-16-16l0-32c0-8.8 7.2-16 16-16zM64 256c-17.7 0-32 14.3-32 32l0 13L187.1 415.9c1.4 1 3.1 1.6 4.9 1.6s3.5-.6 4.9-1.6L352 301l0-13c0-17.7-14.3-32-32-32L64 256zm288 84.8L216 441.6c-6.9 5.1-15.3 7.9-24 7.9s-17-2.8-24-7.9L32 340.8 32 480c0 17.7 14.3 32 32 32l256 0c17.7 0 32-14.3 32-32l0-139.2z"/></svg>
                    </span>
                    {{ __('Bulk SMS') }}
                </a>
            </li>

            <li class="{{ Request::routeIs('users.senderids.index') ? 'active' : ''}}">
                <a href="{{ route('users.senderids.index') }}" class="active">
                    <span class="sidebar-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M289.8 46.8c3.7-9 12.5-14.8 22.2-14.8H424c13.3 0 24 10.7 24 24V168c0 9.7-5.8 18.5-14.8 22.2s-19.3 1.7-26.2-5.2l-33.4-33.4L321 204.2c19.5 28.4 31 62.7 31 99.8c0 97.2-78.8 176-176 176S0 401.2 0 304s78.8-176 176-176c37 0 71.4 11.4 99.8 31l52.6-52.6L295 73c-6.9-6.9-8.9-17.2-5.2-26.2zM400 80l0 0h0v0zM176 416a112 112 0 1 0 0-224 112 112 0 1 0 0 224z"/></svg>
                    </span>
                    {{ __('Sender IDs') }}
                </a>
            </li>

            <li class="{{ Request::routeIs('users.templates.index', 'users.templates.edit') ? 'active' : ''}}">
                <a href="{{ route('users.templates.index') }}" class="active">
                    <span class="sidebar-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M289.8 46.8c3.7-9 12.5-14.8 22.2-14.8H424c13.3 0 24 10.7 24 24V168c0 9.7-5.8 18.5-14.8 22.2s-19.3 1.7-26.2-5.2l-33.4-33.4L321 204.2c19.5 28.4 31 62.7 31 99.8c0 97.2-78.8 176-176 176S0 401.2 0 304s78.8-176 176-176c37 0 71.4 11.4 99.8 31l52.6-52.6L295 73c-6.9-6.9-8.9-17.2-5.2-26.2zM400 80l0 0h0v0zM176 416a112 112 0 1 0 0-224 112 112 0 1 0 0 224z"/></svg>
                    </span>
                    {{ __('Templates') }}
                </a>
            </li>

            <li class="{{ Request::routeIs('users.plans.index') ? 'active' : ''}}">
                <a href="{{ route('users.plans.index') }}" class="active">
                    <span class="sidebar-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.--><path d="M12.4 148l232.9 105.7c6.8 3.1 14.5 3.1 21.3 0l232.9-105.7c16.6-7.5 16.6-32.5 0-40L266.7 2.3a25.6 25.6 0 0 0 -21.3 0L12.4 108c-16.6 7.5-16.6 32.5 0 40zm487.2 88.3l-58.1-26.3-161.6 73.3c-7.6 3.4-15.6 5.2-23.9 5.2s-16.3-1.7-23.9-5.2L70.5 210l-58.1 26.3c-16.6 7.5-16.6 32.5 0 40l232.9 105.6c6.8 3.1 14.5 3.1 21.3 0L499.6 276.3c16.6-7.5 16.6-32.5 0-40zm0 127.8l-57.9-26.2-161.9 73.4c-7.6 3.4-15.6 5.2-23.9 5.2s-16.3-1.7-23.9-5.2L70.3 337.9 12.4 364.1c-16.6 7.5-16.6 32.5 0 40l232.9 105.6c6.8 3.1 14.5 3.1 21.3 0L499.6 404.1c16.6-7.5 16.6-32.5 0-40z"/></svg>
                    </span>
                    {{ __('Rate Plans') }}
                </a>
            </li>

            <li class="{{ Request::routeIs('users.subscribers.index') ? 'active' : ''}}">
                <a href="{{ route('users.subscribers.index') }}" class="active">
                    <span class="sidebar-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M107.3 36.7a16 16 0 0 0 -22.6 0l-80 96C-5.4 142.7 1.8 160 16 160h48v304a16 16 0 0 0 16 16h32a16 16 0 0 0 16-16V160h48c14.2 0 21.4-17.2 11.3-27.3zM400 416h-16V304a16 16 0 0 0 -16-16h-48a16 16 0 0 0 -14.3 8.8l-16 32A16 16 0 0 0 304 352h16v64h-16a16 16 0 0 0 -16 16v32a16 16 0 0 0 16 16h96a16 16 0 0 0 16-16v-32a16 16 0 0 0 -16-16zM330.2 34.9a79 79 0 0 0 -55 54.2c-14.3 51.1 21.2 97.8 68.8 102.5a84.1 84.1 0 0 1 -20.9 12.9c-7.6 3.4-10.8 12.5-8.2 20.3l9.9 20c2.9 8.6 12.5 13.5 20.9 9.9 58-24.8 86.3-61.6 86.3-132V112c0-51.2-48.4-91.3-101.9-77.1zM352 132a20 20 0 1 1 20-20 20 20 0 0 1 -20 20z"/></svg>
                    </span>
                    {{ __('Subscription Orders') }}
                </a>
            </li>

            <li class="{{ Request::routeIs('users.recharges.index', 'users.recharges.edit') ? 'active' : ''}}">
                <a href="{{ route('users.recharges.index') }}" class="active">
                    <span class="sidebar-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" height="16" width="12" viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.--><path d="M377 105L279.1 7c-4.5-4.5-10.6-7-17-7H256v128h128v-6.1c0-6.3-2.5-12.4-7-16.9zm-153 31V0H24C10.7 0 0 10.7 0 24v464c0 13.3 10.7 24 24 24h336c13.3 0 24-10.7 24-24V160H248c-13.2 0-24-10.8-24-24zM64 72c0-4.4 3.6-8 8-8h80c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H72c-4.4 0-8-3.6-8-8V72zm0 80v-16c0-4.4 3.6-8 8-8h80c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H72c-4.4 0-8-3.6-8-8zm144 263.9V440c0 4.4-3.6 8-8 8h-16c-4.4 0-8-3.6-8-8v-24.3c-11.3-.6-22.3-4.5-31.4-11.4-3.9-2.9-4.1-8.8-.6-12.1l11.8-11.2c2.8-2.6 6.9-2.8 10.1-.7 3.9 2.4 8.3 3.7 12.8 3.7h28.1c6.5 0 11.8-5.9 11.8-13.2 0-6-3.6-11.2-8.8-12.7l-45-13.5c-18.6-5.6-31.6-23.4-31.6-43.4 0-24.5 19.1-44.4 42.7-45.1V232c0-4.4 3.6-8 8-8h16c4.4 0 8 3.6 8 8v24.3c11.3 .6 22.3 4.5 31.4 11.4 3.9 2.9 4.1 8.8 .6 12.1l-11.8 11.2c-2.8 2.6-6.9 2.8-10.1 .7-3.9-2.4-8.3-3.7-12.8-3.7h-28.1c-6.5 0-11.8 5.9-11.8 13.2 0 6 3.6 11.2 8.8 12.7l45 13.5c18.6 5.6 31.6 23.4 31.6 43.4 0 24.5-19.1 44.4-42.7 45.1z"/></svg>
                    </span>
                    {{ __('Recharge Payments') }}
                </a>
            </li>

            <li class="dropdown {{ Route::is('users.campaigns.index', 'users.campaigns.create', 'users.campaigns.edit') ? 'active' : '' }}">
                <a href="#">
                    <span class="sidebar-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" height="16" width="15.5" viewBox="0 0 496 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.--><path d="M247.6 8C389.4 8 496 118.1 496 256c0 147.1-118.5 248-248.4 248C113.6 504 0 394.5 0 256 0 123.1 104.7 8 247.6 8zm.8 44.7C130.2 52.7 44.7 150.6 44.7 256c0 109.8 91.2 202.8 203.7 202.8 103.2 0 202.8-81.1 202.8-202.8 .1-113.8-90.2-203.3-202.8-203.3zm107 205.6c-4.7 0-9 2.8-10.7 7.2l-4 9.5-11-92.8c-1.7-13.9-22-13.4-23.1 .4l-4.3 51.4-5.2-68.8c-1.1-14.3-22.1-14.2-23.2 0l-3.5 44.9-5.9-94.3c-.9-14.5-22.3-14.4-23.2 0l-5.1 83.7-4.3-66.3c-.9-14.4-22.2-14.4-23.2 0l-5.3 80.2-4.1-57c-1.1-14.3-22-14.3-23.2-.2l-7.7 89.8-1.8-12.2c-1.7-11.4-17.1-13.6-22-3.3l-13.2 27.7H87.5v23.2h51.3c4.4 0 8.4-2.5 10.4-6.4l10.7 73.1c2 13.5 21.9 13 23.1-.7l3.8-43.6 5.7 78.3c1.1 14.4 22.3 14.2 23.2-.1l4.6-70.4 4.8 73.3c.9 14.4 22.3 14.4 23.2-.1l4.9-80.5 4.5 71.8c.9 14.3 22.1 14.5 23.2 .2l4.6-58.6 4.9 64.4c1.1 14.3 22 14.2 23.1 .1l6.8-83 2.7 22.3c1.4 11.8 17.7 14.1 22.3 3.1l18-43.4h50.5V258l-58.4 .3zm-78 5.2h-21.9v21.9c0 4.1-3.3 7.5-7.5 7.5-4.1 0-7.5-3.3-7.5-7.5v-21.9h-21.9c-4.1 0-7.5-3.3-7.5-7.5 0-4.1 3.4-7.5 7.5-7.5h21.9v-21.9c0-4.1 3.4-7.5 7.5-7.5s7.5 3.3 7.5 7.5v21.9h21.9c4.1 0 7.5 3.3 7.5 7.5 0 4.1-3.4 7.5-7.5 7.5z"/></svg>
                    </span>
                    {{ __('Manage Campaigns') }}
                </a>
                <ul>
                    <li><a class="{{ Route::is('users.campaigns.create') ? 'active' : '' }}" href="{{ route('users.campaigns.create') }}">{{__('Create Campaign')}}</a></li>
                    <li><a class="{{ Route::is('users.campaigns.index', 'users.campaigns.edit') ? 'active' : '' }}" href="{{ route('users.campaigns.index') }}">{{__('Manage Campaigns')}}</a></li>
                </ul>
            </li>

            <li class="dropdown {{ Route::is('users.groups.index', 'users.groups.edit', 'users.contacts.index', 'users.contacts.create', 'users.contacts.edit', 'users.bulk-contacts.index') ? 'active' : '' }}">
                <a href="#">
                    <span class="sidebar-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" height="16" width="18" viewBox="0 0 576 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.--><path d="M528 64H384v96H192V64H48C21.5 64 0 85.5 0 112v352c0 26.5 21.5 48 48 48h480c26.5 0 48-21.5 48-48V112c0-26.5-21.5-48-48-48zM288 224c35.3 0 64 28.7 64 64s-28.7 64-64 64-64-28.7-64-64 28.7-64 64-64zm93.3 224H194.7c-10.4 0-18.8-10-15.6-19.8 8.3-25.6 32.4-44.2 60.9-44.2h8.2c12.3 5.1 25.7 8 39.8 8s27.6-2.9 39.8-8h8.2c28.4 0 52.5 18.5 60.9 44.2 3.2 9.8-5.2 19.8-15.6 19.8zM352 32c0-17.7-14.3-32-32-32h-64c-17.7 0-32 14.3-32 32v96h128V32z"/></svg>
                    </span>
                    {{ __('Contacts Book') }}
                </a>
                <ul>
                    <li><a class="{{ Route::is('users.groups.index') ? 'active' : '' }}" href="{{ route('users.groups.index') }}">{{__('Manage Groups')}}</a></li>
                    <li><a class="{{ Route::is('users.contacts.create') ? 'active' : '' }}" href="{{ route('users.contacts.create') }}">{{__('Create Contacts')}}</a></li>
                    <li><a class="{{ Route::is('users.bulk-contacts.index') ? 'active' : '' }}" href="{{ route('users.bulk-contacts.index') }}">{{__('Bulk Contacts')}}</a></li>
                    <li><a class="{{ Route::is('users.contacts.index', 'users.contacts.edit') ? 'active' : '' }}" href="{{ route('users.contacts.index') }}">{{__('Manage Contacts')}}</a></li>
                </ul>
            </li>

            <li class="dropdown {{ Route::is('users.reports.quick-sms', 'users.reports.campaigns-sms', 'users.reports.subscriptions', 'users.reports.recharges', 'users.reports.group-sms', 'users.reports.transactions') ? 'active' : '' }}">
                <a href="#">
                    <span class="sidebar-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" height="16" width="14" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.--><path d="M48 24C48 10.7 37.3 0 24 0S0 10.7 0 24V64 350.5 400v88c0 13.3 10.7 24 24 24s24-10.7 24-24V388l80.3-20.1c41.1-10.3 84.6-5.5 122.5 13.4c44.2 22.1 95.5 24.8 141.7 7.4l34.7-13c12.5-4.7 20.8-16.6 20.8-30V66.1c0-23-24.2-38-44.8-27.7l-9.6 4.8c-46.3 23.2-100.8 23.2-147.1 0c-35.1-17.6-75.4-22-113.5-12.5L48 52V24zm0 77.5l96.6-24.2c27-6.7 55.5-3.6 80.4 8.8c54.9 27.4 118.7 29.7 175 6.8V334.7l-24.4 9.1c-33.7 12.6-71.2 10.7-103.4-5.4c-48.2-24.1-103.3-30.1-155.6-17.1L48 338.5v-237z"/></svg>
                    </span>
                    {{ __('Reports') }}
                </a>
                <ul>
                    <li><a class="{{ Route::is('users.reports.quick-sms') ? 'active' : '' }}" href="{{ route('users.reports.quick-sms') }}">{{ __('Quick Sms') }}</a></li>

                    <li><a class="{{ Route::is('users.reports.group-sms') ? 'active' : '' }}" href="{{ route('users.reports.group-sms') }}">{{ __('Group Sms') }}</a></li>

                    <li><a class="{{ Route::is('users.reports.campaigns-sms') ? 'active' : '' }}" href="{{ route('users.reports.campaigns-sms') }}">{{ __('Campaigns Sms') }}</a></li>

                    <li><a class="{{ Route::is('users.reports.subscriptions') ? 'active' : '' }}" href="{{ route('users.reports.subscriptions') }}">{{ __('Subscriptions') }}</a></li>

                    <li><a class="{{ Route::is('users.reports.recharges') ? 'active' : '' }}" href="{{ route('users.reports.recharges') }}">{{ __('Recharges') }}</a></li>

                    <li><a class="{{ Route::is('users.reports.transactions') ? 'active' : '' }}" href="{{ route('users.reports.transactions') }}">{{ __('Transactions') }}</a></li>
                </ul>
            </li>

            <li class="dropdown {{ Route::is('users.client-secret.index', 'users.developer-options') ? 'active' : '' }}">
                <a href="#">
                    <span class="sidebar-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" height="16" width="20" viewBox="0 0 640 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.--><path d="M255 261.7c6.3 6.3 16.4 6.3 22.6 0l11.3-11.3c6.3-6.3 6.3-16.4 0-22.6L253.3 192l35.7-35.7c6.3-6.3 6.3-16.4 0-22.6l-11.3-11.3c-6.3-6.3-16.4-6.3-22.6 0l-58.3 58.3c-6.3 6.3-6.3 16.4 0 22.6l58.4 58.3zm96-11.3l11.3 11.3c6.3 6.3 16.4 6.3 22.6 0l58.3-58.3c6.3-6.3 6.3-16.4 0-22.6l-58.3-58.3c-6.3-6.3-16.4-6.3-22.6 0l-11.3 11.3c-6.3 6.3-6.3 16.4 0 22.6L386.8 192l-35.7 35.7c-6.3 6.3-6.3 16.4 0 22.6zM624 416H381.5c-.7 19.8-14.7 32-32.7 32H288c-18.7 0-33-17.5-32.8-32H16c-8.8 0-16 7.2-16 16v16c0 35.2 28.8 64 64 64h512c35.2 0 64-28.8 64-64v-16c0-8.8-7.2-16-16-16zM576 48c0-26.4-21.6-48-48-48H112C85.6 0 64 21.6 64 48v336h512V48zm-64 272H128V64h384v256z"/></svg>
                    </span>
                    {{ __('Developer Options') }}
                </a>
                <ul>
                    <li><a class="{{ Route::is('users.client-secret.index') ? 'active' : '' }}" href="{{ route('users.client-secret.index') }}">{{ __('Api Key & Secret') }}</a></li>
                    <li><a class="{{ Route::is('users.developer-options') ? 'active' : '' }}" href="{{ route('users.developer-options') }}">{{ __('Developer Options') }}</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
