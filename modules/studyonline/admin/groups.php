<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if(!defined('NV_IS_FILE_ADMIN'))
{
	die('Stop!!!');
}
if($nv_Request->isset_request('get_alias_title','post'))
{
	$alias = $nv_Request->get_title('get_alias_title','post','');
	$alias = change_alias($alias);
	die($alias);
}
if($nv_Request->isset_request('delete','post'))
{
	$bid = $nv_Request->get_int('bid','post',0);
	if(empty($bid))
		die("NO");

	$sql = "DELETE FROM " . NV_PREFIXLANG . '_' . $module_data . "_block_cat WHERE bid=" . $bid;
	$db->query($sql);
	$query = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_block WHERE bid=" . $bid;
	$db->query($query);
	$nv_Cache->delMod($module_name);
	die("OK");
}
if($nv_Request->isset_request('chang_block_cat','post'))
{

	$bid = $nv_Request->get_int('bid','post',0);
	$mod = $nv_Request->get_string('mod','post','');
	$new_vid = $nv_Request->get_int('new_vid','post',0);

	if(empty($bid))
	{
		die('NO_' . $bid);
	}
	$content = 'NO_' . $bid;

	if($mod == 'weight' and $new_vid > 0)
	{
		$sql = 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block_cat WHERE bid=' . $bid;
		$numrows = $db->query($sql)->fetchColumn();
		if($numrows != 1)
		{
			die('NO_' . $bid);
		}

		$sql = 'SELECT bid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block_cat WHERE bid!=' . $bid . ' ORDER BY weight ASC';
		$result = $db->query($sql);

		$weight = 0;
		while($row = $result->fetch())
		{
			++$weight;
			if($weight == $new_vid)
			{
				++$weight;
			}
			$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_block_cat SET weight=' . $weight . ' WHERE bid=' . $row['bid'];
			$db->query($sql);
		}

		$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_block_cat SET weight=' . $new_vid . ' WHERE bid=' . $bid;
		$db->query($sql);

		$content = 'OK_' . $bid;
	}
	elseif($mod == 'adddefault' and $bid > 0)
	{
		$new_vid = (intval($new_vid) == 1) ? 1 : 0;
		$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_block_cat SET adddefault=' . $new_vid . ' WHERE bid=' . $bid;
		$db->query($sql);
		$content = 'OK_' . $bid;
	}
	elseif($mod == 'numlinks' and $new_vid >= 0 and $new_vid <= 50)
	{
		$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_block_cat SET numbers=' . $new_vid . ' WHERE bid=' . $bid;
		$db->query($sql);
		$content = 'OK_' . $bid;
	}

	$nv_Cache->delMod($module_name);

	include NV_ROOTDIR . '/includes/header.php';
	echo $content;
	include NV_ROOTDIR . '/includes/footer.php';
	exit;
}
if($nv_Request->isset_request('list_block_cat','get'))
{
	if(!defined('NV_IS_AJAX'))
	{
		die('Wrong URL');
	}

	$contents = nv_show_block_cat_list();

	include NV_ROOTDIR . '/includes/header.php';
	echo $contents;
	include NV_ROOTDIR . '/includes/footer.php';
	exit;
}

$page_title = $lang_module['groups_khoahoc'];

$error = '';
$savecat = 0;
list($bid,$title,$alias,$description,$image,$keywords) = array(
	0,
	'',
	'',
	'',
	'',
	'');
$currentpath = NV_UPLOADS_DIR . '/' . $module_upload;

$savecat = $nv_Request->get_int('savecat','post',0);
if(!empty($savecat))
{
	$bid = $nv_Request->get_int('bid','post',0);
	$title = $nv_Request->get_title('title','post','',1);
	$keywords = $nv_Request->get_title('keywords','post','',1);
	$alias = $nv_Request->get_title('alias','post','');
	$description = $nv_Request->get_string('description','post','');
	$description = nv_nl2br(nv_htmlspecialchars(strip_tags($description)),'<br />');

	$image = $nv_Request->get_string('image','post','');
	if(!empty($image))
	{
		if(nv_is_file($image,NV_UPLOADS_DIR . '/' . $module_upload) === true)
		{
			$lu = strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/');
			$image = substr($image,$lu);
		}
		else
		{
			$image = '';
		}
	}

	// Ki?m tra trùng
	$sql = "SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_block_cat WHERE (title=:title OR alias=:alias)" . ($bid ? ' AND bid!=' . $bid : '');
	$sth = $db->prepare($sql);
	$sth->bindParam(':title',$title,PDO::PARAM_STR);
	$sth->bindParam(':alias',$alias,PDO::PARAM_STR);
	$sth->execute();
	$is_exists = $sth->fetchColumn();

	if(empty($title))
	{
		$error = $lang_module['error_name'];
	}
	elseif($is_exists)
	{
		$error = $lang_module['errorexists'];
	}
	elseif($bid == 0)
	{
		$weight = $db->query("SELECT max(weight) FROM " . NV_PREFIXLANG . "_" . $module_data . "_block_cat")->fetchColumn();
		$weight = intval($weight) + 1;

		$sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_block_cat (adddefault, numbers, title, alias, description, image, weight, keywords, add_time, edit_time) VALUES (0, 4, :title , :alias, :description, :image, :weight, :keywords, " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ")";
		$data_insert = array();
		$data_insert['title'] = $title;
		$data_insert['alias'] = $alias;
		$data_insert['description'] = $description;
		$data_insert['image'] = $image;
		$data_insert['weight'] = $weight;
		$data_insert['keywords'] = $keywords;

		if($db->insert_id($sql,'bid',$data_insert))
		{
			nv_insert_logs(NV_LANG_DATA,$module_name,'log_add_blockcat'," ",$admin_info['userid']);
			Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
			die();
		}
		else
		{
			$error = $lang_module['errorsave'];
		}
	}
	else
	{
		$stmt = $db->prepare("UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_block_cat SET title= :title, alias = :alias, description= :description, image= :image, keywords= :keywords, edit_time=" . NV_CURRENTTIME . " WHERE bid =" . $bid);
		$stmt->bindParam(':title',$title,PDO::PARAM_STR);
		$stmt->bindParam(':alias',$alias,PDO::PARAM_STR);
		$stmt->bindParam(':description',$description,PDO::PARAM_STR);
		$stmt->bindParam(':image',$image,PDO::PARAM_STR);
		$stmt->bindParam(':keywords',$keywords,PDO::PARAM_STR);
		$stmt->execute();
		if($stmt->execute())
		{
			nv_insert_logs(NV_LANG_DATA,$module_name,'log_edit_blockcat',"blockid " . $bid,$admin_info['userid']);
			Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
			die();
		}
		else
		{
			$error = $lang_module['errorsave'];
		}
	}
}

$bid = $nv_Request->get_int('bid','get',0);
if($bid > 0)
{
	list($bid,$title,$alias,$description,$image,$keywords) = $db->query("SELECT bid, title, alias, description, image, keywords FROM " . NV_PREFIXLANG . "_" . $module_data . "_block_cat where bid=" . $bid)->fetch(3);
	$lang_module['add_block_cat'] = $lang_module['edit_block_cat'];
}

$lang_global['title_suggest_max'] = sprintf($lang_global['length_suggest_max'],65);
$lang_global['description_suggest_max'] = sprintf($lang_global['length_suggest_max'],160);

$xtpl = new XTemplate('groups.tpl',NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG',$lang_module);
$xtpl->assign('GLANG',$lang_global);
$xtpl->assign('NV_BASE_ADMINURL',NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE',NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME',$module_name);
$xtpl->assign('OP',$op);

$xtpl->assign('BLOCK_CAT_LIST',nv_show_block_cat_list());

$xtpl->assign('bid',$bid);
$xtpl->assign('title',$title);
$xtpl->assign('alias',$alias);
$xtpl->assign('keywords',$keywords);
$xtpl->assign('description',nv_htmlspecialchars(nv_br2nl($description)));

if(!empty($image) and file_exists(NV_UPLOADS_REAL_DIR . "/" . $module_upload . "/" . $image))
{
	$image = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_upload . "/" . $image;
	$currentpath = dirname($image);
}
$xtpl->assign('image',$image);
$xtpl->assign('UPLOAD_CURRENT',$currentpath);
$xtpl->assign('UPLOAD_PATH',NV_UPLOADS_DIR . '/' . $module_upload);

if(!empty($error))
{
	$xtpl->assign('ERROR',$error);
	$xtpl->parse('main.error');
}

if(empty($alias))
{
	$xtpl->parse('main.getalias');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
