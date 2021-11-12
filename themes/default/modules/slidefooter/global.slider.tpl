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
    <a class="slider-control left" href="#" data-jump="prev"><i class="fa fa-chevron-left fa-3"></i></a>
    <a class="slider-control right" href="#" data-jump="next"><i class="fa fa-chevron-right fa-3"></i></a>
</div>
<link href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/responsive-slider.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/responsive-slider.js"></script>
<!-- END: main -->