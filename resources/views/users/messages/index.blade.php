@extends('layouts.users.master')

@section('title')
    {{ __('Messages History') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-header d-flex justify-content-between">
                        <h4>{{ __('Messages History') }}</h4>
                        <div class="btn-group">
                            <a href="{{ route('users.quick-sms.index') }}" class="btn btn-sm btn-primary text-light">{{ __('Quick SMS') }}</a>
                            <a href="{{ route('users.group-sms.index') }}" class="btn btn-sm btn-dark text-light">{{ __('Group SMS') }}</a>
                        </div>
                    </div>
                    <div class="table-top-form">
                        <form action="{{ route('users.messages.filter') }}" method="post" table="#message-data">
                            @csrf
                            <div class="table-search">
                                <input class="form-control searchInput" type="text" name="search" placeholder="{{ __('Search') }}..." value="{{ request('search') }}">
                            </div>
                        </form>
                    </div>

                    <div class="responsive-table">
                        <table class="table" id="datatable">
                            <thead>
                                <tr>
                                    <th>{{ __('SL') }}.</th>
                                    <th>{{ __('Campaign') }}</th>
                                    <th>{{ __('Senderid ID') }}</th>
                                    <th>{{ __('Number') }}</th>
                                    <th>{{ __('Contents') }}</th>
                                    <th>{{ __('Created At') }}</th>
                                    <th>{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody id="message-data">
                                @include('users.messages.datas')
                            </tbody>
                        </table>
                    </div>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item">{{ $messages->links('pagination::bootstrap-5') }}</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    @include('admin.components.multi-delete-modal')
@endpush
