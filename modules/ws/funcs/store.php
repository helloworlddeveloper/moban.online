<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 19 Jun 2014 09:53:21 GMT
 * API tra ve sp va ton kho cua NPP
 */


$productid = $nv_Request->get_int('productid', 'post', 0);
$fromdate = $nv_Request->get_title('fromdate', 'post');
$todate = $nv_Request->get_title('todate', 'post');
$status = $nv_Request->get_int('status', 'post');
if ($userid > 0)
{
    if( $productid > 0 ){
        $sql = "SELECT t1.id,t1.code,t1.title,t1.image,t1.price_retail,t2.quantity_in,t2.quantity_out FROM " . NV_PREFIXLANG . "_sm_product t1, " . NV_PREFIXLANG . "_sm_warehouse_logs t2 WHERE t1.id=t2.productid AND t1.id = " . $productid . " AND t2.customerid= " . $userid . " ORDER BY weight ASC";
    }else{
        $sql = "SELECT t1.id,t1.code,t1.title,t1.image,t1.price_retail,t2.quantity_in,t2.quantity_out FROM " . NV_PREFIXLANG . "_sm_product t1, " . NV_PREFIXLANG . "_sm_warehouse_logs t2 WHERE t1.id=t2.productid AND t2.customerid= " . $userid . " ORDER BY weight ASC";
    }
    $result = $db->query( $sql );
    $store = array();
    $i = 0;
    while( $row = $result->fetch() )
    {
        $stockin = $row['quantity_in'] - $row['quantity_out'];
        $store[$i] = array();
        $store[$i]["productId"] = $row['id'];
        $store[$i]["productCode"] = $row['code'];
        $store[$i]["productName"] = $row['title'];
        $store[$i]["productPrice"] =  $row['price_retail'];
        $store[$i]["productPrice_format"] = number_format( $row['price_retail'], 0, '.', ',');
        if( !empty( $row['image'] )){
            $store[$i]["productImage"] = NV_MY_DOMAIN . NV_BASE_SITEURL . NV_ASSETS_DIR . "/sm/" . $row['image'];
        }else{
            $store[$i]["productImage"] = '';
        }
        $store[$i]["stockIn"] = $stockin;

        $i++;
    }

    $sql = 'SELECT t1.userid, t1.code, t1.possitonid, t1.agencyid, t1.numsubcat, t1.subcatid, t1.provinceid, t2.username, concat(t2.last_name, " ", t2.first_name) fullname, t2.email, t1.mobile 
    FROM ' . NV_IS_TABLE_AFFILIATE . '_users t1, ' . NV_USERS_GLOBALTABLE . ' t2 WHERE t1.userid = t2.userid AND status=1 AND t1.userid=' . $userid;
    $res = $db->query( $sql );
    $array_data = $res->fetch();
    $region = isset( $array_province[$array_data['provinceid']] )? $array_province[$array_data['provinceid']]['title'] : 'N/A';
    $array = array(
        "userid" => $array_data['userid'],
        "usercode" => $array_data['code'],
        "username" => $array_data['fullname'],
        "mobile" => $array_data['mobile'],
        "region" => $region,
        "orders" => $store
    );    
    echo json_encode($array);
}
else
{
    echo json_encode(array()); 
}