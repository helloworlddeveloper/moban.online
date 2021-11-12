<!-- BEGIN: main -->
<div class="responsive-slider" data-spy="responsive-slider" data-autoplay="true">
    <div class="slides" data-group="slides">
        <ul>
            <!-- BEGIN: slide -->
            <li>
                <div class="slide-body" data-group="slide">
                    <a href="{ROW.link}"><img src="{ROW.image}" /></a>
                    <div class="slidetitle" style="display: none;">&nbsp;&nbsp;{ROW.title}</div>
                </div>
            </li>
            <!-- END: slide -->
        </ul>
    </div>
    <!-- BEGIN: showpage -->
    <div class="pages">
        <!-- BEGIN: numpage -->
        <a class="page" href="#" data-jump-to="{num}">{num}</a>
        <!-- END: numpage -->
    </div>
    <!-- END: showpage -->
</div>
<link href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/responsive-slider.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/responsive-slider.js"></script>
<!-- END: main -->