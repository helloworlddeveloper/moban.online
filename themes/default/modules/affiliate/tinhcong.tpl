<!-- BEGIN: main -->
<!-- BEGIN: view -->
<link href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/bootstrap-select.min.css" rel="stylesheet" />
<form action="{BASE_URL_SITE}" name="fsea" method="get">
	<input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" />
	<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
    <div class="row">
        <div class="col-sm-8 col-md-8">
            <strong>{LANG.chossen_teacher}</strong>
            <select name="teacher[]" class="selectpicker" multiple>
              <!-- BEGIN: teacher -->
              <option{TEACHER.sl} value="{TEACHER.userid}">{TEACHER.full_name}</option>
              <!-- END: teacher -->
            </select>
        </div>
        <div class="col-sm-8 col-md-8">
            <strong>{LANG.chossen_type}</strong>
            <select name="type[]" class="selectpicker" multiple >
                <!-- BEGIN: type -->
                <option{TYPE.sl} value="{TYPE.key}">{TYPE.title}</option>
                <!-- END: type -->
            </select>
        </div>
        <div class="col-sm-8 col-md-8">
            <strong>{LANG.chossen_status}</strong>
            <select name="status[]" class="selectpicker" multiple >
                <!-- BEGIN: status -->
                <option{STATUS.sl} value="{STATUS.key}">{STATUS.title}</option>
                <!-- END: status -->
            </select>
        </div>
        <div class="col-sm-24 col-md-24">
            <div class="form-inline">
                {LANG.from}&nbsp;<input class="form-control" name="starttime" id="starttime" value="{DATA_SEARCH.starttime}" placeholder="dd/mm/yyyy" style="width: 100px;" type="text" />&nbsp;
                {LANG.to}&nbsp;<input class="form-control" name="endtime" id="endtime" value="{DATA_SEARCH.endtime}" placeholder="dd/mm/yyyy" style="width: 100px;" type="text" />
                &nbsp;<input type="submit" name="submit" class="btn btn-primary" value="{LANG.search_data}" />
                <!-- BEGIN: export -->
                &nbsp;
                <span id="loading_bar"><input type="button" class="btn btn-primary" name="data_export" value="{LANG.export}" /></span>
                <!-- END: export -->
            </div>
        </div>
    </div>
    <script src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/bootstrap-select.min.js" type="text/javascript"></script>
    <script src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/jquery.maskedinput.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        jQuery(function($){
           $("#starttime,#endtime").mask("99/99/9999",{placeholder:"dd/mm/yyyy"});
        });
        
    </script>
</form>
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr class="text-center strong-text">
                    <td rowspan="2">{LANG.teacher_name}</td>
                    <td rowspan="2">{LANG.teacher_code}</td>
                    <td colspan="2">{LANG.type_1}</td>
                    <td colspan="2">{LANG.type_2}</td>
                    <td rowspan="2">{LANG.tinhcagio}</td>
                    <td rowspan="2">{LANG.ngaycong_bitru}</td>
                    <td rowspan="2">{LANG.salary}</td>
                    <td rowspan="2">{LANG.benefit}</td>
                    <td rowspan="2">{LANG.total_salary}</td>
                </tr>
                <tr class="text-center strong-text">
                    <td>{LANG.tongcong}</td>
                    <td>{LANG.cophep}</td>
                    <td>{LANG.tongcong}</td>
                    <td>{LANG.cophep}</td>
                </tr>
            </thead>
            <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td>{VIEW.teacher.last_name} {VIEW.teacher.first_name}</td>
                <td> {VIEW.teacher.code} </td>
                <td> {VIEW.dimuon} </td>
                <td> {VIEW.dimuoncophep} </td>
                <td> {VIEW.vesom} </td>
                <td> {VIEW.vesomcophep} </td>
                <td> {VIEW.ngaycong} </td>
                <td> {VIEW.ngaycong_bi_tru} </td>
                <td> {VIEW.salary} </td>
                <td> {VIEW.teacher.benefit} </td>
                <td> {VIEW.total_salary} </td>
            </tr>
            <!-- END: loop -->
            </tbody>
        </table>
    </div>
</form>
<!-- END: view -->
<script type="text/javascript">
    function nv_data_export(set_export, nextid, starttime, endtime) {
        $.ajax({
            type : "POST",
            url : nv_base_siteurl + "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=export&nocache=" + new Date().getTime(),
            data : "step=1&set_export=" + set_export + '&nextid=' + nextid+ '&starttime=' + starttime + '&endtime=' + endtime,
            success : function(response) {
                var data = response.split('_');
                if ( data[0] == "NEXT") {
                    nv_data_export(0, data[1], starttime, endtime);
                } else if (data[0] == "COMPLETE") {
                    $("#loading_bar").hide();
                    alert('{LANG.export_complete}');
                    window.location.href = nv_base_siteurl + 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=export&step=2';
                } else {
                    $("#loading_bar").hide();
                    alert(response);
                    window.location.href = nv_base_siteurl + 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=tinhcong';
                }
            }
        });
    }

    $("input[name=data_export]").click(function() {
        $("input[name=data_export]").attr("disabled", "disabled");
        $('#loading_bar').html('<center>{LANG.export_note}<br /><br /><img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/load_bar.gif" alt="" /></center>');
        nv_data_export(1, 0, '{DATA_SEARCH.starttime}', '{DATA_SEARCH.endtime}');
    });
</script>
<!-- END: main -->