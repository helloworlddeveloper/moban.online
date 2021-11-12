<?php

/**
 * @Project NUKEVIET 4.x
 * @Author EDUSGROUP.JSC (thangbv@edus.vn)
 * @Copyright (C) 2014 EDUSGROUP.JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 14 Mar 2017 10:56:01 GMT
 */

if(!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN'))
	die('Stop!!!');

$submenu['khoahoc'] = $lang_module['khoahoc_manage'];
$submenu['review'] = $lang_module['review'];
$submenu['groups'] = $lang_module['groups_khoahoc'];
$submenu['teacher'] = $lang_module['teacher'];
$submenu['subject'] = $lang_module['subject'];
$submenu['class'] = $lang_module['class'];
$submenu['voucher'] = $lang_module['voucher'];
$submenu['tag'] = $lang_module['tag'];
$submenu['tags'] = $lang_module['tags'];
$submenu['config'] = $lang_module['config'];

$allow_func = array(
	'main',
	'khoahoc',
	'review',
	'baihoc',
    'groups',
    'block',
	'themkhoahoc',
	'thembaihoc',
	'teacher',
	'subject',
	'class',
	'tag',
    'tags',
	'teacherajax',
	'khoahocajax',
    'tagsajax',
	'config',
	'voucher',
    'vouchercode',
    'emailmarketing',
    'view');

$array_viewcat_full = array(
    'viewcat_page_new' => $lang_module['viewcat_page_new'],
    'viewcat_page_old' => $lang_module['viewcat_page_old'],
    'viewcat_grid_new' => $lang_module['viewcat_grid_new'],
    'viewcat_grid_old' => $lang_module['viewcat_grid_old'],
    'viewcat_main_left' => $lang_module['viewcat_main_left'],
    'viewcat_main_right' => $lang_module['viewcat_main_right'],
    'viewcat_main_bottom' => $lang_module['viewcat_main_bottom'],
    'viewcat_two_column' => $lang_module['viewcat_two_column'],
    'viewcat_none' => $lang_module['viewcat_none']
);

define('NV_IS_FILE_ADMIN',true);
require_once NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

/**
 * $total = so luong ma se tao
 * $length = do dai cua ma tao
*/
function nv4_generate_code($total, $length=6)
{
	$collection = array();
	for($i = 0; $i < $total; $i++)
	{
		$ukey = strtoupper(substr(sha1(microtime() . $i),rand(0,$length),$length));
		if(!in_array($ukey,$collection))
		{ // you can check this in database as well.
			$collection[] = implode("-",str_split($ukey,$length));
		}
	}
    return $collection;
}


/**
 * nv_show_block_cat_list()
 *
 * @return
 */
function nv_show_block_cat_list()
{
    global $db_slave, $lang_module, $lang_global, $module_name, $module_data, $module_file, $global_config, $module_info;

    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block_cat ORDER BY weight ASC';
    $_array_block_cat = $db_slave->query($sql)->fetchAll();
    $num = sizeof($_array_block_cat);

    if ($num > 0) {
        $array_adddefault = array(
            $lang_global['no'],
            $lang_global['yes']
        );

        $xtpl = new XTemplate('blockcat_lists.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
        $xtpl->assign('LANG', $lang_module);
        $xtpl->assign('GLANG', $lang_global);

        foreach ($_array_block_cat as $row) {
            $numnews = $db_slave->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block where bid=' . $row['bid'])->fetchColumn();

            $xtpl->assign('ROW', array(
                'bid' => $row['bid'],
                'title' => $row['title'],
                'numnews' => $numnews,
                'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=block&amp;bid=' . $row['bid'],
                'linksite' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=groups/' . $row['alias'],
                'url_edit' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=groups&amp;bid=' . $row['bid'] . '#edit'
            ));

            for ($i = 1; $i <= $num; ++$i) {
                $xtpl->assign('WEIGHT', array(
                    'key' => $i,
                    'title' => $i,
                    'selected' => $i == $row['weight'] ? ' selected="selected"' : ''
                ));
                $xtpl->parse('main.loop.weight');
            }

            foreach ($array_adddefault as $key => $val) {
                $xtpl->assign('ADDDEFAULT', array(
                    'key' => $key,
                    'title' => $val,
                    'selected' => $key == $row['adddefault'] ? ' selected="selected"' : ''
                ));
                $xtpl->parse('main.loop.adddefault');
            }

            for ($i = 1; $i <= 30; ++$i) {
                $xtpl->assign('NUMBER', array(
                    'key' => $i,
                    'title' => $i,
                    'selected' => $i == $row['numbers'] ? ' selected="selected"' : ''
                ));
                $xtpl->parse('main.loop.number');
            }

            $xtpl->parse('main.loop');
        }

        $xtpl->parse('main');
        $contents = $xtpl->text('main');
    } else {
        $contents = '&nbsp;';
    }

    return $contents;
}
/**
 * nv_show_block_list()
 *
 * @param mixed $bid
 * @return
 */
function nv_show_block_list($bid)
{
    global $db_slave, $lang_module, $lang_global, $module_name, $module_data, $op, $global_array_cat, $module_file, $global_config;

    $xtpl = new XTemplate('block_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('OP', $op);
    $xtpl->assign('BID', $bid);

    $global_array_cat[0] = array( 'alias' => 'Other' );

    $sql = 'SELECT t1.id, t1.title, t1.alias, t1.classid, t1.subjectid, t1.numlession, t1.numview, t1.price, t1.addtime, t1.status, t2.weight FROM ' . NV_PREFIXLANG . '_' . $module_data . '_khoahoc t1 INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_block t2 ON t1.id = t2.id WHERE t2.bid= ' . $bid . ' AND t1.status=1 ORDER BY t2.weight ASC';
    $array_block = $db_slave->query($sql)->fetchAll();
    $num = sizeof($array_block);
    if ($num > 0) {
        foreach ($array_block as $row) {
            $xtpl->assign('ROW', array(
                'addtime' => nv_date('H:i d/m/Y', $row['addtime']),
                'status' => $lang_module['status_' . $row['status']],
                'numview' => number_format($row['numview'], 0, ',', '.'),
                'price' => number_format($row['price'], 0, ',', '.'),
                'id' => $row['id'],
                'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$row['catid']]['alias'] . '/' . $row['alias'] . '-' . $row['id'] . $global_config['rewrite_exturl'],
                'title' => $row['title']
            ));

            for ($i = 1; $i <= $num; ++$i) {
                $xtpl->assign('WEIGHT', array(
                    'key' => $i,
                    'title' => $i,
                    'selected' => $i == $row['weight'] ? ' selected="selected"' : ''
                ));
                $xtpl->parse('main.loop.weight');
            }

            $xtpl->parse('main.loop');
        }
        $xtpl->parse('main');
        $contents = $xtpl->text('main');
    } else {
        $contents = '&nbsp;';
    }
    return $contents;
}


/**
 * nv_studyonline_fix_block()
 *
 * @param mixed $bid
 * @param bool $repairtable
 * @return
 */
function nv_studyonline_fix_block($bid, $repairtable = true)
{
    global $db, $module_data;
    $bid = intval($bid);
    if ($bid > 0) {
        $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block where bid=' . $bid . ' ORDER BY weight ASC';
        $result = $db->query($sql);
        $weight = 0;
        while ($row = $result->fetch()) {
            ++$weight;
            if ($weight <= 100) {
                $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_block SET weight=' . $weight . ' WHERE bid=' . $bid . ' AND id=' . $row['id'];
            } else {
                $sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block WHERE bid=' . $bid . ' AND id=' . $row['id'];
            }
            $db->query($sql);
        }
        $result->closeCursor();
        if ($repairtable) {
            $db->query('OPTIMIZE TABLE ' . NV_PREFIXLANG . '_' . $module_data . '_block');
        }
    }
}