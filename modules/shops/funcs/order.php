<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if (!defined('NV_IS_MOD_SHOPS')) {
    die('Stop!!!');
}

if (!defined('NV_IS_USER') and !$pro_config['active_guest_order']) {
    $redirect = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cart';
    Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=login&nv_redirect=' . nv_redirect_encrypt($redirect));
    die();
}
$_agency_chossen = $nv_Request->get_string($module_data . '_agency_chossen', 'session', array() );
if( !empty( $_agency_chossen )){
    $_agency_chossen = unserialize( $_agency_chossen );
}
$contents = '';

$link1 = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=';
$action = 0;
$post_order = $nv_Request->get_int('postorder', 'post', 0);
$order_info = array();
$error = array( );
$precode = $pro_config['format_order_id'];
$useri_ref = $nv_Request->get_int('affiliate_ref', 'session', 0 );
if( $useri_ref > 0 && $site_mods['affiliate'] ){
    $sql = "SELECT precode FROM " . $db_config['prefix'] . "_affiliate_users WHERE userid=" . $useri_ref;
    $result = $db->query($sql);
    list( $precode ) = $result->fetch(3);
    $precode = str_replace('%', 'S%', $precode);
}

$data_order = array(
    'user_id' => $useri_ref,
    'order_name' => isset($user_info['full_name']) ? $user_info['full_name'] : '',
    'order_email' => isset($user_info['email']) ? $user_info['email'] : '',
    'order_phone' => '',
    'order_note' => '',
    'admin_id' => 0,
    'shop_id' => 0,
    'who_is' => isset($user_info['userid'])? $user_info['userid'] : 0,
    'unit_total' => $pro_config['money_unit'],
    'order_total' => 0,
    'order_time' => NV_CURRENTTIME,
    'order_shipping' => 0,
    'shipping' => array(
                    'ship_name' => '',
                    'ship_phone' => '',
                    'ship_location_id' => 0,
                    'ship_address_extend' => '',
                    'ship_shops_id' => 0,
                    'ship_carrier_id' => 0,
                    'weight' => 0,
                    'weight_unit' => 'g' )
);

if (isset($_SESSION[$module_data . '_order_info']) and !empty($_SESSION[$module_data . '_order_info'])) {
    $order_info = $_SESSION[$module_data . '_order_info'];
    $data_order = array(
        'order_name' => $order_info['order_name'],
        'order_email' => $order_info['order_email'],
        'order_address' => $order_info['order_address'],
        'order_phone' => $order_info['order_phone'],
        'order_note' => $order_info['order_note'],
        'unit_total' => $order_info['unit_total'],
        'order_shipping' => $order_info['shipping']['order_shipping'],
        'shipping' => $order_info['shipping']
    );
}

$shipping_data = array( 'list_location' => array(), 'list_carrier' => array(), 'list_shops' => array() );

// Ma giam gia
$array_counpons = array( 'code' => '', 'discount' => 0, 'check' => 0 );
$counpons = array( 'id' => 0, 'total_amount' => 0, 'date_start' => 0, 'uses_per_coupon_count' => 0, 'uses_per_coupon' => 0, 'type' => 0, 'discount' => 0 );
if (isset($_SESSION[$module_data . '_coupons']['check']) and $_SESSION[$module_data . '_coupons']['check'] == 1 and isset($_SESSION[$module_data . '_coupons']['discount']) and $_SESSION[$module_data . '_coupons']['discount'] > 0) {
    $array_counpons = $_SESSION[$module_data . '_coupons'];
}
$total_coupons = 0;
if (!empty($array_counpons['code']) and $array_counpons['check']) {
    $result = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_coupons WHERE code = ' . $db->quote($array_counpons['code']));
    $counpons = $result->fetch();
    $result = $db->query('SELECT pid FROM ' . $db_config['prefix'] . '_' . $module_data . '_coupons_product WHERE cid = ' . $counpons['id']);
    while (list($pid) = $result->fetch(3)) {
        $counpons['product'][] = $pid;
    }
}

if ($post_order == 1) {
    $total = 0;
    $total_point = 0;
    $total_weight = 0;
    $total_weight_price = 0;
    $i = 0;
    $listid = $listnum = $listprice = $listgroup = $listid_old = $listnum_old = array();

    $total_point += intval($pro_config['point_new_order']);
    $total_old = $total;

    $data_order['order_name'] = nv_substr($nv_Request->get_title('order_name', 'post', '', 1), 0, 200);
    $data_order['order_email'] = nv_substr($nv_Request->get_title('order_email', 'post', '', 1), 0, 250);
    $data_order['order_phone'] = nv_substr($nv_Request->get_title('order_phone', 'post', '', 1), 0, 20);
    $data_order['order_address'] = nv_substr($nv_Request->get_title('order_address', 'post', '', 1), 0, 255);
    $data_order['order_note'] = nv_substr($nv_Request->get_title('order_note', 'post', '', 1), 0, 2000);
    $data_order['order_shipping'] = $nv_Request->get_int('order_shipping', 'post', 0);
    $check = $nv_Request->get_int('check', 'post', 0);
    $data_order['order_total'] = $total;

    if (empty($data_order['order_name'])) {
        $error['order_name'] = $lang_module['order_name_err'];
    }
    if (nv_check_valid_email($data_order['order_email']) != '') {
        $error['order_email'] = $lang_module['order_email_err'];
    }
    if (empty($data_order['order_phone'])) {
        $error['order_phone'] = $lang_module['order_phone_err'];
    }
    if ($data_order['order_shipping'] and empty($data_order['shipping']['ship_name'])) {
        $error['order_shipping_name'] = $lang_module['order_shipping_name_err'];
    }
    if ($data_order['order_shipping'] and empty($data_order['shipping']['ship_phone'])) {
        $error['order_shipping_phone'] = $lang_module['order_shipping_phone_err'];
    }
    if ($data_order['order_shipping'] and empty($data_order['shipping']['ship_address_extend'])) {
        $error['order_shipping_address_extend'] = $lang_module['shipping_address_extend_empty'];
    }
    if ($data_order['order_shipping'] and empty($data_order['shipping']['ship_carrier_id'])) {
        $error['shipping_carrier_chose'] = $lang_module['shipping_carrier_chose'];
    }

    if ($check == 0) {
        $error['order_check'] = $lang_module['order_check_err'];
    }

    if (empty($error) ) {
        if (!empty($order_info)) {
            // Sua don hang
            $sth = $db->prepare('UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_orders SET
			order_name = :order_name, order_email = :order_email,
			order_phone = :order_phone, order_address = :order_address, order_note = :order_note, order_total = ' . doubleval($data_order['order_total']) . ',
			unit_total = :unit_total, edit_time = ' . NV_CURRENTTIME . ' WHERE order_id=' . $order_info['order_id']);

            $sth->bindParam(':order_name', $data_order['order_name'], PDO::PARAM_STR);
            $sth->bindParam(':order_email', $data_order['order_email'], PDO::PARAM_STR);
            $sth->bindParam(':order_phone', $data_order['order_phone'], PDO::PARAM_STR);
            $sth->bindParam(':order_address', $data_order['order_address'], PDO::PARAM_STR);
            $sth->bindParam(':order_note', $data_order['order_note'], PDO::PARAM_STR);
            $sth->bindParam(':unit_total', $data_order['unit_total'], PDO::PARAM_STR);
            if ($sth->execute()) {
                $order_id = 1;
            }
        } else {

            //lay cookie lan truy cap cuoi cung cua user vao cac trang dai ly
            $mobilerefer = $_COOKIE['daily_client_access'];
            $userid_refer = 0;
            if( $mobilerefer > 0 ){
                $sql = 'SELECT userid FROM ' . $db_config['prefix'] . "_regsite WHERE mobile=" . $mobilerefer;
                list( $userid_refer ) = $db->query($sql)->fetch(3);
            }

            require NV_ROOTDIR . '/modules/sm/function.order.php';
            $order_id = book_order( $userid_refer, $data_order['order_name'], $data_order['order_email'], $data_order['order_phone'], $data_order['order_address'], $data_order['order_note'] );
        }

        if ($order_id > 0) {
            $checkss = md5($order_id . $global_config['sitekey'] . session_id());
            $review_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=payment&order_id=' . $order_id . '&checkss=' . $checkss;

            if (!empty($order_info)) {
                $order_id = $order_info['order_id'];
                $order_code2 = $order_info['order_code'];
                foreach ($order_info['order_product'] as $pro_id => $info) {
                	$array=explode('_', $pro_id);
                	$listid_old[]=$array[0];
                    $listnum_old[] = $info['num'];
                }
                // Xoa cac ban ghi san pham don hang cu
                $db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_orders_id WHERE order_id=' . $order_info['order_id']);
            }

            /*
            // Gui mail thong bao den nguoi quan ly shops
            $listmail_notify = nv_listmail_notify();
            if (!empty($listmail_notify)) {
                $email_contents_to_admin = call_user_func('email_new_order', $content, $data_order, $data_pro);
                nv_sendmail(array(
                    $global_config['site_name'],
                    $global_config['site_email']
                ), $listmail_notify, sprintf($email_title, $module_info['custom_title'], $data_order['order_code']), $email_contents_to_admin);
            }
            */

            // Chuyen trang xem thong tin don hang vua dat
            unset($_SESSION[$module_data . '_agency_chossen']);
            unset($_SESSION[$module_data . '_cart']);
            unset($_SESSION[$module_data . '_order_info']);
            unset($_SESSION[$module_data . '_coupons']);
			unset( $_SESSION[$module_data . '_point_payment_discount'] );
			unset( $_SESSION[$module_data . '_point_payment_uses'] );
            Header('Location: ' . $review_url);
            $action = 1;
        }
    }
}

// Lay dia diem
$sql = "SELECT id, parentid, title, lev FROM " . $db_config['prefix'] . '_' . $module_data . "_location ORDER BY sort ASC";
$result = $db->query($sql);
while (list($id_i, $parentid_i, $title_i, $lev_i) = $result->fetch(3)) {
    $xtitle_i = '';
    if ($lev_i > 0) {
        $xtitle_i .= '&nbsp;';
        for ($i = 1; $i <= $lev_i; $i++) {
            $xtitle_i .= '&nbsp;&nbsp;&nbsp;';
        }
    }
    $xtitle_i .= $title_i;
    $shipping_data['list_location'][$id_i] = array( 'id' => $id_i, 'parentid' => $parentid_i, 'title' => $xtitle_i );
}
$shipping_data['list_carrier'] = $array_carrier;
$shipping_data['list_shops'] = $array_shops;

if ($action == 0) {
    $page_title = $lang_module['cart_check_cart'];

    $i = 0;
    $arrayid = array( );

	foreach( $_SESSION[$module_data . '_cart'] as $pro_id => $pro_info )
	{
		$arrayid[] = $pro_id;
		$array=explode('_', $pro_id);
		if($array[1]=='')
		{
			$sql = "SELECT t1.id, t1.catid, t1.listcatid, t1.publtime, t1." . NV_LANG_DATA . "_title, t1." . NV_LANG_DATA . "_alias, t1." . NV_LANG_DATA . "_hometext, t1.homeimgalt, t1.homeimgfile, t1.homeimgthumb, t1.product_number, t1.product_price, t1.discount_id, t1.money_unit, t1.product_weight, t1.weight_unit, t2." . NV_LANG_DATA . "_title AS unit FROM " . $db_config['prefix'] . "_" . $module_data . "_rows AS t1, " . $db_config['prefix'] . "_" . $module_data . "_units AS t2 WHERE t1.product_unit = t2.id AND t1.id IN ('" . $array[0] . "') AND t1.status =1";

		}
		else {
			$sql = "SELECT t1.id, t1.catid, t1.listcatid, t1.publtime, t1." . NV_LANG_DATA . "_title, t1." . NV_LANG_DATA . "_alias, t1." . NV_LANG_DATA . "_hometext, t1.homeimgalt, t1.homeimgfile, t1.homeimgthumb, t1.product_number, t1.product_price, t1.discount_id, t1.money_unit, t1.product_weight, t1.weight_unit, t2." . NV_LANG_DATA . "_title AS unit FROM " . $db_config['prefix'] . "_" . $module_data . "_rows AS t1, " . $db_config['prefix'] . "_" . $module_data . "_units AS t2, " . $db_config['prefix'] . "_" . $module_data . "_group_quantity t3 WHERE t1.product_unit = t2.id AND t1.id = t3.pro_id AND  t3.listgroup ='".$array[1]."' AND t1.id IN ('" . $array[0] . "') AND t1.status =1";

		}
		$result = $db->query( $sql );
		$weight_total = 0;
		while( list( $id, $catid_i, $listcatid, $publtime, $title, $alias, $hometext, $homeimgalt, $homeimgfile, $homeimgthumb, $product_number, $product_price, $discount_id, $money_unit,  $product_weight, $weight_unit, $unit ) = $result->fetch( 3 ) )
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

			if( $pro_config['active_price'] == '0' )
			{
				$discount_id = $product_price = 0;
			}

			$num = $_SESSION[$module_data . '_cart'][$id.'_'.$array[1]]['num'];
			$weight_total += nv_weight_conversion( $product_weight, $weight_unit, $pro_config['weight_unit'], $num );

			$group = $_SESSION[$module_data . '_cart'][$id.'_'.$array[1]]['group'];

			$data_content[] = array(
                'id' => $id,
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
                'num' => $num
            );
			++$i;

		}
	}

    $data_order['weight_total'] = $weight_total;
    // Cảnh báo đang sửa đơn hàng
    if (isset($_SESSION[$module_data . '_order_info']) and !empty($_SESSION[$module_data . '_order_info'])) {
        $order_info = $_SESSION[$module_data . '_order_info'];
        $lang_module['order_submit_send'] = $lang_module['order_edit'];
    }

    if ($i == 0) {
        Header('Location: ' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cart', true));
        exit();
    } else {
    
        $contents = call_user_func('uers_order', $data_content, $data_order, $array_counpons['discount'], $order_info, $error, $_agency_chossen);
    }
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
