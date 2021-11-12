<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 19 Jun 2014 09:53:21 GMT
 */

if ($userid > 0)
{

    $search['productid'] = $nv_Request->get_int('productid', 'post', 0);
    $search['date_from'] = $nv_Request->get_title('fromdate', 'post', '');
    $search['date_to'] = $nv_Request->get_title('todate', 'post', '');

    if (! empty($search['date_from'])) {
        if (! empty($search['date_from']) and preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $search['date_from'], $m)) {
            $date_from = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
        } else {
            $date_from = NV_CURRENTTIME;
        }
        $where .= ' AND t1.addtime >= ' . $date_from;
    }

    if (! empty($search['date_to'])) {
        if (! empty($search['date_to']) and preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $search['date_to'], $m)) {
            $date_to = mktime(23, 59, 59, $m[2], $m[1], $m[3]);
        } else {
            $date_to = NV_CURRENTTIME;
        }
        $where .= ' AND t1.addtime <= ' . $date_to . '';
    }
    if ($search['productid'] > 0) {
        $where .= ' AND t1.productid = ' . $productid;
    }
    $db->sqlreset()
        ->select('t1.*, t2.title,t2.code,t2.image,t2.price_retail')
        ->from(NV_IS_LANG_TABLE_SM . '_warehouse_order t1')
        ->order('t2.id, t1.addtime')
        ->join('INNER JOIN ' . NV_IS_LANG_TABLE_SM . '_product t2 ON t1.productid=t2.id WHERE t1.customerid=' . $userid . $where);

    $result = $db->query( $db->sql() );
    $array_statistic = array();
    $total_order = array();
    $total_price = 0;
    while( $view = $result->fetch() )
    {
        $total_order[$view['orderid']] = 1;
        $total_price += $view['price_in'];
        //lay ton dau
        if( !isset( $array_statistic[$view['productid']]['begin'])){
            $array_statistic[$view['productid']]['begin'] = $view['quantity_befor'];
        }
        //lay ton cuoi
        $array_statistic[$view['productid']]['end'] = $view['quantity_after'];

        if( isset( $array_statistic[$view['productid']]['quantity_in'] )){
            $array_statistic[$view['productid']]['quantity_in'] += $view['quantity_in'];
            $array_statistic[$view['productid']]['quantity_out'] += $view['quantity_out'];
        }else{
            $array_statistic[$view['productid']]['quantity_in'] = $view['quantity_in'];
            $array_statistic[$view['productid']]['quantity_out'] = $view['quantity_out'];
        }

        $array_statistic[$view['productid']]['productId'] = $view['productid'];
        $array_statistic[$view['productid']]['productCode'] = $view['code'];
        $array_statistic[$view['productid']]['productName'] = $view['title'];
        $array_statistic[$view['productid']]['productPrice'] = number_format( $view['price_retail'], 0, '.', ',');
        $array_statistic[$view['productid']]['productImage'] = !empty( $view['image'] )? NV_MY_DOMAIN . NV_BASE_SITEURL . NV_ASSETS_DIR . "/sm/" . $view['image'] : '';

    }
    $store = array();
    $i = 0;
    if( !empty( $array_statistic )){
        foreach ( $array_statistic as $statistic){
            $store[$i] = array();
            $store[$i]["productId"] = $statistic['productId'];
            $store[$i]["productCode"] = $statistic['productCode'];
            $store[$i]["productName"] = $statistic['productName'];
            $store[$i]["productPrice"] = $statistic['productPrice'];
            $store[$i]["productImage"] = $statistic['productImage'];
            $store[$i]["stockStart"] = $statistic['begin'];
            $store[$i]["stockIn"] = $statistic['quantity_in'];
            $store[$i]["stockOut"] = $statistic['quantity_out'];
            $store[$i]["stockEnd"] = $statistic['end'];
            $i++;
        }
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
        "totalorder" => count( $total_order ),
        "totalsales" => number_format( $total_price, 0, '.', ','),
        "orders" => $store
    );
    echo json_encode($array);
}
else
{
    echo json_encode(array());
}