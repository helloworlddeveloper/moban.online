<!-- BEGIN: main -->
<script type="text/javascript">
    function nv_get_district(provinceid, districtid) {
        if( provinceid == 0 ){
            provinceid = $('select[name=provinceid]').val();
        }
        $.post(nv_base_siteurl + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '={OP}&nocache=' + new Date().getTime(), 'loaddistrict=1&provinceid=' + provinceid + '&districtid=' + districtid, function(res) {
            $("#district_data").html( res );
        });
    }
</script>
<!-- BEGIN: view -->
<script type="text/javascript">
    <!-- BEGIN: loaddistrict -->
    nv_get_district('{provinceid}', '{districtid}');
    <!-- END: loaddistrict -->
</script>
<form class="form-inline" action="{NV_BASE_SITEURL}index.php" method="get">
    <input type="hidden" name="{NV_LANG_VARIABLE}"  value="{NV_LANG_DATA}" />
    <input type="hidden" name="{NV_NAME_VARIABLE}"  value="{MODULE_NAME}" />
    <input type="hidden" name="{NV_OP_VARIABLE}"  value="{OP}" />
    <input type="hidden" name="eventid"  value="{eventid}" />
    <table class="table table-striped table-bordered table-hover">
        <tbody>
        <tr>
            <td>
                <select style="width: 100%;" class="form-control" name="status">
                    <option value="-1">{LANG.status_search}</option>
                    <!-- BEGIN: status_select -->
                    <option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option>
                    <!-- END: status_select -->
                </select>
            </td>
            <td>
                <select style="width: 100%;" onchange="nv_get_district(this.value, 0)" class="form-control" name="provinceid">
                    <option value="0">{LANG.province_search}</option>
                    <!-- BEGIN: province_select -->
                    <option value="{OPTION.id}" {OPTION.selected}>{OPTION.title}</option>
                    <!-- END: province_select -->
                </select>
            </td>
            <td id="district_data">
                <select style="width: 100%;" class="form-control" name="districtid">
                    <option value="0">{LANG.district_search}</option>
                </select>
            </td>
            <td>
                <input class="form-control" placeholder="{LANG.search_title}" style="width: 250px;" type="text" value="{keyword}" name="keyword" maxlength="255" />&nbsp;
                <input class="btn btn-primary" type="submit" value="{LANG.search}" />
            </td>
        </tr>
        </tbody>
    </table>
</form>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="text-center">
            <h2 style="text-transform: uppercase">DANH SÁCH KHÁCH HÀNG ĐƯỢC GIAO CHO BẠN</h2>
        </div>
    </div>
</div>

<form class="form-inline" action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <div class="table-responsive">
        <table id="tblData" class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>STT</th>
                <th>{LANG.full_name}</th>
                <th>{LANG.mobile}</th>
                <th>{LANG.province}</th>
                <th>Dữ liệu đến từ kênh</th>
                <th>{LANG.edit_time}</th>
                <th>{LANG.status}</th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <td colspan="8">{NV_GENERATE_PAGE} - <strong>Tổng số khách được giao {num_items}</strong></td>
            </tr>
            </tfoot>
            <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td> {VIEW.stt} </td>
                <td> {VIEW.full_name} </td>
                <td> {VIEW.mobile} </td>
                <td> {VIEW.province_name} </td>
                <td> {VIEW.from_by} </td>
                <td> {VIEW.edit_time} </td>
                <td> {VIEW.status} </td>
                <td class="text-center">
                    <i class="fa fa-history fa-lg">&nbsp;</i><a href="{VIEW.addevent}">{LANG.history}</a>
                </td>
            </tr>
            <!-- END: loop -->
            </tbody>
        </table>
    </div>
</form>
<!-- END: view -->
<!-- END: main -->