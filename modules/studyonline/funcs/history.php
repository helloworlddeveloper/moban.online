<?php

/**
 * @Project NUKEVIET 4.x
 * @Author EDUSGROUP.JSC (thangbv@edus.vn)
 * @Copyright (C) 2015 EDUSGROUP.JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 03 Mar 2015 14:33:58 GMT
 */

if(! defined('NV_IS_MOD_STUDYONLINE'))
{
    die('Stop!!!');
}

if (!defined('NV_IS_USER') ) {
    $redirect = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;
    Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=login&nv_redirect=' . nv_redirect_encrypt($redirect));
    die();
}
$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=';

$array_khoahoc = $array_baihoc = array();
//khoa hoc da mua
$_query = $db_slave->query('SELECT t1.priceafter, t1.timebuy, t2.title, t2.alias, t2.id, t2.image, t2.price, t2.hometext, t2.classid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_buyhistory AS t1 INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_khoahoc AS t2 ON t1.idbuy=t2.id WHERE t1.istype=2 AND t1.userid=' . $user_info['userid'] . ' ORDER BY t1.timebuy DESC');
while($row = $_query->fetch())
{

    if(! empty($row['image']) && file_exists(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image']))
    {
        $row['image'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $row['image'];
    }
    else
    {
        $row['image'] = '';
    }

    $row['link'] = $link . $array_class[$row['classid']]['alias'] . '/' . $row['alias'] . '-' . $row['id'] . $global_config['rewrite_exturl'];
    if($row['priceafter'] == 0)
    {
        $row['priceafter'] = $lang_module['free'];
        $array_baihoc_mienphi[] = $row;
    }
    else
    {
        $row['priceafter'] = number_format($row['price'], 0, '.', ',') . '&nbsp' . NV_IS_MONEY_UNIT;
        $array_baihoc[$row['id']] = $row;
    }
    if($row['price'] == 0)
    {
        $row['price_format'] = $lang_module['free'];
    }
    else
    {
        $row['price_format'] = number_format($row['price'], 0, '.', ',') . '&nbsp' . NV_IS_MONEY_UNIT;
    }
    $row['hometext'] = nv_clean60(strip_tags( $row['hometext'] ), 180);
    $array_khoahoc[$row['id']] = $row;
}

//bai hoc le da mua
$_query = $db_slave->query('SELECT t1.priceafter, t1.timebuy, t2.title, t2.alias, t2.id, t2.price, t3.alias AS khoahocalias, t3.classid, t3.id AS khoahocid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_buyhistory AS t1 INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_baihoc AS t2 ON t1.idbuy=t2.id INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_khoahoc AS t3 ON t2.khoahocid=t3.id WHERE t1.istype=1 AND t1.userid=' . $user_info['userid'] . ' ORDER BY t1.timebuy DESC');
while($row = $_query->fetch())
{
    $row['link'] = $link . $array_class[$row['classid']]['alias'] . '/' . $row['khoahocalias'] . '-' . $row['khoahocid'] . '/' . $row['alias'] . '-' . $row['id'] . $global_config['rewrite_exturl'];

    if($row['priceafter'] == 0)
    {
        $row['priceafter'] = $lang_module['free'];
        $array_baihoc_mienphi[] = $row;
    }
    else
    {
        $row['priceafter'] = number_format($row['price'], 0, '.', ',') . '&nbsp' . NV_IS_MONEY_UNIT;
        $array_baihoc[$row['id']] = $row;
    }
    if($row['price'] == 0)
    {
        $row['price_format'] = $lang_module['free'];
    }
    else
    {
        $row['price_format'] = number_format($row['price'], 0, '.', ',') . '&nbsp' . NV_IS_MONEY_UNIT;
    }
    $array_baihoc[$row['id']] = $row;
}

$contents = studyonline_history_theme($array_khoahoc, $array_baihoc);

$page_title = $lang_module['history_baihocdamua'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
