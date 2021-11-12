<!-- BEGIN: main -->
<!-- BEGIN: lib -->
<link rel="stylesheet" type="text/css" href="{LIB_PATH}/slick/slick.css">
<link rel="stylesheet" type="text/css" href="{LIB_PATH}/slick/slick-theme.css">

<script src="{LIB_PATH}/slick/slick.js" type="text/javascript" charset="utf-8"></script>
<!-- END: lib -->
<script type="text/javascript">
    $(".variable{BLOCKID}").slick({
        dots: false,
        infinite: true,
        speed: 300,
        slidesToShow: 5,
        slidesToScroll: 5,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 4,
                    infinite: true,
                    dots: true
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            }
        ]
    });
</script>
<section class="variable{BLOCKID} slider">
    <!-- BEGIN: items -->
    <div class="padding-small">
        <!-- BEGIN: loop -->
        <div class="content__ally__item position-relative">
            <a href="{DATA.link}"><img  data-toggle="tooltip" data-placement="top" class="content__ally__item__img img-fluid w-100" src="{DATA.image}" alt="{DATA.title}"></a>
            <div class="{class} position-absolute">{DATA.bodytext}</div>
        </div>
        <!-- END: loop -->
    </div>
    <!-- END: items -->
</section>
<!-- END: main -->



