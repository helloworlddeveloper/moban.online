<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/9/2010 23:25
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( ! nv_function_exists( 'nv_faqs_category' ) )
{
	function nv_block_config_faqs_category( $module, $data_block, $lang_block )
	{
		$html = '<tr>';
		$html .= '	<td>' . $lang_block['titlelength'] . '</td>';
		$html .= '	<td><input type="text" name="config_titlelength" class="form-control w200" size="5" value="' . $data_block['titlelength'] . '"/><span class="help-block">' . $lang_block['titlenote'] . '</span></td>';
		$html .= '</tr>';
        $html .= '<tr>';
		$html .= '	<td>' . $lang_block['numrow'] . '</td>';
		$html .= '	<td><input type="text" name="config_numrow" class="form-control w200" size="5" value="' . $data_block['numrow'] . '"/></td>';
		$html .= '</tr>';
		return $html;
	}

	function nv_block_config_faqs_category_submit( $module, $lang_block )
	{
		global $nv_Request;
		$return = array();
		$return['error'] = array();
		$return['config'] = array();
		$return['config']['titlelength'] = $nv_Request->get_int( 'config_titlelength', 'post', 0 );
        $return['config']['numrow'] = $nv_Request->get_int( 'config_numrow', 'post', 0 );
		return $return;
	}

	function nv_faqs_category( $block_config )
	{
		global $site_mods, $db, $module_info;

		$module = $block_config['module'];
		$mod_data = $site_mods[$module]['module_data'];
		$mod_file = $site_mods[$module]['module_file'];

		if( file_exists( NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $mod_file . '/block_category_question.tpl' ) )
		{
			$block_theme = $module_info['template'];
		}
		else
		{
			$block_theme = 'default';
		}
		$xtpl = new XTemplate( 'block_category_question.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $mod_file );

		$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $mod_data . "_cat ORDER BY weight ASC";
		$result = $db->query( $sql );
		$listcats = array();
		while( $row = $result->fetch() )
		{
			$listcats[$row['id']] = array( //
				'id' => $row['id'], //
				'title' => $row['title'], //
				'alias' => $row['alias'], //
				'weight' => ( int )$row['weight'], //
				'data_question' => array() );

			$sql = "SELECT qid, title, alias, cus_name, number FROM " . NV_PREFIXLANG . "_" . $module . "_question where status != 0 AND catid=" . $row['id'] . " ORDER BY addtime desc LIMIT " . $block_config['numrow'];
			$resutl_s = $db->query( $sql );
			while( $rows = $resutl_s->fetch() )
			{
				$rows['title0'] = nv_clean60( $rows['title'], $block_config['title_length'] );
				$rows['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module . "&amp;op=detail/" . $rows['alias'];
				$listcats[$row['id']]['data_question'][] = $rows;
			}
		}
		if( ! empty( $listcats ) )
		{
			foreach( $listcats as $cat )
			{
				$cat['title0'] = ! empty( $block_config['titlelength'] ) ? nv_clean60( $cat['title'], $block_config['titlelength'] ) : $cat['title'];
				$cat['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module . "/" . $cat['alias'];
				$xtpl->assign( 'LISTCATS', $cat );
				if( $cat['data_question'] )
				{
					foreach( $cat['data_question'] as $data_question )
					{
						$xtpl->assign( 'QUESTION', $data_question );
						$xtpl->parse( 'main.catloop.question.loop' );
					}
					$xtpl->parse( 'main.catloop.question' );
				}
				$xtpl->parse( 'main.catloop' );
			}
		}

		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}
}

if( defined( 'NV_SYSTEM' ) )
{
	$module = $block_config['module'];
	$content = nv_faqs_category( $block_config );
}
