<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">

<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript">
    var cat = '{LANG.cat}';
    var caton = '{LANG.caton}';
    var nv_lang_data = '{NV_LANG_DATA}';
</script>
<!-- BEGIN: table -->
<form class="navbar-form" method="post" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}&mid={DATA.mid}&parentid={DATA.parentid}">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <colgroup>
                <col class="w50">
                <col span="2">
                <col class="w150">
                <col class="w200">
            </colgroup>
            <thead>
            <tr>
                <th class="text-center"><input name="check_all[]" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" type="checkbox"></th>
                <th>{LANG.title_product}</th>
                <th>{LANG.link_product}</th>
                <th class="text-center">{LANG.display}</th>
                <th class="text-center">{LANG.action}</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <td colspan="7">
                    <select id="action" name="action" class="form-control">
                        <option value="delete">{LANG.delete}</option>
                    </select>
                    <input onclick="return nv_main_action(this.form, '{mod_name}', '{LANG.msgnocheck}')" name="submit" type="button" value="{LANG.action_form}" class="btn btn-primary w100" />
                </td>
            </tr>
            </tfoot>
            <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td class="text-center"><input type="checkbox" name="idcheck[]" value="{ROW.id}" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);"></td>
                <td>
                    <a href="{ROW.url_title}"><strong>{ROW.site_title} </strong></a>
                    <!-- BEGIN: sub -->
                    (<span class="requie">{ROW.sub} {LANG.sub_menu}</span>)
                    <!-- END: sub -->
                </td>
                <td><a href="{ROW.link}" target="_blank">{ROW.link}</a></td>
                <td class="text-center"> <input type="checkbox" id="change_active_{ROW.id}" onclick="nv_change_active({ROW.id})" {ROW.active} /> </td>
                <td class="text-center">
                    <!-- BEGIN: reload -->
                    <em class="fa fa-refresh fa-lg">&nbsp;</em> <a href="#" onclick="nv_menu_reload( '{mod_name}', '{LANG.action_menu_reload_confirm}' );" data-toggle="tooltip" data-placement="top" title="" data-original-title="{LANG.action_menu_reload_note}">{LANG.action_menu_reload}</a>&nbsp;
                    <!-- END: reload -->
                    <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_menu_item_delete({ROW.id},'{ROW.module_name}');">{LANG.delete}</a>
                </td>
            </tr>
            <!-- END: loop -->
            </tbody>
        </table>
    </div>
</form>
<!-- BEGIN: generate_page -->
<div>{GENERATE_PAGE}</div>
<!-- END: generate_page -->
<!-- END: table -->
{CAT_LIST}
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<form id="edit" action="{FORM_ACTION}" method="post">
    <input type="hidden" name="id" value="{DATA.id}">
    <input type="hidden" name="savecat" value="1">
    <input type="hidden" name="module_name" value="{mod_name}">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <caption><em class="fa fa-file-text-o">&nbsp;</em>{FORM_CAPTION}</caption>
            <colgroup>
                <col class="w150" />
                <col class="w300" />
            </colgroup>
            <tfoot>
            <tr>
                <td colspan="3" class="text-center"><input name="submit1" type="submit" value="{LANG.save}" class="btn btn-primary w100" /></td>
            </tr>
            </tfoot>
            <tbody>
            <tr>
                <td><strong>{LANG.title_product}</strong><sup class="required">(*)</sup></td>
                <td>
                    <div class="uiTokenizer uiInlineTokenizer"  style="float:left;">
                        <span id="userid" class="tokenarea">
                            <!-- BEGIN: data_title -->
                            <span class="uiToken removable" title="{DATA.title}">
                                {DATA.title}<input type="hidden" autocomplete="off" name="title" value="{DATA.title}" />
                                <a class="remove uiCloseButton uiCloseButtonSmall" href="javascript:void(0);" onclick="$(this).parent().remove();"></a>
                            </span>
                            <!-- END: data_title -->
                        </span>
                        <span class="uiTypeahead">
                            <input type="hidden" class="hiddenInput" autocomplete="off" value="" />
                            <div class="innerWrap" style="float:left;">
                                <input id="group_cat-search" type="text" placeholder="{LANG.input_title_product}" class="form-control textInput" style="width: 100%;" />
                            </div>
                        </span>
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>{LANG.link_product}</strong><sup class="required">(*)</sup></td>
                <td><input type="text" name="link" style="width: 100%;" class=" form-control" value="{DATA.link}" id="link_product"/></td>
            </tr>
            </tbody>
        </table>
    </div>
</form>

<script type="text/javascript">
    $("#group_cat-search").bind("keydown", function(event) {
        if (event.keyCode === $.ui.keyCode.TAB && $(this).data("ui-autocomplete").menu.active) {
            event.preventDefault();
        }
    }).autocomplete({
        source : function(request, response) {
            $.getJSON(script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=productajax&mod_name={mod_name}", {
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
        var html = "<span title=\"" + data.value + "\" class=\"uiToken removable\">" + data.value + "<input type=\"hidden\" value=\"" + data.value + "\" name=\"title\" autocomplete=\"off\"><a onclick=\"$(this).parent().remove();\" href=\"javascript:void(0);\" class=\"remove uiCloseButton uiCloseButtonSmall\"></a></span>";
        $("#userid").html( html );
        $('#link_product').val(data.link);
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