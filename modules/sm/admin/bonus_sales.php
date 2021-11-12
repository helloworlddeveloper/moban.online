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
$table_name = NV_PREFIXLANG . "_" . $module_data . "_sale_bonus";
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

$page_title = $lang_module['bonus_sales'];

$error = "";
$savecat = 0;

$data = array( "title" => "", 'note' => "", 'agencyid' => 0 );
$data['id'] = $nv_Request->get_int('id', 'post,get', 0);
$savecat = $nv_Request->get_int('savecat', 'post', 0);

if (! empty($savecat)) {
    $data['salesfrom'] = $nv_Request->get_title( 'salesfrom', 'post', '' );
    $data['salesfrom'] = floatval(preg_replace('/[^0-9\,]/', '', $data['salesfrom']));
    $data['salesto'] = $nv_Request->get_title( 'salesto', 'post', '' );
    $data['salesto'] = floatval(preg_replace('/[^0-9\,]/', '', $data['salesto']));
    $data['bonus_money'] = $nv_Request->get_title( 'bonus_money', 'post', '' );
    $data['bonus_money'] = floatval(preg_replace('/[^0-9\,]/', '', $data['bonus_money']));
    $data['bonus_point'] = $nv_Request->get_title( 'bonus_point', 'post', '' );
    $data['bonus_point'] = floatval(preg_replace('/[^0-9\,]/', '', $data['bonus_point']));

    if ($data['id'] == 0) {
        $sql = "INSERT INTO " . $table_name . " (id, salesfrom, salesto, bonus_money, bonus_point) VALUES (NULL, " . floatval( $data['salesfrom'] ) . ",  " . floatval( $data['salesto'] ) . ",  " . floatval( $data['bonus_money'] ) . ",  " . $data['bonus_point'] . ")";

        if ($db->insert_id($sql)) {
            $nv_Cache->delMod($module_name);
            Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
            die();
        } else {
            $error = $lang_module['errorsave'];
        }
    } else {

        $stmt = $db->prepare("UPDATE " . $table_name . " SET salesfrom= :salesfrom, salesto = :salesto, bonus_money = :bonus_money, bonus_point = :bonus_point WHERE id =" . $data['id']);
        $stmt->bindParam(':salesfrom', $data['salesfrom'], PDO::PARAM_INT);
        $stmt->bindParam(':salesto', $data['salesto'], PDO::PARAM_INT);
        $stmt->bindParam(':bonus_money', $data['bonus_money'], PDO::PARAM_INT);
        $stmt->bindParam(':bonus_point', $data['bonus_point'], PDO::PARAM_INT);

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
        $data = $db->query("SELECT * FROM " . $table_name . " WHERE id=" . $data['id'])->fetch();
        $data['salesfrom'] = number_format( $data['salesfrom'], 0, ',', '.');
        $data['salesto'] = number_format( $data['salesto'], 0, ',', '.');
        $data['bonus_money'] = number_format( $data['bonus_money'], 0, ',', '.');
        $data['bonus_point'] = number_format( $data['bonus_point'], 0, ',', '.');
    }
}

$xtpl = new XTemplate("bonus_sales.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('DATA', $data);
$xtpl->assign('caption', $lang_module['bonus_sales_info']);

$count = 0;
$result = $db->query("SELECT * FROM " . $table_name . " ORDER BY id DESC");
while ($row = $result->fetch()) {

    $row['salesfrom'] = number_format( $row['salesfrom'], 0, '.', ',');
    $row['salesto'] = number_format( $row['salesto'], 0, '.', ',');
    $row['bonus_money'] = number_format( $row['bonus_money'], 0, '.', ',');
    $row['bonus_point'] = number_format( $row['bonus_point'], 0, '.', ',');
    $xtpl->assign('ROW', $row);
    $xtpl->assign('link_edit', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&id=" . $row['id']);
    $xtpl->assign('link_del', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&delete=1&id=" . $row['id']);
    //$xtpl->assign('link_saleoff_detail', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=saleoff_detail&sid=" . $row['id']);
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
