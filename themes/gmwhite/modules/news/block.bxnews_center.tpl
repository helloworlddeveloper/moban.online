<!-- BEGIN: main -->
<!-- BEGIN: lib -->
<script data-show="after" type="text/javascript" src="{LIB_PATH}js/jquery.bxslider.min.js"></script>
<link href="{LIB_PATH}css/jquery.bxslider.css" rel="stylesheet" />
<!-- END: lib -->
<script type="text/javascript">
    $(document).ready(function(){
        $('.slider_{BLOCKID}').bxSlider({
            auto:{AUTO},
            mode:'{MODE}',
            speed:{SPEED},
            slideWidth:{WIDTH},
            slideMargin:{MARGIN},
            minSlides:{NUMVIEW},
            maxSlides:{NUMVIEW},
            moveSlides:{MOVE},
            pager:{PAGER},
            adaptiveHeight: true
        });
    });
</script>
<div class="slider_{BLOCKID}">
    <!-- BEGIN: items -->
    <div class="slider_{BLOCKID}_item item wow bounceInUp">
            <div class="bigimg2">
                <a class="thumb_link" href="{DATA.link}" title="{DATA.title}">
                    <img width="350" height="350" src="{DATA.thumb}" class="alignleft wp-post-image" alt="{DATA.title}" />
                    <div class="clear"></div>
                </a>
            </div>
            <div>
                <div class='floadright'>
                    <h4 class="tieude"><a href="{DATA.link}" rel="bookmark" title="{DATA.title}">{DATA.title}</a></h4>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    <!-- END: items -->
</div>
<!-- END: main -->

