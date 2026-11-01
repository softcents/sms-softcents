@extends('layouts.sp.master')

@section('title', __('Service Provider Dashboard'))

@section('main_content')
<div class="erp-table-section">
    <div class="container-fluid">
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card card-stat shadow-sm p-3 bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small text-uppercase fw-bold">{{ __('My Balance') }}</span>
                            <h3 class="mb-0 text-success fw-bold">{{ currency_format($sp->balance) }}</h3>
                        </div>
                        <div class="bg-light-success p-3 rounded-circle text-success" style="font-size: 24px;">
                            <i class="fas fa-wallet"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-stat shadow-sm p-3 bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small text-uppercase fw-bold">{{ __('Sub Resellers (SSP)') }}</span>
                            <h3 class="mb-0 text-primary fw-bold">{{ $totalSsps }}</h3>
                        </div>
                        <div class="bg-light-primary p-3 rounded-circle text-primary" style="font-size: 24px;">
                            <i class="fas fa-sitemap"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-stat shadow-sm p-3 bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small text-uppercase fw-bold">{{ __('Direct Customers') }}</span>
                            <h3 class="mb-0 text-info fw-bold">{{ $totalCustomers }}</h3>
                        </div>
                        <div class="bg-light-info p-3 rounded-circle text-info" style="font-size: 24px;">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-stat shadow-sm p-3 bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small text-uppercase fw-bold">{{ __('Today SMS Traffic') }}</span>
                            <h3 class="mb-0 text-warning fw-bold">{{ number_format($todaySmsSent) }}</h3>
                        </div>
                        <div class="bg-light-warning p-3 rounded-circle text-warning" style="font-size: 24px;">
                            <i class="fas fa-sms"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-bolt text-warning me-2"></i>{{ __('Quick Management Actions') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('sp.ssps.create') }}" class="btn btn-outline-primary">
                                <i class="fas fa-plus-circle me-1"></i> {{ __('Create New SSP') }}
                            </a>
                            <a href="{{ route('sp.customers.create') }}" class="btn btn-outline-success">
                                <i class="fas fa-user-plus me-1"></i> {{ __('Create New Customer') }}
                            </a>
                            <a href="{{ route('sp.ssps.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-list me-1"></i> {{ __('Manage All SSPs') }}
                            </a>
                            <a href="{{ route('sp.customers.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-list me-1"></i> {{ __('Manage All Customers') }}
                            </a>
                            <a href="{{ route('sp.white-label.index') }}" class="btn btn-outline-info">
                                <i class="fas fa-paint-brush me-1"></i> {{ __('Configure White Label Brand') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-history text-primary me-2"></i>{{ __('Recent Downline SMS Activity') }}</h5>
                <a href="{{ route('sp.reports.index') }}" class="btn btn-sm btn-outline-primary">{{ __('View All Traffic') }}</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('User') }}</th>
                            <th>{{ __('Recipient') }}</th>
                            <th>{{ __('Message') }}</th>
                            <th>{{ __('Charge') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Time') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentSms as $sms)
                        <tr>
                            <td>
                                <strong>{{ $sms->user->name ?? 'User' }}</strong><br>
                                <small class="text-muted">{{ $sms->user->username ?? '' }}</small>
                            </td>
                            <td>{{ $sms->number }}</td>
                            <td style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $sms->contents }}</td>
                            <td>{{ currency_format($sms->charge ?? 0) }}</td>
                            <td>
                                <span class="badge bg-success">{{ $sms->status }}</span>
                            </td>
                            <td>{{ $sms->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">{{ __('No SMS activity recorded yet.') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
