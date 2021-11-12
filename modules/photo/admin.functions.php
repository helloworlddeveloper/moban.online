<?php

/**
 * @Project NUKEVIET 4.x
 * @Author DANGDINHTU (dlinhvan@gmail.com)
 * @Copyright (C) 2013 Webdep24.com. All rights reserved
 * @Blog  http://dangdinhtu.com
 * @License GNU/GPL version 2 or any later version
 * @Createdate  Wed, 21 Jan 2015 14:00:59 GMT
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );
 
$allow_func = array(
	'main',
	'category',
	'alias',
	'album',
    'imglist',
	'setting' );

define( 'NV_IS_FILE_ADMIN', true );

define( 'TABLE_PHOTO_NAME', NV_PREFIXLANG . '_' . $module_data ); 

define( 'ACTION_METHOD', $nv_Request->get_string( 'action', 'get,post', '' ) ); 
 
require_once NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php'; 
 
$array_status = array( '0' => $lang_module['disabled'], '1' => $lang_module['enable'] );

$array_viewcat = array( 'viewcat_grid' => $lang_module['category_viewcat_grid'], 'category_viewall_picture' => $lang_module['category_viewall_picture'] );

$array_home_view = array( 'home_view_slider' => $lang_module['home_view_slider'], 'home_view_grid_by_cat' => $lang_module['home_view_grid_by_cat'], 'home_view_grid_by_album' => $lang_module['home_view_grid_by_album'] );

$array_album_view = array( 'album_view_slider' => $lang_module['album_view_slider'], 'album_view_grid' => $lang_module['album_view_grid'] );

 

 
/**
 * nv_fix_cat_order()
 *
 * @param integer $parent_id
 * @param integer $order
 * @param integer $lev
 * @return
 */
function nv_fix_cat_order( $parent_id = 0, $order = 0, $lev = 0 )
{
	global $db, $module_data;

	$sql = 'SELECT category_id, parent_id FROM ' . TABLE_PHOTO_NAME . '_category WHERE parent_id=' . $parent_id . ' ORDER BY weight ASC';
	$result = $db->query( $sql );
	$array_cat_order = array();
	while( $row = $result->fetch() )
	{
		$array_cat_order[] = $row['category_id'];
	}
	$result->closeCursor();
	$weight = 0;
	if( $parent_id > 0 )
	{
		++$lev;
	}
	else
	{
		$lev = 0;
	}
	foreach( $array_cat_order as $category_id_i )
	{
		++$order;
		++$weight;
		$sql = 'UPDATE ' . TABLE_PHOTO_NAME . '_category SET weight=' . $weight . ', sort_order=' . $order . ', lev=' . $lev . ' WHERE category_id=' . intval( $category_id_i );
		$db->query( $sql );
		$order = nv_fix_cat_order( $category_id_i, $order, $lev );
	}
	$numsubcat = $weight;
	if( $parent_id > 0 )
	{
		$sql = 'UPDATE ' . TABLE_PHOTO_NAME . '_category SET numsubcat=' . $numsubcat;
		if( $numsubcat == 0 )
		{
			$sql .= ",subcatid='', viewcat='viewcat_grid'";
		}
		else
		{
			$sql .= ",subcatid='" . implode( ',', $array_cat_order ) . "'";
		}
		$sql .= ' WHERE category_id=' . intval( $parent_id );
		$db->query( $sql );
	}
	return $order;
}
 