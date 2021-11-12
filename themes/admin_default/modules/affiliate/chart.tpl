<!-- BEGIN: main -->


<div class="well">
    <form action="{NV_BASE_ADMINURL}index.php" method="get">
        <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" /> <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" /> <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
        <div class="row">
            <div class="col-xs-24 col-md-8">
                <div class="form-group">
                    <div class="uiTokenizer uiInlineTokenizer"  style="float:left;width: 100%">
                        <span id="userid" class="tokenarea">
                            <!-- BEGIN: data_users -->
                            <span class="uiToken removable" title="{DATA.fullname}">
                                {DATA.fullname}<input type="hidden" autocomplete="off" name="userid" value="{DATA.userid}" />
                                <a class="remove uiCloseButton uiCloseButtonSmall" href="javascript:void(0);" onclick="$(this).parent().remove();"></a>
                            </span>
                            <!-- END: data_users -->
                        </span>
                         <span class="uiTypeahead">
                            <input type="hidden" class="hiddenInput" autocomplete="off" value="" />
                            <div class="innerWrap" style="float:left;">
                                <input id="group_cat-search" type="text" placeholder="{LANG.input_customer}" class="form-control textInput" style="width: 100%;" />
                            </div>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-xs-24 col-md-3">
                <div class="form-group">
                    <div class="form-group">
                        <select name="view_chart" class="form-control">
                            <option value="0">---{LANG.view_chart_by}---</option>
                            <!-- BEGIN: view_chart -->
                            <option value="{VIEW_CHART.key}"{VIEW_CHART.sl} >{VIEW_CHART.value}</option>
                            <!-- END: view_chart -->
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-xs-24 col-md-3">
                <div class="form-group">
                    <div class="form-group">
                        <div class="input-group">
                            <input class="form-control datepicker" value="{SEARCH.starttime}" type="text" name="starttime" readonly="readonly" placeholder="{LANG.starttime}" /> <span class="input-group-btn">
								<button class="btn btn-default" type="button">
									<em class="fa fa-calendar fa-fix">&nbsp;</em>
								</button>
							</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-24 col-md-3">
                <div class="form-group">
                    <div class="form-group">
                        <div class="input-group">
                            <input class="form-control datepicker" value="{SEARCH.endtime}" type="text" name="endtime" readonly="readonly" placeholder="{LANG.endtime}" /> <span class="input-group-btn">
								<button class="btn btn-default" type="button">
									<em class="fa fa-calendar fa-fix">&nbsp;</em>
								</button>
							</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-2">
                <div class="form-group">
                    <input class="btn btn-primary" type="submit" value="{LANG.search}" name="submit" />
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    #container_chart {
        min-width: 310px;
        max-width: 100%;
        height: 400px;
        margin: 0 auto
    }
</style>
<div id="container_chart"></div>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>

<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">

<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script>
    $(".datepicker").datepicker({
        dateFormat: "dd/mm/yy",
        changeMonth: !0,
        changeYear: !0,
        showOtherMonths: !0,
        showOn: "focus",
        yearRange: "-90:+0"
    });

    $("#group_cat-search").bind("keydown", function(event) {
        if (event.keyCode === $.ui.keyCode.TAB && $(this).data("ui-autocomplete").menu.active) {
            event.preventDefault();
        }
    }).autocomplete({
        source : function(request, response) {
            $.getJSON(script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=userajax&type=all", {
                term : extractLast(request.term)
            }, response);
        },
        search : function() {
            // custom minLength
            var term = extractLast(this.value);
            if (term.length < 2) {
                return false;
            }
        },
        select : function(event, data) {
            nv_add_element( data.item );
            $(this).val('');
            return false;
        }
    });
    function nv_add_element( data ){
        var html = "<span title=\"" + data.value + "\" class=\"uiToken removable\">" + data.fullname + "<input type=\"hidden\" value=\"" + data.key + "\" name=\"userid\" autocomplete=\"off\"><a onclick=\"$(this).parent().remove();\" href=\"javascript:void(0);\" class=\"remove uiCloseButton uiCloseButtonSmall\"></a></span>";
        $("#userid").html( html );
        $('#username').html(data.username);
        $('#birthday').html(data.birthday);
        $('#email').html(data.email);
        return false;
    }
    function split(val) {
        return val.split(/,\s*/);
    }

    function extractLast(term) {
        return split(term).pop();
    }
    Highcharts.chart('container_chart', {
        chart: {
            type: 'line'
        },
        title: {
            text: 'Biểu đồ bài viết trong tháng của nhân sự content {USER.fullname} - {USER.email}'
        },
        subtitle: {
            text: 'Nguồn: Website Cash13.vn'
        },
        xAxis: {
            categories: [<!-- BEGIN: key -->'Ngày {KEY}',<!-- END: key -->]
        },
        yAxis: {
            title: {
                text: 'Số bài viết'
            }
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: false
            }
        },
        series: [{
            name: 'Thực tế',
            data: [<!-- BEGIN: value -->{VALUE},<!-- END: value -->]
        }, {
            name: 'Mục tiêu',
            data: [<!-- BEGIN: value2 -->25,<!-- END: value2 -->]
        }]
    });
</script>
<!-- END: main -->
