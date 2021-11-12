<?php

/**
 * @Project NUKEVIET 4.x
 * @Author DANGDINHTU (dlinhvan@gmail.com)
 * @Copyright (C) 2013 Webdep24.com. All rights reserved
 * @Blog  http://dangdinhtu.com
 * @License GNU/GPL version 2 or any later version
 * @Createdate  Wed, 21 Jan 2015 14:00:59 GMT
 */

if( ! defined( 'NV_IS_MOD_PHOTO' ) )
	die( 'Stop!!!' );

/**
 * home_view_grid_by_cat()
 * 
 * @param mixed $array_data
 * @return
 */
function home_view_grid_by_cat( $array_cat )
{
	global $global_config, $global_photo_cat, $module_name, $module_file, $lang_module, $photo_config, $module_info, $op;

	$xtpl = new XTemplate( 'home_view_grid_by_cat.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'MODULE_FILE', $module_file );
	$xtpl->assign( 'OP', $op );
	if( ! empty( $global_photo_cat ) )
	{
		foreach( $array_cat as $key => $catalog )
		{
			if( isset( $array_cat[$key]['content'] ) )
			{
				$xtpl->assign( 'CATALOG', $catalog );
				foreach( $array_cat[$key]['content'] as $album )
				{
					$album['description'] = strip_tags( nv_clean60( $album['description'], 150 ) );
					$album['datePublished'] = date( 'Y-m-d', $album['date_added'] );
					$album['thumb'] = NV_BASE_SITEURL . $album['thumb'];
					$album['file'] = NV_BASE_SITEURL . $album['file'];

					$xtpl->assign( 'ALBUM', $album );
					$xtpl->parse( 'main.loop_catalog.loop_album' );
					$xtpl->set_autoreset();
				}

				$xtpl->parse( 'main.loop_catalog' );
			}
			else
			{
			 if (isset( $array_cat[$key]['listimg'] )){
			     foreach( $array_cat[$key]['listimg'] as $img )
    				{
    					$img['thumb'] = NV_BASE_SITEURL . $img['thumb'];
    					$img['file'] = NV_BASE_SITEURL . $img['file'];
    
    					$xtpl->assign( 'PHOTO', $img );
    					$xtpl->parse( 'main.img.loop' );
    				}
                    $xtpl->parse( 'main.img' );
			     }
			}
		}

	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}
/**
 * viewcat_grid()
 * 
 * @param mixed $array_data
 * @return
 */
function viewcat_grid( $array_catpage, $generate_page )
{
	global $global_config, $category_id, $global_photo_cat, $client_info, $module_name, $module_file, $lang_module, $photo_config, $module_info, $op;

	$xtpl = new XTemplate( 'viewcat_grid.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'MODULE_FILE', $module_file );
	$xtpl->assign( 'OP', $op );
	$xtpl->assign( 'CATALOG', $global_photo_cat[$category_id] );
	$xtpl->assign( 'SELFURL', $client_info['selfurl'] );
	if( ! empty( $array_catpage ) )
	{
		foreach( $array_catpage as $album )
		{

			$album['description'] = strip_tags( nv_clean60( $album['description'], 150 ) );
			$album['datePublished'] = date( 'Y-m-d', $album['date_added'] );
			$album['thumb'] = NV_BASE_SITEURL . $album['thumb'];
			$album['file'] = NV_BASE_SITEURL . $album['file'];

			$xtpl->assign( 'ALBUM', $album );
			$xtpl->parse( 'main.loop_album' );
		}

	}
	if( ! empty( $generate_page ) )
	{

		$xtpl->assign( 'GENERATE_PAGE', $generate_page );
		$xtpl->parse( 'main.generate_page' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * detail_album()
 * 
 * @param mixed $album
 * @return
 */
function detail_album( $album, $array_photo, $other_category_album )
{
	global $global_config, $category_id, $client_info, $global_photo_cat, $module_name, $module_file, $lang_module, $photo_config, $module_info, $op;

	$xtpl = new XTemplate( 'detail_album.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'MODULE_FILE', $module_file );
	$xtpl->assign( 'OP', $op );
	$xtpl->assign( 'CATALOG', $global_photo_cat[$category_id] );
	$xtpl->assign( 'SELFURL', $client_info['selfurl'] );

	if( ! empty( $album ) )
	{

		// $ratingwidth = ( $album['total_rating'] > 0 ) ? ( $album['total_rating'] * 100 / ( $album['click_rating'] * 5 ) ) * 0.01 : 0;

		// $xtpl->assign( 'RATINGVALUE', ( $album['total_rating'] > 0 ) ? round( $album['total_rating']/$album['click_rating'], 1) : 0 );
		// $xtpl->assign( 'RATINGCOUNT', $album['total_rating'] );
		// $xtpl->assign( 'RATINGWIDTH', round( $ratingwidth, 2) );
		// $xtpl->assign( 'LINK_RATE', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=rating&album_id=' . $album['album_id'] );

		$album['description'] = strip_tags( nv_clean60( $album['description'], 150 ) );
		$album['datePublished'] = date( 'Y-m-d', $album['date_added'] );

		$xtpl->assign( 'ALBUM', $album );
		$num = 0;
		if( ! empty( $array_photo ) )
		{
			foreach( $array_photo as $photo )
			{
				//$photo['thumb'] = creat_thumbs( $photo['row_id'], $photo['file'], $module_name, 300, 210, 90 );
				$photo['thumb'] = NV_BASE_SITEURL . $photo['thumb'];
				$photo['file'] = NV_BASE_SITEURL . $photo['file'];
				$photo['num'] = $num;
				$xtpl->assign( 'PHOTO', $photo );
				$xtpl->parse( 'main.loop_slide' );
				$xtpl->parse( 'main.loop_thumb' );
				++$num;
			}
		}

	}
	if( ! empty( $other_category_album ) )
	{
		$key = 1;
		foreach( $other_category_album as $other )
		{
			$other['description'] = strip_tags( nv_clean60( $other['description'], 150 ) );
			$other['datePublished'] = date( 'Y-m-d', $other['date_added'] );
			$other['thumb'] = NV_BASE_SITEURL . $other['thumb'];
			$other['file'] = NV_BASE_SITEURL . $other['file'];
			$other['key'] = $key;
			$xtpl->assign( 'OTHER', $other );
			$xtpl->parse( 'main.loop_album' );
			++$key;
		}
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function no_permission( $groups_view )
{
	return '';

}
