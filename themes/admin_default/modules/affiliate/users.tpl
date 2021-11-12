<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">

<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<div id="module_show_list">
	{CAT_LIST}
</div>
<br />

<div id="edit">
	<!-- BEGIN: error -->
	<div class="alert alert-warning">
		{ERROR}
	</div>
	<!-- END: error -->
	<!-- BEGIN: content -->
	<form action="{NV_BASE_ADMINURL}index.php" method="post">
		<input type="hidden" name ="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
		<input type="hidden" name ="{NV_OP_VARIABLE}" value="{OP}" />
		<input type="hidden" name ="userid" value="{DATA.userid}" />
		<input name="savecat" type="hidden" value="1" />
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<caption>
					<em class="fa fa-file-text-o">&nbsp;</em>{caption}
				</caption>
				<tr>
					<td>{LANG.catinfo}</td>
					<td>
						<select class="form-control" name="parentid" id="parentid">
							<option value="0">--{LANG.root_level}--</option>
							<!-- BEGIN: catinfo -->
							<option value="{CAT_SUB.value}"{CAT_SUB.selected}>{CAT_SUB.title}</option>
							<!-- END: catinfo -->
						</select>
					</td>
				</tr>
				<tr>
					<td style="width:150px;">{LANG.fullname}<i class="requie">(*)</i></td>
					<td style="width:370px;">
						<div class="uiTokenizer uiInlineTokenizer"  style="float:left;">
	                            <span id="userid" class="tokenarea">
                                    <!-- BEGIN: data_users -->
                                    <span class="uiToken removable" title="{DATA.fullname}">
                                        {DATA.fullname}<input type="hidden" autocomplete="off" name="userid" value="{DATA.userid}" />
                                        <a class="remove uiCloseButton uiCloseButtonSmall" href="javascript:void(0);" onclick="$(this).parent().remove();"></a>
                                    </span>
									<!-- END: data_users -->
                                </span>
							<span class="uiTypeahead">
                                    <input type="hidden" class="hiddenInput" autocomplete="off" value="" />
                                    <div class="innerWrap" style="float:left;">
                                        <input id="group_cat-search" type="text" placeholder="{LANG.input_customer}" class="form-control textInput" style="width: 100%;" />
                                    </div>
	                            </span>
						</div>
					</td>
				</tr>
				<tr>
					<td>{LANG.username}</td>
					<td><span id="username"></span></td>
				</tr>
				<tr>
					<td style="width:150px;">{LANG.email}</td>
					<td><span id="email"></span></td>
				</tr>
				<tr>
					<td>{LANG.birthday}</td>
					<td>
						<span id="birthday"></span>
					</td>
				</tr>
				<tr>
					<td>{LANG.possiton}<i class="requie">(*)</i></td>
					<td>
						<select class="form-control" name="possitonid">
							<option value="0">------</option>
							<!-- BEGIN: possiton -->
							<option value="{POSSITION.id}"{POSSITION.sl}>{POSSITION.title}</option>
							<!-- END: possiton -->
						</select>
					</td>
				</tr>
				<tr>
					<td>{LANG.agencyid}<i class="requie">(*)</i></td>
					<td>
						<select class="form-control" name="agencyid">
							<option value="0">------</option>
							<!-- BEGIN: agency -->
							<option value="{AGENCY.id}"{AGENCY.sl}>{AGENCY.title} - {AGENCY.price_require}</option>
							<!-- END: agency -->
						</select>
					</td>
				</tr>
				<tr>
					<td>{LANG.province_select}</td>
					<td>
						<select onchange="nv_load_district('districtid', 0)" class="form-control chosen-select" name="provinceid">
							<option value=""> --- </option>
							<!-- BEGIN: select_province -->
							<option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option>
							<!-- END: select_province -->
						</select>
					</td>
				</tr>
				<tr>
					<td>{LANG.district_select}</td>
					<td>
						<div id="load_district">
							<select  class="form-control" name="districtid" id="district" style="width:100%;" tabindex="2">
								<option value="0"> --- </option>
							</select>
						</div>
					</td>
				</tr>
				<tr>
					<td>{LANG.salary_day}</td>
					<td>
						<input type="text" placeholder="{LANG.salary_day}" value="{DATA.salary_day}" name="salary_day" class="form-control" />
					</td>
				</tr>
				<tr>
					<td>{LANG.benefit}</td>
					<td>
						<input type="text" placeholder="{LANG.benefit}" value="{DATA.benefit}" name="benefit" class="form-control" />
					</td>
				</tr>
				<tr>
					<td>{LANG.mobile}</td>
					<td>
						<input type="text" placeholder="{LANG.mobile}" value="{DATA.mobile}" name="mobile" class="form-control" />
					</td>
				</tr>
				<tr>
					<td>{LANG.address}</td>
					<td>
						<input type="text" placeholder="{LANG.address}" value="{DATA.datatext.address}" name="address" class="form-control" />
					</td>
				</tr>
				<tr>
					<td>{LANG.cmnd}</td>
					<td>
						<input type="text" placeholder="{LANG.cmnd}" value="{DATA.datatext.cmnd}" name="cmnd" class="form-control" />
					</td>
				</tr>
				<tr>
					<td>{LANG.ngaycap}</td>
					<td>
						<input type="text" placeholder="{LANG.ngaycap}" value="{DATA.datatext.ngaycap}" name="ngaycap" class="form-control" />
					</td>
				</tr>
				<tr>
					<td>{LANG.noicap}</td>
					<td>
						<input type="text" placeholder="{LANG.noicap}" value="{DATA.datatext.noicap}" name="noicap" class="form-control" />
					</td>
				</tr>
				<tr>
					<td>{LANG.stknganhang}</td>
					<td>
						<input type="text" placeholder="{LANG.stknganhang}" value="{DATA.datatext.stknganhang}" name="stknganhang" class="form-control" />
					</td>
				</tr>
				<tr>
					<td>{LANG.tennganhang}</td>
					<td>
						<input type="text" placeholder="{LANG.tennganhang}" value="{DATA.datatext.tennganhang}" name="tennganhang" class="form-control" />
					</td>
				</tr>
				<tr>
					<td>{LANG.chinhanh}</td>
					<td>
						<input type="text" placeholder="{LANG.chinhanh}" value="{DATA.datatext.chinhanh}" name="chinhanh" class="form-control" />
					</td>
				</tr>
				</tbody>
			</table>
		</div>
		<br />
		<div class="text-center">
			<input class="btn btn-primary" name="submit1" type="submit" value="{LANG.save}" />
		</div>
	</form>
</div>

<script type="text/javascript">
    function nv_load_district( select_name, selected_id ){
        var province = $('select[name=provinceid]').val();
        if( province == 0 )$('#load_district').html('');
        else{
            $.post(nv_base_siteurl + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(), 'act=district&province=' + province + '&select_name=' + select_name + '&selected_id=' + selected_id, function(res) {
                $('#load_district').html(res)
            });
        }
    }
    <!-- BEGIN: load_district -->
    nv_load_district( 'districtid', '{DATA.districtid}' );
    <!-- END: load_district -->
    $("#parentid").select2();
    $("#group_cat-search").bind("keydown", function(event) {
        if (event.keyCode === $.ui.keyCode.TAB && $(this).data("ui-autocomplete").menu.active) {
            event.preventDefault();
        }
    }).autocomplete({
        source : function(request, response) {
            $.getJSON(script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=userajax", {
                term : extractLast(request.term)
            }, response);
        },
        search : function() {
            // custom minLength
            var term = extractLast(this.value);
            if (term.length < 2) {
                return false;
            }
        },
        select : function(event, data) {
            nv_add_element( data.item );
            $(this).val('');
            return false;
        }
    });
    function nv_add_element( data ){
        var html = "<span title=\"" + data.value + "\" class=\"uiToken removable\">" + data.fullname + "<input type=\"hidden\" value=\"" + data.key + "\" name=\"userid\" autocomplete=\"off\"><a onclick=\"$(this).parent().remove();\" href=\"javascript:void(0);\" class=\"remove uiCloseButton uiCloseButtonSmall\"></a></span>";
        $("#userid").html( html );
        $('#username').html(data.username);
        $('#birthday').html(data.birthday);
        $('#email').html(data.email);
        return false;
    }
    function split(val) {
        return val.split(/,\s*/);
    }

    function extractLast(term) {
        return split(term).pop();
    }
    <!-- BEGIN: data_cus_js -->
    $('#username').html('{DATA.username}');
    $('#birthday').html('{DATA.birthday}');
    $('#email').html('{DATA.email}');
    <!-- END: data_cus_js -->
</script>
<!-- END: content -->
<!-- END: main -->