<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}
$table_name = NV_PREFIXLANG . "_" . $module_data . "_depot";
/*
if( $nv_Request->isset_request('delete', 'get', 0) ){

    $id = $nv_Request->get_int('id', 'post,get', 0);
    $contents = "NO_" . $id;

    if ($id > 0) {
        $sql = "DELETE FROM " . $table_name . " WHERE id=" . $id;
        if ($db->exec($sql)) {
            $contents = "OK_" . $id;
        }
    } else {
        $listall = $nv_Request->get_string('listall', 'post,get');
        $array_id = explode(',', $listall);
        $array_id = array_map("intval", $array_id);

        foreach ($array_id as $id) {
            if ($id > 0) {
                $sql = "DELETE FROM " . $table_name . " WHERE id=" . $id;
                $db->query($sql);
            }
        }
        $contents = "OK_0";
    }
    die($contents);
}
*/

$page_title = $lang_module['depot'];

$error = "";
$savecat = 0;

$data = array( "status" => 1, 'userid' => 0  );
$data['id'] = $nv_Request->get_int('id', 'post,get', 0);
$savecat = $nv_Request->get_int('savecat', 'post', 0);

if (! empty($savecat)) {
    $data['title'] = nv_substr($nv_Request->get_title('title', 'post', '', 1), 0, 255);
    $data['address'] = nv_substr($nv_Request->get_title('address', 'post', '', 1), 0, 255);
    $data['mobile'] = nv_substr($nv_Request->get_title('mobile', 'post', '', 1), 0, 30);
    $data['userid'] = $nv_Request->get_int('userid', 'post', 0);

    if ($data['id'] == 0) {

        $sql = "INSERT INTO " . $table_name . " (id, userid, title, address, mobile, addtime, status) 
        VALUES (NULL, " . intval( $data['userid'] ) . ", " . $db->quote( $data['title'] ) . "," . $db->quote( $data['address'] ) . ",  " . $db->quote( $data['mobile'] ) . ", " . NV_CURRENTTIME . ", 1)";

        if ($db->insert_id($sql)) {
            $nv_Cache->delMod($module_name);

            Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
            die();
        } else {
            $error = $lang_module['errorsave'];
        }
    } else {
        $stmt = $db->prepare("UPDATE " . $table_name . " SET userid=:userid, title= :title, address = :address, mobile=:mobile WHERE id =" . $data['id']);
        $stmt->bindParam(':userid', $data['userid'], PDO::PARAM_INT);
        $stmt->bindParam(':title', $data['title'], PDO::PARAM_STR);
        $stmt->bindParam(':address', $data['address'], PDO::PARAM_STR);
        $stmt->bindParam(':mobile', $data['mobile'], PDO::PARAM_STR);
        if ($stmt->execute()) {
            $error = $lang_module['saveok'];

            $nv_Cache->delMod($module_name);
            Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
            die();
        } else {
            $error = $lang_module['errorsave'];
        }
    }
} else {
    if ($data['id'] > 0) {

        $data_old = $db->query("SELECT * FROM " . $table_name . " WHERE id=" . $data['id'])->fetch();
        $data = array(
            "id" => $data_old['id'],
            "mobile" => $data_old['mobile'],
            "address" => $data_old['address'],
            "title" => $data_old['title'],
            "userid" => $data_old['userid']
        );
    }
}

$caption = $lang_module['depot_add_info'];
if( $data['id']  > 0 ){
    $caption = $lang_module['depot_edit_info'];
}
$data_user =array();
if( $data['userid'] > 0 ){
    $data_user = $db->query("SELECT * FROM " . NV_USERS_GLOBALTABLE . " WHERE userid=" . $data['userid'])->fetch();
    $data_user['fullname'] = nv_show_name_user($data_user['first_name'], $data_user['last_name']);
}

$xtpl = new XTemplate($op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('CONTENT', $data);
$xtpl->assign('caption', $caption );

if( !empty( $data_user )){
    $xtpl->assign('DATA_USER', $data_user );
    $xtpl->parse('main.admin_depot');
}

$count = 0;
$result = $db->query("SELECT t1.*, t2.first_name, t2.last_name FROM " . $table_name . " AS t1 INNER JOIN " . NV_USERS_GLOBALTABLE . " AS t2 ON t1.userid=t2.userid ORDER BY t1.id DESC");
while ($row = $result->fetch()) {
    $row['fullname'] = nv_show_name_user($row['first_name'], $row['last_name']);
    $xtpl->assign('DATA', $row);
    $xtpl->assign('link_edit', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&id=" . $row['id']);
    //$xtpl->assign('link_del', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&delete=1&id=" . $id);
    $xtpl->parse('main.data.row');
    ++$count;
}

$xtpl->assign('URL_DEL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . '&delete=1');
$xtpl->assign('URL_DEL_BACK', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);

if ($count > 0) {
    $xtpl->parse('main.data');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
