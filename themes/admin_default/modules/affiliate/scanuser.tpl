<!-- BEGIN: main -->
<div class="well">
    <a class="btn btn-success"  href="{add_scenario_detail}">{LANG.add_scancontent}</a>
</div>
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>{LANG.content_sms}</th>
                <th class="w150 text-center">{LANG.hourscan}</th>
                <th class="w150">{LANG.action}</th>
                <th class="w150">{LANG.status}</th>
                <th class="w200">&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td>{VIEW.content}</td>
                <td class="text-center">{VIEW.hourscan} {LANG.hour}</td>
                <td>{VIEW.action}</td>
                <td>{VIEW.status}</td>
                <td class="text-center">
                    <i class="fa fa-edit fa-lg">&nbsp;</i> <a href="{VIEW.link_edit}">{LANG.edit}</a> -
                    <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="{VIEW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a>
                </td>
            </tr>
            <!-- END: loop -->
            </tbody>
        </table>
    </div>
</form>
<!-- BEGIN: generate_page -->
<div class="pull-right">{NV_GENERATE_PAGE}</div>
<!-- END: generate_page -->
<!-- END: main -->