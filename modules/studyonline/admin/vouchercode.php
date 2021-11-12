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

$voucherid = $nv_Request->get_int('voucherid','get',0);
$data_voucher = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_voucher WHERE id=' . $voucherid)->fetch();
if(empty($data_voucher))
{
	Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=voucher');
	die();
}

$page = $nv_Request->get_int('page','post,get',1);
$db->sqlreset()->select('*')->from('' . NV_PREFIXLANG . '_' . $module_data . '_vouchercode')->where('idvoucher=' . $voucherid)->order('id DESC');
$sth = $db->prepare($db->sql());
$sth->execute();

$xtpl = new XTemplate($op . '.tpl',NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG',$lang_module);
$xtpl->assign('NV_LANG_VARIABLE',NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA',NV_LANG_DATA);
$xtpl->assign('NV_BASE_ADMINURL',NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE',NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE',NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME',$module_name);
$xtpl->assign('OP',$op);

while($view = $sth->fetch())
{
	$view['status'] = $lang_module['statuscode_' . $view['status']];
    $view['timeuse'] = ($view['timeuse'] > 0) ? date('d/m/Y',$view['timeuse']) : '';
    $view['userid'] = ($view['userid'] > 0) ? 'Buiding' : '';
    $view['buyhistoryid'] = ($view['buyhistoryid'] > 0) ? 'Buiding' : '';
	$view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $view['id'];
	$view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $view['id'] . '&amp;delete_checkss=' . md5($view['id'] . NV_CACHE_PREFIX . $client_info['session_id']);
	$xtpl->assign('VIEW',$view);
	$xtpl->parse('main.loop');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['teacher'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
