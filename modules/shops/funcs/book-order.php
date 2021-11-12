<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if( !defined( 'NV_IS_MOD_SHOPS' ) )
{
    die( 'Stop!!!' );
}
if (!defined('NV_IS_USER') ) {
    $redirect = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;
    Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=login&nv_redirect=' . nv_redirect_encrypt($redirect));
    die();
}
if( $nv_Request->isset_request('setcart', 'get') )
{

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
    } else {
        if ($ac == 0) {
            if ($id > 0) {
                $result = $db->query("SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_rows WHERE id = " . $id);
                $data_content = $result->fetch();

                if ($num > $data_content['product_number'] and empty($pro_config['active_order_number'])) {
                    $contents_msg = 'ERR_' . $lang_module['cart_set_err_num'];
                } else {
                    $update_cart = true;
                    if (! isset($_SESSION[$module_data . '_cart'][$id.'_'.$group])) {
                        $_SESSION[$module_data . '_cart'][$id.'_'.$group] = array(
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
                        if (($_SESSION[$module_data . '_cart'][$id.'_'.$group]['num'] + $num) > $data_content['product_number'] and empty($pro_config['active_order_number'])) {
                            $contents_msg = 'ERR_' . $lang_module['cart_set_err_num'] . ': ' . $data_content['product_number'];
                            $update_cart = false;
                        } else {
                            $_SESSION[$module_data . '_cart'][$id.'_'.$group]['num'] = $_SESSION[$module_data . '_cart'][$id.'_'.$group]['num'] + $num;
                        }
                    }
                    if ($update_cart) {
                        $title = str_replace("_", "#@#", $data_content[NV_LANG_DATA . '_title']);
                        $contents = sprintf($lang_module['set_cart_success'], $title);
                        $contents_msg = 'OK_' . $contents;
                    }
                }
            }
        } else {
            if ($id > 0) {
                $result = $db->query("SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_rows WHERE id = " . $id);
                $data_content = $result->fetch();

                if ($num > $data_content['product_number'] and empty($pro_config['active_order_number'])) {
                    $contents_msg = 'ERR_' . $lang_module['cart_set_err_num'] . ': ' . $data_content['product_number'];
                } else {
                    if (isset($_SESSION[$module_data . '_cart'][$id.'_'.$group])) {
                        $_SESSION[$module_data . '_cart'][$id.'_'.$group]['num'] = $num;
                    }
                    $contents_msg = 'OK_' . $lang_module['cart_set_ok'] . $num;
                }
            }
        }
    }

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_unhtmlspecialchars($contents_msg);
    include NV_ROOTDIR . '/includes/footer.php';
    exit;
}

$order_info = array( );
$order_old = array( );

$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=';

$base_url_rewrite = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=book-order', true );


// Sửa đơn hàng
if( isset( $_SESSION[$module_data . '_order_info'] ) and !empty( $_SESSION[$module_data . '_order_info'] ) )
{
    $order_info = $_SESSION[$module_data . '_order_info'];
    $result = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_orders WHERE order_id=' . $order_info['order_id'] );

    if( $result->rowCount( ) == 0 )
    {
        unset( $_SESSION[$module_data . '_order_info'] );
        Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
        die( );
    }

    if( $_SESSION[$module_data . '_order_info']['checked'] )
    {
        $result = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_orders_id WHERE order_id=' . $order_info['order_id'] );

        while( $row = $result->fetch( ) )
        {
            $array_group = array( );
            $data_content = $db->query( "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_rows WHERE id = " . $row['id'] )->fetch( );
            $result_group = $db->query( "SELECT group_id FROM " . $db_config['prefix'] . "_" . $module_data . "_orders_id_group WHERE order_i = " . $row['id'] );
            while( list( $group_id ) = $result_group->fetch( 3 ) )
            {
                $array_group[] = $group_id;
            }
            $array_group = !empty( $array_group ) ? implode( ',', $array_group ) : '';
            $order_old[$row['proid'].'_'.$array_group] = array(
                'num' => $row['num'],
                'num_old' => $row['num'],
                'order' => 1,
                'price' => $row['price'],
                'money_unit' => $order_info['money_unit'],
                'discount_id' => $row['discount_id'],
                'group' => $array_group,
                'store' => $data_content['product_number'],
                'weight' => $data_content['product_weight'],
                'weight_unit' => $data_content['weight_unit']
            );
        }

        $shipping_old = array(
            'ship_name' => '',
            'ship_phone' => '',
            'ship_location_id' => 0,
            'ship_address_extend' => '',
            'ship_shops_id' => 0,
            'ship_carrier_id' => 0,
            'order_shipping' => 0
        );

        $result = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_orders_shipping WHERE order_id=' . $order_info['order_id'] );
        if( $result->rowCount( ) )
        {
            $shipping_old = $result->fetch( );
            $shipping_old['order_shipping'] = 1;
        }

        $_SESSION[$module_data . '_order_info']['checked'] = 0;
        $_SESSION[$module_data . '_order_info']['order_product'] = $order_old;
        $_SESSION[$module_data . '_order_info']['shipping'] = $shipping_old;
        $_SESSION[$module_data . '_cart'] = $order_old;
    }
}

if( $nv_Request->get_int( 'save', 'post', 0 ) == 1 )
{
    // Set cart to order
    $listproid = $nv_Request->get_array( 'listproid', 'post', '' );
    $coupons_code = $nv_Request->get_title( 'coupons_code', 'post', '' );
    if( !empty( $listproid ) )
    {
        foreach( $listproid as $pro_id => $number )
        {
            if( !empty( $_SESSION[$module_data . '_cart'][$pro_id] ) and $number >= 0 )
            {
                $_SESSION[$module_data . '_cart'][$pro_id]['num'] = $number;
            }
        }
    }
}

$data_content = array( );
$array_error_product_number = array( );


if( !empty( $_SESSION[$module_data . '_cart'] ) )
{
    $arrayid = array( );
    foreach( $_SESSION[$module_data . '_cart'] as $pro_id => $pro_info )
    {
        $array = explode( '_', $pro_id );
        if( $array[1] == '')
        {
            $sql = "SELECT t1.id, t1.catid, t1.listcatid, t1.publtime, t1." . NV_LANG_DATA . "_title, t1." . NV_LANG_DATA . "_alias, t1." . NV_LANG_DATA . "_hometext, t1.homeimgalt, t1.homeimgfile, t1.homeimgthumb, t1.product_number, t1.product_price, t1.discount_id, t2." . NV_LANG_DATA . "_title, t1.money_unit FROM " . $db_config['prefix'] . "_" . $module_data . "_rows AS t1, " . $db_config['prefix'] . "_" . $module_data . "_units AS t2 WHERE t1.product_unit = t2.id AND t1.id IN ('" . $array[0] . "') AND t1.status =1";
        }
        else
        {
            $sql = "SELECT t1.id, t1.catid, t1.listcatid, t1.publtime, t1." . NV_LANG_DATA . "_title, t1." . NV_LANG_DATA . "_alias, t1." . NV_LANG_DATA . "_hometext, t1.homeimgalt, t1.homeimgfile, t1.homeimgthumb, t1.product_number, t1.product_price, t1.discount_id, t2." . NV_LANG_DATA . "_title, t1.money_unit FROM " . $db_config['prefix'] . "_" . $module_data . "_rows AS t1, " . $db_config['prefix'] . "_" . $module_data . "_units AS t2, " . $db_config['prefix'] . "_" . $module_data . "_group_quantity t3 WHERE t1.product_unit = t2.id AND t1.id = t3.pro_id AND  t3.listgroup ='" . $array[1] . "' AND t1.id IN ('" . $array[0] . "') AND t1.status =1";
        }
        $result = $db->query( $sql );
        while( list( $id, $catid_i, $listcatid, $publtime, $title, $alias, $hometext, $homeimgalt, $homeimgfile, $homeimgthumb, $product_number, $product_price, $discount_id, $unit, $money_unit ) = $result->fetch( 3 ) )
        {
            if( $homeimgthumb == 1 )
            {
                //image thumb
                $thumb = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $homeimgfile;
            }
            elseif( $homeimgthumb == 2 )
            {
                //image file
                $thumb = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $homeimgfile;
            }
            elseif( $homeimgthumb == 3 )
            {
                //image url
                $thumb = $homeimgfile;
            }
            else
            {
                //no image
                $thumb = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/no-image.jpg';
            }

            $group = $_SESSION[$module_data . '_cart'][$id . '_' . $array[1]]['group'];
            $number = $_SESSION[$module_data . '_cart'][$id . '_' . $array[1]]['num'];

            if( !empty( $order_info ) )
            {
                $product_number = $product_number + (isset( $_SESSION[$module_data . '_cart'][$id . '_' . $array[1]]['num_old'] ) ? $_SESSION[$module_data . '_cart'][$id . '_' . $array[1]]['num_old'] : $_SESSION[$module_data . '_cart'][$id . '_' . $array[1]]['num']);
            }

            if( !empty( $group ) and $pro_config['active_warehouse'] )
            {
                $group = explode( ',', $group );
                asort( $group );
                $group = implode( ',', $group );
                $product_number = 1;
                $_result = $db->query( 'SELECT quantity FROM ' . $db_config['prefix'] . '_' . $module_data . '_group_quantity WHERE pro_id = ' . $id . ' AND listgroup="' . $group . '"' );
                if( $_result->rowCount( ) > 0 )
                {
                    $product_number = $_result->fetchColumn( );
                }
            }

            if( $number > $product_number and $number > 0 and empty( $pro_config['active_order_number'] ) )
            {
                $number = $_SESSION[$module_data . '_cart'][$id . '_' . $array[1]]['num'] = $product_number;
                $array_error_product_number[] = sprintf( $lang_module['product_number_max'], $title, $product_number );
            }

            if( $pro_config['active_price'] == '0' )
            {
                $discount_id = $product_price = 0;
            }

            $data_content[] = array(
                'id' => $id,
                'catid' => $catid_i,
                'listcatid' => $listcatid,
                'publtime' => $publtime,
                'title' => $title,
                'alias' => $alias,
                'hometext' => $hometext,
                'homeimgalt' => $homeimgalt,
                'homeimgthumb' => $thumb,
                'product_price' => $product_price,
                'discount_id' => $discount_id,
                'product_unit' => $unit,
                'money_unit' => $money_unit,
                'group' => $group,
                'link_pro' => $link . $global_array_shops_cat[$catid_i]['alias'] . '/' . $alias . $global_config['rewrite_exturl'],
                'num' => $number,
                'link_remove' => $link . 'remove&id=' . $id . '&book=1&group=' . $group
            );
            $_SESSION[$module_data . '_cart'][$id.'_'.$array[1]]['order'] = 1;
        }
        if( empty( $array_error_product_number ) and $nv_Request->isset_request( 'cart_order', 'post' ) )
        {
            Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=order', true ) );
            exit( );
        }
    }
}

$act = isset( $array_op[1] )? $array_op[1] : '';
$product_list = $agency_chossen = $array_agency = array();

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_agency WHERE status=1 ORDER BY weight";
$result = $db->query( $sql );
while( $row = $result->fetch( ) ) {
    if( $row['alias'] == $act ){
        $agency_chossen = $row;
    }
    $row['link'] = $link . $op . '/' . $row['alias'];
    $row['price_require_fomart'] = number_format( $row['price_require'], 0, '.', ',');
    $array_agency[] = $row;
}
$nv_Request->set_Session($module_data . '_agency_chossen', serialize( $agency_chossen ) );
$page_title = $lang_module['cart_title'];
if( !empty( $agency_chossen )){

    $db->sqlreset()->select( 'id, listcatid, ' . NV_LANG_DATA . '_title AS title, ' . NV_LANG_DATA . '_alias AS alias, product_number, product_price, money_unit, product_unit' )->from( $db_config['prefix'] . '_' . $module_data . '_rows' );
    $sql_where = 'status=1';

    $db->where( $sql_where );
    $sth = $db->prepare( $db->sql() );
    $sth->execute();

    while( $view = $sth->fetch() )
    {
        $view['price_agency'] = nv_get_price_agency( $view['product_price'], $view['money_unit'], $agency_chossen['percent_sale'], $view['num'], false );
        $view['product_price'] = nv_number_format($view['product_price'], nv_get_decimals($view['money_unit']));
        $view['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_shops_cat[$view['listcatid']]['alias'] . '/' . $view['alias'] . $global_config['rewrite_exturl'];
        $product_list[] = $view;
    }
}

$contents = call_user_func( 'book_product_agency', $data_content, $product_list, $order_info, $array_error_product_number, $act, $array_agency, $agency_chossen );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
