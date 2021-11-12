<!-- BEGIN: main -->
<style>
    .item{
        padding: 5px;
        margin-bottom: 5px;
        background-color: #f5f5f5;
        border: 1px solid #e3e3e3;
        border-radius: 4px;
    }
    .fix-search {
        position: fixed;
        top: 10px;
        z-index: 9999;
    }
</style>
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <div id="menuprovine" class="panel panel-default">
        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-5 col-md-3 control-label"><strong>{LANG.location}</strong></label>
                <div class="col-sm-19 col-md-21">
                    <div class="col-sm-12 col-md-6"><strong>{LANG.typecar}</strong></div>
                    <div class="col-sm-12 col-md-9"><strong>{LANG.registration_fee} (%)</strong></div>
                    <div class="col-sm-12 col-md-9"><strong>{LANG.license_plate_fee}</strong></div>
                </div>
            </div>
        </div>
    </div>
    <!-- BEGIN: loop -->
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-5 col-md-3 control-label"><strong>{PROVINCE.title}</strong></label>
                <div class="col-sm-19 col-md-21">
                    <input type="hidden" name="provinceid[]" value="{PROVINCE.id}">
                    <!-- BEGIN: typecarid -->
                    <div class="item row">
                        <div class="col-sm-12 col-md-6">{TYPECAR.title}</div>
                        <div class="col-sm-12 col-md-9"><input class="form-control numberinput registration_fee" type="text" name="registration_fee[{PROVINCE.id}_{TYPECAR.id}]" value="{TYPECAR.registration_fee}"  /></div>
                        <div class="col-sm-12 col-md-9"><input class="form-control numberinput" onkeyup="this.value=FormatNumber(this.value);" type="text" name="license_plate_fee[{PROVINCE.id}_{TYPECAR.id}]" value="{TYPECAR.license_plate_fee}"  /></div>
                    </div>
                    <!-- END: typecarid -->
                </div>
            </div>
        </div>
    </div>
    <!-- END: loop -->
    <div class="form-group text-center">
        <input class="btn btn-primary" name="savecat" type="submit" value="{LANG.save}" />
    </div>
</form>
<script>
    $(".registration_fee").on('keyup', function (event) {
        if($(this).val() > 100){
            $(this).val(100);
        }
    });
    $(".numberinput").on('keyup', function (event) {
        $(this).val($(this).val().replace(/[a-z]/, ''));
    });
    $("#menuprovine").css('width', $("#menuprovine").width());
</script>
<!-- END: main -->