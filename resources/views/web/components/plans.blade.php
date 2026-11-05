<style>
.pricing-card {
    transition: transform 0.25s ease, box-shadow 0.25s ease !important;
    background: #ffffff;
}
.pricing-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 16px 36px rgba(72, 118, 255, 0.18) !important;
}
.popular-card {
    transform: scale(1.02);
}
.popular-card:hover {
    transform: scale(1.02) translateY(-8px);
}
@media (max-width: 1199px) {
    .popular-card {
        transform: none;
    }
    .popular-card:hover {
        transform: translateY(-6px);
    }
}
.pricing-plan-btn-primary {
    background: linear-gradient(135deg, #4876FF, #2563eb) !important;
    color: #ffffff !important;
    border: none !important;
    transition: all 0.3s ease;
}
.pricing-plan-btn-primary:hover {
    background: linear-gradient(135deg, #2563eb, #1d4ed8) !important;
    box-shadow: 0 4px 15px rgba(72, 118, 255, 0.4) !important;
    color: #ffffff !important;
}
.pricing-plan-btn-outline {
    background: transparent !important;
    color: #4876FF !important;
    border: 2px solid #4876FF !important;
    transition: all 0.3s ease;
}
.pricing-plan-btn-outline:hover {
    background: #4876FF !important;
    color: #ffffff !important;
    box-shadow: 0 4px 15px rgba(72, 118, 255, 0.3) !important;
}
</style>

<section class="pricing-plan-section plans-list {{ $class ?? '' }}" id="pricing">
    <div class="container py-4">
        <div class="section-title text-center mb-5">
            <h2 class="fw-bold">{{ $page_data['headings']['pricing_title'] ?? __('Our Pricing & Tariff Plan') }}</h2>
            <p class="text-muted mt-2" style="font-size: 16px;">{{ __('Transparent SMS rates with maximum delivery speed and reliable gateway routing') }}</p>
        </div>
        <div class="row g-4 justify-content-center">
            @foreach ($plans as $plan)
            @php
                $isPopular = ($plan->id == 3 || $plan->total_sms == 6765);
            @endphp
            <div class="col-xl-3 col-lg-6 col-md-6 col-12 d-flex">
                <div class="card pricing-card w-100 border-0 rounded-4 overflow-hidden d-flex flex-column {{ $isPopular ? 'popular-card' : '' }}" style="box-shadow: {{ $isPopular ? '0 12px 35px rgba(72, 118, 255, 0.22)' : '0 8px 24px rgba(0, 0, 0, 0.07)' }}; position: relative; border: {{ $isPopular ? '2px solid #4876FF' : '1px solid #eef2f7' }} !important;">
                    
                    @if ($isPopular)
                    <div class="popular-ribbon position-absolute top-0 end-0 text-white fw-bold px-3 py-1 text-uppercase" style="background: linear-gradient(135deg, #4876FF, #00d2ff); font-size: 11px; letter-spacing: 0.5px; border-bottom-left-radius: 12px; z-index: 2;">
                        <i class="fas fa-fire me-1"></i> {{ __('Most Popular') }}
                    </div>
                    @endif

                    <div class="card-header py-4 px-4 border-0 text-center" style="background: {{ $isPopular ? 'linear-gradient(180deg, #f0f4ff 0%, #ffffff 100%)' : '#fafbfe' }};">
                        <h4 class="fw-bold mb-2 text-dark" style="font-size: 1.25rem;">{{ $plan->title }}</h4>
                        
                        <div class="pricing-price my-2">
                            <span class="fs-2 fw-bolder text-primary">{{ currency_format($plan->price) }}</span>
                            <span class="text-muted small d-block">/ {{ str_replace("_", " ", $plan->duration) }}</span>
                        </div>
                    </div>

                    <div class="card-body px-4 py-3 d-flex flex-column flex-grow-1">
                        <!-- Tariff Rate Box -->
                        <div class="tariff-box p-3 rounded-3 mb-3" style="background: #f8faff; border: 1px dashed {{ $isPopular ? '#4876FF' : '#cbd5e1' }};">
                            <div class="text-uppercase text-muted fw-bold mb-2 text-center" style="font-size: 11px; letter-spacing: 0.8px;">
                                <i class="fas fa-tags me-1 text-primary"></i> {{ __('SMS Tariff Rates') }}
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-1 border-bottom border-light">
                                <span class="small fw-semibold text-secondary">
                                    <i class="fas fa-bolt text-warning me-1"></i> Non Masking:
                                </span>
                                <span class="fw-bold text-dark font-monospace">৳{{ number_format((float)$plan->non_masking_rate, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center pt-1">
                                <span class="small fw-semibold text-secondary">
                                    <i class="fas fa-shield-alt text-primary me-1"></i> Masking:
                                </span>
                                <span class="fw-bold text-dark font-monospace">৳{{ number_format((float)$plan->masking_rate, 2) }}</span>
                            </div>
                        </div>

                        <!-- Features List -->
                        <div class="features-list mb-3 flex-grow-1">
                            <ul class="list-unstyled mb-0" style="font-size: 13.5px; line-height: 1.6;">
                                @foreach ($plan->features ?? [] as $key => $item)
                                @php
                                    $hasFeature = isset($item[1]) && ($item[1] == '1' || $item[1] == 1);
                                    $featureName = is_array($item) ? ($item[0] ?? '') : $item;
                                @endphp
                                <li class="d-flex align-items-center py-1 {{ $hasFeature ? 'text-dark' : 'text-muted text-decoration-line-through' }}">
                                    <i class="fas {{ $hasFeature ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' }} me-2 flex-shrink-0" style="font-size: 14px;"></i>
                                    <span>{{ $featureName }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- CTA Button -->
                        <div class="mt-auto pt-2">
                            @if (auth()->check())
                            <a href="{{ route('payments-gateways.index', ['plan_id' => $plan->id]) }}" class="btn {{ $isPopular ? 'pricing-plan-btn-primary' : 'pricing-plan-btn-outline' }} w-100 py-2 fw-bold rounded-pill text-uppercase" style="letter-spacing: 0.5px; font-size: 14px;">
                                <i class="fas fa-shopping-cart me-1"></i> {{ __('Buy Now') }}
                            </a>
                            @else
                            <a data-bs-toggle="modal" data-bs-target="#register-modal" class="btn {{ $isPopular ? 'pricing-plan-btn-primary' : 'pricing-plan-btn-outline' }} w-100 py-2 fw-bold rounded-pill text-uppercase" style="letter-spacing: 0.5px; font-size: 14px;">
                                <i class="fas fa-shopping-cart me-1"></i> {{ __('Buy Now') }}
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@include('web.components.register')
