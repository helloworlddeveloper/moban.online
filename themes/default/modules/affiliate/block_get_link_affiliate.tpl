<!-- BEGIN: main -->
<div class="row text-center">
    <!-- BEGIN: showqr -->
    <div class="col-md-24">
        <p><strong>Mã QR Affiliate</strong></p>
        <p><img src="{QRCODE}"></p>
    </div>
    <!-- END: showqr -->
    <!-- BEGIN: showboxlink -->
    <div class="col-md-24">
        <p><strong>URL affiliate</strong></p>
        <div class="input-group input-group-sm">
            <input data-toggle="tooltip" data-placement="top" title="Đã copy url" id="item_link_affiliate" data-trigger="manual" class="form-control link_refer" value="{BOXURL}" type="text">
            <span class="input-group-btn">
                <button class="btn btn-default" onclick="copyToClipboard_block('item_link_affiliate')"><i class="fa fa-copy"></i></button>
            </span>
        </div>
    </div>
    <!-- END: showboxlink -->
</div>
<script type="text/javascript">
    function copyToClipboard_block(obj) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($('#' + obj).val()).select();
        document.execCommand("copy");
        $temp.remove();
        $('#' + obj).tooltip('show');
        setTimeout(function(){ $('#' + obj).tooltip('hide'); }, 2000);
    }
</script>
<!-- END: main -->
