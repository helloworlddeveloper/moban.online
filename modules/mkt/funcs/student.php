<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 18 Nov 2014 01:50:26 GMT
 */

if( ! defined( 'NV_IS_MOD_RM' ) ) die( 'Stop!!!' );

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

$array_data = array();

$contents = '';
$cache_file = '';

$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
$base_url_rewrite = nv_url_rewrite( $base_url, true );
$request_uri = $_SERVER['REQUEST_URI'];

$khoangcach = $nv_Request->get_int( 'khoangcach', 'get', 3000 );
$schooltype = $nv_Request->get_array( 'schooltype', 'get', array() );
$map_lat = $nv_Request->get_float( 'map_lat', 'get', $module_config[$module_name]['gmap_lat'] );
$map_lon = $nv_Request->get_float( 'map_lon', 'get', $module_config[$module_name]['gmap_lng'] );

$maps_config['gmap_z'] = 14;
$maps_config['gmap_lng'] = $map_lon;
$maps_config['gmap_lat'] = $map_lat;

$maps_config['Google_Maps_API_Key'] = $module_config[$module_name]['Google_Maps_API_Key'];

$sql_where = '';
$sql_where_shool_type = array();
$year_current = date( 'Y', NV_CURRENTTIME );
if( ! empty( $schooltype ) )
{
	foreach( $schooltype as $schooltype_i )
	{
		$year_old_to = $year_from = 0;
		if( $schooltype_i == 1 )
		{
			$year_from = 6;
			$year_old_to = 11;
			$sql_where_shool_type[] = '(' . '' . $year_current . ' - FROM_UNIXTIME(birthday,"%Y") - shool_year >=' . $year_from . ' AND ' . $year_current . ' - FROM_UNIXTIME(birthday,"%Y") - shool_year <=' . $year_old_to . ')';
		}
		if( $schooltype_i == 2 )
		{
			$year_from = 12;
			$year_old_to = 15;
			$sql_where_shool_type[] = '(' . '' . $year_current . ' - FROM_UNIXTIME(birthday,"%Y") - shool_year >=' . $year_from . ' AND ' . $year_current . ' - FROM_UNIXTIME(birthday,"%Y") - shool_year <=' . $year_old_to . ')';
		}
		if( $schooltype_i == 3 )
		{
			$year_from = 16;
			$year_old_to = 18;
			$sql_where_shool_type[] = '(' . '' . $year_current . ' - FROM_UNIXTIME(birthday,"%Y") - shool_year >=' . $year_from . ' AND ' . $year_current . ' - FROM_UNIXTIME(birthday,"%Y") - shool_year <=' . $year_old_to . ')';
		}
	}
    if( !empty( $sql_where_shool_type ))
    $sql_where = ' AND(' . implode( ' OR ', $sql_where_shool_type ) . ')';
}
$db->sqlreset()->select( '*, SQRT(POWER(( gmap_lat - ' . $map_lat . ' ),2) + POWER(( gmap_lng - ' . $map_lon . ' ),2)) *  PI() * 6457444.65 / 180 AS khoangcach' )->from( NV_PREFIXLANG . '_' . $module_data . '_student' )->where( 'status= 1 AND POWER((' . $khoangcach . ' * 180 )/ (6457444.65 * PI()),2)  > (POWER(( gmap_lat - ' . $map_lat . ' ),2) + POWER(( gmap_lng - ' . $map_lon . ' ),2))' . $sql_where )->order( 'khoangcach ASC' );

$result = $db->query( $db->sql() );
while( $item = $result->fetch() )
{
	$item['birthday'] = date( 'd/m/Y', $item['birthday'] );
	$array_data[] = $item;
}

$contents = nv_theme_mkt_student( $array_data, $khoangcach, $schooltype, $maps_config );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
