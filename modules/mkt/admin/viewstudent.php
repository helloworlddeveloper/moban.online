<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 18 Nov 2014 04:27:19 GMT
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) )
    die( 'Stop!!!' );

$to = $nv_Request->get_int( 'to', 'get', 3000 );
$from = $nv_Request->get_int( 'from', 'get', 0 );
$array_status = $nv_Request->get_array( 'status', 'get', array() );
$schooltype = $nv_Request->get_array( 'schooltype', 'get', array( 1 ) );
$map_lat = $nv_Request->get_float( 'map_lat', 'get', $module_config[$module_name]['gmap_lat'] );
$map_lon = $nv_Request->get_float( 'map_lon', 'get', $module_config[$module_name]['gmap_lng'] );

$date_from = $nv_Request->get_title( 'date_from', 'get', '' );
$date_to = $nv_Request->get_title( 'date_to', 'get', '' );

$maps_config['gmap_z'] = 14;
$maps_config['gmap_lng'] = $map_lon;
$maps_config['gmap_lat'] = $map_lat;

$maps_config['Google_Maps_API_Key'] = $module_config[$module_name]['Google_Maps_API_Key'];

$sql_where = '';
$list_year_old = array();
$year_current = date( 'Y', NV_CURRENTTIME );
$month_current = date( 'm', NV_CURRENTTIME );
if( $month_current > 5 && $month_current < 12 )
{
    $year_current = $year_current + 1;
}
if( ! empty( $schooltype ) )
{
    foreach( $schooltype as $schooltype_i )
    {
        $list_year_old[] = $schooltype_i + 6;
    }
    $list_year_old = implode( ',', $list_year_old );
    $sql_where = ' AND ' . $year_current . ' - FROM_UNIXTIME(birthday,"%Y") - shool_year IN(' . $list_year_old . ')';
}

if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $date_from, $m ) )
{
    $date_from = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
}
else
{
    $date_from = 0;
}

if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $date_to, $m ) )
{
    $date_to = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
}
else
{
    $date_to = 0;
}

if( $date_from > 0 && $date_to > 0 )
{
    $sql_where .= ' AND (remkt_time>=' . $date_from . ' AND remkt_time<=' . $date_to . ')';
}
elseif( $date_from > 0 )
{
    $sql_where .= ' AND remkt_time>=' . $date_from;
}
elseif( $date_to > 0 )
{
    $sql_where .= ' AND remkt_time<=' . $date_to;
}

if( ! empty( $array_status ) )
{
    $sql_where .= ' AND status IN(' . implode( ',', $array_status ) . ')';
}
$db->sqlreset()->select( '*, SQRT(POWER(( gmap_lat - ' . $map_lat . ' ),2) + POWER(( gmap_lng - ' . $map_lon . ' ),2)) *  PI() * 6457444.65 / 180 AS khoangcach' )->from( NV_PREFIXLANG . '_' . $module_data )->where( 'POWER((' . $to . ' * 180 )/ (6457444.65 * PI()),2)  >= (POWER(( gmap_lat - ' . $map_lat . ' ),2) + POWER(( gmap_lng - ' . $map_lon . ' ),2)) AND POWER((' . $from . ' * 180 )/ (6457444.65 * PI()),2)  <= (POWER(( gmap_lat - ' . $map_lat . ' ),2) + POWER(( gmap_lng - ' . $map_lon . ' ),2))' . $sql_where )->order( 'khoangcach ASC' );

$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'MODULE_FILE', $module_file );
$xtpl->assign( 'TEMPLATE', $global_config['module_theme'] );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'to', $to );
$xtpl->assign( 'from', $from );
$xtpl->assign( 'khoangcach', $to + $from );
$xtpl->assign( 'MAPS_CONFIG', $maps_config );
$xtpl->assign( 'OP', $op );
if( defined( 'NV_IS_SPADMIN' ) )
{
    $xtpl->parse( 'main.export_data' );
}
for( $i = 12; $i >= 1; $i-- )
{
    $ck = in_array( $i, $schooltype ) ? ' checked=checked' : '';
    $xtpl->assign( 'SCHOOLTYPE', array(
        'ck' => $ck,
        'key' => $i,
        'title' => $lang_module['class'] . ' ' . $i ) );
    $xtpl->parse( 'main.schooltype' );
}
foreach( $array_student_status as $key => $status )
{
    $ck = in_array( $key, $array_status ) ? ' checked=checked' : '';
    $xtpl->assign( 'STATUS', array(
        'ck' => $ck,
        'key' => $key,
        'title' => $status ) );
    $xtpl->parse( 'main.status' );
}

$result = $db->query( $db->sql() );
while( $item = $result->fetch() )
{
    $item['birthday'] = date( 'd/m/Y', $item['birthday'] );
    $item['address'] = nv_nl2br( $item['address'] );
    $xtpl->assign( 'VIEW', $item );
    $xtpl->parse( 'main.map_point' );
    $xtpl->parse( 'main.map_info' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );
$page_title = $lang_module['viewstudentonmap'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
