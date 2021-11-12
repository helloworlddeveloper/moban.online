<!-- BEGIN: main -->
<!-- BEGIN: lib -->
<script type="text/javascript" src="{LIB_PATH}js/owl.carousel.min.js"></script>

<link href="{LIB_PATH}css/jquery.bxslider.css" rel="stylesheet" />
<!-- END: lib -->
<script type="text/javascript">
    var owl = $('.slider_{BLOCKID}');
    owl.owlCarousel({
        loop:true,
        margin:{MARGIN},
        autoplay:{AUTO},
        autoplayTimeout:{SPEED},
        autoplayHoverPause:true,
        responsiveClass:true,
        responsive:{
            0:{
                items:3
            },
            321:{
                items:4
            },
            768:{
                items:{NUMVIEW}
            }
        }
    });
</script>
<div class="slider_{BLOCKID} owl-carousel logobao">
    <!-- BEGIN: items -->
    <p><a class="thumb_link" href="{DATA.link}" title="{DATA.title}"><img src="{DATA.image}"></a></p>
    <!-- END: items -->
</div>
<!-- END: main -->



