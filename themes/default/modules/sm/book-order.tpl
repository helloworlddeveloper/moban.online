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
<form action="{LINK_CART}" onsubmit="return check_submit_order();" method="post" id="fpro">
    <input type="hidden" name="submit" value="1">
<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <div class="form-group">
                <div class="col-md-4 col-sm-10">
                    <label for="radio2">{LANG.chossen_ordertype}</label>
                </div>
                <div class="col-md-10 col-sm-14">
                    <select name="ordertype" class="form-control">
                        <option value="0">-----</option>
                        <!-- BEGIN: ordertype -->
                        <option{ORDERTYPE.sl} value="{ORDERTYPE.key}">{ORDERTYPE.value}</option>
                        <!-- END: ordertype -->
                    </select>
                </div>
            </div>
        </div>
        <div id="step2">
            <div class="funkyradio row">
                <p><strong>{LANG.select_book_type}</strong></p>
                <div class="col-md-8 col-sm-8 funkyradio-success">
                    <input type="radio" id="radio2" name="chossentype" value="2"{chossentype_2}/>
                    <label for="radio2">{LANG.bookforagency}</label>
                    <!-- BEGIN: agency -->
                    <select name="agencyid" class="form-control">
                        <option value="0">--{LANG.select_value}--</option>
                        <!-- BEGIN: loop -->
                        <option{AGENCY.sl} value="{AGENCY.key}" data-address="{AGENCY.address}" data-email="{AGENCY.email}" data-phone="{AGENCY.phone}" data-fullname="{AGENCY.fullname}" data-info="{AGENCY.info_agency}">{AGENCY.value}</option>
                        <!-- END: loop -->
                    </select>
                    <!-- END: agency -->
                    <!-- BEGIN: noagency -->
                    <p>{LANG.create_customer_agency} <strong><a href="{link_here}">{LANG.link_here}</a></strong></p>
                    <!-- END: noagency -->
                </div>
                <div class="col-md-8 col-sm-8 funkyradio-success">
                    <input type="radio" id="radio3" name="chossentype" value="3"{chossentype_3} />
                    <label for="radio3">{LANG.bookforcustomer}</label>
                    <div class="uiTokenizer uiInlineTokenizer"  style="float:left; margin-right: 10px; width: 100%">
                    <span id="userid" class="tokenarea">
                        <!-- BEGIN: data_users -->
                        <span class="uiToken removable" title="{DATA.fullname}">
                            {DATA.order_name}<input type="hidden" autocomplete="off" name="customer_id" value="{DATA.customer_id}" />
                            <a class="remove uiCloseButton uiCloseButtonSmall" href="javascript:void(0);" onclick="$(this).parent().remove();"></a>
                        </span>
                        <!-- END: data_users -->
                    </span>
                        <span class="uiTypeahead">
                        <input type="hidden" class="hiddenInput" autocomplete="off" value="" />
                        <div class="innerWrap" style="float:left;width: 68%">
                            <input id="usersearch" onblur="check_info_customer()" type="text" placeholder="{LANG.customer_name_or_mobile}" class="form-control textInput" style="width: 100%;" />
                        </div>
                    </span>
                    </div>
                </div>
                <div class="col-md-8 col-sm-8 funkyradio-success" id="chossentype_1">
                    <input type="radio" id="radio1" name="chossentype" value="1"{chossentype_1} />
                    <label for="radio1">{LANG.order_book_plane}</label>
                    <!-- BEGIN: list_order -->
                    <select name="order_plane" class="form-control">
                        <option value="0">--{LANG.select_value}--</option>
                        <!-- BEGIN: loop -->
                        <option{BOOK_PLANE.sl} value="{BOOK_PLANE.order_id}" data-customerid="{BOOK_PLANE.customer_id}" data-address="{BOOK_PLANE.order_address}" data-email="{BOOK_PLANE.order_email}" data-phone="{BOOK_PLANE.order_phone}" data-fullname="{BOOK_PLANE.order_name}">{BOOK_PLANE.order_code} - {BOOK_PLANE.order_phone}</option>
                        <!-- END: loop -->
                    </select>
                    <!-- END: list_order -->
                    <!-- BEGIN: no_order -->
                    <p>{LANG.no_order_book_plane}</p>
                    <!-- END: no_order -->
                </div>
            </div>
        </div>
        <div id="step3">
            <div class="form-group">
                <label class="col-sm-6 control-label">{LANG.order_name} <span class="error">(*)</span></label>
                <div class="col-sm-18">
                    <p class="form-control-static">
                        <input type="text" name="order_name" class="order_name form-control" value="{DATA.order_name}" />
                    </p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label">{LANG.order_phone} <span class="error">(*)</span></label>
                <div class="col-sm-18">
                    <p class="form-control-static">
                        <input type="text" name="order_phone" class="order_phone form-control" value="{DATA.order_phone}" />
                    </p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label">{LANG.order_email}</label>
                <div class="col-sm-18">
                    <p class="form-control-static"><input type="email" id="order_email" name="order_email" value="{DATA.order_email}" class="form-control" /></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label">{LANG.order_address} </label>
                <div class="col-sm-18">
                    <p class="form-control-static"><input name="order_address" id="order_address" class="form-control" value="{DATA.order_address}" /></p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-18">
                    <label><input name="order_shipcod" type="checkbox" class="form-control"{order_shipcod} value="{DATA.order_shipcod}" />{LANG.order_shipcod}</label>
                </div>
            </div>
        </div>
    </div>
</div>
    <script type="text/javascript">
        function check_submit_order() {
            if( confirm('Bạn có chắc muốn đặt hàng?')){
                $('input[name=book_order]').prop("disabled", true);
                return true;
            }else{
                return false;
            }
        }
        if( $("option:selected", $("select[name=ordertype]")).val() == 0){
            $('#step2,#step3').hide();
        }else{
            $('#step2').show();
        }
        if( $("input[name=chossentype]:checked").val() > 0){
            $('#step3').show();
        }else{
            $('#step3').hide();
        }

        $("select[name=ordertype]").change(function(){
            $('select[name=agencyid]').val(0);
            $('select[name=order_plane]').val(0);

            if( $(this).val() == 2 ){
                $('#note_text').show();
                $('#step2').show();
                $('#chossentype_1').hide();
                $('select[name=order_plane]').prop('disabled', 'disabled');
                $('#radio1').prop('disabled', 'disabled');
            }else if( $(this).val() == 1 ){
                $('#note_text').hide();
                $('#step2').show();
                $('#chossentype_1').show();
                $('select[name=order_plane]').prop('disabled', false);
                $('#radio1').prop('disabled', false);
            }else{
                $('#step2,#step3').hide();
                $('#note_text').hide();
            }

            var urloadcart = nv_base_siteurl + "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=book-order&loadcart=1&chossentype=" + $('input[name=chossentype]:checked').val() + '&customerid=' + $('input[name=customer_id]').val() + '&agencyid=' + $('select[name=agencyid]').val() + "&ordertype=" + $('select[name=ordertype]').val();
            $('#table_product').load( urloadcart );
        });

        $('input[name=chossentype]').click(function () {
            if( $(this).val() == 3 ){
                $('select[name=agencyid]').val(0);
                $('select[name=order_plane]').val(0);
                $('input[name=order_name]').prop('readonly', false);
                $('input[name=order_phone]').prop('readonly', false);
            }else{
                $('input[name=order_name]').prop('readonly', true);
                $('input[name=order_phone]').prop('readonly', true);
            }

            $('#step3').show();
            $("#userid").html( '' );
            $('.order_name').val('');
            $('.order_phone').val('');
            $('#order_email').val('');
            $('#order_address').val('');
            $('#create_customer').html('');
            $('#table_product').load( nv_base_siteurl + "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=book-order&loadcart=1&chossentype=" + $('input[name=chossentype]:checked').val() + "&ordertype=" + $('select[name=ordertype]').val() );
        })

        function check_info_customer() {
            if( $('input[name=customer_id]').length == 0 || $('input[name=customer_id]').val() == 0){
                if( $('input[name=chossentype]:checked').val() == 3 ){
                    $('#create_customer').html('<i>{LANG.create_customer_one}</i>');
                }
            }
        }

        $("#usersearch").bind("keydown", function(event) {
            if (event.keyCode === $.ui.keyCode.TAB && $(this).data("ui-autocomplete").menu.active) {
                event.preventDefault();
            }
        }).autocomplete({
            source : function(request, response) {
                $.getJSON( nv_base_siteurl + "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=userajax", {
                    term : extractLast(request.term),
                    chossentype : $('input[name=chossentype]:checked').val()
                }, response);
            },
            search : function() {
                // custom minLength
                var term = extractLast(this.value);
                if (term.length < 0) {
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

            var html = "<span title=\"" + data.value + "\" class=\"uiToken removable\">" + data.fullname + "<input type=\"hidden\" value=\"" + data.key + "\" name=\"customer_id\" autocomplete=\"off\"><a onclick=\"$(this).parent().remove();\" href=\"javascript:void(0);\" class=\"remove uiCloseButton uiCloseButtonSmall\"></a></span>";
            $("#userid").html( html );
            $('.order_name').val(data.fullname);
            $('.order_phone').val(data.phone);
            $('#order_email').val(data.email);
            $('#order_address').val(data.address);
            $('#table_product').load( nv_base_siteurl + "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=book-order&loadcart=1&chossentype=" + $('input[name=chossentype]:checked').val() + '&customerid=' + data.key + "&ordertype=" + $('select[name=ordertype]').val() );
            numitem = 0;
            return false;
        }
        function split(val) {
            return val.split(/,\s*/);
        }
        function extractLast(term) {
            return split(term).pop();
        }


    $('select[name=agencyid]').click(function () {
        $('#radio2').prop('checked',true);
    })
    $('select[name=order_plane]').click(function () {
        $('#radio1').prop('checked',true);
    })
    $('#usersearch').focus(function () {
        $('#step3').show();
        $('#radio3').prop('checked',true);
        $('select[name=agencyid]').val(0);
        $('select[name=order_plane]').val(0);
        $('input[name=order_name]').prop('readonly', false);
        $('input[name=order_phone]').prop('readonly', false);
    })
    $('select[name=agencyid]').change(function () {
        $('.order_name').val($('option:selected', this).attr('data-fullname'));
        $('.order_phone').val($('option:selected', this).attr('data-phone'));
        $('#order_email').val($('option:selected', this).attr('data-email'));
        $('#order_address').val($('option:selected', this).attr('data-address'));
        $("#userid").html( '' );
        $('select[name=order_plane]').val(0);
        $('#step3').show();

        $('input[name=order_name]').prop('readonly', true);
        $('input[name=order_phone]').prop('readonly', true);

        $('#table_product').load( nv_base_siteurl + "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=book-order&loadcart=1&chossentype=" + $('input[name=chossentype]:checked').val() + '&customerid=' + $(this).val() + "&ordertype=" + $('select[name=ordertype]').val());
    });
    $('select[name=order_plane]').change(function () {
        $('.order_name').val($('option:selected', this).attr('data-fullname'));
        $('.order_phone').val($('option:selected', this).attr('data-phone'));
        $('#order_email').val($('option:selected', this).attr('data-email'));
        $('#order_address').val($('option:selected', this).attr('data-address'));
        $("#userid").html( '' );
        $('select[name=agencyid]').val(0);
        $('input[name=order_name]').prop('readonly', true);
        $('input[name=order_phone]').prop('readonly', true);
        $('#step3').show();
        $('#table_product').load( nv_base_siteurl + "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=book-order&loadcart=1&chossentype=" + $('input[name=chossentype]:checked').val() + '&customerid=' + $('option:selected', this).attr('data-customerid') + '&orderid=' + $(this).val() + "&ordertype=" + $('select[name=ordertype]').val());
    });
    $('#table_product').load( nv_base_siteurl + "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=book-order&loadcart=1&chossentype={DATA.chossentype}&customerid={DATA.customer_id}&orderid={DATA.orderid}&agencyid={DATA.agencyid}&price_total_discount={DATA.price_total_discount}&price_payment={DATA.price_payment}" + "&ordertype=" + $('select[name=ordertype]').val() );
</script>
    <input type="hidden" value="1" name="save"/>
    <div class="table-responsive" id="table_product"></div>
    <div class="row">
        <div class="col-md-24 text-center" style="margin: 10px 0 10px 0">
            <div id="note_text" class="text-center red">{LANG.note_text_ordertype}</div>
            <input type="submit" name="book_order" title="{LANG.cart_order}" value="{LANG.cart_order}" class="btn btn-success btn-sm" />
        </div>
    </div>
</form>
<script type="text/javascript" data-show="after">
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
    function update_order(productid, number, isgift, giftproduct ) {
        var num_com = $('#quantity_com_'+productid).val();
        $.get( nv_base_siteurl + "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=book-order&setcart=1&id=" + productid + '&num='+ number + '&num_com='+ num_com + '&isgift=' + isgift + '&giftproduct=' + giftproduct + "&ordertype=" + $('select[name=ordertype]').val(), function(res) {
            //lay ma o phan nhan don hang hoac tu don ahng dat truoc
            var customerid = $('input[name=customer_id]').val();
            if( customerid == undefined ){
                customerid = $('option:selected', 'select[name=order_plane]').attr('data-customerid');
            }
            var res = res.split('_');
            if( res[0] == 'OK'){
                $('#table_product').load( nv_base_siteurl + "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=book-order&loadcart=1&chossentype=" + $('input[name=chossentype]:checked').val() + '&customerid=' + customerid + '&orderid=' + $('input[name=orderid]').val() + '&agencyid=' + $('select[name=agencyid]').val() + "&ordertype=" + $('select[name=ordertype]').val());
            }else{
                alert(res[1]);
            }
        });
    }
    function update_price_thisproduct(productid, price ) {
        $.get( nv_base_siteurl + "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=book-order&setprice=1&id=" + productid + '&price='+ price, function(res) {
            $('#table_product').load( nv_base_siteurl + "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=book-order&loadcart=1&chossentype=" + $('input[name=chossentype]:checked').val() + '&customerid=' + $('input[name=customer_id]').val() + '&agencyid=' + $('select[name=agencyid]').val() + "&ordertype=" + $('select[name=ordertype]').val());
        });
    }
</script>
<div class="msgshow" id="msgshow">&nbsp;</div>
<!-- END: main -->

<!-- BEGIN: product -->
<table class="table table-striped table-bordered table-hover">
    <thead>
    <tr>
        <th>{LANG.stt}</th>
        <th>{LANG.product_title}</th>
        <th class="text-right" id="table_product_title">{LANG.product_price}</th>
        <!-- BEGIN: number_warehouse -->
        <th class="text-center" style="width: 90px">{LANG.number_warehouse}&nbsp;<span class="info_icon" data-toggle="tooltip" title="" data-original-title="{LANG.number_warehouse_note}">&nbsp;</span></th>
        <!-- END: number_warehouse -->
        <th style="width: 100px">{LANG.cart_numbers}</th>
        <th style="width: 90px">{LANG.product_gift}&nbsp;<span class="info_icon" data-toggle="tooltip" title="" data-original-title="{LANG.product_gift_note}">&nbsp;</span></th>
        <th class="text-right">{LANG.cart_price_total}</th>
        <th>&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    <!-- BEGIN: rows -->
    <tr id="item_{DATA.id}">
        <td align="center">{DATA.stt}</td>
        <td>{DATA.title}</td>
        <!-- BEGIN: nopriceshow -->
        <td class="money text-right"><strong>{DATA.price_retail}</strong></td>
        <!-- END: nopriceshow -->
        <!-- BEGIN: priceshow -->
        <td class="text-right"><input type="text" size="1" onchange="update_price_thisproduct('{DATA.id}', this.value);"  onkeyup="this.value=FormatNumber(this.value);" value="{DATA.price_retail}" name="price_retail[{DATA.id}]" id="price_retail{DATA.id}" class="form-control"/></td>
        <!-- END: priceshow -->
        <!-- BEGIN: number_warehouse -->
        <td class="money text-right">{DATA.quantity_warehouse}</td>
        <!-- END: number_warehouse -->
        <td align="center"><input type="number" size="1" onchange="update_order('{DATA.id}', this.value, '0', 0)" value="{DATA.cartnumber}" name="listproid[{DATA.id}]" id="{DATA.id}" class="form-control"/></td>
        <td align="center">{DATA.giftproduct}</td>
        <td class="money text-right">{DATA.price_total}</td>
        <td align="center"><a class="remove_cart" title="{LANG.cart_remove_pro}" href="{DATA.link_remove}"><em style="color: red" class="fa fa-times-circle">&nbsp;</em></a></td>
    </tr>
    <!-- END: rows -->
    </tbody>
</table>
<!-- BEGIN: list_depot -->
<div class="form-group clearfix">
    <label class="col-sm-6 control-label">{LANG.select_depot_order} <span class="error">(*)</span></label>
    <div class="col-sm-18">
        <select name="depotid" class="form-control">
            <option value="0">--{LANG.select_depot_order}--</option>
            <!-- BEGIN: loop -->
            <option{DEPOT.sl} value="{DEPOT.id}">{DEPOT.title} - {DEPOT.address}</option>
            <!-- END: loop -->
        </select>    </div>
</div>
<!-- END: list_depot -->
<div>
    <div class="clearfix">
        <table class="table text-right">
            <tr>
                <td>{LANG.cart_total_print}</td>
                <td><span class="money" id="price_total_fomart">{price_total_fomart}</span></td>
            </tr>
            <!-- BEGIN: ordertype_1 -->
            <tr>
                <td>{LANG.discount_price}</td>
                <td>
                    <input type="text" value="{price_total_discount_fomart}" readonly onkeyup="this.value=FormatNumber(this.value);update_price()" name="price_total_discount" class="form-control"/>
                    <div class="form-group">
                        <span style="font-size: 16px;color: #0FA015">{agency_info}</span>
                    </div>
                </td>
            </tr>
            <!-- BEGIN: price_payment -->
            <tr>
                <td>{LANG.price_payment}</td>
                <td><span class="money">{price_payment}</span></td>
            </tr>
            <!-- END: price_payment -->
            <tr>
                <td>{LANG.cart_total}</td>
                <td>
                    <span class="money" id="cart_total_fomart">{price_total_end_fomart}</span>
                </td>
            </tr>
            <!-- END: ordertype_1 -->
            <!-- BEGIN: ordertype_2 -->
            <tr>
                <td>{LANG.money_deposits} ({NV_DEFINE_DEPOSITS}%)</td>
                <td>
                    <input onkeyup="this.value=FormatNumber(this.value);" type="text" class="form-control" style="width: 300px;float:right" value="{price_total_discount_fomart}" name="price_payment" />
                </td>
            </tr>
            <!-- END: ordertype_2 -->
        </table>
        <div class="clear"></div>
    </div>
    <input type="hidden" name="price_total" value="{price_total}">
    <input type="hidden" name="orderid" value="{orderid}">
    <input type="hidden" name="customer_id_old" value="{customer_id}">
</div>
<script type="text/javascript" data-show="after">
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
    $(function() {
        $("a.remove_cart").click(function() {
            var href = $(this).attr("href");
            $.ajax({
                type : "GET",
                url : href,
                data : '',
                success : function(data) {
                    if (data != '') {
                        $('#table_product').load( nv_base_siteurl + "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=book-order&loadcart=1&chossentype={chossentype}&customerid={customerid}&ordertype={ordertype}" );
                        $('#item_' + data).remove();
                    }
                }
            });
            return false;
        });
    });
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
<!-- END: product -->
<!-- BEGIN: shareholder -->
<table class="table table-striped table-bordered table-hover">
    <thead class="text-center">
    <tr>
        <th rowspan="2">{LANG.stt}</th>
        <th rowspan="2">{LANG.product_title}</th>
        <th rowspan="2" id="table_product_title">{LANG.product_price}</th>
        <th class="text-center" colspan="2">{LANG.number_warehouse}</th>
        <th rowspan="2" style="width: 100px">{LANG.cart_numbers}</th>
        <th rowspan="2" style="width: 100px">{LANG.product_gift}&nbsp;<span class="info_icon" data-toggle="tooltip" title="" data-original-title="{LANG.product_gift_note}">&nbsp;</span></th>
        <th class="text-center" colspan="2">{LANG.select_warehouse_out}</th>
        <th rowspan="2">{LANG.cart_price_total}</th>
        <th rowspan="2"></th>
    </tr>
    <tr class="text-center">
        <th class="text-center" style="width: 90px">{LANG.total_tonkho_user}</th>
        <th style="width: 90px">{LANG.total_tonkho_com}</th>
        <th class="text-center" style="width: 90px">{LANG.total_tonkho_user}</th>
        <th style="width: 90px">{LANG.total_tonkho_com}</th>
    </tr>
    </thead>
    <tbody>
    <!-- BEGIN: rows -->
    <tr id="item_{DATA.id}">
        <td align="center">{DATA.stt}</td>
        <td>{DATA.title}</td>
        <!-- BEGIN: nopriceshow -->
        <td class="money text-right"><strong>{DATA.price_retail}</strong></td>
        <!-- END: nopriceshow -->
        <!-- BEGIN: priceshow -->
        <td class="text-right"><input type="text" size="1" onchange="update_price_thisproduct('{DATA.id}', this.value);"  onkeyup="this.value=FormatNumber(this.value);" value="{DATA.price_retail}" name="price_retail[{DATA.id}]" id="price_retail{DATA.id}" class="form-control"/></td>
        <!-- END: priceshow -->
        <!-- BEGIN: number_warehouse -->
        <td class="money text-right">{DATA.quantity_warehouse}</td>
        <!-- END: number_warehouse -->
        <!-- BEGIN: quantity_com -->
        <td class="money text-right">{DATA.quantity_com}</td>
        <!-- END: quantity_com -->
        <td align="center"><input type="number" size="1" onchange="update_order('{DATA.id}', this.value, '0')" value="{DATA.cartnumber}" name="listproid[{DATA.id}]" id="{DATA.id}" class="form-control"/></td>
        <td align="center">{DATA.giftproduct}</td>
        <td align="center" id="load_quantity_warehouse_{DATA.id}">{DATA.num_warehouse}</td>
        <td align="center"><input type="number" size="1" onchange="check_number_allow( this, {DATA.id}, this.value, {DATA.quantity_com}, {DATA.giftproduct} ),update_order('{DATA.id}', $('#{DATA.id}').val(), '0', {DATA.giftproduct})" value="{DATA.num_com}" id="quantity_com_{DATA.id}" name="quantity_com[{DATA.id}]" class="form-control"/></td>
        <td class="money text-right">{DATA.price_total}</td>
        <td align="center"><a class="remove_cart" title="{LANG.cart_remove_pro}" href="{DATA.link_remove}"><em style="color: red" class="fa fa-times-circle">&nbsp;</em></a></td>
    </tr>
    <!-- END: rows -->
    </tbody>
</table>
<!-- BEGIN: list_depot -->
<div class="form-group clearfix">
    <label class="col-sm-6 control-label">{LANG.select_depot_order} <span class="error">(*)</span></label>
    <div class="col-sm-18">
        <select name="depotid" class="form-control">
            <option value="0">--{LANG.select_depot_order}--</option>
            <!-- BEGIN: loop -->
            <option{DEPOT.sl} value="{DEPOT.id}">{DEPOT.title} - {DEPOT.address}</option>
            <!-- END: loop -->
        </select>    </div>
</div>
<!-- END: list_depot -->
<div>
    <div class="clearfix">
        <table class="table text-right">
            <tr>
                <td>{LANG.cart_total_print}</td>
                <td><span class="money" id="price_total_fomart">{price_total_fomart}</span></td>
            </tr>
            <!-- BEGIN: ordertype_1 -->
            <tr>
                <td>{LANG.discount_price}</td>
                <td>
                    <input type="text" value="{price_total_discount_fomart}" readonly onkeyup="this.value=FormatNumber(this.value);update_price()" name="price_total_discount" class="form-control"/>
                    <div class="form-group">
                        <span style="font-size: 16px;color: #0FA015">{agency_info}</span>
                    </div>
                </td>
            </tr>
            <!-- BEGIN: price_payment -->
            <tr>
                <td>{LANG.price_payment}</td>
                <td><span class="money">{price_payment}</span></td>
            </tr>
            <!-- END: price_payment -->
            <tr>
                <td>{LANG.cart_total}</td>
                <td><span class="money" id="cart_total_fomart">{price_total_end_fomart}</span></td>
            </tr>
            <!-- END: ordertype_1 -->
            <!-- BEGIN: ordertype_2 -->
            <tr>
                <td>{LANG.money_deposits} ({NV_DEFINE_DEPOSITS}%)</td>
                <td>
                    <input onkeyup="this.value=FormatNumber(this.value);" type="text" class="form-control" style="width: 300px;float:right" value="{price_total_discount_fomart}" name="price_payment" />
                </td>
            </tr>
            <!-- END: ordertype_2 -->
        </table>
        <div class="clear"></div>
    </div>
    <input type="hidden" name="orderid" value="{orderid}">
    <input type="hidden" name="price_total" value="{price_total}">
    <input type="hidden" name="customer_id_old" value="{customer_id}">
</div>
<script type="text/javascript" data-show="after">
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
    function check_number_allow(obj, idchecknum, value, number_allow, giftproduct){
        var number_book = $('#' + idchecknum ).val();
        number_book = parseInt( number_book ) + parseInt( giftproduct );
        if( number_allow < value){
            $(obj).val(number_allow);
            value = number_allow;
        }
        $('#load_quantity_warehouse_' + idchecknum).html(number_book - value );
    }
    $(function() {
        $("a.remove_cart").click(function() {
            var href = $(this).attr("href");
            $.ajax({
                type : "GET",
                url : href,
                data : '',
                success : function(data) {
                    if (data != '') {
                        $('#table_product').load( nv_base_siteurl + "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=book-order&loadcart=1&chossentype={chossentype}&customerid={customerid}&ordertype={ordertype}" );
                        $('#item_' + data).remove();
                    }
                }
            });
            return false;
        });
    });
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
<!-- END: shareholder -->