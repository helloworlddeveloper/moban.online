
<!-- BEGIN: main -->
<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <div class="col-xs-24 col-sm-19 col-md-16">
                <table>
                    <tr style="margin-bottom: 10px">
                        <td width="130px">{LANG.order_name}:</td>
                        <td><strong> {DATA_CUSTOMER.order_name} </strong></td>
                    </tr>
                    <tr>
                        <td>{LANG.order_email}:</td>
                        <td>{DATA_CUSTOMER.order_email}</td>
                    </tr>
                    <tr>
                        <td>{LANG.order_phone}:</td>
                        <td>{DATA_CUSTOMER.order_phone}</td>
                    </tr>
                    <tr>
                        <td>{LANG.order_address}:</td>
                        <td>{DATA_CUSTOMER.order_address}</td>
                    </tr>
                    <tr>
                        <td>{LANG.order_date}:</td>
                        <td>{DATA_CUSTOMER.order_time}</td>
                    </tr>
                </table>
            </div>
            <div class="col-xs-24 col-sm-5 col-md-8">
                <div class="order_code text-center">
                    {LANG.order_code}
                    <br>
                    <span class="text_date"><strong>{DATA_CUSTOMER.order_code}</strong></span>
                    <br>
                    <span class="payment">{payment}</span>
                </div>
            </div>
        </div>
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
    <th class="text-center">{LANG.number_return}</th>
    <th class="text-center">{LANG.type_return}</th>
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
    <td class="money text-right">{DATA.numreturned}</td>
    <td class="money text-right"><strong>{DATA.price_format}</strong></td>
    <td class="text-center">{DATA.num_return}</td>
    <td class="text-center">{DATA.type_return}</td>
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
                    <span class="money" id="price_total_fomart">{price_total_discount_fomart}</span>
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
            <tr>
                <td>{LANG.cart_total}</td>
                <td><span class="money">{congno}</span></td>
            </tr>
        </table>
        <div class="clear"></div>
        <div class="text-center">
            <form class="form-inline" action="" method="post" name="fpost" id="post">
                <input type="hidden" value="{order_id}" name="order_id" /><input type="hidden" value="1" name="save">
                <!-- BEGIN: onpay -->
                {LANG.input_price_order_return}
                <input type="text" onkeyup="this.value=FormatNumber(this.value);" class="form-control" placeholder="{LANG.payment_amount}" value="{payment_amount}" name="payment_amount" />
                <input class="btn btn-primary" type="button" value="{LANG.order_submit_pay}" id="click_pay">
                <br><br>
                <!-- END: onpay -->
                <input class="btn btn-info" type="button" value="{LANG.order_print}" id="click_print">
            </form>
        </div>
    </div>
    <!-- BEGIN: transaction -->
    <table class="table table-striped table-bordered table-hover">
        <caption>
            {LANG.history_transaction}
        </caption>
        <thead>
        <tr>
            <th width="30px">&nbsp;</th>
            <th>{LANG.payment_time}</th>
            <th>{LANG.user_payment}</th>
            <th>{LANG.status}</th>
            <th class="text-right">{LANG.order_total}</th>
            <th class="text-right">{LANG.transaction_time}</th>
        </tr>
        </thead>
        <tbody>
        <!-- BEGIN: loop -->
        <tr>
            <td class="text-center" width="30px">{DATA_TRANS.a}</td>
            <td>{DATA_TRANS.payment_time}</td>
            <td>{DATA_TRANS.payment}</td>
            <td>{DATA_TRANS.transaction}</td>
            <td align="right">{DATA_TRANS.payment_amount}</td>
            <td align="right">{DATA_TRANS.transaction_time}</td>
        </tr>
        <!-- END: loop -->
        </tbody>
    </table>
    <!-- END: transaction -->
</div>
<script type="text/javascript">
    $('#click_print').click(function(event) {
        event.preventDefault();
        nv_open_browse('{LINK_PRINT}', '', 640, 300, 'resizable=no,scrollbars=yes,toolbar=no,location=no,status=no');
        return false;
    });
    $('#click_pay').click(function(event) {
        event.preventDefault();
        if (confirm("{LANG.order_submit_pay}")) {
            nv_settimeout_disable('click_pay', 50000);
            $.ajax({
                type : "POST",
                url : '{URL_ACTIVE_PAY}',
                data : 'save=1&action=pay&payment_amount=' + $('input[name=payment_amount]').val(),
                success : function(data) {
                    alert(data);
                    window.location.href = window.location.href;
                }
            });
        }
    });
</script>
<!-- END: main -->