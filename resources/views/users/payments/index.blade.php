@extends('layouts.web.blank')

@section('main_content')
<section class="payment-method-section">
    <div class="container">
        <div class="payment-method-wrp">
            <div class="row">
                <div class="col-md-4">
                    <div class="nav d-block payment-method-nav">
                        @foreach ($gateways as $gateway)
                        <a href="#{{ str_replace(' ', '-', $gateway->name) }}" data-bs-toggle="pill" @class(['add-report-btn payment-items', 'active' => $loop->first ? true : false])>
                            {{ ucfirst($gateway->name) }}
                        </a>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="tab-content">
                        @foreach ($gateways as $gateway)
                        <div @class(['tab-pane fade', 'show active' => $loop->first ? true : false]) id="{{ str_replace(' ', '-', $gateway->name) }}">
                            <form action="{{ route('recharges-gateways.payment', $gateway->id) }}" method="post" enctype="multipart/form-data">
                                @csrf

                                <div class="payment-list-table">
                                    @if ($errors->any())
                                        @foreach ($errors->all() as $error)
                                        <div class="alert alert-warning alert-dismissible fade show mt-2" role="alert">
                                            {{ $error }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                        @endforeach
                                    @endif

                                    <h5 class="payment-title mb-3">{{ ucfirst($gateway->name) }} ({{ optional($gateway->currency)->code }})</h5>
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <td>{{ __('Gateway Name') }}</td>
                                                <td>{{ $gateway->name }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ __('Recharge Amount') }}</td>
                                                <td>{{ currency_format(round(convert_money($amount, $gateway->currency)), currency: $gateway->currency) }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ __('Gateway Currency') }}</td>
                                                <td>{{ optional($gateway->currency)->code }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ __('Gateway Charge') }}</td>
                                                <td>{{ currency_format($gateway->charge, currency: $gateway->currency) }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ __('Payable Amount') }}</td>
                                                <td>{{ currency_format(round(convert_money($amount, $gateway->currency) + $gateway->charge), currency: $gateway->currency) }}</td>
                                            </tr>
                                            @if ($gateway->phone_required == 1)
                                                <tr>
                                                    <th>
                                                        <label for="phone" class="required">{{ __('Phone Number') }}</label>
                                                    </th>
                                                    <td>
                                                        <input type="text" name="phone" id="phone" class="form-control" placeholder="{{ __('Enter your phone number') }}" required>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                    @if ($gateway->instructions)
                                    <div class="mb-3">
                                        <h5 class="payment-title mb-3">{{ __('Instructions') }}</h5>
                                        <p>{!! $gateway->instructions !!}</p>
                                    </div>
                                    @endif
                                    @if ($gateway->is_manual)
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            @if ($gateway->accept_img)
                                            <div class="form-group">
                                                <label for="">{{ __('Screenshot/Proof Image') }}</label>
                                                <input type="file" name="attachment" class="form-control" required>
                                            </div>
                                            @endif
                                            @foreach ($gateway->manual_data['label'] ?? [] as $key => $row)
                                            <div class="form-group mt-3">
                                                <label for="">{{ $row }}</label>
                                                <input type="text" name="manual_data[]" @required($gateway->manual_data['is_required'][$key] == 1) class="form-control" placeholder="{{ __('Enter ').$row }}">
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-md theme-btn">{{ __('Pay Now') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('web/css/payments.css') }}">
@endpush
