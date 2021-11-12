<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">
	{ERROR}
</div>
<!-- END: error -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">

<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>

<form class="form-inline m-bottom confirm-reload" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" enctype="multipart/form-data" method="post">
	<div class="row">
		<div class="col-sm-24 col-md-24">
			<table class="table table-striped table-bordered">
				<col class="w200" />
				<col />
				<tbody>
                    <tr>
                        <td><strong>{LANG.select_product}</strong>: <sup class="required">(*)</sup></td>
                        <td>
                            <div class="uiTokenizer uiInlineTokenizer"  style="float:left;">
	                            <span id="productid" class="tokenarea">
                                    <!-- BEGIN: data_productid -->
                                    <span class="uiToken removable" title="{PRODUCT.title}">
                                        {PRODUCT.title}<input type="hidden" autocomplete="off" name="productid[]" value="{PRODUCT.id}" />
                                        <a class="remove uiCloseButton uiCloseButtonSmall" href="javascript:void(0);" onclick="$(this).parent().remove();"></a>
                                    </span>
                                    <!-- END: data_productid -->
                                </span>
                                <span class="uiTypeahead">
                                    <input type="hidden" class="hiddenInput" autocomplete="off" value="" />
                                    <div class="innerWrap" style="float:left;">
                                        <input id="group_product_search" type="text" placeholder="{LANG.input_customer}" class="form-control textInput" style="width: 100%;" />
                                    </div>
	                            </span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>{LANG.select_user}</strong>: <sup class="required">(*)</sup></td>
                        <td>
                            <div class="uiTokenizer uiInlineTokenizer"  style="float:left;">
	                            <span id="userid" class="tokenarea">
                                    <!-- BEGIN: data_users -->
                                    <span class="uiToken removable" title="{USER.title}">
                                        {USER.title}<input type="hidden" autocomplete="off" name="userid" value="{USER.userid}" />
                                        <a class="remove uiCloseButton uiCloseButtonSmall" href="javascript:void(0);" onclick="$(this).parent().remove();"></a>
                                    </span>
                                    <!-- END: data_users -->
                                </span>
                                <span class="uiTypeahead">
                                    <input type="hidden" class="hiddenInput" autocomplete="off" value="" />
                                    <div class="innerWrap" style="float:left;">
                                        <input id="group_userid_search" type="text" placeholder="{LANG.input_customer}" class="form-control textInput" style="width: 100%;" />
                                    </div>
	                            </span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>{LANG.fullname}</strong>:</td>
                        <td><input type="text" maxlength="250" value="{DATA.fullname}" id="fullname" name="fullname" class="form-control"  style="width:350px"/></td>
                    </tr>
                    <tr>
                        <td><strong>{LANG.address}</strong>:</td>
                        <td><input type="text" maxlength="250" value="{DATA.address}" id="address" name="address" class="form-control"  style="width:350px"/></td>
                    </tr>
                    <tr>
                        <td><strong>{LANG.phone}</strong>:</td>
                        <td><input type="text" maxlength="250" value="{DATA.phone}" id="phone" name="phone" class="form-control"  style="width:350px"/></td>
                    </tr>
                    <tr>
                        <td><strong>{LANG.email}</strong>:</td>
                        <td><input type="text" maxlength="250" value="{DATA.email}" id="email" name="email" class="form-control"  style="width:350px"/></td>
                    </tr>
                    <tr>
                        <td><strong>{LANG.description}: </strong></td>
                        <td><textarea style="width: 100%" class="form-control" name="description">{DATA.description}</textarea></td>
                    </tr>
				</tbody>
			</table>
            <div class="text-center">
        		<input type="hidden" value="1" name="save" />
        		<input type="hidden" value="{DATA.id}" name="id" />
                <select class="form-control" name="status">
					<!-- BEGIN: status -->
					<option value="{STATUS.key}" {STATUS.sl}>{STATUS.title}</option>
					<!-- END: status -->
				</select>&nbsp;
                <input class="btn btn-primary submit-post" name="submit" type="submit" value="{LANG.save}" />
        		<br />
        	</div>
		</div>
	</div>
</form>

<script type="text/javascript">
    $("#group_product_search").bind("keydown", function(event) {
        if (event.keyCode === $.ui.keyCode.TAB && $(this).data("ui-autocomplete").menu.active) {
            event.preventDefault();
        }
    }).autocomplete({
        source : function(request, response) {
            $.getJSON(script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=productajax", {
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
            var html = "<span title=\"" + data.item.value + "\" class=\"uiToken removable\">" + data.item.value + "<input type=\"hidden\" value=\"" + data.item.key + "\" name=\"productid[]\" autocomplete=\"off\"><a onclick=\"$(this).parent().remove();\" href=\"javascript:void(0);\" class=\"remove uiCloseButton uiCloseButtonSmall\"></a></span>";
            $("#productid").append( html );

            $(this).val('');
            return false;
        }
    });
    $("#group_userid_search").bind("keydown", function(event) {
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
            var html = "<span title=\"" + data.item.value + "\" class=\"uiToken removable\">" + data.item.value + "<input type=\"hidden\" value=\"" + data.item.key + "\" name=\"userid\" autocomplete=\"off\"><a onclick=\"$(this).parent().remove();\" href=\"javascript:void(0);\" class=\"remove uiCloseButton uiCloseButtonSmall\"></a></span>";
            $("#userid").html( html );
            $(this).val('');
            return false;
        }
    });
    function split(val) {
        return val.split(/,\s*/);
    }

    function extractLast(term) {
        return split(term).pop();
    }
</script>
<!-- END:main -->