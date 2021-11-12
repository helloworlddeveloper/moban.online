<!-- BEGIN: main -->
<link href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/jquery.bxslider.min.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/jquery.bxslider.min.js"></script>
<ul class="bxslider otherClips" id="flexisel{bid}">
    <!-- BEGIN: loop -->
    <li>
        <a class="vImg" title="{ROW.title}" href="{ROW.href}" ><img src="{ROW.img}" alt="{ROW.title}"/></a><span class="play">&nbsp;</span>
        <div style="padding: 10px" class="text-center"><strong>{ROW.sortTitle}</strong></div>
    </li>
    <!-- END: loop -->
</ul>
<div class="clearout"></div>
<script type="text/javascript">
    $('#flexisel{bid}').bxSlider({
        minSlides: 1,
        auto: true,
        pager: false,
        maxSlides: 4,
        slideWidth: 280,
        slideMargin: 10
    });
    $('.bx-wrapper').css('margin', '10px auto')
</script>
<!-- END: main -->