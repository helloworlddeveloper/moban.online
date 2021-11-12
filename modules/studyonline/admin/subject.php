<?php

/**
 * @Project NUKEVIET 4.x
 * @Author EDUSGROUP.JSC (thangbv@edus.vn)
 * @Copyright (C) 2014 EDUSGROUP.JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 24 Dec 2014 08:50:19 GMT
 */

if (!defined('NV_IS_FILE_ADMIN'))
	die('Stop!!!');

if ($nv_Request->isset_request('get_alias_title', 'post'))
{
	$alias = $nv_Request->get_title('get_alias_title', 'post', '');
	$alias = strtolower( change_alias($alias) );
	die($alias);
}

if ($nv_Request->isset_request('ajax_action', 'post'))
{
	$id = $nv_Request->get_int('id', 'post', 0);
	$new_vid = $nv_Request->get_int('new_vid', 'post', 0);
	$content = 'NO_' . $id;
	if ($new_vid > 0)
	{
		$sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_subject WHERE id!=' . $id . ' ORDER BY weight ASC';
		$result = $db->query($sql);
		$weight = 0;
		while ($row = $result->fetch())
		{
			++$weight;
			if ($weight == $new_vid)
				++$weight;
			$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_subject SET weight=' . $weight . ' WHERE id=' . $row['id'];
			$db->query($sql);
		}
		$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_subject SET weight=' . $new_vid . ' WHERE id=' . $id;
		$db->query($sql);
		$content = 'OK_' . $id;
	}
	$nv_Cache->delMod($module_name);
	include NV_ROOTDIR . '/includes/header.php';
	echo $content;
	include NV_ROOTDIR . '/includes/footer.php';
	exit();
}
if ($nv_Request->isset_request('delete_id', 'get') and $nv_Request->isset_request('delete_checkss', 'get'))
{
	$id = $nv_Request->get_int('delete_id', 'get');
	$delete_checkss = $nv_Request->get_string('delete_checkss', 'get');
	if ($id > 0 and $delete_checkss == md5($id . NV_CACHE_PREFIX . $client_info['session_id']))
	{
		$weight = 0;
		$sql = 'SELECT weight FROM ' . NV_PREFIXLANG . '_' . $module_data . '_subject WHERE id =' . $db->quote($id);
		$result = $db->query($sql);
		list($weight) = $result->fetch(3);

		$db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_subject  WHERE id = ' . $db->quote($id));
		if ($weight > 0)
		{
			$sql = 'SELECT id, weight FROM ' . NV_PREFIXLANG . '_' . $module_data . '_subject WHERE weight >' . $weight;
			$result = $db->query($sql);
			while (list($id, $weight) = $result->fetch(3))
			{
				$weight--;
				$db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_subject SET weight=' . $weight . ' WHERE id=' . intval($id));
			}
		}
		$nv_Cache->delMod($module_name);
		Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
		die();
	}
}

$row = array();
$error = array();
$row['id'] = $nv_Request->get_int('id', 'post,get', 0);
if ($nv_Request->isset_request('submit', 'post'))
{
	$row['title'] = $nv_Request->get_title('title', 'post', '');
	$row['alias'] = $nv_Request->get_title('alias', 'post', '');
	$row['alias'] = (empty($row['alias'])) ? change_alias($row['title']) : change_alias($row['alias']);
	$row['status'] = $nv_Request->get_int('status', 'post', 0);
	$row['icon'] = $nv_Request->get_title('icon', 'post', '');
	$row['description'] = $nv_Request->get_string('description', 'post', '');
    $row['color'] = $nv_Request->get_title('color', 'post', '');
	if (empty($row['title']))
	{
		$error[] = $lang_module['error_required_subject_name'];
	}

	if (empty($error))
	{
		if (is_file(NV_DOCUMENT_ROOT . $row['icon']))
		{
			$row['icon'] = substr($row['icon'], strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/'));
		}
		else
		{
			$row['icon'] = '';
		}
		try
		{
			if (empty($row['id']))
			{
				$stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_subject (title, alias, description, color, icon, weight, status) VALUES (:title, :alias, :description, :color, :icon, :weight, :status)');

				$weight = $db->query('SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_subject')->fetchColumn();
				$weight = intval($weight) + 1;
				$stmt->bindParam(':weight', $weight, PDO::PARAM_INT);

			}
			else
			{
				$stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_subject SET title = :title, alias = :alias, description=:description, color=:color, icon=:icon, status = :status WHERE id=' . $row['id']);
			}
			$stmt->bindParam(':title', $row['title'], PDO::PARAM_STR);
			$stmt->bindParam(':alias', $row['alias'], PDO::PARAM_STR);
            $stmt->bindParam(':description', $row['description'], PDO::PARAM_STR);
            $stmt->bindParam(':color', $row['color'], PDO::PARAM_STR);
            $stmt->bindParam(':icon', $row['icon'], PDO::PARAM_STR);
			$stmt->bindParam(':status', $row['status'], PDO::PARAM_INT);

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
}
elseif ($row['id'] > 0)
{
	$row = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_subject WHERE id=' . $row['id'])->fetch();
	if (empty($row))
	{
		Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
		die();
	}
}
else
{
	$row['id'] = 0;
	$row['alias'] = $row['subject_name'] = $row['description'] = $row['icon'] = '';
	$row['status'] = 1;
}
if (!empty($row['icon']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $row['icon']))
{
	$row['icon'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $row['icon'];
}
// Fetch Limit
$show_view = false;
if (!$nv_Request->isset_request('id', 'post,get'))
{
	$show_view = true;
	$per_page = 25;
	$page = $nv_Request->get_int('page', 'post,get', 1);
	$db->sqlreset()->select('COUNT(*)')->from('' . NV_PREFIXLANG . '_' . $module_data . '_subject');
	$sth = $db->prepare($db->sql());
	$sth->execute();
	$num_items = $sth->fetchColumn();

	$db->select('*')->order('weight ASC')->limit($per_page)->offset(($page - 1) * $per_page);
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
$xtpl->assign('MODULE_FILE', $module_file);
$xtpl->assign('OP', $op);
$xtpl->assign('ROW', $row);
$xtpl->assign('upload_dir', NV_UPLOADS_DIR . '/' . $module_name);
$xtpl->assign('upload_current', NV_UPLOADS_DIR . '/' . $module_name . '/icon');

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
		$view['status'] = $lang_module['status_' . $view['status']];
		$view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $view['id'];
		$view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_id=' . $view['id'] . '&amp;delete_checkss=' . md5($view['id'] . NV_CACHE_PREFIX . $client_info['session_id']);
		$xtpl->assign('VIEW', $view);
		$xtpl->parse('main.view.loop');
	}
	$xtpl->parse('main.view');
}

if (!empty($error))
{
	$xtpl->assign('ERROR', implode('<br />', $error));
	$xtpl->parse('main.error');
}

$array_select_status = array();

$array_select_status[0] = $lang_module['status_0'];
$array_select_status[1] = $lang_module['status_1'];
foreach ($array_select_status as $key => $title)
{
	$xtpl->assign('OPTION', array(
		'key' => $key,
		'title' => $title,
		'selected' => ($key == $row['status']) ? ' selected="selected"' : ''));
	$xtpl->parse('main.select_status');
}
if (empty($row['id']))
{
	$xtpl->parse('main.auto_get_alias');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['subject'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
