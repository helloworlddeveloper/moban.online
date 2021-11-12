<!-- BEGIN: main -->
<div class="clear">&nbsp;</div>
<div class="alert alert-info">
	<h1>{page_title}</h1>
	<p class="text-justify"></p><h2>{description}</h2><p></p>
</div>
<div class="row" id="items">
    <!-- BEGIN: loop -->
    <div class="col-md-4 col-xs-12">
        <div class="span3 img">
            <a title="{DATA.title}" href="{DATA.link}"> <img title="{DATA.title}" alt="{DATA.title}" src="{DATA.image}" /></a>
        </div>
        <div class="span3 rmitem">
            <div class="rmtitle">
                <h3><a href="{DATA.link}" title="{DATA.title}">{DATA.title}</a></h3>
            </div>
            <div class="content">
                {DATA.hometext}
            </div>
            <div class="rmorder">
                <a title="{DATA.title}" href="{DATA.link}" class="btn btn-rm">{LANG.detail}</a>
                <a href="{DATA.link_order}" class="btn btn-rm">{LANG.order}</a>
            </div>
        </div>
    </div>
    <!-- END: loop -->
    <!-- BEGIN: generate_page -->
    <div class="clearfix">&nbsp;</div>
    <div class="text-center">{generate_page}</div>
    <!-- END: generate_page -->
</div>
<!-- END: main -->