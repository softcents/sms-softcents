@extends('layouts.admin.master')

@section('title')
    {{ __('Notifications List') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-bodys p-16">
                    <div class="table-header">
                        <h4>{{ __('Notifications list') }}</h4>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="user-list">
                            <div class="table-top-form mb-3">
                                <form action="" method="post" class="filter-form">
                                    @csrf

                                    <div class="grid-5">
                                        <select name="search" class="table-select form-control m-0 searchInput">
                                            <option value="daily">{{__('Today')}}</option>
                                            <option value="weekly">{{__('Last 7 Days')}}</option>
                                            <option value="15_days">{{__('Last 15 Days')}}</option>
                                            <option value="monthly">{{__('Last Month')}}</option>
                                            <option value="yearly">{{__('Last Year')}}</option>
                                        </select>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="responsibe-table">
                    <table class="table" id="erp-table">
                        <thead>
                            <tr>
                                <th>{{ __('SL') }}</th>
                                <th>{{ __('Message') }}</th>
                                <th>{{ __('Created At') }}</th>
                                <th>{{ __('Read At') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="searchResults">
                            @include('admin.notifications.datas')
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


