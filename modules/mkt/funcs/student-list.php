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

$db->sqlreset()->select( '*, SQRT(POWER(( gmap_lat - ' . $map_lat . ' ),2) + POWER(( gmap_lng - ' . $map_lon . ' ),2)) *  PI() * 6457444.65 / 180 AS khoangcach, ' . $year_current . ' - FROM_UNIXTIME(birthday,"%Y") AS yearold' )->from( NV_PREFIXLANG . '_' . $module_data . '_student' )->where( 'status= 1 AND POWER((' . $khoangcach . ' * 180 )/ (6457444.65 * PI()),2)  > (POWER(( gmap_lat - ' . $map_lat . ' ),2) + POWER(( gmap_lng - ' . $map_lon . ' ),2))' . $sql_where )->order( 'khoangcach ASC' );

$result = $db->query( $db->sql() );
$listDistrict = nv_District();
$list_school = nv_School();
$array_total_sum = array( 'total_student' => 0, 'khoangcach' => 0 );

while( $item = $result->fetch() )
{
	$item['class'] = $item['yearold'] - 6 - $item['shool_year'];
	if( $item['class'] < 0 )
	{
		$item['class'] = 0;
	}
	elseif( $item['class'] > 12 )
	{
		$item['class'] = $lang_module['hetcap'];
	}

	$array_total_sum['khoangcach'] += $item['khoangcach'];
	$item['khoangcach'] = number_format( $item['khoangcach'], 0, ',', '.' ) . ' m';
	$item['school_name'] = isset( $list_school[$item['school_id']] ) ? $list_school[$item['school_id']]['title'] : 'N/A';
	$item['district'] = $listDistrict[$item['districtid']]['title'];
	$item['birthday'] = date( 'd/m/Y', $item['birthday'] );
	$array_total_sum['total_student']++;
	$array_data[] = $item;
}

$array_total_sum['khoangcach'] = number_format( $array_total_sum['khoangcach'] / $array_total_sum['total_student'], 0, ',', '.' ) . ' m';
;

$url_content = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $map_lat . ',' . $map_lon;
$json = @file_get_contents( $url_content );
$data = json_decode( $json );
$center_address = $data->results[0]->formatted_address;

$contents = nv_theme_mkt_student_list( $array_data, $khoangcach, $center_address, $array_total_sum, $maps_config );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
