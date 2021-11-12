<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sat, 10 Dec 2011 06:46:54 GMT
 */

if(!defined('NV_MAINFILE'))
{
	die('Stop!!!');
}

if(!nv_function_exists('nv_block_review_by_user'))
{
	function nv_block_config_review_by_user($module,$data_block,$lang_block)
	{
		global $nv_Cache,$site_mods;

		$html_input = '';
		$html = '';
		$html .= '<tr>';
		$html .= '<td>' . $lang_block['title_length'] . '</td>';
		$html .= '<td><input type="text" class="form-control w200" name="config_title_length" size="5" value="' . $data_block['title_length'] . '"/></td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td>' . $lang_block['numrow'] . '</td>';
		$html .= '<td><input type="text" class="form-control w200" name="config_numrow" size="5" value="' . $data_block['numrow'] . '"/></td>';
		$html .= '</tr>';
		return $html;
	}

	function nv_block_config_review_by_user_submit($module,$lang_block)
	{
		global $nv_Request;
		$return = array();
		$return['error'] = array();
		$return['config'] = array();
		$return['config']['numrow'] = $nv_Request->get_int('config_numrow','post',0);
		$return['config']['title_length'] = $nv_Request->get_int('config_title_length','post',20);
		return $return;
	}

	function nv_block_review_by_user($block_config)
	{
		global $module_info,$site_mods,$module_config,$global_config,$nv_Cache,$db,$module_name,$my_head;
		$module = $block_config['module'];

		$db->sqlreset()->select('t1.content, t1.addtime, t2.userid, t2.photo, t2.first_name, t2.last_name')->from(NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_review t1')->join('INNER JOIN ' . NV_USERS_GLOBALTABLE . ' t2 ON t1.userid = t2.userid')->where('t1.status=1')->order('t1.addtime DESC')->limit($block_config['numrow']);
		$list = $nv_Cache->db($db->sql(),'id',$module);

		if(!empty($list))
		{
			if(file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $site_mods[$module]['module_file'] . '/block_review.tpl'))
			{
				$block_theme = $global_config['module_theme'];
			}
			else
			{
				$block_theme = 'default';
			}
			$xtpl = new XTemplate('block_review.tpl',NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $site_mods[$module]['module_file']);
			$xtpl->assign('NV_BASE_SITEURL',NV_BASE_SITEURL);
			$xtpl->assign('TEMPLATE',$block_theme);

			foreach($list as $l)
			{
				if(!empty($l['photo']) and file_exists(NV_ROOTDIR . '/' . $l['photo']))
				{
					$l['photo'] =  NV_BASE_SITEURL . $l['photo'];
				}
				else
				{
					$l['photo'] =  NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/users/no_avatar.png';
				}
                $l['content'] = nv_clean60($l['content'],$block_config['title_length']);
				$l['addtime'] = date('d/m/Y H:i',$l['addtime']);
				$xtpl->assign('ROW',$l);
				$xtpl->parse('main.contentloop');
                $xtpl->parse('main.infoloop');
			}
			$xtpl->parse('main');
			return $xtpl->text('main');
		}
	}
}
if(defined('NV_SYSTEM'))
{
	global $site_mods;
	$module = $block_config['module'];
	if(isset($site_mods[$module]))
	{
		$content = nv_block_review_by_user($block_config);
	}
}
