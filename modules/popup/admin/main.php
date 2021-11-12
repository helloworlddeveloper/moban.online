<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2015 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 03 Mar 2015 14:49:18 GMT
 */

if (!defined('NV_IS_FILE_ADMIN'))
	die('Stop!!!');

if ($nv_Request->isset_request('delete_id', 'get') and $nv_Request->isset_request('delete_checkss', 'get'))
{
	$id = $nv_Request->get_int('delete_id', 'get');
	$delete_checkss = $nv_Request->get_string('delete_checkss', 'get');
	if ($id > 0 and $delete_checkss == md5($id . NV_CACHE_PREFIX . $client_info['session_id']))
	{
		if (isset($site_mods['mkt']))
		{
			//xoa ket noi 2 bang trc
			$db->query('DELETE FROM ' . NV_TABLE_MKT . '_connect WHERE id = ' . $db->quote($id) . ' AND tablefrom = ' . $db->quote($module_data . '_data'));
		}
		$db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_data  WHERE dataid = ' . $db->quote($id));
		Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
		die();
	}
}

$q = $nv_Request->get_title('q', 'post,get');
$status = $nv_Request->get_title('status', 'post,get', '');
$modulename = $nv_Request->get_title('modulename', 'post,get', '');
// Fetch Limit
$show_view = false;
if (!$nv_Request->isset_request('id', 'post,get'))
{
	$show_view = true;
	$per_page = 30;
	$page = $nv_Request->get_int('page', 'post,get', 1);
	$db->sqlreset()->select('COUNT(*)')->from('' . NV_PREFIXLANG . '_' . $module_data . '_data');

	if (!empty($q) && $status != '')
	{
		$db->where('(student_name LIKE :q_student_name OR mobile LIKE :q_mobile) AND status =' . intval($status));
	} elseif (!empty($q) && $status == '')
	{
		$db->where('student_name LIKE :q_student_name OR mobile LIKE :q_mobile');
	} elseif (empty($q) && $status != '')
	{
		$db->where('status =' . intval($status));
	} elseif ($modulename != '' )
	{
		$db->where('modulename =' . $db->quote( $modulename ));
	}
	$sth = $db->prepare($db->sql());

	if (!empty($q))
	{
		$sth->bindValue(':q_student_name', '%' . $q . '%');
		$sth->bindValue(':q_mobile', '%' . $q . '%');
	}
	$sth->execute();
	$num_items = $sth->fetchColumn();

	$db->select('*')->order('add_time DESC')->limit($per_page)->offset(($page - 1) * $per_page);
	$sth = $db->prepare($db->sql());

	if (!empty($q))
	{
		$sth->bindValue(':q_student_name', '%' . $q . '%');
		$sth->bindValue(':q_mobile', '%' . $q . '%');
	}
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
$xtpl->assign('Q', $q);

$array_select_status = array();

$array_select_status[0] = $lang_module['status_0'];
$array_select_status[1] = $lang_module['status_1'];
foreach ($array_select_status as $key => $title)
{
	$xtpl->assign('OPTION', array(
		'key' => $key,
		'title' => $title,
		'selected' => ($status != '' && $key == $status) ? ' selected="selected"' : ''));
	$xtpl->parse('main.select_status');
}

foreach ($site_mods as $mod_name => $mod_info )
{
	if (file_exists(NV_ROOTDIR . '/modules/' . $mod_info['module_file'] . '/popup_connect.php'))
	{
		$sl = ($modulename == $mod_name ) ? ' selected="selected"' : '';
		$xtpl->assign('OPTION', array('key' => $mod_name, 'title' => $mod_info['admin_title'], 'sl' => $sl ));
		$xtpl->parse('main.modulename');
	}
}

if ($show_view)
{
	$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
	if (!empty($q))
	{
		$base_url .= '&q=' . $q;
	}
	if (!empty($status))
	{
		$base_url .= '&status=' . $status;
	}
	$xtpl->assign('NV_GENERATE_PAGE', nv_generate_page($base_url, $num_items, $per_page, $page));

	$number = 0;
	while ($view = $sth->fetch())
	{
		$view['number'] = ++$number;
		$view['birthday'] = ($view['birthday']) ? date('d/m/Y', $view['birthday']) : '';
		$view['add_time'] = date('d/m/Y H:i', $view['add_time']);
		$view['provinceid'] = isset($list_province[$view['provinceid']]) ? $list_province[$view['provinceid']]['title'] : 'N/A';
		$view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=rows&amp;dataid=' . $view['dataid'];
		$view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_id=' . $view['dataid'] . '&amp;delete_checkss=' . md5($view['dataid'] . NV_CACHE_PREFIX . $client_info['session_id']);
		$view['status'] = $lang_module['status_' . $view['status']];
		$xtpl->assign('VIEW', $view);
		$xtpl->parse('main.loop');
	}
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['main'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
