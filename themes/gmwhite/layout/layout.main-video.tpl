<!-- BEGIN: main -->
<!DOCTYPE html>
	<html lang="{LANG.Content_Language}" xmlns="http://www.w3.org/1999/xhtml" prefix="og: http://ogp.me/ns#">
	<head>
		<title>{THEME_PAGE_TITLE}</title>
		<!-- BEGIN: metatags --><meta {THEME_META_TAGS.name}="{THEME_META_TAGS.value}" content="{THEME_META_TAGS.content}">
		<!-- END: metatags -->
		<link rel="shortcut icon" href="{SITE_FAVICON}">
        <link rel="StyleSheet" href="/themes/default/css/bootstrap.non-responsive.css?t=25">
		<!-- BEGIN: js -->
		<script<!-- BEGIN: ext --> src="{JS_SRC}"<!-- END: ext -->><!-- BEGIN: int -->{JS_CONTENT}<!-- END: int --></script>
		<!-- END: js -->
		<link rel="stylesheet" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/video_style.css" />
	</head>
	<body>
	<noscript>
		<div class="alert alert-danger">{LANG.nojs}</div>
	</noscript>
     <div class="wraper">   
        <div class="row">
        	<div class="col-md-24">
        		{MODULE_CONTENT}
        		[BOTTOM]
        	</div>
        </div>
     </div>
     {ADMINTOOLBAR}
    <!-- SiteModal Required!!! -->
    <div id="sitemodal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <em class="fa fa-spinner fa-spin">&nbsp;</em>
                </div>
                <button type="button" class="close" data-dismiss="modal"><span class="fa fa-times"></span></button>
            </div>
        </div>
    </div>
    	<!-- BEGIN: lt_ie9 --><p class="chromeframe">{LANG.chromeframe}</p><!-- END: lt_ie9 -->
        <div id="openidResult" class="nv-alert" style="display:none"></div>
        <div id="openidBt" data-result="" data-redirect=""></div>
        <script src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/bootstrap.min.js"></script>
	</body>
</html>
<!-- END: main -->