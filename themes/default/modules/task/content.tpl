<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2-bootstrap.min.css" />
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<form class="form-horizontal" action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <div class="panel panel-default">
        <div class="panel-body">
            <input type="hidden" name="id" value="{ROW.id}" />
            <div class="form-group">
                <label class="col-sm-5 col-md-4 control-label"><strong>{LANG.title}</strong> <span class="red">(*)</span></label>
                <div class="col-sm-19 col-md-20">
                    <input class="form-control" type="text" name="title" value="{ROW.title}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" />
                </div>
            </div>
            <!-- BEGIN: allow_useradd -->
            <div class="form-group">
                <label class="col-sm-5 col-md-4 control-label"><strong>{LANG.useradd}</strong> <span class="red">(*)</span></label>
                <div class="col-sm-19 col-md-20">
                    <select class="form-control select2" name="useradd">
                        <option value="0">---{LANG.useradd_select}---</option>
                        <!-- BEGIN: useradd -->
                        <option value="{USERADD.userid}"{USERADD.selected}>{USERADD.fullname}</option>
                        <!-- END: useradd -->
                    </select>
                </div>
            </div>
            <!-- END: allow_useradd -->
            <div class="form-group">
                <label class="col-sm-5 col-md-4 control-label"><strong>{LANG.performer}</strong> <span class="red">(*)</span></label>
                <div class="col-sm-19 col-md-20">
                    <select class="form-control select2" name="performer[]" multiple="multiple">
                        <option value="0">---{LANG.performer_select}---</option>
                        <!-- BEGIN: leader -->
                        <optgroup label="{LANG.leader}">
                            <!-- BEGIN: loop -->
                            <option value="{USER.userid}"{USER.selected}>&nbsp;&nbsp;&nbsp;&nbsp;{USER.space}{USER.fullname}</option>
                            <!-- END: loop -->
                        </optgroup>
                        <!-- END: leader -->
                        <!-- BEGIN: member -->
                        <optgroup label="{LANG.member}">
                            <!-- BEGIN: loop -->
                            <option value="{USER.userid}"{USER.selected}>&nbsp;&nbsp;&nbsp;&nbsp;{USER.space}{USER.fullname}</option>
                            <!-- END: loop -->
                        </optgroup>
                        <!-- END: member -->
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-4 control-label"><strong>{LANG.begintime}</strong></label>
                <div class="col-sm-19 col-md-20">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="input-group">
                                <input class="form-control datepicker" type="text" name="begindate" value="{ROW.begindate}" pattern="^[0-9]{2,2}\/[0-9]{2,2}\/[0-9]{1,4}$" autocomplete="new-password" /> <span class="input-group-btn">
                                    <button class="btn btn-default" type="button">
                                        <em class="fa fa-calendar fa-fix"> </em>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="input-group">
                                <input class="form-control timepicker" type="text" name="begintime" value="{ROW.begintime}" autocomplete="new-password" /> <span class="input-group-btn">
                                    <button class="btn btn-default" type="button">
                                        <em class="fa fa-clock-o fa-fix"> </em>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-4 control-label"><strong>{LANG.exptime}</strong></label>
                <div class="col-sm-19 col-md-20">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="input-group">
                                <input class="form-control datepicker" type="text" name="expdate" value="{ROW.expdate}" pattern="^[0-9]{2,2}\/[0-9]{2,2}\/[0-9]{1,4}$" autocomplete="new-password" /> <span class="input-group-btn">
                                    <button class="btn btn-default" type="button">
                                        <em class="fa fa-calendar fa-fix"> </em>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="input-group">
                                <input class="form-control timepicker" type="text" name="exptime" value="{ROW.exptime}" autocomplete="new-password" /> <span class="input-group-btn">
                                    <button class="btn btn-default" type="button">
                                        <em class="fa fa-clock-o fa-fix"> </em>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-4 control-label"><strong>{LANG.description}</strong></label>
                <div class="col-sm-19 col-md-20">{ROW.description}</div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-4 control-label"><strong>{LANG.status}</strong></label>
                <div class="col-sm-19 col-md-20">
                    <select name="status" class="form-control">
                        <!-- BEGIN: status -->
                        <option value="{STATUS.index}"{STATUS.selected}>{STATUS.value}</option>
                        <!-- END: status -->
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-4 control-label"><strong>{LANG.priority}</strong></label>
                <div class="col-sm-19 col-md-20">
                    <select class="form-control" name="priority">
                        <!-- BEGIN: looppriority -->
                        <option value="{VALUE.index}"{VALUE.selected}>{VALUE.value}</option>
                        <!-- END: looppriority -->
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group text-center button_fixed_bottom">
        <input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" />
    </div>
</form>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<script type="text/javascript">
    //<![CDATA[
    $('.select2').select2({
        language : '{NV_LANG_INTERFACE}',
        theme : 'bootstrap'
    });
    
    $('.timepicker').timepicker({
        timeFormat : 'HH:mm',
        interval : 30,
        minTime : '30',
        maxTime : '11:59pm',
        defaultTime : 'value',
        startTime : '07:00',
        dynamic : false,
        dropdown : true,
        scrollbar : true
    });
    
    $(".datepicker").datepicker({
        dateFormat : "dd/mm/yy",
        changeMonth : true,
        changeYear : true,
        showOtherMonths : true,
        showOn : "focus",
        yearRange : "-90:+5",
    });

    //]]>
</script>
<!-- END: main -->