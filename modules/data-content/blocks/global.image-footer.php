<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 19 Jun 2014 02:27:09 GMT
 */

if( ! defined( 'NV_MAINFILE' ) )
	die( 'Stop!!!' );
if( ! nv_function_exists( 'nv4_image_block_footer' ) )
{
	function nv4_image_block_footer( $block_config )
	{
		global $global_config, $db, $site_mods, $module_name, $module_info;

		$module = $block_config['module'];
		$list = array();
		if( isset( $site_mods[$module] ) )
		{
			$mod_file = $site_mods[$module]['module_file'];
			$mod_data = $site_mods[$module]['module_data'];
		}
		$db->sqlreset()->select( '*' )->from( '' . NV_PREFIXLANG . '_' . $mod_data )->where( 'status = 1' )->order( 'id DESC' )->limit( 10 );
		$sth = $db->prepare( $db->sql() );
		$sth->execute();
		$list = $sth->fetchAll();
		if( file_exists( NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $mod_file . '/global.block_image_footer.tpl' ) )
		{
			$block_theme = $module_info['template'];
		}
		else
		{
			$block_theme = 'default';
		}
		$xtpl = new XTemplate( 'global.block_image_footer.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $mod_file );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
		$xtpl->assign( 'TEMPLATE', $block_theme );
		$i = 0;
		foreach( $list as $row )
		{
			$i++;
			$row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module . '/' . $row['image'];
			$xtpl->assign( 'ROW', $row );
			$xtpl->parse( 'main.images' );
		}
		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}
}
if( defined( 'NV_SYSTEM' ) )
{
	$content = nv4_image_block_footer( $block_config );
}

?>