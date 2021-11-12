<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:14
 */

if (! defined('NV_IS_MOD_SM')) {
    die('Stop!!!');
}

$table_name = NV_PREFIXLANG . '_' . $module_data . '_orders';
if( $nv_Request->isset_request( 'active_pay', 'get')){
    $contents = $lang_module['order_submit_pay_error'];
    $order_id = $nv_Request->get_int('order_id', 'get', 0);
    $save = $nv_Request->get_title('save', 'post,get', '');
    $action = $nv_Request->get_title('action', 'post,get', '');
    $result = $db->query('SELECT * FROM ' . $table_name . ' WHERE order_id=' . $order_id);
    $data_content = $result->fetch();

    $payment_amount = $nv_Request->get_title('payment_amount', 'post,get', '');
    $payment_amount = doubleval( str_replace(',', '', $payment_amount ) );

    if (empty($data_content) or empty($action)) {
        $error = $lang_module['order_submit_pay_error'];
    }
    if( $payment_amount > $data_content['order_total'] - $data_content['price_payment'] ){
        $error = $lang_module['payment_amount_is_pay_error'];
    }
    if ($save == 1) {
        /* transaction_status: Trang thai giao dich:
         -1 - Giao dich cho duyet
         0 - Giao dich moi tao
         1 - Chua thanh toan;
         2 - Da thanh toan, dang bi tam giu;
         3 - Giao dich bi huy;
         4 - Giao dich da hoan thanh thanh cong (truong hop thanh toan ngay hoac thanh toan tam giu nhung nguoi mua da phe chuan)
         */

        if ($action == 'unpay') {

            $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_transaction WHERE  order_id = ' . $order_id);
            $db->query("UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_orders SET status=0 WHERE order_id=" . $order_id);
            nv_insert_logs(NV_LANG_DATA, $module_name, 'Drop payment product', "Order code: " . $data_content['order_code'], $admin_info['userid']);

            $contents = $lang_module['order_submit_unpay_ok'];
            $nv_Cache->delMod($module_name);
        } elseif ($action == 'pay') {

            $transaction_status = 4;
            $payment_id = 0;
            $payment_data = '';
            $payment = '';
            $userid = $user_info['userid'];

            //lay so luong trong don dat hang de kiem tra so luong
            $result = $db->query('SELECT * FROM ' . NV_PREFIXLANG . "_" . $module_data . '_orders_id WHERE order_id=' . $data_content['order_id']);
            $array_total_productid = array();
            $array_number_productid = array();
            while( $data_order = $result->fetch()){
                if( !isset( $array_total_productid[$data_order['proid']] )){
                    $array_total_productid[$data_order['proid']] = $data_order['num'];
                }else{
                    $array_total_productid[$data_order['proid']] += $data_order['num'];
                }

                if( !isset( $array_number_productid[$data_order['proid']]['num_com'] )){
                    $array_number_productid[$data_order['proid']]['num_com'] = $data_order['num_com'];
                }else{
                    $array_number_productid[$data_order['proid']]['num_com'] += $data_order['num_com'];
                }

                if( $data_order['isgift'] == 1 ){
                    if( !isset( $array_number_productid[$data_order['proid']]['numgift'] )){
                        $array_number_productid[$data_order['proid']]['numgift'] = $data_order['num'];
                    }else{
                        $array_number_productid[$data_order['proid']]['numgift'] += $data_order['num'];
                    }
                }else{
                    if( !isset( $array_number_productid[$data_order['proid']]['num'] )){
                        $array_number_productid[$data_order['proid']]['num'] = $data_order['num'];
                    }else{
                        $array_number_productid[$data_order['proid']]['num'] += $data_order['num'];
                    }

                    $array_number_productid[$data_order['proid']]['price'] = $data_order['price'];
                }
            }

            //kiem tra so luong trong kho truoc khi tru ton
            $error = '';
            foreach ( $array_total_productid as $proid => $num_out ){
                $check_warehouse = ( $data_content['depotid'] > 0 )? 1 : 0;

                //check kho tuyen duoi
                $total_num_product = checkNumTotalInBehideCustomer( $data_content['customer_id'], $proid );
                if( $total_num_product < $num_out ){
                    exit(sprintf( $lang_module['number_in_warehouse_logs_error_1'], $array_product[$proid]['title'], $total_num_product ));
                }
            }
            //cap nhat hang hoa
            if( $data_content['status'] != 4 ){
                foreach ( $array_number_productid as $proid => $datanumber)
                {
                    //cong lai hang vao kho tuyen tren
                    $price = $datanumber['price'] * $datanumber['num'];//gia theo sl dat
                    $num_out = $datanumber['num'] + intval( $datanumber['numgift'] );
                    //hoan ton len tuyen tren
                    save_warehouse_logs_customer( $num_out, $price, $data_content['user_id'], $data_content['depotid'], $proid, '+', $data_content['order_id'] );
                    //tru hang trong kho tuyen duoi
                    save_warehouse_logs_customer( $num_out, $price, $data_content['customer_id'], 0, $proid, '-', $data_content['order_id'] );
                }
            }

            $transaction_id = $db->insert_id("INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_transaction (transaction_id, transaction_time, transaction_status, order_id, userid, payment, payment_id, payment_time, payment_amount, payment_data) VALUES (NULL, " . NV_CURRENTTIME . ", '" . $transaction_status . "', '" . $data_content['order_id'] . "', '" . $user_info['userid'] . "', '" . $payment . "', '" . $payment_id . "', " . NV_CURRENTTIME . ", '" . $payment_amount . "', '" . $payment_data . "')");

            if ($transaction_id > 0) {
                $db->query("UPDATE " . NV_PREFIXLANG  . "_" . $module_data . "_orders SET amount_refunded= " . $data_content['order_total'] . ", status=" . $transaction_status . ", price_payment=price_payment+" . $payment_amount . " WHERE order_id=" . $data_content['order_id']);
            }
            $contents = $lang_module['order_submit_pay_ok'];
            $nv_Cache->delMod($module_name);
        }
        elseif ($action == 'return') {
            $transaction_status = 5;
            $payment_id = 0;
            $payment_amount = $nv_Request->get_int('money_return', 'get,post', 0);
            $payment_data = '';
            $payment = '';
            $userid = $admin_info['userid'];

            $transaction_id = $db->insert_id("INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_transaction (transaction_id, transaction_time, transaction_status, order_id, userid, payment, payment_id, payment_time, payment_amount, payment_data) VALUES (NULL, " . NV_CURRENTTIME . ", '" . $transaction_status . "', '" . $order_id . "', '" . $userid . "', '" . $payment . "', '" . $payment_id . "', " . NV_CURRENTTIME . ", '" . $payment_amount . "', '" . $payment_data . "')");
            $contents = $lang_module['order_submit_return_ok'];
            $nv_Cache->delMod($module_name);
        }
    }
    die($contents);
}

$page_title = $lang_module['orders'];

$order_id = $nv_Request->get_int('order_id', 'get', 0);
$checkss = $nv_Request->get_string('checkss', 'get', '');

if ($order_id > 0 and $checkss == md5($order_id . $global_config['sitekey'] . session_id())) {
    $data_pro = array();
    $link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=';

    // Thong tin don hang
    $result = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_orders WHERE order_id=' . $order_id);
    if ($result->rowCount() == 0) {
        Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
        die();
    }
    $data = $result->fetch();

    if( !empty( $data )){

        //don hang goc (mua vao)
        $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_orders WHERE ordertype=1 AND (user_id=" . $user_info['userid'] . " OR customer_id= " . $user_info['userid'] . ") AND order_id=" . $db->quote( $data['orderid_refer'] );
        $data_order = $db->query( $sql )->fetch();

        if( !empty( $data_order )){
            $data_order['order_code'] = $data['order_code'];
            $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_product WHERE status =1 ORDER BY weight";
            $result = $db->query( $sql );
            $array_product = array();
            while( $row = $result->fetch( ) )
            {
                $array_product[$row['id']] = $row;
            }
            //lay cac sp dat trong don hang goc
            $array_orders_root = array();
            $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_orders_id WHERE order_id =" . $data_order['order_id'];
            $result = $db->query( $sql );

            while( $row = $result->fetch( ) )
            {
                $row['soluong_tra'] = 0;
                if( $row['isgift'] == 0 ){
                    $array_orders_root[$row['proid']] = $row;
                }else{
                    $array_orders_root[$row['proid']]['numgift'] = $row['num'];
                }
            }

            //lay tat ca don hang tra thoa man dieu kien thoi gian say ra trc don hang nay
            $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_orders WHERE ordertype=0 AND user_id=" . $user_info['userid'] . " AND order_time < " . $data['order_time'] . " AND orderid_refer=" . $data['orderid_refer'] . ' ORDER BY order_time ASC';
            $array_orders_no_root = array();
            $tong_so_hang_da_tra = 0;
            $result = $db->query( $sql );
            while( $row = $result->fetch( ) )
            {
                $data_order['order_total'] = $row['amount_refunded'];
                //Chay lenh cac don hang da tra
                $sql_i = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_orders_id WHERE order_id =" . $row['order_id'];
                $result_i = $db->query( $sql_i );
                while( $row = $result_i->fetch( ) )
                {
                    if( $row['isgift'] == 0 ){
                        if( isset( $array_orders_no_root[$row['proid']]['soluong_tra'] )){
                            $array_orders_no_root[$row['proid']]['soluong_tra'] += $row['num'];
                        }else{
                            $array_orders_no_root[$row['proid']]['soluong_tra'] = $row['num'];
                        }

                    }else{
                        if( isset( $array_orders_no_root[$row['proid']]['soluong_gift_tra'] )){
                            $array_orders_no_root[$row['proid']]['soluong_gift_tra'] += $row['num'];
                        }else{
                            $array_orders_no_root[$row['proid']]['soluong_gift_tra'] = $row['num'];
                        }
                    }
                }
            }
            $array_ordersid_current = array();

            $sql_i = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_orders_id WHERE order_id =" . $data['order_id'];//don hang tra dang xem
            $result_i = $db->query( $sql_i );
            while( $row = $result_i->fetch( ) )
            {
                $array_ordersid_current[$row['proid']] = $row;

            }

            foreach ( $array_orders_root as $proid => $data_root ){

                if( isset( $array_ordersid_current[$proid] )){
                    $row = $array_ordersid_current[$proid];
                }else{
                    //lay so luong sp k tra
                    $row = $data_root;
                    $row['num'] = 0;
                    $row['isgift'] = 0;
                }

                $row['product_title'] = $array_product[$row['proid']]['title'];
                $row['product_price'] = 0;
                $row['num_return'] = $row['num'];//san pham tra dot nay
                $row['product_price'] = $array_product[$row['proid']]['price_retail'];//gia ban le

                if( $row['isgift'] == 0 ){
                    //so luong sp qua cua lan tra hag
                    $numpro_available = $array_orders_root[$row['proid']]['num'] - ($array_orders_no_root[$row['proid']]['soluong_tra'] + $row['num']);

                    $row['total_product'] = $array_orders_root[$row['proid']]['num'];
                    $row['numreturned'] = intval( $array_orders_no_root[$row['proid']]['soluong_tra'] );
                    $row['price'] = nv_get_price_for_agency($row['product_price'], $row['proid'], $row['total_product'] - $array_orders_no_root[$row['proid']]['soluong_tra'] );

                    $row['rebook_number'] = $array_orders_root[$row['proid']]['num'] - ($array_orders_no_root[$row['proid']]['soluong_tra'] + $row['num']);//lay so luong sp con lai khi thuc hien lenh tra hang
                    $row['rebook_price'] = $row['price'];//tam thoi lay theo gia nhap ban dau nv_get_price_for_agency($row['product_price'], $row['proid'], $row['rebook_number'] );//lay gia nhap cho cac sp con lai trong don hang moi

                    $row['rebook_total_price'] = $row['rebook_price'] * $row['rebook_number'];

                    if( $row['type_return'] == 2 ){
                        $timebook = $data['order_time'] - $data_order['order_time'];

                        $timebook = ($timebook / 86400);//quy ra ngay
                        $timebook = ceil($timebook / 30);

                        if( isset( $array_defined_return_order[$timebook] ) ){
                            $percent = $array_defined_return_order[$timebook];
                            $row['message'] = sprintf( $lang_module['order_is_allow_return'], $timebook,$array_defined_return_order[$timebook] .'%', number_format($row['price'], 0, '.', ','));
                        }else{
                            $percent = $array_defined_return_order[3];//toi da la key so ba
                            $row['message'] = sprintf( $lang_module['order_is_allow_return_2'], 3,$percent .'%', number_format($row['price'], 0, '.', ','));

                            //$percent = 100;
                            //$row['message'] = $lang_module['order_is_not_allow_return'];
                        }
                        $row['price'] = round( (($row['price'] * $percent)/100));
                    }else{
                        $row['price'] = 0;
                    }
                    $row['price_total'] = $row['num'] * $row['price'];
                }else{
                    $row['numreturned'] = intval( $array_orders_no_root[$row['proid']]['soluong_gift_tra'] );
                    $row['total_product'] = $array_orders_root[$row['proid']]['numgift'];
                    $row['rebook_number'] = $array_orders_root[$row['proid']]['numgift'] - ($array_orders_no_root[$row['proid']]['soluong_gift_tra'] + $row['num']);//lay so luong sp con lai khi thuc hien lenh tra hang
                }

                $array_orders_id[$row['proid']] = $row;
            }

            $agency_of_you = array();
            if( $data_order['chossentype'] != 3 ){
                //khong phai khach le thi lay thong tin NPP
                $sql = 'SELECT datatext FROM ' . NV_TABLE_AFFILIATE . '_users WHERE userid=' . $data_order['customer_id'];
                list( $datatext ) = $db->query($sql)->fetch(3);
                $datatext = unserialize( $datatext );

                if( isset( $array_agency[$datatext['agencyid']] )){
                    $agency_of_you = $array_agency[$datatext['agencyid']];
                }

            }
        }
    }


    $a = 1;
    $array_transaction = array();
    $result = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_transaction WHERE order_id=' . $order_id . ' ORDER BY transaction_id DESC');

    if ($result->rowCount()) {
        while ($row = $result->fetch()) {

            $row['a'] = $a ++;
            $row['transaction_time'] = nv_date('H:i:s d/m/y', $row['transaction_time']);
            $row['order_id'] = (! empty($row['order_id'])) ? $row['order_id'] : '';
            $row['payment_time'] = (! empty($row['payment_time'])) ? nv_date('H:i:s d/m/y', $row['payment_time']) : '';
            $row['payment_id'] = (! empty($row['payment_id'])) ? $row['payment_id'] : '';

            $row['payment_amount'] = number_format( $row['payment_amount'], 0, '.', ',');
            if ($row['transaction_status'] == 4) {
                $row['transaction'] = $lang_module['history_payment_yes'];
            }  elseif ($row['transaction_status'] == 5) {
                $row['transaction'] = $lang_module['history_payment_return'];
            } elseif ($row['transaction_status'] == 3) {
                $row['transaction'] = $lang_module['history_payment_cancel'];
            } elseif ($row['transaction_status'] == 2) {
                $row['transaction'] = $lang_module['history_payment_check'];
            } elseif ($row['transaction_status'] == 1) {
                $row['transaction'] = $lang_module['history_payment_send'];
            } elseif ($row['transaction_status'] == 0) {
                $row['transaction'] = $lang_module['history_payment_no'];
            } elseif ($row['transaction_status'] == - 1) {
                $row['transaction'] = $lang_module['history_payment_wait'];
            } else {
                $row['transaction'] = 'N/A';
            }
            if ($row['userid'] > 0) {
                $username = $db->query('SELECT username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $row['userid'])->fetchColumn();
                $row['payment'] = $username;
            }else {
                $row['payment'] = 'N/A';
            }
            $array_transaction[] = $row;
        }

    }
    //print_r($array_orders_id);die;
    $contents = detail_order_return_view($array_orders_id, $data_order, $data, $agency_of_you, $array_transaction );
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
    exit();
    if (empty($data)) {
        Header('Location: ' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cart', true));
        die();
    }

    // Thong tin chi tiet mat hang trong don hang
    $listid = $listnum = $listprice = $listnumgif = $isgift = array();
    $result = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_orders_id WHERE order_id=' . $order_id);
    while ($row = $result->fetch()) {
        $listid[] = $row['proid'];
        $isgift[] = $row['isgift'];
        $listnum[$row['proid']] = $row['num'];
        $listprice[$row['proid']] = $row['price'];
    }
    $total_gif = array_sum( $listnumgif );
    if (! empty($listid)) {

        foreach ($listid as $id) {

            $sql = 'SELECT t1.*, t2.title AS unit_title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_units AS t2, ' . NV_PREFIXLANG . '_' . $module_data . '_product AS t1 WHERE t1.unit=t2.id AND t1.id =' . $id . ' AND t1.status =1';
            $result = $db->query($sql);
            if ($result->rowCount()) {
                $row = $result->fetch();
                $row['price_order'] = number_format( $listprice[$i], 0, '.', ',');
                $row['order_number'] = $listnum[$i];
                $row['order_numbergif'] = $listnumgif[$i];
                $price = $listprice[$i] * $listnum[$i];
                $row['isgift'] = $isgift[$i];
                $row['total_price'] = $row['order_number'] * $row['price_order'];
                $row['unit_product'] = $array_unit_product[$row['unit']]['title'];
                $data_pro[] = $row;
            }
        }

    }

    if ($data['status'] == 4) {
        $data['transaction_name'] = $lang_module['history_payment_yes'];
    } elseif ($data['status'] == 3) {
        $data['transaction_name'] = $lang_module['history_payment_cancel'];
    } elseif ($data['status'] == 2) {
        $data['transaction_name'] = $lang_module['history_payment_check'];
    } elseif ($data['status'] == 1) {
        $data['transaction_name'] = $lang_module['history_payment_send'];
    } elseif ($data['status'] == 0) {
        $data['transaction_name'] = $lang_module['history_payment_no'];
    } elseif ($data['status'] == - 1) {
        $data['transaction_name'] = $lang_module['history_payment_wait'];
    } else {
        $data['transaction_name'] = 'ERROR';
    }

    $contents = call_user_func('payment', $data, $data_pro, $total_gif );

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
} else {
    Header('Location: ' . NV_BASE_SITEURL);
    die();
}
