<!-- BEGIN: main -->
<div class="well">
    <form action="{NV_BASE_ADMINURL}index.php" method="get">
        <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
        <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />

        <div class="row">
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <input class="form-control" type="text" value="{Q}" maxlength="64" name="q" placeholder="{LANG.search_key}" />
                </div>
            </div>
            <div class="col-xs-12 col-md-2">
                <div class="form-group">
                    <select class="form-control" name="per_page">
                        <option value="">{LANG.search_per_page}</option>
                        <!-- BEGIN: s_per_page -->
                        <option value="{SEARCH_PER_PAGE.page}" {SEARCH_PER_PAGE.selected}>{SEARCH_PER_PAGE.page}</option>
                        <!-- END: s_per_page -->
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <input class="btn btn-primary" type="submit" value="{LANG.search}" />&nbsp;
                    <a class="btn btn-primary" href="{addtag}">{LANG.addtag}</a>
                </div>
            </div>
        </div>
        <input type="hidden" name="checkss" value="{NV_CHECK_SESSION}" />
    </form>
</div>

<form class="navbar-form" name="block_list" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th class="text-center">{LANG.tenkyhieu}</th>
                <th class="text-center">{LANG.bophanquanly}</th>
                <th class="text-center">{LANG.bienbangiaonhan_title}</th>
                <th class="text-center">{LANG.namduavaosudung}</th>
                <th class="text-center">{LANG.congxuat}</th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            <!-- BEGIN: loop -->
            <tr class="{ROW.class}">
                <td>{ROW.tenkyhieu} &nbsp;
                    <span id="export_excel_{ROW.id}"><a href="javascript:void (0);" data_id="{ROW.id}" class="export_excel"><i class="fa fa-file-excel-o" aria-hidden="true"></i></a></span>
                </td>
                <td>{ROW.bophanquanly}</td>
                <td>{ROW.bienbangiaonhan}</td>
                <td class="text-center">{ROW.namsudung}</td>
                <td>{ROW.congsuat}</td>
                <td class="text-center">
                    <i class="fa fa-edit fa-lg">&nbsp;</i><a href="{ROW.link_edit}">{LANG.edit}</a> - <em class="fa fa-trash-o fa-lg">&nbsp;</em><a href="{ROW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a>
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
    $(document).ready(function(){
        $('.export_excel').click(function(){
            $('#export_excel_' + $(this).attr('data_id')).hide();
            $('#loading').show();
            $.ajax({
                type: 'post',
                url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=tag-product',
                data: 'export&step=1&id=' + $(this).attr('data_id'),
                dataType: "json",
                success: function(b) {
                    if(b.status == 'OK'){
                        window.location.href = nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=tag-product&export&step=2';
                    }
                    else{
                        alert(b.mess);
                    }
                    $('#export_excel_' + $(this).attr('data_id')).show();
                }
            });
        });
    });
</script>
<!-- END: main -->