<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.menu.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.autocomplete.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<!-- BEGIN: view -->
<script type="text/javascript">
    <!-- BEGIN: loaddistrict -->
    nv_get_district('{provinceid}', '{districtid}');
    <!-- END: loaddistrict -->
</script>
<form class="form-inline" action="{NV_BASE_ADMINURL}index.php" method="get">
	<input type="hidden" name="{NV_LANG_VARIABLE}"  value="{NV_LANG_DATA}" />
	<input type="hidden" name="{NV_NAME_VARIABLE}"  value="{MODULE_NAME}" />
	<input type="hidden" name="{NV_OP_VARIABLE}"  value="{OP}" />
    
    <table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
                <td>
                    <div class="uiTokenizer uiInlineTokenizer"  style="float:left;">
                        <span id="school_id" class="tokenarea">
                            <!-- BEGIN: school_name -->
                            <span class="uiToken removable" title="{school_name}">
                                {school_name}<input type="hidden" autocomplete="off" name="school_id" value="{school_id}" />
                                <a class="remove uiCloseButton uiCloseButtonSmall" href="javascript:void(0);" onclick="$(this).parent().remove();"></a>
                            </span>
                            <!-- END: school_name -->
                        </span>
                        <span class="uiTypeahead">
                            <input type="hidden" class="hiddenInput" autocomplete="off" value="" />
                            <div class="innerWrap" style="float: left; width: 300px;">
                                <input id="school_search" type="text" placeholder="{LANG.input_school_name}" class="form-control textInput" style="width: 100%;" />
                            </div>
                        </span>
                    </div>
                    &nbsp;&nbsp;
                    <input class="form-control" placeholder="{LANG.search_title}" style="width: 300px;" type="text" value="{Q}" name="q" maxlength="255" />&nbsp;
	                <input class="btn btn-primary" type="submit" value="{LANG.search_submit}" />
                    <!-- BEGIN: allow_link_add -->
                    &nbsp;<a href="{facebook_add_new}" class="btn btn-primary">{LANG.facebook_add_new}</a>
                    <!-- END: allow_link_add -->
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
                    <th>{LANG.school_title}</th>
					<th>{LANG.facebook_name}</th>
                    <th>{LANG.facebook_uid}</th>
					<th>{LANG.email}</th>
                    <th>{LANG.mobile}</th>
					<th>{LANG.addtime}</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="7">{NV_GENERATE_PAGE}</td>
				</tr>
			</tfoot>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
                    <td> {VIEW.school_name} </td>
					<td> {VIEW.facebook_name} </td>	
                    <td> {VIEW.facebook_uid} </td>
					<td> {VIEW.email} </td>
                    <td> {VIEW.mobile} </td>
					<td> {VIEW.addtime} </td>
                    <td class="text-center">
                        <!-- BEGIN: allow_edit -->
                        <i class="fa fa-edit fa-lg">&nbsp;</i> <a href="{VIEW.link_edit}">{LANG.edit}</a>
                        <!-- END: allow_edit -->
                        <!-- BEGIN: allow_del -->
                         - <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="{VIEW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a>
                        <!-- END: allow_del -->
                    </td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
</form>
<!-- END: view -->
<!-- BEGIN: allow_add -->
    <!-- BEGIN: add_row -->
    <!-- BEGIN: error -->
    <div class="alert alert-warning">{ERROR}</div>
    <!-- END: error -->    
    <form class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}&action={action}" method="post">
    	<input type="hidden" name="fbid" value="{ROW.fbid}" />
    	<div class="table-responsive">
    		<table class="table table-striped table-bordered table-hover">
    			<tbody>
    				<tr>
    					<td style="width:100px"> {LANG.facebook_name} <span class="red">(*)</span></td>
    					<td style="width:200px"><input style="width: 100%;" class="form-control" type="text" name="facebook_name" value="{ROW.facebook_name}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" /></td>
                        <td style="width:120px">{LANG.birthday}</td>
                        <td style="width:200px"><input style="width: 100%;" class="form-control" type="text" name="birthday" id="birthday" value="{ROW.birthday}" /></td>
    				</tr>
                    <tr>
    					<td style="width:100px"> {LANG.facebook_uid} <span class="red">(*)</span></td>
    					<td style="width:200px"><input style="width: 100%;" class="form-control" type="text" name="facebook_uid" value="{ROW.facebook_uid}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" /></td>
                        <td style="width:120px">{LANG.facebook_uname}</td>
                        <td style="width:200px"><input style="width: 100%;" class="form-control" type="text" name="facebook_uname" value="{ROW.facebook_uname}" /></td>
    				</tr>
    				<tr>
    					<td> {LANG.mobile} </td>
    					<td style="width:200px"><input style="width: 100%;" class="form-control" type="text" name="mobile" id="mobile" value="{ROW.mobile}" /></td>
                        <td>{LANG.sex}</td>
    					<td>
                            <!-- BEGIN: checkbox_sex -->
        					<input class="form-control" type="radio" name="sex" id="sex_{OPTION.key}" value="{OPTION.key}" {OPTION.checked} /><label for="sex_{OPTION.key}">{OPTION.title}</label> &nbsp; 
        					<!-- END: checkbox_sex -->
                        </td>
    				</tr>
                    <tr>
                        <td>{LANG.facebook_school_id}</td>
    					<td>
                            <div class="uiTokenizer uiInlineTokenizer"  style="float:left;">
                                <span id="school_id" class="tokenarea">
                                    <!-- BEGIN: school_name -->
                                    <span class="uiToken removable" title="{school_name}">
                                        {school_name}<input type="hidden" autocomplete="off" name="school_id" value="{ROW.school_id}" />
                                        <a class="remove uiCloseButton uiCloseButtonSmall" href="javascript:void(0);" onclick="$(this).parent().remove();"></a>
                                    </span>
                                    <!-- END: school_name -->
                                </span>
                                <span class="uiTypeahead">
                                    <input type="hidden" class="hiddenInput" autocomplete="off" value="" />
                                    <div class="innerWrap" style="float: left; width: 300px;">
                                        <input id="school_search" type="text" placeholder="{LANG.input_school_name}" class="form-control textInput" style="width: 100%;" />
                                    </div>
                                </span>
                            </div>
                        </td>
                        <td>{LANG.address}</td>
                        <td><input style="width: 100%;" class="form-control" type="text" name="address" value="{ROW.address}" /></td>
                    </tr>
                    <tr>
                        <td> {LANG.email} </td>
    					<td><input style="width: 100%;" class="form-control" type="text" name="email" id="email" value="{ROW.email}" /></td>
                        <td style="width:50%" colspan="2">
                            <input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" />
                        </td>
                    </tr>
    			</tbody>
    		</table>
    	</div>
    </form>
    <script type="text/javascript">
        $("#birthday").datepicker({
    		showOn : "focus",
    		dateFormat : "dd/mm/yy",
    		changeMonth : true,
    		changeYear : true,
    		showOtherMonths : true,
    		buttonImage : nv_siteroot + "images/calendar.gif",
    		buttonImageOnly : true
    	});
    </script>
    <!-- END: add_row -->
<!-- END: allow_add -->
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
    <script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
    <script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.menu.min.js"></script>
    <script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.autocomplete.min.js"></script>
    <script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
    <script type="text/javascript">
        $("#school_search").bind("keydown", function(event) {
        	if (event.keyCode === $.ui.keyCode.TAB && $(this).data("ui-autocomplete").menu.active) {
        		event.preventDefault();
        	}
        	}).autocomplete({
    		source : function(request, response) {
    			$.getJSON(script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "={OP}&search_school=1", {
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
                nv_add_element( 'school_id', data.item );
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
    </script>
<!-- END: main -->