<?php

/**
 * @Project NUKEVIET 4.x
 * @Author EDUS.,JSC (thangbv@edus.vn)
 * @Copyright (C) 2014 EDUS.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 0:51
 */

if( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

define( 'NV_IS_MOD_DOWNLOAD', true );

/**
 * nv_setcats()
 *
 * @param mixed $id
 * @param mixed $list
 * @param mixed $name
 * @param mixed $is_parentlink
 * @return
 */
function nv_setcats( $id, $list, $name, $is_parentlink )
{
	global $module_name;

	if( $is_parentlink )
	{
		$name = "<a href=\"" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $list[$id]['alias'] . "\">" . $list[$id]['title'] . "</a> &raquo; " . $name;
	}
	else
	{
		$name = $list[$id]['title'] . " &raquo; " . $name;
	}
	$parentid = $list[$id]['parentid'];
	if( $parentid )
	{
		$name = nv_setcats( $parentid, $list, $name, $is_parentlink );
	}

	return $name;
}


/**
 * nv_mod_down_config()
 *
 * @return
 */
function nv_mod_down_config()
{
	global $nv_Cache, $module_data, $module_name;

	$sql = "SELECT config_name,config_value FROM " . NV_PREFIXLANG . "_" . $module_data . "_config";

	$list = $nv_Cache->db( $sql, $module_name );

	$download_config = array();
	foreach( $list as $values )
	{
		$download_config[$values['config_name']] = $values['config_value'];
	}

	$download_config['upload_filetype'] = ! empty( $download_config['upload_filetype'] ) ? explode( ',', $download_config['upload_filetype'] ) : array();
	if( ! empty( $download_config['upload_filetype'] ) ) $download_config['upload_filetype'] = array_map( "trim", $download_config['upload_filetype'] );

	if( empty( $download_config['upload_filetype'] ) )
	{
		$download_config['is_upload'] = 0;
	}

	if( $download_config['is_addfile'] )
	{
		$download_config['is_addfile_allow'] = nv_user_in_groups( $download_config['groups_addfile'] );
	}
	else
	{
		$download_config['is_addfile_allow'] = false;
	}

	if( $download_config['is_addfile_allow'] and $download_config['is_upload'] )
	{
		$download_config['is_upload_allow'] = nv_user_in_groups( $download_config['groups_upload'] );
	}
	else
	{
		$download_config['is_upload_allow'] = false;
	}

	return $download_config;
}

if( $op == "main" )
{
	$catalias = '';
	$filealias = '';
	$catid = 0;
	$nv_vertical_menu = array();

	if( ! empty( $list_cats ) )
	{
		if( ! empty( $array_op ) )
		{
			$catalias = isset( $array_op[0] ) ? $array_op[0] : "";
			$filealias = isset( $array_op[1] ) ? $array_op[1] : "";
		}

		//Het Xac dinh ID cua chu de

		//Xac dinh menu, RSS
		if( $module_info['rss'] )
		{
			$rss[] = array(
				'title' => $module_info['custom_title'],
				'src' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $module_info['alias']['rss']
			);
		}

		//het Xac dinh menu, RSS
		//Xem chi tiet
		if( $catid > 0 )
		{
			$op = "viewcat";
			$page = 1;
			if( preg_match( "/^page\-([0-9]+)$/", $filealias, $m ) )
			{
				$page = intval( $m[1] );
			}
			elseif( ! empty( $filealias ) )
			{
				$op = "viewfile";
			}
		}
	}
}