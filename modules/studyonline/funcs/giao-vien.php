<?php

/**
 * @Project NUKEVIET 4.x
 * @Author EDUSGROUP.JSC (thangbv@edus.vn)
 * @Copyright (C) 2015 EDUSGROUP.JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 03 Mar 2015 14:33:58 GMT
 */

if(!defined('NV_IS_MOD_STUDYONLINE'))
	die('Stop!!!');

$key_words = $module_info['keywords'];
$teacherid = 0;

$alias_giaovien_url = isset($array_op[1]) ? $array_op[1] : '';
foreach($array_teacher as $teacher)
{
	if($alias_giaovien_url == $teacher['alias'])
	{
		$array_mod_title[] = array(
			'classid' => $teacher['id'],
			'title' => $teacher['title'],
			'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=giao-vien/' . $teacher['alias']);
		krsort($array_mod_title,SORT_NUMERIC);

		$page_title = $lang_module['teacher_title_info'] . ' ' . $teacher['title'];
		$teacherid = $teacher['id'];
	}
}
$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $array_op[0] . '/' . $array_op[1];
$base_url_rewrite = nv_url_rewrite(str_replace('&amp;','&',$base_url),true);
$page_url_rewrite = ($page > 1) ? nv_url_rewrite($base_url . '/page-' . $page,true) : $base_url_rewrite;
$request_uri = $_SERVER['REQUEST_URI'];

if(!($teacherid == 0 or $home or $request_uri == $base_url_rewrite or $request_uri == $page_url_rewrite or NV_MAIN_DOMAIN . $request_uri == $base_url_rewrite or NV_MAIN_DOMAIN . $request_uri == $page_url_rewrite))
{
	$redirect = '<meta http-equiv="Refresh" content="3;URL=' . $base_url_rewrite . '" />';
	nv_info_die($lang_global['error_404_title'],$lang_global['error_404_title'],$lang_global['error_404_content'] . $redirect,404);
}
$time_set = $nv_Request->get_int($module_data . '_' . $op . '_' . $teacherid,'session');
if(empty($time_set))
{
	$nv_Request->set_Session($module_data . '_' . $op . '_' . $teacherid,NV_CURRENTTIME);
	$query = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_teacher SET numview=numview+1 WHERE id=' . $teacherid;
	$db->query($query);
}
if(!defined('NV_IS_MODADMIN') and $page < 5)
{

	$cache_file = NV_LANG_DATA . '_' . $module_info['template'] . '_' . $op . '_' . $teacherid . '_' . $page . '_' . NV_CACHE_PREFIX . '.cache';
	if(($cache = $nv_Cache->getItem($module_name,$cache_file,3600)) != false)
	{
		$contents = $cache;
	}
}

if(empty($contents))
{
	$db_slave->select('id, classid, subjectid, title, alias, image,hometext,addtime,numview, numlike, price, teacherid, numbuy, listtag')->from(NV_PREFIXLANG . '_' . $module_data . '_khoahoc')->where('status= 1 AND (teacherid LIKE "%,' . $teacherid . ',%" OR teacherid LIKE "%,' . $teacherid . '%" OR teacherid LIKE "%' . $teacherid . '%," OR teacherid =' . $teacherid . ' )')->order('addtime DESC');
    
	$array_data = array();
	$result = $db_slave->query($db_slave->sql());
	while($item = $result->fetch())
	{
		$item['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $array_class[$item['classid']]['alias'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
		if(!empty($item['image']))
		{
			$item['thumb'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $site_mods[$module_name]['module_upload'] . '/' . $item['image'];
		}
		elseif(!empty($show_no_image))
		{
			$item['thumb'] = NV_BASE_SITEURL . $show_no_image;
		}
		else
		{
			$item['thumb'] = '';
		}
		if($array_subject[$item['subjectid']]['icon'] != '')
		{
			$array_subject[$item['subjectid']]['subject_icon'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $site_mods[$module_name]['module_upload'] . '/' . $array_subject[$item['subjectid']]['icon'];

		}
		$item['teacherid'] = explode(',',$item['teacherid']);
		$item['teacher_info'] = array();
		foreach($item['teacherid'] as $teacherid_i)
		{
			if(isset($array_teacher[$teacherid_i]))
			{
				$item['teacher_info'][$teacherid_i] = $array_teacher[$teacherid_i];
				$item['teacher_info'][$teacherid_i]['teacher_link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=giao-vien/' . $array_teacher[$teacherid_i]['alias'];
				unset($item['teacherid']);
			}
		}
		$item['subject_name'] = $array_subject[$item['subjectid']]['title'];
		$item['subject_link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $array_class[$item['classid']]['alias'] . '/' . $array_subject[$item['subjectid']]['alias'];
		$item['title_clean'] = nv_clean60($item['title'],45);
		if($item['price'] == 0)
        {
            $item['price'] = $lang_module['free'];
        }
        else
        {
            $item['price'] = number_format($item['price'], 0, ',', '.');
        }
		$item['addtime'] = date('d/m',$item['addtime']);
		$array_data[] = $item;
	}

	// comment
	if(isset($site_mods['comment']) and isset($module_config[$module_name]['activecomm']))
	{
		define('NV_COMM_ID',$teacherid); //ID bài viet
		define('NV_COMM_AREA',$module_info['funcs'][$op]['func_id']);
		//check allow comemnt
		$allowed = $module_config[$module_name]['allowed_comm'];
		if($allowed == '-1')
		{
			$allowed = 4;
		}
		require_once NV_ROOTDIR . '/modules/comment/comment.php';
		$area = (defined('NV_COMM_AREA')) ? NV_COMM_AREA : 0;
		$checkss = md5($module_name . '-' . $area . '-' . NV_COMM_ID . '-' . $allowed . '-' . NV_CACHE_PREFIX);

		$content_comment = nv_comment_module($module_name,$checkss,$area,NV_COMM_ID,$allowed,1);
	}
	else
	{
		$content_comment = '';
	}
	$contents = nv_theme_teacher_studyonline($array_data,$array_teacher[$teacherid],$content_comment);
}
if($page > 1)
{
	$page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
