<div class="modal fade" id="approve-reject-modal" tabindex="-1" aria-labelledby="multi-delete-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Status update') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-0">
                <form action="" method="POST" class="ajaxform_instant_reload approve-reject-form">
                    @csrf
                    <div class="modal-body">
                        <textarea name="notes" cols="2" rows="5" class="form-control mt-3" placeholder="{{ __('Enter reason here') }}"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="theme-btn border-btn" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="theme-btn submit-btn">{{__('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
