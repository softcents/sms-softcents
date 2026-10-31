@extends('layouts.admin.master')

@section('title')
    {{ __('Subscriptions List') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h5 class="fw-bold">{{ __('Invoice No') }}: {{ $subscribe->invoice_no }}</h5>
                        <h5 class="fw-bold">{{ __('Date') }}: {{ formatted_date($subscribe->created_at) }}</h5>
                    </div>

                    <div class="responsive-table height-less-table">
                        <table class="table" id="datatable">
                            <thead>
                                <tr>
                                    <th>{{ __('Invoice No') }}.</th>
                                    <th>{{ __('User') }}</th>
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
                                    <td>{{ optional($subscribe->user)->name }}</td>
                                    <td>{{ optional($subscribe->plan)->title }}</td>
                                    <td>{{ $subscribe->gateway->name ?? '' }}</td>
                                    <td class="fw-bold text-dark">{{ $subscribe->price }}</td>
                                    <td class="fw-bold text-dark">{{ $subscribe->total_sms }}</td>
                                    <td>{{ $subscribe->will_expire }}</td>
                                    <td>{{ $subscribe->created_at->format('d M Y') }}</td>
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
                    @if ($subscribe->gateway->is_manual)
                        <div class="row mt-4">
                            @foreach ($subscribe->gateway->manual_data['label'] ?? [] as $key => $label)
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-5 col-sm-3 col-lg-2">
                                        <span class="fw-bold">{{ $label }}</span>
                                    </div>
                                    <div class="col-7 col-sm-9 col-lg-10">
                                        : <span>{{ $subscribe->manual_data[$key] }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                            @if ($subscribe->attachment)
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-5 col-sm-3 col-lg-2">
                                        <span class="fw-bold">{{ __('Attachment') }}</span>
                                    </div>
                                    <div class="col-7 col-sm-9 col-lg-10">
                                        : <a class="text-primary" target="_blank" href="{{ asset($subscribe->attachment) }}">
                                           {{ __('File Preview') }}
                                            <i class="fas fa-arrow-circle-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
