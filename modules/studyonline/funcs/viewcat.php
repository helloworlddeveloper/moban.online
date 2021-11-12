<?php

/**
 * @Project NUKEVIET 4.x
 * @Author EDUSGROUP.JSC (thangbv@edus.vn)
 * @Copyright (C) 2015 EDUSGROUP.JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 03 Mar 2015 14:33:58 GMT
 */

if(! defined('NV_IS_MOD_STUDYONLINE'))
    die('Stop!!!');

$page_title = sprintf($lang_module['class_title_info'], '', $array_class[$classid]['title']);
$description = $array_class[$classid]['description'];
$key_words = $module_info['keywords'];

$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $array_op[0];
$base_url_rewrite = nv_url_rewrite(str_replace('&amp;', '&', $base_url), true);
$page_url_rewrite = ($page > 1) ? nv_url_rewrite($base_url . '/page-' . $page, true) : $base_url_rewrite;
$request_uri = $_SERVER['REQUEST_URI'];

if(! ($home or $request_uri == $base_url_rewrite or $request_uri == $page_url_rewrite or NV_MAIN_DOMAIN . $request_uri == $base_url_rewrite or NV_MAIN_DOMAIN . $request_uri == $page_url_rewrite))
{
    $redirect = '<meta http-equiv="Refresh" content="3;URL=' . $base_url_rewrite . '" />';
    nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] . $redirect, 404);
}

if(! defined('NV_IS_MODADMIN') and $page < 5)
{

    $cache_file = NV_LANG_DATA . '_' . $module_info['template'] . '_' . $op . '_' . $classid . '_' . $page . '_' . NV_CACHE_PREFIX . '.cache';
    if(($cache = $nv_Cache->getItem($module_name, $cache_file, 3600)) != false)
    {
        $contents = $cache;
    }
}

if(empty($contents))
{
    $db_slave->sqlreset()->select('COUNT(*)')->from(NV_PREFIXLANG . '_' . $module_data . '_khoahoc')->where('status= 1 AND classid=' . $classid);
    $num_items = $db_slave->query($db_slave->sql())->fetchColumn();

    $db_slave->select('id, classid, subjectid, title, alias, image,hometext,addtime,numview, numlike, price, teacherid, numbuy, listtag')->order('addtime DESC')->limit($per_page)->offset(($page - 1) * $per_page);

    $array_data = array();
    $result = $db_slave->query($db_slave->sql());
    while($item = $result->fetch())
    {
        $item['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $array_class[$item['classid']]['alias'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
        if(! empty($item['image']))
        {
            $item['thumb'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $site_mods[$module_name]['module_upload'] . '/' . $item['image'];
        }
        elseif(! empty($show_no_image))
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
        $item['teacherid'] = explode(',', $item['teacherid']);
        $item['teacher_info'] = array();
        foreach($item['teacherid'] as $teacherid)
        {
            if(isset($array_teacher[$teacherid]))
            {
                $item['teacher_info'][$teacherid] = $array_teacher[$teacherid];
                $item['teacher_info'][$teacherid]['teacher_link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=giao-vien/' . $array_teacher[$teacherid]['alias'];
                unset($item['teacherid']);
            }
        }
        $item['subject_name'] = $array_subject[$item['subjectid']]['title'];
        $item['subject_link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $array_class[$item['classid']]['alias'] . '/' . $array_subject[$item['subjectid']]['alias'];
        $item['title_clean'] = nv_clean60($item['title'], 45);
        if($item['price'] == 0)
        {
            $item['price'] = $lang_module['free'];
        }
        else
        {
            $item['price'] = number_format($item['price'], 0, ',', '.');
        }
        $item['addtime'] = date('d/m/Y', $item['addtime']);
        $array_data[$item['classid']][] = $item;
    }
    $generate_page = nv_alias_page($page_title, $base_url, $num_items, $per_page, $page);
    $contents = nv_theme_main_studyonline($array_data, $generate_page);
}
if($page > 1)
{
    $page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
