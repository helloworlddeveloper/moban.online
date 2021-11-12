<!-- BEGIN: main -->
<style type="text/css">
    .marquee_options{
        width: 90%;
        overflow: hidden;
        height:36px;
    }
</style>
<script src="{NV_BASE_SITEURL}modules/{MODULE_FILE}/js/jquery.marquee.min.js" type="text/javascript"></script>
<script src="{NV_BASE_SITEURL}modules/{MODULE_FILE}/js/jquery.marquee.min.js" type="text/javascript"></script>
<div class="b_catno">
    <span><img style="padding-right:10px;float:left" src="{NV_BASE_SITEURL}modules/{MODULE_FILE}/template/notice.png" /></span>
	<div class="marquee_options">
        <!-- BEGIN: row -->
    		<!-- BEGIN: link -->
    		<a href="{ROW.link}">{ROW.title}</a>&nbsp;&nbsp;&nbsp;&nbsp;
    		<!-- END: link -->
    		<!-- BEGIN: nolink -->
    		{ROW.title}&nbsp;&nbsp;&nbsp;&nbsp;
    		<!-- END: nolink -->
    	<!-- END: row -->
    </div>            
</div>       
<script type="text/javascript">
    $('.marquee_options').marquee({
    //speed in milliseconds of the marquee
    speed: 10000,
    //gap in pixels between the tickers
    gap: 50,
    //gap in pixels between the tickers
    delayBeforeStart: 0,
    //'left' or 'right'
    direction: 'up',
    //true or false - should the marquee be duplicated to show an effect of continues flow
    duplicated: true,
    //on hover pause the marquee - using jQuery plugin https://github.com/tobia/Pause
    pauseOnHover: true
  });
</script>
<!-- END: main -->