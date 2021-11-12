<!-- BEGIN: main -->
<!-- BEGIN: nolist -->
<div class="well text-center red"><strong>BẠN KHÔNG CÓ SẢN PHẨM ĐƯỢC ĐẶT TRƯỚC</strong></div>
<!-- END: nolist -->
<!-- BEGIN: list -->
<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<div class="well">
    <form action="{NV_BASE_ADMINURL}index.php" class="form-inline" method="get" id="search_form">
        <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" />
        <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
        <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
        <input type="hidden" name="userid" value="{userid}" />
        <input type="hidden" name="checkss" value="{checkss}" />
        <div class="row">
            <div class="col-xs-12 col-md-10">
                <div class="form-group">
                    <div class="input-group">
                        <select class="form-control" name="product">
                            <option value="0">--{LANG.search_product}--</option>
                            <!-- BEGIN:product -->
                            <option value="{PRODUCT.id}"{PRODUCT.sl}>{PRODUCT.title}</option>
                            <!-- END:product -->
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-4">
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" name="from" id="from" value="{SEARCH.date_from}" class="form-control" placeholder="{LANG.date_from}" readonly="readonly">
                        <span class="input-group-btn">
							<button class="btn btn-default" type="button" id="from-btn">
								<em class="fa fa-calendar fa-fix">&nbsp;</em>
							</button> </span>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-4">
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" name="to" id="to" value="{SEARCH.date_to}" class="form-control" placeholder="{LANG.date_to}" readonly="readonly">
                        <span class="input-group-btn">
							<button class="btn btn-default" type="button" id="to-btn">
								<em class="fa fa-calendar fa-fix">&nbsp;</em>
							</button> </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="{LANG.search}" />
            </div>
        </div>
    </form>
</div>
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <colgroup>
            <col />
            <col class="w250" />
            <col class="w150" />
            <col class="w200" span="2" />
        </colgroup>
        <thead>
        <tr>
            <th>{LANG.product_title}</th>
            <th class="text-center">{LANG.content_product_number1}</th>
            <th class="text-center">{LANG.content_product_number_book}</th>
        </tr>
        </thead>
        <tbody>
        <!-- BEGIN: loop -->
        <tr>
            <td>{VIEW.product_title}</td>
            <td class="text-center">{VIEW.numreal}</td>
            <td class="text-center">{VIEW.require}</td>
        </tr>
        <!-- END: loop -->
        </tbody>
    </table>
</div>
<script type='text/javascript'>
    function nv_export_excel()
    {
        $('.exportexcel').attr('disabled', 'disabled');
        $('#loading_bar').html('<center><img src="'+ nv_base_siteurl + 'assets/images/load_bar.gif" alt="" />&nbsp;{LANG.waiting}...</center>');
        $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=exportwarehouse&nocache=' + new Date().getTime(), 'order_id={order_id}', function(res) {
            var r_split = res.split('_');
            if(r_split[0] == 'OK'){
                $("#loading_bar").hide();
                alert(r_split[1]);
                $('.exportexcel').removeAttr('disabled');
                window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=exportwarehouse&step=2';
            }else{
                $("#loading_bar").hide();
                alert(r_split[1]);
                $('.exportexcel').removeAttr('disabled');
            }
        });
    }
    $('.number_sale').keyup(function(){
        var obj = $(this).attr('data_id');
        save_price(obj);
    });
    $('.product_price').keyup(function(){
        var obj = $(this).attr('data_id');
        save_price(obj);
    });
    function save_price(obj){
        var product_price = $('#product_price_' + obj).val();
        product_price = product_price.split('.').join('');
        var total_price = $('#number_sale_' + obj).val() * product_price + '';
        $('input[name=total_price_' + obj + ']').val( total_price );
        $('#total_price_' + obj).html( FormatNumber(total_price));
        var tongloinhuan = total_price - ($('#number_sale_' + obj).val() * $('input[name=gianhap_' + obj + ']').val().split('.').join(''))
        $('#loinhuan_' + obj).html( FormatNumber(tongloinhuan + ''));
        $('input[name=loinhuan_' + obj + ']').val( tongloinhuan );
        var tonkho = $('input[name=quantity_' + obj + ']').val() - $('#number_sale_' + obj).val();
        $('#tonkho_' + obj).html( FormatNumber(tonkho + ''));
        $('input[name=tonkho_' + obj + ']').val( tonkho );
        var tientonkho = $('input[name=gianhap_' + obj + ']').val().split('.').join('') * tonkho;
        $('#tientonkho_' + obj).html( FormatNumber(tientonkho + ''));
        $('input[name=tientonkho_' + obj + ']').val( tientonkho );

        $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=warehouse_logs&nocache=' + new Date().getTime(), 'update_warehouse&logid=' + obj + '&quantity_out=' + $('#number_sale_' + obj).val() + '&price_out=' + $('#product_price_' + obj).val(), function(res) {
            var r_split = res.split('_');
            if (r_split[0] != 'OK') {
                //alert(nv_is_change_act_confirm[2]);
            }
            clearTimeout(nv_timer);
            var parentid = parseInt(r_split[1]);
            nv_show_list_cat(parentid);
            return;
        });
    }
    $(function() {
        $("#from, #to").datepicker({
            dateFormat : "dd/mm/yy",
            changeMonth : true,
            changeYear : true,
            showOtherMonths : true,
            showOn : 'focus'
        });
        $('#to-btn').click(function() {
            $("#to").datepicker('show');
        });
        $('#from-btn').click(function() {
            $("#from").datepicker('show');
        });
    });
</script>
<!-- END: list -->
<!-- END: main -->