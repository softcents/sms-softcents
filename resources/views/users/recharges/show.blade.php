@extends('layouts.users.blank')

@section('title')
    {{ __('Recharge Invoice') }}
@endsection

@section('main_content')
<div class="section-container p-0 erp-new-invice">
    <div class="erp-table-section">
        <div class="container">
            <div class="table-header justify-content-center border-0 d-block text-center pb-1">
                <h3 class="text-center"><strong>{{ __('Recharge Invoice') }}</strong></h3>
            </div>
            <div class="bill-invoice-wrp mt-0">
                <h2>{{ get_option('general')['title'] ?? config('app.name') }}</h2>
            </div>
            <div class="d-flex justify-content-between">
                <h5 class="fw-bold">{{ __('Invoice No') }}: {{ $recharge->invoice_no }}</h5>
                <h5 class="fw-bold">{{ __('Date') }}: {{ formatted_date($recharge->created_at) }}</h5>
            </div>
            <div class="table-container">
                <table class="table mt-3 text-center commercial-invoice party-ledger text-start table-bordered paking-detail-table nowrap" id="erp-table">
                    <thead>
                    <tr>
                        <th>{{ __('Invoice No') }}.</th>
                        <th>{{ __('Payment Gateway') }}</th>
                        <th>{{ __('Recharge Time') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Status') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $recharge->invoice_no }}</td>
                            <td>{{ $recharge->gateway->name ?? '' }}</td>
                            <td>{{ ($recharge->created_at)->format('d M Y') }}</td>
                            <td class="fw-bold text-dark">{{ currency_format($recharge->amount) }}</td>
                            <td>
                                @if ($recharge->status == 'pending')
                                    <div class="badge bg-warning">
                                        {{ ucfirst($recharge->status) }}
                                    </div>
                                @elseif ($recharge->status == 'approved')
                                    <div class="badge bg-success">
                                        {{ ucfirst($recharge->status) }}
                                    </div>
                                @else
                                    <div class="badge bg-danger">
                                        {{ ucfirst($recharge->status) }}
                                    </div>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <h6>{{ __('Notes') }}:</h6>
                    <p>
                        {{ $recharge->notes }}
                    </p>
                </div>
            </div>

            <div class="row text-center mt-5">
                <div class="col-12">
                    <p><b>{{ __('Email') }}:</b> {{ get_option('general')['email'] ?? '' }}</p>
                    <p><b>{{ __('Phone') }}:</b> {{ get_option('general')['phone'] ?? '' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script>
        printPage();
    </script>
@endpush
