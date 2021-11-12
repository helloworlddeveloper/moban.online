<!-- BEGIN: main -->
<form action="" method="post" class="form-horizontal">
    <div class="panel panel-default">
        <div class="panel-heading">{LANG.config_general}</div>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-4 text-right"><strong>{LANG.config_allow_declined}</strong></label>
                <div class="col-sm-20">
                    <label><input type="checkbox" name="allow_declined" value="1" {DATA.ck_allow_declined} />{LANG.config_allow_declined_note}</label>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 text-right"><strong>{LANG.config_allow_cronjobs}</strong></label>
                <div class="col-sm-20">
                    <label><input type="checkbox" name="allow_cronjobs" value="1" {DATA.ck_allow_cronjobs} />{LANG.config_allow_cronjobs_note}</label>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 text-right"><strong>{LANG.config_stoperror}</strong></label>
                <div class="col-sm-20">
                    <label><input type="checkbox" name="stoperror" value="1" {DATA.ck_stoperror} />{LANG.config_stoperror_note}</label>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label"><strong>{LANG.config_numsend}</strong></label>
                <div class="col-sm-20">
                    <input type="number" name="numsend" class="form-control" value="{DATA.numsend}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label"><strong>{LANG.config_new_customer_group}</strong></label>
                <div class="col-sm-20" style="border: 1px solid #ddd; padding: 10px; height: 200px; overflow: scroll;">
                    <!-- BEGIN: group -->
                    <label class="show"><input type="checkbox" name="new_customer_group[]" value="{GROUP.id}" {GROUP.checked} />{GROUP.title}</label>
                    <!-- END: group -->
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">{LANG.customer}</div>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-4 text-right"><strong>{LANG.config_requiredfullname}</strong></label>
                <div class="col-sm-20">
                    <label><input type="checkbox" name="requiredfullname" value="1" {DATA.ck_requiredfullname} />{LANG.config_requiredfullname_note}</label>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 text-right"><strong>{LANG.config_show_undefine}</strong></label>
                <div class="col-sm-20">
                    <label><input type="checkbox" name="show_undefine" value="1" {DATA.ck_show_undefine} />{LANG.config_show_undefine_note}</label>
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
            <!-- BEGIN: info_allow -->
            <div class="panel panel-default">
                <div class="panel-heading">{MODULE_TITLE}</div>
                <div class="panel-body">
                    <!-- BEGIN: loop -->
                    <div class="form-group">
                        <label class="col-sm-4 text-right"><strong>{INFO_ALLOW.keytitleactive}</strong></label>
                        <div class="col-sm-20">
                            <label><input type="checkbox" name="config[{module}][{keymodule}][active]" value="1" {INFO_ALLOW.ck} /></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><strong>{INFO_ALLOW.keytitlecolumn}</strong></label>
                        <div class="col-sm-20">
                            <textarea class="form-control" cols="75" rows="5" name="config[{module}][{keymodule}][reply]">{INFO_ALLOW.data_value}</textarea>
                        </div>
                    </div>
                    <!-- END: loop -->
                </div>
            </div>
            <!-- END: info_allow -->
        </div>
    </div>
    <div class="text-center">
        <input type="submit" class="btn btn-primary" value="{LANG.save}" name="savesetting" />
    </div>
</form>
<!-- BEGIN: main -->