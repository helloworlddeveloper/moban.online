<!-- BEGIN: main -->
<div class="news_column panel-default">
	<div class="panel-body">
        <div class="row" id="detail">
            <table class="table table-striped table-bordered table-hover">
                <tr>
                    <td colspan="2"><h1 class="title">{DETAIL.title}</h1></td>
                    <td><a rel="nofollow" href="{DETAIL.link_order}" class="btn btn-order">{LANG.order}</a></td>
                </tr>
                <tr>
                    <td>{LANG.code}: {DETAIL.code}</td>
                    <td>{LANG.room_size}: {DETAIL.room_size}</td>
                    <td>{LANG.max_person}: {DETAIL.max_person}</td>
                </tr>
            </table>
            <!-- BEGIN: slider -->
            <div class="slider-image">
            </div>
            <!-- END: slider -->
            <div class="content">{DETAIL.description}</div>
            <!-- BEGIN: view360 -->
            <div class="view360">
                <h3><strong>{LANG.view360}</strong></h3>
                <iframe style="width:100%;height:500px;border:none;" src="{VIEW360}"></iframe>
            </div>
            <!-- END: view360 -->
            <!-- BEGIN: maps -->
            <div class="maps">
            <h3><strong>{LANG.viewmaps}</strong></h3>
            <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key={CONFIG.Google_Maps_API_Key}" type="text/javascript"></script>
            <script type="text/javascript" src="{NV_BASE_SITEURL}modules/{MODULE_FILE}/js/googlemap.js"></script>
            <div id="url_google_map"></div>
            <div id="googlemap" style="width: 100%; height: 300px"></div>
            <script type="text/javascript">
                googlemapshow({CONFIG.gmap_lat}, {CONFIG.gmap_lng}, {CONFIG.gmap_z});
            </script>
            </div>
            <!-- END: maps -->
            <!-- BEGIN: comment -->
            <div id="fb-root"></div>
            <script>(function(d, s, id) {
              var js, fjs = d.getElementsByTagName(s)[0];
              if (d.getElementById(id)) return;
              js = d.createElement(s); js.id = id;
              js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&appId=744151792327816&version=v2.0";
              fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>
            <div class="fb-comments" data-href="{CLIENT_INFO.selfurl}" data-width="100%" data-numposts="5" data-colorscheme="light"></div>
            <!-- END: comment -->
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$("#detail img").toggleClass('img-thumbnail');
});
</script>
<!-- END: main -->