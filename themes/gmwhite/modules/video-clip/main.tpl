<!-- BEGIN: main -->
<div>
    <!-- BEGIN: topicList -->
    <h5 class="widget_title">DANH MỤC VIDEO</h5>
    <div class="row" style="padding-bottom: 20px">
        <!-- BEGIN: loop -->
        <div class="col-lg-6 col-md-12">
            <div class="blog_post box_shadow1 radius_all_10 animation animated fadeInUp">
                <div style="height: 190px" class="blog_img radius_ltrt_10">
                    <a title="{OTHERTOPIC.title}" href="{OTHERTOPIC.link}">
                        <img src="{OTHERTOPIC.thumb}" alt="{OTHERTOPIC.title}" />
                    </a>
                </div>
                <div class="blog_footer bg-white radius_lbrb_10">
                    <a title="{OTHERTOPIC.title}" href="{OTHERTOPIC.link}"><strong>{OTHERTOPIC.title}</strong></a>
                </div>
            </div>
        </div>
        <!-- END: loop -->
    </div>
    <!-- END: topicList -->
</div>

<div class="videoMain clearfix">
        <div id="VideoPageData" class="row justify-content-center">
        <!-- BEGIN: otherClips -->
            <div class="row">
        <!-- BEGIN: otherClipsContent -->
            <div class="col-lg-6 col-sm-12">
                <div class="team_box team_style1 box_shadow1">
                    <div class="img-video">
                        <a href="{OTHERCLIPSCONTENT.href}"><img src="{OTHERCLIPSCONTENT.img}" alt="{OTHERCLIPSCONTENT.title}"></a>
                    </div>
                    <div class="team_title radius_lbrb_10 text-center">
                        <h3><a href="{OTHERCLIPSCONTENT.href}">{OTHERCLIPSCONTENT.title}</a></h3>
                    </div>
                </div>
            </div>

        <!-- END: otherClipsContent -->
            </div>
            <!-- BEGIN: nv_generate_page -->
            <div class="generate_page" style="padding-top: 20px">
                {NV_GENERATE_PAGE}
            </div>
            <!-- END: nv_generate_page -->
        <!-- END: otherClips -->
        </div>
</div>
<script type="text/javascript">
$(function(){
    $('.videoMain .topicList').click(function(){
        if( $(this).hasClass('sub') ){
            $('.videoMain .topicList.sub').removeClass('current');
            $(this).addClass('current');
            $(this).parent().show();
            $(this).parent().prev().addClass('current');
        }else{
            if( ! $(this).hasClass('current') ){
                $('.videoMain .topicList:not(.sub)').removeClass('current');
                $(this).addClass('current');
                $('.col1-sub').hide();
                if( $(this).next().attr('class') == 'col1-sub' ){
                    $(this).next().show();
                }
            }
        }
        $('#VideoPageData').load($(this).attr('rel'), function(){
            responsiveVideoGird()
        });
        $('.col1.open').removeClass('open');
    });
});
$(window).on('load', function(){
    var ele = $('.videoMain .col1').find('.current');
    ele.trigger('click');
});
</script>
<!-- END: main -->