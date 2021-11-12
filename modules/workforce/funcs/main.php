<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2018 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 07 Jan 2018 03:36:32 GMT
 */

if (!defined('NV_IS_MOD_WORKFORCE')) die('Stop!!!');

if (!nv_user_in_groups($array_config['group_view_workforce'])) {
    Header('Location: ' . NV_BASE_SITEURL );
    die();
}


if ($nv_Request->isset_request('get_user_json', 'post, get')) {
    $code = $nv_Request->get_title('id', ' get', '');

    $db->sqlreset()
        ->select('*')
        ->from(NV_PREFIXLANG . '_' . $module_data)
        ->where('code=' . $db->quote( $code ) )
        ->limit(1);

    $sth = $db->prepare($db->sql());
    $sth->execute();

    $array_data = $sth->fetch() ;
    $array_data['birthday'] = ( $array_data['birthday'] > 0 )? date('d/m/Y', $array_data['birthday'] ): '';
    $array_data['ngaycap'] = ( $array_data['ngaycap'] > 0 )? date('d/m/Y', $array_data['ngaycap'] ): '';
    $array_data['ngaykyhopdong'] = ( $array_data['ngaykyhopdong'] > 0 )? date('d/m/Y', $array_data['ngaykyhopdong'] ): '';
    $array_data['ngaynghiviec'] = ( $array_data['ngaynghiviec'] > 0 )? date('d/m/Y', $array_data['ngaynghiviec'] ): '';
    $array_data['worktype'] = $lang_module['worktype_' . $array_data['worktype']];
    $array_data['fullname'] =  nv_show_name_user($array_data['first_name'], $array_data['last_name']);
    if (!empty($array_data['image']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $array_data['image'])) {
        $array_data['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $array_data['image'];
    }
    $array_data['gender'] = $array_gender[$array_data['gender']];
    header('Cache-Control: no-cache, must-revalidate');
    header('Content-type: application/json');

    ob_start('ob_gzhandler');
    echo json_encode($array_data);
    exit();
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('TEMPLATE', $module_info['template']);
$xtpl->assign('MODULE_FILE', $module_file);
$xtpl->assign('OP', $op);

$xtpl->parse('main');
$contents = nv_theme_workforce_control( $array_control );
$contents .= $xtpl->text( 'main' );

$page_title = $lang_module['workforce'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';