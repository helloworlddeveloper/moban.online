<!-- BEGIN: main -->
<div style="display: none;" id="ajax_load"><div class="loading" id="set_status"></div></div>
<form class="form-inline" action="{NV_BASE_SITEURL}index.php" method="get">
    <input type="hidden" name="{NV_LANG_VARIABLE}"  value="{NV_LANG_DATA}" />
    <input type="hidden" name="{NV_NAME_VARIABLE}"  value="{MODULE_NAME}" />
    <input type="hidden" name="{NV_OP_VARIABLE}"  value="{OP}" />
    <input type="hidden" name="eventid"  value="{eventid}" />
    <table class="table table-striped table-bordered table-hover">
        <tbody>
        <tr>
            <td>
                <input class="form-control" placeholder="{LANG.search_title}" style="width: 300px;" type="text" value="{keyword}" name="keyword" maxlength="255" />&nbsp;
                <input class="btn btn-primary" type="submit" value="{LANG.search}" />
                &nbsp;<a href="{addcustomer}" class="btn btn-primary">{LANG.addcustomer}</a>
            </td>
        </tr>
        </tbody>
    </table>
</form>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="text-center">
            <h2 style="text-transform: uppercase">CHECK IN DANH SÁCH KHÁCH MỜI SỰ KIỆN {DATA_EVENT.title}</h2>
            <p>Ngày: {DATA_EVENT.timeevent}</p>
            <p>Địa chỉ: {DATA_EVENT.addressevent}</p>
        </div>
    </div>
</div>

<form class="form-inline" action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>{LANG.full_name}</th>
                <th>{LANG.email}</th>
                <th>{LANG.mobile}</th>
                <th>{LANG.province}</th>
                <th>{LANG.address}</th>
                <th>{LANG.edit_time}</th>
                <th>{LANG.status}</th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <td colspan="8">{NV_GENERATE_PAGE}</td>
            </tr>
            </tfoot>
            <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td> {VIEW.full_name} </td>
                <td> {VIEW.email} </td>
                <td> {VIEW.mobile} </td>
                <td> {VIEW.province_name} </td>
                <td> {VIEW.address} </td>
                <td> {VIEW.edit_time} </td>
                <td> {VIEW.status} </td>
                <td class="text-center">
                    <label><input type="checkbox"{VIEW.status_ck} name="checkin" data_customerid="{VIEW.id}" value="3">Check in</label>
                </td>
            </tr>
            <!-- END: loop -->
            </tbody>
        </table>
    </div>
</form>
<script type="text/javascript">
    $("input[name=checkin]").click(function () {
        var value = $(this).prop('checked');
        var userid = $(this).attr('data_customerid');
        value = ( value == true )? 3 : 1;
        show_loading( 'Đang lưu...' );
        $.ajax({
            type: "post",
            url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checkin',
            data: 'save=1&userid=' + userid + '&value=' + value + '&eventid={eventid}',
            success: function(data){
                if ( data != 'OK') {
                    show_loading('Lỗi trong quá trình lưu dữ liệu!');
                }else{
                    show_loading('Đã lưu');
                }
                setTimeout(function(){
                    hide_loading();
                }, 2000);
            }
        });
    });
    function show_loading( html ){
        $("#set_status").html( html );
        $("#ajax_load").show();
    }
    function hide_loading(){
        $("#set_status").html( '' );
        $("#ajax_load").hide();
    }
</script>

<!-- END: main -->