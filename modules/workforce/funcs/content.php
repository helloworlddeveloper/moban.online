<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2018 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 07 Jan 2018 03:36:43 GMT
 */

if (!defined('NV_IS_MOD_WORKFORCE')) die('Stop!!!');

if (!nv_user_in_groups($array_config['group_add_workforce'])) {
    Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=main');
    die();
}

$row = array();
$error = array();
$row['id'] = $nv_Request->get_int('id', 'post,get', 0);

if ($row['id'] > 0) {
    $lang_module['workforce_add'] = $lang_module['workforce_edit'];
    $row = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $row['id'])->fetch();
    if (empty($row)) {
        Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
        die();
    }
} else {
    $row['id'] = 0;
    $row['first_name'] = '';
    $row['last_name'] = '';
    $row['gender'] = 1;
    $row['birthday'] = 0;
    $row['main_phone'] = '';
    $row['other_phone'] = '';
    $row['main_email'] = '';
    $row['other_email'] = '';
    $row['address'] = '';
    $row['image'] = '';
    $row['addtime'] = 0;
    $row['edittime'] = 0;
    $row['useradd'] = 0;
    $row['status'] = 1;
    $row['worktype'] = 0;
    $row['jointime'] = 0;
    $row['companyid'] = 0;
}

if ($nv_Request->isset_request('submit', 'post')) {
    $row['first_name'] = $nv_Request->get_title('first_name', 'post', '');
    $row['last_name'] = $nv_Request->get_title('last_name', 'post', '');
    $row['gender'] = $nv_Request->get_int('gender', 'post', 0);
    $row['companyid'] = $nv_Request->get_int('companyid', 'post', 0);
    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string('birthday', 'post'), $m)) {
        $_hour = 23;
        $_min = 23;
        $row['birthday'] = mktime($_hour, $_min, 59, $m[2], $m[1], $m[3]);
    } else {
        $row['birthday'] = 0;
    }

    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string('ngaykyhopdong', 'post'), $m)) {
        $_hour = 23;
        $_min = 23;
        $row['ngaykyhopdong'] = mktime($_hour, $_min, 59, $m[2], $m[1], $m[3]);
    } else {
        $row['ngaykyhopdong'] = 0;
    }
    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string('ngaynghiviec', 'post'), $m)) {
        $_hour = 23;
        $_min = 23;
        $row['ngaynghiviec'] = mktime($_hour, $_min, 59, $m[2], $m[1], $m[3]);
    } else {
        $row['ngaynghiviec'] = 0;
    }

    $row['phone'] = $nv_Request->get_title('phone', 'post', '');
    $row['email'] = $nv_Request->get_title('email', 'post', '');
    $row['scmnd'] = $nv_Request->get_title('scmnd', 'post', '');
    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string('ngaycap', 'post'), $m)) {
        $_hour = 23;
        $_min = 23;
        $row['ngaycap'] = mktime($_hour, $_min, 59, $m[2], $m[1], $m[3]);
    } else {
        $row['ngaycap'] = 0;
    }
    $row['noicap'] = $nv_Request->get_title('noicap', 'post', '');

    $row['address'] = $nv_Request->get_title('address', 'post', '');
    $row['knowledge'] = $nv_Request->get_string('knowledge', 'post', '');
    $row['image'] = $nv_Request->get_title('image', 'post', '');
    $row['worktype'] = $nv_Request->get_int('worktype', 'post', 0);
    $row['sobhxh'] = $nv_Request->get_title('sobhxh', 'post', '');
    $row['sohdld'] = $nv_Request->get_title('sohdld', 'post', '');
    $row['biensoxe'] = $nv_Request->get_title('biensoxe', 'post', '');

    if (is_file(NV_DOCUMENT_ROOT . $row['image'])) {
        $row['image'] = substr($row['image'], strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/'));
    } else {
        $row['image'] = '';
    }
    if ($row['companyid'] == 0 ) {
        $error[] = $lang_module['error_required_companyid'];
    }
    elseif (empty($row['first_name'])) {
        $error[] = $lang_module['error_required_first_name'];
    } elseif (empty($row['last_name'])) {
        $error[] = $lang_module['error_required_last_name'];
    } elseif (empty($row['birthday'])) {
        $error[] = $lang_module['error_required_birthday'];
    } elseif (empty($row['phone'])) {
        $error[] = $lang_module['error_required_phone'];
    } elseif (empty($row['biensoxe'])) {
        $error[] = $lang_module['error_required_biensoxe'];
    }

    if (empty($error)) {
        try {
            if (empty($row['id'])) {

                $result = $db->query("SHOW TABLE STATUS WHERE Name='" . NV_PREFIXLANG . "_" . $module_data . "'");
                $item = $result->fetch();
                $result->closeCursor();

                $customer_code = vsprintf($module_config[$module_name]['precode'], $item['auto_increment']);

                $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . ' (companyid, code, first_name, last_name, gender, birthday, phone, email, scmnd, ngaycap, noicap, address, image, worktype, sobhxh, sohdld, ngaykyhopdong, ngaynghiviec, biensoxe, addtime, edittime, useradd, status) 
                VALUES ( :companyid, :code, :first_name, :last_name, :gender, :birthday, :phone, :email, :scmnd, :ngaycap, :noicap, :address, :image, :worktype, :sobhxh, :sohdld, :ngaykyhopdong, :ngaynghiviec, :biensoxe, ' . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ', ' . $user_info['userid'] . ', 1)');

                $stmt->bindParam(':code', $customer_code, PDO::PARAM_STR);
            } else {
                $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET companyid=:companyid, first_name = :first_name, last_name = :last_name, gender = :gender, birthday = :birthday, phone = :phone, email = :email, scmnd = :scmnd, ngaycap=:ngaycap, noicap=:noicap, address = :address, image = :image, worktype = :worktype, sobhxh = :sobhxh, sohdld=:sohdld, ngaykyhopdong=:ngaykyhopdong, ngaynghiviec=:ngaynghiviec, biensoxe=:biensoxe, edittime = ' . NV_CURRENTTIME . ' WHERE id=' . $row['id']);
            }
            $stmt->bindParam(':companyid', $row['companyid'], PDO::PARAM_INT);
            $stmt->bindParam(':first_name', $row['first_name'], PDO::PARAM_STR);
            $stmt->bindParam(':last_name', $row['last_name'], PDO::PARAM_STR);
            $stmt->bindParam(':gender', $row['gender'], PDO::PARAM_INT);
            $stmt->bindParam(':birthday', $row['birthday'], PDO::PARAM_INT);
            $stmt->bindParam(':phone', $row['phone'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $row['email'], PDO::PARAM_STR);
            $stmt->bindParam(':scmnd', $row['scmnd'], PDO::PARAM_STR);
            $stmt->bindParam(':ngaycap', $row['ngaycap'], PDO::PARAM_STR);
            $stmt->bindParam(':noicap', $row['noicap'], PDO::PARAM_STR);
            $stmt->bindParam(':address', $row['address'], PDO::PARAM_STR);
            $stmt->bindParam(':image', $row['image'], PDO::PARAM_STR);
            $stmt->bindParam(':worktype', $row['worktype'], PDO::PARAM_INT);
            $stmt->bindParam(':sobhxh', $row['sobhxh'], PDO::PARAM_STR);
            $stmt->bindParam(':sohdld', $row['sohdld'], PDO::PARAM_STR);
            $stmt->bindParam(':ngaykyhopdong', $row['ngaykyhopdong'], PDO::PARAM_INT);
            $stmt->bindParam(':ngaynghiviec', $row['ngaynghiviec'], PDO::PARAM_INT);
            $stmt->bindParam(':biensoxe', $row['biensoxe'], PDO::PARAM_STR);

            $exc = $stmt->execute();
            if ($exc) {
                $nv_Cache->delMod($module_name);
                Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=list');
                die();
            }
        } catch (PDOException $e) {
            $error[] = $e->getMessage();
            trigger_error($e->getMessage());
        }
    }
}

$row['birthday'] = !empty($row['birthday']) ? date('d/m/Y', $row['birthday']) : '';
$row['ngaycap'] = !empty($row['ngaycap']) ? date('d/m/Y', $row['ngaycap']) : '';
$row['ngaykyhopdong'] = !empty($row['ngaykyhopdong']) ? date('d/m/Y', $row['ngaykyhopdong']) : '';
$row['ngaynghiviec'] = !empty($row['ngaynghiviec']) ? date('d/m/Y', $row['ngaynghiviec']) : '';

if (!empty($row['image']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['image'])) {
    $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image'];
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('MODULE_UPLOAD', $module_upload);
$xtpl->assign('OP', $op);
$xtpl->assign('ROW', $row);
$xtpl->assign('URL_USERS', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=workforce&get_user_json=1');
$xtpl->assign('URL_AVATAR', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=avatar');
foreach ($array_gender as $index => $value) {
    $ck = $index == $row['gender'] ? 'checked="checked"' : '';
    $xtpl->assign('GENDER', array(
        'index' => $index,
        'value' => $value,
        'checked' => $ck
    ));
    $xtpl->parse('main.gender');
}

$worktype = array(
    0 => $lang_module['worktype_0'],
    1 => $lang_module['worktype_1'],
    2 => $lang_module['worktype_2'],
);
foreach ($worktype as $key => $title)
{
    $sl = ( $key == $row['worktype'] )? ' selected=selected' : '';
    $xtpl->assign('WORKTYPE', array('key' => $key, 'title' => $title, 'sl' => $sl ));
    $xtpl->parse('main.worktype');

}

foreach ($array_company as $company)
{
    $company['sl'] = ( $company['id'] == $row['companyid'] )? ' selected=selected' : '';
    $xtpl->assign('COMPANY', $company );
    $xtpl->parse('main.company');
}

if (!empty($error)) {
    $xtpl->assign('ERROR', implode('<br />', $error));
    $xtpl->parse('main.error');
}

$xtpl->parse('main');
$contents = nv_theme_workforce_control( $array_control );
$contents .= $xtpl->text( 'main' );

$page_title = $lang_module['workforce_add'];
$array_mod_title[] = array(
    'title' => $lang_module['workforce'],
    'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=list'
);
$array_mod_title[] = array(
    'title' => $page_title,
    'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op
);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';