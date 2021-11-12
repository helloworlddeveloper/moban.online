<!-- BEGIN: main -->
<!-- BEGIN: data -->
<form class="form-inline" action="{NV_BASE_SITEURL}index.php" method="get">
    <input type="hidden" name="{NV_LANG_VARIABLE}"  value="{NV_LANG_DATA}" />
    <input type="hidden" name="{NV_NAME_VARIABLE}"  value="{MODULE_NAME}" />
    <input type="hidden" name="{NV_OP_VARIABLE}"  value="{OP}" />

    <table class="table table-striped table-bordered table-hover">
        <tbody>
        <tr>
            <td>
                <select style="width: 100%;" class="form-control" name="provinceid">
                    <option value="0">{LANG.province_search}</option>
                    <!-- BEGIN: province_select -->
                    <option value="{OPTION.id}" {OPTION.selected}>{OPTION.title}</option>
                    <!-- END: province_select -->
                </select>
            </td>
            <td>
                <input class="form-control" placeholder="{LANG.search_title}" style="width: 300px;" type="text" value="{Q}" name="keyword" maxlength="255" />&nbsp;
                <input class="btn btn-primary" type="submit" value="{LANG.search}" />
                <!-- BEGIN: allow_link_add -->
                &nbsp;<a href="{addcustomer}" class="btn btn-primary">{LANG.addcustomer}</a>
                <!-- END: allow_link_add -->
                &nbsp;<a href="{refer_by_parent}" class="btn btn-danger">Khách hàng được giao</a>
            </td>
        </tr>
        </tbody>
    </table>
</form>
<div class="content">
    <!-- BEGIN: loop -->
    <div class="panel panel-default">
        <div class="panel-body">
            <h2>
                <strong><a href="{VIEW.customer_list}"><span style="color: #0FA015">[{VIEW.timeevent_day}]</span>{VIEW.title}</a></strong>
            </h2>
            <ul class="list-horizontal-bullet clearfix">
                <li><span title="Ngày diễn ra sự kiện"><i class="fa fa-clock-o">&nbsp;</i>{VIEW.timeevent}</span></li>
                <li><span title="Khu vực"><i class="fa fa-location-arrow">&nbsp;</i>{VIEW.province_name}</span></li>
                <li><span title="Tổng số khách mời đăng ký"><i class="fa fa-user">&nbsp;</i>{VIEW.num_register} khách mời</span></li>
            </ul>
            <p><i class="fa fa-map-marker" aria-hidden="true">&nbsp;</i>{VIEW.addressevent}</p>
            <a class="btn btn-primary btn-sm" href="{VIEW.customer_list}">KHÁCH MỜI</a>
            <a class="btn btn-success btn-sm" href="{VIEW.checkin}">CHECK IN</a>
            <p>{VIEW.description}</p>
        </div>
    </div>
    <!-- END: loop -->
</div>
<!-- END: data -->
<!-- END: main -->