<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Mr.Thang (kid.apt@gmail.com)
 * @Copyright (C) 2017 Mr.Thang. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10-01-2017 20:08
 */


if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if ( ! nv_function_exists( 'nv_load_notification' ) )
{
    function nv_load_notification ( $block_config )
    {
        global $site_mods, $module_info, $client_info, $module_config;
        
        $module = $block_config['module'];
        $mod_file = $site_mods[$module]['module_file'];
        
		if ( file_exists( NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $mod_file . "/block_load_notification.tpl" ) )
        {
            $block_theme = $module_info['template'];
        }
        else
        {
            $block_theme = "default";
        }
        
        $xtpl = new XTemplate( "block_load_notification.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $mod_file );
        $xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
        $xtpl->assign( 'MODULE_FILE', $mod_file );
        $module_config[$module]['timeout'] = $module_config[$module]['timeout'] * 1000;
        $xtpl->assign( 'CONFIG', $module_config[$module] );
        $xtpl->assign( 'module', $module );
        $xtpl->assign( 'checkallow', md5( $client_info['ip'] . session_id() ) );
        $xtpl->parse( 'main' );
        return $xtpl->text( 'main' );
    }
}

if ( defined( 'NV_SYSTEM' ) )
{
    $content = nv_load_notification( $block_config );
}

?>