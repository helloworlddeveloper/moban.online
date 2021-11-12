<?php

/**
 * @Project NUKEVIET 4.x
 * @Author EDUSGROUP.JSC (thangbv@edus.vn)
 * @Copyright (C) 2014 EDUSGROUP.JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 24 Dec 2014 08:22:22 GMT
 */

if(!defined('NV_IS_FILE_ADMIN'))
	die('Stop!!!');

if($nv_Request->isset_request('get_alias_title','post'))
{
	$alias = $nv_Request->get_title('get_alias_title','post','');
	$alias = change_alias($alias);
	die($alias);
}

if($nv_Request->isset_request('ajax_action','post'))
{
	$id = $nv_Request->get_int('id','post',0);
	$new_vid = $nv_Request->get_int('new_vid','post',0);
	$content = 'NO_' . $id;
	if($new_vid > 0)
	{
		$sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_voucher WHERE id!=' . $id . ' ORDER BY weight ASC';
		$result = $db->query($sql);
		$weight = 0;
		while($row = $result->fetch())
		{
			++$weight;
			if($weight == $new_vid)
				++$weight;
			$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_voucher SET weight=' . $weight . ' WHERE id=' . $row['id'];
			$db->query($sql);
		}
		$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_voucher SET weight=' . $new_vid . ' WHERE id=' . $id;
		$db->query($sql);
		$content = 'OK_' . $id;
	}
	$nv_Cache->delMod($module_name);
	include NV_ROOTDIR . '/includes/header.php';
	echo $content;
	include NV_ROOTDIR . '/includes/footer.php';
	exit();
}
if($nv_Request->isset_request('id','get') and $nv_Request->isset_request('delete_checkss','get'))
{
	$id = $nv_Request->get_int('id','get');
	$delete_checkss = $nv_Request->get_string('delete_checkss','get');
	if($id > 0 and $delete_checkss == md5($id . NV_CACHE_PREFIX . $client_info['session_id']))
	{
		$db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_voucher  WHERE id = ' . $db->quote($id));
		$db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_vouchercode WHERE idvoucher = ' . $db->quote($id));
		$nv_Cache->delMod($module_name);
		Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
		die();
	}
}

$row = array();
$error = array();
$row['id'] = $nv_Request->get_int('id','post,get',0);
if($nv_Request->isset_request('submit','post'))
{
	$row['title'] = $nv_Request->get_title('title','post','');
	$row['allowfor'] = $nv_Request->get_array('khoahocid','post',array());
	$row['allowfor'] = implode(',',$row['allowfor']);
	$row['totalvoucher'] = $nv_Request->get_int('totalvoucher','post',0);
	$row['timeallow_from'] = $nv_Request->get_title('timeallow_from','post','');
	$row['timeallow_to'] = $nv_Request->get_title('timeallow_to','post','');
	$row['status'] = $nv_Request->get_int('status','post',0);

	if(preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/',$row['timeallow_from'],$m))
	{
		$row['timeallow_from'] = mktime(0,0,0,$m[2],$m[1],$m[3]);
	}
	else
	{
		$row['timeallow_from'] = 0;
	}
	if(preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/',$row['timeallow_to'],$m))
	{
		$row['timeallow_to'] = mktime(0,0,0,$m[2],$m[1],$m[3]);
	}
	else
	{
		$row['timeallow_to'] = 0;
	}
	if(empty($row['title']))
	{
		$error[] = $lang_module['error_required_voucher_name'];
	}
	if(empty($error))
	{
		try
		{
			$row['addtime'] = NV_CURRENTTIME;
			if(empty($row['id']))
			{
				$sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_voucher 
                (title, allowfor, totalvoucher, timeallow_from, timeallow_to, status, addtime) 
                VALUES (' . $db->quote($row['title']) . ', ' . $db->quote($row['allowfor']) . ', ' . $row['totalvoucher'] . ', ' . $row['timeallow_from'] . ', ' . $row['timeallow_to'] . ', ' . $row['status'] . ', ' . NV_CURRENTTIME . ')';

				$row['id'] = $db->insert_id($sql);
				if($row['id'] > 0)
				{
					$array_code = nv4_generate_code($row['totalvoucher'],$length = 6);

					$stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_vouchercode (idvoucher, code, timeuse, status, userid, buyhistoryid) VALUES (' . $row['id'] . ', :code, 0, 0, 0,0)');
					foreach($array_code as $code)
					{
						$stmt->bindParam(':code',$code,PDO::PARAM_STR);
						$exc = $stmt->execute();
					}
				}
			}
			else
			{
				$data_old = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_voucher WHERE id=' . $row['id'])->fetch();

				$stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_voucher SET title=:title, allowfor=:allowfor, totalvoucher=:totalvoucher, timeallow_from=:timeallow_from, timeallow_to=:timeallow_to, status=:status WHERE id=' . $row['id']);
				$stmt->bindParam(':title',$row['title'],PDO::PARAM_STR);
				$stmt->bindParam(':allowfor',$row['allowfor'],PDO::PARAM_STR);
				$stmt->bindParam(':totalvoucher',$row['totalvoucher'],PDO::PARAM_INT);
				$stmt->bindParam(':timeallow_from',$row['timeallow_from'],PDO::PARAM_INT);
				$stmt->bindParam(':timeallow_to',$row['timeallow_to'],PDO::PARAM_INT);
				$stmt->bindParam(':status',$row['status'],PDO::PARAM_INT);
				$exc = $stmt->execute();
                
                //neu $row['totalvoucher'] != $data_old['totalvoucher'] thi phai xu ly code thua hoac thieu
				if($data_old['totalvoucher'] != $row['totalvoucher'])
				{
				    //xoa code da  tao
					if($data_old['totalvoucher'] > $row['totalvoucher'])
					{
						$total_code_delete = $data_old['totalvoucher'] - $row['totalvoucher'];
						$db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_vouchercode WHERE status=0 AND idvoucher = ' . $db->quote($row['id']) . ' LIMIT ' . $total_code_delete);
					}
					else
					{
					   //them code thieu so voi truoc do
						$total_code_insert = $row['totalvoucher'] - $data_old['totalvoucher'];
                        
						$array_code = nv4_generate_code($total_code_insert,$length = 6);
						$stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_vouchercode (idvoucher, code, timeuse, status, userid, buyhistoryid) VALUES (' . $row['id'] . ', :code, 0, 0, 0,0)');
						foreach($array_code as $code)
						{
							$stmt->bindParam(':code',$code,PDO::PARAM_STR);
							$exc = $stmt->execute();
						}
					}
				}
			}

			if($exc)
			{
				$nv_Cache->delMod($module_name);
				Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
				die();
			}
		}
		catch (PDOException $e)
		{
			trigger_error($e->getMessage());
			$error[] =$e->getMessage();
		}
	}
}
elseif($row['id'] > 0)
{
	$row = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_voucher WHERE id=' . $row['id'])->fetch();
	if(empty($row))
	{
		Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
		die();
	}
}
else
{
	$row['id'] = $row['timeallow_from'] = $row['timeallow_to'] = 0;
	$row['status'] = 1;
}
if($row['timeallow_from'] > 0)
	$row['timeallow_from'] = date('d/m/Y',$row['timeallow_from']);
if($row['timeallow_to'] > 0)
	$row['timeallow_to'] = date('d/m/Y',$row['timeallow_to']);

// Fetch Limit
$show_view = false;
if(!$nv_Request->isset_request('id','post,get') && !$nv_Request->isset_request('add','post,get'))
{
	$show_view = true;
	$page = $nv_Request->get_int('page','post,get',1);
	$db->sqlreset()->select('COUNT(*)')->from('' . NV_PREFIXLANG . '_' . $module_data . '_voucher');
	$sth = $db->prepare($db->sql());
	$sth->execute();
	$num_items = $sth->fetchColumn();

	$db->select('*')->order('addtime DESC');
	$sth = $db->prepare($db->sql());
	$sth->execute();
}

$xtpl = new XTemplate($op . '.tpl',NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG',$lang_module);
$xtpl->assign('NV_LANG_VARIABLE',NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA',NV_LANG_DATA);
$xtpl->assign('NV_BASE_ADMINURL',NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE',NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE',NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME',$module_name);
$xtpl->assign('OP',$op);
$xtpl->assign('ROW',$row);
$xtpl->assign('addnew_voucher',NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;add=1');
if($show_view)
{
	while($view = $sth->fetch())
	{
		$view['status'] = $lang_module['status_' . $view['status']];
		$view['addtime'] = date('d/m/Y',$view['addtime']);
		$view['timeallow'] = ($view['timeallow_from'] > 0) ? date('d/m/Y',$view['timeallow_from']) : '';
		$view['timeallow'] .= ($view['timeallow_to'] > 0) ? ' - ' . date('d/m/Y',$view['timeallow_to']) : '';
		$view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $view['id'];
        $view['link_viewcode'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=vouchercode&amp;voucherid=' . $view['id'];
		$view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $view['id'] . '&amp;delete_checkss=' . md5($view['id'] . NV_CACHE_PREFIX . $client_info['session_id']);
		$xtpl->assign('VIEW',$view);
		$xtpl->parse('main.view.loop');
	}
	$xtpl->parse('main.view');
}
else
{
	if(!empty($error))
	{
		$xtpl->assign('ERROR',implode('<br />',$error));
		$xtpl->parse('main.addnew.error');
	}

	$array_select_status = array();
	$array_select_status[1] = $lang_module['status_1'];
	$array_select_status[0] = $lang_module['status_0'];
	foreach($array_select_status as $key => $title)
	{
		$xtpl->assign('OPTION',array(
			'key' => $key,
			'title' => $title,
			'selected' => ($key == $row['status']) ? ' selected="selected"' : ''));
		$xtpl->parse('main.addnew.select_status');
	}
	if(empty($row['id']))
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
