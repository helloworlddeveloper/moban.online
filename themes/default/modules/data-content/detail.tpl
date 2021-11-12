<!-- BEGIN: main -->
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/star-rating/jquery.rating.pack.js"></script>
<script src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/star-rating/jquery.MetaData.js" type="text/javascript"></script>
<link href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/star-rating/jquery.rating.css" type="text/css" rel="stylesheet"/>
<div class="text-center clearfix">
    <a href="{send_data}" class="btn btn-success"><i class="fa fa-database">&nbsp;</i><span style="color: #fff">{LANG.send_data}</span></a>
    <p><i>Gửi cho chúng tôi dữ liệu bạn muốn chia sẻ! Chúng tôi luôn đánh giá cao các đóng góp của bạn.</i></p>
</div>
<script src="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/{MODULE_FILE}/jquery.copiq.js"></script>
<!-- BEGIN: content -->
<div class="panel panel-default">
    <div class="panel-body">
        <!-- BEGIN: havelink -->
        <h4>
            <a target="_blank" title="{CAT.title}" href="{CAT.link}"><i class="fa fa-database">&nbsp;</i><strong>{CAT.title}</strong></a>
            <button class="btn btn-default" data-text="Sao chép" data-text-copied="Đã sao chép">
                <span>Sao chép</span>
            </button>
        </h4>
        <!-- END: havelink -->
        <!-- BEGIN: nolink -->
        <h4>
            <i class="fa fa-database">&nbsp;</i><strong>{CAT.title}</strong>
            <button class="btn btn-default" data-text="Sao chép" data-text-copied="Đã sao chép">
                <span>Sao chép</span>
            </button>
        </h4>
        <!-- END: nolink -->
        <div class="bodytext">
            <p><strong>{CAT.title}</strong></p>
            {CAT.bodytext}
        </div>
    </div>
</div>
<div class="clearfix">&nbsp;</div>
<!-- END: content -->
<!-- BEGIN: video -->
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
        <p class="bodytext">
            {CAT.bodytext}
        </p>
    </div>
</div>
<!-- END: video -->
<!-- BEGIN: link -->
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
        <p class="bodytext">{CAT.bodytext}</p>
    </div>
</div>
<!-- END: link -->
<script>
    $('button').copiq({
        parent: '.panel-body',
        content: '.bodytext',
        onSuccess: function($element, source, selection) {
            $('span', $element).text($element.attr("data-text-copied"));
            setTimeout(function() {
                $('span', $element).text($element.attr("data-text"));
            }, 2000);
        }
    });
</script>
<div class="news_column panel panel-default">
    <div class="panel-body">
        <form id="form3B" action="">
            <div class="h5 clearfix">
                <p id="stringrating">{STRINGRATING}</p>
                <span itemscope itemtype="http://data-vocabulary.org/Review-aggregate">{LANG.rating_average}:
                    <span itemprop="rating" id="numberrating">{CAT.numberrating}</span> -
                    <span itemprop="votes" id="click_rating">{CAT.click_rating}</span> {LANG.rating_count}
                </span>
                <div style="padding: 5px;">
                    <input class="hover-star" type="radio" value="1" title="{LANGSTAR.verypoor}" /><input class="hover-star" type="radio" value="2" title="{LANGSTAR.poor}" /><input class="hover-star" type="radio" value="3" title="{LANGSTAR.ok}" /><input class="hover-star" type="radio" value="4" title="{LANGSTAR.good}" /><input class="hover-star" type="radio" value="5" title="{LANGSTAR.verygood}" /><span id="hover-test" style="margin: 0 0 0 20px;">{LANGSTAR.note}</span>
                </div>
            </div>
        </form>
        <script type="text/javascript">
            $(function() {
                var sr = 0;
                $(".hover-star").rating({
                    focus: function(b, c) {
                        var a = $("#hover-test");
                        2 != sr && (a[0].data = a[0].data || a.html(), a.html(c.title || "value: " + b), sr = 1)
                    },
                    blur: function(b, c) {
                        var a = $("#hover-test");
                        2 != sr && ($("#hover-test").html(a[0].data || ""), sr = 1)
                    },
                    callback: function(b, c) {
                        1 == sr && (sr = 2, $(".hover-star").rating("disable"), sendrating("{CAT.id}", b, "{NEWSCHECKSS}"))
                    }
                });
                $(".hover-star").rating("select", "{NUMBERRATING}");
                <!-- BEGIN: disablerating -->
                $(".hover-star").rating('disable');
                sr = 2;
                <!-- END: disablerating -->
            })
        </script>
    </div>
</div>
<!-- END: main -->