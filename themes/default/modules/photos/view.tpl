<!-- BEGIN: main -->
<div id="photo-{OP}">
    <div class="page-header pd10_0 mg0_10_10">
        <h1 class="txt20 txt_bold">{LANG.list_upload_by_me}: {DATA.name}</h1>
    </div>
    <div class="panel-body">
        <div class="row">
            <!-- BEGIN: photo -->
            <div class="col-xs-24 col-sm-12 col-md-8 album-album">
                <div class="panel panel-default">
                    <div class="album-image panel-body pd5">
                        <a title="{PHOTO.name}" href="{PHOTO.image_url}" data-gallery="gallery"> <img class="lazy img-responsive" data-original="{PHOTO.thumb}" data-src="{PHOTO.thumb}" src="{PHOTO.thumb}" alt="{PHOTO.name}" /> </a>
                    </div>
                    <div class="catalog_content panel-footer view_detail status_{PHOTO.status}">
                        <div class="pic-name fl">
                            <i class="fa fa-picture-o">&nbsp;{PHOTO.status_title}</i>&nbsp;
                            <i class="fa fa-eye">&nbsp;{PHOTO.viewed}</i>&nbsp;
                            <i class="fa fa-download">&nbsp;{PHOTO.download}</i>&nbsp;
                        </div>
                        <div class="fr"><i class="fa fa-clock-o">&nbsp;</i> {PHOTO.date_modified}</div>
                    </div>
                </div>
            </div>
            <!-- END: photo -->
        </div>
    </div>
    <hr />
</div>
<script src="{NV_BASE_SITEURL}themes/default/modules/{MODULE_FILE}/plugins/lazy/jquery.lazyload.min.js" type="text/javascript" ></script>
<script type="text/javascript">
    $(function() {
        $(".album-image.lazy").lazyload({
            effect : "fadeIn"
        });
    });
</script>
<!-- END: main -->