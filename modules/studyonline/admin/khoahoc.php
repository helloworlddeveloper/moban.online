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
    $sql = "SELECT numbuy FROM " . NV_PREFIXLANG . '_' . $module_data . "_khoahoc WHERE id =" . $id;
    $result = $db->query( $sql );
    list( $numbuy ) = $result->fetch(3);
    if( $numbuy == 0 )
    {
        $sql = "DELETE FROM " . NV_PREFIXLANG . '_' . $module_data . "_khoahoc WHERE id=" . $id;
        $db->query( $sql );

        $nv_Cache->delMod($module_name);
        die( "OK" );
    }
    else
    {
        die( $lang_module['error_delete_khoahoc'] );
    }
}

// Page title collum
$page_title = $lang_module['khoahoc_manage'];
$page = $nv_Request->get_int( 'page', 'get', 1 );
$per_page = 30;
$array = array();

// Base data
$where = "id!=0";
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;

// Search data
$data_search = array(
    "q" => $nv_Request->get_title( 'q', 'get', '', 1, 100 ), //
    "classid" => $nv_Request->get_int( 'classid', 'get', 0 ), //
    "subjectid" => $nv_Request->get_int( 'subjectid', 'get', 0 ), //
    "disabled" => " disabled=\"disabled\"" //
        );

// Enable cancel filter data
if(  !empty( $data_search['q'] ) or !empty( $data_search['classid'] ) or !empty( $data_search['subjectid'] ) )
{
    $data_search['disabled'] = "";
}

// Filter data
if( !empty( $data_search['q'] ) )
{
    $base_url .= "&amp;q=" . $data_search['q'];
    $where .= " AND title LIKE '%" . $data_search['q'] . "%'";
}
if( !empty( $data_search['subjectid'] ) )
{
    $base_url .= "&amp;subjectid=" . $data_search['subjectid'];
    $where .= " AND subjectid=" . $data_search['subjectid'];
}
if( !empty( $data_search['classid'] ) )
{
    $base_url .= "&amp;classid=" . $data_search['classid'];
    $where .= " AND classid=" . $data_search['classid'];
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

$db->sqlreset()->select( 'COUNT(*)' )->from( NV_PREFIXLANG . '_' . $module_data . '_khoahoc' )->where( $where );

$num_items = $db->query( $db->sql() )->fetchColumn();

$db->select( '*' )->limit( $per_page )->offset( ( $page - 1 ) * $per_page )->order('addtime DESC' );

$result = $db->query( $db->sql() );
while( $row = $result->fetch() )
{
    if( !empty( $row['listtag'] ) )
    {
        $row['title'] .= '<i style="color:#f00">';
        $listtag = explode( ',', $row['listtag'] );
        foreach( $listtag as $tagid )
        {
            if( isset( $array_tag[$tagid] ) )
            {
                if( $array_tag[$tagid]['tag_icon'] != '' && is_file( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $array_tag[$tagid]['tag_icon'] ) )
                {
                    $row['title'] .= '<img src="' . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $array_tag[$tagid]['tag_icon'] . '" width="25px" />&nbsp;';
                }
                else
                {
                    $row['title'] .= $array_tag[$tagid]['tag_name'] . '&nbsp;';
                }
            }
        }
        $row['title'] .= '</i>';
    }
    
    $row['class_name'] = $array_class[$row['classid']]['title'];
    $row['subject_name'] = $array_subject[$row['subjectid']]['title'];
    $row['teacherid'] = explode(',', $row['teacherid'] );
    $row['teacher'] = array();
    foreach( $row['teacherid'] as $teacherid ){
        $row['teacher'][] = $array_teacher[$teacherid]['title'];
    }
    $row['teacher'] = implode(', ', $row['teacher'] );
    $row['addtime'] = nv_date( "d/m/Y", $row['addtime'] );
    $row['price'] = number_format( $row['price'], 0, ',', '.' );
    $row['numview'] = number_format( $row['numview'], 0, ',', '.' );
    $row['numlike'] = number_format( $row['numlike'], 0, ',', '.' );
    $row['numbuy'] = number_format( $row['numbuy'], 0, ',', '.' );
    $row['url_edit'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=themkhoahoc&amp;id=" . $row['id'];
    $row['qlbaihoc'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=baihoc&amp;khoahocid=" . $row['id'];
    $xtpl->assign( 'ROW', $row );
    if( isset( $site_mods['emailmarketing'])){
        $xtpl->parse( 'main.row.emailmarketing' );    
    }
    $xtpl->parse( 'main.row' );
    
}

$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page );


foreach( $array_class as $class )
{
    $class['selected'] = ( $data_search['classid'] == $class['id'] ) ? " selected=\"selected\"" : "";
    $xtpl->assign( 'CLASS', $class );
    $xtpl->parse( 'main.class' );
}

foreach( $array_subject as $subject )
{
    $subject['selected'] = ( $data_search['subjectid'] == $subject['id'] ) ? " selected=\"selected\"" : "";
    $xtpl->assign( 'SUBJECT', $subject );
    $xtpl->parse( 'main.subject' );
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