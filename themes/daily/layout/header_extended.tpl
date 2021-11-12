	<noscript>
		<div class="alert alert-danger">{LANG.nojs}</div>
	</noscript>
    <header>
        <div class="container">
            <div id="header" class="row">
                <div class="logo col-xs-24 col-sm-24 col-md-6">
                    <!-- BEGIN: image -->
                    <a title="{SITE_NAME}" href="{THEME_SITE_HREF}"><img src="{LOGO_SRC}" width="{LOGO_WIDTH}" height="{LOGO_HEIGHT}" alt="{SITE_NAME}" /></a>
                    <!-- END: image -->
                </div>
                <div class="col-xs-24 col-sm-24 col-md-18 service">
                    <div class="row">
                        <div class="col-xs-24 col-sm-12 col-md-6">
                            <div class="items-service freeship">
                                <div class="">FREE SHIPPING</div>
                                <div class="d-block">ĐƠN HÀNG &gt;= 500.000đ</div>
                            </div>
                        </div>
                        <div class="col-xs-24 col-sm-12 col-md-6">
                            <div class="items-service giao-hang">
                                <div class="">ĐỔI TRÀ HÀNG</div>
                                <div class="d-block">TRONG VÒNG 7 NGÀY</div>
                            </div>
                        </div>
                        <div class="col-xs-24 col-sm-12 col-md-5">
                            <div class="items-service thanhtoan">
                                <div class="">THANH TOÁN</div>
                                <div class="d-block">KHI NHẬN HÀNG</div>
                            </div>
                        </div>
                        <div class="col-xs-24 col-sm-12 col-md-7">
                            <div class="items-service hotline">
                                <div class=""><a href="tel:0938555282" title="hotline lily's white"><span class="hot">(+84) 919 660 270 </span></a></div>
                                <div class="d-block">GỌI NGAY ĐỂ ĐƯỢC TƯ VẤN</div>
                            </div>
                        </div>
                    </div>
                    <div class="header-2">
                        <div class="col-xs-24 col-sm-12 col-md-16 form-inline headerSearch">
                            <div class="input-group">
                                <div class="input-group">
                                    <input type="text" style="width: 500px" class="form-control" maxlength="{NV_MAX_SEARCH_LENGTH}" placeholder="{LANG.search}..."><span class="input-group-btn"><button type="button" class="btn btn-info" data-url="{THEME_SEARCH_URL}" data-minlength="{NV_MIN_SEARCH_LENGTH}" data-click="y"><em class="fa fa-search fa-lg"></em></button></span>
                                </div>
                            </span>
                            </div>
                        </div>
                        <div class="col-xs-24 col-sm-12 col-md-8 widget-cat">
                            [CAT_TOP_BLOCK]
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="header" id="fixmenu">
        <div class="container">[MENU_SITE]</div>
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
