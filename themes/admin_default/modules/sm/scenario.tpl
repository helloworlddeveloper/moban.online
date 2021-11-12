<!-- BEGIN: main -->
<!-- BEGIN: view -->
<div class="well">
    <form action="{NV_BASE_ADMINURL}index.php" method="get">
        <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
        <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />

        <div class="row">
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <select class="form-control" name="productid" id="productid">
                        <option value="0"> -- {LANG.discount_product} -- </option>
                        <!-- BEGIN: sl_productid -->
                        <option value="{PRODUCT.id}" {PRODUCT.sl} >{PRODUCT.title}</option>
                        <!-- END: sl_productid -->
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-md-4">
                <div class="form-group">
                    <input class="btn btn-primary" type="submit" value="{LANG.search}" />&nbsp;
                </div>
            </div>
        </div>
        <input type="hidden" name="checkss" value="{NV_CHECK_SESSION}" />
        <label><em>{LANG.search_note}</em></label>
    </form>
</div>
<form class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>{LANG.discount_product}</th>
                <th>{LANG.description}</th>
                <th>{LANG.status}</th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <!-- BEGIN: generate_page -->
            <tfoot>
            <tr>
                <td colspan="6" class="text-center">{NV_GENERATE_PAGE}</td>
            </tr>
            </tfoot>
            <!-- END: generate_page -->
            <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td><a href="{VIEW.link_list_scenario}">{VIEW.product}</a></td>
                <td> {VIEW.note} </td>
                <td><input type="checkbox" name="status" id="change_status_{VIEW.id}" value="{VIEW.id}" {CHECK} onclick="nv_change_status_scenario({VIEW.id});" /></td>
                <td class="text-center"><i class="fa fa-edit fa-lg">&nbsp;</i><a href="{VIEW.link_edit}">{LANG.edit}</a> - <em class="fa fa-trash-o fa-lg">&nbsp;</em><a href="{VIEW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a></td>
            </tr>
            <!-- END: loop -->
            </tbody>
        </table>
    </div>
</form>
<script type="text/javascript">
    function nv_change_status_scenario(id) {
        var new_status = $('#change_status_' + id).is(':checked') ? true : false;
        if (confirm(nv_is_change_act_confirm[0])) {
            var nv_timer = nv_settimeout_disable('change_status_' + id, 5000);
            $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '={OP}&nocache=' + new Date().getTime(), 'change_status=1&id=' + id, function(res) {
                var r_split = res.split('_');
                if (r_split[0] != 'OK') {
                    alert(nv_is_change_act_confirm[2]);
                }else{
                    window.location.href = window.location.href;
                }
            });
        }
        else{
            $('#change_status_' + id).prop('checked', new_status ? false : true );
        }
        return;
    }
</script>
<!-- END: view -->
<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <input type="hidden" name="id" value="{ROW.id}" />
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <caption>{CAPTION}</caption>
            <thead>
            <tr>
                <th> {LANG.discount_product} </th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <select class="form-control" name="proid">
                        <option value="0">--{LANG.select_product}--</option>
                        <!-- BEGIN: productid -->
                        <option value="{PRODUCT.id}"{PRODUCT.sl}>{PRODUCT.title}</option>
                        <!-- END: productid -->
                    </select>
                </td>
            </tr>
            <tr>
                <td><textarea class="form-control" placeholder="{LANG.description}" name="note">{ROW.note}</textarea></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div style="text-align: center"><input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" />
    </div>
</form>
<!-- END: main -->