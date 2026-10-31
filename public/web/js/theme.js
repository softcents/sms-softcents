
(function ($) {

    "use strict";
        //* Navbar Fixed
        function navbarFixed(){
            if ( $('header').length ){
                $(window).on('scroll', function() {
                    var scroll = $(window).scrollTop();
                    if (scroll >=90) {
                        $("header").addClass("navbar-fixed").fadeIn();
                    } else {
                        $("header").removeClass("navbar-fixed");
                    }
                });
            };
        };
        navbarFixed()
    // mobile menu
    function MobileMenu (){
        $('.sidebar-btn').click (function(){
            $(".sidebar-close-btn, .overlay").on("click", function(){
                $(".overlay, .header-main-menu-wrapper").removeClass("nav_activee");
            });
            $('.overlay, .header-main-menu-wrapper').toggleClass("nav_activee");
        });
        if($('.mobile-menu li.dropdown .dropdown-menu').length){
            $('.mobile-menu li.dropdown').append('<div class="dropdown-btn"><span class="fa fa-angle-down"></span></div>');
            $('.mobile-menu li.dropdown .dropdown-btn').on('click', function() {
                $(this).prev('.dropdown-menu').slideToggle(500);
            });
        }
    }
    MobileMenu ();

    // data background
    function bgImg() {
        $("[data-background]").each(function() {
            $(this).css("background-image", "url(" + $(this).attr("data-background") + ")");
        });
    };
    bgImg();


    var $swiperSelector = $('.creative-interface-slider');
        $swiperSelector.each(function(index) {
            var $this = $(this);
            $this.addClass('swiper-sliderone-' + index);

            var dragSize = $this.data('drag-size') ? $this.data('drag-size') : 50;
            var freeMode = $this.data('free-mode') ? $this.data('free-mode') : false;
            var loop = $this.data('loop') ? $this.data('loop') : true;
            var slidesDesktop = $this.data('slides-desktop') ? $this.data('slides-desktop') : 4;
            var slideslaptop = $this.data('slides-laptop') ? $this.data('slides-laptop') : 4;
            var slidesTablet = $this.data('slides-tablet') ? $this.data('slides-tablet') : 4;
            var slidesMobile = $this.data('slides-mobile') ? $this.data('slides-mobile') : 2.2;
            var spaceBetween = $this.data('space-between') ? $this.data('space-between'): 20;

            var swiper7 = new Swiper('.swiper-sliderone-' + index, {
            direction: 'horizontal',
            loop: loop,
            freeMode: freeMode,
            spaceBetween: spaceBetween,
            breakpoints: {
                1920: {
                slidesPerView: slidesDesktop
                },
                1024: {
                slidesPerView: slideslaptop
                },
                767: {
                slidesPerView: slidesTablet
                },
                320: {
                slidesPerView: slidesMobile
                }
            },
            navigation: {
                nextEl: '.next',
                prevEl: '.prev'
            },
            scrollbar: {
                el: '.swiper-scrollbar',
                draggable: true,
                dragSize: dragSize
            }
        });
        });



    var $swiperSelectorOne = $('.premium-product-slide');
    $swiperSelectorOne.each(function(index) {
        var $this = $(this);
        $this.addClass('swiper-slidertwo-' + index);

        var dragSize = $this.data('drag-size') ? $this.data('drag-size') : 50;
        var freeMode = $this.data('free-mode') ? $this.data('free-mode') : false;
        var loop = $this.data('loop') ? $this.data('loop') : true;
        var slidesDesktop = $this.data('slides-desktop') ? $this.data('slides-desktop') : 3;
        var slideslaptop = $this.data('slides-laptop') ? $this.data('slides-laptop') : 3;
        var slidesTablet = $this.data('slides-tablet') ? $this.data('slides-tablet') : 2.5;
        var slidesMobile = $this.data('slides-mobile') ? $this.data('slides-mobile') : 1.2;
        var spaceBetween = $this.data('space-between') ? $this.data('space-between'): 20;

        var swiper5 = new Swiper('.swiper-slidertwo-' + index, {
        direction: 'horizontal',
        loop: loop,
        freeMode: freeMode,
        spaceBetween: spaceBetween,
        breakpoints: {
            1920: {
            slidesPerView: slidesDesktop
            },
            1024: {
            slidesPerView: slideslaptop
            },
            767: {
            slidesPerView: slidesTablet
            },
            320: {
            slidesPerView: slidesMobile
            }
        },
        navigation: {
            nextEl: '.next1',
            prevEl: '.prev1'
        },
        scrollbar: {
            el: '.swiper-scrollbar',
            draggable: true,
            dragSize: dragSize
        }
    });
    });

    var $swiperSelectorTwo = $('.testimonial-slider');
    $swiperSelectorTwo.each(function(index) {
        var $this = $(this);
        $this.addClass('swiper-sliderthree-' + index);

        var dragSize = $this.data('drag-size') ? $this.data('drag-size') : 50;
        var freeMode = $this.data('free-mode') ? $this.data('free-mode') : false;
        var loop = $this.data('loop') ? $this.data('loop') : true;
        var slidesDesktop = $this.data('slides-desktop') ? $this.data('slides-desktop') : 3;
        var slideslaptop = $this.data('slides-laptop') ? $this.data('slides-laptop') : 3;
        var slidesTablet = $this.data('slides-tablet') ? $this.data('slides-tablet') : 1.5;
        var slidesMobile = $this.data('slides-mobile') ? $this.data('slides-mobile') : 1;
        var spaceBetween = $this.data('space-between') ? $this.data('space-between'): 20;

        var swiper2 = new Swiper('.swiper-sliderthree-' + index, {
        direction: 'horizontal',
        loop: loop,
        freeMode: freeMode,
        spaceBetween: spaceBetween,
        breakpoints: {
            1920: {
            slidesPerView: slidesDesktop
            },
            1024: {
            slidesPerView: slideslaptop
            },
            767: {
            slidesPerView: slidesTablet
            },
            320: {
            slidesPerView: slidesMobile
            }
        },
        navigation: {
            nextEl: '.next2',
            prevEl: '.prev2'
        },
        scrollbar: {
            el: '.swiper-scrollbar',
            draggable: true,
            dragSize: dragSize
        }
    });
    });



    var swiper = new Swiper(".admin-panel-slider", {
        slidesPerView: 1,
        spaceBetween: 30,
        autoplay: false,
        loop: true,
        speed: 1400,
        autoplay:{
            delay:6000,
        },
        navigation: {
            nextEl: ".next5",
            prevEl: ".prev5",
        },
    });


})(jQuery);

