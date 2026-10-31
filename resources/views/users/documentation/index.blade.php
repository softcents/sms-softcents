@extends('layouts.users.master')

@section('title')
    {{ __('API Key') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="cards shadow-sm">
                <div class="card-body">
                    <div class="table-header mb-4">
                        <h4>{{ __('Your Client Secret') }}</h4>
                    </div>
                    <div class="tab-content order-summary-tab mb-1">
                        <div class="tab-pane fade show active" id="add-new-user">
                            <div class="order-form-section mt-5">
                                <div class="add-suplier-modal-wrapper">
                                    <div class="row">
                                        <div class="col-12 col-sm-6 align-self-center">
                                            <label>{{ __('Your Client Secret') }}</label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" value="{{ auth()->user()->client_secret }}" aria-describedby="basic-addon5">
                                                <button class="input-group-text btn btn-primary text-light copyLink" data-text="{{ auth()->user()->client_secret }}" id="basic-addon5"><i class="fas fa-copy"></i></button>
                                            </div>

                                            <h5 title="Not changeable">{{ __('Your client ID:') }} <span class="fw-bold">{{ auth()->user()->client_id }}</span></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cards shadow-sm border-0 mt-3">
                <div class="card-body">
                    <h5 class="fw-bold mb-2">{{ __('Send message using api') }}</h5>
                    <p class="fw-bold mb-2">{{ __('Below parameters required before sending message') }}</p>
                    <p class="fw-bold">{{ __('1') }}. {{ __('Client Id') }}</p>
                    <p class="fw-bold">{{ __('2') }}. {{ __('Client Secret') }}</p>
                    <p class="fw-bold">{{ __('3') }}. {{ __('Sender ID') }}</p>
                    <p class="fw-bold">{{ __('4') }}. {{ __('Numbers') }}</p>
                    <p class="fw-bold mb-3">{{ __('5') }}. {{ __('Message Contents') }}</p>

                    <div class="order-form-section mt-2">
                        <div class="add-suplier-modal-wrapper">
                            <div class="row">
                                <div class="col-sm-6 align-self-center">
                                    <label for="single-sms-api">{{ __('API for single sms') }}</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" value="{{ route('send-message.index', ['clientId' => auth()->user()->client_id, 'clientSecret' => auth()->user()->client_secret, 'senderId' => 'SENDER_ID', 'numbers' => '8801xxx', 'contents' => 'This is demo message']) }}" aria-describedby="basic-addon2" id="single-sms-api">
                                        <button class="input-group-text btn btn-primary text-light copyLink" data-text="{{ route('send-message.index', ['clientId' => auth()->user()->client_id, 'clientSecret' => auth()->user()->client_secret, 'senderId' => 'SENDER_ID', 'numbers' => '8801xxx', 'contents' => 'This is demo message']) }}" id="basic-addon2"><i class="fas fa-copy"></i></button>
                                    </div>
                                </div>
                                <div class="col-sm-6 align-self-center">
                                    <label for="single-sms-api">{{ __('API for multiple destinations') }}</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" value="{{ route('send-message.index', ['clientId' => auth()->user()->client_id, 'clientSecret' => auth()->user()->client_secret, 'senderId' => 'SENDER_ID', 'numbers' => '8801xxx, 8801xxx, 8801xxx', 'contents' => 'This is demo message']) }}" aria-describedby="basic-addon2" id="single-sms-api">
                                        <button class="input-group-text btn btn-primary text-light copyLink" data-text="{{ route('send-message.index', ['clientId' => auth()->user()->client_id, 'clientSecret' => auth()->user()->client_secret, 'senderId' => 'SENDER_ID', 'numbers' => '8801xxx, 8801xxx, 8801xxx', 'contents' => 'This is demo message']) }}"  id="basic-addon2"><i class="fas fa-copy"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cards shadow-sm border-0 mt-3">
                <div class="card-body">
                    <div class="order-form-section mt-2">
                        <div class="add-suplier-modal-wrapper">
                            <h5 class="fw-bold mb-2">{{ __('Parameter details') }}</h5>
                            <div class="row">
                                <div class="col-12">
                                    <h6>★ {{ __('clientId') }} : {{ auth()->user()->client_id }}</h6>
                                    <p><span class="fw-bold">{{ __('Description:') }}</span> {{ __('When sending messages to your users or checking your balance, please use the provided client ID. Please note that this client ID is not changeable, and regenerating it is not possible. The client ID is a unique identifier, and it serves as a secure key for your messaging and balance-checking operations') }}.</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <h6>★ {{ __('clientSecret') }} : {{ auth()->user()->client_secret }}</h6>
                                    <p><span class="fw-bold">{{ __('Description:') }}</span> {{ __('For messaging your users or checking your balance, kindly utilize the provided client secret. Please be aware that the client secret is changeable, and regenerating it is possible. This secret serves as a unique identifier and acts as a secure key for your messaging and balance-checking operations. Safeguard your client secret and update it as needed for enhanced security') }}.</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <h6>★ {{ __('senderId') }} : <small>{{ __('You can find your sender ids') }} <a class="text-primary" href="{{ route('users.senderids.index') }}">{{ __('here') }}</a></small></h6>
                                    <p><span class="fw-bold">{{ __('Description:') }}</span> {{ __('To send messages to your users, make sure to use the assigned sender ID. The sender ID uniquely identifies your messages and ensures effective communication with your audience') }}.
                                    </p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <h6>★ {{ __('numbers') }}</h6>
                                    <p><span class="fw-bold">{{ __('Description:') }}</span> {{ __('When providing user numbers as parameters, please enter the desired recipient numbers here. You can send messages to a single user by entering their number (e.g., 8801xxxx), or to multiple users by separating the numbers with commas (e.g., 8801xxxx, 8801xxxx)') }}.
                                    </p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <h6>★ {{ __('contents') }}</h6>
                                    <p><span class="fw-bold">{{ __('Description:') }}</span> {{ __('When specifying message content as parameters, please input the desired messages here. This is where you can provide the text of the messages you want to send to your users') }}.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cards shadow-sm border-0 mt-3">
                <div class="card-body">
                    <div class="order-form-section mt-2">
                        <div class="add-suplier-modal-wrapper">
                            <h5 class="fw-bold mb-2">{{ __('API Responses') }}</h5>
                            <p>{{ __('Successful response in json format') }}.</p>
                            <p class="text-primary">{{ __('"status":"200","message":"Message sent successfully"') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cards shadow-sm border-0 mt-3">
                <div class="card-body">
                    <div class="order-form-section mt-2">
                        <div class="add-suplier-modal-wrapper">
                            <h5 class="fw-bold mb-2">{{ __('Balance Check API') }}</h5>

                            <div class="order-form-section mt-2">
                                <div class="add-suplier-modal-wrapper">
                                    <div class="row">
                                        <div class="col-sm-12 align-self-center">
                                            <label>{{ __('API for Balance Check using clientId & clientSecret') }}</label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" value="{{ route('check-balance.index', ['clientId' => auth()->user()->client_id, 'clientSecret' => auth()->user()->client_secret]) }}" aria-describedby="basic-addon3">
                                                <button class="input-group-text btn btn-primary text-light copyLink" data-text="{{ route('check-balance.index', ['clientId' => auth()->user()->client_id, 'clientSecret' => auth()->user()->client_secret]) }}" id="basic-addon3"><i class="fas fa-copy"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h6 class="fw-bold">{{ __('Successful response in json format') }}.</h6>
                            <p class="text-primary">{{ __('"status":"200","balance": 50000') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
