<!-- BEGIN: main -->
<!-- BEGIN: nodata -->
<div class="alert alert-success">
    {LANG.no_users_pending}
</div>
<!-- END: nodata -->
<!-- BEGIN: data -->
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <col span="4" style="white-space: nowrap;" />
        <col class="w250" />
        <col style="white-space: nowrap;" />
        <thead>
        <tr>
            <th class="text-center">{LANG.fullname}</th>
            <th class="text-center">{LANG.birthday}</th>
            <th class="text-center">{LANG.email}</th>
            <th class="text-center">{LANG.mobile}</th>
            <th class="text-center">{LANG.address}</th>
            <th class="text-center">{LANG.functional}</th>
        </tr>
        </thead>
        <tbody>
        <!-- BEGIN: loop -->
        <tr>
            <td><strong>{ROW.fullname}</strong></td>
            <td class="text-center">{ROW.birthday}</td>
            <td class="text-center">{ROW.email}</td>
            <td class="text-center">{ROW.datatext.mobile}</td>
            <td class="text-center">{ROW.datatext.address}</td>
            <td class="text-center">
                <em class="fa fa-edit fa-lg">&nbsp;</em> <a href="{ROW.active_url}">{LANG.awaiting_active}</a> &nbsp;
                <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_waiting_row_del({CONTENT_TD.userid});">{LANG.delete}</a>
            </td>
        </tr>
        <!-- END: loop -->
        </tbody>
    </table>
</div>
<!-- END: data -->
<!-- END: main -->