@extends('layouts.users.master')

@section('title')
    {{ __('SMS Inbox') }}
@endsection

@section('main_content')
<div class="erp-table-section">
    <div class="container-fluid">
        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-md-4">
                <div class="card shadow-sm border-0" style="border-radius: 10px; background: #ffffff;">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted d-block small fw-semibold">{{ __('Total Received') }}</span>
                            <h3 class="fw-bold mb-0 text-dark mt-1">{{ number_format($totalCount) }}</h3>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #e0f2fe; color: #0284c7;">
                            <i class="fas fa-inbox fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="card shadow-sm border-0" style="border-radius: 10px; background: #ffffff;">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted d-block small fw-semibold">{{ __('Unread SMS') }}</span>
                            <h3 class="fw-bold mb-0 text-primary mt-1">{{ number_format($unreadCount) }}</h3>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #eef2ff; color: #4f46e5;">
                            <i class="fas fa-envelope-open-text fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-4">
                <div class="card shadow-sm border-0" style="border-radius: 10px; background: #ffffff;">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted d-block small fw-semibold">{{ __('Active Sender Numbers') }}</span>
                            <div class="mt-1">
                                @forelse($userSenders as $s)
                                    <span class="badge bg-light text-dark border font-monospace me-1 mb-1">{{ $s }}</span>
                                @empty
                                    <span class="text-muted small">{{ __('No numbers assigned') }}</span>
                                @endforelse
                            </div>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #ecfdf5; color: #059669;">
                            <i class="fas fa-phone-alt fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="card shadow-sm border-0" style="border-radius: 10px;">
            <div class="card-body p-4">
                <div class="table-header d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <h4 class="mb-0 fw-bold text-dark">{{ __('Received SMS Messages') }}</h4>
                        @if($unreadCount > 0)
                            <span class="badge bg-danger rounded-pill">{{ $unreadCount }} {{ __('new') }}</span>
                        @endif
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @if($unreadCount > 0)
                            <form action="{{ route('users.inbox.mark-all-read') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-secondary" style="border-radius: 6px;">
                                    <i class="fas fa-check-double me-1"></i> {{ __('Mark All Read') }}
                                </button>
                            </form>
                        @endif
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="window.location.reload();" style="border-radius: 6px;">
                            <i class="fas fa-sync me-1"></i> {{ __('Refresh') }}
                        </button>
                    </div>
                </div>

                <!-- Filters -->
                <form action="{{ route('users.inbox.index') }}" method="GET" class="mb-4">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-7">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" name="search" class="form-control border-start-0" placeholder="{{ __('Search by sender number, receiver or message content...') }}" value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">{{ __('All Messages') }}</option>
                                <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>{{ __('Unread only') }}</option>
                                <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>{{ __('Read only') }}</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100" style="border-radius: 6px;">{{ __('Search') }}</button>
                            @if(request()->hasAny(['search', 'status']))
                                <a href="{{ route('users.inbox.index') }}" class="btn btn-light border" title="{{ __('Clear') }}">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">{{ __('Status') }}</th>
                                <th>{{ __('From (Sender)') }}</th>
                                <th>{{ __('Sent To (Your Number)') }}</th>
                                <th>{{ __('Message') }}</th>
                                <th>{{ __('Received At') }}</th>
                                <th class="text-end" style="width: 140px;">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($messages as $msg)
                                <tr style="{{ !$msg->is_read ? 'background-color: #f8fafc;' : '' }}">
                                    <td>
                                        @if(!$msg->is_read)
                                            <span class="badge bg-primary rounded-circle p-1" title="{{ __('Unread') }}" style="width: 10px; height: 10px; display: inline-block;"></span>
                                        @else
                                            <i class="fas fa-check text-muted" title="{{ __('Read') }}" style="font-size: 11px;"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">
                                            <i class="fas fa-mobile-alt text-secondary me-1"></i> {{ $msg->sender }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border px-2 py-1 font-monospace" style="font-size: 12px;">
                                            {{ $msg->receiver }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-break" style="max-width: 450px;">
                                            <span class="{{ !$msg->is_read ? 'fw-bold text-dark' : 'text-secondary' }}">
                                                {{ $msg->message }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-dark small fw-medium">
                                            {{ $msg->received_at ? $msg->received_at->format('d M Y, h:i A') : $msg->created_at->format('d M Y, h:i A') }}
                                        </div>
                                        <small class="text-muted">
                                            {{ $msg->received_at ? $msg->received_at->diffForHumans() : $msg->created_at->diffForHumans() }}
                                        </small>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-1">
                                            <!-- Quick Reply Button -->
                                            <a href="{{ route('users.quick-sms.index') }}?phone={{ $msg->sender }}" class="btn btn-sm btn-outline-primary" title="{{ __('Reply SMS') }}" style="padding: 3px 8px; font-size: 12px;">
                                                <i class="fas fa-reply me-1"></i> {{ __('Reply') }}
                                            </a>
                                            @if(!$msg->is_read)
                                                <form action="{{ route('users.inbox.read', $msg->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-light border text-primary" title="{{ __('Mark Read') }}" style="padding: 3px 8px;">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('users.inbox.destroy', $msg->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this message?') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light border text-danger" title="{{ __('Delete') }}" style="padding: 3px 8px;">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="fas fa-inbox fa-3x text-secondary opacity-50 mb-3"></i>
                                            <h5 class="fw-bold text-dark">{{ __('No Incoming Messages') }}</h5>
                                            <p class="text-muted small mb-0">{{ __('Replies sent from mobile phones to your sender numbers will appear here.') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-4">
                    <div class="text-muted small">
                        {{ __('Showing') }} {{ $messages->firstItem() ?? 0 }} {{ __('to') }} {{ $messages->lastItem() ?? 0 }} {{ __('of') }} {{ $messages->total() }} {{ __('messages') }}
                    </div>
                    <div>
                        {{ $messages->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
