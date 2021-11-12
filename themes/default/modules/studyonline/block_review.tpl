<!-- BEGIN: main -->
<div class="module-testimonials-01">
<div class="slider slider-for">
	<!-- BEGIN: contentloop -->
    <div class="text-testi slick-slide slick-current slick-active">
		<p>
        <i class="fa fa-quote-left" aria-hidden="true"></i>&nbsp;{ROW.content}&nbsp;<i class="fa fa-quote-right" aria-hidden="true"></i></p>
		<a href="#" tabindex="0">- {ROW.first_name} {ROW.last_name} | {ROW.addtime}</a>
	</div>
	<!-- END: contentloop -->
</div>
<div class="col-md-12 col-sm-16 col-xs-24 col-md-offset-6 col-sm-offset-4">
	<div class="slider slider-nav ">
		<!-- BEGIN: infoloop -->
        <div class="entry-thumb-slick">
			<img src="{ROW.photo}" alt="{ROW.first_name} {ROW.last_name}" />
		</div>
        <!-- END: infoloop -->
	</div>
</div>
</div>
<script type="text/javascript">
$('.slider-for').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    fade: true,
    asNavFor: '.slider-nav',
    adaptiveHeight: true,
});
$('.slider-nav').slick({
    slidesToShow: 5,
    slidesToScroll: 1,
    asNavFor: '.slider-for',
    dots: false,
    centerMode: true,
    focusOnSelect: true,
    prevArrow: null,
    nextArrow: null,
    loop: true,
});
</script>
<!-- END: main -->