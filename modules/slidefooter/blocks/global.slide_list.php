<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 19 Jun 2014 02:27:09 GMT
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

function nv4_block_slide( $block_config )
{
	global $global_config, $db, $site_mods, $module_name, $module_info;

	$module = $block_config['module'];
    
	$list = array();
	if( isset( $site_mods[$module] ) )
	{
		$mod_file = $site_mods[$module]['module_file'];
		$mod_data = $site_mods[$module]['module_data'];
	}
    $db->sqlreset()
        ->select( '*' )
        ->from( '' . NV_PREFIXLANG . '_' . $mod_data )
        -> where('status = 1')
        ->order( 'id DESC' )
        ->limit( 20 );
    $sth = $db->prepare( $db->sql() );
    $sth->execute();
    $list = $sth->fetchAll();
	
	if( file_exists( NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $mod_file . '/block_slide_list.tpl' ) )
	{
		$block_theme = $module_info['template'];
	}
	else
	{
		$block_theme = 'default';
	}
	$xtpl = new XTemplate( 'block_slide_list.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $mod_file );
	
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'TEMPLATE', $block_theme );
    $xtpl->assign( 'bid', $block_config['bid'] );
    $i=0;
    foreach( $list as $row )
    {     $i++;   
       
        $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module . '/' .$row['image'] ;
        $xtpl->assign( 'num', $i );
        $xtpl->assign( 'ROW', $row );
        $xtpl->assign( 'class', $row );       
        $xtpl->parse( 'main.slide' );
        $xtpl->parse( 'main.num' );
    }
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

if( defined( 'NV_SYSTEM' ) )
{
	$content = nv4_block_slide( $block_config );
}

?>