<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<form class="form-horizontal" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <input type="hidden" name="id" value="{ROW.id}" />
    <input type="hidden" name="eventid" value="{eventid}" />
    <div class="row">
        <div class="col-xs-24 col-sm-14">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="form-group">
                        <label><strong>{LANG.content}</strong></label>
                        <textarea class="form-control" style="width:100%; height:150px" name="content">{ROW.content}</textarea>
                    </div>
                </div>
                <div class="form-group text-center">
                    <input class="btn btn-primary loading" name="submit" type="submit" value="{LANG.campaign_add}" />
                    <input class="btn btn-warning loading" name="draft" type="submit" value="{LANG.adddraft}" />
                </div>
            </div>
        </div>
        <div class="col-xs-24 col-sm-10">
            <div class="panel panel-default form-inline">
                <div class="panel-heading">{LANG.content_note}</div>
                <div class="panel-body">
                    <div class="form-group">
                        <!-- BEGIN: personal -->
                        <div class="col-xs-24 col-sm-12">
                            <label>{PERSONAL.index}</label> {PERSONAL.value}
                        </div>
                        <!-- END: personal -->
                    </div>
                </div>
                <div class="panel-heading">{LANG.timesend}</div>
                <div class="panel-body">
                    <div class="form-group">
                        {LANG.hoursend}&nbsp;<input class="form-control w150" type="text" name="hoursend" value="{ROW.hoursend}" />&nbsp;{LANG.hour}
                    </div>
                </div>

            </div>
        </div>
    </div>
</form>
<!-- END: main -->