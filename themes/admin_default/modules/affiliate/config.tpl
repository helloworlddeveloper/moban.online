<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <div class="panel panel-default">
        <div class="panel-heading">{LANG.config_system}</div>
        <div class="panel-body">
            <div class="form-group clearfix">
                <label class="col-sm-4 control-label">
                    <strong>{LANG.config_verify_user}</strong>
                </label>
                <div class="col-sm-20">
                    <input type="checkbox" name="verify_user" {DATA_CONFIG.verify_user} value="1" class="form-control" />
                </div>
            </div>
            <div class="form-group clearfix">
                <label class="col-sm-4 control-label">
                    <strong>{LANG.sms_register}</strong>
                </label>
                <div class="col-sm-20">
                    <input type="checkbox" name="sms_register" {DATA_CONFIG.sms_register} value="1" class="form-control" />
                </div>
            </div>
            <div class="form-group clearfix">
                <label class="col-sm-4 control-label">
                    <strong>{LANG.config_per_page}</strong>
                </label>
                <div class="col-sm-20">
                    <input type="text" name="per_page" value="{DATA_CONFIG.per_page}" class="form-control" />
                </div>
            </div>
            <div class="form-group clearfix">
                <label class="col-sm-4 control-label">
                    <strong>{LANG.config_precode}</strong>
                </label>
                <div class="col-sm-20">
                    <input type="text" name="precode" value="{DATA_CONFIG.precode}" class="form-control" />
                </div>
            </div>
            <div class="form-group clearfix">
                <label class="col-sm-4 control-label">
                    <strong>{LANG.setting_export_headerfile}</strong>
                </label>
                <div class="col-sm-20">
                    <input class="form-control" style="width: 80%;float:left" type="text" name="headerfile" id="headerfile" value="{DATA_CONFIG.headerfile}"/>
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button" id="selectheaderfile">
                            <em class="fa fa-folder-open-o fa-fix">&nbsp;</em>
                        </button>
                    </span>
                </div>
            </div>
            <div class="form-group clearfix">
                <label class="col-sm-4 control-label">
                    <strong>{LANG.setting_export_companyname}</strong>
                </label>
                <div class="col-sm-20">
                    <input type="text" name="companyname" value="{DATA_CONFIG.companyname}" class="form-control" />
                </div>
            </div>
            <div class="form-group clearfix">
                <label class="col-sm-4 control-label">
                    <strong>{LANG.setting_export_address}</strong>
                </label>
                <div class="col-sm-20">
                    <input type="text" name="address" value="{DATA_CONFIG.address}" class="form-control" />
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">{LANG.config_scan_user}</div>
        <div class="panel-body">
            <div class="form-group clearfix">
                <label class="col-sm-4 control-label">
                    <strong>{LANG.scan_user}</strong>
                </label>
                <div class="col-sm-20">
                    <input type="checkbox" name="scan_user" {DATA_CONFIG.scan_user} value="1" class="form-control" />
                </div>
            </div>
            <div class="form-group clearfix">
                <label class="col-sm-4 control-label">
                    <strong>{LANG.inactive_or_delete}</strong>
                </label>
                <div class="col-sm-20">

                    <select name="inactive_or_delete" class="form-control">
                        <!-- BEGIN:inactive_or_delete -->
                        <option value="{inactive_or_delete.key}"{inactive_or_delete.sl}>{inactive_or_delete.value}</option>
                        <!-- END:inactive_or_delete -->
                    </select>
                </div>
            </div>

            <div class="form-group clearfix">
                <label class="col-sm-4 control-label">
                </label>
                <div class="col-sm-20">
                    <blockquote class="personal">
                       <strong>Chi tiết cấu hình tại menu: Cấu hình quét user</strong>
                    </blockquote>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">{LANG.config_chamcong}</div>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-4 control-label"><strong>{LANG.group_access_ngaycong}</strong></label>
                <div class="col-sm-20">
                    <ul style="padding:5px; margin-left:10px;list-style:none;">
                        <li>
                            <div class="message_body">
                                <!-- BEGIN: daytot_group_access_ngaycong -->
                                <div class="row">
                                    <label><input name="nhansu_group_edit_ngaycong[]" type="checkbox" value="{NHANSU_EDIT.value}" {NHANSU_EDIT.checked} />{NHANSU_EDIT.title}</label>
                                </div>
                                <!-- END: daytot_group_access_ngaycong -->
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">
                    <strong>{LANG.config_dimuon}</strong>
                    &nbsp;<em class="fa fa-question-circle fa-pointer text-info" data-toggle="tooltip" data-original-title="{LANG.config_note_dimuon}">&nbsp;</em>
                </label>
                <div class="col-sm-20">
                    <input type="text" class="form-control" value="{DATA_CONFIG.dimuon}" name="dimuon"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">
                    <strong>{LANG.config_vesom}</strong>
                    &nbsp;<em class="fa fa-question-circle fa-pointer text-info" data-toggle="tooltip" data-original-title="{LANG.config_note_vesom}">&nbsp;</em>
                </label>
                <div class="col-sm-20">
                    <input type="text" class="form-control" value="{DATA_CONFIG.vesom}" name="vesom"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">
                    <strong>{LANG.config_nghigiuaca}</strong>
                    &nbsp;<em class="fa fa-question-circle fa-pointer text-info" data-toggle="tooltip" data-original-title="{LANG.config_note_nghigiuaca}">&nbsp;</em>
                </label>
                <div class="col-sm-20">
                    <input type="text" class="form-control" value="{DATA_CONFIG.nghigiuaca}" name="nghigiuaca"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">
                    <strong>{LANG.config_nuaca}</strong>
                    &nbsp;<em class="fa fa-question-circle fa-pointer text-info" data-toggle="tooltip" data-original-title="{LANG.config_note_nuaca}">&nbsp;</em>
                </label>
                <div class="col-sm-20">
                    <input type="text" class="form-control" value="{DATA_CONFIG.nuaca}" name="nuaca"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">
                    <strong>{LANG.config_motca}</strong>
                    &nbsp;<em class="fa fa-question-circle fa-pointer text-info" data-toggle="tooltip" data-original-title="{LANG.config_note_motca}">&nbsp;</em>
                </label>
                <div class="col-sm-20">
                    <input type="text" class="form-control" value="{DATA_CONFIG.motca}" name="motca"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">
                    <strong>{LANG.config_max_infringe}</strong>
                </label>
                <div class="col-sm-20">
                    <input type="text" class="form-control" value="{DATA_CONFIG.max_infringe}" name="max_infringe"/>
                </div>
            </div>
        </div>
    </div>
    <input name="submit" class="btn btn-primary" type="submit" value="{LANG.save}" />
</form>
<script type="text/javascript">
    var path = "{NV_UPLOADS_DIR}/{module_upload}";
    var currentpath = path;
    var type = "image";
    $("#selectheaderfile").click(function() {
        var area = "headerfile";
        nv_open_browse("{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 500, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
        return false;
    });
</script>

<!-- END: main -->