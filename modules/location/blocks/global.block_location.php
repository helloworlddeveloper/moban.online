<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Dec 3, 2010  12:57:52 PM 
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

/**
 * nv_block_sample()
 * 
 * @return
 */
if( ! function_exists( 'nv_block_location' ) )
{

	function nv_block_location( $block_config )
	{
		global $lang_module, $module_name, $module_data, $module_file, $module_config, $module_info, $global_config, $db, $op, $array_op;
		$module = $block_config['module'];

		$xtpl = new XTemplate( "block_location.tpl", NV_ROOTDIR . "/themes/" . $global_config['site_theme'] . "/modules/" . $module );
		$xtpl->assign( 'LANG', $lang_module );
		$xtpl->assign( 'themes', $global_config['site_theme'] );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
		$arr = array();

		if( $module_name != 'diaoc' || $module_name != 'project' ) $url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=diaoc&' . NV_OP_VARIABLE . '=';
		else  $url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=';
		
        $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module . "_province WHERE status=1 ORDER BY weight ASC";
		$result = $db->query( $sql );
		//$list = array();
		while( $row = $result->fetch() )
		{
			$xtpl->assign( 'ROW', array( //
				'title' => $row['title'], //
				'alias' => $row['alias'], //
				'weight' => ( int )$row['weight'], //
				'link' => $url . $row['alias'] ) );
			$xtpl->parse( 'main.loop' );
		}

		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );

	}
}
if( defined( 'NV_SYSTEM' ) )
{
	$content = nv_block_location( $block_config );
}

?>