<!-- BEGIN: main -->
<div class="row">
    <!-- BEGIN: data -->
        <div class="news_column">
        	<div class="alert alert-info clearfix">
                <h1>{LANG.teacher_info} {TEACHER_INFO.title}</h1>
        		<div class="col-md-8 col-sm-8 col-xs-24">
                    <img class="img-thumbnail" src="{TEACHER_INFO.avatar}" />
                </div>
                <div class="col-md-16 col-sm-16 col-xs-24">
                    <ul class="info_teacher">
                        <li class="col-md-12 col-sm-12 col-xs-24">{LANG.teacher_name}</li>
                        <li class="col-md-12 col-sm-12 col-xs-24">{TEACHER_INFO.title}</li>
                        <li class="col-md-12 col-sm-12 col-xs-24">{LANG.subjectlist}</li>
                        <li class="col-md-12 col-sm-12 col-xs-24">{TEACHER_INFO.subject}</li>
                        <li class="col-md-12 col-sm-12 col-xs-24">{LANG.facebooklink}</li>
                        <li class="col-md-12 col-sm-12 col-xs-24"><a href="{TEACHER_INFO.facebooklink}" rel="nofollow">{TEACHER_INFO.facebooklink}</a></li>
                        <li class="col-md-12 col-sm-12 col-xs-24">{LANG.numview}</li>
                        <li class="col-md-12 col-sm-12 col-xs-24">{TEACHER_INFO.numview}</li>
                        <li class="col-md-12 col-sm-12 col-xs-24">{LANG.numfollow}</li>
                        <li class="col-md-12 col-sm-12 col-xs-24">{TEACHER_INFO.numfollow}</li>
                    </ul>
                </div>
        	</div>
            <!-- BEGIN: khoahoc -->
            <div class="items clearfix">
            <h2>{LANG.teacher_title_info} {TEACHER_INFO.title}</h2>
            <!-- BEGIN: loop -->
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
        	<!-- END: khoahoc -->
            <p>{TEACHER_INFO.infotext}</p>
        </div>
    <!-- END: data -->
    <!-- BEGIN: nodata -->
    <div class="alert alert-danger">
    	{LANG.no_data}
    </div>
    <!-- END: nodata -->
    <!-- BEGIN: comment -->
    <div class="news_column panel panel-default">
    	<div class="panel-body">
    	{CONTENT_COMMENT}
        </div>
    </div>
    <!-- END: comment -->
</div>
<!-- END: main -->