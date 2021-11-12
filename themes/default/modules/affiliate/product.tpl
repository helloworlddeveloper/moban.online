<!-- BEGIN: main -->
<!-- BEGIN: nocontent -->
<div class="alert alert-danger">
    {LANG.no_product}
</div>
<!-- END: nocontent -->

<!-- BEGIN: content -->
<form action="{FORM_ACTION}" method="get">
    <input name="{NV_NAME_VARIABLE}" type="hidden" value="{MODULE_NAME}" />
    <input name="{NV_OP_VARIABLE}" type="hidden" value="{OP}" />
    <div class="row">
        <div class="col-xs-12 col-md-12">
            <div class="form-group">
                <input class="form-control" type="text" name="keyword" value="{SEARCH.keyword}" id="f_value" placeholder="{LANG.search_product_title}" />
            </div>
        </div>
        <div class="col-xs-12 col-md-6">
            <div class="form-group">
                <select class="form-control" name="status" id="status">
                    <option value="">---{LANG.search_status}---</option>
                    <!-- BEGIN: search_status -->
                    <option value="{STATUS.key}"{STATUS.selected}>{STATUS.value}</option>
                    <!-- END: search_status -->
                </select>
            </div>
        </div>
        <div class="col-xs-12 col-md-6">
            <div class="form-group">
                <input class="btn btn-primary" name="search" type="submit" value="{LANG.submit}" />&nbsp;
            </div>
        </div>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
            <th>{LANG.title_product}</th>
            <th>{LANG.link_product}</th>
            <th class="text-center">{LANG.action}</th>
        </tr>
        </thead>
        <tbody>
        <!-- BEGIN: loop -->
        <tr>
            <td>
                <a target="_blank" href="{ROW.link}"><strong>{ROW.title}</strong></a>
            </td>
            <td>
                <!-- BEGIN: allow_share -->
                <div class="input-group input-group-sm">
                    <input data-toggle="tooltip" data-placement="top" title="{LANG.copyed_url}" data-trigger="manual" class="form-control link_refer" value="{ROW.link_share}" id="link_{ROW.id}" type="text">
                    <span class="input-group-btn">
                        <button class="btn btn-default" onclick="copyToClipboard('link_{ROW.id}')"><i class="fa fa-copy"></i></button>
                    </span>
                </div>
                <!-- END: allow_share -->
                <!-- BEGIN: noallow_share -->
                <strong>{LANG.no_share_product}</strong>
                <!-- END: noallow_share -->
            </td>
            <td class="text-center">
                <!-- BEGIN: status_product -->
                <strong><i class="fa fa-check" aria-hidden="true"></i> {ROW.status_product}</strong>
                <!-- END: status_product -->
                <span class="itemproduct">
                    <!-- BEGIN: register -->
                    <button data-id="{ROW.id}" class="btn btn-success register_product_affiliate"><i class="fa fa-plus-circle" aria-hidden="true"></i> &nbsp;{LANG.register}</button>
                    <!-- END: register -->
                </span>
            </td>
        </tr>
        <!-- END: loop -->
        </tbody>
    </table>
</div>
<!-- BEGIN: generate_page -->
<div class="text-center">{GENERATE_PAGE}</div>
<!-- END: generate_page -->

<script type="text/javascript">
    $('.link_refer').focus(function() {
        $(this).select();
    });
    $('.register_product_affiliate').click(function() {
        var obj = $(this);
        $.post(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=product&nocache=' + new Date().getTime(), 'register=1&id=' + obj.attr('data-id'), function(res) {
            res = res.split('_');
            if (res[0] == 'OK') {
                $('#item_' + obj.attr('data-id')).html('<strong><i class="fa fa-check" aria-hidden="true"></i> ' + res[1] + '</strong>');
                obj.parent('.itemproduct').html('');
            } else {
                alert(res[1]);
            }
        });
        return false;
    })

</script>
<!-- END: content -->
<!-- END: main -->