<!-- BEGIN: main -->
<!-- BEGIN: view -->
<form class="form-inline" action="{NV_BASE_ADMINURL}index.php" method="get">
	<input type="hidden" name="{NV_LANG_VARIABLE}"  value="{NV_LANG_DATA}" />
	<input type="hidden" name="{NV_NAME_VARIABLE}"  value="{MODULE_NAME}" />
	<input type="hidden" name="{NV_OP_VARIABLE}"  value="{OP}" />
   
	<strong>{LANG.search_title}</strong>&nbsp;<input class="w300 form-control" type="text" value="{Q}" name="q" maxlength="255" />&nbsp;
	<input class="btn btn-primary" type="submit" value="{LANG.search}" />
    <a href="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}&add=1" class="btn btn-success">{LANG.add_customer}</a>
</form>
<br>

<form class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>{LANG.number}</th>
					<th>{LANG.customer_code}</th>
					<th>{LANG.customer_name}</th>
					<th>{LANG.address}</th>
					<th>{LANG.phone}</th>
					<th>{LANG.email}</th>
					<th>{LANG.custype}</th>
					<th>{LANG.edit_time}</th>
					<th>{LANG.status}</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="10">{NV_GENERATE_PAGE}</td>
				</tr>
			</tfoot>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td> {VIEW.number} </td>
					<td> {VIEW.code} </td>
					<td> {VIEW.fullname} </td>
					<td> {VIEW.address} </td>
					<td> {VIEW.phone} </td>
					<td> {VIEW.email} </td>
					<td> {VIEW.custype} </td>
					<td> {VIEW.edit_time} </td>
					<td> {VIEW.status} </td>
                    <td class="text-center">
					   <i class="fa fa-edit fa-lg">&nbsp;</i> <a href="{VIEW.link_edit}">{LANG.edit}</a> - <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="{VIEW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a>
                    </td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
</form>
<!-- END: view -->

<!-- BEGIN: add_customer -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">

<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<form class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<input type="hidden" name="customer_id" value="{ROW.customer_id}" />
    <div class="tab_container">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <caption>{LANG.customer_info}</caption>
    			<tbody>
					<tr>
						<td style="width:150px;">{LANG.refer_user}</td>
						<td>
							<div class="uiTokenizer uiInlineTokenizer"  style="float:left;">
									<span id="userid" class="tokenarea">
										<!-- BEGIN: data_users -->
										<span class="uiToken removable" title="{ROW.refer_title}">
											{ROW.refer_fullname}<input type="hidden" autocomplete="off" name="refer_userid" value="{ROW.refer_userid}" />
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
						<td> {LANG.customer_code} </td>
						<td><input class="w300 form-control" type="text" name="code" value="{ROW.code}" /></td>
					</tr>
    				<tr>
    					<td> {LANG.customer_name} </td>
    					<td><input class="w300 form-control" type="text" name="fullname" value="{ROW.fullname}" /></td>
    				</tr>
    				<tr>
    					<td> {LANG.address} </td>
    					<td><input class="w300 form-control" type="text" name="address" value="{ROW.address}" /></td>
    				</tr>
    				<tr>
    					<td> {LANG.phone} </td>
    					<td><input class="w300 form-control" type="text" name="phone" value="{ROW.phone}" /></td>
    				</tr>
    				<tr>
    					<td> {LANG.email} </td>
    					<td><input class="w300 form-control" type="text" name="email" value="{ROW.email}" /></td>
    				</tr>
					<tr>
						<td> {LANG.description} </td>
						<td><textarea class="w300 form-control" name="description">{ROW.description}</textarea></td>
					</tr>
					<tr>
						<td> {LANG.custype} </td>
						<td>
							<select name="custype" class="form-control">
								<!-- BEGIN: select_custype -->
								<option value="{OPTION.key}"{OPTION.selected}>{OPTION.title}</option>
								<!-- END: select_custype -->
							</select>
						</td>
					</tr>
					<tr>
						<td> {LANG.status} </td>
						<td>
							<select name="status" class="form-control">
								<!-- BEGIN: select_status -->
								<option value="{OPTION.key}"{OPTION.selected}>{OPTION.title}</option>
								<!-- END: select_status -->
							</select>
						</td>
					</tr>
    			</tbody>
    		</table>
        </div>
    </div>    
    <div style="text-align: center">
        <input type="hidden" name="cus_type_value" value="" />
        <input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" />
    </div>
    <div class="clear">&nbsp;</div> 
 </form>

<script type="text/javascript">
    $("#parentid").select2();
    $("#group_cat-search").bind("keydown", function(event) {
        if (event.keyCode === $.ui.keyCode.TAB && $(this).data("ui-autocomplete").menu.active) {
            event.preventDefault();
        }
    }).autocomplete({
        source : function(request, response) {
            $.getJSON(script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=customerajax&act=referuser", {
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
        var html = "<span title=\"" + data.value + "\" class=\"uiToken removable\">" + data.fullname + "<input type=\"hidden\" value=\"" + data.key + "\" name=\"refer_userid\" autocomplete=\"off\"><a onclick=\"$(this).parent().remove();\" href=\"javascript:void(0);\" class=\"remove uiCloseButton uiCloseButtonSmall\"></a></span>";
        $("#userid").html( html );
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
<!-- END: add_customer -->
<!-- END: main -->