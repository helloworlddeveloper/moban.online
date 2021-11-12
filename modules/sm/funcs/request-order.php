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

if( $nv_Request->isset_request('resetcart', 'get') )
{
    unset($_SESSION[$module_data . '_cart']);

    $contents_msg = 'OK_update';
    include NV_ROOTDIR . '/includes/header.php';
    echo $contents_msg;
    include NV_ROOTDIR . '/includes/footer.php';
    exit;
}

if( $nv_Request->isset_request('setcart', 'get') )
{
    /*
    if (! isset($_SESSION[$module_data . '_cart'])) {
        $_SESSION[$module_data . '_cart'] = array();
    }
    $_SESSION[$module_data . '_cart']['updated'] = 1;
    */
    $id = $nv_Request->get_int('id', 'post,get', 1);
    $num = $nv_Request->get_int('num', 'post,get', 1);
    $giftproduct = $nv_Request->get_int('giftproduct', 'post,get', 1);
    $isgift = $nv_Request->get_int('isgift', 'post,get', 1);
    $contents_msg = "";

    if (! is_numeric($num) || $num < 0) {
        $contents_msg = 'ERR_' . $lang_module['cart_set_err'];
    } elseif ($id > 0) {
        if( !$isgift){
            $_SESSION[$module_data . '_cart'][$id]['num'] = $num;
        }else{
            $_SESSION[$module_data . '_cart'][$id]['numgif'] = $num;
        }
    }
    $contents_msg = 'OK_update';
    include NV_ROOTDIR . '/includes/header.php';
    echo $contents_msg;
    include NV_ROOTDIR . '/includes/footer.php';
    exit;
}

if( $nv_Request->isset_request('remove', 'get') )
{
    $id = $nv_Request->get_int('id', 'post,get', 1);

    if( isset( $_SESSION[$module_data . '_cart'][$id] )){
        unset( $_SESSION[$module_data . '_cart'][$id] );
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
    if( $user_data_affiliate['agencyid'] > 0){
        $agency_of_you = $array_agency[$user_data_affiliate['agencyid']];
    }

    $producttype = $nv_Request->get_int( 'producttype', 'get', 0 );
    if ($producttype == 0)
    {
        unset($_SESSION[$module_data . '_cart']);
        echo "";
        exit;
    }
    //die($producttype);
    $price_total_discount = $nv_Request->get_title( 'price_total_discount', 'get', 0 );//ID giam gia theo %
    $bonus_cumulative = $producttype == 2 ? 1 : 0;//1=hang tich luy san pham,0 khong tich luy san pham

    //$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_product WHERE status =1 ORDER BY weight";//all product
    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_product WHERE status = 1 and bonus_cumulative = " . $bonus_cumulative . " and id in (SELECT DISTINCT productid FROM " . NV_PREFIXLANG . "_" . $module_data . "_discounts) ORDER BY weight";//product with discount
    //die($sql);

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

    $data_order = array();
    $total_price = $total =  0;
    $data_content = array();
    foreach( $_SESSION[$module_data . '_cart'] as $pro_id => $pro_info )
    {
        if( intval( $pro_id ) > 0 && isset($array_product[$pro_id])){
            $row = $array_product[$pro_id];
            $number = $_SESSION[$module_data . '_cart'][$row['id']]['num'];
            //die($row['id'] . ":".$row['pnumber'].":".$_SESSION[$module_data . '_cart'][$row['id']]['num']);

            $row['price_sale'] = -1;
            if( $row['priceshow'] == 1 && $_SESSION[$module_data . '_cart'][$row['id']]['price'] > 0 ){
                //lay gia tuy bien neu co
                $row['price_sale'] = $_SESSION[$module_data . '_cart'][$row['id']]['price'];
            }
            $row['link_remove'] =  $link . 'remove&id=' . $row['id'];
            $row['cartnumber'] = $number;
            $row['giftproduct'] = 0;
            $row['giftproductdesc'] = 0;
            $data_content[$pro_id] = $row;
        }
        else if( intval( $pro_id ) > 0 && !isset($array_product[$pro_id])){
            unset( $_SESSION[$module_data . '_cart'][$pro_id]);
        }
    }

    $contents = cart_product_load_request_order($data_content, $agency_of_you, $bonus_cumulative);
    include NV_ROOTDIR . '/includes/header.php';
    echo $contents;
    include NV_ROOTDIR . '/includes/footer.php';
    exit;
}
$data_order['orderid'] = 0;
if( $nv_Request->isset_request('submit', 'post') ) {
    //print_r($_SESSION[$module_data . '_cart']);die;
    $agency_of_you = array();
    if( $user_data_affiliate['agencyid'] > 0){
        $agency_of_you = $array_agency[$user_data_affiliate['agencyid']];
    }

    $data_order['order_name'] = nv_substr($nv_Request->get_title('order_name', 'post', '', 1), 0, 200);
    $data_order['order_email'] = nv_substr($nv_Request->get_title('order_email', 'post', '', 1), 0, 250);
    $data_order['order_phone'] = nv_substr($nv_Request->get_title('order_phone', 'post', '', 1), 0, 20);
    $data_order['order_address'] = nv_substr($nv_Request->get_title('order_address', 'post', '', 1), 0, 255);
    $data_order['order_note'] = nv_substr($nv_Request->get_title('order_note', 'post', '', 1), 0, 2000);
    $data_order['customer_id'] = $user_info['userid'] ;// $nv_Request->get_int('customer_id', 'post', 0);
    $data_order['chossentype'] = 2;//khách đại lý//$nv_Request->get_int('chossentype', 'post', 0);
    $data_order['agencyid'] = $user_info['userid'] ;//$nv_Request->get_int('agencyid', 'post', 0);
    $data_order['ordertype'] = 1;//$nv_Request->get_int('ordertype', 'post', 0);
    $data_order['producttype'] = $nv_Request->get_int( 'producttype', 'post', 1 );

    $bonus_cumulative = $data_order['producttype'] == 2 ? 1 : 0;//1=hang tich luy san pham,0 khong tich luy san pham

    $data_order['order_shipcod'] = intval( $nv_Request->isset_request('order_shipcod', 'post') );
    $data_order['orderid'] = $nv_Request->get_int('orderid', 'post', 0);//id don hang dat truoc
    $data_order['depotid'] = $nv_Request->get_int('depotid', 'post', 0);//id kho hang
    $data_order['price_payment'] = $nv_Request->get_int('price_payment', 'post', 0);

    $data_order['price_total'] = $nv_Request->get_int('price_total', 'post', 0);
    $data_order['price_total_discount'] = $nv_Request->get_int('price_total_discount', 'post', 0);
    $data_order['order_total'] = $nv_Request->get_int('price_total_end', 'post', 0);
//print_r($data_order);die;
    //neu sua tu don hang dat truoc thi lay customer_id tu don hang dat truoc
    if(empty($data_order['order_name']) or empty( $data_order['order_phone']))
    {
        $error['order_name'] = $lang_module['error_customer_info'];
    }

    if ( $data_order['order_total'] <= 0 ){
        $error[] = $lang_module['error_price_total'];
    }

    if (empty($error)) {
        //don hang tao moi tu dau
        if( $data_order['orderid']  == 0 ){

            //lay ma don hang theo cau truc ma ng quan ly + ma don hang
            $sql = "SELECT precode FROM " . NV_TABLE_AFFILIATE . "_users WHERE userid=" . $user_data_affiliate['parentid'];
            $result = $db->query($sql);
            list( $precode ) = $result->fetch(3);
            if( $precode == ''){
                $precode = 'GIO%01s';
            }else{
                $precode = str_replace('%', '-S%', $precode) ;
            }
            $result = $db->query("SHOW TABLE STATUS WHERE Name='" . NV_PREFIXLANG . "_" . $module_data . "_orders'");
            $item = $result->fetch();
            $result->closeCursor();

            $order_code = vsprintf($precode, $item['auto_increment']);

            $status = OD1_WAITING_APPROVE;

            $approverid = $user_data_affiliate['parentid'];
            if ($approverid == 0 || $approverid == 4) {
                //$approverid = 0;
                $showadmin = 1;//công ty duyệt
            } else {
                $showadmin = 0;//tuyến trên duyệt
                $data_order['depotid'] = $approverid;//kho tuyến trên
            }



            $data_order['producttype'] = ($agency_of_you['apply_cumulative'] == 1 || $bonus_cumulative == 0) ? 0 : 1;

            $sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_orders (
                customer_id, order_code, order_name, order_email, order_phone, 
                order_address, order_note, user_id, admin_id, order_total, order_time, edit_time, postip, 
                saleoff, feeship, price_payment, shipcode, showadmin, chossentype, ordertype, orderid_refer, amount_refunded, depotid, status, producttype
            ) VALUES (
                " . $data_order['customer_id'] . ", :order_code, :order_name, :order_email, :order_phone, :order_address, :order_note,
                " . $approverid . ", 0," . doubleval($data_order['order_total']) . ",
                " . NV_CURRENTTIME . ", 0, :ip, " . $data_order['price_total_discount'] . ", 0, " . $data_order['price_payment'] . ", " . $data_order['order_shipcod'] . ", " . $showadmin . ", " . $data_order['chossentype'] . ", " . $data_order['ordertype'] . ", 0, " . doubleval($data_order['order_total']) . ", " . intval( $data_order['depotid'] ) . ", " . $status . ", " . $data_order['producttype'] . "
            )";
            //die($sql);
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
                //khoi tao kho hang theo tung khach hang
                $sql = 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_warehouse WHERE customerid=' . $data_order['customer_id'];
                $check_exits = $db->query( $sql )->fetchColumn();
                if( $check_exits == 0 ){
                    $title = $lang_module['warehouse_off'] . $user_data_affiliate['code'];
                    $note = $lang_module['warehouse_off_create_date'] . NV_CURRENTTIME;
                    nvCreatWarehouse( $data_order['customer_id'], $title, $note );
                }

                //Them chi tiet don hang
                foreach ( $_SESSION[$module_data . '_cart'] as $pro_id => $order_product ) {
                    if( $order_product['num'] > 0  ){
                        $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_orders_id( order_id, proid, num, type_return, numreturn, price, num_out, num_com, isgift ) 
                        VALUES ( :order_id, :proid, :num, 0, 0, :price, 0, :num_com, 0 )';
                        $data_insert = array();
                        $data_insert['order_id'] = $order_id;
                        $data_insert['proid'] = $pro_id;
                        $data_insert['num'] = $order_product['num'];
                        $data_insert['price'] = $order_product['price_retail'];
                        $data_insert['num_com'] = $order_product['num_com'];
                        $orders_id_detail = $db->insert_id($sql, 'id', $data_insert);
                    }
                    if( $order_product['numgif'] > 0 ){
                        //neu co sp tang thi
                        $quantity_gift = intval( $order_product['numgif'] );
                        $giftproductid = $order_product['idprogif'] > 0 ? $order_product['idprogif'] : $pro_id;
                        if( $quantity_gift > 0 ){
                            $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_orders_id( order_id, proid, num, type_return, numreturn, price, num_out, num_com, isgift ) 
                                VALUES ( :order_id, :proid, :num, 0, 0, :price, 0, :num_com, 1 )';
                            $data_insert = array();
                            $data_insert['order_id'] = $order_id;
                            $data_insert['proid'] = $giftproductid;
                            $data_insert['num'] = $quantity_gift;
                            $data_insert['price'] = 0;//tang nen se co gia = 0
                            $data_insert['num_com'] = 0;
                            $db->insert_id($sql, 'id', $data_insert);
                        }
                    }
                }

                $checkss = md5($order_id . $global_config['sitekey'] . session_id());
                $review_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=or_view&order_id=' . $order_id . '&checkss=' . $checkss;

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
            if( !empty( $data_order_old )){
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
                        foreach ( $_SESSION[$module_data . '_cart'] as $pro_id => $order_product ) {

                            $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_orders_id( order_id, proid, num, type_return, numreturn, price, num_out, num_com, isgift ) 
                            VALUES ( :order_id, :proid, :num, 0, 0, :price, 0, :num_com, 0 )';
                            $data_insert = array();
                            $data_insert['order_id'] = $data_order_old['order_id'];
                            $data_insert['proid'] = $pro_id;
                            $data_insert['num'] = $order_product['num'];
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
                                    $data_insert['proid'] = $pro_id;
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
    else {

    }

}
else {
    $data_order['order_name'] = $user_data_affiliate['fullname'];
    $data_order['order_phone'] = $user_data_affiliate['mobile'];
    $data_order['order_email'] = $user_data_affiliate['email'];
    $data_order['order_address'] = $user_data_affiliate['datatext']['address'];
}

$page_title = $lang_module['cart_title'];

$contents = call_user_func( 'cart_product_request_order', $data_order, $user_data_affiliate, $error );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
