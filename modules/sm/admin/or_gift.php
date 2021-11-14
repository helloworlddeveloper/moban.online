<?php
/**

 * @Project NUKEVIET 4.x

 * @Author VINADES.,JSC (contact@vinades.vn)

 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved

 * @License GNU/GPL version 2 or any later version

 * @Createdate Tue, 18 Nov 2014 10:21:15 GMT

 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) )
    die( 'Stop!!!' );

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
$per_page = 20;
$page = $nv_Request->get_int( 'page', 'post,get', 1 );
$q = $nv_Request->get_title( 'q', 'post,get', '' );
$sstatus = $nv_Request->get_int( 'sstatus', 'post,get', -1 );
$product = $nv_Request->get_title('product', 'post,get', '');
$where1 = '';
$where = '';

if( $sstatus != -1 ) {
    $where .= ' and t1.status = ' . $sstatus;
    $where1 .= ' and t1.status = ' . $sstatus;
    $base_url .= '&amp;sstatus=' . $sstatus;
}
if( !empty( $q ) ) {
    $where .= " and (t1.barcode like '%" . $q  . "%' or t1.gift_desc like '%" . $q  . "%' or t1.gift = '" . $q . "' or t2.first_name like '%" . $q . "%' or t2.last_name like '%" . $q . "%')";
    $where1 .= " and (t1.barcode like '%" . $q  . "%' or t1.gift_desc like '%" . $q  . "%' or t1.gift = '" . $q . "')";
    $base_url .= '&amp;q=' . $q;
}
if( !empty( $product ) ) {
    $where1 .= " and t1.gift = '" . $product . "'";
    $where .= " and t1.gift = '" . $product . "'";
    $base_url .= '&amp;product=' . $product;
}

$sql = "SELECT count(*) FROM nv4_vi_sm_customer_gifts t1";
$sql .= " WHERE (t1.agencyid not in (-1,-2) or t1.agencyid is null) ". $where1;
//echo $sql; die();
$num_items = $db->query($sql)->fetchColumn();

$sql = "SELECT t1.*, t2.username, concat(t2.first_name, ' ', t2.last_name) as customer, t2.phone as customer_phone, t2.address as customer_address, 
            t4.customer_id, t4.fullname, t4.address, t4.phone, t4.email,
        concat(t3.first_name, ' ', t3.last_name) as agency, t3.phone as agency_phone 
    FROM nv4_vi_sm_customer_gifts t1
    LEFT OUTER JOIN nv4_users t2 ON t1.userid = t2.userid
    LEFT OUTER JOIN nv4_users t3 ON t1.agencyid = t3.userid
    LEFT OUTER JOIN nv4_vi_sm_customer t4 ON t4.refer_userid = t1.userid";
$sql .= " WHERE (t1.agencyid not in (-1,-2) or t1.agencyid is null) ". $where . " ORDER BY t1.userid LIMIT " . (($page - 1) * $per_page) . ", " . $per_page;
//echo $sql; die();
//echo $db->sql();die();
$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'UPLOAD_CURRENT', NV_UPLOADS_DIR . '/shared' );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'ROW', $row );
$xtpl->assign( 'Q', $q );
$xtpl->assign( 'TOTAL_GIFT', $num_items );

$xtpl->assign( 'NV_GENERATE_PAGE', nv_generate_page( $base_url, $num_items, $per_page, $page ) );

$result = $db->query($sql);
$number = 0;
while( $view = $result->fetch() )
{
    $view['number'] = ++$number;

    if (empty($view['agencyid']) || $view['agencyid'] == 0) {
        $view['gift_status'] = 'Chưa nhận quà';
    } elseif ($view['agencyid'] != -1 && $view['agencyid'] != -2) {
        $view['gift_status'] = "Đã nhận quà ngày " . date( 'd/m/Y H:i', $view['updated_date'] ) . " bởi ĐL: " . $view['agency'] . ' [' . $view['agency_phone'] . ']';
    } else {
        $view['gift_status'] = "Công ty đã trả quà ngày " . date( 'd/m/Y H:i', $view['updated_date'] );
    }
    $view['customer_type'] = empty($view['customer_id']) ? 'NPP/ĐL Minh Khang' : 'Khách lẻ';
    $sta = empty($view['customer_id']) ? -2 : -1;
    $view['link_gift'] = empty($view['customer_id']) ? '#' : NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=customer_gifts&amp;id=' . $view['customer_id'];
    $view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=exchange_gifts&amp;id=' . $view['id'] . '&amp;sta=' . $sta;
    //$view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_customer_id=' . $view['customer_id'] . '&amp;delete_checkss=' . md5( $view['customer_id'] . NV_CACHE_PREFIX . $client_info['session_id'] );
    $view['edit_time'] = date( 'd/m/Y H:i', $view['edit_time'] );
    $view['created_date'] = date( 'd/m/Y H:i', $view['created_date'] );
    $view['status'] = $lang_module['active_' . $view['status']];
    $view['return_gifts'] = ($view['agencyid'] != -1 && $view['agencyid'] != -2) ? "Trả quà" : "";

    if (empty($view['agencyid']) || $view['agencyid'] == 0 || $view['agencyid'] > 0) {
        $xtpl->assign( 'return_gifts', $view['return_gifts'] );
        $xtpl->assign( 'link_gift', $view['link_edit'] );
        $xtpl->parse( 'main.loop.return_gift' );
    }

    $xtpl->assign( 'VIEW', $view );
    $xtpl->parse( 'main.loop' );
}

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_giftcode';
$array_gift = $db->query( $sql );
while ($product = $array_gift->fetch()) {
    $product['sl'] = ( $array_search['product'] == $product['code'])? ' selected=selected' : '';
    $xtpl->assign('PRODUCT', $product);
    $xtpl->parse('main.product');
}

$array_status = array( '0' => "Chưa nhận quà", '1' => "Đã nhận quà" );
foreach( $array_status as $key => $_status ) {
    $sl = ( $key == $sstatus ) ? ' selected="selected"' : '';
    $xtpl->assign( 'SEARCH_STATUS', array(
        'selected' => $sl,
        'key' => $key,
        'value' => $_status ) );
    $xtpl->parse( 'main.search_status' );
}
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );
$page_title = "Quản lý quà tặng thẻ cào";

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';