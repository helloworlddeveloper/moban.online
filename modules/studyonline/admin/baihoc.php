<?php

/**
 * @Project NUKEVIET 3.1
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES.,JSC. All rights reserved
 * @Createdate 24-06-2011 10:35
 */

if( !defined( 'NV_IS_FILE_ADMIN' ) )
    die( 'Stop!!!' );

// Delete
if( $nv_Request->isset_request( 'delete', 'post' ) )
{
    $id = $nv_Request->get_int( 'id', 'post', 0 );
    if( empty( $id ) )
        die( "NO" );

    // Get base
    $sql = "SELECT numbuy FROM " . NV_PREFIXLANG . '_' . $module_data . "_baihoc WHERE id =" . $id;
    $result = $db->query( $sql );
    list( $numbuy ) = $result->fetch(3);
    if( $numbuy == 0 )
    {
        $sql = "DELETE FROM " . NV_PREFIXLANG . '_' . $module_data . "_baihoc WHERE id=" . $id;
        $db->query( $sql );
        $nv_Cache->delMod($module_name);
        die( "OK" );
    }
    else
    {
        die( $lang_module['error_delete_khoahoc'] );
    }
}
if( $nv_Request->isset_request( 'ajax_action', 'post' ) )
{
    $baihoc = $nv_Request->get_int( 'baihoc', 'post', 0 );
    $new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );
    $content = 'NO_' . $baihoc;
    if( $new_vid > 0 )
    {
        $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_baihoc SET weight=' . $new_vid . ' WHERE id=' . $baihoc;
        $db->query( $sql );
        $content = 'OK_' . $baihoc;
    }
    $nv_Cache->delMod($module_name);
    include NV_ROOTDIR . '/includes/header.php';
    echo $content;
    include NV_ROOTDIR . '/includes/footer.php';
    exit();
}


$page = $nv_Request->get_int( 'page', 'get', 1 );
$per_page = 30;
$array = array();
$khoahocid = $nv_Request->get_int( 'khoahocid', 'get', 0 );
if( $khoahocid == 0 ){
    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=khoahoc');
    die();
}else{
    
    $db->sqlreset()->select( '*' )->from( NV_PREFIXLANG . '_' . $module_data . '_khoahoc' )->where( 'id=' . $khoahocid );
    $data_khoahoc = $db->query( $db->sql() )->fetch();
    if( empty( $data_khoahoc )){
        Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=khoahoc');
        die();
    }
}
$page_title = $lang_module['qlbaihoc'] . ': ' . $data_khoahoc['title'];
// Base data
$where = 'khoahocid=' . $khoahocid;
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;

// Search data
$data_search = array(
    "q" => $nv_Request->get_title( 'q', 'get', '', 1, 100 ), //
    "timeend" => $nv_Request->get_title( 'timeend', 'get', '' ), //
    "timebegin" => $nv_Request->get_title( 'timebegin', 'get', '' ), //
    "disabled" => " disabled=\"disabled\"" //
        );

if(preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $data_search['timebegin'],$m))
{
	$timebegin = mktime(0,0,0,$m[2],$m[1],$m[3]);
}else{
    $timebegin = 0;
}

if(preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $data_search['timeend'],$m))
{
	$timeend = mktime(23,0,0,$m[2],$m[1],$m[3]);
}else{
    $timeend = 0;
}
    
// Enable cancel filter data
if(  !empty( $data_search['q'] ) or !empty( $data_search['timeend'] ) or !empty( $data_search['timebegin'] ) )
{
    $data_search['disabled'] = "";
}
$base_url .= "&amp;khoahocid=" . $khoahocid;

// Filter data
if( !empty( $data_search['q'] ) )
{
    $base_url .= "&amp;q=" . $data_search['q'];
    $where .= " AND title LIKE '%" . $data_search['q'] . "%'";
}
if( $timebegin > 0 && $timeend > 0 )
{
    $base_url .= "&amp;timebegin=" . $timebegin . '&amp;timeend=' . $timeend;
    $where .= " AND timephathanh>=" . $timebegin . ' AND timephathanh<=' . $timeend;
}elseif( $timebegin > 0 && $timeend == 0 )
{
    $base_url .= "&amp;timebegin=" . $timebegin;
    $where .= " AND timephathanh>=" . $timebegin;
}elseif( $timebegin == 0 && $timeend > 0 )
{
    $base_url .= '&amp;timeend=' . $timeend;
    $where .= ' AND timephathanh<=' . $timeend;
}

$xtpl = new XTemplate( $op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'module_file', $module_file );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'CHECKSESS', md5( $module_data ."_" . $admin_info['userid'] . "_" . session_id() ) );
$xtpl->assign( 'DATA_SEARCH', $data_search );
$xtpl->assign( 'KHOAHOC', $data_khoahoc );
$xtpl->assign( 'URL_CANCEL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op );
$xtpl->assign( 'URL_ADD', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=thembaihoc&khoahocid=" . $khoahocid );

$db->sqlreset()->select( 'COUNT(*)' )->from( NV_PREFIXLANG . '_' . $module_data . '_baihoc' )->where( $where );

$num_items = $db->query( $db->sql() )->fetchColumn();
$db->select( '*' )->limit( $per_page )->offset( ( $page - 1 ) * $per_page )->order('weight ASC' );
$result = $db->query( $db->sql() );
while( $row = $result->fetch() )
{    
    $row['timephathanh'] = nv_date( "d/m/Y", $row['timephathanh'] );
    $row['addtime'] = nv_date( "d/m/Y", $row['addtime'] );
    $row['price'] = number_format( $row['price'], 0, ',', '.' );
    $row['numview'] = number_format( $row['numview'], 0, ',', '.' );
    $row['numlike'] = number_format( $row['numlike'], 0, ',', '.' );
    $row['numbuy'] = number_format( $row['numbuy'], 0, ',', '.' );
    $row['url_edit'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=thembaihoc&amp;khoahocid=" . $row['khoahocid'] . "&amp;id=" . $row['id'];
    $xtpl->assign( 'ROW', $row );
    $xtpl->parse( 'main.row' );
    
}

$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page );
if( !empty( $generate_page ) )
{
    $xtpl->assign( 'GENERATE_PAGE', $generate_page );
    $xtpl->parse( 'main.generate_page' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>