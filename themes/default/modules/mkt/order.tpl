<!-- BEGIN: main -->
<!-- BEGIN: view -->
<form class="form-inline" action="" method="post">
	<input type="hidden" name="confirm" value="1" />
    <div class="head-title">{LANG.confirm_order}</div>
    <table class="table table-striped table-bordered table-hover">
    	<tbody>
    		<tr>
                <td style="width:150px">{LANG.fullname}</td>
                <td><strong>{ORDER.fullname}</strong></td>
    		</tr>
    		<tr>
                <td>{LANG.address}</td>
                <td><strong>{ORDER.address}</strong></td>
    		</tr>
            <tr>
                <td>{LANG.province}</td>
                <td><strong>{ORDER.province}</strong></td>
    		</tr>
            <tr>
                <td>{LANG.phone}</td>
                <td><strong>{ORDER.phone}</strong></td>
    		</tr>
            <tr>
                <td>{LANG.email}</td>
                <td><strong>{ORDER.email}</strong></td>
    		</tr>
            <tr>
                <td>{LANG.company}</td>
                <td><strong>{ORDER.company}</strong></td>
    		</tr>
            <tr>
                <td>{LANG.require_content}</td>
                <td><strong>{ORDER.require_content}</strong></td>
    		</tr>
            <tr>
                <td>{LANG.purpose}</td>
                <td><strong>{ORDER.purpose}</strong></td>
    		</tr>
            <tr>
                <td>{LANG.timestart}</td>
                <td><strong>{ORDER.timestart_hour}:{ORDER.timestart_min}&nbsp;{ORDER.timestart}</strong></td>
    		</tr>
    	    <tr>
                <td>{LANG.timeend}</td>
                <td><strong>{ORDER.timeend_hour}:{ORDER.timeend_min}&nbsp;{ORDER.timeend}</strong></td>
    		</tr>
            <tr>
                <td>{LANG.num_rerson}</td>
                <td><strong>{ORDER.num_rerson}</strong></td>
    		</tr>
    		<tr>
                <td colspan="2">
                    <input class="btn btn-primary" name="confirm_submit_order" type="submit" value="{LANG.confirm_submit_order}" />
                    <input class="btn btn-primary" name="gohistory" type="button" onclick="window.history.go(-1);" value="{LANG.edit_order}" />
                </td>
    		</tr>
    	</tbody>
    </table>
</form>
<!-- END: view -->
<!-- BEGIN: add -->
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.menu.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
<div class="head-title">{LANG.order_note}</div>
<!-- BEGIN: error -->
<div class="alert alert-warning"><span style="color:#f00">{ERROR}</span></div>
<!-- END: error -->
<form class="form-inline" action="" method="post">
	<input type="hidden" name="room_id" value="{DATA_ROOM.room_id}" />
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<tbody>
				<tr>
					<td colspan="2"><input placeholder="{LANG.fullname} (*)" class="form-control w100" type="text" name="fullname" value="{ORDER.fullname}" required="true" /></td>
				</tr>
				<tr>
					<td colspan="2"><input placeholder="{LANG.address} (*)" class="form-control w100" type="text" name="address" value="{ORDER.address}" required="true" /></td>
				</tr>
                <tr>
					<td colspan="2">
                        <select class="form-control w100" name="province">
                            <option value="0">--{LANG.select_province}--</option>
                            <!-- BEGIN: province -->
                            <option value="{PROVINCE.province_id}"{PROVINCE.sl}>{PROVINCE.title}</option>
                            <!-- END: province -->
                        </select>
                    </td>
				</tr>
                <tr>
					<td class="w50"><input placeholder="{LANG.phone} (*)" class="form-control w100" type="text" name="phone" value="{ORDER.phone}" required="true" /></td>
                    <td class="w50"><input placeholder="{LANG.email} (*)" class="form-control w100" type="email" oninvalid="setCustomValidity( nv_email )" name="email" value="{ORDER.email}" required="true" /></td>
				</tr>
                <tr>
					<td colspan="2"><input placeholder="{LANG.company}" class="form-control w100" type="text" name="company" value="{ORDER.company}" /></td>
				</tr>
                <tr>
					<td colspan="2"><textarea placeholder="{LANG.require_content}" class="form-control w100" style="height:100px;" name="require_content">{ORDER.require_content}</textarea></td>
				</tr>
				<tr>
					<td colspan="2"><input placeholder="{LANG.purpose}" class="form-control w100" type="text" name="purpose" value="{ORDER.purpose}" /></td>
				</tr>
				</tr>
				<tr>
					<td colspan="2">{LANG.timestart}: <input placeholder="{LANG.hour} (*)" class="form-control" type="text" pattern="^[0-9]{1,2}$" name="timestart_hour" value="{ORDER.timestart_hour}" >:<input  placeholder="{LANG.minute} (*)" class="form-control" type="text" pattern="^[0-9]{1,2}$" name="timestart_min" value="{ORDER.timestart_min}" >&nbsp;<input placeholder="{LANG.date} (*)" class="form-control" type="text" name="timestart" value="{ORDER.timestart}" id="timestart" pattern="^[0-9]{2,2}\/[0-9]{2,2}\/[0-9]{1,4}$" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" /></td>
				</tr>
				<tr>
					<td colspan="2">{LANG.timeend}: <input placeholder="{LANG.hour} (*)" class="form-control" type="text" pattern="^[0-9]{1,2}$" name="timeend_hour" value="{ORDER.timeend_hour}" >:<input placeholder="{LANG.minute} (*)" class="form-control" type="text" pattern="^[0-9]{1,2}$" name="timeend_min" value="{ORDER.timeend_min}" >&nbsp;<input placeholder="{LANG.date} (*)" class="form-control" type="text" name="timeend" value="{ORDER.timeend}" id="timeend" pattern="^[0-9]{2,2}\/[0-9]{2,2}\/[0-9]{1,4}$" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" /></td>
				</tr>
				<tr>
					<td><input placeholder="{LANG.num_rerson} (*)" class="form-control" type="text" name="num_rerson" value="{ORDER.num_rerson}" pattern="^[0-9]*$" oninvalid="setCustomValidity( nv_digits )" oninput="setCustomValidity('')" required="required" /></td>
                    <td><input class="btn btn-primary" name="submit" type="submit" value="{LANG.submit_order}" /></td>
				</tr>
			</tbody>
		</table>
	</div>
</form>

<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.menu.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<script type="text/javascript">
//<![CDATA[
	$("#timestart,#timeend").datepicker({
		showOn : "focus",
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
		buttonImage : nv_siteroot + "images/calendar.gif",
		buttonImageOnly : true
	});

//]]>
</script>
<!-- END: add -->
<!-- END: main -->