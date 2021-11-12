<?php

/**
 * @Project NUKEVIET 4.x
 * @Author EDUSGROUP.JSC (thangbv@edus.vn)
 * @Copyright (C) 2014 EDUSGROUP.JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 24 Dec 2014 08:22:22 GMT
 */

if (!defined('NV_IS_FILE_ADMIN'))
	die('Stop!!!');

if ($nv_Request->isset_request('get_alias_title', 'post'))
{
	$alias = $nv_Request->get_title('get_alias_title', 'post', '');
	$alias = change_alias($alias);
	die($alias);
}

if ($nv_Request->isset_request('ajax_action', 'post'))
{
	$id = $nv_Request->get_int('id', 'post', 0);
	$new_vid = $nv_Request->get_int('new_vid', 'post', 0);
	$content = 'NO_' . $id;
	if ($new_vid > 0)
	{
		$sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_teacher WHERE id!=' . $id . ' ORDER BY weight ASC';
		$result = $db->query($sql);
		$weight = 0;
		while ($row = $result->fetch())
		{
			++$weight;
			if ($weight == $new_vid)
				++$weight;
			$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_teacher SET weight=' . $weight . ' WHERE id=' . $row['id'];
			$db->query($sql);
		}
		$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_teacher SET weight=' . $new_vid . ' WHERE id=' . $id;
		$db->query($sql);
		$content = 'OK_' . $id;
	}
	$nv_Cache->delMod($module_name);
	include NV_ROOTDIR . '/includes/header.php';
	echo $content;
	include NV_ROOTDIR . '/includes/footer.php';
	exit();
}
if ($nv_Request->isset_request('id', 'get') and $nv_Request->isset_request('delete_checkss', 'get'))
{
	$id = $nv_Request->get_int('id', 'get');
	$delete_checkss = $nv_Request->get_string('delete_checkss', 'get');
	if ($id > 0 and $delete_checkss == md5($id . NV_CACHE_PREFIX . $client_info['session_id']))
	{
		$weight = 0;
		$sql = 'SELECT weight FROM ' . NV_PREFIXLANG . '_' . $module_data . '_teacher WHERE id =' . $db->quote($id);
		$result = $db->query($sql);
		list($weight) = $result->fetch(3);

		$db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_teacher  WHERE id = ' . $db->quote($id));
		if ($weight > 0)
		{
			$sql = 'SELECT id, weight FROM ' . NV_PREFIXLANG . '_' . $module_data . '_teacher WHERE weight >' . $weight;
			$result = $db->query($sql);
			while (list($id, $weight) = $result->fetch(3))
			{
				$weight--;
				$db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_teacher SET weight=' . $weight . ' WHERE id=' . intval($id));
			}
		}
		$nv_Cache->delMod($module_name);
		Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
		die();
	}
}
if (empty($array_subject))
{
	Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=subject');
	die();
}

$row = array();
$error = array();
$row['id'] = $nv_Request->get_int('id', 'post,get', 0);
if ($nv_Request->isset_request('submit', 'post'))
{
	$row['title'] = $nv_Request->get_title('title', 'post', '');
	$row['avatar'] = $nv_Request->get_title('avatar', 'post', '');
	$row['facebooklink'] = $nv_Request->get_title('facebooklink', 'post', '');
	$row['mobile'] = $nv_Request->get_title('mobile', 'post', '');
	$row['address'] = $nv_Request->get_title('address', 'post', '');
	$row['email'] = $nv_Request->get_title('email', 'post', '');
	$row['infotext'] = $nv_Request->get_editor('infotext', '', NV_ALLOWED_HTML_TAGS);

	$row['alias'] = $nv_Request->get_title('alias', 'post', '');
	$row['alias'] = (empty($row['alias'])) ? change_alias($row['title']) : change_alias($row['alias']);
	$row['subjectlist'] = $nv_Request->get_array('subjectlist', 'post', array());
	$row['subjectlist'] = implode(',', $row['subjectlist']);
	$row['description'] = $nv_Request->get_string('description', 'post', '');
	$row['status'] = $nv_Request->get_int('status', 'post', 0);

	if (is_file(NV_DOCUMENT_ROOT . $row['avatar']))
	{
		$row['avatar'] = substr($row['avatar'], strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/'));
	} else
	{
		$row['avatar'] = '';
	}
	if (empty($row['title']))
	{
		$error[] = $lang_module['error_required_teacher_name'];
	}
	if (empty($error))
	{
		try
		{
			$row['updatetime'] = NV_CURRENTTIME;
			if (empty($row['id']))
			{
				$stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_teacher (title, alias, description, infotext, avatar, subjectlist, facebooklink, address, mobile, email, weight, numview, numfollow, status, updatetime) VALUES (:title, :alias, :description, :infotext, :avatar, :subjectlist, :facebooklink, :address, :mobile, :email, :weight, 0, 0, :status, :updatetime)');

				$weight = $db->query('SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_teacher')->fetchColumn();
				$weight = intval($weight) + 1;
				$stmt->bindParam(':weight', $weight, PDO::PARAM_INT);

			} else
			{
				$stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_teacher SET title=:title, alias=:alias, description=:description, infotext=:infotext, avatar=:avatar, subjectlist=:subjectlist, facebooklink=:facebooklink, address=:address, mobile=:mobile, email=:email, status=:status, updatetime=:updatetime WHERE id=' . $row['id']);
			}

			$stmt->bindParam(':title', $row['title'], PDO::PARAM_STR);
			$stmt->bindParam(':alias', $row['alias'], PDO::PARAM_STR);
			$stmt->bindParam(':description', $row['description'], PDO::PARAM_STR, strlen($row['description']));
			$stmt->bindParam(':infotext', $row['infotext'], PDO::PARAM_STR, strlen($row['infotext']));
			$stmt->bindParam(':avatar', $row['avatar'], PDO::PARAM_STR);
			$stmt->bindParam(':subjectlist', $row['subjectlist'], PDO::PARAM_STR);
			$stmt->bindParam(':facebooklink', $row['facebooklink'], PDO::PARAM_STR);
			$stmt->bindParam(':address', $row['address'], PDO::PARAM_STR);
			$stmt->bindParam(':mobile', $row['mobile'], PDO::PARAM_STR);
			$stmt->bindParam(':email', $row['email'], PDO::PARAM_STR);
			$stmt->bindParam(':status', $row['status'], PDO::PARAM_INT);
			$stmt->bindParam(':updatetime', $row['updatetime'], PDO::PARAM_INT);

			$exc = $stmt->execute();
			if ($exc)
			{
				$nv_Cache->delMod($module_name);
				Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
				die();
			}
		}
		catch (PDOException $e)
		{
			trigger_error($e->getMessage());
			die($e->getMessage()); //Remove this line after checks finished
		}
	}
} elseif ($row['id'] > 0)
{
	$row = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_teacher WHERE id=' . $row['id'])->fetch();
	if (empty($row))
	{
		Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
		die();
	}
} else
{
	$row['id'] = 0;
	$row['subjectlist'] = '';
	$row['infotext'] = '';
	$row['avatar'] = '';
	$row['status'] = 1;
}
if (!empty($row['avatar']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $row['avatar']))
{
	$row['avatar'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $row['avatar'];
}
$row['subjectlist'] = explode(',', $row['subjectlist']);

if (defined('NV_EDITOR'))
	require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
$row['infotext'] = htmlspecialchars(nv_editor_br2nl($row['infotext']));
if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor'))
{
	$row['infotext'] = nv_aleditor('infotext', '100%', '300px', $row['infotext']);
} else
{
	$row['infotext'] = '<textarea style="width:100%;height:300px" name="infotext">' . $row['infotext'] . '</textarea>';
}

// Fetch Limit
$show_view = false;
if (!$nv_Request->isset_request('id', 'post,get') && !$nv_Request->isset_request('add', 'post,get'))
{
	$show_view = true;
	$page = $nv_Request->get_int('page', 'post,get', 1);
	$db->sqlreset()->select('COUNT(*)')->from('' . NV_PREFIXLANG . '_' . $module_data . '_teacher');
	$sth = $db->prepare($db->sql());
	$sth->execute();
	$num_items = $sth->fetchColumn();

	$db->select('*')->order('weight ASC');
	$sth = $db->prepare($db->sql());
	$sth->execute();
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('ROW', $row);
$xtpl->assign('addnew_teacher', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;add=1' );
if ($show_view)
{
	while ($view = $sth->fetch())
	{
		for ($i = 1; $i <= $num_items; ++$i)
		{
			$xtpl->assign('WEIGHT', array(
				'key' => $i,
				'title' => $i,
				'selected' => ($i == $view['weight']) ? ' selected="selected"' : ''));
			$xtpl->parse('main.view.loop.weight_loop');
		}
		if (!empty($view['avatar']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $view['avatar']))
		{
			$xtpl->assign('avatar', NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $view['avatar']);
			$xtpl->parse('main.view.loop.avatar');
		}
		$view['subjectlist'] = explode(',', $view['subjectlist']);
		$view['subject_title'] = array();
		foreach ($view['subjectlist'] as $subjectid)
		{
			$view['subject_title'][] = isset($array_subject[$subjectid]) ? $array_subject[$subjectid]['title'] : '';
		}
		$view['subject_title'] = implode(', ', $view['subject_title']);
		$view['status'] = $lang_module['status_' . $view['status']];
		$view['updatetime'] = date('d/m/Y', $view['updatetime']);
		$view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $view['id'];
		$view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $view['id'] . '&amp;delete_checkss=' . md5($view['id'] . NV_CACHE_PREFIX . $client_info['session_id']);
		$xtpl->assign('VIEW', $view);
		$xtpl->parse('main.view.loop');
	}
	$xtpl->parse('main.view');
} 
else
{
	if (!empty($error))
	{
		$xtpl->assign('ERROR', implode('<br />', $error));
		$xtpl->parse('main.addnew.error');
	}

	foreach ($array_subject as $subject)
	{
		$subject['ck'] = (in_array($subject['id'], $row['subjectlist'])) ? ' checked=checked' : '';
		$xtpl->assign('SUBJECT', $subject);
		$xtpl->parse('main.addnew.list_subject');
	}

	$array_select_status = array();
	$array_select_status[1] = $lang_module['status_1'];
	$array_select_status[0] = $lang_module['status_0'];
	foreach ($array_select_status as $key => $title)
	{
		$xtpl->assign('OPTION', array(
			'key' => $key,
			'title' => $title,
			'selected' => ($key == $row['status']) ? ' selected="selected"' : ''));
		$xtpl->parse('main.addnew.select_status');
	}
	if (empty($row['id']))
	{
		$xtpl->parse('main.addnew.auto_get_alias');
	}
	$xtpl->parse('main.addnew');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['teacher'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
