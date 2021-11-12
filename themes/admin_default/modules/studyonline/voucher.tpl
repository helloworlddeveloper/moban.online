<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css" />
<!-- BEGIN: view -->
<form class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>{LANG.voucher_name}</th>
                    <th>{LANG.timeallow}</th>
					<th>{LANG.totalvoucher}</th>
					<th>{LANG.status}</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td> <a href="{VIEW.link_viewcode}">{VIEW.title}</a></td>
                    <td> {VIEW.timeallow} </td>
					<td> {VIEW.totalvoucher} </td>
					<td> {VIEW.status} </td>
					<td class="text-center"><i class="fa fa-edit fa-lg">&nbsp;</i> <a href="{VIEW.link_edit}">{LANG.edit}</a> - <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="{VIEW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a></td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
        <div class="text-center"><a class="btn btn-primary" href="{addnew_voucher}">{LANG.addnew_voucher}</a></div>
	</div>
</form>
<!-- END: view -->
<!-- BEGIN: addnew -->
<div class="row">
    <!-- BEGIN: error -->
    <div class="alert alert-warning">{ERROR}</div>
    <!-- END: error -->
    <form class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    	<input type="hidden" name="id" value="{ROW.id}" />
    	<div class="table-responsive">
    		<table class="table table-striped table-bordered table-hover">
    			<tbody>
    				<tr>
    					<td> {LANG.voucher_name} </td>
    					<td><input class="form-control w300" type="text" name="title" value="{ROW.title}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" /></td>
    				</tr>
    				<tr>
    					<td> {LANG.allowfor} </td>
    					<td>
                            <div class="message_body" style="overflow: auto">
								<div class="clearfix uiTokenizer uiInlineTokenizer">
		                            <div id="khoahocid" class="tokenarea">
		                                <!-- BEGIN: classes_teacher -->
		                                <span class="uiToken removable" title="{TEACHER.title} - {TEACHER.mobile}" ondblclick="$(this).remove();">
		                                    {TEACHER.title} - {TEACHER.mobile}
		                                    <input type="hidden" autocomplete="off" name="khoahocid[]" value="{TEACHER.id}" />
		                                    <a onclick="$(this).parent().remove();" class="remove uiCloseButton uiCloseButtonSmall" href="javascript:void(0);"></a>
		                                </span>
		                                <!-- END: classes_teacher -->
		                            </div>
		                            <div class="uiTypeahead">
		                                <div class="wrap">
		                                    <input type="hidden" class="hiddenInput" autocomplete="off" value="" />
		                                    <div class="innerWrap">
		                                        <input id="khoahoc_search" type="text" placeholder="{LANG.voucher_of_khoahoc}" class="form-control textInput" style="width: 100%;" />
		                                    </div>
		                                </div>
		                            </div>
		                        </div>
		                	</div>
                        </td>
    				</tr>
                    <tr>
    					<td> {LANG.totalvoucher} </td>
    					<td><input class="form-control w300" type="text" name="totalvoucher" value="{ROW.totalvoucher}" /></td>
    				</tr>
    				<tr>
    					<td> {LANG.timeallow} </td>
    					<td>
                            <input class="form-control w150" placeholder="{LANG.from}" type="text" id="timeallow_from" name="timeallow_from" value="{ROW.timeallow_from}" />
                            <input class="form-control w150" placeholder="{LANG.to}" type="text" id="timeallow_to" name="timeallow_to" value="{ROW.timeallow_to}" />
                        </td>
    				</tr>
                    <tr>
                        <td>{LANG.status}</td>
    					<td>
                            <select class="form-control" name="status">
            					<!-- BEGIN: select_status -->
            					<option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option>
            					<!-- END: select_status -->
            				</select>
                        </td>
    				</tr>
    			</tbody>
    		</table>
    	</div>
        <div style="text-align: center"><input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" /></div>
    </form>
</div>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/admin_default/js/studyonline_searchajax.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript">
    $("#timeallow_from,#timeallow_to").datepicker({
		showOn : "focus",
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
		buttonImage : nv_base_siteurl + "images/calendar.gif",
		buttonImageOnly : true
	});
</script>
<!-- END: addnew -->
<!-- END: main -->