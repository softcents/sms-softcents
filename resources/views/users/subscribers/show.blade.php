@extends('layouts.users.blank')

@section('title')
    {{ __('Subscription Invoice') }}
@endsection

@section('main_content')
<div class="section-container p-0 erp-new-invice">
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="table-header justify-content-center border-0 d-block text-center pb-1">
                <h3 class="text-center"><strong>{{ __('Subscription Invoice') }}</strong></h3>
            </div>
            <div class="bill-invoice-wrp mt-0">
                <h2>{{ get_option('general')['title'] ?? config('app.name') }}</h2>
            </div>
            <div class="d-flex justify-content-between">
                <h5 class="fw-bold">{{ __('Invoice No') }}: {{ $subscribe->invoice_no }}</h5>
                <h5 class="fw-bold">{{ __('Date') }}: {{ formatted_date($subscribe->created_at) }}</h5>
            </div>
            <div class="table-container">
                <table class="table mt-3 text-center commercial-invoice party-ledger text-start table-bordered paking-detail-table nowrap" id="erp-table">
                    <thead>
                    <tr>
                        <th>{{ __('Invoice No') }}.</th>
                        <th>{{ __('Plan name') }}</th>
                        <th>{{ __('Payment Method') }}</th>
                        <th>{{ __('Price') }}</th>
                        <th>{{ __('Total SMS') }}</th>
                        <th>{{ __('Will Expire') }}</th>
                        <th>{{ __('Subscribe At') }}</th>
                        <th>{{ __('Status') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $subscribe->invoice_no }}</td>
                            <td>{{ optional($subscribe->plan)->title }}</td>
                            <td>{{ $subscribe->gateway->name ?? '' }}</td>
                            <td class="fw-bold text-dark">{{ $subscribe->price }}</td>
                            <td class="fw-bold text-dark">{{ $subscribe->total_sms }}</td>
                            <td>{{ $subscribe->will_expire }}</td>
                            <td>{{ ($subscribe->created_at)->format('d M Y') }}</td>
                            <td>
                                @if ($subscribe->status == 'pending')
                                    <div class="badge bg-warning">
                                        {{ ucfirst($subscribe->status) }}
                                    </div>
                                @elseif ($subscribe->status == 'approved')
                                    <div class="badge bg-success">
                                        {{ ucfirst($subscribe->status) }}
                                    </div>
                                @else
                                    <div class="badge bg-danger">
                                        {{ ucfirst($subscribe->status) }}
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
                        {{ $subscribe->notes }}
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
