<!-- BEGIN: main -->
<!-- BEGIN: lib -->
<script type="text/javascript" src="{LIB_PATH}js/owl.carousel.min.js"></script>
<link href="{LIB_PATH}css/jquery.bxslider.css" rel="stylesheet" />
<!-- END: lib -->
<script type="text/javascript">
    var owl = $('.slider_review_{BLOCKID}');
    owl.owlCarousel({
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
                items:1
            }
        }
    });
</script>
<div class="slider_review_{BLOCKID} owl-carousel">
    <div class="row">
        <!-- BEGIN: items -->
        <div class="col-md-4 col-xs-12">
            <div class="toicogi-item toicogi-item2">
                <img class="avata" alt="{DATA.title}" src="{DATA.image}">
                <p class="name">{DATA.title}</p>
                <p class="otoke">{DATA.bodytext}</p>
            </div>
        </div>
        <!-- END: items -->
    </div>
</div>
<!-- END: main -->



