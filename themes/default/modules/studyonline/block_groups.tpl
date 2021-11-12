<!-- BEGIN: main -->
<div class="row">
	<!-- BEGIN: loop -->
	<div class="col-md-6 col-sm-8 col-xs-24">
		<article class="entry-item">
			<div class="entry-thumb">
				<a title="{ROW.title}" href="{ROW.link}">
					<img alt="{ROW.title}" src="{ROW.thumb}" class="img-thumbnail" />
				</a>
			</div>
			<div class="entry-content">
				<a href="{ROW.subject_link}" class="course-category">
                    <!-- BEGIN: subject_icon -->
                    <img alt="{ROW.subject_name}" src="{subject_icon}" style="width:25px" />
                    <!-- END: subject_icon -->
                    {ROW.subject_name}
                </a>
				<h4 class="entry-title">
					<a href="{ROW.link}" title="{ROW.title}">{ROW.title_clean}</a>
				</h4>
                <!-- BEGIN: teacher -->
				<a href="{TEACHER.teacher_link}" class="course-author">{TEACHER.title}</a>&nbsp;&nbsp;
                <!-- END: teacher -->
				<div class="course-price">
					<span class="price">
						{ROW.price}
						<!-- BEGIN: money_icon -->
						<img alt="{ROW.price} {MSYSTEM.symbol}" title="{ROW.price} {MSYSTEM.symbol}" style="height: 20px;" src="{MSYSTEM.icon}">
						<!-- END: money_icon -->
						<!-- BEGIN: money_text -->
                        {MSYSTEM.symbol}
						<!-- END: money_text -->
					</span>
					<div class="rating">
						<i class="fa fa-star"></i>
						<i class="fa fa-star"></i>
						<i class="fa fa-star"></i>
						<i class="fa fa-star"></i>
						<i class="fa fa-star"></i>
					</div>
				</div>
				<ul class="course-detail">
					<li>
						<i class="fa fa-clock-o"></i>
						{ROW.addtime}
					</li>
                    <li>
						<i class="fa fa-eye"></i>
						{ROW.numview}
					</li>
                    <!--
					<li>
						<i class="fa fa-user"></i>
						{ROW.numbuy}
					</li>
                    -->
					<li>
						<i class="fa fa-hand-peace-o "></i>
						{ROW.numlike}
					</li>
				</ul>
			</div>
		</article>
	</div>
	<!-- END: loop -->
</div>
<!-- END: main -->