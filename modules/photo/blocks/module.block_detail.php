<?php

/**
 * @Project NUKEVIET 4.x
 * @Author DANGDINHTU (dlinhvan@gmail.com)
 * @Copyright (C) 2013 Webdep24.com. All rights reserved
 * @Blog  http://dangdinhtu.com
 * @License GNU/GPL version 2 or any later version
 * @Createdate  Wed, 21 Jan 2015 14:00:59 GMT
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( ! nv_function_exists( 'block_photo_detail' ) )
{
 
	function block_photo_detail( $block_config )
	{
		global $data_album, $module_photo_cat, $lang_module, $op, $client_info, $site_mods, $module_info, $db, $module_config, $global_config, $my_head;

		if(  $op == 'detail' )
		{
		
			$module = $block_config['module'];
			$mod_data = $site_mods[$module]['module_data'];
			$mod_file = $site_mods[$module]['module_file'];
			
			if( file_exists( NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/'. $mod_file .'/module.block_detail.tpl' ) )
			{
				$block_theme = $module_info['template'];
			}
			else
			{
				$block_theme = 'default';
			}
			$xtpl = new XTemplate( 'module.block_detail.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/'. $mod_file .'/' );
			$xtpl->assign( 'LANG', $lang_module );
			$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
			$xtpl->assign( 'TEMPLATE', $module_info['template'] );
			$xtpl->assign( 'MODULE_FILE', $mod_file );
			$xtpl->assign( 'SELFURL', $client_info['selfurl'] );
			
			$data_album['image'] = NV_MY_DOMAIN . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module . '/images/' . $data_album['file'];
			$data_album['thumb'] = NV_MY_DOMAIN . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module . '/thumb/' . $data_album['thumb'];
			
			
			// $my_head="<meta name=\"thumbnail\" content=\"".$data_album['thumb']."\"/>";
			// $my_head.="<!--";
			// $my_head.="  <PageMap>";
			// $my_head.="	<DataObject type=\"thumbnail\">";
			// $my_head.="	  <Attribute name=\"src\" value=\"http://dangdinhtu.com/uploads/photo/thumb/2015_01/90x72-148-copy.jpg\"/>";
			// $my_head.="	  <Attribute name=\"width\" value=\"100\"/>";
			// $my_head.="	  <Attribute name=\"height\" value=\"130\"/>";
			// $my_head.="	</DataObject>";
			// $my_head.="  </PageMap>";
			// $my_head.="-->";
			
			$ratingwidth = ( $data_album['total_rating'] > 0 ) ? ( $data_album['total_rating'] * 100 / ( $data_album['click_rating'] * 5 ) ) * 0.01 : 0;
		 
			$xtpl->assign( 'RATINGVALUE', ( $data_album['total_rating'] > 0 ) ? round( $data_album['total_rating']/$data_album['click_rating'], 1) : 0 );
			$xtpl->assign( 'RATINGCOUNT', $data_album['click_rating'] );
			$xtpl->assign( 'REVIEWCOUNT', $data_album['total_rating'] );
			$xtpl->assign( 'RATINGWIDTH', round( $ratingwidth, 2) );
			$xtpl->assign( 'LINK_RATE', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module . '&' . NV_OP_VARIABLE . '=rating&album_id=' . $data_album['album_id'] );
			
			$data_album['capturedate'] = nv_date('d-m-Y', $data_album['capturedate']);
			$xtpl->assign( 'DATA', $data_album );
			
			

			$xtpl->parse( 'main' );
			return $xtpl->text( 'main' );
		}
	}
}

if( defined( 'NV_SYSTEM' ) )
{
	global $site_mods, $module_name, $global_photo_cat, $module_photo_cat;
	$module = $block_config['module'];
	if( isset( $site_mods[$module] ) )
	{
 
		if( $module == $module_name )
		{
			$module_photo_cat = $global_photo_cat;
			unset( $module_photo_cat[0] );
		}
		else
		{
			$module_photo_cat = array();
			$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_category ORDER BY sort_order ASC';
			$list = nv_db_cache( $sql, 'category_id', $module  );
			foreach( $list as $l )
			{
				$module_photo_cat[$l['category_id']] = $l;
				$module_photo_cat[$l['category_id']]['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias'];
				
			}
		}
		$content = block_photo_detail( $block_config  );
	}
}