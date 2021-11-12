<!-- BEGIN: main -->
<!-- BEGIN: view -->
<form class="form-inline" action="{NV_BASE_ADMINURL}index.php" method="get">
	<input type="hidden" name="{NV_LANG_VARIABLE}"  value="{NV_LANG_DATA}" />
	<input type="hidden" name="{NV_NAME_VARIABLE}"  value="{MODULE_NAME}" />
	<input type="hidden" name="{NV_OP_VARIABLE}"  value="{OP}" />
    <table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
                <td>
                    <select style="width: 100%;" class="form-control" name="status">
    					<option value="-1">{LANG.status_search}</option>
                        <!-- BEGIN: status_select -->
    					<option value="{STATUS.key}" {STATUS.sl}>{STATUS.title}</option>
    					<!-- END: status_select -->
    			    </select>
                </td>
                <td>
                    <select style="width: 100%;" class="form-control" name="module_search">
    					<option value="0">{LANG.module_search}</option>
                        <!-- BEGIN: module_search -->
    					<option value="{MODULE.key}" {MODULE.sl}>{MODULE.title}</option>
    					<!-- END: module_search -->
				    </select>
                </td>
                <td>
                    <input class="form-control" placeholder="{LANG.search_title}" style="width: 300px;" type="text" value="{Q}" name="q" maxlength="255" />&nbsp;
	                <input class="btn btn-primary" type="submit" value="{LANG.search_submit}" />
                    &nbsp;<a href="{addschool}" class="btn btn-primary">{LANG.addpopup}</a>
                </td>
            </tr>
        </tbody>
    </table>        
</form>
<br />
<form class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
                    <th>{LANG.bymodule_title}</th>
                    <th>{LANG.bymodule_module_name}</th>
					<th>{LANG.bymodule_op_name}</th>
                    <th>{LANG.numview}</th>
					<th>{LANG.numclick}</th>
                    <th>{LANG.numdownload}</th>
					<th>{LANG.edit_time}</th>
					<th>{LANG.bymodule_status}</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="9">{NV_GENERATE_PAGE}</td>
				</tr>
			</tfoot>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
                    <td> {VIEW.title} </td>
                    <td> {VIEW.module_name} </td>
					<td> {VIEW.op_name} </td>	
                    <td> {VIEW.numview} </td>
					<td> {VIEW.numclick} </td>
                    <td> {VIEW.numdownload} </td>
					<td> {VIEW.edit_time} </td>
					<td> {VIEW.status} </td>
                    <td class="text-center">
                        <i class="fa fa-edit fa-lg">&nbsp;</i> <a href="{VIEW.link_edit}">{LANG.edit}</a>
                         - <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="{VIEW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a>
                    </td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
</form>
<!-- END: view -->
<!-- BEGIN: add_row -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<form class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}&action={action}" method="post">
	<input type="hidden" name="id" value="{ROW.id}" />
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<tbody>
				<tr>
					<td style="width:100px"> {LANG.bymodule_title} <span class="red">(*)</span></td>
					<td style="width:200px"><input style="width: 100%;" class="form-control" type="text" name="title" id="bymodule_title" value="{ROW.title}" /></td>
				</tr>
				<tr>
					<td> {LANG.alias} </td>
					<td>
                        <input style="width: 100%;" class="form-control" type="text" id="id_alias" name="alias" value="{ROW.alias}" />
                    </td>
				</tr>
                <tr>
					<td> {LANG.bymodule_image} </td>
					<td>
                        <input style="width: 50%;" class="form-control" type="text" name="image" id="image" value="{ROW.image}" />
                        &nbsp;<input type="button" value="Browse server" name="selectimg" class="btn btn-info" />
                    </td>
				</tr>
                <tr>
                    <td> {LANG.showtype} </td>
                    <td>
                        <select name="showtype" class="form-control">
                            <option value="">-----------</option>
                            <!-- BEGIN: showtype -->
                            <option value="{SHOWTYPE.key}"{SHOWTYPE.sl}>{SHOWTYPE.title}</option>
                            <!-- END: showtype -->
                        </select>
                    </td>
                </tr>
                <tr id="show_type">
					<td id="title_lang_change"> {LANG.link_download} </td>
					<td>
                        <input style="width: 50%;" class="form-control" type="text" name="link_download" id="link_download" value="{ROW.link_download}" />
                        &nbsp;<input type="button" value="Browse server" name="selectlink_download" class="btn btn-info" />
                    </td>
				</tr>
                <tr>
					<td> {LANG.bymodule_module_name} </td>
					<td>
                        <select name="module_name" class="form-control">
                            <option value="">-----------</option>
                            <!-- BEGIN: mod_info -->
                            <option value="{MODULE_INFO.module_name}"{MODULE_INFO.sl}>{MODULE_INFO.module_tilte}</option>
                            <!-- END: mod_info -->
                        </select>
                    </td>
				</tr>
                <tr>
					<td> {LANG.bymodule_op_name} </td>
					<td>
                        <select id="op_name" name="op_name" class="form-control">
                            <option>-----------</option>                                
                        </select>
                    </td>
				</tr>
                <tr>
					<td> {LANG.bytable_mysql} </td>
					<td>
                        <select name="table_mysql" id="table_mysql" class="form-control">
                            <option>-----------</option> 
                        </select>
                    </td>
				</tr>
                <tr>
					<td> {LANG.bymodule_itemid} </td>
					<td>
                        <div class="uiTokenizer uiInlineTokenizer"  style="float:left;">
                            <span id="itemid" class="tokenarea">
                                <!-- BEGIN: search_ajax -->
                                <span class="uiToken removable" title="{SEARCH.title}">
                                    {SEARCH.title}<input type="hidden" autocomplete="off" name="itemid" value="{SEARCH.itemid}" />
                                    <a class="remove uiCloseButton uiCloseButtonSmall" href="javascript:void(0);" onclick="$(this).parent().remove();"></a>
                                </span>
                                <!-- END: search_ajax -->
                            </span>
                            <span class="uiTypeahead">
                                <input type="hidden" class="hiddenInput" autocomplete="off" value="" />
                                <div class="innerWrap" style="float: left; width: 300px;">
                                    <input id="itemid_search" type="text" placeholder="{LANG.input_facebook_student}" class="form-control textInput" style="width: 100%;" />
                                </div>
                            </span>
                        </div>
                    </td>
				</tr>
                <tr>
					<td> {LANG.popup_on} </td>
					<td>
                        <select name="popup_on" class="form-control">
                            <!-- BEGIN: popup_on -->
                            <option value="{POPUP_ON.key}"{POPUP_ON.sl}>{POPUP_ON.title}</option>
                            <!-- END: popup_on -->
                        </select>
                    </td>
				</tr>
                <tr>
                    <td> {LANG.action_popup} </td>
					<td>
                        <input class="form-control" type="text" name="action_popup" id="action_popup" value="{ROW.action_popup}" />
                    </td>
                </tr>
                <tr>
					<td colspan="2"> {LANG.bymodule_description} </td>
				</tr>
                <tr>
                    <td colspan="2">
                        {description}
                    </td>
                </tr>
                <tr>
                    <td style="width:50%" colspan="2">
                        <input class="btn btn-primary" name="save_only" type="submit" value="{LANG.save}" />
                    </td>
                </tr>
			</tbody>
		</table>
	</div>
</form>

<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<script type="text/javascript">
    $('select[name=showtype]').change(function () {
        change_show_type($(this).val());
    })
    change_show_type('{ROW.showtype}');
    function  change_show_type(valuechange) {
        if( valuechange == 0 ){
            $('#show_type').show();
            $('input[name=selectlink_download]').hide();
            $('#title_lang_change').html('{LANG.showtype_lang_0}');
        }
        else if( valuechange == 2 ){
            $('#show_type').show();
            $('input[name=selectlink_download]').show();
            $('#title_lang_change').html('{LANG.showtype_lang_2}');
        }else{
            $('#show_type').hide();
        }
    }
    $("#itemid_search").bind("keydown", function(event) {
    	if (event.keyCode === $.ui.keyCode.TAB && $(this).data("ui-autocomplete").menu.active) {
    		event.preventDefault();
    	}
    	}).autocomplete({
		source : function(request, response) {
			$.getJSON(script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "={OP}&search_itemid=1&table_mysql=" +  $("select[name=table_mysql]").val() + '&module_name=' + $("select[name=module_name]").val(), {
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
            nv_add_element( 'itemid', data.item );
            $(this).val('');
            return false;
		}
	});
    function nv_add_element( id_add, data ){
       var html = "<span title=\"" + data.value + "\" class=\"uiToken removable\">" + data.value + "<input type=\"hidden\" value=\"" + data.key + "\" name=\""+id_add+"\" autocomplete=\"off\"><a onclick=\"$(this).parent().remove();\" href=\"javascript:void(0);\" class=\"remove uiCloseButton uiCloseButtonSmall\"></a></span>";
        $("#" + id_add).html( html );
    	return false;
    }
    function split(val) {
    	return val.split(/,\s*/);
    }
    
    function extractLast(term) {
    	return split(term).pop();
    }
    $("input[name=selectimg]").click(function() {
    	var area = "image";
    	var alt = "";
    	var path = "{UPLOADS_DIR}";
    	var currentpath = "{UPLOADS_DIR}";
    	var type = "image";
    	nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&alt=" + alt + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
    	return false;
    });
    $("input[name=selectlink_download]").click(function() {
    	var area = "link_download";
    	var alt = "";
    	var path = "{UPLOADS_DIR}";
    	var currentpath = "{UPLOADS_DIR}";
    	var type = "file";
    	nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&alt=" + alt + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
    	return false;
    });
    $("select[name=module_name]").change(function() {
    	var module_name = $(this).val();
        load_data_show( module_name );
    });
    function load_data_funtion( module_name, function_op ){
        $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '={OP}&nocache=' + new Date().getTime(), 'loadfuntion=1&module_name=' + module_name + '&function_op=' +function_op, function(res) {
			$("#op_name").html( res );
		});
    }
    function load_data_database( module_name, table ){
        $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '={OP}&nocache=' + new Date().getTime(), 'loaddatabase=1&module_name=' + module_name + '&table=' +table, function(res) {
			$("#table_mysql").html( res );
		});
    }
    function load_data_show( module_name ){
        var function_op = '{ROW.op_name}';
        var table = '{ROW.table_mysql}';
        load_data_funtion( module_name, function_op );
        load_data_database( module_name, table );
    }
    
    //<![CDATA[
	function nv_get_alias(id) {
		var title = strip_tags( $("[name='bymodule_title']").val() );
		if (title != '') {
			$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '={OP}&nocache=' + new Date().getTime(), 'get_alias_title=' + encodeURIComponent(title), function(res) {
				$("#"+id).val( strip_tags( res ) );
			});
		}
		return false;
	}
    <!-- BEGIN: load_data_show -->
    	load_data_show( '{mod_name}' );
    <!-- END: load_data_show -->
    
    <!-- BEGIN: auto_get_alias -->
	$("[name='bymodule_title']").change(function() {
		nv_get_alias('id_alias');
	});
    <!-- END: auto_get_alias -->
    //]]>
</script>
<!-- END: add_row -->
<!-- END: main -->