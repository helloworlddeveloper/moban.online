<!-- BEGIN: main -->
<div class="table-responsive">
    <div class="text-center clearfix"><a class="btn btn-primary" href="{agencycontent}">{LANG.add_agencycontent}</a><br></div>
    <table class="table table-striped table-bordered table-hover">
        <colgroup>
            <col class="w100">
            <col span="1">
            <col span="2" class="w150">
        </colgroup>
        <thead>
        <tr class="text-center">
            <th>{LANG.order}</th>
            <th>{LANG.title}</th>
            <th>{LANG.price_require}</th>
            <th>{LANG.sale_product}</th>
            <th>{LANG.edit_time}</th>
            <th>{LANG.status}</th>
            <th>{LANG.feature}</th>
        </tr>
        </thead>
        <tbody>
        <!-- BEGIN: row -->
        <tr>
            <td class="text-center">
                <select id="change_weight_{ROW.id}" onchange="nv_chang_weight('{ROW.id}');" class="form-control">
                    <!-- BEGIN: weight -->
                    <option value="{WEIGHT.w}"{WEIGHT.selected}>{WEIGHT.w}</option>
                    <!-- END: weight -->
                </select></td>
            <td><a href="{ROW.url_view}" title="{ROW.title}">{ROW.title}</a></td>
            <td>{ROW.price_require}</td>
            <td>{ROW.sale_product_show}</td>
            <td>{ROW.edit_time}</td>
            <td class="text-center">
                <select id="change_status_{ROW.id}" onchange="nv_chang_status('{ROW.id}');" class="form-control">
                    <!-- BEGIN: status -->
                    <option value="{STATUS.key}"{STATUS.selected}>{STATUS.val}</option>
                    <!-- END: status -->
                </select></td>
            <td class="text-center"><!-- BEGIN: copy_page --><a href={URL_COPY} title="{LANG.title_copy_page}"><em class="fa fa-copy fa-lg">&nbsp;</em></a><!-- END: copy_page --><em class="fa fa-edit fa-lg">&nbsp;</em><a href="{ROW.url_edit}">{GLANG.edit}</a> &nbsp; <em class="fa fa-trash-o fa-lg">&nbsp;</em><a href="javascript:void(0);" onclick="nv_module_del({ROW.id}, '{ROW.checkss}');">{GLANG.delete}</a></td>
        </tr>
        <!-- END: row -->
        </tbody>
    </table>
</div>
<!-- END: main -->