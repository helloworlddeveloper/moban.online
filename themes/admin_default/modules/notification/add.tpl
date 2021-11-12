<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<!-- BEGIN: error -->
<div style="width: 780px;" class="quote">
    <blockquote class="error">
        <p>
            <span>{ERROR}</span>
        </p>
    </blockquote>
</div>
<div class="clear"></div>
<!-- END: error -->
<form action="{FORM_CATION}" method="post">
<input type="hidden" name="id" value="{DATA.id}" />
    <div class="col-md-16">
        <div class="table-responsive">
            <table id="table_field_read" class="table table-striped table-bordered table-hover">
                <tbody>
                <tr>
                    <td style="width:200px">
                        {LANG.notification_message} (<span style="color:#FF0000">*</span>)
                    </td>
                    <td style="white-space: nowrap">
                        <textarea maxlength="255" name="message" class="form-control">{DATA.message}</textarea>
                    </td>
                </tr>
                <tr>
                    <td style="width:200px">
                        {LANG.notification_description}
                    </td>
                    <td style="white-space: nowrap">
                        <textarea name="description" class="form-control">{DATA.description}</textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        {LANG.notification_url}
                    </td>
                    <td style="white-space: nowrap">
                        <input class="form-control" type="text" value="{DATA.url}" name="url" style="width:300px" maxlength="255" />
                    </td>
                </tr>
                <tr>
                    <td>
                        {LANG.notification_icon}
                    </td>
                    <td class="form-inline">
                        <input class="form-control" type="text" value="{DATA.icon}" name="icon" id="icon" style="width:300px" maxlength="255" />
                        &nbsp;<input type="button" value="Browse server" name="selectimg" class="btn btn-info" />
                    </td>
                </tr>
                <tr class="form-inline">
                    <td>
                        {LANG.notification_addtime}
                    </td>
                    <td style="white-space: nowrap">
                        <select class="form-control" name="phour">
                            {phour}
                        </select>
                        :
                        <select class="form-control" name="pmin">
                            {pmin}
                        </select>
                        <input class="form-control" type="text" value="{DATA.addtime}" name="addtime" id="addtime" maxlength="10" />
                    </td>
                </tr>
                <tr>
                    <td>
                        {LANG.notification_author}
                    </td>
                    <td style="white-space: nowrap">
                        <input class="form-control" type="text" value="{DATA.author}" name="author" style="width:300px" maxlength="255" />
                    </td>
                </tr>
                <tr>
                    <td>
                        {LANG.notification_allowed_view}
                    </td>
                    <td style="white-space: nowrap">
                        <!-- BEGIN: allowed_view -->
                        <div class="row">
                            <label><input name="allowed_view[]" type="checkbox" value="{ALLOWED_VIEW.value}" {ALLOWED_VIEW.checked} />{ALLOWED_VIEW.title}</label>
                        </div>
                        <!-- END: allowed_view -->
                    </td>
                </tr>
                <tr>
                    <td>
                        {LANG.notification_status}
                    </td>
                    <td style="white-space: nowrap">
                        <select class="form-control" name="status">
                            <!-- BEGIN: status -->
                            <option value="{STATUS.key}"{STATUS.selected}>{STATUS.title}</option>
                            <!-- END: status -->
                        </select>
                    </td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="2" style="text-align:center">
                        <input class="btn btn-primary" type="submit" value="{LANG.save}" name="submit" />
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="col-md-8">
        <span style="margin-top: 10px; display: block; font-weight: bold"><h2>{LANG.content_note}</h2></span>
        <blockquote class="personal">
            <div class="row">
                <!-- BEGIN: personal -->
                <div class="col-xs-24 col-sm-24">
                    <label><strong>{PERSONAL.index}</strong></label> {PERSONAL.value}
                </div>
                <!-- END: personal -->
            </div>
        </blockquote>
    </div>
</form>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript">
$("#addtime").datepicker({
	showOn : "focus",
	dateFormat : "dd/mm/yy",
	changeMonth : true,
	changeYear : true,
	showOtherMonths : true,
	buttonImage : nv_base_siteurl + "assets/images/calendar.gif",
	buttonImageOnly : true
});
$("input[name=selectimg]").click(function() {
	var area = "icon";
	var alt = "homeimgalt";
	var path = "{UPLOADS_DIR}";
	var currentpath = "{UPLOADS_DIR}";
	var type = "image";
	nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&alt=" + alt + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
	return false;
});
</script>
<!-- END: main -->