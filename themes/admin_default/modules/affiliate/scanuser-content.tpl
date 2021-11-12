<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<form class="form-horizontal" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <input type="hidden" name="id" value="{ROW.id}" />
    <input type="hidden" name="sid" value="{sid}" />
    <div class="row">
        <div class="col-xs-24 col-sm-19">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="form-group">
                        <label><strong>{LANG.content_sms}</strong></label>
                        <textarea class="form-control" style="width:100%; height:150px" name="content">{ROW.content}</textarea>
                        <span style="margin-top: 10px; display: block; font-weight: bold">{LANG.content_sms_note}</span>
                        <blockquote class="personal">
                            <div class="row">
                                <!-- BEGIN: personal -->
                                <div class="col-xs-24 col-sm-12">
                                    <label>{PERSONAL.index}</label> {PERSONAL.value}
                                </div>
                                <!-- END: personal -->
                            </div>
                        </blockquote>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-24 col-sm-5">
            <div class="panel panel-default form-inline">
                <div class="panel-heading">{LANG.hourscan} ({LANG.hour})</div>
                <div class="panel-body">
                    <div class="form-group">
                       <input class="form-control" type="text" name="hourscan" value="{ROW.hourscan}" />&nbsp;
                    </div>
                </div>
            </div>
            <div class="panel panel-default form-inline">
                <div class="panel-heading">{LANG.action_note}</div>
                <div class="panel-body">
                    <div class="form-group">
                        <select name="action" class="form-control">
                            <!-- BEGIN: action -->
                            <option value="{ACTION.key}"{ACTION.selected}>{ACTION.val}</option>
                            <!-- END: action -->
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group text-center">
        <input class="btn btn-primary loading" name="submit" type="submit" value="{LANG.campaign_add}" />
        <input class="btn btn-warning loading" name="draft" type="submit" value="{LANG.adddraft}" />
    </div>
</form>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript">

</script>
<!-- END: main -->