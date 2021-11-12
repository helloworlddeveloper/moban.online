<!-- BEGIN: main -->
<form action="" method="post" class="form-horizontal">
    <div class="panel panel-default">
    <div class="panel panel-default">
        <div class="panel-heading">{LANG.custom_config}</div>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-4 text-right"><strong>{LANG.email_notify}</strong></label>
                <div class="col-sm-20">
                    <label><input class="form-control" type="text" name="email_notify" value="{DATA.email_notify}" /><i>{LANG.email_notify_note}</i></label>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">{LANG.config_sms}</div>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-4 text-right"><strong>{LANG.config_sms_on}</strong></label>
                <div class="col-sm-20">
                    <input type="checkbox" value="1" name="sms_on" {SMS_ON}/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label"><strong>{LANG.apikey}</strong></label>
                <div class="col-sm-20">
                    <input type="text" class="form-control" value="{DATA.apikey}" name="apikey" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label"><strong>{LANG.secretkey}</strong></label>
                <div class="col-sm-20">
                    <input type="text" class="form-control" value="{DATA.secretkey}" name="secretkey" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label"><strong>{LANG.config_sms_type}</strong></label>
                <div class="col-sm-20">
                    <select class="form-control" name="sms_type">
                        <!-- BEGIN: sms_type -->
                        <option value="{SMS_TYPE.key}"{SMS_TYPE.selected}>{SMS_TYPE.title}</option>
                        <!-- END: sms_type -->
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label"><strong>{LANG.brandname}</strong></label>
                <div class="col-sm-20">
                    <input type="text" class="form-control" value="{DATA.brandname}" name="brandname" />
                </div>
            </div>
        </div>
    </div>
    <div class="text-center">
        <input type="submit" class="btn btn-primary" value="{LANG.submit}" name="savesetting" />
    </div>
</form>
<!-- BEGIN: main -->