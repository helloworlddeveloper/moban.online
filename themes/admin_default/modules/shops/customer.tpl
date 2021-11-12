<!-- BEGIN: main -->
<!-- BEGIN: view -->
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.menu.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.autocomplete.css" rel="stylesheet" />
<form class="form-inline" action="{NV_BASE_ADMINURL}index.php" method="get">
    <input type="hidden" name="{NV_LANG_VARIABLE}"  value="{NV_LANG_DATA}" />
    <input type="hidden" name="{NV_NAME_VARIABLE}"  value="{MODULE_NAME}" />
    <input type="hidden" name="{NV_OP_VARIABLE}"  value="{OP}" />
    <div class="uiTokenizer uiInlineTokenizer"  style="float:left;">
        <span id="data_admin" class="tokenarea">
            <!-- BEGIN: data_admin -->
            <span class="uiToken removable" title="{ADMIN.full_name} - {ADMIN.username} - {ADMIN.email}">
                {ADMIN.full_name} - {ADMIN.username} - {ADMIN.email}<input type="hidden" autocomplete="off" name="aid" value="{ADMIN.userid}" />
                <a class="remove uiCloseButton uiCloseButtonSmall" href="javascript:void(0);" onclick="$(this).parent().remove();"></a>
            </span>
            <!-- END: data_admin -->
        </span>
        <span class="uiTypeahead">
            <input type="hidden" class="hiddenInput" autocomplete="off" value="" />
            <div class="innerWrap" style="float:left;">
                <input id="group_cat-search" type="text" placeholder="{LANG.admin_add}" class="form-control textInput" style="width: 100%;" />
            </div>
        </span>
    </div>
    <label> {LANG.customer_from}: </label>
    <select class="form-control" name="fromby">
        <option value="-"> ----------- </option>
        <!-- BEGIN: search_fromby -->
        <option value="{OPTION.key}" {OPTION.selected} >{OPTION.title}</option>
        <!-- END: search_fromby -->
    </select>
    <label> {LANG.customer_type}: </label>
    <select class="form-control" name="customer_type">
        <option value="-"> ----------- </option>
        <!-- BEGIN: search_customer_type -->
        <option value="{OPTION.key}" {OPTION.selected} >{OPTION.title}</option>
        <!-- END: search_customer_type -->
    </select>
    <label> {LANG.customer_tag}: </label>
    <select class="form-control" name="customer_tag">
        <option value="-"> ----------- </option>
        <!-- BEGIN: search_customer_tag -->
        <option value="{OPTION.tagid}" {OPTION.selected} >{OPTION.tag_title}</option>
        <!-- END: search_customer_tag -->
    </select>
    <strong>{LANG.search_title}</strong>&nbsp;<input class="w300 form-control" type="text" value="{Q}" name="q" maxlength="255" />&nbsp;
    <input class="btn btn-primary" type="submit" value="{LANG.search_submit}" />
    <a href="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}&add=1" class="btn btn-success">{LANG.add_customer}</a>
</form>
<br>

<form class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>{LANG.number}</th>
                <th>{LANG.name}</th>
                <th>{LANG.address}</th>
                <th>{LANG.province_name}</th>
                <th>{LANG.phone}</th>
                <th>{LANG.email}</th>
                <th>{LANG.customer_type}</th>
                <th>{LANG.edit_time}</th>
                <th>{LANG.active}</th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <td colspan="10">{NV_GENERATE_PAGE}</td>
            </tr>
            </tfoot>
            <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td> {VIEW.number} </td>
                <td> <a href="{VIEW.link_history}">{VIEW.fullname}</a> </td>
                <td> {VIEW.address} </td>
                <td> {VIEW.province} </td>
                <td> {VIEW.phone} </td>
                <td> {VIEW.email} </td>
                <td> {VIEW.customer_type} </td>
                <td> {VIEW.edit_time} </td>
                <td> {VIEW.status} </td>
                <td class="text-center">
                    <!-- BEGIN: admin -->
                    <i class="fa fa-eye fa-lg">&nbsp;</i> <a href="{VIEW.link_view}">{LANG.view}</a> - <i class="fa fa-edit fa-lg">&nbsp;</i> <a href="{VIEW.link_edit}">{LANG.edit}</a> - <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="{VIEW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a>
                    <!-- END: admin -->
                </td>
            </tr>
            <!-- END: loop -->
            </tbody>
        </table>
    </div>
</form>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.menu.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.autocomplete.min.js"></script>
<script>
    $("#group_cat-search").bind("keydown", function(event) {
        if (event.keyCode === $.ui.keyCode.TAB && $(this).data("ui-autocomplete").menu.active) {
            event.preventDefault();
        }
    }).autocomplete({
        source : function(request, response) {
            $.getJSON(script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=customer&sadmin=1", {
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
        var html = "<span title=\"" + value + "\" class=\"uiToken removable\">" + data.value + "<input type=\"hidden\" value=\"" + data.key + "\" name=\"aid\" autocomplete=\"off\"><a onclick=\"$(this).parent().remove();\" href=\"javascript:void(0);\" class=\"remove uiCloseButton uiCloseButtonSmall\"></a></span>";
        $("#data_admin").html( html );
        return false;
    }
    function split(val) {
        return val.split(/,\s*/);
    }

    function extractLast(term) {
        return split(term).pop();
    }
</script>
<!-- END: view -->

<!-- BEGIN: add_customer -->
<div class="alert alert-danger" id="alert_danger" style="display:none;"></div>
<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<form class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <input type="hidden" name="customer_id" value="{ROW.customer_id}" />
    <ul class="tabs">
        <!-- BEGIN: customer_type -->
        <li class="" id="class_{OPTION.key}"><a href="#customer_type_{OPTION.key}">{OPTION.title}</a></li>
        <!-- END: customer_type -->
    </ul>
    <div class="tab_container">
        <div class="table-responsive">
            <div style="padding:20px">
                <table class="table table-striped table-bordered table-hover">
                    <caption>Thông tin chung</caption>
                    <tbody>
                    <tr>
                        <td> {LANG.province_name} </td>
                        <td>
                            <select class="w300 form-control" name="province">
                                <option value=""> --- </option>
                                <!-- BEGIN: select_province -->
                                <option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option>
                                <!-- END: select_province -->
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td> {LANG.customer_jobs} </td>
                        <td>
                            <select class="w300 form-control" name="jobs">
                                <option value=""> --- </option>
                                <!-- BEGIN: select_jobs -->
                                <option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option>
                                <!-- END: select_jobs -->
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>{LANG.ten_nh}</td>
                        <td><input class="w300 form-control" type="text" name="ten_nh" value="{ROW.ten_nh}" /></td>
                    </tr>
                    <tr>
                        <td>{LANG.stk_nh}</td>
                        <td><input class="w300 form-control" type="text" name="stk_nh" value="{ROW.stk_nh}" /></td>
                    </tr>
                    <tr>
                        <td>{LANG.tentk_nh}</td>
                        <td><input class="w300 form-control" type="text" name="tentk_nh" value="{ROW.tentk_nh}" /></td>
                    </tr>
                    <tr>
                        <td> {LANG.note} </td>
                        <td><textarea class="form-control" style="width: 98%; height:100px;" cols="75" rows="5" name="description">{ROW.description}</textarea></td>
                    </tr>
                    <tr>
                        <td> {LANG.customer_from} </td>
                        <td>
                            <select class="w300 form-control" name="fromby">
                                <!-- BEGIN: customer_from -->
                                <option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option>
                                <!-- END: customer_from -->
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td> {LANG.active} </td>
                        <td>
                            <select class="w300 form-control" name="status">
                                <option value=""> --- </option>
                                <!-- BEGIN: select_status -->
                                <option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option>
                                <!-- END: select_status -->
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>{LANG.add_tag}</td>
                        <td>
                            <!-- BEGIN: add_tag -->
                            <label><input type="checkbox" name="add_tag[]" value="{TAG.tagid}"{TAG.ck} />{TAG.tag_title}</label>&nbsp;
                            <!-- END: add_tag -->
                        </td>
                    </tr>
                    </tbody>
                </table>
                <table class="table table-striped table-bordered table-hover">
                    <caption>Thông tin liên hệ</caption>
                    <tbody>
                    <tr>
                        <td> {LANG.name} </td>
                        <td><input class="w300 form-control" type="text" name="fullname" value="{ROW.fullname}" /></td>
                    </tr>
                    <tr>
                        <td> {LANG.address} </td>
                        <td><input class="w300 form-control" type="text" name="address" value="{ROW.address}" /></td>
                    </tr>
                    <tr>
                        <td> {LANG.phone} </td>
                        <td><input class="w300 form-control" type="text" name="phone" value="{ROW.phone}" /></td>
                    </tr>
                    <tr>
                        <td> {LANG.email} </td>
                        <td><input class="w300 form-control" type="text" name="email" value="{ROW.email}" /></td>
                    </tr>
                    <tr>
                        <td> {LANG.facebook} </td>
                        <td><input class="w300 form-control" type="text" name="facebook" value="{ROW.facebook}" /></td>
                    </tr>

                    <tr id="customer_type_0" class="tab_content" style="display: none;">
                        <td> {LANG.passport} </td>
                        <td><input class="w300 form-control" type="text" name="passport" id="passport" value="{ROW.passport}" />&nbsp;<input type="button" value="Browse server" name="selectpassport" class="btn btn-info" /></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="customer_type_1" class="tab_content" style="display: none;">
            <table class="table table-striped table-bordered table-hover">
                <caption>Thông tin doanh nghiệp</caption>
                <tbody>
                <tr>
                    <td> {LANG.customer_type} </td>
                    <td>
                        <label><input class="form-control" name="customer_type" id="cus_type_value_1" value="1" type="radio" />{LANG.customer_type_1}</label>
                        <label><input class="form-control" name="customer_type" id="cus_type_value_2" value="2" type="radio" />{LANG.customer_type_2}</label>
                    </td>
                </tr>
                <tr>
                    <td> {LANG.company_name} </td>
                    <td><input class="w300 form-control" type="text" name="company_name" value="{ROW.company_name}" /></td>
                </tr>
                <tr>
                    <td> {LANG.company_mst} </td>
                    <td><input class="w300 form-control" type="text" name="company_mst" value="{ROW.company_mst}" /></td>
                </tr>
                <tr>
                    <td> {LANG.company_gpkd} </td>
                    <td><input class="w300 form-control" type="text" name="company_gpkd" id="gpkd" value="{ROW.company_gpkd}" />&nbsp;<input type="button" value="Browse server" name="selectgpkd" class="btn btn-info" /></td>
                </tr>
                <tr>
                    <td> {LANG.company_address} </td>
                    <td><input class="w300 form-control" type="text" name="company_address" value="{ROW.company_address}" /></td>
                </tr>
                <tr>
                    <td> {LANG.company_phone} </td>
                    <td><input class="w300 form-control" type="text" name="company_phone" value="{ROW.company_phone}" /></td>
                </tr>
                <tr>
                    <td> {LANG.company_email} </td>
                    <td><input class="w300 form-control" type="text" name="company_email" value="{ROW.company_email}" /></td>
                </tr>
                <tr>
                    <td> {LANG.company_fax} </td>
                    <td><input class="w300 form-control" type="text" name="company_fax" value="{ROW.company_fax}" /></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div style="text-align: center">
        <input type="hidden" name="cus_type_value" value="" />
        <input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" />
    </div>
    <div class="clear">&nbsp;</div>
</form>

<script type="text/javascript">
    $("input[name=selectpassport]").click(function() {
        var area = "passport";
        var alt = "";
        var path = "{UPLOAD_CURRENT}";
        var currentpath = "{UPLOAD_CURRENT}";
        var type = "file";
        nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&alt=" + alt + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
        return false;
    });
    $("input[name=selectgpkd]").click(function() {
        var area = "gpkd";
        var alt = "";
        var path = "{UPLOAD_CURRENT}";
        var currentpath = "{UPLOAD_CURRENT}";
        var type = "file";
        nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&alt=" + alt + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
        return false;
    });

    var customer_type = '{ROW.customer_type}';

    var id_tab = 0;
    if( customer_type > 0 ){
        id_tab = 1;
    }
    //SHOW BY customer_type
    $(".tab_content").hide(); //Hide all content
    $("#class_" + id_tab ).addClass("active").show();
    $("#customer_type_" + id_tab ).show();

    //day la gia tri lay khi submit
    $('input[name=cus_type_value]').val( customer_type );
    if( customer_type > 0 ){
        $('#cus_type_value_' + customer_type ).attr( 'checked', 'checked' );
    }

    $("input[name=customer_type]").click(function() {
        $('input[name=cus_type_value]').val( $(this).val() );

        $('#alert_danger').show();
        var notice = 'Dữ liệu sẽ được lưu trữ ở dạng là khách hàng cá nhân!';

        if( $(this).val() == 1 ) notice = 'Dữ liệu sẽ được lưu trữ ở dạng là đối tác!';
        else notice = 'Dữ liệu sẽ được lưu trữ ở dạng là khách hàng doanh nghiệp!';

        $('#alert_danger').html( notice );

    });

    //On Click Event
    $("ul.tabs li").click(function() {
        $("ul.tabs li").removeClass("active");
        $(this).addClass("active");
        $(".tab_content").hide();

        var activeTab = $(this).find("a").attr("href");
        $(activeTab).fadeIn();

        $('#alert_danger').show();
        var notice = 'Dữ liệu sẽ được lưu trữ ở dạng là khách hàng cá nhân!';
        if( activeTab == '#customer_type_1' ){
            var value_check = $("input[name=customer_type]:checked").val();
            if( value_check == undefined ){
                value_check = 2;
                $('#cus_type_value_' + value_check ).attr( 'checked', 'checked' );
            }
            $('input[name=cus_type_value]').val( value_check );
            if( $('input[name=cus_type_value]').val() == 1 ) notice = 'Dữ liệu sẽ được lưu trữ ở dạng là đối tác!';
            else notice = 'Dữ liệu sẽ được lưu trữ ở dạng là khách hàng doanh nghiệp!';
        }else{
            $('input[name=cus_type_value]').val( 0 );
        }
        $('#alert_danger').html( notice );

        return false;
    });
</script>
<!-- END: add_customer -->
<!-- END: main -->