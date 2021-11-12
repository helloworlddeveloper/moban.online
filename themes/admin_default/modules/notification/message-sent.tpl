<!-- BEGIN: main -->
<div class="well">
    <form action="{NV_BASE_ADMINURL}index.php" method="get">
        <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
        <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />

        <div class="row">
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <input type="text" name="q" value="{q}" class="form-control" placeholder="{LANG.search_note}">
                </div>
            </div>
            <div class="col-xs-12 col-md-4">
                <div class="form-group">
                    <input class="btn btn-primary" type="submit" value="{LANG.search}" />&nbsp;
                </div>
            </div>
        </div>
        <input type="hidden" name="checkss" value="{NV_CHECK_SESSION}" />
    </form>
</div>
<form class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>{LANG.content}</th>
                <th>{LANG.receiver}</th>
                <th>{LANG.timesend}</th>
                <th>{LANG.timesent}</th>
                <th>{LANG.status}</th>
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
            <tr id="item{VIEW.stt}" data_item="{VIEW.stt}" data_id="{VIEW.id}" data_smsid="{VIEW.smsid}" data_status="{VIEW.status}">
                <td> {VIEW.content} </td>
                <td> {VIEW.receiver} </td>
                <td> {VIEW.timesend} </td>
                <td> {VIEW.timesent} </td>
                <td> <span class="status">&nbsp;{VIEW.status_text} </span></td>
            </tr>
            <!-- END: loop -->
            </tbody>
        </table>
    </div>
</form>
<!-- END: main -->