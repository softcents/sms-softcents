@extends('layouts.admin.master')

@section('title')
    {{ __('Recharge Invoice') }}
@endsection

@section('main_content')
<div class="erp-table-section">
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="fw-bold">{{ __('Invoice No') }}: {{ $recharge->invoice_no }}</h5>
                    <h5 class="fw-bold">{{ __('Date') }}: {{ formatted_date($recharge->created_at) }}</h5>
                </div>

                <div class="responsive-table height-less-table">
                    <table class="table" id="datatable">
                        <tr>
                            <th>{{ __('User') }}</th>
                            <th>{{ __('Invoice No') }}.</th>
                            <th>{{ __('Payment Gateway') }}</th>
                            <th>{{ __('Recharge Time') }}</th>
                            <th>{{ __('Amount') }}</th>
                            <th>{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ optional($recharge->user)->name }}</td>
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

                @if ($recharge->gateway->is_manual)
                    <div class="row mt-4">
                        @foreach ($recharge->gateway->manual_data['label'] ?? [] as $key => $label)
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-5 col-sm-3 col-lg-2">
                                    <span class="fw-bold">{{ $label }}</span>
                                </div>
                                <div class="col-7 col-sm-9 col-lg-10">
                                    <span class="me-3"> : </span> <span>{{ $recharge->manual_data[$key] }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        @if ($recharge->attachment)
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-5 col-sm-3 col-lg-2">
                                    <span class="fw-bold">{{ __('Attachment') }}</span>
                                </div>
                                <div class="col-7 col-sm-9 col-lg-10">
                                    <span class="me-3"> : </span> <a class="text-primary" target="_blank" href="{{ asset($recharge->attachment) }}">
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
