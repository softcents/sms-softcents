<footer class="container-fluid bg-white d-flex align-items-center justify-content-center justify-content-sm-between flex-wrap py-4 mt-4 ms-0">
    <p>{{ get_option('general')['copyright'] ?? '' }}</p>
    <h6>{{ __('Developed by') }} <a href="javascript:void(0)" class="arabic-primary-clr">{{ get_option('general')['develop_by'] ?? '' }}</a></h6>
</footer>
