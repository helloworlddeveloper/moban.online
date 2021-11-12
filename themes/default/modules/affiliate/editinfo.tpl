<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<div id="edit">
    <!-- BEGIN: return -->
    <div class="alert alert-success">
        <strong>{RETURN}</strong>
    </div>
    <!-- END: return -->
    <!-- BEGIN: error -->
    <div class="alert alert-warning">
        {ERROR}
    </div>
    <!-- END: error -->
    <form action="{NV_BASE_SITEURL}index.php" method="post">
        <input type="hidden" name ="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
        <input type="hidden" name ="{NV_OP_VARIABLE}" value="{OP}" />
        <input type="hidden" name ="userid" value="{DATA.userid}" />
        <input name="savecat" type="hidden" value="1" />
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <caption>
                    <em class="fa fa-file-text-o">&nbsp;</em>{LANG.caption_refer}
                </caption>
                <tr>
                    <td style="width:150px;">{LANG.refer_name} (*)</td>
                    <td style="width:370px;">
                        <!-- BEGIN: edit_parent -->
                        <div class="uiTokenizer uiInlineTokenizer"  style="float:left;">
	                            <span id="parentid" class="tokenarea">
                                    <span class="uiToken removable" title="{DATA.fullname}">
                                        {DATA.fullname}<input type="hidden" autocomplete="off" name="parentid" value="{DATA.parentid}" />
                                        <a class="remove uiCloseButton uiCloseButtonSmall" href="javascript:void(0);" onclick="$(this).parent().remove();"></a>
                                    </span>
                                </span>
                            <span class="uiTypeahead">
                                    <input type="hidden" class="hiddenInput" autocomplete="off" value="" />
                                    <div class="innerWrap" style="float:left;">
                                        <input id="group_cat-search" type="text" placeholder="{LANG.input_customer}" class="form-control textInput" style="width: 100%;" />
                                    </div>
	                            </span>
                        </div>
                        <!-- END: edit_parent -->
                        <!-- BEGIN: not_edit_parent -->
                        {DATA.fullname}
                        <!-- END: not_edit_parent -->
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
            </table>
            <table class="table table-striped table-bordered table-hover">
                <caption>
                    <em class="fa fa-file-text-o">&nbsp;</em>{LANG.caption_account}
                </caption>
                <tr>
                    <td>{LANG.status}</td>
                    <td>
                        <label class="btn lable-{DATA.status}">{DATA.status_text}</label>
                    </td>
                </tr>
                <tr>
                    <td>{LANG.code}</td>
                    <td>
                        <strong>{DATA.code}</strong>
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
                        <input type="text" placeholder="{LANG.address}" value="{DATA.address}" name="address" class="form-control" />
                    </td>
                </tr>
                <tr>
                    <td>{LANG.cmnd}</td>
                    <td>
                        <input type="text" placeholder="{LANG.cmnd}" value="{DATA.cmnd}" name="cmnd" class="form-control" />
                    </td>
                </tr>
                <tr>
                    <td>{LANG.ngaycap}</td>
                    <td>
                        <input type="text" placeholder="{LANG.ngaycap}" value="{DATA.ngaycap}" name="ngaycap" class="form-control" />
                    </td>
                </tr>
                <tr>
                    <td>{LANG.noicap}</td>
                    <td>
                        <input type="text" placeholder="{LANG.noicap}" value="{DATA.noicap}" name="noicap" class="form-control" />
                    </td>
                </tr>
                <tr>
                    <td>{LANG.stknganhang}</td>
                    <td>
                        <input type="text" placeholder="{LANG.stknganhang}" value="{DATA.stknganhang}" name="stknganhang" class="form-control" />
                    </td>
                </tr>
                <tr>
                    <td>{LANG.tennganhang}</td>
                    <td>
                        <input type="text" placeholder="{LANG.tennganhang}" value="{DATA.tennganhang}" name="tennganhang" class="form-control" />
                    </td>
                </tr>
                <tr>
                    <td>{LANG.chinhanh}</td>
                    <td>
                        <input type="text" placeholder="{LANG.chinhanh}" value="{DATA.chinhanh}" name="chinhanh" class="form-control" />
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <br />
        <div class="text-center">
            <!-- BEGIN: data_send -->
            <input class="btn btn-primary" name="submit1" type="submit" value="{LANG.send_register}" />
            <!-- END: data_send -->
            <!-- BEGIN: data_update -->
            <input class="btn btn-primary" name="submit1" type="submit" value="{LANG.data_update}" />
            <!-- END: data_update -->
        </div>
    </form>
</div>

<script type="text/javascript">
    $("#group_cat-search").bind("keydown", function(event) {
        if (event.keyCode === $.ui.keyCode.TAB && $(this).data("ui-autocomplete").menu.active) {
            event.preventDefault();
        }
    }).autocomplete({
        source : function(request, response) {
            $.getJSON(nv_base_siteurl + "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=userajax", {
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
        var html = "<span title=\"" + data.value + "\" class=\"uiToken removable\">" + data.fullname + "<input type=\"hidden\" value=\"" + data.key + "\" name=\"parentid\" autocomplete=\"off\"><a onclick=\"$(this).parent().remove();\" href=\"javascript:void(0);\" class=\"remove uiCloseButton uiCloseButtonSmall\"></a></span>";
        $("#parentid").html( html );
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
    $('#username').html('{DATA.username}');
    $('#birthday').html('{DATA.birthday}');
    $('#email').html('{DATA.email}');
</script>
<!-- END: main -->