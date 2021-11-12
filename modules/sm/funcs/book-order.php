<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if( !defined( 'NV_IS_MOD_SM' ) )
{
    die( 'Stop!!!' );
}

$data_order = array( 'chossentype' => 0, 'ordertype' => 0, 'customer_id' => 0, 'order_shipcod' => 0 );
$order_old = $error = array( );
$coupons_code = '';
$coupons_check = 0;
$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=';
$custype = $nv_Request->get_int('custype', 'post,get', 0);

if( $nv_Request->isset_request('change_price', 'get') )
{
    $array_data = array();
    $price_total = $nv_Request->get_int('price_total', 'post,get', 1);
    $chossentype = $nv_Request->get_int('chossentype', 'post,get', 0);
    $array_data['cart_total_fomart'] = $price_total;
    if( $chossentype == 3 ){
        $percent = $nv_Request->get_int('percent', 'post,get', 1);
        $array_data['price_discount_price'] = ($price_total * $percent / 100);
        $array_data['cart_total_fomart'] = $price_total - $array_data['price_discount_price'];

        $array_data['cart_total_fomart'] = number_format( $array_data['cart_total_fomart'], 0, '.', ',');
        $array_data['price_discount_price'] = number_format( $array_data['price_discount_price'], 0, '.', ',');
        $price_total = number_format( $price_total, 0, '.', ',');
        $array_data['price_total'] = $price_total;

    }else{
        $price_total = number_format( $price_total, 0, '.', ',');
        $array_data['price_total'] = $price_total;
        $array_data['price_discount_price'] = 0;
        $array_data['cart_total_fomart'] = $price_total;
    }


    header( 'Cache-Control: no-cache, must-revalidate' );
    header( 'Content-type: application/json' );

    ob_start( 'ob_gzhandler' );
    echo json_encode( $array_data );
    exit();
}
if( $nv_Request->isset_request('setprice', 'get') )
{
    $id = $nv_Request->get_int('id', 'post,get', 1);
    $price = $nv_Request->get_title('price', 'post,get', '');
    $price = str_replace(',', '', $price );
    $_SESSION[$module_data . '_cart'][$id]['price'] = $price;
    include NV_ROOTDIR . '/includes/header.php';
    echo $price;
    include NV_ROOTDIR . '/includes/footer.php';
    exit;
}

if( $nv_Request->isset_request('setcart', 'get') )
{
    if (! isset($_SESSION[$module_data . '_cart'])) {
        $_SESSION[$module_data . '_cart'] = array();
    }
    $_SESSION[$module_data . '_cart']['updated'] = 1;
    $id = $nv_Request->get_int('id', 'post,get', 1);
    $num = $nv_Request->get_int('num', 'post,get', 1);
    $giftproduct = $nv_Request->get_int('giftproduct', 'post,get', 1);
    $num_com = $nv_Request->get_int('num_com', 'post,get', 1);
    $isgift = $nv_Request->get_int('isgift', 'post,get', 1);
    $ordertype = $nv_Request->get_int( 'ordertype', 'get', 1 );//1=dat hang,2 dat truoc
    $contents_msg = "";

    $total_num = $num + $giftproduct;
    $num_com = ( $num_com > $total_num )? $total_num : $num_com;

    if (! is_numeric($num) || $num < 0) {
        $contents_msg = 'ERR_' . $lang_module['cart_set_err'];
    } elseif ($id > 0) {
        $check_num = $quantity_allow = 0;
        if( $user_data_affiliate['agencyid'] > 0 && $ordertype == 1 ){
            $check_num = 1;
            $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_warehouse_logs WHERE customerid =" . $user_data_affiliate['userid'] . ' AND productid=' . $id;
            $result = $db->query( $sql );
            $row = $result->fetch( );
            //tong so luong trong kho
            $quantity_allow = $row['quantity_in'] - $row['quantity_out'];
        }

        $result = $db->query("SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_product WHERE id = " . $id);
        $data_content = $result->fetch();
        $_SESSION[$module_data . '_cart'][$id]['num_com'] = $num_com;
        if ($check_num ==1 && $num > $quantity_allow ) {
            $_SESSION[$module_data . '_cart'][$id]['num'] = $quantity_allow;
        } else {
            if( !$isgift){
                $_SESSION[$module_data . '_cart'][$id]['num'] = $num;
            }else{
                $_SESSION[$module_data . '_cart'][$id]['numgif'] = $num;
            }
        }
    }
    $contents_msg = 'OK_update';
    include NV_ROOTDIR . '/includes/header.php';
    echo $contents_msg;
    include NV_ROOTDIR . '/includes/footer.php';
    exit;
}


if( $nv_Request->isset_request('loadcart', 'get') )
{
    $agency_of_you = array();
    $chossentype = $nv_Request->get_int( 'chossentype', 'get', 1 );//loai nhap hang 1,2 la cho DL, NPP..., 3 = cho khach le
    $ordertype = $nv_Request->get_int( 'ordertype', 'get', 1 );//1=dat hang,2 dat truoc
    $customerid = $nv_Request->get_int( 'customerid', 'get', 0 );//ID cua khach hang nhap hang
    $orderid = $nv_Request->get_int( 'orderid', 'get', 0 );//ID cua khach hang nhap hang
    $agencyid = $nv_Request->get_int( 'agencyid', 'get', 0 );//ID loai agencyid nhap hang
    $price_total_discount = $nv_Request->get_title( 'price_total_discount', 'get', 0 );//ID giam gia theo %
    $price_payment = $nv_Request->get_title( 'price_payment', 'get', 0 );//so tien dat coc
    $sale_off_checked = array();
    if( isset( $array_saleoff[$saleoff] )){
        $sale_off_checked = $array_saleoff[$saleoff];
    }
    if( $agencyid > 0 ){
        $customerid = $agencyid;//neu agencyid ma dc chon thi gan customer_id bang agencyid
        $chossentype = 2;
    }

    if( $customerid != $_SESSION[$module_data . '_customerid'] ){
        $_SESSION[$module_data . '_customerid'] = $customerid;
        unset($_SESSION[$module_data . '_cart']);
    }

    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_product WHERE status =1 ORDER BY weight";
    $result = $db->query( $sql );
    $array_product = array();
    while( $row = $result->fetch( ) )
    {
        if( !isset( $_SESSION[$module_data . '_cart'][$row['id']] )){
            $_SESSION[$module_data . '_cart'][$row['id']]['num'] = 0;
            $_SESSION[$module_data . '_cart'][$row['id']]['numgif'] = 0;
        }
        $array_product[$row['id']] = $row;
    }

    if( $chossentype != 3 ){
        if( $customerid > 0 ){
            $sql = 'SELECT agencyid FROM ' . NV_TABLE_AFFILIATE . '_users WHERE userid=' . $customerid;
            list( $agencyid ) = $db->query($sql)->fetch(3);
            if( $agencyid > 0 ){
                $agency_of_you = $array_agency[$agencyid];
            }
        }elseif( $agencyid == 0 ){
            exit('');
        }else{
            $agency_of_you = $array_agency[$agencyid];
        }
        if( $agency_of_you['price_for_discount'] > 0 and $agency_of_you['price_discount'] >0 ){
            $agency_of_you['agency_info'] = sprintf( $lang_module['info_discount_price_agency'], number_format( $agency_of_you['price_for_discount'], 0, '.', ',' ), number_format( $agency_of_you['price_discount'], 0, '.', ',' ));
        }
    }

    $show_quantity_warehouse = false;
    //kiem tra so trong kho tuyen tren
    if( $user_data_affiliate['agencyid'] > 0 ){
        $show_quantity_warehouse = true;
        $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_warehouse_logs WHERE customerid =" . $user_data_affiliate['userid'];
        $result = $db->query( $sql );
        $array_product_warehouse = array();
        while( $row = $result->fetch( ) )
        {
            $quantity = $row['quantity_in']  - ($row['quantity_out'] + $row['quantity_com']);
            $array_product[$row['productid']]['quantity_warehouse'] = $quantity;
            $array_product[$row['productid']]['quantity_com'] = $row['quantity_com'];
        }
    }
    $data_order = array( 'price_payment' => $price_payment );
    if( $orderid > 0 ){
        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_orders WHERE ordertype=2 AND user_id =' . $user_info['userid'] . ' AND order_id=' . $orderid;
        $result = $db->query($sql);
        $data_order = $result->fetch();
        if( !empty( $data_order )){
            if( $_SESSION[$module_data . '_cart']['updated']  != 1){
                $sql_i = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_orders_id WHERE order_id=' . $orderid;
                $result_i = $db->query($sql_i);
                while ( $row_i = $result_i->fetch()){
                    if( $row_i['isgift'] == 0 ){
                        $_SESSION[$module_data . '_cart'][$row_i['proid']]['num'] = $row_i['num'];
                    }else{
                      //  $_SESSION[$module_data . '_cart'][$row_i['proid']]['giftproduct'] = $row_i['num'];
                    }
                }
            }

        }

    }
    $total_price = $total =  0;
    $data_content = array();
    foreach( $_SESSION[$module_data . '_cart'] as $pro_id => $pro_info )
    {
        if( intval( $pro_id ) > 0 ){
            $row = $array_product[$pro_id];
            $number = $_SESSION[$module_data . '_cart'][$row['id']]['num'];

            if( $number > $row['pnumber'] and $number > 0 )
            {
                if( $number < $row['pnumber'] ){
                    $_SESSION[$module_data . '_cart'][$row['id']]['num'] = $number;
                }else{
                    $number = $_SESSION[$module_data . '_cart'][$row['id']]['num'] = $row['pnumber'];
                }
            }

            $row['price_sale'] = -1;
            if( $row['priceshow'] == 1 && $_SESSION[$module_data . '_cart'][$row['id']]['price'] > 0 ){
                //lay gia tuy bien neu co
                $row['price_sale'] = $_SESSION[$module_data . '_cart'][$row['id']]['price'];
            }
            $row['link_remove'] =  $link . 'remove&id=' . $row['id'];
            $row['cartnumber'] = $number;
            $row['num_com'] = $_SESSION[$module_data . '_cart'][$row['id']]['num_com'];
            $row['num_warehouse'] = $number -  $row['num_com'];
            $row['giftproduct'] = 0;
            $data_content[$pro_id] = $row;

            //lay tong tin de tinh chiet khau qua tang
            if( $chossentype == 3){
                //gia tuy bien
                if( $row['price_sale'] != -1){
                    $row['price_total'] = $row['price_sale'] * $row['cartnumber'];
                    $total = $total + $data_row['price_total'];
                }
                else{
                    $row['price_total'] = $row['price_retail'] * $row['cartnumber'];
                    $total = $total + $data_row['price_total'];
                }
            }else{

                if( $row['price_sale'] != -1){
                    $row['price_total'] = $row['price_sale'] * $row['cartnumber'];
                    $row['price_retail'] = $row['price_sale'];
                    $total = $total + $row['price_total'];
                }
                else{
                    $row['price_total'] = nv_get_price_for_agency( $row['price_retail'], $row['id'], $row['cartnumber'], true );
                    $total = $total + $row['price_total'];
                }
            }
        }
    }

    if( $data_order['chossentype'] != 3 ){
        $sql_check_gift = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_saleoff AS t1 INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_saleoff_detail AS t2 ON t1.id=t2.saleoffid WHERE salesfrom<' . $total . ' AND salesto>=' . $total;
        $gif_info_array = $db->query($sql_check_gift)->fetchAll();
        if( !empty( $gif_info_array )){
            foreach ( $gif_info_array as $gif_info ){
                $numbergift = floor( $total / $gif_info['moneyrequire'] ) * $gif_info['numbergift'];
                $data_content[$gif_info['productid']]['giftproduct'] = $numbergift;
                $data_content[$gif_info['productid']]['num_warehouse'] += $numbergift;
            }
        }
    }

    //number_warehouse
    $contents = cart_product_load($data_content, $agency_of_you, $chossentype, $ordertype, $customerid, $data_order, $show_quantity_warehouse );
    include NV_ROOTDIR . '/includes/header.php';
    echo $contents;
    include NV_ROOTDIR . '/includes/footer.php';
    exit;
}
$data_order['orderid'] = 0;
if( $nv_Request->isset_request('submit', 'post') ) {

    $data_order['order_name'] = nv_substr($nv_Request->get_title('order_name', 'post', '', 1), 0, 200);
    $data_order['order_email'] = nv_substr($nv_Request->get_title('order_email', 'post', '', 1), 0, 250);
    $data_order['order_phone'] = nv_substr($nv_Request->get_title('order_phone', 'post', '', 1), 0, 20);
    $data_order['order_address'] = nv_substr($nv_Request->get_title('order_address', 'post', '', 1), 0, 255);
    $data_order['order_note'] = nv_substr($nv_Request->get_title('order_note', 'post', '', 1), 0, 2000);
    $data_order['price_total_discount'] = $nv_Request->get_title('price_total_discount', 'post', 0);
    $data_order['customer_id'] = $nv_Request->get_int('customer_id', 'post', 0);
    $data_order['chossentype'] = $nv_Request->get_int('chossentype', 'post', 0);
    $data_order['agencyid'] = $nv_Request->get_int('agencyid', 'post', 0);
    $data_order['ordertype'] = $nv_Request->get_int('ordertype', 'post', 0);
    $data_order['order_shipcod'] = intval( $nv_Request->isset_request('order_shipcod', 'post') );
    $data_order['orderid'] = $nv_Request->get_int('orderid', 'post', 0);//id don hang dat truoc
    $data_order['depotid'] = $nv_Request->get_int('depotid', 'post', 0);//id kho hang
    $data_order['price_payment'] = $nv_Request->get_title('price_payment', 'post', 0);
    $order_info = array();
    $data_order['price_total'] = $nv_Request->get_int('price_total', 'post', 0);
    $data_order['price_total_discount'] = str_replace(',', '', $data_order['price_total_discount'] );
    $data_order['price_payment'] = str_replace(',', '', $data_order['price_payment'] );

    if( $data_order['agencyid'] > 0 ){
        $data_order['customer_id'] = $data_order['agencyid'];//neu agencyid ma dc chon thi gan customer_id bang agencyid
        $data_order['chossentype'] = 2;
    }
    //neu sua tu don hang dat truoc thi lay customer_id tu don hang dat truoc
    if( $data_order['orderid'] > 0 ){
        $data_order['customer_id'] = $nv_Request->get_int('customer_id_old', 'post', 0);
    }
    if( $data_order['customer_id'] == 0 && ( empty( $data_order['order_name'] ) or empty( $data_order['order_phone'] ) ) )
    {
        $error['order_name'] = $lang_module['error_customer_info'];
    }

    if( $data_order['agencyid'] > 0){
        $sql = 'SELECT * FROM ' . NV_TABLE_AFFILIATE . '_users WHERE userid=' . $data_order['agencyid'];
       $data_user_info = $db->query($sql)->fetch();
    }
    $saleoff = $discount = $check_warehouse = 0;
    //kiem tra check kho hang o dau = 1 la check kho tong cua cty
    if( $user_data_affiliate['agencyid'] == 0 ){
        $check_warehouse = 1;
    }

    //kiem tra doi tuong dat hang de giam gia theo %
    if( $data_order['chossentype'] != 3 ){
        if( $data_order['agencyid'] > 0){
            $sql = 'SELECT * FROM ' . NV_TABLE_AFFILIATE . '_users WHERE userid=' . $data_order['agencyid'];
            $data_user_info = $db->query($sql)->fetch();
            if( $data_user_info['agencyid'] > 0 ){
                $agency_of_you = $array_agency[$data_user_info['agencyid']];
                //$discount = $agency_of_you['percent_sale'];
            }else{
                $agency_of_you = array();
            }
        } elseif( $data_order['customer_id'] > 0){
            $sql = 'SELECT * FROM ' . NV_TABLE_AFFILIATE . '_users WHERE userid=' . $data_order['customer_id'];
            $data_user_info = $db->query($sql)->fetch();
            if( $data_user_info['agencyid'] > 0 ){
                $agency_of_you = $array_agency[$data_user_info['agencyid']];
                //$discount = $agency_of_you['percent_sale'];
            }else{
                $agency_of_you = array();
            }
        }
    }
    $data_order['saleoff'] = 0;
    //giam gia cho theo % phan saleoff
    if( $data_order['saleoff'] > 0 && isset( $array_saleoff[$data_order['saleoff']] ) ){
        $saleoff = $array_saleoff[$data_order['saleoff']]['percent'];
    }

    $error_warehouse = 0;
    if( $check_warehouse == 1 && $data_order['depotid'] == 0 ){
        $error[] = $lang_module['error_depotid_not_select'];
        $error_warehouse = 1;
    }else{
        $data_order_old = array();
        if($data_order['orderid'] > 0){
            $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_orders WHERE ordertype=2 AND user_id =' . $user_info['userid'] . ' AND order_id=' . $data_order['orderid'];
            $result = $db->query($sql);
            $data_order_old = $result->fetch();
        }
        if(empty($data_order_old) || $data_order_old['chossentype'] != 3 ){
            $sql_check_gift = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_saleoff AS t1 INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_saleoff_detail AS t2 ON t1.id=t2.saleoffid WHERE salesfrom<' . $data_order['price_total'] . ' AND salesto>=' . $data_order['price_total'];
            $gif_info_array = $db->query($sql_check_gift)->fetchAll();
            $array_gif = array();
            if( !empty( $gif_info_array )){
                foreach ( $gif_info_array as $gif_info ){
                    $numbergift = floor( $data_order['price_total'] / $gif_info['moneyrequire'] ) * $gif_info['numbergift'];
                    $_SESSION[$module_data . '_cart'][$gif_info['productid']]['giftproduct'] = $numbergift;
                    $array_gif[$gif_info['productid']] = $numbergift;
                }
            }
        }

        $total_price = 0;
        foreach( $_SESSION[$module_data . '_cart'] as $pro_id => $pro_info )
        {
            if( intval( $pro_id ) > 0 ){
                $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_product WHERE id = " . $pro_id . " AND status =1";
                $result = $db->query( $sql );
                $total_number = 0;
                while( $data_row = $result->fetch( ) )
                {
                    $number = $_SESSION[$module_data . '_cart'][$data_row['id']]['num'];
                    $numbergift = isset( $array_gif[$pro_id] )? intval( $array_gif[$pro_id] ) : 0;

                    $total_number = $number + $numbergift;
                    $data_row['number_gift'] = $numbergift;
                    $data_row['cartnumber'] = $number;
                    $data_row['num_com'] = $_SESSION[$module_data . '_cart'][$data_row['id']]['num_com'];

                    $data_row['price_sale'] = -1;
                    if( $data_row['priceshow'] == 1 && $_SESSION[$module_data . '_cart'][$data_row['id']]['price'] > 0 ){
                        //lay gia tuy bien neu co
                        $data_row['price_sale'] = $_SESSION[$module_data . '_cart'][$data_row['id']]['price'];
                        $data_row['price_retail'] = $data_row['price_sale'];
                    }
                    if( $data_order['chossentype'] == 3){
                        if( $data_row['price_sale'] != -1){
                            $data_row['price_total'] = $data_row['price_sale'] * $data_row['cartnumber'];
                        }else{
                            $data_row['price_total'] = $data_row['price_retail'] * $data_row['cartnumber'];
                        }

                        $total_price = $total_price + $data_row['price_total'];
                    }else{
                        if( $data_row['price_sale'] != -1){
                            $data_row['price_total'] = $data_row['price_sale'] *  $data_row['cartnumber'];
                            $data_row['price_retail'] = $data_row['price_sale'];
                            $total_price = $total_price + $data_row['price_total'];
                        }
                        else{
                            if( $data_order_old['chossentype'] != 3 ){
                                $data_row['price_total'] = nv_get_price_for_agency( $data_row['price_retail'], $data_row['id'], $data_row['cartnumber'], true );
                                $data_row['price_retail'] = nv_get_price_for_agency( $data_row['price_retail'], $data_row['id'], $data_row['cartnumber'] );
                            }else{
                                $data_row['price_total'] = $data_row['price_retail'] * $data_row['cartnumber'];
                            }
                            $total_price = $total_price + $data_row['price_total'];
                        }
                    }

                    $total_num_product = checkNumTotalInParentCustomer( $data_order['customer_id'], $data_order['depotid'], $pro_id, $check_warehouse );

                    $data_content[$data_row['id']] = $data_row;

                    //kiem tra trong kho nhanh tren co du hang de nhap khong
                    if( $total_num_product < $total_number && $data_order['ordertype'] == 1 ){
                        $error['number_in_warehouse'] = sprintf( $lang_module['number_in_warehouse_logs_error'], $data_row['title'], $total_num_product );
                    }elseif ( $data_row['num_com'] > $total_number && $data_order['ordertype'] == 1 ){
                        //neu so lg hang trong kho cty can xuat lon hon so sp xuat se bao loi
                        $error['number_in_warehouse'] = sprintf( $lang_module['number_in_warehouse_logs_com_error'], $data_row['title'] );
                    }elseif( $user_data_affiliate['shareholder'] == 1 && $data_order['ordertype'] == 1 ){
                        //kiem tra xem co phai co dong khong thi se check so luong hang trong kho cty
                        $sql = "SELECT quantity_com, quantity_in, quantity_out FROM " . NV_PREFIXLANG . "_" . $module_data . "_warehouse_logs WHERE customerid =" . $user_data_affiliate['userid'] . ' AND productid=' . $data_row['id'];
                        $result = $db->query( $sql );
                        list( $quantity_com, $quantity_in, $quantity_out ) = $result->fetch( 3 );
                        $quantity_allow =  $quantity_in - $quantity_out - $quantity_com;

                        if( $quantity_com < $data_row['num_com'] ){
                            $error['number_in_warehouse'] = sprintf( $lang_module['number_in_warehouse_logs_com2_error'], $data_row['title'], $quantity_com );
                        }elseif( $quantity_allow < ($data_row['cartnumber'] + $data_row['number_gift']) - $data_row['num_com'] ){
                            $error['number_in_warehouse'] = sprintf( $lang_module['number_in_warehouse_logs_error'], $data_row['title'], $quantity_allow );
                        }
                    }
                }
            }
        }
    }

    if( $data_order['customer_id'] == 0 && !empty( $data_order['order_phone'] ) && !empty( $data_order['order_name'] ) && empty( $error ) ){
        //$data_order['chossentype'] sua code chi tao tk cho khach le
        $result_return = createCustomer( 3, $data_order['order_name'], $data_order['order_email'], $data_order['order_phone'], $data_order['order_address'], $data_order['agencyid'], $user_info['userid'] );
        if( $result_return['customer_id'] > 0 ){
            $data_order['customer_id'] = $result_return['customer_id'];
        }else{
            $error[] = $result_return['mess_error'];
        }
    }

    $data_order['price_total_discount'] = 0;
    if( $agency_of_you['price_for_discount'] > 0 and $agency_of_you['price_discount'] >0 && $data_order['ordertype'] == 1){
        $data_order['price_total_discount'] = floor($total_price / $agency_of_you['price_for_discount']) * $agency_of_you['price_discount'];
    }

    $total_check_price_require = $total_price;//so tien de check don hang toi thieu
    if( $data_order['price_total_discount'] > 0 ){
        $total_price = $total_price - $data_order['price_total_discount'];
    }
    $data_order['order_total'] = $total_price;

    //tam thoi comment cho cac bac nhap hang
    $price_require = $agency_of_you['price_require'];// gia nhap bat buoc trong 1 thang cua agency

    $percent_book_one = $module_config[$module_name]['percent_book_one']; //ti le % gia tri don hang moi lan nhap
    if( $error_warehouse == 0 && $total_check_price_require < $price_require ){
        $error['number_in_warehouse'] = sprintf( $lang_module['error_percent_book_first'], $agency_of_you['title'], number_format( $agency_of_you['price_require'], 0, '.', ','));
    }
    $price_payment = 0;
    if( $data_order['ordertype'] == 2 ){
        $price_payment = ($data_order['order_total'] / 100) * NV_DEFINE_DEPOSITS;
        if( $data_order['price_payment'] < $price_payment ){
            $error[] = sprintf( $lang_module['error_price_payment_order_plane'], number_format( $price_payment, 0, '.', ','), NV_DEFINE_DEPOSITS . '%' );
        }
    }elseif( $data_order['orderid'] > 0 ){
        //lay thong tin don hagn dat trc
        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_orders WHERE ordertype=2 AND user_id =' . $user_info['userid'] . ' AND order_id=' . $data_order['orderid'];
        $result = $db->query($sql);
        $data_order_old = $result->fetch();
        if( isset( $data_order_old['price_payment'] ) && $data_order['order_total'] < $data_order_old['price_payment'] ){
            $error[] = $lang_module['error_price_total_for_plane'];
        }
    }elseif ( $data_order['order_total'] <= 0 ){
        $error[] = $lang_module['error_price_total'];
    }

    if (empty($error)) {
        /*
        if( $data_order['price_total'] != $total_check_price_require ){
            $a = $data_order['price_total'] - $total_check_price_require;
            die('Loi khong tao duoc don hang!' . $data_order['price_total'] . ' ' . $total_check_price_require . '= ' . $a);
        }

        */
        //don hang tao moi tu dau
        if( $data_order['orderid']  == 0 ){

            //lay ma don hang theo cau truc ma ng quan ly + ma don hang
            if( $data_order['chossentype'] == 3 or $data_order['customer_id'] != $user_info['userid'] ){
                $sql = "SELECT precode FROM " . NV_TABLE_AFFILIATE . "_users WHERE userid=" . $data_order['customer_id'];
            }else{
                $sql = "SELECT parentid FROM " . NV_TABLE_AFFILIATE . "_users WHERE userid=" . $data_order['customer_id'];
                $result = $db->query($sql);
                list( $parentid ) = $result->fetch(3);
                $sql = "SELECT precode FROM " . NV_TABLE_AFFILIATE . "_users WHERE userid=" . $parentid;
            }

            $result = $db->query($sql);
            list( $precode ) = $result->fetch(3);
            if( $precode == ''){
                $precode = 'S%01s';
            }else{
                $precode = str_replace('%', '-S%', $precode) ;
            }

            //khach hang le
            if( $data_order['chossentype'] == 3){
                $sql = "SELECT refer_userid FROM " . NV_PREFIXLANG . "_" . $module_data . "_customer WHERE customer_id=" . $data_order['customer_id'];
            }else{
                $sql = "SELECT parentid FROM " . NV_TABLE_AFFILIATE . "_users WHERE userid=" . $data_order['customer_id'];
            }
            $result = $db->query($sql);
            list( $user_id ) = $result->fetch(3);

            $showadmin = 0;//don hang hien trong admin hay khong
            $kho_tru_chiet_khau = $user_id;
            //neu $possitonid > 0 thi gan $user_id = 0 (kho tong)
            list( $possitonid ) = $db->query("SELECT possitonid FROM " . NV_TABLE_AFFILIATE . "_users WHERE userid=" . $user_id)->fetch(3);;
            if( $possitonid > 0 ){
                $kho_tru_chiet_khau = 0;
                $showadmin = 1;
            }

            $result = $db->query("SHOW TABLE STATUS WHERE Name='" . NV_PREFIXLANG . "_" . $module_data . "_orders'");
            $item = $result->fetch();
            $result->closeCursor();

            $order_code = vsprintf($precode, $item['auto_increment']);
            
            $status = 0;
            if( $data_order['ordertype'] == 2 ){
                $status = 4;
            }
            
            $sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_orders (
                customer_id, order_code, order_name, order_email, order_phone, 
                order_address, order_note, user_id, admin_id, order_total, order_time, edit_time, postip, 
                saleoff, feeship, price_payment, shipcode, showadmin, chossentype, ordertype, orderid_refer, amount_refunded, depotid, status
            ) VALUES (
                " . $data_order['customer_id'] . ", :order_code, :order_name, :order_email, :order_phone, :order_address, :order_note,
                " . $user_id . ", 0," . doubleval($data_order['order_total']) . ",
                " . NV_CURRENTTIME . ", 0, :ip, " . $data_order['price_total_discount'] . ", 0, " . $data_order['price_payment'] . ", " . $data_order['order_shipcod'] . ", " . $showadmin . ", " . $data_order['chossentype'] . ", " . $data_order['ordertype'] . ", 0, " . doubleval($data_order['order_total']) . ", " . intval( $data_order['depotid'] ) . ", " . $status . "
            )";
            $data_insert = array( );
            $data_insert['order_code'] = $order_code;
            $data_insert['order_name'] = $data_order['order_name'];
            $data_insert['order_email'] = $data_order['order_email'];
            $data_insert['order_phone'] = $data_order['order_phone'];
            $data_insert['order_address'] = $data_order['order_address'];
            $data_insert['order_note'] = $data_order['order_note'];
            $data_insert['ip'] = $client_info['ip'];

            $order_id = $db->insert_id($sql, 'order_id', $data_insert);

            if ($order_id > 0) {
                if( $data_order['chossentype'] != 3 && $data_order['ordertype'] == 1) {
                    //khoi tao kho hang theo tung khach hang
                    $sql = 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_warehouse WHERE customerid=' . $data_order['customer_id'];
                    $check_exits = $db->query( $sql )->fetchColumn();
                    if( $check_exits == 0 ){
                        $title = $lang_module['warehouse_off'] . $user_data_affiliate['code'];
                        $note = $lang_module['warehouse_off_create_date'] . NV_CURRENTTIME;
                        nvCreatWarehouse( $data_order['customer_id'], $title, $note );
                    }
                    if( $data_order['price_total_discount'] > 0 ){
                     //   add_discount_customer( $data_order['customer_id'], $data_order['price_total_discount'], 1 );//cong tien chiet khau cho tuyen duoi
                       // add_discount_customer( $kho_tru_chiet_khau, $data_order['price_total_discount'], 0 );//tru tien chiet khau cho DL cap phan phoi hang cho tuyen duoi
                    }
                }
                //ghi lich su giao dich voi don dat trc va status =4
                if( $data_order['ordertype'] == 2 && $status == 4 ){
                    $payment_id = 0;
                    $payment_data = '';
                    $payment = '';
                    $userid = $user_info['userid'];
                    
                    $transaction_id = $db->insert_id("INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_transaction (transaction_id, transaction_time, transaction_status, order_id, userid, payment, payment_id, payment_time, payment_amount, payment_data) VALUES (NULL, " . NV_CURRENTTIME . ", '" . $status . "', '" . $order_id . "', '" . $userid . "', '" . $payment . "', '" . $payment_id . "', " . NV_CURRENTTIME . ", '" . $data_order['price_payment'] . "', '" . $payment_data . "')");
                    if ($transaction_id == 0) {
                        //ghi log neu k ghi dc giao dich
                        nv_insert_logs(NV_LANG_DATA, $module_name, 'Log payment order', "Order code: " . $data_content['order_code'], $user_info['userid']);
                    }
                }
                //Them chi tiet don hang
                foreach ( $data_content as $order_product ) {
                    if( $order_product['cartnumber'] > 0  ){
                        $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_orders_id( order_id, proid, num, type_return, numreturn, price, num_out, num_com, isgift ) 
                        VALUES ( :order_id, :proid, :num, 0, 0, :price, 0, :num_com, 0 )';
                        $data_insert = array();
                        $data_insert['order_id'] = $order_id;
                        $data_insert['proid'] = $order_product['id'];
                        $data_insert['num'] = $order_product['cartnumber'];
                        $data_insert['price'] = $order_product['price_retail'];
                        $data_insert['num_com'] = $order_product['num_com'];
                        $orders_id_detail = $db->insert_id($sql, 'id', $data_insert);
                        if( $orders_id_detail > 0 ){
                            //nhap kho neu la don dat hang
                            if( $data_order['ordertype'] == 1){
                                //Khach le mua thi ghi vào bang cham soc khach hang
                                if( $data_order['chossentype'] == 3 ){
                                    $day_received = ($data_order['order_shipcod'] == 1) ? NV_DEFINE_DAY_RECEIVED : 0;
                                    $product_name = $array_product[$order_product['id']]['title'];
                                    nvInsertSmsQueue( $order_id, $order_product['id'], $product_name, NV_CURRENTTIME, $data_order['order_name'], $data_order['order_email'], $data_order['order_phone'], $data_order['order_address'], $day_received );
                                }
                                /*
                                //nhap kho hang voi he thong DL, CTV
                                $quantity = $order_product['cartnumber'];

                                $price = $order_product['price_retail'] * $order_product['cartnumber'];
                                $price = $price - ( $price * $saleoff )/100;
                                //chua tru ton voi don hang moi dat
                                nhapkhohanghoa( $data_order['customer_id'], $data_order['depotid'], $order_product['id'], $quantity, $quantity_gift, $price, '+', $data_order['chossentype'], $order_id, $order_product['num_com'] );
                                */
                            }
                        }else{
                            //loi khi ghi dc ban ghi order_id thi k tru ton
                        }
                    }
                    if( $order_product['number_gift'] > 0 ){
                        //neu co sp tang thi
                        $quantity_gift = intval( $order_product['number_gift'] );
                        if( $quantity_gift > 0 ){
                            $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_orders_id( order_id, proid, num, type_return, numreturn, price, num_out, num_com, isgift ) 
                                VALUES ( :order_id, :proid, :num, 0, 0, :price, 0, :num_com, 1 )';
                            $data_insert = array();
                            $data_insert['order_id'] = $order_id;
                            $data_insert['proid'] = $order_product['id'];
                            $data_insert['num'] = $quantity_gift;
                            $data_insert['price'] = 0;//tang nen se co gia = 0
                            $data_insert['num_com'] = 0;
                            $db->insert_id($sql, 'id', $data_insert);
                        }
                    }
                }

                $checkss = md5($order_id . $global_config['sitekey'] . session_id());
                $review_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=or_view&order_id=' . $order_id . '&checkss=' . $checkss;

                /*
                            // Gui mail thong bao den khach hang
                            $data_order['id'] = $order_id;
                            $data_order['order_code'] = $order_code2;


                            // Thong tin san pham dat hang

                            $lang_module['order_email_noreply'] = sprintf($lang_module['order_email_noreply'], $global_config['site_url'], $global_config['site_url']);
                            $lang_module['order_email_thanks'] = sprintf($lang_module['order_email_thanks'], $global_config['site_url']);
                            $lang_module['order_email_review'] = sprintf($lang_module['order_email_review'], $global_config['site_url'] . $review_url);
                            $data_order['review_url'] = $review_url;

                            $content = '';
                            $email_contents_table = call_user_func('email_new_order', $content, $data_order, $data_pro, true);
                            $replace_data = array(
                                'order_code' => $data_order['order_code'],
                                'order_name' => $data_order['order_name'],
                                'order_email' => $data_order['order_email'],
                                'order_phone' => $data_order['order_phone'],
                                'order_address' => !empty($data_order['order_address']) ? $data_order['order_address'] : '-',
                                'order_note' => $data_order['order_note'],
                                'order_total' => $data_order['order_total'],
                                'unit_total' => $data_order['unit_total'],
                                'dateup' => nv_date("d-m-Y", $data_order['order_time']),
                                'moment' => nv_date("H:i", $data_order['order_time']),
                                'review_url' => '<a href="' . $global_config['site_url'] . $data_order['review_url'] . '">' . $lang_module['content_here'] . '</a>',
                                'table_product' => $email_contents_table,
                                'site_url' => $global_config['site_url'],
                                'site_name' => $global_config['site_name'],
                            );

                            $content_file = NV_ROOTDIR . '/' . NV_DATADIR . '/' . NV_LANG_DATA . '_' . $module_data . '_order_content.txt';
                            if (file_exists($content_file)) {
                                $content = file_get_contents($content_file);
                                $content = nv_editor_br2nl($content);
                            } else {
                                $content = $lang_module['order_payment_email'];
                            }

                            foreach ($replace_data as $key => $value) {
                                $content = str_replace('{' . $key . '}', $value, $content);
                            }

                            $email_contents = call_user_func('email_new_order', $content, $data_order, $data_pro);
                            $email_title = empty($order_info) ? $lang_module['order_email_title'] : $lang_module['order_email_edit_title'];

                            nv_sendmail(array(
                                $global_config['site_name'],
                                $global_config['site_email']
                            ), $data_order['order_email'], sprintf($email_title, $module_info['custom_title'], $data_order['order_code']), $email_contents);

                            // Gui mail thong bao den nguoi quan ly shops
                            $listmail_notify = nv_listmail_notify();
                            if (!empty($listmail_notify)) {
                                $email_contents_to_admin = call_user_func('email_new_order', $content, $data_order, $data_pro);
                                nv_sendmail(array(
                                    $global_config['site_name'],
                                    $global_config['site_email']
                                ), $listmail_notify, sprintf($email_title, $module_info['custom_title'], $data_order['order_code']), $email_contents_to_admin);
                            }

                            // Them vao notification
                            $content = array( 'order_id' => $data_order['id'], 'order_code' => $data_order['order_code'], 'order_name' => $data_order['order_name'] );
                            $userid = isset($user_info['userid']) and !empty($user_info['userid']) ? $user_info['userid'] : 0;
                            nv_insert_notification($module_name, empty($order_info) ? 'order_new' : 'order_edit', $content, 0, $userid, 1);
    */
                // Chuyen trang xem thong tin don hang vua dat
                unset($_SESSION[$module_data . '_cart']);
                unset($_SESSION[$module_data . '_order_info']);
                Header('Location: ' . $review_url);
                exit();

            }
        }
        else{
            //kiem tra lai lan nua tinh xac thuc cua don hang dat truoc
            $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_orders WHERE ordertype=2 AND user_id =' . $user_info['userid'] . ' AND order_id=' . $data_order['orderid'];

            $result = $db->query($sql);
            $data_order_old = $result->fetch();
            if( !empty( $data_order_old ) && $data_order_old['ordertype'] == 2 ){
                try {
                    $_sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_orders
                     SET order_name = :order_name, order_email = :order_email, order_phone=:order_phone, 
                     order_address = :order_address, order_note = :order_note, order_total =:order_total, edit_time=' . NV_CURRENTTIME . ', postip=:postip,
                     saleoff=:saleoff, shipcode=:shipcode, ordertype=1, status=0 WHERE order_id =' . $data_order_old['order_id'];

                    $sth = $db->prepare($_sql);
                    $sth->bindParam(':order_name', $data_order['order_name'], PDO::PARAM_STR);
                    $sth->bindParam(':order_email', $data_order['order_email'], PDO::PARAM_STR);
                    $sth->bindParam(':order_phone', $data_order['order_phone'], PDO::PARAM_STR);
                    $sth->bindParam(':order_address', $data_order['order_address'], PDO::PARAM_STR);
                    $sth->bindParam(':order_note', $data_order['order_note'], PDO::PARAM_STR);
                    $sth->bindParam(':order_total', $data_order['order_total'], PDO::PARAM_INT);
                    $sth->bindParam(':postip', $client_info['ip'], PDO::PARAM_STR);
                    $sth->bindParam(':saleoff', $data_order['price_total_discount'], PDO::PARAM_INT);
                    $sth->bindParam(':shipcode', $data_order['order_shipcod'], PDO::PARAM_INT);
                    $sth->execute();

                    if ($sth->rowCount()) {

                        //xoa cac ban ghi trong phan chi tiet don hang cu
                        $db->query("DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_orders_id WHERE order_id=" . $data_order_old['order_id']);

                        //Them chi tiet don hang
                        foreach ( $data_content as $order_product ) {

                            $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_orders_id( order_id, proid, num, type_return, numreturn, price, num_out, num_com, isgift ) 
                            VALUES ( :order_id, :proid, :num, 0, 0, :price, 0, :num_com, 0 )';
                            $data_insert = array();
                            $data_insert['order_id'] = $data_order_old['order_id'];
                            $data_insert['proid'] = $order_product['id'];
                            $data_insert['num'] = $order_product['cartnumber'];
                            $data_insert['price'] = $order_product['price_retail'];
                            $data_insert['num_com'] = $order_product['num_com'];
                            $order_i = $db->insert_id($sql, 'id', $data_insert);

                            if( $order_product['number_gift'] > 0 ){
                                //neu co sp tang thi
                                $quantity_gift = intval( $order_product['number_gift'] );
                                if( $quantity_gift > 0 ){
                                    $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_orders_id( order_id, proid, num, type_return, numreturn, price, num_out, num_com, isgift ) 
                                VALUES ( :order_id, :proid, :num, 0, 0, :price, 0, :num_com, 1 )';
                                    $data_insert = array();
                                    $data_insert['order_id'] = $data_order_old['order_id'];
                                    $data_insert['proid'] = $order_product['id'];
                                    $data_insert['num'] = $quantity_gift;
                                    $data_insert['price'] = 0;//tang nen se co gia = 0
                                    $data_insert['num_com'] = 0;
                                    $db->insert_id($sql, 'id', $data_insert);
                                }
                            }
                        }


                        $checkss = md5($data_order_old['order_id'] . $global_config['sitekey'] . session_id());
                        $review_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=or_view&order_id=' . $data_order_old['order_id'] . '&checkss=' . $checkss;

                        // Chuyen trang xem thong tin don hang vua dat
                        unset($_SESSION[$module_data . '_cart']);
                        unset($_SESSION[$module_data . '_order_info']);
                        Header('Location: ' . $review_url);
                        exit();
                    }
                } catch (PDOException $e) {

                    $error = $e->getMessage();
                }
            }
        }
    }
}
$data_content = array( );
$page_title = $lang_module['cart_title'];

//danh sach NPP cua tl dang nhap
$sql = 'SELECT numsubcat, subcatid FROM ' . NV_TABLE_AFFILIATE . '_users WHERE status=1 AND userid=' . $user_info['userid'];
list( $numsubcat, $subcatid ) = $db->query($sql)->fetch(3);
$listuserid = array();
if( $numsubcat > 0 ){
    $listuserid = nvGetUseridInParent($user_info['userid'], $subcatid, false, false);
    $sql_where = ' AND t1.userid IN ( ' . implode(',', $listuserid ) . ')';
}else{
    $sql_where = ' AND t1.userid =0';
}

//he thong ctv, dl
$db->sqlreset()->select('t1.userid, t2.code, t2.agencyid, t1.username, t1.first_name, t1.last_name, t1.email, t2.datatext' )->from( NV_TABLE_AFFILIATE . '_users AS t2')->join( 'INNER JOIN ' . NV_USERS_GLOBALTABLE . ' AS t1 ON t1.userid=t2.userid')->where( 't2.status=1 AND t1.userid != ' . $user_info['userid'] . ' AND agencyid>0 ' . $sql_where );

$sth = $db->prepare( $db->sql() );
$sth->execute();
$list_Agency = array();
while( list( $userid, $code, $agencyid, $username, $first_name, $last_name, $email, $datatext ) = $sth->fetch( 3 ) )
{
    $percent_sale_agency = '';
    $fullname = nv_show_name_user( $first_name, $last_name, $username );

    if( $array_agency[$agencyid]['number_sale'] > 0 and $array_agency[$agencyid]['number_gift'] >0 ){
        $percent_sale_agency = $percent_sale_agency . sprintf( $lang_module['gift_product_agency'], $array_agency[$agencyid]['number_sale'], $array_agency[$agencyid]['number_gift'] );
    }
    if( $array_agency[$agencyid]['price_for_discount'] > 0 and $array_agency[$agencyid]['price_discount'] >0 ){
        $percent_sale_agency .= sprintf( $lang_module['info_discount_price_agency'], number_format( $array_agency[$agencyid]['price_for_discount'], 0, '.', ',' ), number_format( $array_agency[$agencyid]['price_discount'], 0, '.', ',' ));
    }
    $datatext = unserialize( $datatext );

    $list_Agency[] = array(
        'key' => $userid,
        'value' => $code . ' - ' . $fullname . ' - ' . $array_agency[$agencyid]['title'],
        'fullname' => $fullname,
        'phone' => $datatext['mobile'],
        'address' => $datatext['address'],
        'username' => $username,
        'info_agency' => $percent_sale_agency,
        'email' => $email,
    );
}

$array_order_book_plane = array();
$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_orders WHERE ordertype=2 AND status=4 AND user_id=' . $user_info['userid'];

$result = $db->query( $sql );
while ( $row = $result ->fetch()){
    $array_order_book_plane[] = $row;
}
$contents = call_user_func( 'cart_product', $data_order, $user_data_affiliate, $array_agency, $list_Agency, $array_order_book_plane, $error );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
