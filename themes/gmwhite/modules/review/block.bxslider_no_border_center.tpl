<!-- BEGIN: main -->
<div class="row">
    <div class="col-md-12 animation" data-animation="fadeInUp" data-animation-delay="0.01s">
        <div class="cl_logo_slider no-boder carousel_slider owl-carousel owl-theme" data-margin="15" data-loop="true" data-nav="true" data-autoplay="true" data-dots="false" data-responsive='{"0":{"items": "2"}, "380":{"items": "3"}, "600":{"items": "4"}, "1000":{"items": "5"}, "1199":{"items": "6"}}'>
            <!-- BEGIN: items -->
            <div class="item">
                <a target="_blank"" title=" title="{DATA.title}"" href="{DATA.link}">
                    <img title="{DATA.bodytext}" src="{DATA.image}"  alt="{DATA.title}"/>
                    <p style="padding-top: 10px" class="text-center">{DATA.title}</p>
                </a>
            </div>
            <!-- END: items -->
        </div>
    </div>
</div>
<style>
    .no-boder .owl-nav [class*="owl-"]{
        top: 38%;
    }
</style>
<!-- END: main -->