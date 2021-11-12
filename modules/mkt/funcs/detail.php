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

if( isset( $array_op[1] ) )
{
	$tab = NV_PREFIXLANG . '_' . $module_data . '_room';
	$stmt = $db->prepare( 'SELECT * FROM ' . $tab . ' WHERE status=1 AND alias= :alias' );
	$stmt->bindParam( ':alias', $array_op[1], PDO::PARAM_STR );
	$stmt->execute();
	$array_data = $stmt->fetch();
	if( empty( $array_data ) )
	{
		$redirect = '<meta http-equiv="Refresh" content="3;URL=' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true ) . '" />';
		nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] . $redirect );
	}
	$description = $array_data['hometext'];
	$page_title = $array_data['title'];
    
    $base_url_rewrite = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$catid]['alias'] . '/' . $array_data['alias'] . $global_config['rewrite_exturl'], true );
	if( $_SERVER['REQUEST_URI'] != $base_url_rewrite )
	{
		Header( 'Location: ' . $base_url_rewrite );
		die();
	}
    $canonicalUrl = NV_MAIN_DOMAIN . $base_url_rewrite;
    
    $array_data['link_order'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp' . NV_OP_VARIABLE . '=order/' . $array_data['alias'];
    
	$maps_config = array();
	if( ! empty( $array_data['maps'] ) )
	{
		$maps_config['gmap_z'] = 14;
		$maps_config['Google_Maps_API_Key'] = $module_config[$module_name]['Google_Maps_API_Key'];
		$gmap_lat = $module_config[$module_name]['gmap_lat'];
		$gmap_lng = $module_config[$module_name]['gmap_lng'];
		$gmap_z = 14;

		$map = $array_data['maps'];
		$smap = nv_unhtmlspecialchars( $map );
		$pos = strpos( $smap, "&z=" );
		if( $pos > 0 )
		{
			$gmap_z = substr( $smap, $pos + 3 );

			$pos = strpos( $gmap_z, "&" );
			if( $pos > 0 )
			{
				$gmap_z = substr( $gmap_z, 0, $pos );
			}
			$gmap_z = ( intval( $gmap_z ) > 0 ) ? intval( $gmap_z ) : 14;
		}
        $maps_config['gmap_z'] = $gmap_z;
		$pos1 = strpos( $smap, "?ll=" );
		$pos2 = strpos( $smap, "&ll=" );
		$pos3 = strpos( $smap, "?sll=" );
		$pos4 = strpos( $smap, "&sll=" );
		$pos = ( $pos1 > 0 ) ? $pos1 : ( ( $pos2 > 0 ) ? $pos2 : ( ( $pos3 > 0 ) ? $pos3 : ( $pos4 ) ) );
		if( $pos )
		{
			$gmap_lng_lat = substr( $smap, $pos );
			$pos1 = strpos( $gmap_lng_lat, "=" ) + 1;
			$gmap_lng_lat = substr( $gmap_lng_lat, $pos1 );
			$pos2 = strpos( $gmap_lng_lat, "&" );
			$gmap_lng_lat = substr( $gmap_lng_lat, 0, $pos2 );
			if( strpos( $gmap_lng_lat, "," ) )
			{
				list( $gmap_lat, $gmap_lng ) = explode( ",", $gmap_lng_lat );
			}
		}
		$maps_config['gmap_lat'] = $gmap_lat;
		$maps_config['gmap_lng'] = $gmap_lng;
	}
}

$contents = nv_theme_rm_detail( $array_data, $maps_config );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
