@extends('layouts.users.master')

@section('title')
    {{__('Plans List') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-bodys p-16">
                    <div class="table-header">
                        <h4>{{__('Subscribers Plans List')}}</h4>
                    </div>
                    <div class="table-top-form">
                        <form action="{{ route('users.subscribers.index') }}" method="post">
                            @csrf
                            <div class="table-search">
                                <input class="form-control searchInput" type="text" name="search" placeholder="{{ __('Search') }}..." value="{{ request('search') }}">
                            </div>
                        </form>
                    </div>


                </div>
                <div class="responsive-table">
                    <table class="table" id="datatable">
                        <thead>
                            <tr>
                                <th>{{ __('SL') }}.</th>
                                <th>{{ __('Invoice No') }}.</th>
                                <th>{{ __('Plan name') }}</th>
                                <th>{{ __('Payment Method') }}</th>
                                <th>{{ __('Price') }}</th>
                                <th>{{ __('Total SMS') }}</th>
                                <th>{{ __('Will Expire') }}</th>
                                <th>{{ __('Subscribe At') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="searchResults">
                            @include('users.subscribers.datas')
                        </tbody>
                    </table>
                </div>
                <nav>
                    <ul class="pagination">
                        <li class="page-item">{{ $subscribers->links('pagination::bootstrap-5') }}</li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    <div class="modal fade" id="subscribe-modal" tabindex="-1" aria-labelledby="multi-delete-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Subscribe a Plan') }}(<span class="plan-title"></span>)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post" class="ajaxform_instant_reload">
                    @csrf

                    <div class="modal-body pt-0 order-form-section">
                        <div class="row">
                            <div class="col-12 mt-2">
                                <label>{{ __('Plan Price') }}</label>
                                <input type="number" step="any" name="price" required class="form-control plan-price" placeholder="{{ __('Plan Price') }}" readonly>
                            </div>
                            <div class="col-12 mt-2">
                                <label>{{ __('Payment Method') }}</label>
                                <div class="gpt-up-down-arrow position-relative">
                                    <input type="hidden" name="plan_id" class="plan-id">
                                    <select name="payment_method" required="" class="form-control select-dropdown">
                                        <option value="">{{ __('Select payment method') }}</option>
                                        <option value="Bank">{{ __('Bank') }}</option>
                                        <option value="bKash">{{ __('bKash') }}</option>
                                        <option value="Rocket">{{ __('Rocket') }}</option>
                                        <option value="Nagad">{{ __('Nagad') }}</option>
                                        <option value="Upay">{{ __('Upay') }}</option>
                                    </select>
                                    <span></span>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <label>{{ __('Transaction Id') }}</label>
                                <input type="text" name="transaction_id" required class="form-control" placeholder="{{ __('Enter transaction id') }}">
                            </div>

                            <div class="button-group text-center mt-5">
                                <button type="reset" class="theme-btn border-btn m-2">{{ __('Reset') }}</button>
                                <button class="theme-btn m-2 submit-btn">{{ __('Save') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush
