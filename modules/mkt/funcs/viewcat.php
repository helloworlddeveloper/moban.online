<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 18 Nov 2014 01:50:26 GMT
 */

if( ! defined( 'NV_IS_MOD_RM' ) ) die( 'Stop!!!' );

$cache_file = '';
$contents = '';
if( isset( $global_array_cat[$catid] ) )
{
	$page_title = $global_array_cat[$catid]['title'];
	$description = $global_array_cat[$catid]['description'];

}

$base_url_rewrite = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$catid]['alias'];
if( $page > 1 )
{
	$base_url_rewrite .= '/page-' . $page;
}

if( ! defined( 'NV_IS_MODADMIN' ) and $page < 5 )
{
	if( $set_view_page )
	{
		$cache_file = NV_LANG_DATA . '_' . $module_info['template'] . '_' . $op . '_' . $catid . '_page_' . $page . '_' . NV_CACHE_PREFIX . '.cache';
	}
	else
	{
		$cache_file = NV_LANG_DATA . '_' . $module_info['template'] . '_' . $op . '_' . $catid . '_' . $page . '_' . NV_CACHE_PREFIX . '.cache';
	}
	if( ( $cache = nv_get_cache( $module_name, $cache_file ) ) != false )
	{
		$contents = $cache;
	}
}
if( empty( $contents ) )
{
	$array_catpage = array();
	$array_cat_other = array();
	$base_url = $global_array_cat[$catid]['link'];
	$order_by = 'weight ASC';

	$data_room_id = $db->query( 'SELECT room_id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat_room WHERE catid=' . $catid )->fetchAll( 7 );
	if( ! empty( $data_room_id ) )
	{
		$db->sqlreset()->select( 'COUNT(*)' )->from( NV_PREFIXLANG . '_' . $module_data . '_room' )->where( 'room_id IN (' . implode( ',', $data_room_id ) . ')' );

		$num_items = $db->query( $db->sql() )->fetchColumn();

		$db->select( '*' )->order( $order_by )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );
		$result = $db->query( $db->sql() );
		while( $item = $result->fetch() )
		{
			if( ! empty( $item['image'] ) && file_exists( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . '/' . $item['image'] ) )
			{
				$item['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $item['image'];
			}
			else
			{
				$item['image'] = '';
			}

			$item['link'] = current( $list_room_on_cat[$item['room_id']] ) . '/' . $item['alias'] . $global_config['rewrite_exturl'];
            $item['link_order'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=order/' . $item['alias'] . $global_config['rewrite_exturl'];
			$array_data[] = $item;
		}

		$generate_page = nv_alias_page( $page_title, $base_url, $num_items, $per_page, $page );
		$contents = nv_theme_rm_viewcat( $page_title, $description, $array_data, $generate_page );

		if( ! defined( 'NV_IS_MODADMIN' ) and $contents != '' and $cache_file != '' )
		{
			nv_set_cache( $module_name, $cache_file, $contents );
		}
	}
    else{
        $contents = $lang_module['empty_content']; 
    }
}

if( $page > 1 )
{
	$page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
	$description .= ' ' . $page;
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
