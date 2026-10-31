<div class="table-header justify-content-end border-0 p-0">
    <div class="button-group nav">
        @can('plans-read')
        <a href="{{ route('admin.plans.index') }}" class="add-report-btn {{ Route::is('admin.plans.index') ? 'active' : '' }}"><i class="fas fa-list me-1"></i> {{ __('Plans List') }}</a>
        @endcan
        @can('plans-create')
        <a href="{{ route('admin.plans.create') }}" class="add-report-btn {{ Route::is('admin.plans.create') ? 'active' : '' }}"><i class="fas fa-plus-circle me-1"></i> {{ __('Manage Plans') }}</a>
        @endcan
    </div>
</div>