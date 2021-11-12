<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (! defined('NV_IS_MOD_AFFILIATE')) {
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

$error = '';

if (! empty($nv_Request->get_int('register', 'post', 0))) {
    $id = $nv_Request->get_int('id', 'post', 0);
    if ( $id > 0 ) {

        $db_slave->sqlreset()
            ->select('COUNT(*)')
            ->from(NV_PREFIXLANG . '_' . $module_data . '_productregister')
            ->where('userid = ' . $user_info['userid']);

        $num_items = $db_slave->query($db_slave->sql())->fetchColumn();
        if( $num_items > 0 ){
            if( $affiliate_config['register_product_type'] == 0 ){
                $db_slave->select('product_pedding');
            }else{
                $db_slave->select('product_allow');
            }

            list( $product_pedding ) = $db_slave->query($db_slave->sql())->fetch(3);
            if( !empty($product_pedding)){
                $product_pedding = explode(',', $product_pedding );
                $product_pedding[$id] = $id;
                $product_pedding = array_unique($product_pedding);
                $product_pedding = implode(',', $product_pedding );
            }else{
                $product_pedding = $id;
            }


            if( $affiliate_config['register_product_type'] == 0 ){
                $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_productregister SET product_pedding=:product_pedding WHERE userid=' . $user_info['userid']);
                $stmt->bindParam(':product_pedding', $product_pedding, PDO::PARAM_STR, strlen( $product_pedding ));
            }else{
                $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_productregister SET product_allow=:product_allow WHERE userid=' . $user_info['userid']);
                $stmt->bindParam(':product_allow', $product_pedding, PDO::PARAM_STR, strlen( $product_pedding ));
            }

            $stmt->execute();
        }else{
            if( $affiliate_config['register_product_type'] == 0 ) {
                $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_productregister (userid, product_pedding) VALUES( :userid, :product_pedding)');
                $stmt->bindParam(':userid', $user_info['userid'], PDO::PARAM_INT);
                $stmt->bindParam(':product_pedding', $id, PDO::PARAM_STR);
            }else{
                $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_productregister (userid, product_allow) VALUES( :userid, :product_allow)');
                $stmt->bindParam(':userid', $user_info['userid'], PDO::PARAM_INT);
                $stmt->bindParam(':product_allow', $id, PDO::PARAM_STR);
            }
            $stmt->execute();
        }
        if( $affiliate_config['register_product_type'] == 0 ){
            $content = 'OK_' . $lang_module['product_pending'];
        }else{
            $content = 'OK_' . $lang_module['product_allow'];
        }
    } else {
        $content = 'ERROR_' . $lang_module['error_product_name'];
    }

    exit($content);
}

// san pham da dang ky ban
$db_slave->sqlreset()
    ->select('*')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_productregister ')
    ->where('userid= ' . $user_info['userid']);
$result = $db_slave->query($db_slave->sql());
$data_productregister = $result->fetch();
if( empty( $data_productregister )){
    $data_productregister['product_allow'] = '';
    $data_productregister['product_pedding'] = '';
    $data_productregister['product_noallow'] =  '';
}
$array_search['status'] = $nv_Request->get_int('status', 'get', -1);
$array_search['keyword'] = $nv_Request->get_title('keyword', 'get', '');
$array_search['search'] = 0;
$sql_where = '';
if( $array_search['status'] == 0 ){
    $array_search['search'] = 1;
    $sql_where .= ' AND id NOT IN (0';
    if( !empty( $data_productregister['product_allow'] )){
        $sql_where .= ',' . $data_productregister['product_allow'];
    }
    if( !empty( $data_productregister['product_noallow'] )){
        $sql_where .= ',' . $data_productregister['product_noallow'];
    }
    if( !empty( $data_productregister['product_pedding'] )){
        $sql_where .= ',' . $data_productregister['product_pedding'];
    }
    $sql_where .= ')';
}elseif( $array_search['status'] == 1 ){
    $array_search['search'] = 1;
    $sql_where .= ' AND id IN (0';
    if( !empty( $data_productregister['product_allow'] )){
        $sql_where .= ',' . $data_productregister['product_allow'];
    }
    $sql_where .= ')';
}elseif( $array_search['status'] == 2 ){
    $array_search['search'] = 1;
    $sql_where .= ' AND id IN (0';
    if( !empty( $data_productregister['product_pedding'] )){
        $sql_where .= ',' . $data_productregister['product_pedding'];
    }
    $sql_where .= ')';
}
elseif( $array_search['status'] == 3 ){
    $array_search['search'] = 1;
    $sql_where .= ' AND id IN (0';
    if( !empty( $data_productregister['product_noallow'] )){
        $sql_where .= ',' . $data_productregister['product_noallow'];
    }
    $sql_where .= ')';
}
if( !empty( $array_search['keyword'] ) ){
    $array_search['search'] = 1;
    $sql_where .= " AND title LIKE '%" . $array_search['keyword'] . "%'";
    //die($sql_where);
}
$data_productregister['product_allow'] = explode(',', $data_productregister['product_allow'] );
$data_productregister['product_pedding'] = explode(',', $data_productregister['product_pedding'] );
$data_productregister['product_noallow'] = explode(',', $data_productregister['product_noallow'] );

//san pham cua website
$db_slave->sqlreset()
    ->select('COUNT(*)')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_product')
    ->where('status= 1' . $sql_where);

$num_items = $db_slave->query($db_slave->sql())->fetchColumn();
$db_slave->select('*')
    ->limit($per_page)
    ->offset(($page - 1) * $per_page);
$result = $db_slave->query($db_slave->sql());

$data_content = array();
while ( $row = $result->fetch()) {
    $data_content[] = $row;
}

$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;
$html_pages = nv_alias_page($page_title, $base_url, $num_items, $per_page, $page);
$contents = nv_affiliate_product( $data_content, $data_productregister, $array_search, $html_pages);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
