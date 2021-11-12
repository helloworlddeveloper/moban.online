<!-- BEGIN: main -->
<!-- BEGIN: data -->
<div class="reviews row">
	<!-- BEGIN: loop -->
	<div class="review-item">
		<div class="col-md-3"><img class="img-thumbnail" src="https://public-assets.envato-static.com/assets/common/icons-buttons/default-user-b8d0c2225ee9d9e68b5d80cd0ed6b7800c0135da303b735b777ac5a39f778a69.jpg" itemprop="image" alt="Wix"></div>
		<div class="col-md-6">
			<p class="fullname">{DATA.fullname}</p>
			<p><i class="fas fa-calendar-alt"></i>
				<i class="addtime">{DATA.edit_time}</i></p>
		</div>
		<div class="col-md-6">
            {DATA.phone}<br>
            {DATA.address}
		</div>
		<div class="col-md-9">
			<!-- BEGIN: product --><a href="{PRODUCT.link}">{PRODUCT.title}</a><br><!-- END: product -->
		</div>
		<div class="col-md-24">{DATA.description}</div>
	</div>
	<!-- END: loop -->
</div>
<!-- END: data -->
<!-- END: main -->