<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2-bootstrap.min.css" />

<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->

<form class="form-horizontal" action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <div class="panel panel-default">
        <div class="panel-body">
            <input type="hidden" name="id" value="{ROW.id}" />
            <div class="form-group">
                <label class="col-sm-5 col-md-6 control-label"><strong>{LANG.company}</strong> <span class="red">(*)</span></label>
                <div class="col-sm-19 col-md-18">
                    <select name="companyid" id="companyid" class="form-control">
                        <option value="0">-----</option>
                        <!-- BEGIN: company -->
                        <option value="{COMPANY.id}" {COMPANY.sl}>{COMPANY.title}</option>
                        <!-- END: company -->
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-6 control-label"><strong>{LANG.fullname}</strong> <span class="red">(*)</span></label>
                <div class="col-sm-19 col-md-18">
                    <div class="row">
                        <div class="col-xs-12">
                            <input class="form-control" type="text" name="last_name" value="{ROW.last_name}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" placeholder="{LANG.last_name}" />
                        </div>
                        <div class="col-xs-12">
                            <input class="form-control" type="text" name="first_name" value="{ROW.first_name}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" placeholder="{LANG.first_name}" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-6 text-right"><strong>{LANG.gender}</strong></label>
                <div class="col-sm-19 col-md-18">
                    <!-- BEGIN: gender -->
                    <label><input type="radio" name="gender" value="{GENDER.index}"{GENDER.checked} >{GENDER.value}</label>&nbsp;&nbsp;&nbsp;
                    <!-- END: gender -->
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-6 control-label"><strong>{LANG.birthday}</strong> <span class="red">(*)</span></label>
                <div class="col-sm-19 col-md-18">
                    <div class="input-group">
                        <input class="form-control datepicker" type="text" name="birthday" value="{ROW.birthday}" /> <span class="input-group-btn">
                            <button class="btn btn-default" type="button" id="birthday-btn">
                                <em class="fa fa-calendar fa-fix"> </em>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-6 control-label"><strong>{LANG.phone}</strong> <span class="red">(*)</span></label>
                <div class="col-sm-19 col-md-18">
                    <input class="form-control" type="text" name="phone" value="{ROW.phone}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-6 control-label"><strong>{LANG.email}</strong></label>
                <div class="col-sm-19 col-md-18">
                    <input class="form-control" type="text" name="email" value="{ROW.email}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-6 control-label"><strong>{LANG.address}</strong></label>
                <div class="col-sm-19 col-md-18">
                    <input class="form-control" type="text" name="address" value="{ROW.address}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-6 control-label"><strong>{LANG.scmnd}</strong></label>
                <div class="col-sm-19 col-md-18">
                    <input class="form-control" type="text" name="scmnd" value="{ROW.scmnd}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-6 control-label"><strong>{LANG.ngaycap}</strong></label>
                <div class="col-sm-19 col-md-18">
                    <div class="input-group">
                    <input class="form-control datepicker" type="text" name="ngaycap" value="{ROW.ngaycap}" /> <span class="input-group-btn">
                            <button class="btn btn-default" type="button">
                                <em class="fa fa-calendar fa-fix"> </em>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-6 control-label"><strong>{LANG.noicap}</strong></label>
                <div class="col-sm-19 col-md-18">
                    <input class="form-control" type="text" name="noicap" value="{ROW.noicap}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-6 control-label"><strong>{LANG.sobhxh}</strong></label>
                <div class="col-sm-19 col-md-18">
                    <input class="form-control" type="text" name="sobhxh" value="{ROW.sobhxh}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-6 control-label"><strong>{LANG.biensoxe}</strong> <span class="red">(*)</span></label>
                <div class="col-sm-19 col-md-18">
                    <input class="form-control" type="text" name="biensoxe" value="{ROW.biensoxe}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-6 control-label"><strong>{LANG.worktype}</strong></label>
                <div class="col-sm-19 col-md-18">
                    <select name="worktype" id="worktype" class="form-control">
                        <!-- BEGIN: worktype -->
                        <option value="{WORKTYPE.key}" {WORKTYPE.sl}>{WORKTYPE.title}</option>
                        <!-- END: worktype -->
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-6 control-label"><strong>{LANG.sohdld}</strong></label>
                <div class="col-sm-19 col-md-18">
                    <input class="form-control" type="text" name="sohdld" value="{ROW.sohdld}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-6 control-label"><strong>{LANG.ngaykyhopdong}</strong></label>
                <div class="col-sm-19 col-md-18">
                    <div class="input-group">
                        <input class="form-control datepicker" type="text" name="ngaykyhopdong" value="{ROW.ngaykyhopdong}" /> <span class="input-group-btn">
                            <button class="btn btn-default" type="button">
                                <em class="fa fa-calendar fa-fix"> </em>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-6 control-label"><strong>{LANG.ngaynghiviec}</strong></label>
                <div class="col-sm-19 col-md-18">
                    <div class="input-group">
                        <input class="form-control datepicker" type="text" name="ngaynghiviec" value="{ROW.ngaynghiviec}" /> <span class="input-group-btn">
                            <button class="btn btn-default" type="button">
                                <em class="fa fa-calendar fa-fix"> </em>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-6 control-label"><strong>{LANG.image}</strong></label>
                <div class="col-sm-19 col-md-18">
                    <div class="input-group">
                        <input class="form-control" type="text" name="image" value="{ROW.image}" id="myavatar" /> <span class="input-group-btn">
                            <button class="btn btn-default selectfile" type="button" onclick="uploadAvatar('{URL_AVATAR}');">
                                <em class="fa fa-folder-open-o fa-fix">&nbsp;</em>
                            </button>

                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group text-center button_fixed_bottom">
        <input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" />
    </div>
</form>

<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript">
    //<![CDATA[
    $(document).ready(function() {

        $(".datepicker").datepicker({
            dateFormat : "dd/mm/yy",
            changeMonth : true,
            changeYear : true,
            showOtherMonths : true,
            showOn : "focus",
            yearRange : "-90:+5",
        });
    });

    //]]>
</script>
<!-- END: main -->