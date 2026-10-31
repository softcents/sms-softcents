<section class="pricing-plan-section plans-list {{ $class ?? '' }}" id="pricing">
    <div class="container">
        <div class="section-title text-center">
            <h2>{{ $page_data['headings']['pricing_title'] ?? '' }}</h2>
        </div>
        <div class="row">
            @foreach ($plans as $plan)
            <div class="col-md-6 col-xl-4 ">
                <div class="card border-0 mb-4">
                    <div class="card-header py-3 border-0">
                        <h4 class="fw-bold">{{ $plan->title }}</h4>
                    </div>
                    <div class="card-body">
                        <h2 class="py-1">{{ currency_format($plan->price) }} <small>/ {{ str_replace("_", " ", $plan->duration) }}</small></h2>
                        <h6>{{ $plan->total_sms }} SMS</h6>
                        <ul>
                            <li><i class="fas {{ $plan->non_masking_rate ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' }} me-1"></i> {{ __('Non Masking Rate') }} {{ $plan->non_masking_rate }}</li>
                            <li><i class="fas {{ $plan->masking_rate ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' }} me-1"></i> {{ __('Masking Rate') }} {{ $plan->masking_rate }}</li>
                            @foreach ($plan->features ?? [] as $key => $item)
                            <li><i class="fas {{ isset($item[1]) ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' }} me-1"></i> {{ $item[0] ?? '' }}</li>
                            @endforeach
                        </ul>
                        @if (auth()->check())
                        <a href="{{ route('payments-gateways.index', ['plan_id' => $plan->id]) }}" class="btn subscribe-plan text-custom-primary d-block mt-3 mb-2"> {{ __('Buy Now') }} </a>
                        @else
                        <a data-bs-toggle="modal" data-bs-target="#register-modal" class="btn subscribe-plan text-custom-primary d-block mt-3 mb-2"> {{ __('Buy Now') }} </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@include('web.components.register')
