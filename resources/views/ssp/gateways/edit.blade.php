@extends('layouts.ssp.master')

@section('title', __('Edit Payment Gateway'))

@section('main_content')
<div class="erp-table-section">
    <div class="container-fluid">
        <div class="card shadow-sm col-lg-9 mx-auto border-0">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold"><i class="fas fa-edit text-primary me-2"></i>{{ __('Edit Payment Gateway: ') }} {{ $gateway->name }}</h4>
                <a href="{{ route('ssp.gateways.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> {{ __('Back to List') }}
                </a>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('ssp.gateways.update', $gateway->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Gateway Name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $gateway->name) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Status') }} <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="1" {{ old('status', $gateway->status) == 1 ? 'selected' : '' }}>{{ __('Active') }}</option>
                                <option value="0" {{ old('status', $gateway->status) == 0 ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Transaction Charge / Fee (%)') }}</label>
                            <input type="number" step="0.01" name="charge" class="form-control" value="{{ old('charge', $gateway->charge) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Gateway Logo / Icon') }}</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            @if($gateway->image)
                                <div class="mt-2 p-2 border rounded bg-light d-inline-block">
                                    <img src="{{ asset($gateway->image) }}" alt="Logo" style="max-height: 35px;">
                                </div>
                            @endif
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">{{ __('Payment Instructions & Account Details') }}</label>
                            <textarea name="instructions" rows="4" class="form-control">{{ old('instructions', $gateway->instructions) }}</textarea>
                        </div>

                        <div class="col-12 mt-4 text-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-1"></i> {{ __('Save Changes') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
