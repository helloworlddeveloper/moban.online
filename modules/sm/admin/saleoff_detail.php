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
$table_name = NV_PREFIXLANG . "_" . $module_data . "_saleoff_detail";
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

$page_title = $lang_module['saleoff'];

$error = "";
$savecat = 0;

$data = array( "title" => "", 'note' => "", 'productid' => 0 );
$data['sid'] = $nv_Request->get_int('sid', 'get', 0);
$data['id'] = $nv_Request->get_int('id', 'post,get', 0);
$savecat = $nv_Request->get_int('savecat', 'post', 0);
if( $data['sid'] == 0 ){
    Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=saleoff");
    die();
}
if (! empty($savecat)) {
    $data['sid'] = $nv_Request->get_int('sid', 'post', 0);
    $data['productid'] = $nv_Request->get_int('productid', 'post', 0);
    $data['numbergift'] = $nv_Request->get_title( 'numbergift', 'post', '' );
    $data['numbergift'] = floatval(preg_replace('/[^0-9\,]/', '', $data['numbergift']));
    $data['moneygift'] = $nv_Request->get_title( 'moneygift', 'post', '' );
    $data['moneygift'] = floatval(preg_replace('/[^0-9\,]/', '', $data['moneygift']));

    if ($data['id'] == 0) {
       $sql = "INSERT INTO " . $table_name . " (id, saleoffid, productid, numbergift, moneygift) VALUES (NULL, " . $data['sid'] . ", " . $data['productid'] . ",  " . $data['numbergift'] . ",  " . floatval( $data['moneygift'] ) . ")";

        if ($db->insert_id($sql)) {
            $nv_Cache->delMod($module_name);
            Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . '&sid=' . $data['sid']);
            die();
        } else {
            $error = $lang_module['errorsave'];
        }
    } else {
        $stmt = $db->prepare("UPDATE " . $table_name . " SET saleoffid=:saleoffid, productid= :productid, numbergift = :numbergift, moneygift = :moneygift WHERE id =" . $data['id']);
        $stmt->bindParam(':saleoffid', $data['sid'], PDO::PARAM_INT);
        $stmt->bindParam(':productid', $data['productid'], PDO::PARAM_INT);
        $stmt->bindParam(':numbergift', $data['numbergift'], PDO::PARAM_INT);
        $stmt->bindParam(':moneygift', $data['moneygift'], PDO::PARAM_INT);

        if ($stmt->execute()) {
            $error = $lang_module['saveok'];

            $nv_Cache->delMod($module_name);
            Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . '&sid=' . $data['sid'] );
            die();
        } else {
            $error = $lang_module['errorsave'];
        }
    }
} else {
    if ($data['id'] > 0) {
        $data = $db->query("SELECT * FROM " . $table_name . " WHERE id=" . $data['id'])->fetch();
        $data['sid'] = $data['saleoffid'];
    }
}

$xtpl = new XTemplate($op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('DATA', $data);
$xtpl->assign('caption', $lang_module['prounit_info']);

$count = 0;
$result = $db->query('SELECT * FROM ' . $table_name . ' WHERE saleoffid= ' . $data['sid'] . ' ORDER BY id DESC');
while ($row = $result->fetch()) {
    $row['productid'] = $array_product[$row['productid']]['title'];
    $row['numbergift'] = number_format( $row['numbergift'], 0, '.', ',');
    $row['moneygift'] = number_format( $row['moneygift'], 0, '.', ',');
    $xtpl->assign('ROW', $row);
    $xtpl->assign('link_edit', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&id=" . $row['id'] . '&sid=' . $row['saleoffid']);
    $xtpl->assign('link_del', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&delete=1&id=" . $row['id']);

    $xtpl->parse('main.data.row');
    ++$count;
}

foreach ( $array_product as $product ){
    $product['sl'] = ( $data['productid'] == $product['id'])? ' selected=selected' : '';
    $xtpl->assign('PRODUCT', $product);
    $xtpl->parse('main.productid');
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
