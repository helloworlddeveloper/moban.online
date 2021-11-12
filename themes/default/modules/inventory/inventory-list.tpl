<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_DATA}.js"></script>
<div class="well">
    <form action="{NV_BASE_ADMINURL}index.php" method="get">
        <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
        <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
        <div class="row">
            <div class="col-xs-12 col-md-8">
                <div class="form-group">
                    <select class="form-control" name="departmentid">
                        <option value="0"> -- {LANG.search_department} -- </option>
                        <!-- BEGIN: cat_content -->
                        <option value="{CAT_CONTENT.id}" {CAT_CONTENT.selected} >{CAT_CONTENT.title}</option>
                        <!-- END: cat_content -->
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <input class="btn btn-primary" type="submit" value="{LANG.search}" />&nbsp;
                    <a class="btn btn-primary" href="{addinventory}">{LANG.addinventory}</a>
                </div>
            </div>
        </div>
        <input type="hidden" name="checkss" value="{NV_CHECK_SESSION}" />
    </form>
</div>

<form name="block_list" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th class="text-center">{LANG.stt}</th>
                <th class="text-center">{LANG.department_name_inventory}</th>
                <th class="text-center">{LANG.thoigiankiemke}</th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td class="text-center">{ROW.stt}</td>
                <td><a href="{ROW.link_detail}">{ROW.department}</a></td>
                <td class="text-center">{ROW.time_inventory}</td>
                <td class="text-center">
                   <a href="{ROW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a>
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
<script type="text/javascript">
    $(document).ready(function() {
        $("#catid").select2({
            language : '{NV_LANG_DATA}'
        });
    });
</script>
<!-- END: main -->