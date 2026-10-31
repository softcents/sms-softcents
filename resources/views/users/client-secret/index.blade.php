@extends('layouts.users.master')

@section('title')
    {{ __('API Key') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-header mb-4 d-flex justify-content-between">
                        <h4>{{ __('Your client secret') }}</h4>
                        <a href="{{ route('users.developer-options') }}" class="btn btn-sm btn-primary text-light"><i class="fas fa-book me-1"></i> {{ __('Documention') }}</a>
                    </div>
                    <div class="tab-content order-summary-tab my-4">
                        <div class="tab-pane fade mt-1 show active" id="add-new-user">
                            <div class="order-form-section">
                                <div class="add-suplier-modal-wrapper">
                                    <form action="{{ route('users.client-secret.store') }}" method="post" class="re-generate-key">
                                        <div class="row">
                                            <div class="col-9 col-sm-6 align-self-center">
                                                <label>{{ __('Your Client Secret') }}</label>
                                                <input type="text" name="client_secret" value="{{ auth()->user()->client_secret }}" required class="form-control client_secret" placeholder="{{ __('Generate api key here') }}">
                                            </div>
                                            <div class="col-3 col-sm-6 align-self-center">
                                                <button type="submit" class="btn btn-primary text-light mt-3 py-2 px-3 submit-btn">{{ __('Re-Generate') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
