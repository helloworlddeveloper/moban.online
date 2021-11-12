<!-- BEGIN: main -->
<!-- BEGIN: lib -->
<script type="text/javascript" src="{LIB_PATH}js/owl.carousel.min.js"></script>
<link href="{LIB_PATH}css/jquery.bxslider.css" rel="stylesheet" />
<!-- END: lib -->
<script type="text/javascript">
    $('.slider_{BLOCKID}').owlCarousel({
        loop:true,
        margin:{MARGIN},
        autoplay:{AUTO},
        autoplayTimeout:{SPEED},
        autoplayHoverPause:true,
        responsiveClass:true,
        responsive:{
            0:{
                items:1
            },
            321:{
                items:1
            },
            768:{
                items:{NUMVIEW}
            }
        }
    });
</script>
<div class="slider_{BLOCKID} owl-carousel logobao2">
    <!-- BEGIN: items -->
    <div>
        <a target="_blank" title="{DATA.title}" href="{DATA.link}">
            <img class="img-logo" alt="{DATA.title}" src="{DATA.image}">
        </a>
        <div class="motabao">
            <a target="_blank" href="{DATA.link}">
                {DATA.bodytext}
                <span class="click_open">Mở bài báo</span></a>
        </div>
    </div>
    <!-- END: items -->
</div>
<!-- END: main -->



