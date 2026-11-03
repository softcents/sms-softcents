@extends('layouts.ssp.master')

@section('title', __('Recharge My Account'))

@push('css')
<style>
    .erp-table-section .card, .erp-table-section .cards { min-height: auto !important; height: auto; }
</style>
@endpush

@section('main_content')
<div class="erp-table-section">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Submit Recharge Request -->
            <div class="col-lg-5">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-wallet text-primary me-2"></i>{{ __('Recharge From Parent Reseller') }}</h5>
                        <p class="text-muted small mb-0">{{ __('Current Balance:') }} <strong class="text-success fs-6">{{ number_format(auth()->user()->balance, 2) }} BDT</strong></p>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('ssp.my-recharge.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold">{{ __('Amount (BDT)') }} <span class="text-danger">*</span></label>
                                <input type="number" step="1" min="1" name="amount" class="form-control" placeholder="e.g. 500" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">{{ __('Select Payment Gateway') }} <span class="text-danger">*</span></label>
                                <select name="gateway_id" id="sspGatewaySelect" class="form-select" required>
                                    <option value="">{{ __('-- Select Payment Method --') }}</option>
                                    @foreach($gateways as $gw)
                                        <option value="{{ $gw->id }}" data-instructions="{{ $gw->instructions }}">
                                            {{ $gw->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3 p-3 bg-light rounded" id="sspGwInstructions" style="display: none;">
                                <h6 class="fw-bold small mb-1">{{ __('Payment Details:') }}</h6>
                                <p class="small text-muted mb-0" id="sspGwInstText"></p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">{{ __('Transaction ID (TrxID) / Sender Number') }}</label>
                                <input type="text" name="manual_data" class="form-control" placeholder="e.g. TrxID: 9JK84920, Phone: 017XXXXXXXX">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">{{ __('Payment Receipt / Screenshot') }}</label>
                                <input type="file" name="attachment" class="form-control" accept="image/*,.pdf">
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2">
                                <i class="fas fa-paper-plane me-1"></i> {{ __('Submit Recharge Request') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Recharge History -->
            <div class="col-lg-7">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-history text-secondary me-2"></i>{{ __('My Recharge History') }}</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="responsive-table" style="overflow-x: auto; -webkit-overflow-scrolling: touch; width: 100%; min-height: auto; margin-top: 0;">
                            <table class="table table-hover table-striped align-middle text-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('Invoice') }}</th>
                                        <th>{{ __('Gateway') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($myRecharges as $rech)
                                        <tr>
                                            <td><span class="fw-bold">{{ $rech->invoice_no }}</span></td>
                                            <td>{{ $rech->gateway->name ?? 'Reseller Gateway' }}</td>
                                            <td><span class="fw-bold text-success">{{ number_format($rech->amount, 2) }} BDT</span></td>
                                            <td class="small text-muted">{{ $rech->created_at->format('d M Y') }}</td>
                                            <td>
                                                @if($rech->status == 'approved')
                                                    <span class="badge bg-success">{{ __('Approved') }}</span>
                                                @elseif($rech->status == 'pending')
                                                    <span class="badge bg-warning text-dark">{{ __('Pending') }}</span>
                                                @else
                                                    <span class="badge bg-danger">{{ __('Rejected') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">{{ __('No recharge records found.') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('sspGatewaySelect').addEventListener('change', function() {
        var opt = this.options[this.selectedIndex];
        var inst = opt.getAttribute('data-instructions');
        var block = document.getElementById('sspGwInstructions');
        var text = document.getElementById('sspGwInstText');
        if (inst && inst.trim() !== '') {
            text.innerText = inst;
            block.style.display = 'block';
        } else {
            block.style.display = 'none';
        }
    });
</script>
@endsection
