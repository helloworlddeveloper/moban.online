<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 18 Nov 2014 04:27:19 GMT
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$error = array();
if( $nv_Request->isset_request( 'save_price', 'post' ) )
{
    $price_agency = $nv_Request->get_array( 'price_agency', 'post', array() );
    $aid = $nv_Request->get_int( 'aid', 'post', 0 );
    foreach( $price_agency as $productid => $price )
    {
        $price = str_replace(',', '', $price );
        $price = str_replace('.', '', $price );
        $inserted = $db->query( "SELECT COUNT(*) FROM " . NV_PREFIXLANG . '_' . $module_data . "_agency_detail WHERE productid=" . intval( $productid ) . ' AND aid=' . intval( $aid ) )->fetchColumn();
        try
        {
            if( $inserted == 0 )
            {
                $db->query( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_agency_detail (aid, productid, price, add_time, edit_time, status) VALUES (' . intval( $aid ) . ', ' . intval( $productid ) . ', ' . floatval( $price ) . ', ' . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ', 1)' );
            }
            else
            {
                $db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_agency_detail SET price=' . floatval( $price ) . ' WHERE productid=' . intval( $productid ) . ' AND aid=' . intval( $aid ) );
            }
        }
        catch ( PDOException $e )
        {
            die( $e->getMessage() );
        }
    }

    $nv_Cache->delMod($module_name);
    Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&id=' . $aid );
    die();
}

$_agency = array();
$aid = $nv_Request->get_int('id', 'get', 0);
if( $aid > 0 ){
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_agency WHERE id= ' . $aid;
    $_agency = $db->query($sql)->fetch();
}
if( empty( $_agency )){
    Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=agency' );
    die();
}
$lang_module['price_agency'] = sprintf( $lang_module['price_agency'], $_agency['title']);
$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'MODULE_FILE', $module_file );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'aid', $aid );

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

$db->sqlreset()->select( 'id, listcatid, ' . NV_LANG_DATA . '_title AS title, ' . NV_LANG_DATA . '_alias AS alias, product_number, product_price, money_unit, product_unit' )->from( $db_config['prefix'] . '_' . $module_data . '_rows' );
$sql_where = 'status=1';

$db->where( $sql_where );
$sth = $db->prepare( $db->sql() );
$sth->execute();

$page_title = sprintf( $lang_module['agencyprice'], $_agency['title']);;
$array_data = $array_data_price_agency  = array();

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_agency_detail WHERE aid=' . $aid;
$array_data_price_agency = $nv_Cache->db($sql, 'productid', $module_name);


while( $view = $sth->fetch() )
{
    $view['product_price'] = nv_number_format($view['product_price'], nv_get_decimals($view['money_unit']));
    $view['price_agency'] = isset( $array_data_price_agency[$view['id']] )? nv_number_format( $array_data_price_agency[$view['id']]['price'], nv_get_decimals($view['money_unit'])) : '';
    $view['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_shops_cat[$view['listcatid']]['alias'] . '/' . $view['alias'] . $global_config['rewrite_exturl'];
    //$array_data[$view['id']] = $view;
    $xtpl->assign( 'VIEW', $view );
    $xtpl->parse( 'main.loop' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
