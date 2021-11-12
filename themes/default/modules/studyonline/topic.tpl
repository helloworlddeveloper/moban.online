<!-- BEGIN: main -->
<!-- BEGIN: topicdescription -->
<div class="news_column">
	<div class="alert alert-info clearfix">
		<h1>{TOPPIC_TITLE}</h1>
		<!-- BEGIN: image -->
		<img alt="{TOPPIC_TITLE}" src="{HOMEIMG1}" width="{IMGWIDTH1}" class="img-thumbnail pull-left imghome" />
		<!-- END: image -->
		<p class="text-justify">{TOPPIC_DESCRIPTION}</p>
	</div>
</div>
<!-- END: topicdescription -->

<!-- BEGIN: topic -->
<div class="col-md-8 col-sm-8 col-xs-24">
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
            <!-- BEGIN: teacher_info -->
			<a href="{TEACHER_INFO.teacher_link}" class="course-author">{TEACHER_INFO.title}</a>&nbsp;&nbsp;
            <!-- END: teacher_info -->
			<div class="course-price">
				<span class="price">{ROW.price}</span>
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
<!-- END: topic -->
<!-- BEGIN: generate_page -->
<div class="text-center">
	{GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<!-- END: main -->