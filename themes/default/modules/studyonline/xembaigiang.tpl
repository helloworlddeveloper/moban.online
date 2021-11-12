<!-- BEGIN: main -->
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/star-rating/jquery.rating.pack.js"></script>
<script src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/star-rating/jquery.MetaData.js" type="text/javascript"></script>
<link href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/star-rating/jquery.rating.css" type="text/css" rel="stylesheet"/>
<link href="{NV_BASE_SITEURL}{NV_EDITORSDIR}/ckeditor/plugins/codesnippet/lib/highlight/styles/github.css" rel="stylesheet">
<div class="news_column panel panel-default">
	<div class="panel-body">
		<h1 class="title margin-bottom-lg">{CONTENT_BAIHOC.title}</h1>
		<!-- BEGIN: no_public -->
		<div class="alert alert-warning">
			{LANG.no_public}
		</div>
		<!-- END: no_public -->
        <div class="row">
            <div class="col-xs-24 col-sm-24 col-md-12">
    				<!-- BEGIN: image -->
            		<figure class="article center">
            			<img alt="{CONTENT_BAIHOC.titleseo}" src="{CONTENT_BAIHOC.image}" class="img-thumbnail" />
            		</figure>
            		<!-- END: image -->
    		</div>
            <div class="col-xs-24 col-sm-24 col-md-12">
                <table class="table table-bordered">
                    <!-- BEGIN: teacherinfo -->
                    <tr>
                        <td>{LANG.teacher}</td>
                        <td>
                            <ul>
                            <!-- BEGIN: loop -->
                            <li><a href="{TEACHERINFO.teacher_link}" title="{TEACHERINFO.teacher_name}">{TEACHERINFO.teacher_name}</a></li>
                            <!-- END: loop -->
                            </ul>
                        </td>
                    </tr>
                    <!-- END: teacherinfo -->
                    <tr>
                        <td>{LANG.thuockhoahoc}</td>
                        <td>{DETAIL.title}</td>
                    </tr>
                    <tr>
                        <td>{LANG.price_lession}</td>
                        <td>{CONTENT_BAIHOC.price_show}</td>
                    </tr>
                    <tr>
                        <td>{LANG.timephathanh}</td>
                        <td>{CONTENT_BAIHOC.timephathanh_text}</td>
                    </tr>
                    <tr>
                        <td>{LANG.timeend}</td>
                        <td>{DETAIL.timeend}</td>
                    </tr>
                </table>
                <!-- BEGIN: socialbutton -->
                        <div class="socialicon text-center margin-bottom-lg">
                            <!-- BEGIN: money_facebook -->
                            <a class="btn btn-facebook" href="#" title="{LANG.share_to_gift}" onclick="shareOnFacebook();">{LANG.share}</a><br />
                            <div class="fb-like" data-href="{SELFURL}" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false">&nbsp;</div>
                            <script type="text/javascript">
                                function shareOnFacebook() {
                                    FB.ui(
                                        {
                                            method        : 'feed',
                                            display       : 'iframe',
                                            name          : 'name',
                                            picture       : '{CONFIG_TAIKHOAN.image}',
                                            link          : '{SELFURL}',
                                            caption       : '{DETAIL.title}',
                                            description   : '{DETAIL.description}',
                                            access_token  : ''
                                        },
                                        function(response) {
                                            if ( response == undefined) {
                                                return false;
                                            } else {
                                                //send request
                                                send_taikhoan_data( nv_module_name, '{DETAIL.id}', '{CONFIG_TAIKHOAN.content_transaction}', '{CONFIG_TAIKHOAN.share_facebook}', '', '{CONFIG_TAIKHOAN.tokenkey}', 1 )
                                            }
                                        }
                                    );
                                }
                            </script>
                            <!-- END: money_facebook -->
                            <!-- BEGIN: no_money_facebook -->
                            <div class="fb-like" data-href="{SELFURL}" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true">&nbsp;</div>
                            <!-- END: no_money_facebook -->
                            <div class="g-plusone" data-size="medium"></div>
                            <a href="http://twitter.com/share" class="twitter-share-button">Tweet</a>
                        </div>
                <!-- END: socialbutton -->
            </div>
        </div>
		<div id="studyonline-bodyhtml" class="bodytext margin-bottom-lg">
			{CONTENT_BAIHOC.description}
		</div>
        <div class="row text-center">
            <!-- BEGIN: strong_note --><p class="well strong_note">{strong_note}</p><!-- END: strong_note -->
            <div class="col-xs-24 col-sm-24 col-md-12">
                <p><strong>{LANG.price_buy_lession}</strong></p>
                <p class="price">
                    {CONTENT_BAIHOC.price_show}
                    <!-- BEGIN: money_icon -->
                    <img alt="{CONTENT_BAIHOC.price_show} {MSYSTEM.symbol}" title="{CONTENT_BAIHOC.price_show} {MSYSTEM.symbol}" style="height: 20px;" src="{MSYSTEM.icon}">
                    <!-- END: money_icon -->
                    <!-- BEGIN: money_text -->
                    {MSYSTEM.symbol}
                    <!-- END: money_text -->
                </p>
                <p>
                    <!-- BEGIN: muabaihoc -->
                    <a class="btn btn-success buy_khoahoc" data_khoahoc_id="{DETAIL.id}" data_khoahoc_title="{DETAIL.title}" href="javascript:void(0)"> 
                        <strong>{LANG.buy_khoahoc}</strong> 
                    </a>
                    <a class="btn btn-success buy_baihoc" data_baihoc_id="{CONTENT_BAIHOC.id}" data_khoahoc_id="{DETAIL.id}" data_baihoc_title="{CONTENT_BAIHOC.title}" href="javascript:void(0)"> 
                        <strong>{LANG.buy_baihoc}</strong> 
                    </a>
                    <!-- END: muabaihoc -->
                    <!-- BEGIN: vaohoc -->
                    <a class="btn btn-success" href="{CONTENT_BAIHOC.linkbaigiang}"> 
                        <strong>{LANG.vaohoc}</strong> 
                    </a>
                    <!-- END: vaohoc -->
                    <!-- BEGIN: chuaphathanh -->
                    <div id="countdown_baigiang">
                        <a class="btn btn-info" href="javascript:void(0);"> 
                            <strong>{LANG.chovaohoc}</strong> 
                        </a><br />
                        <span id="clock"></span>
                    </div>
                    <script src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/jquery.countdown.min.js"></script>
                    <script type="text/javascript">
                        $('#clock').countdown('{CONTENT_BAIHOC.timephathanh_countdown}', {elapse: true})
                        .on('update.countdown', function(event) {
                          var $this = $(this);
                          if (event.elapsed) {
                            $('#countdown_baigiang').html('<a class="btn btn-success" href="{CONTENT_BAIHOC.linkbaigiang}"> <strong>{LANG.vaohoc}</strong> </a>');
                          } else {
                            $this.html(event.strftime('{LANG.conlai} %D {LANG.day} %H:%M:%S'));
                          }
                        });
                    </script>
                    <!-- END: chuaphathanh -->
                </p>
            </div>
            <div class="col-xs-24 col-sm-24 col-md-12">
                <!-- BEGIN: muakhoahoc -->
                <p><strong>{LANG.price_buy_khoahoc}</strong></p>
                <p class="price">
                    {DETAIL.price_show}
                    <!-- BEGIN: money_icon -->
                    <img alt="{DETAIL.price_show} {MSYSTEM.symbol}" title="{DETAIL.price_show} {MSYSTEM.symbol}" style="height: 20px;" src="{MSYSTEM.icon}">
                    <!-- END: money_icon -->
                    <!-- BEGIN: money_text -->
                    {MSYSTEM.symbol}
                    <!-- END: money_text -->
                </p>
                <p>
                    <a class="btn btn-success buy_khoahoc" data_khoahoc_id="{DETAIL.id}" data_khoahoc_title="{DETAIL.title}" href="javascript:void(0)"> 
                        <strong>{LANG.buy_khoahoc}</strong> 
                    </a>
                </p>
                <!-- END: muakhoahoc -->
                <!-- BEGIN: damuakhoahoc -->
                <p class="well">{LANG.damuacakhoahoc}</p>
                <!-- END: damuakhoahoc -->
            </div>
            <div class="clearfix">&nbsp;</div>
        </div>
       <div class="row">
           <!-- BEGIN: baihoc_mienphi -->
            <div class="section">
               <h3 class="section-right-name clearfix">
                  <span class="name">{LANG.baigiangmienphi}</span>
               </h3>
               <ul class="learn-outline-list">
                  <!-- BEGIN: loop -->
                  <li class="learn-outline-item learn-lesson-wr">
                     <a class="scorm-right-link-wr" href="{BAIHOC.link}" title="{BAIHOC.title}">
                        <div class="lesson-process-wr"><span class="lesson-process-percent{BAIHOC.classcss_status_phathanh}"><i class="fa "></i></span></div>
                        <!-- BEGIN: image --><img src="{BAIHOC.image}" class="fl img-thumbnail" /><!-- END: image -->
                        <h4 class="scorm-right-name visible">
                           <span class="scorm-right-link">{BAIHOC.title}</span>
                        </h4>
                      </a>  
                        <ul class="scorm-right-action clearfix">
                           <li>
                              <span title="{LANG.timeamount}" class="scorm-learn-times"><i class="fa fa-play-circle"></i> {BAIHOC.timeamount} {LANG.minute}</span>
                           </li>
                           <li><span title="{LANG.price}"><i class="fa fa-usd"></i> {BAIHOC.price_format}</span></li>
                           <li><span title="{LANG.timephathanh}" class="scorm-learn-times"><i class="fa fa-calendar"></i> {BAIHOC.timephathanh_text}</span></li>
                           <li>
                            {BAIHOC.text_status_baigiang}
                            <!-- BEGIN: loading --><img style="height:20px" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/{module_theme}/loading.gif" /><!-- END: loading -->
                           </li>
                           <!-- BEGIN: vaohoc -->
                           <li>
                            <a class="btn btn-success" href="{BAIHOC.linkbaigiang}"> 
                                <strong>{LANG.vaohoc}</strong> 
                            </a>
                           </li>
                           <!-- END: vaohoc -->
                        </ul>
                  </li>
                  <!-- END: loop -->
               </ul>
            </div>
           <!-- END: baihoc_mienphi -->
           <!-- BEGIN: baihoc -->
            <div class="section">
               <h3 class="section-right-name clearfix">
                  <span class="name">{LANG.danhsachbaigiang}</span>
               </h3>
               <ul class="learn-outline-list">
                  <!-- BEGIN: loop -->
                  <li class="learn-outline-item learn-lesson-wr">
                     <a class="scorm-right-link-wr" href="{BAIHOC.link}" title="{BAIHOC.title}">
                        <div class="lesson-process-wr"><span class="lesson-process-percent{BAIHOC.classcss_status_phathanh}"><i class="fa "></i></span></div>
                        <!-- BEGIN: image --><img src="{BAIHOC.image}" class="fl img-thumbnail" /><!-- END: image -->
                        <h4 class="scorm-right-name visible">
                           <span class="scorm-right-link">{BAIHOC.title}</span>
                        </h4>
                      </a>  
                      <ul class="scorm-right-action clearfix">
                       <li>
                          <span title="{LANG.timeamount}" class="scorm-learn-times"><i class="fa fa-play-circle"></i> {BAIHOC.timeamount} {LANG.minute}</span>
                       </li>
                       <li>
                           <span title="{LANG.price}"><i class="fa fa-usd"></i> {BAIHOC.price_format}</span>
                           <!-- BEGIN: money_icon -->
                           <img alt="{BAIHOC.price_format} {MSYSTEM.symbol}" title="{BAIHOC.price_format} {MSYSTEM.symbol}" style="height: 20px;" src="{MSYSTEM.icon}">
                           <!-- END: money_icon -->
                           <!-- BEGIN: money_text -->
                           {MSYSTEM.symbol}
                           <!-- END: money_text -->
                       </li>
                       <li><span title="{LANG.timephathanh}" class="scorm-learn-times"><i class="fa fa-calendar"></i> {BAIHOC.timephathanh_text}</span></li>
                       <li>
                        {BAIHOC.text_status_baigiang}
                        <!-- BEGIN: loading --><img style="height:20px" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/{module_theme}/loading.gif" /><!-- END: loading -->
                       </li>
                       <!-- BEGIN: buybaihoc -->
                       <li>
                        <button class="btn btn-success buy_baihoc" data_baihoc_id="{BAIHOC.id}" data_khoahoc_id="{DETAIL.id}" data_baihoc_title="{BAIHOC.title}">{LANG.buy_baihoc}</button>
                       </li>
                       <!-- END: buybaihoc -->
                       <!-- BEGIN: damuabaihoc -->
                       <li>
                        <button onclick="javascript:alert('{LANG.baigiang_chuaphathanh}');" class="btn btn-info">{LANG.damua_baihoc}</button>
                       </li>
                       <!-- END: damuabaihoc -->
                       <!-- BEGIN: vaohoc -->
                       <li>
                        <a class="btn btn-success" href="{BAIHOC.linkbaigiang}"> 
                            <strong>{LANG.vaohoc}</strong> 
                        </a>
                       </li>
                       <!-- END: vaohoc -->
                    </ul>
                  </li>
                  <!-- END: loop -->
               </ul>
            </div>
           <!-- END: baihoc -->
       </div>
    </div>
</div>

<!-- BEGIN: keywords -->
<div class="news_column panel panel-default">
	<div class="panel-body">
        <div class="h5">
            <em class="fa fa-tags">&nbsp;</em><strong>{LANG.keywords}: </strong><!-- BEGIN: loop --><a title="{KEYWORD}" href="{LINK_KEYWORDS}"><em>{KEYWORD}</em></a>{SLASH}<!-- END: loop -->
        </div>
    </div>
</div>
<!-- END: keywords -->
<!-- BEGIN: allowed_rating -->
<div class="news_column panel panel-default">
	<div class="panel-body">
        <form id="form3B" action="">
            <div class="h5 clearfix">
                <p id="stringrating">{STRINGRATING}</p>
                <!-- BEGIN: data_rating -->
                <span itemscope itemtype="http://data-vocabulary.org/Review-aggregate">{LANG.rating_average}:
                    <span itemprop="rating" id="numberrating">{DETAIL.numberrating}</span> -
                    <span itemprop="votes" id="click_rating">{DETAIL.click_rating}</span> {LANG.rating_count}
                </span>
                <!-- END: data_rating -->
                <div style="padding: 5px;">
                    <input class="hover-star" type="radio" value="1" title="{LANGSTAR.verypoor}" /><input class="hover-star" type="radio" value="2" title="{LANGSTAR.poor}" /><input class="hover-star" type="radio" value="3" title="{LANGSTAR.ok}" /><input class="hover-star" type="radio" value="4" title="{LANGSTAR.good}" /><input class="hover-star" type="radio" value="5" title="{LANGSTAR.verygood}" /><span id="hover-test" style="margin: 0 0 0 20px;">{LANGSTAR.note}</span>
                </div>
            </div>
        </form>
<script>
var buy_baihoc_confrim = '{LANG.buy_baihoc_confrim}';
var buy_khoahoc_confrim = '{LANG.buy_khoahoc_confrim}';

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
    		1 == sr && (sr = 2, $(".hover-star").rating("disable"), sendrating("{NEWSID}", b, "{NEWSCHECKSS}"))
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
<!-- END: allowed_rating -->
<!-- BEGIN: comment -->
<div class="news_column panel panel-default">
	<div class="panel-body">
	{CONTENT_COMMENT}
    </div>
</div>
<!-- END: comment -->

<!-- BEGIN: others -->
<div class="news_column panel panel-default">
	<div class="panel-body other-news">
    	<!-- BEGIN: topic -->
        <div class="clearfix">
        	<p class="h3"><strong>{LANG.topic}</strong></p>
            <div class="clearfix">
            	<ul class="related">
            		<!-- BEGIN: loop -->
            		<li>
            			<em class="fa fa-angle-right">&nbsp;</em>
            			<a href="{TOPIC.link}" {TOPIC.target_blank} <!-- BEGIN: tooltip -->data-placement="{TOOLTIP_POSITION}" data-content="{TOPIC.hometext_clean}" data-img="{TOPIC.imghome}" data-rel="tooltip"<!-- END: tooltip --> title="{TOPIC.title}"><h4>{TOPIC.title}</h4></a>
            			<em>({TOPIC.time})</em>
            			<!-- BEGIN: newday -->
            			<span class="icon_new">&nbsp;</span>
            			<!-- END: newday -->
            		</li>
            		<!-- END: loop -->
            	</ul>
            </div>
        	<p class="text-right">
        		<a title="{TOPIC.topictitle}" href="{TOPIC.topiclink}">{LANG.more}</a>
        	</p>
        </div>
    	<!-- END: topic -->

    	<!-- BEGIN: related_new -->
    	<p class="h3"><strong>{LANG.related_new}</strong></p>
    	<div class="clearfix">
            <ul class="related list-inline">
        		<!-- BEGIN: loop -->
        		<li>
        			<em class="fa fa-angle-right">&nbsp;</em>
        			<a href="{RELATED_NEW.link}" {RELATED_NEW.target_blank} <!-- BEGIN: tooltip -->data-placement="{TOOLTIP_POSITION}" data-content="{RELATED_NEW.hometext_clean}" data-img="{RELATED_NEW.imghome}" data-rel="tooltip"<!-- END: tooltip --> title="{RELATED_NEW.title}"><h4>{RELATED_NEW.title}</h4></a>
        			<em>({RELATED_NEW.time})</em>
        			<!-- BEGIN: newday -->
        			<span class="icon_new">&nbsp;</span>
        			<!-- END: newday -->
        		</li>
        		<!-- END: loop -->
        	</ul>
        </div>
    	<!-- END: related_new -->

    	<!-- BEGIN: related -->
    	<p class="h3"><strong>{LANG.related}</strong></p>
    	<div class="clearfix">
            <ul class="related list-inline">
        		<!-- BEGIN: loop -->
        		<li>
        			<em class="fa fa-angle-right">&nbsp;</em>
        			<a href="{RELATED.link}" {RELATED.target_blank} <!-- BEGIN: tooltip --> data-placement="{TOOLTIP_POSITION}" data-content="{RELATED.hometext_clean}" data-img="{RELATED.imghome}" data-rel="tooltip"<!-- END: tooltip --> title="{RELATED.title}"><h4>{RELATED.title}</h4></a>
        			<em>({RELATED.time})</em>
        			<!-- BEGIN: newday -->
        			<span class="icon_new">&nbsp;</span>
        			<!-- END: newday -->
        		</li>
        		<!-- END: loop -->
        	</ul>
        </div>
    	<!-- END: related -->
    </div>
</div>
<!-- END: others -->
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_EDITORSDIR}/ckeditor/plugins/codesnippet/lib/highlight/highlight.pack.js"></script>
<script type="text/javascript">hljs.initHighlightingOnLoad();</script>
<!-- END: main -->
<!-- BEGIN: no_permission -->
<div class="alert alert-info">
	{NO_PERMISSION}
</div>
<!-- END: no_permission -->