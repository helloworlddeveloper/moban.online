<!-- BEGIN: main -->
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'> <!-- Google web font link-->
<link type="text/css" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/custom.css" rel="stylesheet" />  <!--CUSTOM CSS FILE-->
<link type="text/css" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/animate.css" rel="stylesheet" />  <!--animate.css FILE-->
<section>
    <div class="container">
        <div class="row row1">
            <div class="col-md-24">
                <h3 class="center capital f1 wow fadeInLeft" data-wow-duration="2s">{title_response}</h3>
                <h1 id="error" class="center wow fadeInRight" data-wow-duration="2s">0</h1>
                <p class="center wow bounceIn" data-wow-delay="2s">{description_response}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-24">
                <div id="cflask-holder" class="wow fadeIn" data-wow-delay="2800ms">
                    <span class="wow tada " data-wow-delay="3000ms"><i class="fa fa-flask fa-5x flask wow flip" data-wow-delay="3300ms"></i> 
                        <i id="b1" class="bubble"></i>
                        <i id="b2" class="bubble"></i>
                        <i id="b3" class="bubble"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="row"> <!--Links Start-->
            <div class="col-md-24">
                <div class="links-wrapper col-md-12 col-md-offset-9">
                    <ul class="links col-md-24">
                        <li class="wow fadeInRight" data-wow-delay="4400ms"><a href="{NV_MY_DOMAIN}"><i class="fa fa-home fa-2x"></i></a></li>
                        <li class="wow fadeInRight" data-wow-delay="4300ms"><a href="https://facebook.com/daytot.vn"><i class="fa fa-facebook fa-2x"></i></a></li>
                        <li class="wow fadeInRight" data-wow-delay="4200ms"><a href="https://twitter.com/daytot_vn"><i class="fa fa-twitter fa-2x"></i></a></li>
                        <li class="wow fadeInLeft" data-wow-delay="4200ms"><a href="https://www.youtube.com/c/DaytotVn-Day-La-Tot"><i class="fa fa-youtube fa-2x"></i></a></li>
                    </ul>
                </div>
            </div>

        </div> <!-- Links End--> 
    </div>
</section>

<!--ADDING THE REQUIRED SCRIPT FILES-->
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/countUp.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/wow.js"></script>
<!--Initiating the CountUp Script-->
<script type="text/javascript">
    "use strict";
    var count = new countUp("error", 0, '{num_error_reponse}', 0, 3);
    window.onload = function() {
        // fire animation
        count.start();
    }
</script>
<!--Initiating the Wow Script-->
<script>  
    "use strict";
    var wow = new WOW(
    {
        animateClass: 'animated',
        offset:       100
    }
);
    wow.init();
</script>
<!-- END: main -->