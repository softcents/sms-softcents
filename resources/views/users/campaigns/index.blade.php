@extends('layouts.users.master')

@section('title')
    {{ __('Campaigns Messages') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-bodys p-16">
                    <div class="table-header d-flex justify-content-between">
                        <h4>{{ __('Campaigns Messages') }}</h4>
                        <div class="btn-group">
                            <a href="{{ route('users.campaigns.create') }}" class="btn btn-sm btn-primary text-light">
                                <i class="far fa-plus me-1" aria-hidden="true"></i>
                                {{ __('Add Campaigns') }}
                            </a>
                        </div>
                    </div>
                    <div class="table-top-form">
                        <form action="{{ route('users.campaigns.index') }}" method="post">
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
                                <th>{{ __('Campaign Name') }}</th>
                                <th>{{ __('Total Numbers') }}</th>
                                <th>{{ __('Created At') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Send Sms') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="searchResults">
                            @include('users.campaigns.datas')
                        </tbody>
                    </table>
                </div>
                <nav>
                    <ul class="pagination">
                        <li class="page-item">{{ $campaigns->links('pagination::bootstrap-5') }}</li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    @include('admin.components.multi-delete-modal')
@endpush
