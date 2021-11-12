<!-- BEGIN: main -->
<div class="row justify-content-center">
    <div class="col-6 animation" data-animation="fadeInUp" data-animation-delay="0.02s">
        <div class="testimonial_slider testimonial_style1 carousel_slider owl-carousel owl-theme" data-margin="10" data-loop="true" data-autoplay="true" data-dots="false" data-responsive='{"0":{"items": "1"}, "576":{"items": "2"}, "1199":{"items": "4"}}'>
            <!-- BEGIN: items -->
            <div class="testimonial_box">
                <div class="testimonial_img">
                    <img class="radius_all_5" src="{DATA.image}" alt="client">
                </div>
                <div class="testi_meta">
                    <div class="testi_user">
                        {DATA.title}
                        <p class="text_default">{DATA.jobs}</p>
                    </div>
                    <div class="testi_desc">
                        <p>{DATA.bodytext_cut}</p>
                    </div>
                    <div class="viewmore text-center">
                        <a href="{DATA.link}">XEM THÊM&nbsp;<i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <!-- END: items -->
        </div>
    </div>
</div>

<!-- END: main -->



