<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_DATA}.js"></script>
<div class="well">
	<form action="{NV_BASE_ADMINURL}index.php" method="get">
		<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
		<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />

		<div class="row">
			<div class="col-xs-12 col-md-6">
				<div class="form-group">
					<input class="form-control" type="text" value="{Q}" maxlength="64" name="q" placeholder="{LANG.search_key}" />
				</div>
			</div>
			<div class="col-xs-12 col-md-4">
				<div class="form-group">
					<select class="form-control" name="departmentid">
                        <option value="0"> -- {LANG.search_department} -- </option>
						<!-- BEGIN: cat_content -->
						<option value="{CAT_CONTENT.id}" {CAT_CONTENT.selected} >{CAT_CONTENT.title}</option>
						<!-- END: cat_content -->
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-md-3">
				<div class="form-group">
					<select class="form-control" name="sstatus">
						<option value="-1"> -- {LANG.search_status} -- </option>
						<!-- BEGIN: search_status -->
						<option value="{SEARCH_STATUS.key}" {SEARCH_STATUS.selected} >{SEARCH_STATUS.value}</option>
						<!-- END: search_status -->
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-md-2">
				<div class="form-group">
					<select class="form-control" name="per_page">
						<option value="">{LANG.search_per_page}</option>
						<!-- BEGIN: s_per_page -->
						<option value="{SEARCH_PER_PAGE.page}" {SEARCH_PER_PAGE.selected}>{SEARCH_PER_PAGE.page}</option>
						<!-- END: s_per_page -->
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-md-6">
				<div class="form-group">
					<input class="btn btn-primary" type="submit" value="{LANG.search}" />&nbsp;
                    <a class="btn btn-primary" href="{addproduct}">{LANG.addproduct}</a>
				</div>
			</div>
			<div class="col-md-24 clearfix">
				<div class="col-md-7">
					{LANG.chossen_time_export}:
					<input type="text" name="time_export" id="time_export" placeholder="mm/yyyy" class="form-control">
				</div>
				<div class="col-md-10">
					<div id="action_export">
						<input class="btn btn-primary" type="button" name="export_khauhaotaisan" id="export_khauhaotaisan" value="{LANG.export_khauhaotaisan}">
						&nbsp;<input class="btn btn-primary" type="button" name="export_phanboccdc" id="export_phanboccdc" value="{LANG.export_phanboccdc}">
					</div>
					<div id="loading" class="text-center"><img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/load_bar.gif"></div>
				</div>

			</div>
		</div>
		<input type="hidden" name="checkss" value="{NV_CHECK_SESSION}" />
	</form>
</div>

<form class="navbar-form" name="block_list" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th class="text-center">{LANG.code}</th>
					<th class="text-center">{LANG.title}</th>
					<th class="text-center">{LANG.department_name}</th>
					<th class="text-center">{LANG.status}</th>
					<th class="text-center">{LANG.amount}</th>
					<th class="text-center">{LANG.price_total}</th>
					<th class="text-center">{LANG.time_in}</th>
					<th class="text-center">{LANG.time_depreciation}</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: loop -->
				<tr class="{ROW.class}">
					<td>{ROW.code}</td>
					<td class="text-left">{ROW.title}</td>
					<td>{ROW.department}</td>
					<td title="{ROW.status}">{ROW.status}</td>
					<td class="text-center">{ROW.amount}</td>
					<td class="text-center">{ROW.price}</td>
					<td class="text-center">{ROW.time_in}</td>
					<td class="text-center">{ROW.time_depreciation}</td>
					<td class="text-center">
                        <i class="fa fa-edit fa-lg">&nbsp;</i><a href="{ROW.link_edit}">{LANG.edit}</a> - <em class="fa fa-trash-o fa-lg">&nbsp;</em><a href="{ROW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a>
                    </td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
</form>
<!-- BEGIN: generate_page -->
<div class="text-center">
	{GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<script src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/bootstrap-select.min.js" type="text/javascript"></script>
<script src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/jquery.maskedinput.min.js" type="text/javascript"></script>
<script type="text/javascript">
	$('#loading').hide();
    jQuery(function($){
        $("#time_export").mask("99/9999",{placeholder:"mm/yyyy"});
    });
	$(document).ready(function() {
		$("#catid").select2({
			language : '{NV_LANG_DATA}'
		});
	});

    $(document).ready(function(){
        $('#export_khauhaotaisan').click(function(){
			if( $('#time_export').val() == ''){
                $('#time_export').focus();
            }else{
                $('#action_export').hide();
                $('#loading').show();
                $.ajax({
                    type: 'post',
                    url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=product',
                    data: 'export&step=1&act=khauhaotaisan&time_export=' + $('#time_export').val(),
                    dataType: "json",
                    success: function(b) {
                        $('#action_export').show();
                        $('#loading').hide();
                        if(b.status == 'OK'){
                            window.location.href = nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=product&export&step=2';
                        }
                        else{
                            alert(b.mess);
                        }
                    }
                });
            }

        });
        $('#export_phanboccdc').click(function(){
            if( $('#time_export').val() == ''){
                $('#time_export').focus();
            }else{
                $('#action_export').hide();
                $('#loading').show();
                $.ajax({
                    type: 'post',
                    url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=product',
                    data: 'export&step=1&act=phanboccdc&time_export=' + $('#time_export').val(),
                    dataType: "json",
                    success: function(b) {
                        $('#action_export').show();
                        $('#loading').hide();
                        if(b.status == 'OK'){
                            window.location.href = nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=product&export&step=2';
                        }
                        else{
                            alert(b.mess);
                        }
                    }
                });
            }

        });
    });
</script>
<!-- END: main -->