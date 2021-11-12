<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if( !defined( 'NV_IS_FILE_ADMIN' ) )
{
    die( 'Stop!!!' );
}
$array_search = array();
$array_warehouse = array();
$per_page = 50;
$page = $nv_Request->get_int('page', 'post,get', 1);
$gift_id = $nv_Request->get_int('id', 'post,get', 0);
$agencyid = $nv_Request->get_int('sta', 'post,get', 0);

if( $gift_id == 0 ){
    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=or_gift');
    die();
}
else{
    $sql = "SELECT t1.userid, t1.username, concat(t1.first_name, ' ', t1.last_name) as fullname, t1.phone, t1.email, t1.address, t1.purchase_points, IFNULL(agencyid,0) as agencyid
            FROM nv4_users AS t1      
                INNER JOIN nv4_vi_sm_customer_gifts t2 ON t1.userid = t2.userid         
            WHERE t2.id=" . $gift_id;
    $customer = $db->query($sql)->fetch();

    $sql = "SELECT t1.* 
            FROM nv4_vi_sm_giftcode AS t1              
            WHERE t1.code=(SELECT gift FROM nv4_vi_sm_customer_gifts WHERE id = " . $gift_id . ")";
    //echo $sql;
    $gift = $db->query($sql)->fetch();
}

$productid = $nv_Request->get_int('pid', 'post,get', 0);
$num = $nv_Request->get_int('num', 'post,get', 0);
if ($nv_Request->isset_request('num', 'post, get') && $num > 0) {
    if ($productid > 0) {//tặng quà là sản phẩm => tạo đơn hàng
        try{
            $precode = 'QT%01s';
            $result = $db->query("SHOW TABLE STATUS WHERE Name='" .  NV_PREFIXLANG . '_' . $module_data . "_orders'");
            $item = $result->fetch();
            $result->closeCursor();
            $order_code = vsprintf($precode, $item['auto_increment']);

            $customer_id = $customer['agencyid'] == 0 ? $customer['userid'] : $customer['agencyid'];
            $status = OD7_FINISHED;

            $sql = "INSERT INTO " .  NV_PREFIXLANG . '_' . $module_data . "_orders (
            customer_id, order_code, order_name, order_email, order_phone, 
            order_address, order_note, user_id, admin_id, order_total, order_time, edit_time, postip, 
            saleoff, feeship, price_payment, shipcode, showadmin, chossentype, ordertype, orderid_refer, amount_refunded, depotid, status, producttype, paymentpoint
            ) VALUES (
                " . $customer_id . ", :order_code, :order_name, :order_email, :order_phone, :order_address, :order_note,
                " . $admin_info['userid'] . ", 0,0,
                " . NV_CURRENTTIME . ", 0, :ip, 0, 0, 0, 0, 1, 0, -1, 0, 0, -1, " . $status . ", 0, 0
            )";

            $data_insert = array( );
            $data_insert['order_code'] = $order_code;
            $data_insert['order_name'] = $customer['fullname'];
            $data_insert['order_email'] = $customer['email'];
            $data_insert['order_phone'] = $customer['phone'];
            $data_insert['order_address'] = $customer['address'];
            $data_insert['order_note'] = 'Công ty trả thưởng thẻ cào';
            $data_insert['ip'] = $client_info['ip'];
            //echo $order_code . ":" . $customer_id.":".$admin_info['userid'];
            //print_r($data_insert);die();
            $order_id = $db->insert_id($sql, 'order_id', $data_insert);
            if ($order_id > 0) {
                //khoi tao kho hang theo tung khach hang
                $sql = 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_warehouse WHERE customerid=' . $customer_id;
                $check_exits = $db->query( $sql )->fetchColumn();
                if( $check_exits == 0 ){
                    $title = $lang_module['warehouse_off'] . $user_data_affiliate['code'];
                    $note = $lang_module['warehouse_off_create_date'] . NV_CURRENTTIME;
                    //khoi tao kho hang
                    $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_warehouse( customerid, title, note, addtime, price_discount_in, price_discount_out )
                 VALUES ( ' . $customer_id . ', ' . $db->quote( $title ) . ', ' . $db->quote( $note ) . ', ' . NV_CURRENTTIME . ',0 ,0 )';
                    $db->query($sql);
                }

                //Them chi tiet don hang
                $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_orders_id( order_id, proid, num, type_return, numreturn, price, num_out, num_com, isgift, point ) 
                        VALUES ( :order_id, :proid, :num, 0, 0, :price, 0, 0, 0, :point )';
                $data_insert = array();
                $data_insert['order_id'] = $order_id;
                $data_insert['proid'] = $productid;
                $data_insert['num'] = $num;
                $data_insert['price'] = 0;
                $data_insert['point'] = 0;
                $order_i = $db->insert_id($sql, 'id', $data_insert);
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }

    }

    //cập nhật trạng thái trả thưởng
    $sql = 'SELECT agencyid, historyusers FROM ' . NV_PREFIXLANG . '_' . $module_data . '_customer_gifts WHERE id=' . $gift_id;
    list( $old_agencyid, $historyusers ) = $db->query( $sql )->fetch(3);//neu = 0 thi se tao tai khoan
    $historyusers = empty($historyusers) ? $old_agencyid : (empty($old_agencyid) ? $historyusers : $historyusers . ',' . $old_agencyid);
//echo $old_agencyid . ":" . $historyusers . ":" . $agencyid . ":" . $gift_id; die();
    $stmt = $db->prepare("UPDATE " . NV_PREFIXLANG . '_' . $module_data . "_customer_gifts SET status = 1, agencyid = :agencyid, historyusers = :historyusers, updated_date = " .  NV_CURRENTTIME . " WHERE id = :id");
    $stmt->bindParam(':agencyid', $agencyid, PDO::PARAM_INT);
    $stmt->bindParam(':historyusers', $historyusers, PDO::PARAM_STR);
    $stmt->bindParam(':id', $gift_id, PDO::PARAM_INT);
    $stmt->execute();

    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=or_gift');
    die();
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('NV_UPLOADS_DIR', NV_UPLOADS_DIR);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('SEARCH', $array_search);

$xtpl->assign('DATA_USER', $customer);
$xtpl->assign('customer_id', $customer_id);

//load quà tặng
if ($gift) {
    $gift['STT'] = 1;
    //if ($gift['productid'] > 0 && $gift['quantity'] > 0)
        $gift['link_exchange_gift'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=exchange_gifts&amp;id=' . $gift_id . '&amp;sta=' . $agencyid . '&amp;pid=' . $gift['productid'] . '&amp;num=' . $gift['quantity'];
    //else
    //    $gift['Note'] = '';
    $xtpl->assign('GIFT', $gift);
    $xtpl->parse('main.or_gift');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = 'Trả thưởng thẻ cào';

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';

