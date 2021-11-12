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

$wid = $nv_Request->get_int('wid', 'get', 0);
$array_search = array();
$array_warehouse = array();

$page_title = $lang_module['importplan'];

$array_search = array();
$where = '';
$array_search['date_from'] = $nv_Request->get_title('from', 'get', '');
$array_search['date_to'] = $nv_Request->get_title('to', 'get', '');
$array_search['product'] = $nv_Request->get_int('product', 'get', 0);

$date_to = $date_from = 0;
if ($array_search['product'] > 0 ) {
    $where .= ' AND t1.productid = ' . $array_search['product'];
}
if (! empty($array_search['date_from'])) {
    if (! empty($array_search['date_from']) and preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $array_search['date_from'], $m)) {
        $date_from = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
    }
}

if (! empty($array_search['date_to'])) {
    if (! empty($array_search['date_to']) and preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $array_search['date_to'], $m)) {
        $date_to = mktime(23, 59, 59, $m[2], $m[1], $m[3]);
    }
}
$customer_id = 0;
$array_data_num_product = get_order_ordertype2( $customer_id, $date_from, $date_to );

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

foreach ($array_product as $product ){
    $product['sl'] = ( $array_search['product'] == $product['id'])? ' selected=selected' : '';
    $xtpl->assign('PRODUCT', $product);
    $xtpl->parse('main.list.product');
}
$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
if( !empty( $array_data_num_product )){
    foreach ( $array_data_num_product as $producid => $product_info ) {

        $product_info['product_title'] = $array_product[$producid]['title'];
        $xtpl->assign('VIEW', $product_info );
        $xtpl->parse('main.list.loop');
    }
    $xtpl->parse('main.list');
}else{
    $xtpl->parse('main.nolist');
}
$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
