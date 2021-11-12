<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if( !defined( 'NV_IS_MOD_AFFILIATE' ) )
{
    die( 'Stop!!!' );
}
if (!defined('NV_IS_USER') ) {
    $redirect = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;
    Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=login&nv_redirect=' . nv_redirect_encrypt($redirect));
    die();
}

//chua phai trong he thong thi khong vao dc chuc nang nay
if( !isset( $list_userdata[$user_info['userid']] ))
{
    Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=users');
    die();
}

if( $nv_Request->isset_request('book_order', 'post,get') ){

    if (! isset($_SESSION[$module_data . '_cart'])) {
        $_SESSION[$module_data . '_cart'] = array();
    }

    $id = $nv_Request->get_int('id', 'post,get', 1);
    $group = $nv_Request->get_string('group', 'post,get', '');
    $num = $nv_Request->get_int('num', 'post,get', 1);
    $ac = $nv_Request->get_string('ac', 'post,get', 0);
    $contents_msg = "";

    if (! is_numeric($num) || $num < 0) {
        $contents_msg = 'ERR_' . $lang_module['cart_set_err'];
    } else if ($id > 0) {
        $result = $db->query("SELECT * FROM " . NV_TABLE_SHOPS . "_rows WHERE id = " . $id);
        $data_content = $result->fetch();

        $update_cart = true;
        if (! isset($_SESSION[$module_data . '_cart'][$id])) {
            $_SESSION[$module_data . '_cart'][$id] = array(
                'num' => $num,
                'order' => 0,
                'price' => $data_content['product_price'],
                'money_unit' => $data_content['money_unit'],
                'discount_id' => $data_content['discount_id'],
                'store' => $data_content['product_number'],
                'group' => $group,
                'weight' => $data_content['product_weight'],
                'weight_unit' => $data_content['weight_unit']
            );
        } else {
            $_SESSION[$module_data . '_cart'][$id]['num'] = $_SESSION[$module_data . '_cart'][$id]['num'] + $num;
        }
        if ($update_cart) {
            $title = str_replace("_", "#@#", $data_content[NV_LANG_DATA . '_title']);
            $contents = sprintf($lang_module['set_cart_success'], $title);
            $contents_msg = 'OK_' . $contents;
        }
    }

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_unhtmlspecialchars($contents_msg);
    include NV_ROOTDIR . '/includes/footer.php';
}


$agency_info = $list_userdata[$user_info['userid']];
$agency_chossen = array();
if( $agency_info['agencyid'] > 0 ){
    $agency_chossen = $array_agency[$agency_info['agencyid']];
}


$db->sqlreset()->select( 'id, catid, ' . NV_LANG_DATA . '_title AS title, ' . NV_LANG_DATA . '_alias AS alias, product_number, product_price, money_unit, product_unit' )->from( NV_TABLE_SHOPS. '_rows' );
$sql_where = 'status=1';

$db->where( $sql_where );
$sth = $db->prepare( $db->sql() );
$sth->execute();

while( $view = $sth->fetch() )
{
    $view['price_agency'] = nv_get_price_agency($view['product_price'], $agency_chossen['percent_sale'], $view['num'], false );
    $view['product_price'] = number_format($view['product_price'], 0, '.', ',');
    $view['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=shops&amp;' . NV_OP_VARIABLE . '=' . $global_array_shops_cat[$view['catid']]['alias'] . '/' . $view['alias'] . $global_config['rewrite_exturl'];
    $product_list[$view['id']] = $view;
}


if( !empty( $_SESSION[$module_data . '_cart'] ) )
{
    foreach( $_SESSION[$module_data . '_cart'] as $pro_id => $pro_info )
    {
        if( isset($product_list[$pro_id])){
            $data_content[] = $product_list[$pro_id];
        }
    }
}

$contents = call_user_func( 'book_product_agency', $product_list, $data_content, $agency_info, $agency_chossen );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
