@extends('layouts.admin.master')

@section('title')
    {{ __('Group Sms History') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-bodys p-16">
                    <div class="table-header d-flex justify-content-between">
                        <h4>{{ __('Group Sms History') }}</h4>
                    </div>
                    <div class="table-top-form">
                        <form action="{{ route('admin.reports.group-sms') }}" method="post" >
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
                                <th>{{ __('User') }}</th>
                                <th>{{ __('Senderid ID') }}</th>
                                <th>{{ __('Number') }}</th>
                                <th>{{ __('Contents') }}</th>
                                <th>{{ __('Created At') }}</th>
                                <th>{{ __('Gateway Name') }}</th>
                                <th>{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody class="searchResults">
                            @include('admin.reports.group-sms.datas')
                        </tbody>
                    </table>
                </div>
                <nav>
                    <ul class="pagination">
                        <li class="page-item">{{ $groups->links('pagination::bootstrap-5') }}</li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
@endsection
