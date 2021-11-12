<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Mr.An (anvh.ceo@gmail.com)
 * @Copyright (C) 2014 EDUS.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 0:51
 */

if( ! defined( 'NV_IS_MOD_DOWNLOAD' ) ) die( 'Stop!!!' );

/**
 * theme_viewcat_download()
 *
 * @param mixed $array
 * @param mixed $download_config
 * @param mixed $subs
 * @param mixed $generate_page
 * @return
 */
function theme_main_download( $array_item, $array_users, $download_config )
{
	global $global_config, $lang_module, $lang_global, $module_info, $module_name, $module_file, $my_head, $op;
	$xtpl = new XTemplate( 'main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file . '/' );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'OP', $op );
    $xtpl->assign( 'GLANG', $lang_global );
    $xtpl->assign( 'TEMPLATE', $module_info['template'] );
    $xtpl->assign( 'MODULE_FILE', $module_file );
	$xtpl->assign( 'IMG_FOLDER', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file );
	$xtpl->assign( 'MODULELINK', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' );

	if( ! empty( $array_item ) )
	{
		foreach( $array_item as $item )
		{
			$xtpl->assign( 'ITEM', $item );
			$xtpl->parse( 'main.items.loop' );
		}
        foreach( $array_users as $users )
		{
			$xtpl->assign( 'USERS', $users );
			$xtpl->parse( 'main.users' );
		}
		$xtpl->parse( 'main.items' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function theme_main_share_u( $array_item, $download_config )
{
	global $global_config, $lang_module, $lang_global, $module_info, $module_name, $module_file, $my_head, $op;
	$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file . '/' );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'OP', $op );
    $xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'IMG_FOLDER', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/download/' );
	$xtpl->assign( 'MODULELINK', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' );

	if( ! empty( $array_item ) )
	{
		foreach( $array_item as $item )
		{
			$xtpl->assign( 'ITEM', $item );
			$xtpl->parse( 'main.items.loop' );
		}
		$xtpl->parse( 'main.items' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function theme_viewcat_download( $array, $download_config, $subs, $generate_page )
{
	global $global_config, $lang_module, $lang_global, $module_info, $module_name, $module_file;

	$xtpl = new XTemplate( 'viewcat_page.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file . '/' );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'IMG_FOLDER', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/download/' );
	$xtpl->assign( 'MODULELINK', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' );

	if( $download_config['is_addfile_allow'] )
	{
		$xtpl->assign( 'UPLOAD', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=upload' );
		$xtpl->parse( 'main.is_addfile_allow' );
	}
	#view cat
	if( ! empty( $subs ) )
	{
		$i = 1;
		foreach( $subs as $cat )
		{
			$cat['current'] = 'current-cat';
			$xtpl->assign( 'listsubcat', $cat );
			if( ! empty( $cat['posts'] ) )
			{
				//post in subcat
				$items = $cat['posts'];
				#parse the first items
				$thefirstcat = current( $items );
				$xtpl->assign( 'itemcat', $thefirstcat );
				if( ! empty( $thefirstcat['imagesrc'] ) )
				{
					$xtpl->parse( 'main.listsubcat.itemcat.image' );
				}
				foreach( $items as $item )
				{
					if( $item['id'] != $thefirstcat['id'] )
					{
						$xtpl->assign( 'loop', $item );
						$xtpl->parse( 'main.listsubcat.itemcat.related.loop' );
					}
				}
				$xtpl->parse( 'main.listsubcat.itemcat.related' );
				$xtpl->parse( 'main.listsubcat.itemcat' );
				//post in subcat
			}

			$xtpl->parse( 'main.listsubcat' );
			$i = 0;
		}
	}

	if( ! empty( $array ) )
	{
		foreach( $array as $row )
		{
			$xtpl->assign( 'listpostcat', $row );

			if( ! empty( $row['author_name'] ) )
			{
				$xtpl->parse( 'main.row.author_name' );
			}

			if( ! empty( $row['imagesrc'] ) )
			{
				$xtpl->parse( 'main.listpostcat.image' );
			}

			if( ! empty( $row['edit_link'] ) )
			{
				$xtpl->parse( 'main.listpostcat.is_admin' );
			}

			$xtpl->parse( 'main.listpostcat' );
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
 * view_file()
 *
 * @param mixed $row
 * @param mixed $download_config
 * @return
 */
function view_file( $row, $download_config, $content_comment )
{
	global $global_config, $lang_global, $lang_module, $module_name, $module_file, $module_info, $my_head;

	if( ! defined( 'SHADOWBOX' ) and isset( $row['fileimage']['src'] ) and ! empty( $row['fileimage']['src'] ) )
	{
		$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.js\"></script>\n";
		$my_head .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.css\" />\n";
		$my_head .= "<script type=\"text/javascript\">\n";
		$my_head .= "Shadowbox.init();\n";
		$my_head .= "</script>\n";

		define( 'SHADOWBOX', true );
	}

	$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/star-rating/jquery.rating.pack.js\"></script>\n";
	$my_head .= "<script src=\"" . NV_BASE_SITEURL . "js/star-rating/jquery.MetaData.js\" type=\"text/javascript\"></script>\n";
	$my_head .= "<link href=\"" . NV_BASE_SITEURL . "js/star-rating/jquery.rating.css\" type=\"text/css\" rel=\"stylesheet\" />\n";

	$xtpl = new XTemplate( 'viewfile.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file . '/' );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'ROW', $row );

	if( $download_config['is_addfile_allow'] )
	{
		$xtpl->assign( 'UPLOAD', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=upload' );
		$xtpl->parse( 'main.is_addfile_allow' );
	}

	if( isset( $row['fileimage']['src'] ) and ! empty( $row['fileimage']['src'] ) )
	{
		$xtpl->assign( 'FILEIMAGE', $row['fileimage'] );
		$xtpl->parse( 'main.is_image' );
	}

	if( ! empty( $row['download_info'] ) )
	{
		$xtpl->parse( 'main.download_info' );
	}

	if( ! empty( $row['description'] ) )
	{
		$xtpl->parse( 'main.introtext' );
	}

	if( $row['is_download_allow'] )
	{
		$xtpl->parse( 'main.report' );

		if( ! empty( $row['fileupload'] ) )
		{
			$xtpl->assign( 'SITE_NAME', $global_config['site_name'] );

			$a = 0;
			foreach( $row['fileupload'] as $fileupload )
			{
				$fileupload['key'] = $a;
				$xtpl->assign( 'FILEUPLOAD', $fileupload );
				$xtpl->parse( 'main.download_allow.fileupload.row' );
				++$a;
			}

			$xtpl->parse( 'main.download_allow.fileupload' );
		}

		if( ! empty( $row['linkdirect'] ) )
		{
			foreach( $row['linkdirect'] as $host => $linkdirect )
			{
				$xtpl->assign( 'HOST', $host );

				foreach( $linkdirect as $link )
				{
					$xtpl->assign( 'LINKDIRECT', $link );
					$xtpl->parse( 'main.download_allow.linkdirect.row' );
				}

				$xtpl->parse( 'main.download_allow.linkdirect' );
			}
		}

		$xtpl->parse( 'main.download_allow' );
	}
	else
	{
		$xtpl->parse( 'main.download_not_allow' );
	}

	if( $row['rating_disabled'] )
	{
		$xtpl->parse( 'main.disablerating' );
	}

	if( defined( 'NV_IS_MODADMIN' ) )
	{
		$xtpl->parse( 'main.is_admin' );
	}

	if( ! empty( $content_comment ) )
	{
		$xtpl->assign( 'CONTENT_COMMENT', $content_comment );
		$xtpl->parse( 'main.comment' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * theme_upload()
 *
 * @param mixed $array
 * @param mixed $download_config
 * @param mixed $error
 * @return
 */
function theme_upload( $array, $download_config, $fileshare, $error )
{
	global $module_info, $module_name, $module_file, $lang_module, $lang_global, $my_head;

	$xtpl = new XTemplate( 'upload.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file . '/' );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'MODULE_FILE', $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'DOWNLOAD', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name );
	$xtpl->assign( 'UPLOAD', $array );
	$xtpl->assign( 'FORM_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=upload' );
	$xtpl->assign( 'URL_UPLOAD', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=upload&uploadfile=1' );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'CAPTCHA_MAXLENGTH', NV_GFX_NUM );
	$xtpl->assign( 'EXT_ALLOWED', implode( ', ', $download_config['upload_filetype'] ) );

	if( ! empty( $error ) )
	{
		$xtpl->assign( 'ERROR', $error );
		$xtpl->parse( 'main.is_error' );
	}
	if( ! empty( $fileshare ) )
	{
		$xtpl->assign( 'fileshare', $fileshare );
		$xtpl->parse( 'main.fileshare' );
	}
	if( $download_config['is_upload_allow'] )
	{
		$xtpl->parse( 'main.is_upload_allow' );
	}
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}
