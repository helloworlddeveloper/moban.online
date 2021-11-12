<!-- BEGIN: main -->
<div class="text-center clearfix">
    <a href="{send_data}" class="btn btn-success"><i class="fa fa-database">&nbsp;</i><span style="color: #fff">{LANG.send_data}</span></a>
    <p><i>Gửi cho chúng tôi dữ liệu bạn muốn chia sẻ! Chúng tôi luôn đánh giá cao các đóng góp của bạn.</i></p>
</div>
<!-- BEGIN: cat -->
<!-- BEGIN: loop -->
<div class="col-xs-24 col-sm-12 col-md-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <h2><a title="{CAT.title}" href="{CAT.link}"><i class="fa fa-database">&nbsp;</i><strong>{CAT.title}</strong></a></h2>
        </div>
    </div>
</div>
<!-- END: loop -->
<!-- END: cat -->
<script src="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/{MODULE_FILE}/jquery.copiq.js"></script>
<!-- BEGIN: content -->
<div class="col-xs-24 col-sm-12 col-md-24">
    <div class="panel panel-default">
        <!-- BEGIN: loop -->
        <div class="panel-body">
            <h5>
                <strong style="color: #0a6fd2">Bài {CAT.stt}: </strong><a title="{CAT.title}" href="{CAT.link_detail}"><strong>{CAT.title}</strong></a>
            </h5>
            <p class="more" id="content_{CAT.id}">{CAT.bodytext} <span>[<a style="color: #0FA015" title="{CAT.title}" href="{CAT.link_detail}">Xem chi tiết</a>]</span></p>
        </div>
        <!-- END: loop -->
    </div>
</div>

<div class="clearfix">&nbsp;</div>
<!-- BEGIN: generate_page -->
<div class="text-center">{PAGE}</div>
<!-- END: generate_page -->
<script>
    $('button').copiq({
        parent: '.content_data',
        content: '.more',
        onSuccess: function($element, source, selection) {
            $('span', $element).text($element.attr("data-text-copied"));
            setTimeout(function() {
                $('span', $element).text($element.attr("data-text"));
            }, 2000);
        }
    });
</script>
<!-- END: content -->
<!-- BEGIN: video -->
<!-- BEGIN: loop -->
<div class="col-xs-24 col-sm-12 col-md-8">
    <div class="panel panel-default">
        <div class="panel-body">
            <h4>
                <p style="height: 50px; overflow: hidden"><i class="fa fa-database">&nbsp;</i><strong>{CAT.title}</strong></p>
                <button class="btn btn-default" data-text="Sao chép" data-text-copied="Đã sao chép">
                    <span>Sao chép</span>
                </button>
            </h4>
            <!-- BEGIN: iframe -->
            <div class="youtube-embed-wrapper" style="position:relative;padding-bottom:56.25%;padding-top:30px;height:0;overflow:hidden">
                <iframe allowfullscreen="" frameborder="0" height="480" src="https://youtube.com/embed/{VIDEOID}?rel=0&amp;autoplay=0" style="position:absolute;top:0;left:0;width:100%;height:100%" width="640"></iframe>
            </div>
            <!-- END: iframe -->
            <p class="more" id="content_{CAT.id}">
                {CAT.bodytext}
            </p>
        </div>
    </div>
</div>
<!-- END: loop -->
<div class="clearfix">&nbsp;</div>
<!-- BEGIN: generate_page -->
<div class="text-center">{PAGE}</div>
<!-- END: generate_page -->
<!-- END: video -->
<!-- BEGIN: link -->
<!-- BEGIN: loop -->
<div class="col-xs-24 col-sm-12 col-md-24">
    <div class="panel panel-default">
        <div class="panel-body">
            <!-- BEGIN: havelink -->
            <h2>
                <a target="_blank" title="{CAT.title}" href="{CAT.link}"><i class="fa fa-database">&nbsp;</i><strong>{CAT.title}</strong></a>
            </h2>
            <!-- END: havelink -->
            <!-- BEGIN: nolink -->
            <h2>
                <i class="fa fa-database">&nbsp;</i><strong>{CAT.title}</strong>
            </h2>
            <!-- END: nolink -->
            <p class="more" id="content_{CAT.id}">{CAT.bodytext}</p>
        </div>
    </div>
</div>
<!-- END: loop -->
<div class="clearfix">&nbsp;</div>
<!-- BEGIN: generate_page -->
<div class="text-center">{PAGE}</div>
<!-- END: generate_page -->
<!-- END: link -->
<script>
    $('button').copiq({
        parent: '.panel-body',
        content: '.more',
        onSuccess: function($element, source, selection) {
            $('span', $element).text($element.attr("data-text-copied"));
            setTimeout(function() {
                $('span', $element).text($element.attr("data-text"));
            }, 2000);
        }
    });
</script>
<!-- END: main -->