<!-- BEGIN: main -->
<!-- BEGIN: lib -->
<link rel="stylesheet" type="text/css" href="{LIB_PATH}/carousel/owl.carousel.min.css">
<script src="{LIB_PATH}/carousel/owl.carousel.min.js" type="text/javascript" charset="utf-8"></script>
<!-- END: lib -->

<div class="animation" data-animation="fadeInUp" data-animation-delay="0.01s">
    <div class="cl_logo_slider carousel_slider owl-item-3 owl-carousel owl-theme" data-margin="15" data-loop="true" data-nav="true" data-autoplay="true" data-dots="false" data-responsive='{"0":{"items": "2"}, "380":{"items": "2"}, "600":{"items": "4"}, "1000":{"items": "5"}, "1199":{"items": "5"}}'>
        <!-- BEGIN: items -->
        <div class="item">
            <a target="_blank"" title=" title="{DATA.title}"" href="{DATA.link}">
            <img title="{DATA.title}" src="{DATA.image}"  alt="{DATA.title}"/>
            </a>
        </div>
        <!-- END: items -->
    </div>
</div>

<script>
    $( window ).on( "load", function() {
        $('.carousel_slider').each( function() {
            var $carousel = $(this);
            $carousel.owlCarousel({
                dots : $carousel.data("dots"),
                loop : $carousel.data("loop"),
                margin: $carousel.data("margin"),
                items: $carousel.data("items"),
                mouseDrag: $carousel.data("mouse-drag"),
                touchDrag: $carousel.data("touch-drag"),
                center: $carousel.data("center"),
                autoHeight: $carousel.data("autoheight"),
                rewind: $carousel.data("rewind"),
                nav: $carousel.data("nav"),
                navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
                autoplay : $carousel.data("autoplay"),
                animateIn : $carousel.data("animate-in"),
                animateOut: $carousel.data("animate-out"),
                autoplayTimeout : $carousel.data("autoplay-timeout"),
                smartSpeed: $carousel.data("smart-speed"),
                responsive: $carousel.data("responsive")
            });
        });
    });
</script>
<!-- END: main -->



