        <!-- BEGIN: lt_ie9 --><p class="chromeframe">{LANG.chromeframe}</p><!-- END: lt_ie9 -->
        <!-- BEGIN: cookie_notice --><div class="cookie-notice"><div><button onclick="cookie_notice_hide();">&times;</button>{COOKIE_NOTICE}</div></div><!-- END: cookie_notice -->
        <div id="timeoutsess" class="chromeframe">
            {LANG.timeoutsess_nouser}, <a onclick="timeoutsesscancel();" href="#">{LANG.timeoutsess_click}</a>. {LANG.timeoutsess_timeout}: <span id="secField"> 60 </span> {LANG.sec}
        </div>
        <div id="openidResult" class="nv-alert" style="display:none"></div>
        <div id="openidBt" data-result="" data-redirect=""></div>
        <!-- BEGIN: crossdomain_listener -->
        <script type="text/javascript">
        function nvgSSOReciver(event) {
            if (event.origin !== '{SSO_REGISTER_ORIGIN}') {
                return false;
            }
            if (
                event.data !== null && typeof event.data == 'object' && event.data.code == 'oauthback' &&
                typeof event.data.redirect != 'undefined' && typeof event.data.status != 'undefined' && typeof event.data.mess != 'undefined'
            ) {
                $('#openidResult').data('redirect', event.data.redirect);
                $('#openidResult').data('result', event.data.status);
                $('#openidResult').html(event.data.mess + (event.data.status == 'success' ? ' <span class="load-bar"></span>' : ''));
                $('#openidResult').addClass('nv-info ' + event.data.status);
                $('#openidBt').trigger('click');
            }
        }
        window.addEventListener('message', nvgSSOReciver, false);
        </script>
        <!-- END: crossdomain_listener -->
        <script src="{NV_STATIC_URL}themes/{TEMPLATE}/js/bootstrap.min.js"></script>
		
		<link rel="stylesheet" id="flatsome-effects-css" href="{NV_STATIC_URL}themes/{TEMPLATE}/assets1/img/Effects.css" type="text/css" media="all">
		<link rel="stylesheet" id="flatsome-countdown-style-css" href="{NV_STATIC_URL}themes/{TEMPLATE}/assets1/ux-countdown.css" type="text/css" media="all">
		<script type="text/javascript" src="{NV_STATIC_URL}themes/{TEMPLATE}/assets1/wp-polyfill.js" id="wp-polyfill-js"></script>

		<script type="text/javascript" src="{NV_STATIC_URL}themes/{TEMPLATE}/assets1/index.js" id="contact-form-7-js"></script>
		<script type="text/javascript" src="{NV_STATIC_URL}themes/{TEMPLATE}/assets1/jquery.js" id="jquery-blockui-js"></script>

		<script type="text/javascript" src="{NV_STATIC_URL}themes/{TEMPLATE}/assets1/add-to-cart.js" id="wc-add-to-cart-js"></script>
		<script type="text/javascript" src="{NV_STATIC_URL}themes/{TEMPLATE}/assets1/js.js" id="js-cookie-js"></script>

		<script type="text/javascript" src="{NV_STATIC_URL}themes/{TEMPLATE}/assets1/woocommerce.js" id="woocommerce-js"></script>

		<script type="text/javascript" src="{NV_STATIC_URL}themes/{TEMPLATE}/assets1/cart-fragments.js" id="wc-cart-fragments-js"></script>
		<script type="text/javascript" src="{NV_STATIC_URL}themes/{TEMPLATE}/assets1/flatsome-live-search.js" id="flatsome-live-search-js"></script>
		<script type="text/javascript" src="{NV_STATIC_URL}themes/{TEMPLATE}/assets1/hoverIntent.js" id="hoverIntent-js"></script>

		<script type="text/javascript" src="{NV_STATIC_URL}themes/{TEMPLATE}/assets1/flatsome.js" id="flatsome-js-js"></script>
		<script type="text/javascript" src="{NV_STATIC_URL}themes/{TEMPLATE}/assets1/woocommerce_002.js" id="flatsome-theme-woocommerce-js-js"></script>
		<script type="text/javascript" src="{NV_STATIC_URL}themes/{TEMPLATE}/assets1/wp-embed.js" id="wp-embed-js"></script>

		<script type="text/javascript" src="{NV_STATIC_URL}themes/{TEMPLATE}/assets1/countdown-script-min.js" id="flatsome-countdown-script-js"></script>
		<script type="text/javascript" src="{NV_STATIC_URL}themes/{TEMPLATE}/assets1/ux-countdown.js" id="flatsome-countdown-theme-js-js"></script>


    </body>
</html>
