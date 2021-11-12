<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<form onsubmit="return check_data_inventory(this)" name="block_list" method="post" action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}">
    <div class="table-responsive" id="data_export">
        <div class="text-center">
            <h1><strong>{LANG.bienbankiemke}</strong></h1>
            <p>
                {LANG.donvi}: {DATA.department}
            </p>
        </div>
        <div class="text-left">
            <div class="item-data clearfix">
                {LANG.thoigiankiemke}:
                &nbsp;{DATA.hour}&nbsp;
                {LANG.hour}
                &nbsp;{DATA.minute}&nbsp;
                {LANG.day}
                &nbsp;{DATA.day}&nbsp;
                {LANG.month}
                &nbsp;{DATA.month}&nbsp;
                {LANG.year}
                &nbsp;{DATA.year}&nbsp;
            </div>
            <div class="clearfix">&nbsp;</div>
            <p>{LANG.thanhphamkiemke}:</p>
            <div class="item-data">
                <!-- BEGIN: bankiemke -->
                <div class="item clear">
                    <div class="col-sm-8 col-md-8">{LANG.ongba}: {BANKIEMKE.last_name}&nbsp;{BANKIEMKE.first_name}</div>
                    <div class="col-sm-8 col-md-8">{LANG.chucvu}: {BANKIEMKE.postion_name}</div>
                </div>
                <!-- END: bankiemke -->
            </div>
            <div class="clearfix">&nbsp;</div>
        </div>
        <table class="table table-striped table-bordered table-hover">
            <thead class="text-center" style="font-weight: bold">
            <tr>
                <td rowspan="2" class="text-center">{LANG.tt}</td>
                <td rowspan="2" class="text-center">{LANG.product_list}</td>
                <td rowspan="2" class="text-center">{LANG.select_unit}</td>
                <td style="width: 60px" rowspan="2" class="text-center">{LANG.time_in}</td>
                <td rowspan="2" class="text-center">{LANG.select_department}</td>
                <td colspan="3" class="text-center">{LANG.solieusoketoan}</td>
                <td style="width: 50px" rowspan="2" class="text-center">{LANG.solieukiemkethucte}</td>
                <td colspan="2" class="text-center">{LANG.tinhtrang}</td>
                <td colspan="2" class="text-center">{LANG.sotaisanthuathieu}</td>
                <td rowspan="2" class="text-center">{LANG.ghichu}</td>
            </tr>
            <tr>
                <td style="width: 30px">{LANG.amount}</td>
                <td style="width: 50px">{LANG.nguyengia}</td>
                <td style="width: 50px">{LANG.giatriconlai}</td>
                <td>{LANG.hong}</td>
                <td>{LANG.dangsd}</td>
                <td>{LANG.thua}</td>
                <td>{LANG.thieu}</td>
            </tr>
            <tr>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5</td>
                <td>6</td>
                <td>7</td>
                <td>8</td>
                <td>9</td>
                <td>10</td>
                <td>11</td>
                <td>12</td>
                <td>13</td>
                <td>14</td>
            </tr>
            </thead>
            <tbody>
            <!-- BEGIN: producttype -->
            <tr>
                <td colspan="14"><strong>{PRODUCTTYPE.title}</strong></td>
            </tr>
            <!-- BEGIN: loop -->
            <tr>
                <td>{ROW.tt}</td>
                <td class="text-left">{ROW.title}</td>
                <td>{ROW.unit}</td>
                <td>{ROW.time_in}</td>
                <td class="text-center">{ROW.department}</td>
                <td class="text-center">{ROW.amount}</td>
                <td class="text-center">{ROW.price}</td>
                <td class="text-center">{ROW.price_conlai}</td>
                <td class="text-center">{ROW.amount_inventory}</td>
                <td class="text-center">{ROW.amount_broken}</td>
                <td class="text-center">{ROW.amount_using}</td>
                <td class="text-center">{ROW.amount_redundant}</td>
                <td class="text-center">{ROW.amount_missing}</td>
                <td class="text-center">{ROW.note}</td>
            </tr>
            <!-- END: loop -->
            <!-- END: producttype -->
            </tbody>
        </table>
        <div class="form-group">
            <strong>{LANG.nguyennhanthuathieu}:</strong> {DATA.nguyennhan}
        </div>
        <div class="form-group">
            <strong>{LANG.kiennghi}:</strong> {DATA.kiennghi}
        </div>
        <div class="text-center clear">
            <input class="btn btn-primary" type="button" name="create_excel" id="create_excel" value="{LANG.export_excel}"><br><br>
        </div>
    </div>
</form>
<script>
    $(document).ready(function(){
        $('#create_excel').click(function(){
            var excel_data = $('#data_export').html();
            $.ajax({
                type: 'post',
                url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=export',
                data: 'step=1&id={DATA.id}',
                dataType: "json",
                success: function(b) {
                    if(b.status == 'OK'){
                        window.location.href = nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=export&step=2';
                    }
                    else{
                        alert(b.mess);
                    }
                }
            });
        });
    });
</script>
<!-- END: main -->