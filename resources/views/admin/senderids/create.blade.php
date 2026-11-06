@extends('layouts.admin.master')

@section('title')
    {{ __('Create sender id') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-header mb-4">
                        <h4>{{ __('Create sender id') }}</h4>
                        <a href="{{ route('admin.senderids.index') }}" class="theme-btn print-btn text-light">
                            <i class="fas fa-plus-circle me-1"></i>
                            {{ __('Manage List') }}
                        </a>
                    </div>
                    <div class="tab-content order-summary-tab">
                        <div class="tab-pane fade mt-1 show active" id="add-new-user">
                            <div class="order-form-section">
                                <form action="{{ route('admin.senderids.store') }}" method="post" enctype="multipart/form-data" class="ajaxform_instant_reload">
                                    @csrf

                                    <div class="add-suplier-modal-wrapper">
                                        <div class="row">
                                            <div class="col-sm-6 mt-2">
                                                <label>{{ __('Select Customer (Optional)') }}</label>
                                                <div class="gpt-up-down-arrow position-relative">
                                                    <select name="user_id" class="form-control select-dropdown">
                                                        <option value="">{{ __('Select a customer') }}</option>
                                                        @foreach ($users as $user)
                                                            <option value="{{ $user->id }}">{{ $user->name }} ({{ '@' . ($user->username ?: ('user' . $user->id)) }}) - {{ $user->phone }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 mt-2">
                                                <label>{{ __('Sender Id') }}</label>
                                                <input type="text" name="sender" required class="form-control" placeholder="{{ __('Enter sender id') }}">
                                                <div id="sender-assignment-warning" class="alert alert-warning py-2 px-3 mt-2 d-none" style="font-size: 13px; border-radius: 6px;">
                                                    <i class="fas fa-exclamation-triangle text-warning me-1"></i>
                                                    <span id="sender-assignment-warning-text"></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 mt-2">
                                                <label>{{ __('Type') }}</label>
                                                <div class="gpt-up-down-arrow position-relative">
                                                    <select name="type" required="" class="form-control select-dropdown">
                                                        <option value="">{{ __('Select a type') }}</option>
                                                        <option value="Masking">{{ __('Masking') }}</option>
                                                        <option value="Non-Masking">{{ __('Non-Masking') }}</option>
                                                    </select>
                                                    <span></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 mt-2">
                                                <label>{{ __('SMS Gateway') }}</label>
                                                <div class="gpt-up-down-arrow position-relative">
                                                    <select name="gateway_id" required="" class="form-control select-dropdown">
                                                        <option value="">{{ __('Select a Sms Gateway') }}</option>
                                                        @foreach ($gatewayList as $gateway)
                                                            <option value="{{ $gateway->id }}">{{ $gateway->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 mt-2">
                                                <label>{{ __('Status') }}</label>
                                                <div class="gpt-up-down-arrow position-relative">
                                                    <select name="status" required="" class="form-control select-dropdown">
                                                        <option value="">{{ __('Select a status') }}</option>
                                                        <option value="1">{{ __('Active') }}</option>
                                                        <option value="0">{{ __('Deactive') }}</option>
                                                    </select>
                                                    <span></span>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="button-group text-center mt-5">
                                                    <a href="" class="theme-btn border-btn m-2">{{ __('Cancel') }}</a>
                                                    <button class="theme-btn m-2 submit-btn">{{ __('Save') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                {{-- form end --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    let checkTimer = null;
    $('input[name="sender"]').on('keyup change paste input', function() {
        clearTimeout(checkTimer);
        const val = $(this).val().trim();
        if (val.length < 2) {
            $('#sender-assignment-warning').addClass('d-none');
            return;
        }
        checkTimer = setTimeout(function() {
            $.get('{{ route("admin.senderids.check-assignment") }}', { sender: val }, function(res) {
                if (res && res.assigned && res.users.length > 0) {
                    let userListStr = res.users.map(u => u.name + ' (@' + (u.username || 'user' + u.user_id) + ', Phone: ' + (u.phone || '-') + ')').join(', ');
                    $('#sender-assignment-warning-text').html('<strong>{{ __("Warning:") }}</strong> {{ __("This Sender ID is already assigned to:") }} ' + userListStr);
                    $('#sender-assignment-warning').removeClass('d-none');
                } else {
                    $('#sender-assignment-warning').addClass('d-none');
                }
            });
        }, 350);
    });
});
</script>
@endpush
