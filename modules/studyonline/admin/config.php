<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['setting'];


$savesetting = $nv_Request->get_int('savesetting', 'post', 0);
if (!empty($savesetting)) {
    $array_config = array();
    $array_config['indexfile'] = $nv_Request->get_title('indexfile', 'post', '', 1);
    $array_config['per_page'] = $nv_Request->get_int('per_page', 'post', 0);
    $array_config['st_links'] = $nv_Request->get_int('st_links', 'post', 0);
    $array_config['homewidth'] = $nv_Request->get_int('homewidth', 'post', 0);
    $array_config['homeheight'] = $nv_Request->get_int('homeheight', 'post', 0);
    $array_config['blockwidth'] = $nv_Request->get_int('blockwidth', 'post', 0);
    $array_config['blockheight'] = $nv_Request->get_int('blockheight', 'post', 0);
    $array_config['imagefull'] = $nv_Request->get_int('imagefull', 'post', 0);
    $array_config['auto_tags'] = $nv_Request->get_int('auto_tags', 'post', 0);
    $array_config['allowed_rating_point'] = $nv_Request->get_int('allowed_rating_point', 'post', 0);
    $array_config['showhometext'] = $nv_Request->get_int('showhometext', 'post', 0);
    
    $array_config['facebookappid'] = $nv_Request->get_title('facebookappid', 'post', '');
    $array_config['socialbutton'] = $nv_Request->get_int('socialbutton', 'post', 0);
    $array_config['show_no_image'] = $nv_Request->get_title('show_no_image', 'post', '', 0);
    $array_config['imgposition'] = $nv_Request->get_int('imgposition', 'post', 0);
    $array_config['alias_lower'] = $nv_Request->get_int('alias_lower', 'post', 0);
    $array_config['streaming'] = $nv_Request->get_int('streaming', 'post', 0);
    $array_config['server_streaming'] = $nv_Request->get_title('server_streaming', 'post', 0);
    $array_config['numview_guest'] = $nv_Request->get_int('numview_guest', 'post', 0);//so bai giang xem khong can dang nhap, ap dung voi khoa hoc mien phi 100%
    
    if (!nv_is_url($array_config['show_no_image']) and nv_is_file($array_config['show_no_image'])) {
        $lu = strlen(NV_BASE_SITEURL);
        $array_config['show_no_image'] = substr($array_config['show_no_image'], $lu);
    } else {
        $array_config['show_no_image'] = '';
    }
    if (empty($error)) {
        $sth = $db->prepare("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = '" . NV_LANG_DATA . "' AND module = :module_name AND config_name = :config_name");
        $sth->bindParam(':module_name', $module_name, PDO::PARAM_STR);
        foreach ($array_config as $config_name => $config_value) {
            $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR);
            $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
            $sth->execute();
        }

        $nv_Cache->delMod('settings');
        $nv_Cache->delMod($module_name);
        Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
        die();
    }

}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('DATA', $module_config[$module_name]);
if (!empty($error)) {
    $xtpl->assign('error', $error);
    $xtpl->parse('main.error');
}


// Cach hien thi tren trang chu
foreach ($array_viewcat_full as $key => $val) {
    $xtpl->assign('INDEXFILE', array(
        'key' => $key,
        'title' => $val,
        'selected' => $key == $module_config[$module_name]['indexfile'] ? ' selected="selected"' : ''
    ));
    $xtpl->parse('main.indexfile');
}


$array_config_video_streaming = array('0' => $lang_module['videostreaming_option_0'], '1' => $lang_module['videostreaming_option_1']);
// Cach hien thi tren trang chu
foreach ($array_config_video_streaming as $key => $val) {
    $xtpl->assign('STREAMING', array(
        'key' => $key,
        'title' => $val,
        'selected' => $key == $module_config[$module_name]['streaming'] ? ' selected="selected"' : ''
    ));
    $xtpl->parse('main.streaming');
}


// So bai viet tren mot trang
for ($i = 5; $i <= 100; ++$i) {
    $xtpl->assign('PER_PAGE', array(
        'key' => $i,
        'title' => $i,
        'selected' => $i == $module_config[$module_name]['per_page'] ? ' selected="selected"' : ''
    ));
    $xtpl->parse('main.per_page');
}

// Bai viet chi hien thi link
for ($i = 0; $i <= 50; ++$i) {
    $xtpl->assign('ST_LINKS', array(
        'key' => $i,
        'title' => $i,
        'selected' => $i == $module_config[$module_name]['st_links'] ? ' selected="selected"' : ''
    ));
    $xtpl->parse('main.st_links');
}

// Show points rating article on google
for ($i = 0; $i <= 6; ++$i) {
    $xtpl->assign('RATING_POINT', array(
        'key' => $i,
        'title' => ($i == 6) ? $lang_module['no_allowed_rating'] : $i,
        "selected" => $i == $module_config[$module_name]['allowed_rating_point'] ? " selected=\"selected\"" : ""
    ));
    $xtpl->parse('main.allowed_rating_point');
}
$xtpl->assign('PATH', NV_UPLOADS_DIR . '/' . $module_upload);
$xtpl->assign('CURRENTPATH', NV_UPLOADS_DIR . '/' . $module_upload);

$xtpl->assign('SHOWHOMETEXT', $module_config[$module_name]['showhometext'] ? ' checked="checked"' : '');
$xtpl->assign('SOCIALBUTTON', $module_config[$module_name]['socialbutton'] ? ' checked="checked"' : '');
$xtpl->assign('ALIAS_LOWER', $module_config[$module_name]['alias_lower'] ? ' checked="checked"' : '');
$xtpl->assign('AUTO_TAGS', $module_config[$module_name]['auto_tags'] ? ' checked="checked"' : '');
$xtpl->assign('SHOW_NO_IMAGE', (!empty($module_config[$module_name]['show_no_image'])) ? NV_BASE_SITEURL . $module_config[$module_name]['show_no_image'] : '');

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
