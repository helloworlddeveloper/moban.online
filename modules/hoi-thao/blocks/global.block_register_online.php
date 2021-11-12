<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/9/2010 23:25
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( ! nv_function_exists( 'nv_reg_register_online' ) )
{
	function nv_reg_register_online( $block_config )
	{
		global $module_info, $lang_module, $site_mods, $nv_Request;

		$module = $block_config['module'];
		$mod_file = $site_mods[$module]['module_file'];

		if( file_exists( NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $mod_file . '/block_register_online.tpl' ) )
		{
			$block_theme = $module_info['template'];
		}
		else
		{
			$block_theme = 'default';
		}
        $ref_user = $nv_Request->get_int('ref', 'get');
		if( $ref_user > 0 ){
            $nv_Request->set_Session($module . '_ref', $ref_user );
        }

		$xtpl = new XTemplate( 'block_register_online.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $mod_file );
		$xtpl->assign( 'LANG', $lang_module );
		$xtpl->assign( 'TEMPLATE', $block_theme );
		$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
		$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
		$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
		$xtpl->assign( 'MODULE_NAME', $module );
		$xtpl->assign( 'BASE_URL_SITE', NV_BASE_SITEURL . 'index.php' );
		$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
		$xtpl->assign( 'OP_NAME', 'search' );

		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}
}

if( defined( 'NV_SYSTEM' ) )
{
	global $site_mods;
	$module = $block_config['module'];
	if( isset( $site_mods[$module] ) )
	{
		$content = nv_reg_register_online( $block_config );
	}
}
