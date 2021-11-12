<!-- BEGIN: main -->
<div class="input-group input-group-sm">
    <input type="text" class="form-control" value="{URL}" id="FileAbsolutePath">
    <span class="input-group-btn">
       <button class="btn btn-default" onclick="copyToClipboard()" ><i class="fa fa-copy"></i></button>
    </span>
</div>
<script>
    function copyToClipboard() {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($('#FileAbsolutePath').val()).select();
        document.execCommand("copy");
        $temp.remove();
        alert('Đường dẫn đã được sao chép');
    }
</script>
<!-- END: main -->