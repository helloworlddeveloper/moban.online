<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 19 Jun 2014 09:53:21 GMT
 * Lay thong tin don hang tu don hang dat truoc cua npp
 */

if ($userid > 0 )
{
    $sql = 'SELECT * FROM ' . NV_IS_LANG_TABLE_SM . '_warehouse_logs WHERE customerid=' . $userid;
    $result = $db->query( $sql );
    $data_warehouse_logs = array();
    while ( $row = $result->fetch()){
        $row['stockin'] = $row['quantity_in'] - $row['quantity_out'];
        $data_warehouse_logs[$row['productid']] = $row;
    }
    $array_order_book_plane = array();
    $sql = 'SELECT t1.*, t2.agencyid FROM ' . NV_IS_LANG_TABLE_SM . '_orders t1, ' . NV_IS_TABLE_AFFILIATE . '_users t2 WHERE t1.customer_id=t2.userid AND t1.ordertype=2 AND t1.status=4 AND t1.user_id=' . $userid;
    $result = $db->query( $sql );
    while ( $row = $result ->fetch()){

        $price_for_discount = isset( $array_agency[$row['agencyid']] )? $array_agency[$row['agencyid']]['price_for_discount'] : 0;
        $price_discount = isset( $array_agency[$row['agencyid']] )? $array_agency[$row['agencyid']]['price_discount'] : 0;

        $row['price_total_discount'] = 0;
        if( $price_for_discount > 0 and $price_discount >0 ){
            $row['price_total_discount'] = floor($row['order_total'] / $price_for_discount ) * $price_discount;
        }
        $amount = $row['order_total'] - $row['price_total_discount'];
        $debt = $amount - $row['payment'];

        $sql_i = 'SELECT t1.code,t1.title,t1.image,t1.price_retail,t2.proid, t2.num FROM ' . NV_IS_LANG_TABLE_SM . '_product t1, ' . NV_IS_LANG_TABLE_SM . '_orders_id t2 WHERE t1.id=t2.proid AND t2.order_id=' . $row['order_id'];
        $result_i = $db->query( $sql_i );
        $array_product = array();
        while ( $row_i = $result_i ->fetch()){
            $array_product[] = array(
                'productid' => $row_i['proid'],
                'productid' => $row_i['proid'],
                'productcode' => $row_i['code'],
                'productname' => $row_i['title'],
                'productprice' => number_format( $row_i['price_retail'],0, '.', ','),
                'productimage' => !empty( $row_i['image'] )? NV_MY_DOMAIN . NV_BASE_SITEURL . NV_ASSETS_DIR . "/sm/" . $row_i['image'] : '',
                'stockin' => $data_warehouse_logs[$row_i['proid']]['stockin'],
                'quantity' => $row_i['num'],
            );
        }

        $array_order_book_plane[] = array(
            'orderid' => $row['order_id'],
            'ordercode' => $row['order_code'],
            'orderdate' => date('d/m/y H:i', $row['order_time']),
            'customer' => $row['order_name'],
            'customerid' => $row['customer_id'],
            'userid' => $row['user_id'],
            'email' => $row['order_email'],
            'mobile' => $row['order_phone'],
            'address' => $row['order_address'],
            'ordertotal' => number_format( $row['order_total'], 0, '.', ','),
            'bonus' => number_format( $row['price_total_discount'], 0, '.', ','),
            'amount' => number_format( $amount, 0, '.', ','),
            'payment' => number_format( $row['payment'], 0, '.', ','),
            'debt' => number_format( $debt, 0, '.', ','),
            'status' => $lang_module['status_ordertype_' . $row['status']],
            'products' => $array_product
        );
    }
    echo json_encode( $array_order_book_plane );
}
else
{
    echo json_encode(array()); 
}
