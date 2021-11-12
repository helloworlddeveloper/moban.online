<!-- BEGIN: main -->
<form class="navbar-form" name="block_list" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}">
    <!-- BEGIN: photo -->
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th class="text-center"><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" /></th>
                <th>{LANG.img}</th>
                <th>{LANG.album_date_added}</th>
                <th>{LANG.action}</th>
            </tr>
        </thead>
        <!-- BEGIN: loop -->
        <tr id="photo_{PHOTO.row_id}">
            <td class="text-center"><input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{PHOTO.row_id}" name="idcheck[]" /></td>
            <td class="col-md-2">
                <img src="{PHOTO.thumb}" width="40">
            </td>
            <td>{PHOTO.date_added}</td>
            <td class="text-center">
                <a class="btn btn-primary btn-xs btn_edit" href="javascript:void(0);" onclick="nv_show_image({PHOTO.row_id})"><em class="fa fa-edit margin-right"></em> {LANG.show_image}</a>
                <a class="btn btn-danger btn-xs" href="javascript:void(0);" onclick="nv_delete_image({PHOTO.row_id})"><em class="fa fa-trash-o margin-right"></em> {LANG.delete}</a>
            </td>
        </tr>
        <!-- END: loop -->
        <tfoot>
        <tr class="text-left">
            <td colspan="12">
                <select class="form-control" name="action" id="action">
                    <!-- BEGIN: action -->
                    <option value="{ACTION.value}">{ACTION.title}</option>
                    <!-- END: action -->
                </select>&nbsp;<input type="button" class="btn btn-primary" onclick="nv_main_action(this.form, '{NV_CHECK_SESSION}', '{LANG.msgnocheck}')" value="{LANG.action}" /></td>
        </tr>
        </tfoot>
    </table>
    <!-- END: photo -->
    <!-- BEGIN: generate_page -->
    <div class="text-center">
        {GENERATE_PAGE}
    </div>
    <!-- END: generate_page -->
</form>
<!-- END: main -->