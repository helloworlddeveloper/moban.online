<!-- BEGIN: main -->
<!-- BEGIN: chossen_agency -->
<div class="panel-body">
    <!-- BEGIN: loop -->
    <div class="col-md-12">
        <div class="text-center item-agencey">
            <p class="title">{AGENCY.title}</p>
            <p>{percent_sale}</p>
            <!-- BEGIN: number_sale -->
            <p>{number_sale}</p>
            <!-- END: number_sale -->
            <p>{LANG.price_require}: <span style="font-size: 16px" class="money">{AGENCY.price_require_fomart} VNĐ</span></p>
            <p class="text-left">{AGENCY.bodytext}</p>
            <a href="{AGENCY.link}" class="btn btn-success">{LANG.book_product_by_agency}</a>
        </div>
    </div>
    <!-- END: loop -->
</div>
<!-- END: chossen_agency -->
<!-- BEGIN: errortitle -->
<ul class="alert alert-danger text-center">
    <!-- BEGIN: errorloop -->
    <li class="clearfix">
        {ERROR_NUMBER_PRODUCT}
    </li>
    <!-- END: errorloop -->
</ul>
<!-- END: errortitle -->

<!-- BEGIN: point_note -->
<div class="alert alert-info">
    {point_note}
</div>
<!-- END: point_note -->

<!-- BEGIN: edit_order -->
<div class="alert alert-warning">
    {EDIT_ORDER}
</div>
<!-- END: edit_order -->
<!-- BEGIN: agency -->
<div class="item-agencey">
    <p class="title">{LANG.use_for_agency}{AGENCY_CURRENT.title}</p>
    <p>{percent_sale}</p>
    <!-- BEGIN: number_sale -->
    <p>{number_sale}</p>
    <!-- END: number_sale -->
    <p>{LANG.price_require}: <span style="font-size: 16px" class="money">{AGENCY_CURRENT.price_require_fomart} VNĐ</span></p>
    <p class="text-left">{AGENCY_CURRENT.bodytext}</p>
</div>
<!-- END: agency -->
    <!-- BEGIN: data_order_cart -->
    <form action="{LINK_CART}" method="post" id="fpro">
        <input type="hidden" value="1" name="save"/>
        <div id="data_ordercart">
            <!-- BEGIN: price6 -->
            <span class="text-right help-block"><strong>{LANG.product_unit_price}:</strong> {unit_config}</span>
            <!-- END: price6 -->
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>{LANG.order_no_products}</th>
                        <th>{LANG.cart_products}</th>

                        <!-- BEGIN: main_group -->
                        <th>{MAIN_GROUP.title}</th>
                        <!-- END: main_group -->

                        <!-- BEGIN: price1 -->
                        <th class="text-right">
                            {LANG.price_one}
                            <span class="info_icon" data-toggle="tooltip" title="" data-original-title="{LANG.cart_price_note}">&nbsp;</span>
                        </th>
                        <th class="text-right">
                            {LANG.price_agency}
                        </th>
                        <!-- END: price1 -->
                        <th style="width: 80px">{LANG.cart_numbers}</th>
                        <!--<th style="width: 90px">{LANG.product_gift}&nbsp;<span class="info_icon" data-toggle="tooltip" title="" data-original-title="{LANG.product_gift_note}">&nbsp;</span></th>-->
                        <th>{LANG.cart_unit}</th>
                        <!-- BEGIN: price4 -->
                        <th class="text-right">{LANG.cart_price_total}</th>
                        <!-- END: price4 -->
                        <th>&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- BEGIN: rows -->
                    <tr id="{id}_{list_group_id}">
                        <td align="center">{stt}</td>
                        <td>
                            <a title="{title_pro}" href="{link_pro}">{title_pro}</a>
                            <!-- BEGIN: display_group -->
                            <p>
                                <!-- BEGIN: group -->
                                <span class="show"><span class="text-muted">{group.parent_title}: <strong>{group.title}</strong></span></span>
                                <!-- END: group -->
                            </p>
                            <!-- END: display_group -->
                        </td>

                        <!-- BEGIN: sub_group -->
                        <td>
                            <!-- BEGIN: loop -->
                            <a href="{SUB_GROUP.link}" title="{SUB_GROUP.title}">{SUB_GROUP.title}</a>
                            <!-- END: loop -->
                        </td>
                        <!-- END: sub_group -->

                        <!-- BEGIN: price2 -->
                        <td class="money text-right"><strong>{PRICE.sale_format}</strong></td>
                        <td class="money text-right"><strong>{PRICE.price_agency.sale_format}</strong></td>
                        <!-- END: price2 -->
                        <td align="center"><input type="number" size="1" value="{pro_num}" name="listproid[{id}_{list_group}]" id="{id}" class="form-control"/></td>
                        <!--<td align="center">{pro_gift}</td>-->
                        <td>{product_unit}</td>
                        <!-- BEGIN: price5 -->
                        <td class="money text-right">{PRICE_TOTAL.sale_format}</td>
                        <!-- END: price5 -->
                        <td align="center"><a class="remove_cart" title="{LANG.cart_remove_pro}" href="{link_remove}"><em style="color: red" class="fa fa-times-circle">&nbsp;</em></a></td>
                    </tr>
                    <!-- END: rows -->
                    </tbody>
                </table>
            </div>
            <div>
                <!-- BEGIN: price3 -->
                <div class="clearfix">
                    <div class="pull-left">
                        {LANG.cart_total}: <span id="total"></span> {unit_config}
                    </div>
                    <div class="clear"></div>
                </div>
                <!-- END: price3 -->

                <div class="row">
                    <div class="col-md-12 text-left" style="margin-top: 10px;">
                        <a title="{LANG.cart_back} {LANG.cart_page_product}" href="{LINK_PRODUCTS}"><em class="fa fa-arrow-circle-left">&nbsp;</em>{LANG.cart_back} <span>{LANG.cart_page_product}</span></a>
                    </div>
                    <div class="col-md-12 text-right" style="margin: 10px 0 10px 0">
                        <input type="submit" name="cart_update" title="{LANG.cart_update}" value="{LANG.cart_update}" class="btn btn-primary btn-sm">
                        <input type="submit" name="cart_order" title="{LANG.cart_order}" value="{LANG.cart_order}" class="btn btn-primary btn-sm">
                    </div>
                </div>
            </div>
        </div>
        <!-- BEGIN: product_list -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <caption>{LANG.chossen_product}</caption>
                <thead>
                <tr>
                    <th>{LANG.stt}</th>
                    <th>{LANG.product_title}</th>
                    <th>{LANG.price_one}</th>
                    <th>{LANG.price_agency}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td>{PRODUCT.stt}</td>
                    <td><a href="{PRODUCT.link}" title="{PRODUCT.title}">{PRODUCT.title}</a></td>
                    <td>{PRODUCT.product_price}</td>
                    <td>{PRODUCT.price_agency_format}</td>
                    <td><a href="javascript:void(0)" id="{PRODUCT.id}" title="{PRODUCT.title}" onclick="cartorder_Agency(this, 0, '{PRODUCT.link}')"><button type="button" class="btn btn-primary btn-xs">{LANG.add_product}</button></a></td>
                </tr>
                <!-- END: loop -->
                </tbody>
            </table>
        </div>
        <!-- END: product_list -->
    </form>
    <!-- END: data_order_cart -->
<script type="text/javascript" data-show="after">
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });

    var urload = nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=loadcart';
    $("#total").load(urload + '&t=2');

    $(function() {
        $("a.remove_cart").click(function() {
            var href = $(this).attr("href");
            $.ajax({
                type : "GET",
                url : href,
                data : '',
                success : function(data) {
                    if (data != '') {
                        $("#" + data).html('');
                        $("#cart_" + nv_module_name).load(urload);
                        $("#total").load(urload + '&t=2');
                    }
                }
            });
            return false;
        });
    });
</script>
<div class="msgshow" id="msgshow">&nbsp;</div>
<!-- END: main -->