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

if ($nv_Request->isset_request('delete_did', 'get') and $nv_Request->isset_request('delete_checkss', 'get')) {
    $did = $nv_Request->get_int('delete_did', 'get');
    $delete_checkss = $nv_Request->get_string('delete_checkss', 'get');
    if ($did > 0 and $delete_checkss == md5($did . NV_CACHE_PREFIX . $client_info['session_id'])) {
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_bonus_cumulative_direct  WHERE id = ' . $db->quote($did));
        Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
        die();
    }
}

$row = array();
$error = array();
$row['id'] = $nv_Request->get_int('id', 'post,get', 0);
if ($nv_Request->isset_request('submit', 'post')) {
    $row['agencyid'] = $nv_Request->get_int('agencyid', 'post', 0);
    $row['productid'] = $nv_Request->get_int('productid', 'post', 0);
    $row['begin_quantity'] = $nv_Request->get_int('begin_quantity', 'post', 0);
    $row['end_quantity'] = $nv_Request->get_int('end_quantity', 'post', 0);
    $row['percent'] = $nv_Request->get_float('percent', 'post', 0);
    $row['discount'] = $nv_Request->get_float('discount', 'post', 0);
    $row['bonus_productnum'] = $nv_Request->get_float('bonus_productnum', 'post', 0);

    /*
    if (empty($row['productid'])) {
        $error[] = $lang_module['error_required_productid'];
    }
    */
    if (empty($error)) {
        try {
            if (empty($row['id'])) {
                $row['add_time'] = NV_CURRENTTIME;
                $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_bonus_cumulative_direct (agencyid, productid, add_time, begin_quantity, end_quantity, percent, discount, bonus_productid, bonus_productnum) VALUES ( :agencyid, :productid, :add_time, :begin_quantity, :end_quantity, :percent, :discount, :bonus_productid, :bonus_productnum)');

                $stmt->bindParam(':add_time', $row['add_time'], PDO::PARAM_INT);
            } else {
                $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_bonus_cumulative_direct SET agencyid = :agencyid, productid = :productid, begin_quantity = :begin_quantity, end_quantity = :end_quantity, percent = :percent, discount = :discount, bonus_productid = :bonus_productid, bonus_productnum = :bonus_productnum WHERE id=' . $row['id']);
            }
            $stmt->bindParam(':agencyid', $row['agencyid'], PDO::PARAM_INT);
            $stmt->bindParam(':productid', $row['productid'], PDO::PARAM_INT);
            $stmt->bindParam(':begin_quantity', $row['begin_quantity'], PDO::PARAM_INT);
            $stmt->bindParam(':end_quantity', $row['end_quantity'], PDO::PARAM_INT);
            $stmt->bindParam(':percent', $row['percent'], PDO::PARAM_INT);
            $stmt->bindParam(':discount', $row['discount'], PDO::PARAM_INT);
            $stmt->bindParam(':bonus_productid', $row['productid'], PDO::PARAM_INT);
            $stmt->bindParam(':bonus_productnum', $row['bonus_productnum'], PDO::PARAM_INT);

            $exc = $stmt->execute();
            if ($exc) {
                $nv_Cache->delMod($module_name);
                Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
                die();
            }
        } catch (PDOException $e) {
            //die($e->getMessage());
            trigger_error($e->getMessage());
        }
    }
} elseif ($row['id'] > 0) {
    $row = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_bonus_cumulative_direct WHERE id=' . $row['id'])->fetch();
    if (empty($row)) {
        Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
        die();
    }
} else {
    $row['id'] = 0;
    $row['agencyid'] = 0;
    $row['productid'] = 0;
    $row['begin_quantity'] = '';
    $row['end_quantity'] = '';
    $row['percent'] = '';
    $row['discount'] = '';
    $row['bonus_productnum'] = '';
}

$productid = 0;
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
// Fetch Limit
$show_view = false;
if (!$nv_Request->isset_request('id', 'post,get')) {

    $show_view = true;
    $per_page = 10;
    $page = $nv_Request->get_int('page', 'post,get', 1);
    //$agencyid = $nv_Request->get_int('agencyid', 'get', 0);
    $productid = $nv_Request->get_int('productid', 'get', 0);
    $db->sqlreset()->select('COUNT(*)')->from('' . NV_PREFIXLANG . '_' . $module_data . '_bonus_cumulative_direct');
    if( $productid > 0 ){
        $db->where('productid=' . $productid);
        $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&productid=' . $productid;
    }
    $sth = $db->prepare($db->sql());
    $sth->execute();
    $num_items = $sth->fetchColumn();

    $db->select('*')->order('productid, agencyid, begin_quantity')->limit($per_page)->offset(($page - 1) * $per_page);
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
$xtpl->assign('CAPTION', ($row['id']) ? $lang_module['discount_edit'] : $lang_module['discount_add']);

if ($show_view) {

    $generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);
    if (!empty($generate_page)) {
        $xtpl->assign('NV_GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.view.generate_page');
    }

    while ($view = $sth->fetch()) {
        $view['agency'] = $view['agencyid'] != 0 ? $array_agency[$view['agencyid']]['title'] : $lang_module['discounts_all'];
        $view['product'] = $view['productid'] != 0 ? $array_product[$view['productid']]['title'] : $lang_module['discounts_all_product'];
        $view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $view['id'];
        $view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_did=' . $view['id'] . '&amp;delete_checkss=' . md5($view['id'] . NV_CACHE_PREFIX . $client_info['session_id']);
        $xtpl->assign('VIEW', $view);
        $xtpl->parse('main.view.loop');
    }

    foreach ( $array_agency as $agency ){
        $agency['sl'] = ( $agencyid == $agency['id'])? ' selected=selected' : '';
        $xtpl->assign('AGENCY', $agency);
        $xtpl->parse('main.view.sl_agencyid');
    }

    foreach ( $array_product as $product ){
        $product['sl'] = ( $productid == $product['id'])? ' selected=selected' : '';
        $xtpl->assign('PRODUCT', $product);
        $xtpl->parse('main.view.sl_productid');
    }
    $xtpl->parse('main.view');
}

foreach ( $array_product as $product ){
    $product['sl'] = ( $row['productid'] == $product['id'])? ' selected=selected' : '';
    $xtpl->assign('PRODUCT', $product);
    $xtpl->parse('main.productid');
}

foreach ( $array_agency as $agency ){
    $agency['sl'] = ( $row['agencyid'] == $agency['id'])? ' selected=selected' : '';
    $xtpl->assign('AGENCY', $agency);
    $xtpl->parse('main.agencyid');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['bonus_cumulative_direct'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
