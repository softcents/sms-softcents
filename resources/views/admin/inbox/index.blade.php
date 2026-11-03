@extends('layouts.admin.master')

@section('title')
    {{ __('SMS Inbox (Two-Way Messaging)') }}
@endsection

@section('main_content')
<div class="erp-table-section">
    <div class="container-fluid">
        <!-- Header & Stats Cards -->
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="card shadow-sm border-0" style="border-radius: 10px; background: #ffffff;">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted d-block small fw-semibold">{{ __('Total Incoming SMS') }}</span>
                            <h3 class="fw-bold mb-0 text-dark mt-1">{{ number_format($totalCount) }}</h3>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #e0f2fe; color: #0284c7;">
                            <i class="fas fa-inbox fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card shadow-sm border-0" style="border-radius: 10px; background: #ffffff;">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted d-block small fw-semibold">{{ __('Unread Messages') }}</span>
                            <h3 class="fw-bold mb-0 text-primary mt-1">{{ number_format($unreadCount) }}</h3>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #eef2ff; color: #4f46e5;">
                            <i class="fas fa-envelope-open-text fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card shadow-sm border-0" style="border-radius: 10px; background: #ffffff;">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted d-block small fw-semibold">{{ __('Live Webhook') }}</span>
                            <span class="badge bg-success-subtle text-success border border-success-subtle mt-1" style="font-size: 13px;">
                                <i class="fas fa-check-circle me-1"></i> {{ __('Active') }}
                            </span>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #ecfdf5; color: #059669;">
                            <i class="fas fa-satellite-dish fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card shadow-sm border-0" style="border-radius: 10px; background: #ffffff;">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted d-block small fw-semibold">{{ __('Anbernet Sync') }}</span>
                            <button type="button" class="btn btn-sm btn-primary mt-1" data-bs-toggle="modal" data-bs-target="#syncAnbernetModal" style="border-radius: 6px; font-size: 12px; font-weight: 500;">
                                <i class="fas fa-sync-alt me-1"></i> {{ __('Sync Gateway') }}
                            </button>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #fef3c7; color: #d97706;">
                            <i class="fas fa-cloud-download-alt fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Webhook Info Banner -->
        <div class="alert alert-info border-0 shadow-sm d-flex align-items-center justify-content-between flex-wrap gap-2 py-2 px-3 mb-4" style="border-radius: 8px; background: #f0fdf4; border-left: 4px solid #10b981 !important;">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-link text-success"></i>
                <span class="text-dark fw-medium small">
                    <strong>{{ __('Incoming SMS Webhook URL') }}:</strong>
                    <code class="px-2 py-1 bg-white text-primary rounded ms-1 border" style="font-size: 13px;">{{ url('/api/v1/incoming-sms') }}</code>
                </span>
            </div>
            <span class="text-muted small">
                {{ __('Accepts POST/GET with params:') }} <code>from</code>, <code>to</code>, <code>message</code>
            </span>
        </div>

        <!-- Main Inbox Table Card -->
        <div class="card shadow-sm border-0" style="border-radius: 10px;">
            <div class="card-body p-4">
                <div class="table-header d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <h4 class="mb-0 fw-bold text-dark">{{ __('Incoming Messages') }}</h4>
                        @if($unreadCount > 0)
                            <span class="badge bg-danger rounded-pill">{{ $unreadCount }} {{ __('new') }}</span>
                        @endif
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @if($unreadCount > 0)
                            <form action="{{ route('admin.inbox.mark-all-read') }}" method="POST" class="d-inline">
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
                <form action="{{ route('admin.inbox.index') }}" method="GET" class="mb-4">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" name="search" class="form-control border-start-0" placeholder="{{ __('Search by sender phone, receiver number, user name or message...') }}" value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">{{ __('All Status (Read & Unread)') }}</option>
                                <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>{{ __('Unread only') }}</option>
                                <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>{{ __('Read only') }}</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100" style="border-radius: 6px;">{{ __('Filter') }}</button>
                            @if(request()->hasAny(['search', 'status', 'date']))
                                <a href="{{ route('admin.inbox.index') }}" class="btn btn-light border" title="{{ __('Clear Filters') }}">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="border-collapse: separate; border-spacing: 0;">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">{{ __('Status') }}</th>
                                <th>{{ __('From (Mobile)') }}</th>
                                <th>{{ __('To (Sender ID / Number)') }}</th>
                                <th>{{ __('Matched User') }}</th>
                                <th>{{ __('Message Content') }}</th>
                                <th>{{ __('Gateway') }}</th>
                                <th>{{ __('Received At') }}</th>
                                <th class="text-end" style="width: 120px;">{{ __('Action') }}</th>
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
                                        @if($msg->user)
                                            <div class="fw-semibold text-primary">{{ $msg->user->name }}</div>
                                            <small class="text-muted">{{ $msg->user->email }}</small>
                                        @else
                                            <span class="text-muted small"><em>{{ __('System / Unassigned') }}</em></span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-break" style="max-width: 380px;">
                                            <span class="{{ !$msg->is_read ? 'fw-bold text-dark' : 'text-secondary' }}">
                                                {{ $msg->message }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">
                                            {{ $msg->gateway ? $msg->gateway->name : ucfirst($msg->gateway_type ?? 'Anbernet') }}
                                        </span>
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
                                            @if(!$msg->is_read)
                                                <form action="{{ route('admin.inbox.read', $msg->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-light border text-primary" title="{{ __('Mark as Read') }}" style="padding: 3px 8px;">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('admin.inbox.destroy', $msg->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this message?') }}');">
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
                                    <td colspan="8" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="fas fa-inbox fa-3x text-secondary opacity-50 mb-3"></i>
                                            <h5 class="fw-bold text-dark">{{ __('No Incoming Messages Found') }}</h5>
                                            <p class="text-muted small mb-3">{{ __('When customers or recipients reply to your non-masking numbers, messages will appear here.') }}</p>
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#syncAnbernetModal">
                                                <i class="fas fa-sync-alt me-1"></i> {{ __('Sync Anbernet Gateway Now') }}
                                            </button>
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

<!-- Sync Anbernet Modal -->
<div class="modal fade" id="syncAnbernetModal" tabindex="-1" aria-labelledby="syncAnbernetModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.inbox.sync-anbernet') }}" method="POST">
                @csrf
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold" id="syncAnbernetModalLabel">
                        <i class="fas fa-cloud-download-alt text-primary me-2"></i>{{ __('Sync Incoming SMS from Anbernet') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted small mb-3">
                        {{ __('Fetch the latest incoming messages sent to your non-masking virtual number from the Anbernet Incoming API.') }}
                    </p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">{{ __('MSISDN / Virtual Number') }}</label>
                        <input type="text" name="msisdn" class="form-control" value="8809639189627" placeholder="8809639189627" required>
                        <small class="text-muted">{{ __('Default is 8809639189627') }}</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">{{ __('Incoming API Password (Optional)') }}</label>
                        <input type="password" name="incoming_password" class="form-control" placeholder="{{ __('Leave blank to use gateway secret') }}">
                    </div>
                </div>
                <div class="modal-footer border-top bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-sync-alt me-1"></i> {{ __('Start Sync') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
