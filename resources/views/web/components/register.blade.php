<div class="modal fade" id="register-modal">
    <div class="modal-dialog modal-dialog-centered modal-lg" >
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold">Register</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="personal-info">
                    <form action="{{ route('register') }}" method="post" enctype="multipart/form-data"
                        class="add-brand-form pt-0 ajaxform_instant_reload">
                        @csrf

                        <div class="row">
                          <div class="mt-3 col-lg-6">
                                <label class="custom-top-label">{{ __('Name') }}</label>
                                <input type="text" name="name" placeholder="Enter your Name"
                                    class="form-control" />
                            </div>
                            <div class="mt-3 col-lg-6">
                                <label class="custom-top-label">{{ __('Email') }}</label>
                                <input type="email" name="email" placeholder="Enter your email"
                                    class="form-control" />
                            </div>
                            <div class="mt-3 col-lg-6">
                                <label class="custom-top-label">{{ __('Phone') }}</label>
                                <input type="text" name="phone" placeholder="Enter your phone"
                                    class="form-control" />
                            </div>

                            <div class="mt-3 col-lg-6">
                                <label class="custom-top-label">{{ __('Password') }}</label>
                                <input type="password" name="password" placeholder="Enter your password"
                                    class="form-control" />
                            </div>
                            <div class="mt-3 col-lg-6">
                                <label class="custom-top-label">{{ __('Country') }}</label>
                                <div class="gpt-up-down-arrow position-relative">
                                    <select class="form-control form-selected" name="country" >
                                        <option value=""> {{ __('Select a country') }}</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country['name'] }}">{{ $country['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mt-3 col-lg-6">
                                <label class="custom-top-label">{{ __('Confirm Password') }}</label>
                                <input type="password" name="password_confirmation" placeholder="Enter your password"
                                    class="form-control" />
                            </div>
                        </div>

                        <div class="offcanvas-footer mt-3 d-flex justify-content-center">
                            <button type="button" data-bs-dismiss="modal" class="cancel-btn btn btn-outline-danger" data-bs-dismiss="offcanvas"
                                aria-label="Close">Close
                            </button>
                            <button class="submit-btn btn btn-primary text-white ms-2" type="submit">{{ __('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
