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
    $contents_msg = "";

    if (! is_numeric($num) || $num < 0) {
        $contents_msg = 'ERR_' . $lang_module['cart_set_err'];
    } elseif ($id > 0) {
        $_SESSION[$module_data . '_cart'][$id]['num'] = $num;
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

    //$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_product WHERE status =1 ORDER BY weight";//all product
    $sql = "SELECT t1.*, ifnull(t2.quantity_in,0) as num FROM " . NV_PREFIXLANG . "_" . $module_data . "_product t1
                LEFT OUTER JOIN " . NV_PREFIXLANG . "_" . $module_data . "_warehouse_logs t2 ON t1.id = t2.productid AND t2.customerid = " . $user_info['userid'] . "
            WHERE t1.status = 1 ORDER BY t1.weight";//product with discount
    //die($sql);

    $result = $db->query( $sql );

    $resetcart = $nv_Request->isset_request('resetcart', 'get');
    $array_product = array();
    while( $row = $result->fetch( ) )
    {
        if( !isset( $_SESSION[$module_data . '_cart'][$row['id']] ) || $resetcart){
            $_SESSION[$module_data . '_cart'][$row['id']]['num'] = $row['num'];
        }
        $array_product[$row['id']] = $row;
    }
    //print_r($_SESSION[$module_data . '_cart']);
    $data_content = array();
    foreach( $_SESSION[$module_data . '_cart'] as $pro_id => $pro_info )
    {
        if( intval( $pro_id ) > 0 && isset($array_product[$pro_id])){
            $row = $array_product[$pro_id];
            $number = $_SESSION[$module_data . '_cart'][$row['id']]['num'];
            $row['cartnumber'] = $number;
            $data_content[$pro_id] = $row;
        }
        else if( intval( $pro_id ) > 0 && !isset($array_product[$pro_id])){
            unset( $_SESSION[$module_data . '_cart'][$pro_id]);
        }
    }

    $contents = cart_product_load_warehouse_init($data_content, $agency_of_you);
    include NV_ROOTDIR . '/includes/header.php';
    echo $contents;
    include NV_ROOTDIR . '/includes/footer.php';
    exit;
}

if( $nv_Request->isset_request('submit', 'post') ) {
    //khoi tao kho hang
    $sql = 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_warehouse WHERE customerid=' . $user_info['userid'];
    $check_exits = $db->query( $sql )->fetchColumn();
    if( $check_exits == 0 ){
        $title = $lang_module['warehouse_off'] . $user_data_affiliate['code'];
        $note = $lang_module['warehouse_off_create_date'] . NV_CURRENTTIME;
        nvCreatWarehouse( $user_info['userid'], $title, $note );
    }
    //kiểm tra nếu chưa có giao dịch thì mới khỏi tạo tồn kho
    $sql = 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_orders WHERE customer_id=' . $user_info['userid'] . ' or user_id=' . $user_info['userid'];
    //echo $sql;
    $check_exits = $db->query($sql)->fetchColumn();
    //echo $check_exits;die();
    if ($check_exits == 0) {
        //xóa tồn kho cũ
        $sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_warehouse_logs WHERE customerid=' . $user_info['userid'] . ' AND depotid=0';
        $db->query($sql);

        $sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_warehouse_order WHERE customerid=' . $user_info['userid'] . ' AND depotid=0';
        $db->query($sql);

        //khoi tao ton kho
        foreach ( $_SESSION[$module_data . '_cart'] as $pro_id => $order_product ) {
            if( $order_product['num'] > 0  ){
                //tăng tồn kho
                $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_warehouse_logs( customerid, depotid, productid, quantity_in, price_in, quantity_out, price_out, quantity_com ) 
                    VALUES ( ' . $user_info['userid'] . ',0 , ' . $pro_id . ', ' . $order_product['num']  . ', 0, 0, 0, 0)';
                $db->query($sql);

                //cập nhật giao dịch
                $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_warehouse_order( customerid, depotid, productid, orderid, quantity_befor, quantity_in, price_in, quantity_after, quantity_out, price_out, addtime ) 
                    VALUES ( :customerid, 0, :productid, 0, 0, :quantity_in, 0, :quantity_after, 0, 0, :addtime )';
                $data_insert = array();
                $data_insert['customerid'] = $user_info['userid'];
                $data_insert['productid'] = $pro_id;
                $data_insert['quantity_in'] = $order_product['num'];
                $data_insert['quantity_after'] = $order_product['num'];
                $data_insert['addtime'] = NV_CURRENTTIME;
                $db->insert_id($sql, '', $data_insert);
            }
        }
    }

    // Chuyen ve trang chu
    unset($_SESSION[$module_data . '_cart']);
    unset($_SESSION[$module_data . '_order_info']);
    echo "<script>alert('Cập nhật tồn kho thành công');</script>";
    Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=affiliate&op=maps');
    exit();
}

$page_title = $lang_module['cart_title'];

$contents = call_user_func( 'cart_product_warehouse_init', $error );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
