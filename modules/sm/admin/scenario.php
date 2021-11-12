<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 10 Jun 2014 02:22:18 GMT
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$config_discount = array();


// change status
if ($nv_Request->isset_request('change_status', 'post, get')) {
    $id = $nv_Request->get_int('id', 'post, get', 0);
    $content = 'NO_' . $id;

    $sql = 'SELECT proid, status FROM ' . NV_PREFIXLANG . '_' . $module_data . '_scenario_header WHERE id=' . $id;

    $row = $db->query($sql)->fetch();
    if (isset($row['status'])) {
        $status = ($row['status'] == 1) ? 0 : 1;
        if( $status == 1 ){
            $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_scenario_header SET status=0 WHERE proid=' . $row['proid'] );
        }
        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_scenario_header SET status=' . intval($status) . ' WHERE id=' . $id );
        $content = 'OK_' . $id;
    }
    nvUpdatemsQueueByHeader( $row['proid'] );//cap nhat lai tin nhan

    $nv_Cache->delMod($module_name);
    include NV_ROOTDIR . '/includes/header.php';
    echo $content;
    include NV_ROOTDIR . '/includes/footer.php';
    exit();
}
elseif ($nv_Request->isset_request('delete_did', 'get') and $nv_Request->isset_request('delete_checkss', 'get')) {
    $did = $nv_Request->get_int('delete_did', 'get');
    $delete_checkss = $nv_Request->get_string('delete_checkss', 'get');
    if ($did > 0 and $delete_checkss == md5($did . NV_CACHE_PREFIX . $client_info['session_id'])) {
        $db->exec("DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_message_queue WHERE sid=" . $id);
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_scenario_header  WHERE id = ' . $db->quote($did));
        Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
        die();
    }
}

$row = array();
$error = '';
$row['id'] = $nv_Request->get_int('id', 'post,get', 0);
if ($nv_Request->isset_request('submit', 'post')) {
    $row['proid'] = $nv_Request->get_int('proid', 'post', 0);
    $row['note'] = $nv_Request->get_string('note', 'post', '');

    if (empty($row['proid'])) {
        $error = $lang_module['error_required_productid_scenario'];
    }
    if (empty($error)) {
        try {
            if (empty($row['id'])) {
                $row['addtime'] = NV_CURRENTTIME;
                $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_scenario_header (proid, note, addtime, status) VALUES ( :proid, :note, :addtime, 0)');

                $stmt->bindParam(':addtime', $row['addtime'], PDO::PARAM_INT);
            } else {
                $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_scenario_header SET proid = :proid, note = :note WHERE id=' . $row['id']);
            }
            $stmt->bindParam(':proid', $row['proid'], PDO::PARAM_INT);
            $stmt->bindParam(':note', $row['note'], PDO::PARAM_STR);

            $exc = $stmt->execute();
            if ($exc) {
                $nv_Cache->delMod($module_name);
                Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
                die();
            }
        } catch (PDOException $e) {
            $error = $e->getMessage();
        }
    }
} elseif ($row['id'] > 0) {
    $row = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_scenario_header WHERE id=' . $row['id'])->fetch();
    if (empty($row)) {
        Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
        die();
    }
} else {
    $row['id'] = 0;
    $row['proid'] = 0;
}

$productid = 0;
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
// Fetch Limit
$show_view = false;
if (!$nv_Request->isset_request('id', 'post,get')) {

    $show_view = true;
    $per_page = 10;
    $page = $nv_Request->get_int('page', 'post,get', 1);
    $productid = $nv_Request->get_int('productid', 'get', 0);
    $db->sqlreset()->select('COUNT(*)')->from('' . NV_PREFIXLANG . '_' . $module_data . '_scenario_header');
    if( $productid > 0 ){
        $db->where('productid=' . $productid);
        $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&productid=' . $productid;
    }
    $sth = $db->prepare($db->sql());
    $sth->execute();
    $num_items = $sth->fetchColumn();

    $db->select('*')->order('addtime DESC')->limit($per_page)->offset(($page - 1) * $per_page);
    $sth = $db->prepare($db->sql());
    $sth->execute();
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('ROW', $row);
$xtpl->assign('CAPTION', ($row['id']) ? $lang_module['scenario_edit'] : $lang_module['scenario_add']);

if ($show_view) {
    $generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);
    if (!empty($generate_page)) {
        $xtpl->assign('NV_GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.view.generate_page');
    }

    while ($view = $sth->fetch()) {
        $view['product'] = $array_product[$view['proid']]['title'];
        $view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $view['id'];
        $view['link_list_scenario'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=list-scenario&amp;id=' . $view['id'];
        $view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_did=' . $view['id'] . '&amp;delete_checkss=' . md5($view['id'] . NV_CACHE_PREFIX . $client_info['session_id']);
        if ($view['status'] == 1) {
            $check = 'checked';
        } else {
            $check = '';
        }
        $xtpl->assign('CHECK', $check);
        $xtpl->assign('VIEW', $view);
        $xtpl->parse('main.view.loop');
    }

    foreach ( $array_product as $product ){
        $product['sl'] = ( $productid == $product['id'])? ' selected=selected' : '';
        $xtpl->assign('PRODUCT', $product);
        $xtpl->parse('main.view.sl_productid');
    }
    $xtpl->parse('main.view');
}
else{
    if( !empty( $error )){
        $xtpl->assign('ERROR', $error);
        $xtpl->parse('main.error');
    }
}
foreach ( $array_product as $product ){
    $product['sl'] = ( $row['proid'] == $product['id'])? ' selected=selected' : '';
    $xtpl->assign('PRODUCT', $product);
    $xtpl->parse('main.productid');
}


$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['scenario'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
