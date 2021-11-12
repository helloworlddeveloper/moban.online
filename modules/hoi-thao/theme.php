<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 24 Dec 2014 06:56:00 GMT
 */

if( ! defined( 'NV_IS_MOD_REG' ) ) die( 'Stop!!!' );

/**
 * nv_theme_lich_hoc_main()
 * 
 * @param mixed $array_data
 * @return
 */
function nv_theme_reg_main( )
{
	global $module_name, $module_file, $lang_module, $module_info, $op, $user_info;

	$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
    $url = NV_MY_DOMAIN . NV_BASE_SITEURL . '?ref=' . $user_info['userid'];
    $xtpl->assign( 'URL', $url );
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}
