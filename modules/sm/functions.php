<?php

/**

 * @Project NUKEVIET 4.x

 * @Author VINADES.,JSC <contact@vinades.vn>

 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved

 * @License GNU/GPL version 2 or any later version

 * @Createdate 10/03/2010 10:51

 */

if (!defined('NV_SYSTEM')) {
    die('Stop!!!');
}

//kiem tra quyen han truoc khi truy cap module

if ( $op != 'sendsms' && !defined('NV_IS_USER')) {
    $redirect = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;
    Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=login&nv_redirect=' . nv_redirect_encrypt($redirect));
    die();
}
if( defined('NV_IS_USER') ){
    require_once NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';
    $sql = 'SELECT t1.code, t1.possitonid, t1.agencyid, t1.salary_day, t1.benefit, t1.subcatid, t1.datatext, t1.shareholder, t2.userid, t2.username, t2.first_name, t2.last_name, t2.birthday, t2.email FROM ' . NV_TABLE_AFFILIATE . '_users AS t1 INNER JOIN ' . NV_USERS_GLOBALTABLE . ' AS t2 ON t1.userid=t2.userid WHERE t2.userid=' . $user_info['userid'] . ' ORDER BY t1.sort ASC';
    $user_data_affiliate = $db->query($sql)->fetch();
}

//chua phai trong he thong thi khong vao dc chuc nang nay
if( !isset( $user_data_affiliate ) &&  $op != 'sendsms' )
{
    Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=users');
    die();
}

$user_data_affiliate['fullname'] = nv_show_name_user( $user_data_affiliate['first_name'], $user_data_affiliate['last_name'], $user_data_affiliate['username']);
$user_data_affiliate['datatext'] = unserialize( $user_data_affiliate['datatext']);
$user_data_affiliate['agencytitle'] = ( $user_data_affiliate['agencyid']> 0 )? $array_agency[$user_data_affiliate['agencyid']]['title'] : $array_possiton[$user_data_affiliate['possitonid']]['title'];
define('NV_IS_MOD_SM', true);

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_product WHERE status=1 ORDER BY weight';
$array_product = $nv_Cache->db($sql, 'id', $module_name);

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_units';
$array_unit_product = $nv_Cache->db($sql, 'id', $module_name);

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_saleoff';
$array_saleoff = $nv_Cache->db($sql, 'id', $module_name);



//nhap hang cho thanh vien
function nhapkhohanghoa( $customerid, $depotid, $productid, $quantity, $price, $type, $typeorder = 3, $order_id = 0, $num_com = 0 ){

    global $db, $module_data, $user_data_affiliate;

    $customerid = intval($customerid);
    if ( $productid > 0 and ($quantity > 0 ) ) {
        //khong phai khach le
        if( $typeorder != 3 ) {
            $sql = 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_warehouse_logs WHERE customerid=' . $customerid . ' AND productid=' . $productid . ' AND depotid=0';
            $quantity_com = 0;
            $check_exits = $db->query($sql)->fetchColumn();
            if ($check_exits == 0) {
                //kiem tra xem la co dong hay la npp
                list( $shareholder ) = $db->query( 'SELECT shareholder FROM ' . NV_TABLE_AFFILIATE . '_users WHERE userid=' . $customerid )->fetch(3);
                if( $shareholder == 1 ){
                    $quantity_com = $quantity;
                }
                $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_warehouse_logs( customerid, depotid, productid, quantity_in, price_in, quantity_out, price_out, quantity_com ) 
                VALUES ( ' . $customerid . ', 0, ' . $productid . ', ' . $quantity . ', 0, 0, ' . $price . ', ' . $quantity_com . ')';

                $res = $db->query($sql);
            } else {
                //kiem tra xem la co dong hay la npp
                list( $shareholder ) = $db->query( 'SELECT shareholder FROM ' . NV_TABLE_AFFILIATE . '_users WHERE userid=' . $customerid )->fetch(3);
                if( $shareholder == 1 ){
                    $quantity_com = $quantity;
                }
                $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_warehouse_logs SET quantity_in = quantity_in ' . $type . ' ' . $quantity . ', quantity_com = quantity_com ' . $type . ' ' . $quantity_com . ', price_out= price_out ' . $type . ' ' . $price . ' WHERE customerid =' . $customerid . ' AND productid=' . $productid . ' AND depotid=0';
                $res = $db->query($sql);
            }
            save_warehouse_order_customer( $quantity, $price, $customerid, 0, $productid, $type, $order_id );
        }
        if( ($res or $typeorder == 3) && $customerid > 0){
            if( $typeorder != 3 ){
                $sql = 'SELECT parentid FROM ' . NV_TABLE_AFFILIATE . '_users WHERE userid=' . $customerid;
            }else{
                $sql = 'SELECT refer_userid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_customer WHERE customer_id=' . $customerid;
            }
            list( $userid_parent ) = $db->query( $sql )->fetch(3);//neu = 0 thi se tru tai kho tong cty

            $sql = 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_warehouse_logs WHERE customerid=' . $userid_parent . ' AND productid=' .$productid;
            $check_exits = $db->query( $sql )->fetchColumn();
            if( $check_exits == 0 ) $userid_parent = 0;
            //tru so luong trong kho tong ben tren va cong tien cua khach hang nay
            if( $userid_parent == 0 ){
                $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_warehouse_logs SET quantity_out = quantity_out' . $type . ' ' . $quantity . ', price_in= price_in ' . $type . ' ' . $price . ' WHERE customerid =' . $userid_parent . ' AND productid=' . $productid . ' AND depotid=' . $depotid );
            }else{
                $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_warehouse_logs SET quantity_out = quantity_out' . $type . ' ' . $quantity . ', quantity_com = quantity_com-' . intval( $num_com ) . ', price_in= price_in ' . $type . ' ' . $price . ' WHERE customerid =' . $userid_parent . ' AND productid=' . $productid . ' AND depotid=' . $depotid );
            }


            save_warehouse_order_customer( $quantity, $price, $userid_parent, $depotid, $productid, '-', $order_id );
        }
    }
}


