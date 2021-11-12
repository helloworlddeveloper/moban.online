<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<div class="table-responsive">
<table class="table table-striped table-bordered table-hover">
	<tbody>
		<tr>
			<td>
				<form class="form-inline" id="filter-form" method="get" action="" onsubmit="return false;">
					<input class="form-control" style="width:130px;" type="text" name="q" value="{DATA_SEARCH.q}" placeholder="{LANG.filter_enterkey}"/>
                    {LANG.timephathanh}
                    <input placeholder="{LANG.from}" class="form-control" type="text" name="timebegin" value="{DATA_SEARCH.timebegin}" id="timebegin" />
                    <input placeholder="{LANG.to}" class="form-control" type="text" name="timeend" value="{DATA_SEARCH.timeend}" id="timeend" />
                    <input class="btn btn-primary" type="button" name="do" value="{LANG.filter_action}"/>
    					<input class="btn btn-default" type="button" name="cancel" value="{LANG.filter_cancel}" onclick="window.location='{URL_CANCEL}';"{DATA_SEARCH.disabled}/>
    					<input class="btn btn-default" type="button" name="clear" value="{LANG.filter_clear}"/>
                        <input class="btn btn-primary" type="button" name="add_question" value="{LANG.thembaihoc}" onclick="window.location='{URL_ADD}';"/>
                    <div class="clearfix">&nbsp;</div>
				</form>
			</td>
		</tr>
	</tbody>
</table>
</div>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript">
$("#timebegin,#timeend").datepicker({
		showOn : "focus",
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
		buttonImage : nv_base_siteurl + "images/calendar.gif",
		buttonImageOnly : true
	});
$(document).ready(function(){
	$('input[name=clear]').click(function(){
		$('#filter-form .text').val('');
		$('input[name=q]').val('');
	});
	$('input[name=do]').click(function(){
		var f_q = $('input[name=q]').val();
		var f_timebegin = $('input[name=timebegin]').val();
		var f_timeend = $('input[name=timeend]').val();
		if (  f_q != '' || f_timebegin != '' || f_timeend != '' )
		{
			$('#filter-form input, #filter-form select').attr('disabled', 'disabled');
			window.location = '{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}&khoahocid={KHOAHOC.id}&q=' + f_q + '&timebegin=' + f_timebegin + '&timeend=' + f_timeend;	
		}
		else
		{
			alert ('{LANG.filter_err_submit}');
		}
	});
});
</script>

<form action="{FORM_ACTION}" method="post" name="levelnone" id="levelnone">
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <caption>{KHOAHOC.title}</caption>
		<tbody>
			<tr>
				<td style="width:30px">{LANG.weight}</td>
                <td>{LANG.title_khoahoc}</td>
                <td style="width:100px">{LANG.price_baihoc}</td>
                <td style="width:100px">{LANG.timephathanh}</td>
                <td style="width:40px;" class="text-center"><i title="{LANG.numview}" class="fa fa-eye">&nbsp;</i></td>
                <td style="width:40px;" class="text-center"><i title="{LANG.numlike}" class="fa fa-hand-peace-o">&nbsp;</i></td>
                <td style="width:40px;" class="text-center"><i title="{LANG.numbuy}" class="fa fa-usd">&nbsp;</i></td>
				<td style="width:90px">{LANG.addtime}</td>
				<td class="center">{LANG.feature}</td>
			</tr>
		<!-- BEGIN: row -->
			<tr class="topalign">
				<td>
					<input style="width:60px" onchange="nv_save_weight_baihoc('{ROW.id}')" class="form-control" size="4" id="id_weight_{ROW.id}" type="text" name="weight" value="{ROW.weight}" />
				</td>
				<td>{ROW.title}</td>
                <td>{ROW.price}</td>
                <td>{ROW.timephathanh}</td>
                <td class="text-center">{ROW.numview}</td>
                <td class="text-center">{ROW.numlike}</td>
                <td class="text-center">{ROW.numbuy}</td>
				<td><strong>{ROW.addtime}</strong></td>
				<td class="center">
                    <em class="fa fa-edit fa-lg">&nbsp;</em><a href="{ROW.url_edit}">{LANG.edit}</a>
					<em class="fa fa-trash-o fa-lg"></em>&nbsp;<a href="javascript:void(0);" onclick="nv_delete_baihoc({ROW.id});">{GLANG.delete}</a>
				</td>
			</tr>
		<!-- END: row -->
		</tbody>
		<!-- BEGIN: generate_page -->
		<tbody>
			<tr>
				<td colspan="9">
					{GENERATE_PAGE}
				</td>
			</tr>
		</tbody>
		<!-- END: generate_page -->
	</table>
</div>
</form>
<script type="text/javascript">
    function nv_save_weight_baihoc(id) {
		var nv_timer = nv_settimeout_disable('id_weight_' + id, 5000);
		var new_vid = $('#id_weight_' + id).val();
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '={OP}&nocache=' + new Date().getTime(), 'ajax_action=1&baihoc=' + id + '&new_vid=' + new_vid, function(res) {
			var r_split = res.split('_');
			if (r_split[0] != 'OK') {
				alert(nv_is_change_act_confirm[2]);
			}
			clearTimeout(nv_timer);
			return;
		});
		return;
	}
</script>
<!-- END: main -->