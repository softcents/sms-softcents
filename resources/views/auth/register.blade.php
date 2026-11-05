@extends('layouts.auth.app')

@section('title')
    {{ __('Terms & Conditions') }} | {{ __('Register') }}
@endsection

@section('main_content')
<style>
.terms-page-wrapper {
    background-color: #EBF3FC;
    min-height: 100vh;
    padding: 30px 15px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.terms-card-container {
    max-width: 860px;
    width: 100%;
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 15px 45px rgba(12, 25, 40, 0.1);
    overflow: hidden;
}
.terms-header {
    background: linear-gradient(135deg, #055DD6 0%, #0d9aff 100%);
    color: #ffffff;
    padding: 30px 25px 22px;
    text-align: center;
}
.step-nav {
    display: flex;
    justify-content: center;
    gap: 12px;
    margin-top: 15px;
    flex-wrap: wrap;
}
.step-badge {
    padding: 6px 18px;
    border-radius: 50px;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.3s ease;
}
.step-badge.active {
    background: #ffffff;
    color: #055DD6;
}
.step-badge.inactive {
    background: rgba(255, 255, 255, 0.2);
    color: #ffffff;
}
.term-item {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 16px 20px;
    margin-bottom: 14px;
    transition: all 0.2s ease;
}
.term-item:hover {
    border-color: #93c5fd;
    background: #f0f7ff;
}
.term-badge-num {
    width: 28px;
    height: 28px;
    background: #0d9aff;
    color: #ffffff;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 700;
    margin-right: 10px;
    flex-shrink: 0;
}
.term-title {
    font-size: 16px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 6px;
}
.bengali-p {
    font-size: 14.5px;
    line-height: 1.6;
    color: #334155;
    margin-bottom: 0;
}
.english-p {
    font-size: 13.5px;
    line-height: 1.5;
    color: #64748b;
    margin-bottom: 8px;
}
.agreement-box {
    background: #f0f7ff;
    border: 2px solid #93c5fd;
    border-radius: 12px;
    padding: 22px;
    margin-top: 25px;
}

/* Step 2 Form Responsive Styling */
.registration-form-box {
    width: 100%;
    max-width: 100%;
}
.custom-form-control {
    height: 48px !important;
    font-size: 14.5px !important;
    border: 1.5px solid #cbd5e1 !important;
    border-radius: 8px !important;
    padding: 10px 14px !important;
    background-color: #f8fafc !important;
    transition: all 0.2s ease !important;
    width: 100% !important;
}
.custom-form-control:focus {
    background-color: #ffffff !important;
    border-color: #0d9aff !important;
    box-shadow: 0 0 0 3px rgba(13, 154, 255, 0.15) !important;
    outline: none !important;
}
.custom-input-group {
    position: relative;
    display: flex;
    align-items: center;
    width: 100%;
}
.custom-input-icon {
    position: absolute;
    left: 14px;
    color: #94a3b8;
    z-index: 4;
    font-size: 15px;
    pointer-events: none;
}
.custom-input-with-icon {
    padding-left: 42px !important;
}
.custom-pwd-toggle {
    position: absolute;
    right: 12px;
    background: none;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    z-index: 4;
    padding: 6px;
    font-size: 15px;
}
.custom-pwd-toggle:hover {
    color: #0d9aff;
}

@media (max-width: 768px) {
    .terms-page-wrapper {
        padding: 12px 8px;
    }
    .terms-header {
        padding: 22px 14px 18px;
    }
    .terms-body {
        padding: 18px 14px !important;
    }
    .term-item {
        padding: 14px 14px;
    }
    .agreement-box {
        padding: 15px 12px;
    }
}
</style>

<div class="terms-page-wrapper">
    <div class="terms-card-container">
        <!-- Header -->
        <div class="terms-header">
            <h2 class="fw-bold mb-1" style="font-size: 1.65rem;">
                <i class="fas fa-file-contract me-2"></i> Terms & Conditions – esmsbd.net
            </h2>
            <p class="mb-0 text-white-50 small">Please read carefully and agree to our terms before creating your account</p>
            
            <div class="step-nav">
                <span class="step-badge active" id="badge-step-1">
                    <i class="fas fa-check-circle me-1"></i> Step 1: Terms & Conditions
                </span>
                <span class="step-badge inactive" id="badge-step-2">
                    <i class="fas fa-user-plus me-1"></i> Step 2: Create Account
                </span>
            </div>
        </div>

        <div class="terms-body p-4 p-md-5">
            <!-- STEP 1: TERMS & CONDITIONS -->
            <div id="step-terms-content">
                <!-- Alert Box -->
                <div class="alert alert-danger border-2 p-3 p-md-4 rounded-3 mb-4" style="background: #fff5f5; border-color: #feb2b2;">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-exclamation-triangle text-danger fs-4 me-3 mt-1 flex-shrink-0"></i>
                        <div>
                            <h6 class="fw-bold text-danger mb-1" style="font-size: 15.5px;">গুরুত্বপূর্ণ নির্দেশনা:</h6>
                            <p class="mb-0 text-dark" style="font-size: 14px; line-height: 1.6;">
                                আমাদের SMS সার্ভিস ব্যবহার করার পূর্বে অনুগ্রহ করে নিচের সকল শর্তাবলি ভালোভাবে পড়ে নিন। অন্যথায়, কোনো শর্ত ভঙ্গের কারণে আপনার অ্যাকাউন্ট সাসপেন্ড বা স্থায়ীভাবে বন্ধ করা হলে এ বিষয়ে কোনো ধরনের আপত্তি, অভিযোগ বা পুনর্বহালের অনুরোধ গ্রহণ করা হবে না।
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Terms List -->
                <div class="terms-list">
                    <!-- 1 -->
                    <div class="term-item">
                        <div class="d-flex align-items-center mb-1">
                            <span class="term-badge-num">1</span>
                            <span class="term-title">SMS Data Retention</span>
                        </div>
                        <p class="english-p">Your sent SMS messages will not be stored on our servers for an extended period.</p>
                        <p class="bengali-p"><strong>বাংলা:</strong> আপনার পাঠানো SMS আমাদের সার্ভারে দীর্ঘ সময় ধরে সংরক্ষণ করা হয় না।</p>
                    </div>

                    <!-- 2 -->
                    <div class="term-item">
                        <div class="d-flex align-items-center mb-1">
                            <span class="term-badge-num">2</span>
                            <span class="term-title">SMS Package Validity & Renewal</span>
                        </div>
                        <p class="english-p">If you recharge a new SMS package before your existing package expires, the remaining SMS balance will be carried forward and added to the newly purchased package. However, if your existing package expires before recharging, the unused SMS balance will be forfeited and will not be added to your account.</p>
                        <p class="bengali-p"><strong>বাংলা:</strong> পুরাতন SMS প্যাকেজের মেয়াদ শেষ হওয়ার পূর্বে নতুন SMS প্যাকেজ রিচার্জ করলে অ্যাকাউন্টে থাকা অবশিষ্ট SMS নতুন প্যাকেজের সঙ্গে যুক্ত হবে। তবে পুরাতন প্যাকেজের মেয়াদ শেষ হওয়ার পর নতুন প্যাকেজ রিচার্জ করলে পূর্বের অব্যবহৃত SMS আর ফেরত বা যুক্ত করা হবে না।</p>
                    </div>

                    <!-- 3 -->
                    <div class="term-item">
                        <div class="d-flex align-items-center mb-1">
                            <span class="term-badge-num">3</span>
                            <span class="term-title">Prohibited & Illegal Activities</span>
                        </div>
                        <p class="english-p">Our SMS service must not be used for abusive, threatening, defamatory, anti-national, fraudulent or any other illegal activities. Any violation may result in immediate account suspension or permanent termination, and necessary legal action may be taken in accordance with applicable laws.</p>
                        <p class="bengali-p"><strong>বাংলা:</strong> আমাদের SMS সার্ভিস ব্যবহার করে কোনো ধরনের গালিগালাজ, হুমকি, মানহানিকর, দেশবিরোধী, প্রতারণামূলক অথবা অন্য কোনো বেআইনি কার্যক্রম পরিচালনা করা সম্পূর্ণ নিষিদ্ধ। এ ধরনের কার্যক্রম শনাক্ত হলে তাৎক্ষণিকভাবে অ্যাকাউন্ট সাসপেন্ড বা স্থায়ীভাবে বন্ধ করা হতে পারে এবং প্রচলিত আইন অনুযায়ী প্রয়োজনীয় আইনগত ব্যবস্থা গ্রহণ করা হতে পারে।</p>
                    </div>

                    <!-- 4 -->
                    <div class="term-item">
                        <div class="d-flex align-items-center mb-1">
                            <span class="term-badge-num">4</span>
                            <span class="term-title">Personal & Abusive SMS Restrictions</span>
                        </div>
                        <p class="english-p">You are strictly prohibited from using our SMS service for personal messaging, including romantic messages, love messages, harassment, threats, slang, abusive language, adult content or any other unauthorized personal communication. Violation of this policy may result in immediate and permanent account suspension without any reinstatement guarantee.</p>
                        <p class="bengali-p"><strong>বাংলা:</strong> আমাদের SMS সার্ভিস কোনো ধরনের ব্যক্তিগত মেসেজিংয়ের জন্য ব্যবহার করা যাবে না। যেমন: প্রেম-ভালোবাসা সম্পর্কিত মেসেজ, ব্যক্তিগত সম্পর্কের মেসেজ, হয়রানিমূলক মেসেজ, হুমকি, অশ্লীল ভাষা, অ্যাডাল্ট কনটেন্ট অথবা অন্য কোনো অননুমোদিত ব্যক্তিগত যোগাযোগ। এ ধরনের কার্যক্রম শনাক্ত হলে কোনো পূর্বঘোষণা ছাড়াই অ্যাকাউন্ট স্থায়ীভাবে সাসপেন্ড করা হতে পারে। এ ক্ষেত্রে অ্যাকাউন্ট পুনরায় চালুর নিশ্চয়তা থাকবে না।</p>
                    </div>

                    <!-- 5 -->
                    <div class="term-item">
                        <div class="d-flex align-items-center mb-1">
                            <span class="term-badge-num">5</span>
                            <span class="term-title">Fraudulent Promotional SMS</span>
                        </div>
                        <p class="english-p">Sending fraudulent, misleading or deceptive promotional SMS is strictly prohibited. This includes fake lottery notifications, false prize-winning messages, phishing messages, misleading offers or any other fraudulent promotional activities.</p>
                        <p class="bengali-p"><strong>বাংলা:</strong> প্রতারণামূলক, বিভ্রান্তিকর বা মিথ্যা প্রচারণামূলক SMS পাঠানো সম্পূর্ণ নিষিদ্ধ। যেমন: ভুয়া লটারি জেতার মেসেজ, মিথ্যা পুরস্কার পাওয়ার বিজ্ঞপ্তি, ফিশিং মেসেজ, বিভ্রান্তিকর অফার অথবা অন্য কোনো প্রতারণামূলক প্রচারণা।</p>
                    </div>

                    <!-- 6 -->
                    <div class="term-item">
                        <div class="d-flex align-items-center mb-1">
                            <span class="term-badge-num">6</span>
                            <span class="term-title">Adult Product Marketing</span>
                        </div>
                        <p class="english-p">Marketing, advertising or promoting adult products, adult services or sexually explicit content through our SMS platform is strictly prohibited.</p>
                        <p class="bengali-p"><strong>বাংলা:</strong> আমাদের SMS প্ল্যাটফর্ম ব্যবহার করে কোনো ধরনের অ্যাডাল্ট প্রোডাক্ট, অ্যাডাল্ট সার্ভিস অথবা যৌন উত্তেজক ও অশ্লীল কনটেন্টের মার্কেটিং, বিজ্ঞাপন বা প্রচারণা করা সম্পূর্ণ নিষিদ্ধ।</p>
                    </div>

                    <!-- 7 -->
                    <div class="term-item">
                        <div class="d-flex align-items-center mb-1">
                            <span class="term-badge-num">7</span>
                            <span class="term-title">SMS Delivery & Sending Speed</span>
                        </div>
                        <p class="english-p">SMS sending speed is generally designed for fast delivery. However, during high-volume campaigns, network congestion, operator restrictions or technical issues may cause delays. Customers are requested to remain patient until the sending process is completed.</p>
                        <p class="bengali-p"><strong>বাংলা:</strong> সাধারণত আমাদের SMS দ্রুত ডেলিভারির জন্য পরিচালিত হয়। তবে বড় ভলিউমের ক্যাম্পেইন, নেটওয়ার্কের চাপ, মোবাইল অপারেটরের সীমাবদ্ধতা অথবা প্রযুক্তিগত সমস্যার কারণে SMS পাঠাতে বিলম্ব হতে পারে। অনুগ্রহ করে সম্পূর্ণ প্রক্রিয়া শেষ হওয়া পর্যন্ত অপেক্ষা করুন।</p>
                    </div>

                    <!-- 8 -->
                    <div class="term-item">
                        <div class="d-flex align-items-center mb-1">
                            <span class="term-badge-num">8</span>
                            <span class="term-title">Customer Responsibility & Liability</span>
                        </div>
                        <p class="english-p">You are solely responsible for all SMS messages sent through your account, including their content, recipients, purpose and compliance with applicable laws and regulations. esmsbd.net shall not be held responsible for any misuse of the service by customers. Any violation may result in account suspension, permanent termination or legal action as applicable.</p>
                        <p class="bengali-p"><strong>বাংলা:</strong> আপনার অ্যাকাউন্ট থেকে পাঠানো প্রতিটি SMS-এর বিষয়বস্তু, প্রাপক, উদ্দেশ্য এবং প্রচলিত আইন ও বিধিবিধান মেনে চলার সম্পূর্ণ দায়ভার আপনাকে বহন করতে হবে। গ্রাহকের দ্বারা সার্ভিসের অপব্যবহারের জন্য esmsbd.net দায়ী থাকবে না। কোনো ধরনের নিয়ম লঙ্ঘন হলে অ্যাকাউন্ট সাসপেন্ড, স্থায়ীভাবে বন্ধ অথবা প্রযোজ্য ক্ষেত্রে আইনগত ব্যবস্থা গ্রহণ করা হতে পারে।</p>
                    </div>

                    <!-- 9 -->
                    <div class="term-item">
                        <div class="d-flex align-items-center mb-1">
                            <span class="term-badge-num">9</span>
                            <span class="term-title">No Refund Policy for Suspended Accounts</span>
                        </div>
                        <p class="english-p">If an account is suspended or permanently terminated due to violation of these Terms & Conditions, no refund will be issued for any remaining balance, unused SMS credits or purchased packages, subject to applicable law.</p>
                        <p class="bengali-p"><strong>বাংলা:</strong> এই Terms & Conditions লঙ্ঘনের কারণে কোনো অ্যাকাউন্ট সাসপেন্ড বা স্থায়ীভাবে বন্ধ করা হলে অ্যাকাউন্টে থাকা অবশিষ্ট ব্যালেন্স, অব্যবহৃত SMS অথবা ক্রয়কৃত প্যাকেজের জন্য কোনো টাকা ফেরত দেওয়া হবে না, প্রযোজ্য আইন সাপেক্ষে।</p>
                    </div>

                    <!-- 10 -->
                    <div class="term-item">
                        <div class="d-flex align-items-center mb-1">
                            <span class="term-badge-num">10</span>
                            <span class="term-title">SMS Monitoring & Account Suspension</span>
                        </div>
                        <p class="english-p">esmsbd.net reserves the right to monitor SMS traffic and investigate suspected misuse to the extent permitted by applicable law. Any illegal, abusive, fraudulent, suspicious or prohibited content may result in immediate account suspension or permanent termination. Where required, relevant information may be provided to the competent authorities in accordance with applicable laws.</p>
                        <p class="bengali-p"><strong>বাংলা:</strong> esmsbd.net প্রচলিত আইন অনুযায়ী SMS ট্রাফিক পর্যবেক্ষণ এবং সন্দেহজনক অপব্যবহার তদন্ত করার অধিকার সংরক্ষণ করে। কোনো বেআইনি, আপত্তিকর, প্রতারণামূলক, সন্দেহজনক অথবা নিষিদ্ধ কনটেন্ট শনাক্ত হলে তাৎক্ষণিকভাবে অ্যাকাউন্ট সাসপেন্ড বা স্থায়ীভাবে বন্ধ করা হতে পারে। আইন অনুযায়ী প্রয়োজন হলে সংশ্লিষ্ট কর্তৃপক্ষকে প্রয়োজনীয় তথ্য প্রদান করা হতে পারে।</p>
                    </div>
                </div>

                <!-- Customer Agreement & Declaration -->
                <div class="agreement-box">
                    <h5 class="fw-bold text-primary mb-2">
                        <i class="fas fa-handshake me-2"></i> Customer Agreement & Declaration
                    </h5>
                    <p class="english-p fw-semibold text-dark mb-2">
                        I have carefully read and understood all the above Terms & Conditions of esmsbd.net. I agree to comply with each and every rule mentioned above. I understand that any violation may result in account suspension or permanent termination, and I accept responsibility for all SMS activities conducted through my account.
                    </p>
                    <p class="bengali-p text-secondary mb-3">
                        আমি esmsbd.net-এর উপরে উল্লেখিত সকল শর্তাবলি মনোযোগ সহকারে পড়েছি এবং বুঝেছি। আমি উল্লিখিত প্রতিটি নিয়ম মেনে চলতে সম্মত হচ্ছি। আমি অবগত আছি যে, কোনো শর্ত ভঙ্গ করলে আমার অ্যাকাউন্ট সাসপেন্ড অথবা স্থায়ীভাবে বন্ধ করা হতে পারে। আমার অ্যাকাউন্ট থেকে পরিচালিত সকল SMS কার্যক্রমের সম্পূর্ণ দায়ভার আমি গ্রহণ করছি।
                    </p>

                    <div class="p-3 bg-white rounded-3 border">
                        <div class="form-check d-flex align-items-center">
                            <input class="form-check-input me-3" type="checkbox" id="agreeCheckbox" style="width: 22px; height: 22px; cursor: pointer; flex-shrink: 0;">
                            <label class="form-check-label fw-bold text-dark" for="agreeCheckbox" style="cursor: pointer; font-size: 14.5px;">
                                By clicking "I Agree", you confirm that you have read, understood and accepted all Terms & Conditions of esmsbd.net.
                                <span class="d-block text-primary fw-normal small mt-1">(আমি সকল শর্তাবলি পড়েছি, বুঝেছি এবং মেনে চলতে সম্মত হচ্ছি।)</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Step 1 Footer Action -->
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center mt-4 pt-2 gap-3">
                    <a href="{{ route('login') }}" class="text-muted text-decoration-none">
                        <i class="fas fa-arrow-left me-1"></i> {{ __('Already have an account? Login') }}
                    </a>

                    <button type="button" id="btnProceedToSignup" class="btn btn-primary px-4 py-3 fw-bold text-uppercase" style="background: #0d9aff; border-color: #0d9aff; font-size: 15px; border-radius: 8px; letter-spacing: 0.5px; opacity: 0.5; pointer-events: none; transition: all 0.3s ease;">
                        <i class="fas fa-check-circle me-1"></i> {{ __('I Agree & Continue') }} (সম্মত আছি) <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                </div>
            </div>

            <!-- STEP 2: REGISTRATION FORM (100% Mobile & Desktop Responsive) -->
            <div id="step-register-content" style="display: none;">
                <div class="registration-form-box">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold mb-1 text-dark" style="font-size: 1.65rem;">{{ __('Create Your Account') }}</h2>
                        <p class="text-muted small mb-2">{{ __('Please fill up your details to complete registration') }}</p>
                        
                        <div class="alert alert-success d-flex align-items-center justify-content-between py-2 px-3 rounded-3 mt-3 mb-0" style="background: #f0fdf4; border: 1px solid #bbf7d0;">
                            <span class="small fw-semibold text-success d-flex align-items-center">
                                <i class="fas fa-check-circle me-2 fs-6"></i> {{ __('Terms & Conditions Accepted') }}
                            </span>
                            <a href="javascript:void(0)" id="btnBackToTerms" class="text-success small fw-bold text-decoration-underline">
                                <i class="fas fa-undo me-1"></i> {{ __('Review Terms') }}
                            </a>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="ajaxform_instant_reload">
                        @csrf

                        <div class="row g-3">
                            <!-- Full Name -->
                            <div class="col-md-6 col-12">
                                <label class="form-label fw-bold text-dark small mb-1">{{ __('Full Name') }} <span class="text-danger">*</span></label>
                                <div class="custom-input-group">
                                    <i class="fas fa-user custom-input-icon"></i>
                                    <input type="text" name="name" class="form-control custom-form-control custom-input-with-icon" placeholder="e.g. Shakil Ahmed" required>
                                </div>
                            </div>

                            <!-- Username -->
                            <div class="col-md-6 col-12">
                                <label class="form-label fw-bold text-dark small mb-1">{{ __('Username') }} <small class="text-muted fw-normal">({{ __('Optional') }})</small></label>
                                <div class="custom-input-group">
                                    <i class="fas fa-at custom-input-icon"></i>
                                    <input type="text" name="username" class="form-control custom-form-control custom-input-with-icon font-monospace" placeholder="e.g. yourbrand">
                                </div>
                            </div>

                            <!-- Phone Number -->
                            <div class="col-md-6 col-12">
                                <label class="form-label fw-bold text-dark small mb-1">{{ __('Phone Number') }} <span class="text-danger">*</span></label>
                                <div class="custom-input-group">
                                    <i class="fas fa-phone-alt custom-input-icon"></i>
                                    <input type="text" name="phone" class="form-control custom-form-control custom-input-with-icon" placeholder="017XXXXXXXX" required>
                                </div>
                            </div>

                            <!-- Email Address -->
                            <div class="col-md-6 col-12">
                                <label class="form-label fw-bold text-dark small mb-1">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                                <div class="custom-input-group">
                                    <i class="fas fa-envelope custom-input-icon"></i>
                                    <input type="email" name="email" class="form-control custom-form-control custom-input-with-icon" placeholder="name@example.com" required>
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="col-md-6 col-12">
                                <label class="form-label fw-bold text-dark small mb-1">{{ __('Password') }} <span class="text-danger">*</span></label>
                                <div class="custom-input-group">
                                    <i class="fas fa-lock custom-input-icon"></i>
                                    <input type="password" name="password" id="reg_password" class="form-control custom-form-control custom-input-with-icon pe-5" placeholder="{{ __('Password (min 4 characters)') }}" required>
                                    <button type="button" class="custom-pwd-toggle toggle-pwd-btn" data-target="reg_password" aria-label="Toggle Password Visibility">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="col-md-6 col-12">
                                <label class="form-label fw-bold text-dark small mb-1">{{ __('Confirm Password') }} <span class="text-danger">*</span></label>
                                <div class="custom-input-group">
                                    <i class="fas fa-lock custom-input-icon"></i>
                                    <input type="password" name="password_confirmation" id="reg_password_conf" class="form-control custom-form-control custom-input-with-icon pe-5" placeholder="{{ __('Confirm Password') }}" required>
                                    <button type="button" class="custom-pwd-toggle toggle-pwd-btn" data-target="reg_password_conf" aria-label="Toggle Password Visibility">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="country" value="Bangladesh">
                        <input type="hidden" name="terms" value="1">

                        <div class="mt-4">
                            <button type="submit" class="btn submit-btn w-100 py-3 fw-bold text-white text-uppercase" style="background: linear-gradient(135deg, #0d9aff, #055dd6); border-radius: 8px; font-size: 15px; letter-spacing: 0.5px; border: none; box-shadow: 0 4px 15px rgba(13, 154, 255, 0.35);">
                                <i class="fas fa-user-check me-2"></i> {{ __('Complete Registration') }}
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            <a href="{{ route('login') }}" class="text-muted text-decoration-none small">
                                {{ __("Already have an account?") }} <span class="text-primary fw-semibold">{{ __('Login here') }}</span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" data-model="Registration" id="auth">
@endsection

@push('js')
<script src="{{ asset('assets/js/auth.js') }}"></script>
<script>
$(document).ready(function() {
    // Checkbox enables "I Agree" button
    $('#agreeCheckbox').on('change', function() {
        if ($(this).is(':checked')) {
            $('#btnProceedToSignup').css({
                'opacity': '1',
                'pointer-events': 'auto'
            });
        } else {
            $('#btnProceedToSignup').css({
                'opacity': '0.5',
                'pointer-events': 'none'
            });
        }
    });

    // Proceed to Signup Step 2
    $('#btnProceedToSignup').on('click', function() {
        if ($('#agreeCheckbox').is(':checked')) {
            $('#step-terms-content').slideUp(300, function() {
                $('#step-register-content').slideDown(300);
            });
            $('#badge-step-1').removeClass('active').addClass('inactive');
            $('#badge-step-2').removeClass('inactive').addClass('active');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });

    // Back to Terms Step 1
    $('#btnBackToTerms').on('click', function() {
        $('#step-register-content').slideUp(300, function() {
            $('#step-terms-content').slideDown(300);
        });
        $('#badge-step-2').removeClass('active').addClass('inactive');
        $('#badge-step-1').removeClass('inactive').addClass('active');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // Custom Password Toggle
    $(document).on('click', '.toggle-pwd-btn', function() {
        let targetId = $(this).data('target');
        let input = $('#' + targetId);
        let icon = $(this).find('i');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
});
</script>
@endpush
