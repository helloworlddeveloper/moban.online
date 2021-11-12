<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 - 2016 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 08 Apr 2012 00:00:00 GMT GMT
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );
if( ! nv_function_exists( 'nv_news_notice_blocks' ) )
{
	function nv_block_config_news_notice_blocks( $module, $data_block, $lang_block )
	{
		$html = "";

		$mang = '';

		if( $handle = opendir( NV_ROOTDIR . "/modules/notice/template" ) )
		{
			while( false !== ( $entry = readdir( $handle ) ) )
			{
				if( $entry != "." && $entry != ".." )
				{
					if( $entry != '.htaccess' && $entry != 'index.html' && $entry != 'main.tpl' )
						if( $mang == '' )
						{
							$entry = substr( $entry, 0, -4 );
							$mang .= $entry;
						}
						else
						{
							$entry = substr( $entry, 0, -4 );
							$mang .= "," . $entry;
						}

				}
			}
			closedir( $handle );
		}

		$mang1 = array();
		$mang1 = explode( ",", $mang );

        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_block['xtem'] . ':</label>';
        $html .= '<div class="col-sm-18">';
        $html .= '<select name="xtem" class="form-control">';

		foreach( $mang1 as $l )
		{
			$html .= "<option value=\"" . $l . "\" " . ( ( $data_block['xtem'] == $l ) ? " selected=\"selected\"" : "" ) . ">" . $l . "</option>\n";
		}
        $html .= '</select>';
		$html .= "</div>\n";
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_block['numrow'] . ':</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_numrow" value="' . $data_block['numrow'] . '"/></div>';
        $html .= '</div>';
        $html .= '</div>';

		return $html;
	}

	function nv_block_config_news_notice_blocks_submit( $module, $lang_block )
	{
		global $nv_Request;
		$return = array();
		$return['error'] = array();
		$return['config'] = array();
		$return['config']['xtem'] = $nv_Request->get_string( 'xtem', 'post', '' );
		$return['config']['numrow'] = $nv_Request->get_int( 'config_numrow', 'post', 0 );
		return $return;
	}

	function nv_news_notice_blocks( $block_config )
	{
		global $module_array_cat, $module_info, $site_mods, $db;
		$module = $block_config['module'];
		$module_data = $site_mods[$module]['module_data'];
		$module_file = $site_mods[$module]['module_file'];
		//Lập danh sách các thông báo có thể được hiển thị
		$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows";
		$sql .= " WHERE (status =1) AND (pubtime < " . NV_CURRENTTIME . ") AND (exptime=0 OR exptime>" . NV_CURRENTTIME . ")";
		$sql .= " ORDER BY weight ASC LIMIT 0 , " . $block_config['numrow'];

		$result = $db->query( $sql );
		$list = array();
		$i = 0;
		while( $row = $result->fetch() )
		{
			$i++;
			$row['class'] = ( $row['catid'] != 0 ) ? "class='catbnotic" . $row['catid'] . "'" : "";
			$row['STT'] = $i;
			$list[] = $row;
		}

		//Hiện thông báo
		if( ! empty( $list ) )
		{
			$xtpl = new XTemplate( $block_config['xtem'] . ".tpl", NV_ROOTDIR . "/modules/" . $module_file . "/template" );
            $xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
            $xtpl->assign( 'MODULE_FILE', $module_file );
			foreach( $list as $row )
			{
				$xtpl->assign( 'ROW', $row );
				if( $row['link'] == '' )
				{
					$xtpl->parse( 'main.row.nolink' );
				}
				else
				{
					$xtpl->parse( 'main.row.link' );
				}
				$xtpl->parse( 'main.row' );
			}
			$xtpl->parse( 'main' );
			return $xtpl->text( 'main' );
		}
		else  return "";
	}

}
if( defined( 'NV_SYSTEM' ) )
{
	$content = nv_news_notice_blocks( $block_config );
}
