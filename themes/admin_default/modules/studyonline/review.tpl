<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <tbody>
        <tr>
            <td>
                <form class="form-inline" id="filter-form" method="get" action="" onsubmit="return false;">
                    <input class="form-control" style="width:130px;" type="text" name="q" value="{DATA_SEARCH.q}" placeholder="{LANG.filter_enterkey}"/>
                    <select class="form-control"  class="text" name="khoahocid">
                        <option value="0">--{LANG.filter_all_khoahoc}--</option>
                        <!-- BEGIN: khoahoc -->
                        <option value="{KHOAHOC.id}"{KHOAHOC.selected}>{KHOAHOC.title}</option>
                        <!-- END: khoahoc -->
                    </select>
                    <input placeholder="{LANG.from}" class="form-control" type="text" name="timebegin" value="{DATA_SEARCH.timebegin}" id="timebegin" />
                    <input placeholder="{LANG.to}" class="form-control" type="text" name="timeend" value="{DATA_SEARCH.timeend}" id="timeend" />
                    <input class="btn btn-primary" type="button" name="do" value="{LANG.filter_action}"/>
                    <input class="btn btn-default" type="button" name="clear" value="{LANG.filter_clear}"/>
                    <div class="clearfix">&nbsp;</div>
                </form>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript">
    $("#timebegin,#timeend").datepicker({
        showOn : "focus",
        dateFormat : "dd/mm/yy",
        changeMonth : true,
        changeYear : true,
        showOtherMonths : true,
        buttonImage : nv_base_siteurl + "images/calendar.gif",
        buttonImageOnly : true
    });
    $(document).ready(function(){
        $('input[name=clear]').click(function(){
            $('#filter-form .text').val('');
            $('input[name=q]').val('');
        });
        $('input[name=do]').click(function(){
            var f_q = $('input[name=q]').val();
            var f_khoahocid = $('select[name=khoahocid]').val();
            var f_timebegin = $('input[name=timebegin]').val();
            var f_timeend = $('input[name=timeend]').val();
            if (  f_q != '' || f_timebegin != '' || f_timeend != '' || f_khoahocid != 0 )
            {
                $('#filter-form input, #filter-form select').attr('disabled', 'disabled');
                window.location = '{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}&q=' + f_q + '&khoahocid=' + f_khoahocid + '&timebegin=' + f_timebegin + '&timeend=' + f_timeend;
            }
            else
            {
                alert ('{LANG.filter_err_submit}');
            }
        });
    });
</script>

<form action="{FORM_ACTION}" method="post" name="levelnone" id="levelnone">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <tbody>
            <tr>
                <td style="width:30px">ID</td>
                <td>{LANG.reviewcontent}</td>
                <td style="width:90px">{LANG.addtime}</td>
                <td style="width:90px">{LANG.status}</td>
                <td style="width:120px" class="center">{LANG.feature}</td>
            </tr>
            <!-- BEGIN: row -->
            <tr class="topalign">
                <td>{ROW.id}</td>
                <td>{ROW.content}</td>
                <td><strong>{ROW.addtime}</strong></td>
                <td>
                    <select id="id_weight_{ROW.id}" class="form-control" onchange="nv_studyonline_change_review({ROW.id})" name="status">
                        <!-- BEGIN: status -->
                        <option value="{STATUS.key}"{STATUS.sl}>{STATUS.title}</option>
                        <!-- END: status -->
                    </select>
                </td>
                <td class="center">
                    <em class="fa fa-trash-o fa-lg"></em>&nbsp;<a href="javascript:void(0);" onclick="nv_studyonline_delete_review({ROW.id});">{GLANG.delete}</a>
                </td>
            </tr>
            <!-- END: row -->
            </tbody>
            <!-- BEGIN: generate_page -->
            <tbody>
            <tr>
                <td colspan="4">
                    {GENERATE_PAGE}
                </td>
            </tr>
            </tbody>
            <!-- END: generate_page -->
        </table>
    </div>
</form>
<!-- END: main -->
