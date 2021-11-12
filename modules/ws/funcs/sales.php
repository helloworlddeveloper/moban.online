<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 19 Jun 2014 09:53:21 GMT
 */

$search = array();
if ( $userid > 0) {

    $transaction_status = array(
        '4' => $lang_module['history_payment_yes'],
        '5' => $lang_module['history_order_ships'],
        '0' => $lang_module['history_payment_no']);

    $search['order_code'] = $nv_Request->get_title('order', 'post', '');
    $search['date_from'] = $nv_Request->get_title('fromdate', 'post', '');
    $search['date_to'] = $nv_Request->get_title('todate', 'post', '');
    $search['status'] = $nv_Request->get_title('status', 'post', '');

    $per_page = 20;
    $page = $nv_Request->get_int('page', 'post', 1);
    $where = 'user_id=' . $userid;
    if (! empty($search['order_code'])) {
        $where .= ' AND order_code like "%' . $search['order_code'] . '%"';
    }
    if (! empty($search['date_from'])) {
        if (! empty($search['date_from']) and preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $search['date_from'], $m)) {
            $search['date_from'] = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
        } else {
            $search['date_from' ] = NV_CURRENTTIME;
        }
        $where .= ' AND order_time >= ' . $search['date_from'] . '';
    }

    if (! empty($search['date_to'])) {
        if (! empty($search['date_to']) and preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $search['date_to'], $m)) {
            $search['date_to'] = mktime(23, 59, 59, $m[2], $m[1], $m[3]);
        } else {
            $search['date_to' ] = NV_CURRENTTIME;
        }
        $where .= ' AND order_time <= ' . $search['date_to'] . '';
    }
    if ($search['status'] != '') {
        $status = explode('_', $search['status'] );
        $where .= ' AND status  = ' . intval( $status[1] ) . ' AND ordertype=' .intval( $status[0] );
    }

    // Fetch Limit
    $db->sqlreset()->select('COUNT(*)')
        ->from(NV_IS_LANG_TABLE_SM . '_orders')
        ->where( $where );

    $num_items = $db->query($db->sql())->fetchColumn();

    $db->select('*')->where($where)->order('order_id DESC')->limit($per_page)->offset(($page - 1) * $per_page);
    $query = $db->query($db->sql());
    $sum_price_out = $sum_price_in = 0;
    while ($row = $query->fetch()) {

        if (( $row['customer_id'] != $userid ) or ( $row['chossentype'] == 3 && $row['customer_id'] == $userid )) {
            if( $row['ordertype'] == 1 || $row['ordertype'] == 2 ){
                $sum_price_in = $sum_price_in  + $row['order_total'];
            }else{
                $sum_price_in = $sum_price_in - $row['order_total'];
            }
        }else{
            if( $row['ordertype'] == 1 ){
                $sum_price_out = $sum_price_out + $row['order_total'];
            }else{
                $sum_price_out = $sum_price_out - $row['order_total'];
            }
        }

        if( $row['ordertype'] == 2 ){
            $transaction_status[0] = $lang_module['status_ordertype_0'];
            $transaction_status[4] = $lang_module['status_ordertype_4'];
        }else{
            $transaction_status[0] = $lang_module['history_payment_no'];
            $transaction_status[4] = $lang_module['history_payment_yes'];
        }

        if ($row['status'] == 4) {
            $row['status_payment'] = $transaction_status[4];
        } elseif ($row['status'] == 1) {
            $row['status_payment'] = $transaction_status[1];
        } elseif ($row['status'] == 0) {
            $row['status_payment'] = $transaction_status[0];
        } else {
            $row['status_payment'] = "N/A";
        }

        $array_order[] = array(
            'ordercode' => $row['order_code'],
            'ordercode' => $row['order_code'],
            'orderdate' => date('d/m/Y H:i', $row['order_time']),
            'customer' => $row['order_name'],
            'amount' => number_format( $row['order_total'], 0, '.', ','),
            'status' => $row['status_payment'],
            'status_int' => $row['status']
        );
    }

    $sql = 'SELECT t1.userid, t1.code, t1.possitonid, t1.agencyid, t1.numsubcat, t1.subcatid, t1.datatext, t1.provinceid, t2.username, concat(t2.last_name, " ", t2.first_name) fullname, t2.email, t1.mobile 
    FROM ' . NV_IS_TABLE_AFFILIATE . '_users t1, ' . NV_USERS_GLOBALTABLE . ' t2 WHERE t1.userid = t2.userid AND t1.userid=' . $userid;
    $data_user = $db->query( $sql )->fetch();

    $array_reponsive = array(
        "userid" => $data_user['userid'],
        "usercode" => $data_user['code'],
        "username" => $data_user['fullname'],
        "mobile" => $data_user['mobile'],
        "region" => isset( $array_province[$data_user['provinceid']] )? $array_province[$data_user['provinceid']]['title'] : 'N/A',
        "totalorder" => $num_items,
        "totalsales" => number_format($sum_price_in, 0, '.', ','),
        "totalsales_out" => number_format($sum_price_out, 0, '.', ','),
        'orders' => $array_order
    );

    echo json_encode($array_reponsive);
}
else
{
    echo json_encode(array()); 
}
