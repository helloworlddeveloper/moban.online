<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['product'];


// Delete menu
if ($nv_Request->isset_request('delete', 'get,post')) {
    if (! defined('NV_IS_AJAX')) {
        die('Wrong URL');
    }
    $id = $nv_Request->get_int('id', 'post', 0);
    $listid = $nv_Request->get_title('listid', 'post', '');
    if( $id > 0){
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_product WHERE id = ' . $id);
        $nv_Cache->delMod($module_name);
    }else if( !empty( $listid )){
        $listid = $listid . '0';
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_product WHERE id IN( ' . $listid . ')');
        $nv_Cache->delMod($module_name);
    } else {
        die('NO_' . $id);
    }
    die('OK_' . $id);
}
// change_active menu
elseif ($nv_Request->isset_request('change_active', 'get,post')) {
    if (! defined('NV_IS_AJAX')) {
        die('Wrong URL');
    }
    $id = $nv_Request->get_int('id', 'post', 0);

    $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_product WHERE id=' . $id;
    $id = $db->query($sql)->fetchColumn();
    if (empty($id)) {
        die('NO_' . $id);
    }
    $new_status = $nv_Request->get_bool('new_status', 'post');
    $new_status = ( int )$new_status;

    $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_product SET status=' . $new_status . ' WHERE id=' . $id;
    $db->query($sql);
    $nv_Cache->delMod($module_name);

    exit( 'OK_' . $id);
}
elseif ($nv_Request->isset_request('reload', 'post,get')) {

    $mod_name = $nv_Request->get_title('module', 'post');

    $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_product WHERE module_name = ' . $db->quote( $mod_name ) );

        if (file_exists(NV_ROOTDIR . '/modules/' . $site_mods[$mod_name]['module_file']. '/affiliate-product.php')) {
            $mod_data = $site_mods[$mod_name]['module_data'];
            include NV_ROOTDIR . '/modules/' . $site_mods[$mod_name]['module_file']. '/affiliate-product.php';

            // Nap lai menu moi
            foreach ($array_item as $key => $item) {
                if( !empty( $item['title'] )){
                    $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_product (title, link, module_name, status) VALUES( :title, :link, :module_name, 1)');
                    $stmt->bindParam(':title', $item['title'], PDO::PARAM_STR);
                    $stmt->bindParam(':link', $item['link'], PDO::PARAM_STR);
                    $stmt->bindParam(':module_name', $mod_data, PDO::PARAM_STR);
                    $stmt->execute();
                }
            }
            die('OK_' . $lang_module['action_menu_reload_success']);
        }else{
            die('ERROR_' . $lang_module['action_menu_reload_error']);
        }
}

$error = '';
$savecat = 0;
$array_data = array();
$array_data['id'] = 0;
$array_data['id'] = $nv_Request->get_int('id', 'get', 0);
$caption = $lang_module['add_product'];

$savecat = $nv_Request->get_int('savecat', 'post', 0);
if (! empty($savecat)) {
    $array_data['id'] = $nv_Request->get_int('id', 'post', 0);
    $array_data['module_name']  = $nv_Request->get_title('module_name', 'post', '', 1);
    $array_data['title']  = $nv_Request->get_title('title', 'post', '', 1);
    $array_data['link']  = $nv_Request->get_title('link', 'post', '', 1);

    if ( $array_data['id'] == 0) {

        $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_product (title, link, module_name, status) VALUES( :title, :link, :module_name, 1)');
        $stmt->bindParam(':title', $array_data['title'], PDO::PARAM_STR);
        $stmt->bindParam(':link', $array_data['link'], PDO::PARAM_STR);
        $stmt->bindParam(':module_name', $array_data['module_name'], PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount()) {
            $nv_Cache->delMod($module_name);
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&module=' . $array_data['module_name']);
        } else {
            $error = $lang_module['errorsave'];
        }
    } else {
        $error = $lang_module['error_product_name'];
    }
}


$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('caption', $caption);
$xtpl->assign('DATA', $array_data);
$module = $nv_Request->get_title('module', 'get', '', 1);
$xtpl->assign('mod_name', $module);
if( empty( $module )){
    $show = 0;
    foreach ($site_mods as $mod_name => $site_info ){

        if( file_exists( NV_ROOTDIR . '/modules/' . $site_info['module_file'] . '/affiliate-product.php' )){
            $show = 1;
            $site_info['active'] = ' disabled="disabled" checked="checked"';
            $site_info['link'] = NV_MY_DOMAIN . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $mod_name, true);
            $site_info['url_title'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&module=' . $mod_name;
            $xtpl->assign('ROW', $site_info);
            $xtpl->assign('mod_name', $mod_name);
            $xtpl->parse('main.table.loop.reload');
            $xtpl->parse('main.table.loop');
        }
    }
    if( $show == 1 ){
        $xtpl->parse('main.table');
    }
}else{
    $page = $nv_Request->get_int('page', 'get', 1);
    $xtpl->assign('CAT_LIST', nv_show_product_list($module, $per_page, $page));
}

if( $array_data['id'] > 0 ){
    $xtpl->parse('main.data_title');
}

if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

$xtpl->parse('main.content');


$xtpl->parse('main');
$contents .= $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
