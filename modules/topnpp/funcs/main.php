<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if (!defined('NV_IS_MOD_SM')) {
    die('Stop!!!');
}
$month_start = strtotime('first day of this month', time());

$sql = "SELECT * FROM " . $db_config['prefix']  . "_affiliate_agency WHERE status=1 ORDER BY weight";
$array_agency = $nv_Cache->db($sql, 'id', 'affiliate');

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_affiliate_province WHERE status=1 ORDER BY weight";
$array_province = $nv_Cache->db($sql, 'id', 'affiliate');

$month_start = mktime(0,0,0, 12, 01, 2018);
$month_end = mktime(23,59,0, 12, 31, 2018);

$db->sqlreset()
    ->select('t1.customer_id, t2.title as fullname, t2.email, t2.image_site, t2.domain, t3.agencyid, t3.provinceid, t3.code, t3.mobile, SUM(t1.order_total) AS total_price')
    ->from(NV_PREFIXLANG . '_sm_orders t1, ' . $db_config['prefix'] . '_regsite t2, ' . $db_config['prefix'] . '_affiliate_users t3')
    ->where('t1.customer_id=t2.userid AND t2.userid=t3.userid AND t1.ordertype=1 AND t3.lev>1 AND t1.status= 4 AND t1.order_time>=' . $month_start . ' AND t1.order_time<=' . $month_end )
    ->order('total_price DESC')
    ->group('t1.customer_id')
    ->limit( 5 );

$result = $db->query( $db->sql() );
$array_statistic = array();
while ($row = $result ->fetch()){
    $array_statistic[] = $row;
}

$page_title = 'VINH DANH DOANH SỐ THÁNG 12/2018 CÁC NPP';

$contents = nv_theme_topnpp_main( $array_statistic, $array_agency, $array_province, $page_title );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';