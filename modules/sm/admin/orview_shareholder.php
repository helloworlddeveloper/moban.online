<?php



/**

 * @Project NUKEVIET 4.x

 * @Author VINADES.,JSC (contact@vinades.vn)

 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved

 * @License GNU/GPL version 2 or any later version

 * @Createdate 2-10-2010 18:49

 */

if (! defined('NV_IS_FILE_ADMIN')) {

    die('Stop!!!');
}



$table_name = NV_PREFIXLANG . '_' . $module_data . '_orders';
if( $nv_Request->isset_request( 'active_pay', 'get')){

    $contents = '';

    $order_id = $nv_Request->get_int('order_id', 'get', 0);
    $save = $nv_Request->get_string('save', 'post,get', '');
    $action = $nv_Request->get_string('action', 'post,get', '');
    $payment_amount = $nv_Request->get_title('payment_amount', 'post,get', '');
    $payment_amount = str_replace('.', '', $payment_amount );

    $result = $db->query('SELECT * FROM ' . $table_name . ' WHERE order_id=' . $order_id);
    $data_content = $result->fetch();

    $result = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_transaction WHERE order_id=' . $order_id );
    $array_data_transaction = $result->fetchAll();
    $total_payment_amount = 0;
    foreach ( $array_data_transaction as $data_transaction ){
        $total_payment_amount += $data_transaction['payment_amount'];
    }
    if (empty($data_content) or empty($action)) {
        $contents = $lang_module['order_submit_pay_error'];
    }elseif( $payment_amount > $data_content['order_total'] + $total_payment_amount ){
        $contents = $lang_module['payment_amount_error'];
    }

    if (empty( $contents ) && $save == 1) {
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
        } elseif ($action == 'feeship')
        {
            $feeship = $nv_Request->get_title('payment_amount', 'post,get', '');
            $feeship = str_replace('.', '', $feeship );
            $db->query("UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_orders SET status=5, feeship = " . $feeship . " WHERE order_id=" . $order_id);
            nv_insert_logs(NV_LANG_DATA, $module_name, 'Action Ship order ', "Order code: " . $data_content['order_code'], $admin_info['userid']);
            $contents = $lang_module['order_submit_ship_ok'];

            $nv_Cache->delMod($module_name);
        } elseif ($action == 'pay') {
            $transaction_status = 4;
            $payment_id = 0;

            $payment_data = '';
            $payment = '';
            $userid = $admin_info['userid'];

            //chi ghi nhan hanh dong nay lan dau tien
            if( $data_content['status'] < 4 ){
                save_statistic_customer( $data_content['customer_id'], $data_content['order_total'] );//ghi doanh so thang cho TV dat han
                if( isset( $site_mods['affiliate'])){
                    require NV_ROOTDIR . '/modules/affiliate/affiliate.class.php';
                    $data_content['customer_id'];
                    $userid = nv_get_customer_refer( $data_content['customer_id'] );//Lay userid quan ly agency nay
                    //$userid = $data_content['user_id'];
                    $money = $data_content['order_total'];
                    $content_tracstion = 'Chiếu khấu từ đơn hàng có ID: ' . $data_content['order_id'];
                    $mod_name = $module_name;
                    $product_id = $data_content['order_id'];
                    $affiliateClass = new moneyService( $userid, $money, $content_tracstion, $mod_name, $product_id, false );
                    $subject  = 'Thong bao co don hang do ban gioi thieu duoc hoan thanh!';
                    $message = 'Noi dung don hang';
                    //ham gui mail thong bao
                    //$affiliateClass->sendmail_notification($subject, $message);
                    //goi ham cong tien triet khau
                    $affiliateClass->callActionMoney(1);
                }
                $db->query("UPDATE " . NV_PREFIXLANG  . "_" . $module_data . "_orders SET status=" . $transaction_status . " WHERE order_id=" . $order_id);
            }

            $transaction_id = $db->insert_id("INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_transaction (transaction_id, transaction_time, transaction_status, order_id, userid, payment, payment_id, payment_time, payment_amount, payment_data) VALUES (NULL, " . NV_CURRENTTIME . ", '" . $transaction_status . "', '" . $order_id . "', '" . $userid . "', '" . $payment . "', '" . $payment_id . "', " . NV_CURRENTTIME . ", '" . $payment_amount . "', '" . $payment_data . "')");
            if ($transaction_id > 0) {
                $db->query("UPDATE " . NV_PREFIXLANG  . "_" . $module_data . "_orders SET price_payment=price_payment+" . $payment_amount . " WHERE order_id=" . $order_id);
                nv_insert_logs(NV_LANG_DATA, $module_name, 'Log payment product', "Order code: " . $data_content['order_code'], $admin_info['userid']);
            }

            $contents = $lang_module['order_submit_pay_ok'];


            $nv_Cache->delMod($module_name);
        }

    }

    die($contents);
}
elseif ($nv_Request->isset_request( 'numberout', 'get')){
    $order_id = $nv_Request->get_int('order_id', 'get', 0);
    $data = $nv_Request->get_array('number_out', 'post');

    $result = $db->query('SELECT t1.title, t2.* FROM ' . NV_PREFIXLANG . '_' . $module_data . '_product t1, ' . NV_PREFIXLANG . '_' . $module_data . '_orders_id t2
    WHERE t1.id=t2.proid AND t2.order_id='.$order_id . ' AND isgift=0' );
    while ($row = $result->fetch()) {
        $num_allow = $row['num_com'] - $row['num_out'];
        $num_out = intval( $data[$row['proid']] );

        if( $num_allow < $num_out ){
            exit(sprintf( $lang_module['error_numberout'], $row['title'], $num_allow ));
        }
    }

    foreach ( $data as $proid => $numberout ){
        $num_out = intval( $data[$proid] );
        $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_orders_id_out( order_id, proid, num_out, timeout ) 
                VALUES ( :order_id, :proid, :num_out, ' . NV_CURRENTTIME . ' )';
        $data_insert = array();
        $data_insert['order_id'] = $order_id;
        $data_insert['proid'] = $proid;
        $data_insert['num_out'] = $num_out;
        $orders_id = $db->insert_id($sql, 'id', $data_insert);
        if( $orders_id > 0 ){
            $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_orders_id SET num_out= num_out+' . $num_out . ' WHERE order_id =' . $order_id . ' AND proid=' . $proid );
        }
    }
    exit('OK');
}elseif ($nv_Request->isset_request( 'showpopup', 'get')){
    $order_id = $nv_Request->get_int('order_id', 'get', 0);
    $proid = $nv_Request->get_int('proid', 'get', 0);

    $data_orderid = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_orders_id WHERE order_id='.$order_id . ' AND proid=' . $proid )->fetch();

    $data_orderid_out = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_orders_id_out WHERE order_id='.$order_id . ' AND proid=' . $proid . ' ORDER BY timeout DESC' )->fetchAll();


    $sql = 'SELECT t1.*, t2.title AS unit_title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_units AS t2, ' . NV_PREFIXLANG . '_' . $module_data . '_product AS t1 WHERE t1.unit=t2.id AND t1.id =' . $proid . ' AND t1.status =1';
    $result = $db->query($sql);
    if ($result->rowCount()) {

        $row = $result->fetch();
        $row['product_price'] = number_format( $listprice[$i], 0, '.', ',');
        $row['product_number'] = $data_orderid['num'];
        $row['product_number_out'] = $data_orderid['num_out'];
        $row['price'] = number_format( $data_orderid['price'], 0, '.', ',');
        $price = $data_orderid['price'] * $data_orderid['num'];
        $row['product_price_total'] = number_format( $price, 0, '.', ',');
        $data_pro = $row;
    }

    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('DATA', $data_pro);
    $stt = 1;
    if( !empty( $data_orderid_out )){
        foreach ( $data_orderid_out as $orderinfo ){
            $orderinfo['stt'] = $stt++;
            $orderinfo['timeout'] = date('H:i d/m/Y', $orderinfo['timeout'] );
            $xtpl->assign('DETAIL', $orderinfo);
            $xtpl->parse('loadpopup.data.loop');
        }
        $xtpl->parse('loadpopup.data');
    }else{
        $xtpl->parse('loadpopup.nodata');
    }

    $xtpl->parse('loadpopup');
    $contents = $xtpl->text('loadpopup');
    echo $contents;
    exit;

}


$page_title = $lang_module['order_title'];


$order_id = $nv_Request->get_int('order_id', 'post,get', 0);
$save = $nv_Request->get_string('save', 'post', '');


$result = $db->query('SELECT * FROM ' . $table_name . ' WHERE order_id=' . $order_id);
$data_content = $result->fetch();


if (empty($data_content)) {

    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=order');
}

if ($save == 1 and intval($data_content['transaction_status']) == - 1) {

    $order_id = $nv_Request->get_int('order_id', 'post', 0);
    $transaction_status = 0;
    $payment_id = 0;
    $payment_amount = 0;
    $payment_data = '';
    $payment = '';
    $userid = $admin_info['userid'];

    $db->query("UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_orders SET status=" . $transaction_status . " WHERE order_id=" . $order_id);
    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_process_product', "order_id " . $order_id, $admin_info['userid']);

    $nv_Cache->delMod($module_name);
    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=order');
}


$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=';

// Thong tin chi tiet mat hang trong don hang
$listid = $listnum_out = $listnum = $listprice = array();
$result = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_orders_id WHERE isgift=0 AND order_id='.$order_id );
while ($row = $result->fetch()) {

    $listid[] = $row['proid'];
    $listnum[] = $row['num'];
    $listnum_com[] = $row['num_com'];
    $listnum_out[] = $row['num_out'];
    $listprice[] = $row['price'];
}

$data_pro = array();
$i = 0;
$total = 0;
foreach ($listid as $id) {

    $sql = 'SELECT t1.*, t2.title AS unit_title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_units AS t2, ' . NV_PREFIXLANG . '_' . $module_data . '_product AS t1 WHERE t1.unit=t2.id AND t1.id =' . $id . ' AND t1.status =1';
    $result = $db->query($sql);
    if ($result->rowCount()) {

        $row = $result->fetch();
        $row['product_price'] = number_format( $listprice[$i], 0, '.', ',');
        $row['product_number'] = $listnum[$i];
        $row['product_number_com'] = $listnum_com[$i];
        $row['product_number_out'] = $listnum_out[$i];
        $price = $listprice[$i] * $listnum[$i];
        $total += $price;
        $row['product_price_total'] = number_format( $price, 0, '.', ',');
        $data_pro[] = $row;
        ++ $i;
    }

}

if ($data_content['status'] == '4') {
    $lang_module['order_submit_pay_comfix'] = $lang_module['order_submit_unpay_comfix'];
}

if( $data_content['status'] != 0 ){
    $payment_amount = $data_content['order_total'] - $data_content['price_payment'];
}elseif( $data_content['status'] == 0 ){
    $payment_amount = $data_content['order_total'];
}
if ($data_content['ordertype'] == 2 && $data_content['status'] == 0 ) {
    //  $lang_module['total_payment_no'] = $lang_module['price_book_plane'];
}

$total = $total - $data_content['saleoff'];
$data_content['saleoff'] = number_format( $data_content['saleoff'], 0, '.', ',');
$data_content['price_payment_fomart'] = number_format( $data_content['price_payment'], 0, '.', ',');
$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('dateup', date('d-m-Y', $data_content['order_time']));
$xtpl->assign('moment', date('H:i', $data_content['order_time']));
$xtpl->assign('DATA', $data_content);
$xtpl->assign('order_id', $data_content['order_id']);
$xtpl->assign('total', number_format( $total, 0, '.', ','));

$show_submit = 0;
$stt = 1;
foreach ($data_pro as $pdata) {

    $pdata['stt'] = $stt++;
    $pdata['linkview'] = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE  . '=' . $op . '&showpopup&order_id=' . $order_id . '&proid=' . $pdata['id'];
    $xtpl->assign('PDATA', $pdata);
    if( $pdata['product_number'] != $pdata['product_number_out'] ){
        $xtpl->parse('main.loop.xuathang');
        $show_submit = 1;
    }else{
        $xtpl->parse('main.loop.noxuathang');
    }
    $xtpl->parse('main.loop');
}
if ( $show_submit == 1 ) {
    $xtpl->parse('main.show_submit');
}
if (! empty($data_content['order_note'])) {
    $xtpl->parse('main.order_note');
}

$a = 1;
$array_transaction = array();
$result = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_transaction WHERE order_id=' . $order_id . ' ORDER BY transaction_id DESC');


if ($result->rowCount()) {

    $array_payment = array();
    while ($row = $result->fetch()) {

        $row['a'] = $a ++;
        $row['transaction_time'] = nv_date('H:i:s d/m/y', $row['transaction_time']);
        $row['order_id'] = (! empty($row['order_id'])) ? $row['order_id'] : '';
        $row['payment_time'] = (! empty($row['payment_time'])) ? nv_date('H:i:s d/m/y', $row['payment_time']) : '';
        $row['payment_id'] = (! empty($row['payment_id'])) ? $row['payment_id'] : '';

        if (! empty($row['payment_id'])) {
            $array_payment[] = $row['payment_id'];
        }
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
            $row['transaction'] = 'ERROR';
        }
        if ($row['userid'] > 0) {
            $username = $db->query('SELECT username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $row['userid'])->fetchColumn();
            $row['payment'] = $username;
            $row['link_user'] = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=edit&userid=' . $row['userid'];
        } elseif (isset($array_data_payment[$row['payment']])) {
            $row['link_user'] = $array_data_payment[$row['payment']]['domain'];
            $row['payment'] = $array_data_payment[$row['payment']]['paymentname'];
        } else {

            $row['link_user'] = '#';
        }
        $xtpl->assign('DATA_TRANS', $row);
        $xtpl->parse('main.transaction.looptrans');
    }

    if (! empty($array_payment)) {

        $xtpl->assign('LINK_CHECK_PAYMENT', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&order_id=' . $order_id . '&checkpayment=' . md5($order_id . session_id() . $global_config['sitekey']));
        $xtpl->parse('main.transaction.checkpayment');
    }

    $xtpl->parse('main.transaction');
}

if ($data_content['status'] == 4) {
    $html_payment = $lang_module['history_payment_yes'];
} elseif ($data_content['status'] == 5) {
    $html_payment = $lang_module['history_order_ships'];
}  elseif ($data_content['status'] == 3) {
    $html_payment = $lang_module['history_payment_cancel'];
} elseif ($data_content['status'] == 2) {
    $html_payment = $lang_module['history_payment_check'];
} elseif ($data_content['status'] == 1) {
    $html_payment = $lang_module['history_payment_send'];
} elseif ($data_content['status'] == 0) {
    $html_payment = $lang_module['history_payment_no'];
} elseif ($data_content['status'] == - 1) {
    $html_payment = $lang_module['history_payment_wait'];
} else {
    $html_payment = 'ERROR';
}

$xtpl->assign('payment', $html_payment);

if ($data_content['status'] == - 1) {
    $xtpl->parse('main.onsubmit');
}

$action_pay = $action_ship = '';
$user_permission_ketoan = explode(',', $module_config[$module_name]['ketoan']);
$user_permission_kho = explode(',', $module_config[$module_name]['kho']);

$xtpl->assign('payment_amount', number_format( $payment_amount, 0, ',', '.'));
if ( $data_content['showadmin'] != 0 && ($data_content['status'] < '4' || $data_content['price_payment'] < $data_content['order_total']) && (defined( 'NV_IS_SPADMIN' ) or in_array( $admin_info['userid'], $user_permission_ketoan) )) {
    $action_pay = '&action=pay';
    $xtpl->parse('main.onpay');
}

if ( $data_content['showadmin'] != 0 && ($data_content['ordertype'] == 1 && ($data_content['status'] == '4' or $data_content['status'] == '5') && (defined( 'NV_IS_SPADMIN' ) or in_array( $admin_info['userid'], $user_permission_kho) ) or $data_content['shipcode'] == 1)) {
    $action_ship = '&action=feeship';
    $xtpl->assign('feeship', number_format( $data_content['feeship'], 0, ',', '.'));
    $xtpl->parse('main.feeship');
}


$xtpl->assign('LINK_PRINT', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=print&admin=1&order_id=' . $data_content['order_id'] . '&checkss=' . md5($data_content['order_id'] . $global_config['sitekey'] . session_id()));
$xtpl->assign('URL_ACTIVE_PAY', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&active_pay=1&order_id=' . $order_id . $action_pay);
$xtpl->assign('URL_NUMBER_OUT', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&numberout=1&order_id=' . $order_id);
$xtpl->assign('URL_ACTIVE_SHIP', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&active_pay=1&order_id=' . $order_id . $action_ship);
$xtpl->assign('URL_BACK', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=or_view&order_id=' . $order_id);

$xtpl->parse('main');
$contents = $xtpl->text('main');


$set_active_op = 'order';


include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
