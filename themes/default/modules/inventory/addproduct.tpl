<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">
	{ERROR}
</div>
<!-- END: error -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">

<form class="m-bottom" action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" enctype="multipart/form-data" method="post">
	<div class="row">
		<div class="col-sm-24 col-md-24">
			<table class="table table-striped table-bordered">
				<tbody>
                    <tr>
                        <td><strong>{LANG.select_cat}</strong>: <sup class="required">(*)</sup></td>
                        <td>
                            <select name="producerid" class="form-control">
                                <option value="0">-------</option>
                                <!-- BEGIN: select_cat -->
                                <option value="{CATS.id}"{CATS.sl}>{CATS.title}</option>
                                <!-- END: select_cat -->
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>{LANG.select_unit}</strong>: <sup class="required">(*)</sup></td>
                        <td>
                            <select name="unitid" class="form-control">
                                <option value="0">-------</option>
                                <!-- BEGIN: select_unit -->
                                <option value="{UNITS.id}"{UNITS.sl}>{UNITS.title}</option>
                                <!-- END: select_unit -->
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>{LANG.select_producttype}</strong>: <sup class="required">(*)</sup></td>
                        <td>
                            <select name="producttypeid" class="form-control">
                                <option value="0">-------</option>
                                <!-- BEGIN: select_producttype -->
                                <option value="{PRODUCTTYPE.id}"{PRODUCTTYPE.sl}>{PRODUCTTYPE.title}</option>
                                <!-- END: select_producttype -->
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>{LANG.status}</strong></td>
                        <td>
                            <select class="form-control" name="status">
                                <!-- BEGIN: status -->
                                <option value="{STATUS.key}" {STATUS.sl}>{STATUS.title}</option>
                                <!-- END: status -->
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>{LANG.select_department}</strong>: <sup class="required">(*)</sup></td>
                        <td>
                            <select name="departmentid" class="form-control">
                                <option value="0">-------</option>
                                <!-- BEGIN: select_department -->
                                <option value="{DEPARMENT.id}"{DEPARMENT.sl}>{DEPARMENT.title}</option>
                                <!-- END: select_department -->
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>{LANG.room_use}</strong>:</td>
                        <td><input type="text" maxlength="250" value="{rowcontent.room_use}" id="code" name="room_use" class="form-control"  style="width:350px"/></td>
                    </tr>
                    <tr>
                        <td><strong>{LANG.code}</strong>:</td>
                        <td><input type="text" maxlength="250" value="{rowcontent.code}" id="code" name="code" class="form-control"  style="width:350px"/></td>
                    </tr>
                    <tr>
                        <td><strong>{LANG.title}</strong>: <sup class="required">(*)</sup></td>
                        <td><input type="text" maxlength="250" value="{rowcontent.title}" id="idtitle" name="title" class="form-control"  style="width:350px"/></td>
                    </tr>
                    <tr>
                        <td><strong>{LANG.time_in}</strong>: <sup class="required">(*)</sup></td>
                        <td><input type="text" maxlength="250" value="{rowcontent.time_in}" name="time_in" id="time_in" class="form-control"  style="width:350px"/></td>
                    </tr>
                    <tr>
                        <td>
                            <strong data-placement="bottom" data-content="{LANG.time_depreciation_note}" data-rel="tooltip" data-original-title="" title="">{LANG.time_depreciation}</strong>: <sup class="required">(*)</sup>
                            <i class="fa fa-info" data-placement="bottom" data-content="{LANG.time_depreciation_note}" data-rel="tooltip" data-original-title="" title=""></i>
                        </td>
                        <td><input type="text" maxlength="250" value="{rowcontent.time_depreciation}" name="time_depreciation" class="form-control"  style="width:350px"/></td>
                    </tr>
                    <tr>
                        <td><strong>{LANG.amount}: </strong><sup class="required">(*)</sup></td>
                        <td><input class="form-control" name="amount" onkeyup="this.value=FormatNumber(this.value);" type="text" value="{rowcontent.amount}" maxlength="250"  style="width:350px"/></td>
                    </tr>
                    <tr>
                        <td><strong>{LANG.price_total}: </strong><sup class="required">(*)</sup></td>
                        <td><input class="form-control" name="price" onkeyup="this.value=FormatNumber(this.value);" type="text" value="{rowcontent.price}" maxlength="250"  style="width:350px"/>&nbsp;</td>
                    </tr>

				</tbody>
			</table>
            <div class="text-center">
        		<input type="hidden" value="1" name="save" />
        		<input type="hidden" value="{rowcontent.id}" name="id" />
                <input class="btn btn-primary submit-post" name="submit" type="submit" value="{LANG.save}" />
        	</div>
		</div>
	</div>
</form>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript">
    $("#time_in").datepicker({
        showOn : "focus",
        dateFormat : "dd/mm/yy",
        changeMonth : true,
        changeYear : true,
        showOtherMonths : true,
        buttonImage : nv_base_siteurl + "assets/images/calendar.gif",
        buttonImageOnly : true
    });
</script>
<!-- END:main -->