<!-- BEGIN: main -->
<!DOCTYPE html>
<html lang="{LANG.Content_Language}" xmlns="http://www.w3.org/1999/xhtml" prefix="og: http://ogp.me/ns#">
<head>
	<title>{THEME_PAGE_TITLE}</title>
	<!-- BEGIN: metatags --><meta {THEME_META_TAGS.name}="{THEME_META_TAGS.value}" content="{THEME_META_TAGS.content}">
	<!-- END: metatags -->
	<link rel="shortcut icon" href="{SITE_FAVICON}">
	<!-- BEGIN: links -->
	<link<!-- BEGIN: attr --> {LINKS.key}<!-- BEGIN: val -->="{LINKS.value}"<!-- END: val --><!-- END: attr -->>
	<!-- END: links -->
	<!-- BEGIN: js -->
	<script<!-- BEGIN: ext --> src="{JS_SRC}"<!-- END: ext -->><!-- BEGIN: int -->{JS_CONTENT}<!-- END: int --></script>
    <!-- END: js -->
    <!-- Facebook CHat -->
        <script type="text/javascript">
        (function(d,s,id){var z=d.createElement(s);z.type="text/javascript";z.id=id;z.async=true;z.src="//static.zotabox.com/a/3/a323dc776737ee62c7f79a212286f7be/widgets.js";var sz=d.getElementsByTagName(s)[0];sz.parentNode.insertBefore(z,sz)}(document,"script","zb-embed-code"));
        </script>
        <!-- END Facebook CHat -->
	<!-- Facebook Pixel Code -->
	<script>
        !function(f,b,e,v,n,t,s)
				{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
				n.callMethod.apply(n,arguments):n.queue.push(arguments)};
				if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
				n.queue=[];t=b.createElement(e);t.async=!0;
				t.src=v;s=b.getElementsByTagName(e)[0];
				s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '2084893395084087');
        fbq('track', 'PageView');
	</script>
	<noscript><img height="1" width="1" style="display:none"
				   src="https://www.facebook.com/tr?id=2084893395084087&ev=PageView&noscript=1"
		/></noscript>
	<!-- End Facebook Pixel Code CASH13 -->
        <!-- Google Code for cash13.vn Conversion Page -->
        <script type="text/javascript">
        /* <![CDATA[ */
        var google_conversion_id = 1006118101;
        var google_conversion_label = "idpKCIKYynoQ1cng3wM";
        var google_remarketing_only = false;
        /* ]]> */
        </script>
        <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
        </script>
        <noscript>
        <div style="display:inline;">
        <img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/1006118101/?label=idpKCIKYynoQ1cng3wM&amp;guid=ON&amp;script=0"/>
        </div>
        </noscript>
</head>

<body>
<div class="">
	<div class="fix-search header" id="fixmenu">[MENU_SITE]</div>
</div>
<div style="padding-top:20px">{MODULE_CONTENT}</div>
<script type="text/javascript">

    var owl2 = $('.logobao2');
    owl2.owlCarousel({
        loop:true,
        margin:10,
        autoplay:false,
        autoplayTimeout:1500,
        responsiveClass:true,
        responsive:{
            0:{
                items:1
            },
            321:{
                items:2
            },
            768:{
                items:4

            }
        }

    });
    var owl3 = $('.logobao3');
    owl3.owlCarousel({
        loop:true,
        margin:10,
        autoplay:true,
        autoplayTimeout:1500,
        responsiveClass:true,
        responsive:{
            0:{
                items:1
            },
            321:{
                items:2
            },
            768:{
                items:5

            }
        }

    });
</script>

<!-- Load Facebook SDK for JavaScript -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/vi_VN/sdk/xfbml.customerchat.js#xfbml=1&version=v2.12&autoLogAppEvents=1';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<!-- Your customer chat code -->
<div class="fb-customerchat"
  attribution="setup_tool"
  page_id="1058921834258529"
  theme_color="#0084ff"
  logged_in_greeting="Tập đoàn Dịch vụ CASH 13"
  logged_out_greeting="Tập đoàn Dịch vụ CASH 13">
</div>
</body>

</html>
<!-- END: main -->