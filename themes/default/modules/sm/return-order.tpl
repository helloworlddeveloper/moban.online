<!-- BEGIN: main1 -->
<div class="panel-body text-center alert-danger">Chức năng đang được xây dựng, Vui lòng quay lại sau!</div>
<!-- END: main1 -->
<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">

<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<!-- BEGIN: error -->
<div class="alert alert-danger">
    {ERROR}
</div>
<!-- END: error -->
<form onsubmit="return load_info_order();" method="post" id="fpro">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-6 control-label">{LANG.input_ordercode} <span class="error">(*)</span></label>
                <div class="col-sm-18">
                    <input name="ordercode" onblur="load_info_order()" value="{DATA.ordercode}" type="text" placeholder="{LANG.input_ordercode}" class="form-control textInput" style="width: 100%;" />
                </div>
            </div>
            <script type="text/javascript">
                function load_info_order() {
                    $('#table_product').load( nv_base_siteurl + "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "={OP}&loadcart=1&ordercode=" + $('input[name=ordercode]').val() );
                    return false;
                }
                load_info_order();
            </script>
        </div>
    </div>
</form>
<form action="{LINK_CART}" onsubmit="return check_submit_order();" method="post" id="fpro">
    <input type="hidden" value="1" name="save"/>
    <input type="hidden" name="submit" value="1">
    <div class="table-responsive" id="table_product"></div>
</form>
<script type="text/javascript" data-show="after">
    function update_order(productid, order_id, number ) {
        $.get( nv_base_siteurl + "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "={OP}&setcart=1&id=" + productid + '&order_id=' + order_id + '&num='+ number + '&type_return=' + $('select[name="type_return\\['+ productid + '\\]"]').val(), function(res) {
            $('#table_product').load( nv_base_siteurl + "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "={OP}&loadcart=1&ordercode=" + $('input[name=ordercode]').val());
        });
    }
    function update_type_return(productid, order_id, type_return ) {
        $.get( nv_base_siteurl + "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "={OP}&setcart=1&id=" + productid + '&order_id=' + order_id + '&num='+ $('input[name="listproid['+ productid + ']"]').val() + '&type_return=' + type_return , function(res) {
            $('#table_product').load( nv_base_siteurl + "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "={OP}&loadcart=1&ordercode=" + $('input[name=ordercode]').val());
        });
    }
</script>
<div class="msgshow" id="msgshow">&nbsp;</div>
<!-- END: main -->

<!-- BEGIN: product -->
<input type="hidden" name="ordercode" value="{DATA_CUSTOMER.order_code}">
<div class="form-group">
    <label class="col-sm-6 control-label">{LANG.order_name} <span class="error">(*)</span></label>
    <div class="col-sm-18">
        <p class="form-control-static"><input disabled name="order_name" id="order_name" class="form-control" value="{DATA_CUSTOMER.order_name}" /></p>
        <span class="error">{ERROR.order_name}</span>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-6 control-label">{LANG.order_phone} <span class="error">(*)</span></label>
    <div class="col-sm-18">
        <p class="form-control-static"><input disabled name="order_phone" id="order_phone" class="form-control" value="{DATA_CUSTOMER.order_phone}" /></p>
        <span class="error">{ERROR.order_phone}</span>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-6 control-label">{LANG.order_email}</label>
    <div class="col-sm-18">
        <p class="form-control-static"><input type="email" id="order_email" name="order_email" value="{DATA_CUSTOMER.order_email}" class="form-control" /></p>
        <span class="error">{ERROR.order_email}</span>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-6 control-label">{LANG.order_address} </label>
    <div class="col-sm-18">
        <p class="form-control-static"><input name="order_address" id="order_address" class="form-control" value="{DATA_CUSTOMER.order_address}" /></p>
        <span class="error">{ERROR.order_address}</span>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-6 control-label"></label>
    <div class="col-sm-18">
        <label><input name="order_shipcod" type="checkbox" class="form-control"{order_shipcod} value="{DATA.order_shipcod}" />{LANG.order_shipcod}</label>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-6 control-label"></label>
    <div class="col-sm-18">
        <label style="color: #0FA015">{message}</label>
    </div>
</div>
<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th rowspan="2">{LANG.stt}</th>
            <th class="text-center" rowspan="2">{LANG.product_title}</th>
            <th class="text-center" rowspan="2" style="width: 90px">{LANG.number_book}</th>
            <th class="text-center" rowspan="2" style="width: 100px">{LANG.number_returned}</th>
            <th class="text-center" colspan="4">{LANG.book_return_title}</th>
            <th class="text-center" colspan="3">{LANG.rebook_order_title}</th>
        </tr>
    <tr>
        <th class="text-right" id="table_product_title">{LANG.price_warehousing}</th>
        <th style="width: 100px">{LANG.number_return}</th>
        <th style="width: 180px">{LANG.type_return}</th>
        <th class="text-right">{LANG.cart_price_total}</th>
        <th class="text-right">{LANG.product_price_wholesale}</th>
        <th class="text-right">{LANG.number_book}</th>
        <th class="text-right">{LANG.cart_price_total}</th>
    </tr>
    </thead>
    <tbody>
    <!-- BEGIN: rows -->
    <tr id="item_{DATA.id}">
        <td align="center">{DATA.stt}</td>
        <td>
            {DATA.product_title}
            <!-- BEGIN: message -->
            <br>
            <label style="font-size: 11px; color: #0a6fd2">{DATA.message}</label>
            <!-- END: message -->
        </td>
        <td class="money text-right">{DATA.total_product}</td>
        <td class="money text-right">{DATA.numreturn}</td>
        <td class="money text-right"><strong>{DATA.price_format}</strong></td>
        <td align="center"><input{DISABLE} type="number" size="1" onchange="update_order('{DATA.proid}', '{DATA.order_id}',  this.value)" value="{DATA.num_return}" name="listproid[{DATA.proid}]" id="{DATA.id}" class="form-control"/></td>
        <td class="money text-right">
            <select{DISABLE} class="form-control" onchange="update_type_return('{DATA.proid}', '{DATA.order_id}',  this.value)"  name="type_return[{DATA.proid}]">
                <!-- BEGIN: type_return -->
                <option value="{NOTE_RETURN.value}"{NOTE_RETURN.sl}>{NOTE_RETURN.title}</option>
                <!-- END: type_return -->
            </select>
        </td>
        <td class="money text-right">{DATA.price_total_format}</td>
        <td class="money text-right">{DATA.rebook_price_format}</td>
        <td class="money text-right">{DATA.rebook_number}</td>
        <td class="money text-right">{DATA.rebook_total_price_format}</td>
    </tr>
    <!-- END: rows -->
    <tr>
        <td class="text-right" colspan="7">{LANG.price_total_warehousing}</td>
        <td class="money text-right">{total_price_return}</td>
        <td class="text-right" colspan="2">{LANG.price_rebook}</td>
        <td class="money text-right">{rebook_total_format}</td>
    </tr>
    </tbody>
</table>
<div>
    <div class="clearfix">
        <table class="table text-right">
            <tr>
                <td width="50%">{LANG.discount_price}</td>
                <td>
                    <input type="text" value="{price_total_discount_fomart}" readonly onkeyup="this.value=FormatNumber(this.value);update_price()" name="price_total_discount" class="form-control"/>
                    <div class="form-group">
                        <span style="font-size: 16px;color: #0FA015">{agency_info}</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td>{LANG.total_price_return}</td>
                <td><span class="money" id="price_total_fomart">{total_order_old_format} - ({total_price_return} + {rebook_total_after_discount_format})</span></td>
            </tr>
            <tr>
                <td>{LANG.order_product_price_total}</td>
                <td><span class="money" id="cart_total_fomart">{price_total_end_fomart}</span></td>
            </tr>
        </table>
        <div class="clear"></div>
    </div>
    <div class="row">
        <div class="col-md-24 text-center" style="margin: 10px 0 10px 0">
            <input type="submit" name="book_order" title="{LANG.submit_return}" value="{LANG.submit_return}" class="btn btn-success btn-sm">
        </div>
    </div>
</div>
<script type="text/javascript" data-show="after">
    function check_submit_order() {
        if( confirm('Bạn có chắc muốn trả hàng?')){
            $('input[name=book_order]').prop("disabled", true);
            return true;
        }else{
            return false;
        }
    }
    function update_price() {
        var price_total = {price_total};
        var price_total_discount = $('input[name=price_total_discount]').val();
        price_total_discount = price_total_discount.replace(/\,/g, "");
        price_total_discount = parseInt(price_total_discount);
        if( price_total_discount > price_total ){
            alert( 'Số tiền chiết khấu không thể lớn hơn tổng giá trị đơn hàng');
            $('input[name=price_total_discount]').val( FormatNumber( price_total + '' ) );
            $('#cart_total_fomart').html( '0' );
            return;
        }
        $('#cart_total_fomart').html( FormatNumber( price_total - price_total_discount + '' ) );
    }
</script>
<!-- END: product -->