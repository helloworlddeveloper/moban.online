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
    $sql = "DELETE FROM " . NV_PREFIXLANG . '_' . $module_data . "_review WHERE id=" . $id;
    $db->query( $sql );

    $nv_Cache->delMod($module_name);
    die( "OK" );

}
if($nv_Request->isset_request('changeblock','post'))
{
    $id = $nv_Request->get_int( 'id', 'post', 0 );
    $new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );
    $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_review SET status=' . $new_vid . ' WHERE id=' . $id;
    $db->query($sql);
    die( 'OK_' . $id);
}

// Page title collum
$page_title = $lang_module['review'];
$page = $nv_Request->get_int( 'page', 'get', 1 );
$per_page = 30;
$array = array();

// Base data
$where = "id!=0";
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;


// Search data
$data_search = array(
    "q" => $nv_Request->get_title( 'q', 'get', '', 1, 100 ), //
    "khoahocid" => $nv_Request->get_int( 'khoahocid', 'get', 0 ), //
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

// Filter data
if( !empty( $data_search['q'] ) )
{
    $base_url .= "&amp;q=" . $data_search['q'];
    $where .= " AND content LIKE '%" . $data_search['q'] . "%'";
}
if( !empty( $data_search['khoahocid'] ) )
{
    $base_url .= "&amp;khoahocid=" . $data_search['khoahocid'];
    $where .= " AND khoahocid=" . $data_search['khoahocid'];
}

if( $timebegin > 0 && $timeend > 0 )
{
    $base_url .= "&amp;timebegin=" . $timebegin . '&amp;timeend=' . $timeend;
    $where .= " AND addtime>=" . $timebegin . ' AND addtime<=' . $timeend;
}elseif( $timebegin > 0 && $timeend == 0 )
{
    $base_url .= "&amp;timebegin=" . $timebegin;
    $where .= " AND addtime>=" . $timebegin;
}elseif( $timebegin == 0 && $timeend > 0 )
{
    $base_url .= '&amp;timeend=' . $timeend;
    $where .= ' AND addtime<=' . $timeend;
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
$xtpl->assign( 'URL_CANCEL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op );
$xtpl->assign( 'URL_ADD', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=themkhoahoc" );
$xtpl->assign( 'emailmarketing_link', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=emailmarketing" );

$db->sqlreset()->select( 'COUNT(*)' )->from( NV_PREFIXLANG . '_' . $module_data . '_review' )->where( $where );
$num_items = $db->query( $db->sql() )->fetchColumn();

$db->select( '*' )->limit( $per_page )->offset( ( $page - 1 ) * $per_page )->order('addtime DESC' );
$result = $db->query( $db->sql() );

$array_review_option = array($lang_module['review_0'], $lang_module['review_1']);
while( $row = $result->fetch() )
{
    foreach ($array_review_option as $keyreview => $titlereview ){
        $sl = ( $keyreview == $row['status'] )? ' selected=selected' : '';
        $xtpl->assign( 'STATUS', array('key' => $keyreview, 'title' => $titlereview, 'sl' => $sl) );
        $xtpl->parse( 'main.row.status' );
    }
    $row['addtime'] = nv_date( "H:i d/m", $row['addtime'] );
    $xtpl->assign( 'ROW', $row );
    $xtpl->parse( 'main.row' );
}

$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page );

$array_khoahoc = $nv_Cache->db('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_khoahoc', 'id', $module_name);
foreach( $array_khoahoc as $khoahoc )
{
    $khoahoc['selected'] = ( $data_search['khoahocid'] == $khoahoc['id'] ) ? " selected=\"selected\"" : "";
    $xtpl->assign( 'KHOAHOC', $khoahoc );
    $xtpl->parse( 'main.khoahoc' );
}

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