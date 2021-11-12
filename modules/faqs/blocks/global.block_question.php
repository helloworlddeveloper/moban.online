<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/9/2010 23:25
 */

if( !defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( !nv_function_exists( 'nv_faqs_book' ) )
{
	function nv_block_config_question( $module, $data_block, $lang_block )
	{
		global $db, $site_mods;
		$html = '';
        
        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_cat ORDER BY weight ASC';
		$list = nv_db_cache( $sql, '', $module );
        
        $html .= '<tr>';
		$html .= '<td>' . $lang_block['category'] . '</td>';
		$html .= '<td><select name="config_category" class="form-control w200">';
		$html .= '<option value="0"> -- </option>';
		foreach( $list as $l )
		{
			$html .= '<option value="' . $l['id'] . '" ' . ( ( $data_block['category'] == $l['id'] ) ? ' selected="selected"' : '' ) . '>' . $xtitle . $l['title'] . '</option>';
		}
		$html .= '</select>';
		$html .= '</tr>';
        $html .= '<tr>';
		$html .= '	<td>' . $lang_block['title_length'] . '</td>';
		$html .= '	<td><input type="text" name="config_title_length" class="form-control w200" value="' . $data_block['title_length'] . '"/></td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '	<td>' . $lang_block['numrow'] . '</td>';
		$html .= '	<td><input type="text" name="config_numrow" class="form-control w200" value="' . $data_block['numrow'] . '"/></td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '	<td>' . $lang_block['is_statics'] . '</td>';
		$ck = $data_block['is_statics'] ? 'checked="checked"' : '';
		$html .= '	<td><input type="checkbox" name="config_is_statics" value="1" ' . $ck . ' /></td>';
		$html .= '</tr>';
		return $html;
	}

	function nv_block_config_question_submit( $module, $lang_block )
	{
		global $nv_Request;
		$return = array();
		$return['error'] = array();
		$return['config'] = array();
		$return['config']['category'] = $nv_Request->get_int( 'config_category', 'post', 24 );
        $return['config']['title_length'] = $nv_Request->get_int( 'config_title_length', 'post', 24 );
		$return['config']['numrow'] = $nv_Request->get_int( 'config_numrow', 'post', 5 );
		$return['config']['is_statics'] = $nv_Request->get_int( 'config_is_statics', 'post', 0 );
		return $return;
	}

	function nv_faqs_book( $block_config )
	{
		global $db, $module_array_cat, $module_info, $lang_module, $site_mods, $db, $module_name, $global_config, $my_head;

		$module = $block_config['module'];

		if( $module != $module_name )
		{
			$lang_temp = $lang_module;
			if( file_exists( NV_ROOTDIR . "/modules/" . $module . "/language/" . $global_config['site_lang'] . ".php" ) )
			{
				require_once NV_ROOTDIR . "/modules/" . $module . "/language/" . $global_config['site_lang'] . ".php";
			}
			$lang_module = $lang_module + $lang_temp;
			unset( $lang_temp );

			$my_head .= '<link rel="StyleSheet" href="' . NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/css/faqs.css" type="text/css" />';
		}

		if( file_exists( NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module . "/block_question.tpl" ) )
		{
			$block_theme = $module_info['template'];
		}
		else
		{
			$block_theme = "default";
		}

		$xtpl = new XTemplate( "block_question.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $module );
		$xtpl->assign( 'LINK', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module . "&amp;op=question" );
		$xtpl->assign( 'LANG', $lang_module );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
		$xtpl->assign( 'themes', $block_theme );
        
        if( $block_config['category'] > 0 ){
            $sql = "SELECT qid, title, alias, cus_name, number FROM " . NV_PREFIXLANG . "_" . $module . "_question where status != 0 AND catid=" . $block_config['category'] . " ORDER BY qid desc limit " . $block_config['numrow'];
        }else{
            $sql = "SELECT qid, title, alias, cus_name, number FROM " . NV_PREFIXLANG . "_" . $module . "_question where status != 0 ORDER BY qid desc limit " . $block_config['numrow'];
        }
		
		$resutl = $db->query( $sql );
		while( $rows = $resutl->fetch( ) )
		{
			$rows['title0'] = nv_clean60( $rows['title'], $block_config['title_length'] );
			$rows['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module . "&amp;op=detail/" . $rows['alias'];
			$xtpl->assign( 'ROW', $rows );

			if( $block_config['is_statics'] ) $xtpl->parse( 'main.que.statics' );

			$xtpl->parse( 'main.que' );
		}

		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );

	}

}

if( defined( 'NV_SYSTEM' ) )
{
	$content = nv_faqs_book( $block_config );
}