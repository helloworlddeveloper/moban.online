	<noscript>
		<div class="alert alert-danger">{LANG.nojs}</div>
	</noscript>
    <section class="page_topline cs main_color2 table_section table_section_sm section_padding_top_5 section_padding_bottom_5">
        <div class="container">
            <div class="row">
                <div class="floatleft col-md-6">
                    <i class="fa fa-clock-o rightpadding_5" aria-hidden="true"></i><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"> Giờ mở cửa: Thứ Hai - Thứ Bảy 8,00 - 17,30 </font></font>
                </div>
                <div class="floatleft col-md-12">
                    [HEAD_RIGHT]
                </div>
                <div class="floatright col-md-6">
                    [SOCIAL_ICONS]
                </div>
            </div>
        </div>
    </section>
    <section class="page_toplogo table_section table_section_md section_padding_top_25 section_padding_bottom_25 ls">
        <div class="container">
            <div class="row">
                <!-- BEGIN: image -->
                <div class="col-md-8">
                    <a title="{SITE_NAME}" href="{THEME_SITE_HREF}"><img class="logo" src="{LOGO_SRC}" alt="{SITE_NAME}" /></a>
                </div>
                <!-- END: image -->
                <div class="col-xs-24 col-sm-12 col-md-8">
                    <div class="items-service">
                        <div class="teaser_icon size_small border_icon highlight2 rounded"> <i class="fa fa-4x fa-mobile"></i> </div>
                        <div class="media-body media-middle">
                            <h4><a href="tel:028.99957779">028.99957779</a></h4>
                            <p class="greylinks fontsize_12"> <a href="mailto:lienhe@moban.online">lienhe@moban.online</a> </p>
                        </div>
                    </div>
                </div>
                <div class="col-xs-24 col-sm-12 col-md-8">
                    <div class="items-service">
                        <div class="teaser_icon size_small border_icon highlight2 rounded"> <i class="fa fa-4x fa-map-marker"></i> </div>
                        <div class="media-body media-middle">
                            <h4>102A Huỳnh Văn Bánh</h4>
                            <p class="greylinks fontsize_12">Phường 12, Quận Phú Nhuận, HCM</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="header" id="fixmenu">
        <div class="container">
            <div class="col-md-18" id="menusite">[MENU_SITE]</div>
            <div class="col-md-4 headerSearch">
                <div class="input-group">
                    <input type="text" class="form-control" maxlength="{NV_MAX_SEARCH_LENGTH}" placeholder="{LANG.search}..."><span class="input-group-btn"><button type="button" class="btn btn-info" data-url="{THEME_SEARCH_URL}" data-minlength="{NV_MIN_SEARCH_LENGTH}" data-click="y"><em class="fa fa-search fa-lg"></em></button></span>
                </div>
            </div>
            <div class="col-md-2">[BLOCK_SEARCH]</div>
        </div>
    </div>
    [HEADER]
    <!-- BEGIN: breadcrumbs -->
    <div class="container">
        <nav class="third-nav">
            <div class="bg">
                <div class="clearfix">
                    <div class="col-xs-24 col-sm-18 col-md-18">
                        
                        <div class="breadcrumbs-wrap">
                        	<div class="display">
                        		<a class="show-subs-breadcrumbs hidden" href="#" onclick="showSubBreadcrumbs(this, event);"><em class="fa fa-lg fa-angle-right"></em></a>
                                <ul class="breadcrumbs list-none"></ul>
    						</div>
    						<ul class="subs-breadcrumbs"></ul>
                            <ul class="temp-breadcrumbs hidden">
                                <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="{THEME_SITE_HREF}" itemprop="url" title="{LANG.Home}"><span itemprop="title">{LANG.Home}</span></a></li>
                                <!-- BEGIN: loop --><li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="{BREADCRUMBS.link}" itemprop="url" title="{BREADCRUMBS.title}"><span class="txt" itemprop="title">{BREADCRUMBS.title}</span></a></li><!-- END: loop -->
                            </ul>
    					</div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
    <!-- END: breadcrumbs -->
    [THEME_ERROR_INFO]
