<!-- BEGIN: main -->
<!-- BEGIN: loopimg -->
<div class="imgcontent{IMG.sel}" title="{IMG.title}" did="{IMG.did}" data-alt="{IMG.alt}" data-src="{IMG.src}" >
	<div style="width:100px;height:86px;display:table-cell; vertical-align:middle;">
		<img class="previewimg" alt="{IMG.alt}" title="{IMG.title}" name="{IMG.data}" src="{IMG.src}" width="{IMG.srcwidth}" height="{IMG.srcheight}" />
	</div>
	<div class="imgInfo">
		{IMG.name}
		<br />
		{IMG.size}
	</div>
</div>
<!-- END: loopimg -->
<!-- BEGIN: generate_page -->
<div class="text-center">
	{GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<script type="text/javascript">
//<![CDATA[
$('.imgcontent').bind("mouseup contextmenu", function(e) {
	e.preventDefault();
	fileMouseup(this, e);
});
</script>

<!-- BEGIN: imgsel -->
<script type="text/javascript">
$(".imgcontent.imgsel:first").attr('id', 'nv_imgsel_{NV_CURRENTTIME}');
window.location.href = "#nv_imgsel_{NV_CURRENTTIME}";
</script>
<!-- END: imgsel -->
<!-- END: main -->