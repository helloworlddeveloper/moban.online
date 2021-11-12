<?php

/**
 * @Project PHOTOS 4.x
 * @Author KENNY NGUYEN (nguyentiendat713@gmail.com)
 * @Copyright (C) 2015 tradacongnghe.com. All rights reserved
 * @Based on NukeViet CMS
 * @License GNU/GPL version 2 or any later version
 * @Createdate  Fri, 18 Sep 2015 11:52:59 GMT
 */

if( !defined( 'NV_MAINFILE' ) )
	die( 'Stop!!!' );


if( !nv_function_exists( 'nv_block_category_video' ) )
{
	function nv_block_config_category_video( $module, $data_block, $lang_block )
	{
		global $nv_Cache, $site_mods;

		$html = '<tr>';
		$html .= '<td>' . $lang_block['category_id'] . '</td>';
		$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_topic ORDER BY weight ASC';
		$list = $nv_Cache->db( $sql, '', $module );
		$html .= '<td>';
		foreach( $list as $l )
		{
			$xtitle_i = '';
			if( $l['parentid'] > 0 )
			{
			    $xtitle_i .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			}
			$html .= $xtitle_i . '<label><input type="checkbox" name="config_category[]" value="' . $l['id'] . '" ' . (( in_array( $l['id'], $data_block['category_id'] = !empty( $data_block['category_id'] ) ? $data_block['category_id'] : array( ) )) ? ' checked="checked"' : '') . '</input>' . $l['title'] . '</label><br />';
		}
		$html .= '</td>';
		$html .= '</tr>';

		$html .= '<tr>';
		$html .= '<td>' . $lang_block['numrow'] . '</td>';
		$html .= '<td><input type="text" class="form-control w200" name="config_numrow" size="5" value="' . $data_block['numrow'] . '"/></td>';
		$html .= '</tr>';

		$html .= '<tr>';
		$html .= '<td>' . $lang_block['title_length'] . '</td>';
		$html .= '<td><input type="text" class="form-control w200" name="config_title_length" size="5" value="' . $data_block['title_length'] . '"/></td>';
		$html .= '</tr>';

		$html .= '<tr>';
		$html .= '<td>' . $lang_block['des_length'] . '</td>';
		$html .= '<td><input type="text" class="form-control w200" name="config_des_length" size="5" value="' . $data_block['des_length'] . '"/></td>';
		$html .= '</tr>';

		return $html;
	}

	function nv_block_config_category_video_submit( $module, $lang_block )
	{
		global $nv_Request;
		$return = array( );
		$return['error'] = array( );
		$return['config'] = array( );
		$return['config']['category_id'] = $nv_Request->get_array( 'config_category', 'post', array( ) );
		$return['config']['numrow'] = $nv_Request->get_int( 'config_numrow', 'post', 0 );
		$return['config']['title_length'] = $nv_Request->get_int( 'config_title_length', 'post', 0 );
		$return['config']['des_length'] = $nv_Request->get_int( 'config_des_length', 'post', 0 );
		return $return;
	}

	function nv_block_category_video( $block_config )
	{
		global $nv_Cache, $module_photo_category, $module_info, $site_mods, $module_config, $lang_module, $global_config, $db, $blockID;

		$module = $block_config['module'];
		$thumb_width = $module_config[$module]['cr_thumb_width'];
		$thumb_height = $module_config[$module]['cr_thumb_height'];
		$thumb_quality = $module_config[$module]['cr_thumb_quality'];
		$mod_data = $site_mods[$module]['module_data'];
		$mod_file = $site_mods[$module]['module_file'];

		if( empty( $block_config['category_id'] ) )
			return '';

		$category_id = $block_config['category_id'];

        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_topic ORDER BY weight ASC';
        $list_topic = $nv_Cache->db( $sql, 'id', $module );

        $array_content = array();
		foreach ( $list_topic as $category ){

            if( in_array( $category['id'], $category_id )){

                $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $site_mods[$module]['module_data'] . "_clip WHERE tid= " . $category['id'] . " AND status=1 ORDER BY id DESC LIMIT 0," . $block_config['numrow'];
                $result = $db->query( $sql );
                while ($row = $result->fetch()){
                    $array_content[$category['id']]['data'][] = $row;
                }
                if( isset( $array_content[$category['id']]['data']  )){
                    $array_content[$category['id']]['cat'] = $category;
                }
            }
        }
		if( !empty( $array_content ) )
		{
			if( file_exists( NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $mod_file . '/block_category_album.tpl' ) )
			{
				$block_theme = $global_config['module_theme'];
			}
			else
			{
				$block_theme = 'default';
			}

			$xtpl = new XTemplate( 'block_category_album.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $mod_file );
			$xtpl->assign( 'BLOCK_ID', $blockID );
			$xtpl->assign( 'LANG', $lang_module );

			foreach( $array_content as $content_category )
			{
                foreach ( $content_category['data'] as $row){
                    if (!empty($row['img'])) {
                        $row['img'] = substr($row['img'], strlen(NV_UPLOADS_DIR));
                        if (file_exists(NV_ROOTDIR . '/' . NV_ASSETS_DIR . $row['img'])) {
                            $row['img'] = NV_BASE_SITEURL . NV_ASSETS_DIR . $row['img'];
                        } elseif (file_exists(NV_UPLOADS_REAL_DIR . $row['img'])) {
                            $row['img'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . $row['img'];
                        } else {
                            $row['img'] = '';
                        }
                    }
                    if (empty($row['img'])) {
                        $row['img'] = NV_BASE_SITEURL . "themes/" . $block_theme . "/images/" . $mod_file . "/video.png";
                    }
                    $row['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . "&amp;" . NV_OP_VARIABLE . "=" . $row['alias'] . $global_config['rewrite_exturl'];

                    $xtpl->assign( 'ALBUM', $row );
                    $xtpl->parse( 'main.category.loop_album' );
                }

                $xtpl->assign( 'CAT', $content_category['cat'] );
                $xtpl->parse( 'main.category' );
			}
			$xtpl->parse( 'main' );
			return $xtpl->text( 'main' );
		}
	}

}
if( defined( 'NV_SYSTEM' ) )
{
	global $nv_Cache, $site_mods, $module_name, $global_photo_cat, $module_photo_category;
	$module = $block_config['module'];
	if( isset( $site_mods[$module] ) )
	{
		$content = nv_block_category_video( $block_config );
	}
}
