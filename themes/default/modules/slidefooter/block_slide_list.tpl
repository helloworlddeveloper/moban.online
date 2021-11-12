<!-- BEGIN: main -->
<link href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/jquery.bxslider.min.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/jquery.bxslider.min.js"></script>
<ul class="bxslider" id="flexisel{bid}">
	<!-- BEGIN: slide -->
	 <li>
        <a title="{ROW.title}" href="{ROW.link}" ><img title="{ROW.title}" src="{ROW.image}" alt="{ROW.title}" /></a>
     </li>
	<!-- END: slide -->    
</ul>    
<div class="clearout"></div>
<script type="text/javascript">
$('#flexisel{bid}').bxSlider({
  minSlides: 5,
  auto: true,
  pager: false,
  maxSlides: 6,
  slideWidth: 200,
  slideMargin: 10
});
$('.bx-wrapper').css('margin', '10px auto')
</script>
<!-- END: main -->