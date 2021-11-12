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

if($nv_Request->isset_request('changeblock','post'))
{
	$id = $nv_Request->get_int('id','post',0);
	$bid = $nv_Request->get_int('bid','post',0);
	$del_list = $nv_Request->get_string('del_list','post','');
    $mod = $nv_Request->get_title('mod','post','');
    $new_vid = $nv_Request->get_int('new_vid', 'post', 0);
	$content = 'NO_' . $bid;

	if($bid > 0)
	{
		if($del_list != '')
		{
			$array_id = array_map('intval',explode(',',$del_list));
			foreach($array_id as $id)
			{
				if($id > 0)
				{
					$db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block WHERE bid=' . $bid . ' AND id=' . $id);
				}
			}
			nv_studyonline_fix_block($bid);
			$content = 'OK_' . $bid;
		}
		elseif($id > 0)
		{
			list($bid,$id) = $db->query('SELECT bid, id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block WHERE bid=' . intval($bid) . ' AND id=' . intval($id))->fetch(3);
			if($bid > 0 and $id > 0)
			{
				if($mod == 'weight' and $new_vid > 0)
				{
					$query = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block WHERE bid=' . $bid . ' AND id!=' . $id . ' ORDER BY weight ASC';
					$result = $db->query($query);

					$weight = 0;
					while($row = $result->fetch())
					{
						++$weight;
						if($weight == $new_vid)
						{
							++$weight;
						}
						$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_block SET weight=' . $weight . ' WHERE bid=' . $bid . ' AND id=' . intval($row['id']);
						$db->query($sql);
					}

					$result->closeCursor();
					$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_block SET weight=' . $new_vid . ' WHERE bid=' . $bid . ' AND id=' . intval($id);
					$db->query($sql);

					$content = 'OK_' . $bid;
				}
				elseif($mod == 'delete')
				{
					$db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block WHERE bid=' . $bid . ' AND id=' . intval($id));
					$content = 'OK_' . $bid;
				}
			}
		}

		nv_studyonline_fix_block($bid);
		$nv_Cache->delMod($module_name);
	}

	include NV_ROOTDIR . '/includes/header.php';
	echo $content;
	include NV_ROOTDIR . '/includes/footer.php';
	exit;
}
if($nv_Request->isset_request('list_block','get'))
{
	$bid = $nv_Request->get_int('bid','get',0);
	$contents = nv_show_block_list($bid);

	include NV_ROOTDIR . '/includes/header.php';
	echo $contents;
	include NV_ROOTDIR . '/includes/footer.php';
    exit;
}

$page_title = $lang_module['block'];

$sql = 'SELECT bid, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block_cat ORDER BY weight ASC';
$result = $db_slave->query($sql);

$array_block = array();
while(list($bid_i,$title_i) = $result->fetch(3))
{
	$array_block[$bid_i] = $title_i;
}
if(empty($array_block))
{
	Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=blockcat');
}

$cookie_bid = $nv_Request->get_int('int_bid','cookie',0);
if(empty($cookie_bid) or !isset($array_block[$cookie_bid]))
{
	$cookie_bid = 0;
}

$bid = $nv_Request->get_int('bid','get,post',$cookie_bid);
if(!in_array($bid,array_keys($array_block)))
{
	$bid_array_id = array_keys($array_block);
	$bid = $bid_array_id[0];
}

if($cookie_bid != $bid)
{
	$nv_Request->set_Cookie('int_bid',$bid,NV_LIVE_COOKIE_TIME);
}
$page_title = $array_block[$bid];

if($nv_Request->isset_request('checkss,idcheck','post') and $nv_Request->get_string('checkss','post') == NV_CHECK_SESSION)
{
	$sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block WHERE bid=' . $bid;
	$result = $db_slave->query($sql);
	$_id_array_exit = array();
	while(list($_id) = $result->fetch(3))
	{
		$_id_array_exit[] = $_id;
	}

	$id_array = array_map('intval',$nv_Request->get_array('idcheck','post'));
	foreach($id_array as $id)
	{
		if(!in_array($id,$_id_array_exit))
		{
			try
			{
				$db->query('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_block (bid, id, weight) VALUES (' . $bid . ', ' . $id . ', 0)');
			}
			catch (PDOException $e)
			{
				trigger_error($e->getMessage());
			}
		}
	}
	nv_studyonline_fix_block($bid);
	$nv_Cache->delMod($module_name);
	Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&bid=' . $bid);
	die();
}

if($bid > 0 and defined('NV_IS_SPADMIN') and $nv_Request->get_string('order_publtime','get') == md5($bid . NV_CHECK_SESSION))
{
	$_result = $db->query('SELECT t1.id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows t1 INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_block t2 ON t1.id = t2.id WHERE t2.bid= ' . $bid . ' ORDER BY t1.publtime DESC, t2.weight ASC');
	$weight = 0;
	while($_row = $_result->fetch())
	{
		++$weight;
		$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_block SET weight=' . $weight . ' WHERE bid=' . $bid . ' AND id=' . $_row['id'];
		$db->query($sql);
	}
	$result->closeCursor();
	$nv_Cache->delMod($module_name);
	Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&bid=' . $bid);
	die();
}

$select_options = array();
foreach($array_block as $xbid => $blockname)
{
	$select_options[NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;bid=' . $xbid] = $blockname;
}

$xtpl = new XTemplate('block.tpl',NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG',$lang_module);
$xtpl->assign('GLANG',$lang_global);
$xtpl->assign('NV_BASE_ADMINURL',NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE',NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE',NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME',$module_name);
$xtpl->assign('OP',$op);
$xtpl->assign('BLOCK_LIST',nv_show_block_list($bid));

$xtpl->parse('main');
$contents = $xtpl->text('main');

$set_active_op = 'groups';
include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
