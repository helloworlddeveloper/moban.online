<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">

<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>

<form action="{LINK_CART}" method="post" id="fpro">
<div class="panel panel-default">
    <div class="panel-body">
        <div class="funkyradio row">
            <p><strong>{LANG.select_book_type}</strong></p>
            <!--
            <div class="col-md-8 col-sm-8 funkyradio-success">
                <input type="radio" id="radio1" name="chossentype" value="1"{chossentype_1} />
                <label for="radio1">{LANG.bookforyou}</label>
            </div>
            -->
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
        </div>

        <div class="form-group">
            <div class="col-sm-18">
                <span id="create_customer"></span>
                <div class="clear"></div>
            </div>
        </div>
        <script type="text/javascript">
            $('input[name=chossentype]').click(function () {
                change_action_chossentype($(this).val());
            })

            function check_info_customer() {
                if( $('input[name=customer_id]').length == 0 || $('input[name=customer_id]').val() == 0){
                    if( $('input[name=chossentype]:checked').val() == 3 ){
                        $('#create_customer').html('<i>{LANG.create_customer_one}</i>');
                    }
                }
            }
            function change_action_chossentype(vtype) {
                if( vtype == 1){
                    var html = "<span title=\"{USERINFO.fullname}\" class=\"uiToken removable\">{USERINFO.fullname}<input type=\"hidden\" value=\"{USERINFO.userid}\" name=\"customer_id\" autocomplete=\"off\"><a onclick=\"$(this).parent().remove();\" href=\"javascript:void(0);\" class=\"remove uiCloseButton uiCloseButtonSmall\"></a></span>";
                    $("#userid").html( html );
                    $('#order_name').val('{USERINFO.fullname}');
                    $('#order_phone').val('{USERINFO.datatext.mobile}');
                    $('#order_email').val('{USERINFO.email}');
                    $('#order_address').val('{USERINFO.datatext.address}');
                    $('#create_customer').html('<div style="color:#5cb85c;font-size:16px;">{LANG.percent_sale_agency}</div>');
                    $('#table_product').load( nv_base_siteurl + "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=book-order&loadcart=1&chossentype=" + $('input[name=chossentype]:checked').val() + '&customerid={USERINFO.userid}' );
                }else{
                    $("#userid").html( '' );
                    $('#order_name').val('');
                    $('#order_phone').val('');
                    $('#order_email').val('');
                    $('#order_address').val('');
                    $('#create_customer').html('');
                    $('#table_product').load( nv_base_siteurl + "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=book-order&loadcart=1&chossentype=" + $('input[name=chossentype]:checked').val() );
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
                $('#order_name').val(data.fullname);
                $('#order_phone').val(data.phone);
                $('#order_email').val(data.email);
                $('#order_address').val(data.address);
                $('#table_product').load( nv_base_siteurl + "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=book-order&loadcart=1&chossentype=" + $('input[name=chossentype]:checked').val() + '&customerid=' + data.key );
                numitem = 0;
                return false;
            }
            function split(val) {
                return val.split(/,\s*/);
            }
            function extractLast(term) {
                return split(term).pop();
            }
        </script>
        <div class="form-group">
            <label class="col-sm-6 control-label">{LANG.order_name} <span class="error">(*)</span></label>
            <div class="col-sm-18">
                <p class="form-control-static"><input name="order_name" id="order_name" class="form-control" value="{DATA.order_name}" /></p>
                <span class="error">{ERROR.order_name}</span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-6 control-label">{LANG.order_phone} <span class="error">(*)</span></label>
            <div class="col-sm-18">
                <p class="form-control-static"><input name="order_phone" id="order_phone" class="form-control" value="{DATA.order_phone}" /></p>
                <span class="error">{ERROR.order_phone}</span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-6 control-label">{LANG.order_email}</label>
            <div class="col-sm-18">
                <p class="form-control-static"><input type="email" id="order_email" name="order_email" value="{DATA.order_email}" class="form-control" /></p>
                <span class="error">{ERROR.order_email}</span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-6 control-label">{LANG.order_address} </label>
            <div class="col-sm-18">
                <p class="form-control-static"><input name="order_address" id="order_address" class="form-control" value="{DATA.order_address}" /></p>
                <span class="error">{ERROR.order_address}</span>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-18">
                <label><input name="order_shipcod" type="checkbox" class="form-control"{order_shipcod} value="{DATA.order_shipcod}" />{LANG.order_shipcod}</label>
            </div>
        </div>
    </div>
</div>
<!-- BEGIN: saleoff -->
<div class="form-group">
    <label class="col-sm-6 control-label">{LANG.saleoff} </label>
    <div class="col-sm-18">
        <select name="saleoff" class="form-control">
            <option value="0">--{LANG.select_saleoff}--</option>
            <!-- BEGIN: loop -->
            <option{SALE_OFF.sl} value="{SALE_OFF.id}" percent="{SALE_OFF.percent}">{SALE_OFF.title} - {SALE_OFF.percent}%</option>
            <!-- END: loop -->
        </select>
    </div>
</div>
<!-- END: saleoff -->
<div class="error text-center">{ERROR.number_in_warehouse}</div>
<script type="text/javascript">
    $("select[name=saleoff]").change(function(){
        var urloadcart = nv_base_siteurl + "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=book-order&loadcart=1&chossentype=" + $('input[name=chossentype]:checked').val() + '&customerid=' + $('input[name=customer_id]').val() + '&agencyid=' + $('select[name=agencyid]').val();
        $('#table_product').load( urloadcart + '&saleoff=' + $(this).val());
    });
    $('select[name=agencyid]').click(function () {
        $('#radio2').prop('checked',true);
    })
    $('#usersearch').focus(function () {
        $('select[name=agencyid]').val(0);
        $('#radio3').prop('checked',true);
    })
    $('select[name=agencyid]').change(function () {
        if( $(this).val() > 0 ){
            //$('#create_customer').html('<div style="color:#5cb85c;font-size:16px;">' + $('option:selected', this).attr('data-info') + '</div>');
        }else{
            $('#create_customer').html('');
        }
        $('#order_name').val($('option:selected', this).attr('data-fullname'));
        $('#order_phone').val($('option:selected', this).attr('data-phone'));
        $('#order_email').val($('option:selected', this).attr('data-email'));
        $('#order_address').val($('option:selected', this).attr('data-address'));
        $("#userid").html( '' );

        $('#table_product').load( nv_base_siteurl + "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=book-order&loadcart=1&chossentype=" + $('input[name=chossentype]:checked').val() + '&customerid=' + $(this).val() + '&saleoff=' + $('select[name=saleoff]').val());
    })
    $('#table_product').load( nv_base_siteurl + "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=book-order&loadcart=1&chossentype={DATA.chossentype}&customerid={DATA.customer_id}&agencyid={DATA.agencyid}&saleoff={DATA.saleoff}&price_total_discount={DATA.price_total_discount}" );
</script>
    <input type="hidden" value="1" name="save"/>
    <div class="table-responsive" id="table_product"></div>
    <div class="row">
        <div class="col-md-24 text-center" style="margin: 10px 0 10px 0">
            <input type="submit" name="book_order" title="{LANG.cart_order}" value="{LANG.cart_order}" class="btn btn-success btn-sm">
        </div>
    </div>
</form>
<script type="text/javascript" data-show="after">
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
    function update_order(productid, number, isgift ) {
        $.get( nv_base_siteurl + "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=book-order&setcart=1&id=" + productid + '&num='+ number + '&isgift=' + isgift, function(res) {
            $('#table_product').load( nv_base_siteurl + "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=book-order&loadcart=1&chossentype=" + $('input[name=chossentype]:checked').val() + '&customerid=' + $('input[name=customer_id]').val() + '&agencyid=' + $('select[name=agencyid]').val() + '&saleoff=' + $('select[name=saleoff]').val());
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
        <th style="width: 90px">{LANG.number_warehouse}&nbsp;<span class="info_icon" data-toggle="tooltip" title="" data-original-title="{LANG.number_warehouse_note}">&nbsp;</span></th>
        <!-- END: number_warehouse -->
        <th style="width: 100px">{LANG.cart_numbers}</th>
        <!-- BEGIN: product_gift -->
        <th style="width: 90px">{LANG.product_gift}&nbsp;<span class="info_icon" data-toggle="tooltip" title="" data-original-title="{LANG.product_gift_note}">&nbsp;</span></th>
        <!-- END: product_gift -->
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
        <td class="money text-right"><strong>{DATA.price_retail}</strong></td>
        <!-- BEGIN: number_warehouse -->
        <td class="money text-right">{DATA.quantity_warehouse}</td>
        <!-- END: number_warehouse -->
        <td align="center"><input type="number" size="1" onchange="update_order('{DATA.id}', this.value, '0')" value="{DATA.cartnumber}" name="listproid[{DATA.id}]" id="{DATA.id}" class="form-control"/></td>
        <!-- BEGIN: product_gift --><td align="center">{pro_gift}</td><!-- END: product_gift -->
        <td align="center"><input type="numgif" size="1" onchange="update_order('{DATA.id}', this.value, '1')" value="{DATA.numgif}" name="listproid[{DATA.id}]" id="{DATA.id}" class="form-control"/></td>
        <td class="money text-right">{DATA.price_total}</td>
        <td align="center"><a class="remove_cart" title="{LANG.cart_remove_pro}" href="{DATA.link_remove}"><em style="color: red" class="fa fa-times-circle">&nbsp;</em></a></td>
    </tr>
    <!-- END: rows -->
    </tbody>
</table>
<div>
    <div class="clearfix">
        <table class="table text-right">
            <tr>
                <td>{LANG.cart_total_print}</td>
                <td><span class="money" id="price_total_fomart">{price_total_fomart}</span></td>
            </tr>
            <tr>
                <td>{LANG.discount_price}</td>
                <td><input type="text" value="{price_total_discount_fomart}" onkeyup="this.value=FormatNumber(this.value);update_price()" name="price_total_discount" class="form-control"/></td>
            </tr>
            <tr>
                <td>{LANG.cart_total}</td>
                <td><span class="money" id="cart_total_fomart">{price_total_end_fomart}</span></td>
            </tr>
        </table>
        <div class="clear"></div>
    </div>

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
                        $('#table_product').load( nv_base_siteurl + "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=book-order&loadcart=1&chossentype={chossentype}&customerid={customerid}" );
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