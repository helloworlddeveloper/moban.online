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

$data_order = array( 'chossentype' => 0, 'customer_id' => 0, 'order_shipcod' => 0 );
$order_old = $error = array( );
$coupons_code = '';
$coupons_check = 0;
$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=';
$custype = $nv_Request->get_int('custype', 'post,get', 0);


if( $nv_Request->isset_request('setcart', 'get') )
{
    if (! isset($_SESSION[$module_data . '_cart'])) {
        $_SESSION[$module_data . '_cart'] = array();
    }
    $id = $nv_Request->get_int('id', 'post,get', 0);
    $num = $nv_Request->get_int('num', 'post,get', 1);
    $order_id = $nv_Request->get_int('order_id', 'post,get', 0);
    $type_return = $nv_Request->get_int('type_return', 'post,get', 2);//2 mac dinh la k ban dc
    $contents_msg = "";

    if (! is_numeric($num) || $num < 0) {
        $contents_msg = 'ERR_' . $lang_module['cart_set_err'];
    } elseif ($id > 0) {
        $result = $db->query("SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_orders_id WHERE order_id = " .  $order_id . " AND proid = " . $id);
        $data_orders = $result->fetch();

        $total_num_allow = $data_orders['num'] - $data_orders['numreturn'];
        if ($num > $total_num_allow ) {
            $_SESSION[$module_data . '_cart'][$id]['num'] = $total_num_allow;
        } else {
            $_SESSION[$module_data . '_cart'][$id]['num'] = $num;
        }
        $_SESSION[$module_data . '_cart'][$id]['type_return'] = $type_return;
        $contents_msg = 'OK_' . $_SESSION[$module_data . '_cart'][$id]['num'];
    }
    include NV_ROOTDIR . '/includes/header.php';
    echo $contents_msg;
    include NV_ROOTDIR . '/includes/footer.php';
    exit;
}


if( $nv_Request->isset_request('loadcart', 'get') )
{
    $agency_of_you = array();
    $ordercode = $nv_Request->get_title( 'ordercode', 'get', '' );
    if( !empty( $ordercode )){
            $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_orders WHERE ordertype=1 AND user_id=" . $user_info['userid'] . " AND order_code=" . $db->quote( $ordercode );
            $data_order = $db->query( $sql )->fetch();

        if( !empty( $data_order )){

            $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_orders WHERE ordertype=0 AND user_id=" . $user_info['userid'] . " AND orderid_refer=" . $data_order['order_id'] . ' ORDER BY order_time DESC LIMIT 1';
            $data_order_current = $db->query( $sql )->fetch();
            if( !empty( $data_order_current )){
                exit('ĐƠN HÀNG NÀY ĐÃ THỰC HIỆN LỆNH TRẢ LẠI VỚI MÃ ĐƠN MỚI LÀ: ' . $data_order_current['order_code']);
            }
            $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_product WHERE status =1 ORDER BY weight";
            $result = $db->query( $sql );
            $array_product = array();
            while( $row = $result->fetch( ) )
            {
                $array_product[$row['id']] = $row;
            }

            $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_orders_id WHERE order_id =" . $data_order['order_id'];
            $result = $db->query( $sql );
            $array_orders_id = array();
            $total = $rebook_total = 0;
            while( $row = $result->fetch( ) )
            {
                $row['product_title'] = $array_product[$row['proid']]['title'];
                $row['total_product'] = $row['num'];
                if( $row['isgift'] == 0 ){
                    $numpro_available = $row['num'] - $row['numreturn'];

                    if( !isset( $_SESSION[$module_data . '_cart'][$row['proid']]['num'] )){
                        $_SESSION[$module_data . '_cart'][$row['proid']]['num'] = $numpro_available;
                        $_SESSION[$module_data . '_cart'][$row['proid']]['type_return'] = 1;
                    }
                    $row['product_price'] = $array_product[$row['proid']]['price_retail'];//gia ban le
                    $row['price'] = nv_get_price_for_agency($row['product_price'], $row['proid'], $numpro_available );

                    $row['num_return'] = $_SESSION[$module_data . '_cart'][$row['proid']]['num'];//san pham tra dot nay
                    $row['type_return'] = $_SESSION[$module_data . '_cart'][$row['proid']]['type_return'];

                    $row['rebook_number'] = $row['num'] - ($row['num_return'] + $row['numreturn'] );//lay so luong sp con lai khi thuc hien lenh tra hang
                    $row['rebook_price'] = $row['price']; //tam thoi lay theo gia da dat hang nv_get_price_for_agency($row['product_price'], $row['proid'], $row['rebook_number'] );//lay gia nhap cho cac sp con lai trong don hang moi
                    $row['rebook_total_price'] = $row['rebook_price'] * $row['rebook_number'];

                    if( $_SESSION[$module_data . '_cart'][$row['proid']]['type_return'] == 2 ){
                        $timebook = NV_CURRENTTIME - $data_order['order_time'];

                        $timebook = ($timebook / 86400);//quy ra ngay
                        $timebook = ceil($timebook / 30);

                        if( isset( $array_defined_return_order[$timebook] ) ){
                            $percent = $array_defined_return_order[$timebook];
                            $row['message'] = sprintf( $lang_module['order_is_allow_return'], $timebook,$array_defined_return_order[$timebook] .'%', number_format($row['price'], 0, '.', ','));
                        }else{
                            $percent = $array_defined_return_order[3];//toi da la key so ba
                            $row['message'] = sprintf( $lang_module['order_is_allow_return_2'], 3,$percent .'%', number_format($row['price'], 0, '.', ','));
                        }
                        $row['price'] = round( (($row['price'] * $percent)/100));
                    }else{
                        $row['price'] = 0;
                    }
                    $rebook_total+= $row['rebook_total_price'];
                    $row['price_total'] = $_SESSION[$module_data . '_cart'][$row['proid']]['num'] * $row['price'];
                    $total = $total + $row['price_total'];
                }
                $array_orders_id[] = $row;
            }


            //print_r($array_orders_id);die;
            $agency_of_you = $array_gift_new = array();
            if( $data_order['chossentype'] != 3 ){
                //khong phai khach le thi lay thong tin NPP
                $sql = 'SELECT datatext FROM ' . NV_TABLE_AFFILIATE . '_users WHERE userid=' . $data_order['customer_id'];
                list( $datatext ) = $db->query($sql)->fetch(3);
                $datatext = unserialize( $datatext );

                if( isset( $array_agency[$datatext['agencyid']] )){
                    $agency_of_you = $array_agency[$datatext['agencyid']];
                }

                $price_total_discount = 0;
                if( $agency_of_you['price_for_discount'] > 0 and $agency_of_you['price_discount'] >0 ){
                    $price_total_discount = floor($rebook_total / $agency_of_you['price_for_discount']) * $agency_of_you['price_discount'];
                }
                $rebook_total_after_discount = $rebook_total - $price_total_discount;

                //lay thong tin hang tang theo so tien con lai
                $sql_check_gift = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_saleoff AS t1 INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_saleoff_detail AS t2 ON t1.id=t2.saleoffid WHERE salesfrom<' . $rebook_total_after_discount . ' AND salesto>=' . $rebook_total_after_discount;

                $gif_info_array = $db->query($sql_check_gift)->fetchAll();
                if( !empty( $gif_info_array )){
                    foreach ( $gif_info_array as $gif_info ){
                        $numbergift = floor( $rebook_total_after_discount / $gif_info['moneyrequire'] ) * $gif_info['numbergift'];
                        $_SESSION[$module_data . '_cart'][$gif_info['productid']]['numgift'] = $numbergift;
                        $array_gift_new[$gif_info['productid']] = $numbergift;
                    }
                }
            }
        }
    }
    $contents = order_return_product_load($array_orders_id, $data_order, $data_order_current, $agency_of_you, $array_gift_new );
    include NV_ROOTDIR . '/includes/header.php';
    echo $contents;
    include NV_ROOTDIR . '/includes/footer.php';
    exit;
}

if( $nv_Request->isset_request('submit', 'post') ) {

    $data_order['ordercode'] = nv_substr($nv_Request->get_title('ordercode', 'post', '', 1), 0, 200);
    $data_order['order_name'] = nv_substr($nv_Request->get_title('order_name', 'post', '', 1), 0, 200);
    $data_order['order_email'] = nv_substr($nv_Request->get_title('order_email', 'post', '', 1), 0, 250);
    $data_order['order_phone'] = nv_substr($nv_Request->get_title('order_phone', 'post', '', 1), 0, 20);
    $data_order['order_address'] = nv_substr($nv_Request->get_title('order_address', 'post', '', 1), 0, 255);
    $data_order['order_note'] = nv_substr($nv_Request->get_title('order_note', 'post', '', 1), 0, 2000);
    $data_order['price_total_discount'] = $nv_Request->get_title('price_total_discount', 'post', 0);
    $data_order['order_shipcod'] = intval( $nv_Request->isset_request('order_shipcod', 'post') );
    $order_info = array();

    if( !empty( $data_order['ordercode'] )) {
        $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_orders WHERE user_id=" . $user_info['userid'] . " AND order_code=" . $db->quote($data_order['ordercode']);
        $data_order_old = $db->query($sql)->fetch();
        if (!empty($data_order_old)) {

            $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_orders WHERE ordertype=0 AND user_id=" . $user_info['userid'] . " AND orderid_refer=" . $data_order_old['order_id'] . ' ORDER BY order_time DESC LIMIT 1';
            $data_order_current = $db->query( $sql )->fetch();

            $total_price = $rebook_total_price = 0;
            $data_order['customer_id'] = $data_order_old['customer_id'];
            $data_order['chossentype'] = $data_order_old['chossentype'];

            $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_orders_id WHERE order_id =" . $data_order_old['order_id'];
            $result = $db->query( $sql );
            while( $data_order_detail = $result->fetch( ) )
            {
                $pro_info = $_SESSION[$module_data . '_cart'][$data_order_detail['proid']];//lay thong tin tu session

                if( intval( $data_order_detail['proid'] ) > 0 && $pro_info['num'] > 0 ){
                    $sql_i = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_product WHERE id = " . $data_order_detail['proid'] . " AND status =1";
                    $result_i = $db->query( $sql_i );
                    $data_product = $result_i->fetch( );
                    if( !empty( $data_product )){

                        if( $data_order_detail['isgift'] == 0 ){
                            $numpro_available = $data_order_detail['num'] - $data_order_detail['numreturn'];

                            $data_order_detail['price'] = nv_get_price_for_agency($data_product['price_retail'], $data_order_detail['proid'], $numpro_available );

                            /* khong kiem tra vi khi xac nhan moi tru kho
                            $total_num_product = checkNumTotalInBehideCustomer( $data_order_old['customer_id'], $data_order_detail['proid'] );
                            //kiem tra trong kho nhanh duoi xem co du hang de tra khong
                            if( $data_order_old['chossentype'] != 3 && $total_num_product < $pro_info['num'] ){
                                $error[] = sprintf( $lang_module['number_in_warehouse_logs_error_1'], $data_product['title'], $total_num_product );
                            }
                            */

                            if( $pro_info['type_return'] == 2 ){
                                //tra hang vi khong ban duoc
                                $timebook = NV_CURRENTTIME - $data_order_old['order_time'];
                                $timebook = ($timebook / 86400);//quy ra ngay;
                                $timebook = ceil($timebook / 30);

                                if( isset( $array_defined_return_order[$timebook] ) ){
                                    $percent = $array_defined_return_order[$timebook];
                                }else{
                                    $percent = $array_defined_return_order[3];//toi da la key so ba
                                    //$error[] = sprintf( $lang_module['error_no_return_product'], $data_product['title'] );
                                }
                                $price = ($data_order_detail['price'] * $percent)/100;
                                $total_price += $pro_info['num'] * $price;
                            }else{
                                $price = $data_order_detail['price'];
                            }

                            $rebook_number = $data_order_detail['num'] - ($pro_info['num'] + $data_order_detail['numreturn'] );//lay so luong sp con lai khi thuc hien lenh tra hang
                            $rebook_price = $data_order_detail['price'];//tam thoi lay theo gia nhap nv_get_price_for_agency($data_product['price_retail'], $data_order_detail['proid'], $rebook_number );//lay gia nhap cho cac sp con lai trong don hang moi
                            $rebook_total_price += $rebook_price * $rebook_number;//so tien phai tra khi cho so sp con lai
                            $data_content[$data_order_detail['proid']] = array('id' => $data_order_detail['proid'], 'num' => $pro_info['num'], 'type_return' => $pro_info['type_return'], 'price' => $price );
                        }else{
                            $numgift = $_SESSION[$module_data . '_cart'][$data_order_detail['proid']]['numgift'];
                            $data_content[$data_order_detail['proid']]['numgift'] = $data_order_detail['num'] - $numgift;//Lay so luong hag tang phai tra lai
                        }

                    }
                }else{
                    $rebook_total_price += $data_order_detail['price'] * $data_order_detail['num'];//lay so tien san pham khong tra lai
                }
            }
        }
    }else{
        $error[] = $lang_module['error_no_search_info_order'];
    }
    if( empty( $data_content )){
        $error[] = $lang_module['error_no_order_product_return'];
    }

    if (empty($error)) {
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

        $data_order['price_total_discount'] = 0;
        //lay so tien chiet khau theo han muc
        $agency_of_you = array();
        if( $data_order_old['chossentype'] != 3 ){
            //khong phai khach le thi lay thong tin NPP
            $sql = 'SELECT datatext FROM ' . NV_TABLE_AFFILIATE . '_users WHERE userid=' . $data_order_old['customer_id'];
            list( $datatext ) = $db->query($sql)->fetch(3);
            $datatext = unserialize( $datatext );

            if( isset( $array_agency[$datatext['agencyid']] )){
                $agency_of_you = $array_agency[$datatext['agencyid']];
            }
        }
        if( $agency_of_you['price_for_discount'] > 0 and $agency_of_you['price_discount'] >0 ){
            $data_order['price_total_discount'] = floor($rebook_total_price / $agency_of_you['price_for_discount']) * $agency_of_you['price_discount'];
        }
        $rebook_total_after_discount = $rebook_total_price - $data_order['price_total_discount'];
        if( !empty( $data_order_current )){
            $data_order['order_total'] = $data_order_current['amount_refunded'] - ($rebook_total_after_discount + $total_price);
        }else{
            $data_order['order_total'] = $data_order_old['amount_refunded'] - ($rebook_total_after_discount + $total_price);
        }

        if( $data_order['order_total'] > 0 ){
            $sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_orders (
            customer_id, order_code, order_name, order_email, order_phone, 
            order_address, order_note, user_id, admin_id, order_total, order_time, edit_time, postip, 
            saleoff, feeship, price_payment, shipcode, showadmin, chossentype, ordertype, orderid_refer, amount_refunded, depotid, status
        ) VALUES (
            " . $data_order['customer_id'] . ", :order_code, :order_name, :order_email, :order_phone, :order_address, :order_note,
            " . $user_id . ", 0," . doubleval($data_order['order_total']) . ",
            " . NV_CURRENTTIME . ", 0, :ip, " . $data_order['price_total_discount'] . ", 0, 0, " . $data_order['order_shipcod'] . ", " . $showadmin . ", 
            " . $data_order['chossentype'] . ", 0, " . $data_order_old['order_id'] . ", " . $rebook_total_after_discount . ", 0, " . $status . "
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
                try{
                    //cap nhat don hang goc ve so tien sau hoan tra ---ti sua lai
                    //$db->query("UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_orders SET amount_refunded= " . $data_order['order_total'] . " WHERE order_id=" . $data_order_old['order_id']);

                    //Them chi tiet don hang
                    foreach ( $data_content as $order_product ) {
                        $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_orders_id( order_id, proid, num, type_return, numreturn, price, num_out ) 
                VALUES ( :order_id, :proid, :num, :type_return, 0, :price, 0 )';
                        $data_insert = array();
                        $data_insert['order_id'] = $order_id;
                        $data_insert['proid'] = $order_product['id'];
                        $data_insert['num'] = $order_product['num'];
                        $data_insert['type_return'] = $order_product['type_return'];
                        $data_insert['price'] = $order_product['price'];

                        $order_i = $db->insert_id($sql, 'id', $data_insert);
                        //cap nhat so luong don hang cu ---ti sua lai
                        //$db->query("UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_orders_id SET numreturn= numreturn+" . $order_product['num'] . " WHERE proid= " .$order_product['id'] . " AND order_id=" . $data_order_old['order_id']);

                        if( $order_product['numgift'] > 0 ){
                            //neu co sp tang thi
                            $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_orders_id( order_id, proid, num, type_return, numreturn, price, num_out, num_com, isgift ) 
                            VALUES ( :order_id, :proid, :num, 0, 0, :price, 0, :num_com, 1 )';
                            $data_insert = array();
                            $data_insert['order_id'] = $order_id;
                            $data_insert['proid'] = $order_product['id'];
                            $data_insert['num'] = $order_product['numgift'];
                            $data_insert['price'] = 0;//tang nen se co gia = 0
                            $data_insert['num_com'] = 0;
                            $db->insert_id($sql, 'id', $data_insert);
                        }

                        //nhap kho hang voi he thong DL, CTV
                        $quantity = $order_product['num'];
                        $price = $order_product['price'] * $order_product['num'];

                        //cong lai hang vao kho tuyen tren
                      //  save_warehouse_logs_customer( $quantity, $price, $kho_tru_chiet_khau, $data_order_old['depotid'], $order_product['id'], '+', $order_id );

                        //tru hang trong kho tuyen duoi
                        //save_warehouse_logs_customer( $quantity, $price, $data_order_old['customer_id'], 0, $order_product['id'], '-', $order_id );

                    }
                } catch (PDOException $e) {
                    die($e->getMessage());
                    $error[] = $e->getMessage();

                }

                $checkss = md5($order_id . $global_config['sitekey'] . session_id());
                $review_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=return-or-view&order_id=' . $order_id . '&checkss=' . $checkss;

                // Chuyen trang xem thong tin don hang vua dat
                unset($_SESSION[$module_data . '_cart']);
                unset($_SESSION[$module_data . '_order_info']);
                Header('Location: ' . $review_url);
                exit();

            }else{
                $error[] = 'Lỗi hệ thống!';
            }
        }
        else{
            $error[] = $lang_module['error_not_return_order'];
        }
    }
}
$data_content = array( );
$page_title = $lang_module['return_order'];

$contents = call_user_func( 'return_orders', $data_order, $error );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
