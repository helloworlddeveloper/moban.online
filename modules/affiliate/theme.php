<?php

/**
 * @Project NUKEVIET 4.x
 * @Author mynukeviet (contact@mynukeviet.com)
 * @Copyright (C) 2017 mynukeviet. All rights reserved
 * @Createdate Tue, 07 Nov 2017 07:57:51 GMT
 */

if (!defined('NV_IS_MOD_AFFILIATE')) die('Stop!!!');

/**
 * nv_theme_affiliate_main()
 * 
 * @param mixed $array_data
 * @return
 */
function nv_theme_affiliate_main($array_data, $arr_transaction, $search_per_page, $generate_page, $number_begin, $total_price, $search)
{
    global $global_config, $module_name, $module_file, $lang_module, $module_config, $module_info, $op;
    
    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
    $xtpl->assign('OP', $op );
    $xtpl->assign('SEARCH', $search );

    $xtpl->assign('sum', $total_price );
    foreach ($array_data as $element) {
        $xtpl->assign('stt', $number_begin ++);
        $xtpl->assign('CONTENT', $element);
        $xtpl->parse('main.loop');
    }

    foreach ($arr_transaction as $key => $val) {

        if ($search['transaction'] == $key) {
            $sl = "selected = \"selected\"";
        } else {
            $sl = "";
        }
        $xtpl->assign('sl_transaction', $sl);
        $xtpl->assign('key_transaction', $key);
        $xtpl->assign('val_transaction', $val);
        $xtpl->parse('main.looptransaction');
    }
    foreach ($search_per_page as $s_per_page) {
        $xtpl->assign('SEARCH_PER_PAGE', $s_per_page);
        $xtpl->parse('main.s_per_page');
    }
    if ($generate_page) {
        $xtpl->assign('PAGE', $generate_page);
        $xtpl->parse('main.page');
    }
    $xtpl->parse('main');
    return $xtpl->text('main');
}

function nv_affiliate_product($data_content, $data_productregister, $array_search, $html_pages)
{
    global $global_config, $module_name, $module_file, $lang_module, $array_search_status, $module_info, $user_info, $op;

    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . 'index.php');
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
    $xtpl->assign('OP', $op );
    $xtpl->assign('SEARCH', $array_search );

    if( empty( $data_content ) && $array_search['search'] == 0 ){
        $xtpl->parse('main.nocontent');
    }else{
        foreach ($data_content as $content ){
            $content['type_action'] = 0;
            if( in_array( $content['id'], $data_productregister['product_allow'] )){
                $content['type_action'] = 1;
                $content['status_product'] = $lang_module['product_allow'];
            }elseif( in_array( $content['id'], $data_productregister['product_pedding'] )){
                $content['type_action'] = 2;
                $content['status_product'] = $lang_module['product_pending'];
            }elseif( in_array( $content['id'], $data_productregister['product_noallow'] )){
                $content['status_product'] = $lang_module['product_noallow'];
            }
            $content['link_share'] = NV_MY_DOMAIN . nv_url_rewrite($content['link'], true) . '?ref=' . $user_info['userid'];
            $xtpl->assign('ROW', $content);
            if( $content['type_action'] == 1 ){
                $xtpl->parse('main.content.loop.allow_share');
            }else{
                $xtpl->parse('main.content.loop.noallow_share');
            }
            if( !empty( $content['status_product'] )){
                $xtpl->parse('main.content.loop.status_product');
            }else{
                $xtpl->parse('main.content.loop.register');
            }
            $xtpl->parse('main.content.loop');
        }
        foreach ($array_search_status as $status ){
            $status['selected'] = ( $array_search['status'] == $status['key'] )? ' selected=selected' : '';
            $xtpl->assign('STATUS', $status);
            $xtpl->parse('main.content.search_status');
        }

        if( !empty( $html_pages )){
            $xtpl->assign('GENERATE_PAGE', $html_pages);
            $xtpl->parse('main.content.generate_page');
        }
        $xtpl->parse('main.content');
    }
    $xtpl->parse('main');
    return $xtpl->text('main');
}

function nv_theme_affiliate_notice( $data_result, $return ){
    global $module_name, $module_file, $lang_module, $module_info, $op;

    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . 'index.php');
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
    $xtpl->assign('OP', $op );
    if( $return > 0 ){
        $xtpl->assign('back_edit', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&userid=' . $data_result['customer_id'] );
        $xtpl->assign('DATA', $data_result );
        $xtpl->assign('RETURN', $lang_module['return_account_alert_' . $return] );
        $xtpl->parse('main.return' . $return );
    }else{
        return '';
    }
    return $xtpl->text('main.return' . $return);
}

function nv_theme_affiliate_register( $array_data, $array_agency, $array_province, $agency_weight, $return, $error,$array_gender , $array_job, $array_istype ){
    global $global_config, $module_name, $module_file, $lang_module, $array_search_status, $module_info, $user_info, $op;

    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . 'index.php');
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
    $xtpl->assign('OP', $op );
    $xtpl->assign('DATA', $array_data );
    if( $return > 0 ){
        $xtpl->assign('RETURN', $lang_module['return_alert_' . $return] );
        $xtpl->parse('main.return');
    }
    if( !empty( $error )){
        $xtpl->assign('ERROR',implode('<br/>- ', $error));
        $xtpl->parse('main.error');
    }
    if( $array_data['photo_befor'] != '' )
    {
        $xtpl->parse( 'main.image_befor' );
    }
    if( $array_data['photo_after'] != '' )
    {
        $xtpl->parse( 'main.image_after' );
    }

    if( $array_data['gpkd'] != '' )
    {
        $xtpl->parse( 'main.image_gpkd' );
    }
    if( $array_data['photo_shops'] != '' )
    {
        $xtpl->parse( 'main.photo_shops' );
    }
    if( $array_data['photo_product_in_shops'] != '' )
    {
        $xtpl->parse( 'main.photo_product_in_shops' );
    }

    foreach ( $array_agency as $agency ){
        if( $agency_weight == 0 || $agency['weight'] <= $agency_weight ){
            $agency['sl'] = ($agency['id'] == $array_data['agencyid'])? ' selected=selected' : '';
           // $agency['agency_info'] = sprintf( $lang_module['chossen_agency_info_show'], number_format($agency['price_require'], 0, '.', ','), $agency['percent_sale'] . '%' );
            $xtpl->assign('AGENCY', $agency);
            $xtpl->parse('main.agency.loop');
        }
    }
    $xtpl->parse('main.agency');


    foreach( $array_province as $province )
    {
        $xtpl->assign( 'OPTION', array(
            'key' => $province['id'],
            'title' => $province['title'],
            'selected' => ( $province['id'] == $array_data['provinceid'] ) ? ' selected="selected"' : '' ) );
        $xtpl->parse( 'main.select_province' );
    }
    if( $array_data['provinceid'] > 0 ){
        $xtpl->parse('main.load_district');
    }

    if( $array_data['userid'] > 0 ){
        $xtpl->parse('main.not_edit_parent');
        $xtpl->parse('main.data_update');
    }else{
        $xtpl->parse('main.edit_parent');
        $xtpl->parse('main.data_send');
    }
    foreach( $array_gender as $key => $gender )
    {
        $xtpl->assign( 'OPTION', array(
            'key' => $key,
            'title' => $gender,
            'selected' => ( $key == $array_data['gender'] ) ? ' selected="selected"' : '' ) );
        $xtpl->parse( 'main.gender' );
    }
    foreach( $array_istype as $key => $istype )
    {
        $xtpl->assign( 'OPTION', array(
            'key' => $key,
            'title' => $istype,
            'selected' => ( $key == $array_data['istype'] ) ? ' selected="selected"' : '' ) );
        $xtpl->parse( 'main.istype' );
    }

    foreach( $array_job as $job )
    {
        $xtpl->assign( 'OPTION', array(
            'key' => $job['id'],
            'title' => $job['title'],
            'selected' => ( $job['id'] == $array_data['jobid'] ) ? ' selected="selected"' : '' ) );
        $xtpl->parse( 'main.job' );
    }
    $xtpl->parse('main');
    return $xtpl->text('main');
}


function nv_theme_affiliate_info( $array_data, $return ){
    global $global_config, $module_name, $module_file, $lang_module, $array_search_status, $module_info, $user_info, $op;

    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . 'index.php');
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
    $xtpl->assign('OP', $op );
    $xtpl->assign('DATA', $array_data );
    if( $return > 0 ){
        $xtpl->assign('RETURN', $lang_module['return_alert_' . $return] );
        $xtpl->parse('main.return');
    }

    if( $array_data['userid'] > 0 ){
        $xtpl->parse('main.not_edit_parent');
        $xtpl->parse('main.data_update');
    }else{
        $xtpl->parse('main.edit_parent');
        $xtpl->parse('main.data_send');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * cart_product()
 *
 * @param mixed $data_content
 * @param mixed $coupons_code
 * @param mixed $array_error_number
 * @return
 */
function book_product_agency( $product_list, $data_content, $agency_info, $agency_chossen )
{
    global $module_info, $lang_module, $module_config, $module_file, $module_name, $pro_config, $money_config, $global_array_group, $global_array_shops_cat;

    $lang_module['price_agency'] = sprintf( $lang_module['price_agency'], $agency_chossen['title']);
    $xtpl = new XTemplate('book-order.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('TEMPLATE', $module_info['template']);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);

        $agency_chossen['price_require_fomart'] = number_format( $agency_chossen['price_require'], 0, '.', ',');
        $xtpl->assign('AGENCY_CURRENT', $agency_chossen);
        /*
        if( $agency_chossen['number_sale'] > 0 ){
            $xtpl->assign('number_sale', sprintf( $lang_module['number_sale'], $agency_chossen['number_sale'], $agency_chossen['number_gift']));
            $xtpl->parse('main.agency.number_sale');
        }
        */

        $xtpl->assign('percent_sale', sprintf( $lang_module['percent_sale'], $agency_chossen['percent_sale']) . '%');
        $xtpl->parse('main.agency');
        if (!empty($product_list)) {
            $stt = 1;
            foreach ($product_list as $product ) {
                $product['stt'] = $stt++;
                $product['price_agency_format'] = number_format($product['price_agency'], 0, '.', ',');
                $xtpl->assign('PRODUCT', $product);
                $xtpl->parse('main.data_order_cart.product_list.loop');
            }
            $xtpl->parse('main.data_order_cart.product_list');
        }
        $price_total = 0;
        $point_total = 0;
        if (!empty($data_content)) {
            $j = 1;
            foreach ($data_content as $data_row) {
                $xtpl->assign('stt', $j);
                $xtpl->assign('id', $data_row['id']);
                $xtpl->assign('title_pro', $data_row['title']);
                $xtpl->assign('link_pro', $data_row['link_pro']);
                $xtpl->assign('img_pro', $data_row['homeimgthumb']);
                /*
                $number_gift = 0;
                if( $agency_chossen['number_sale'] > 0 ){
                    $number_gift = floor( $data_row['num'] / $agency_chossen['number_sale']) * $agency_chossen['number_gift'];
                }
                $xtpl->assign('pro_gift', $number_gift );
                */
print_r($data_row);die;
                $price = nv_get_price($data_row['id'], $pro_config['money_unit'], $data_row['num'], true);

                $price['price_agency'] = nv_get_price_agency( $price['sale'], $pro_config['money_unit'], $agency_chossen['percent_sale'], $data_row['num'], false );

                $xtpl->assign('PRICE', $price);
                //gia tien danh cho agency
                $price = nv_get_price_agency( $price['sale'], $pro_config['money_unit'], $agency_chossen['percent_sale'], $data_row['num'], true );

                $xtpl->assign('PRICE_TOTAL', $price);
                $xtpl->assign('pro_num', $data_row['num']);
                $xtpl->assign('link_remove', $data_row['link_remove']);
                $xtpl->assign('product_unit', $data_row['product_unit']);
                $xtpl->assign( 'list_group', $data_row['group'] );
                $xtpl->assign( 'list_group_id', str_replace(',', '_', $data_row['group']) );

                if ($pro_config['active_price'] == '1') {
                    $xtpl->parse('main.data_order_cart.rows.price2');
                    $xtpl->parse('main.data_order_cart.rows.price5');
                }

                $xtpl->parse('main.data_order_cart.rows');
                $price_total = $price_total + $price['sale'];
                $j++;
            }

            // Hien thi thong bao so diem sau khi hoan tat don hang
            if ($pro_config['point_active']) {
                $point_total += intval($pro_config['point_new_order']);
                if (defined('NV_IS_USER')) {
                    $xtpl->assign('point_note', sprintf($lang_module['point_cart_note_user'], $point_total));
                } else {
                    $redirect = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cart';
                    $login = NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=login&nv_redirect=' . nv_redirect_encrypt($redirect);
                    $xtpl->assign('point_note', sprintf($lang_module['point_cart_note_guest'], $point_total, $login));
                }
                $xtpl->parse('main.data_order_cart.point_note');
            }
        }

        if (!empty($array_error_number)) {
            foreach ($array_error_number as $title_error) {
                $xtpl->assign('ERROR_NUMBER_PRODUCT', $title_error);
                $xtpl->parse('main.data_order_cart.errortitle.errorloop');
            }
            $xtpl->parse('main.data_order_cart.errortitle');
        }

       // $xtpl->assign('price_total', nv_number_format($price_total, nv_get_decimals($pro_config['money_unit'])));
        $xtpl->assign('unit_config',$money_config[$pro_config['money_unit']]['symbol'] );
        $xtpl->assign('LINK_DEL_ALL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=remove');
        $xtpl->assign('LINK_CART', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=book-order');
        $xtpl->assign('LINK_PRODUCTS', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '');
        $xtpl->assign('link_order_all', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=order');

        if ($pro_config['active_price'] == '1') {
            $xtpl->parse('main.data_order_cart.price1');
            $xtpl->parse('main.data_order_cart.price3');
            $xtpl->parse('main.data_order_cart.price4');
            $xtpl->parse('main.data_order_cart.price6');
        }

        if (!empty($order_info)) {
            $xtpl->assign('EDIT_ORDER', sprintf($lang_module['cart_edit_warning'], $order_info['order_url'], $order_info['order_code'], $order_info['order_edit']));
            $xtpl->parse('main.data_order_cart.edit_order');
        }
        $xtpl->parse('main.data_order_cart');



    $xtpl->parse('main');
    return $xtpl->text('main');
}

function nv_affilate_maps( $array_data )
{
    global $module_info, $module_file, $array_agency, $array_possiton, $global_config, $module_name, $lang_module, $user_info, $array_province, $user_data_affiliate, $op;


    $link_warehouse = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=sm&' . NV_OP_VARIABLE . '=warehouse_logs&userid=';
    $link_doanhso = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=sm&' . NV_OP_VARIABLE . '=doanhso&userid=';
    $link_affiliate = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=';
    $link_export = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=export';
    $link_load_sub = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&loadsub=1';
    $xtpl = new XTemplate( 'maps.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
    $xtpl->assign( "LANG", $lang_module );
    $xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
    $xtpl->assign( 'TEMPLATE', $module_info['template'] );
    $xtpl->assign( 'module_file', $module_file );
    $xtpl->assign( 'user_info', $user_info );
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('OP', $op);


    $xtpl->assign( "LOADSUB", $link_load_sub );
    $array_data['postion'] = ( $array_data['agencyid']> 0 && isset( $array_agency[$array_data['agencyid']] ) )? $array_agency[$array_data['agencyid']]['title'] : $array_possiton[$array_data['possitonid']]['title'];
    $array_data['fullname'] = nv_show_name_user( $array_data['first_name'] , $array_data['last_name'] , $array_data['username']  );
    $array_data['province_name'] = isset( $array_province[$array_data['provinceid']] )? $array_province[$array_data['provinceid']]['title'] : '';

    $xtpl->assign( 'DATA_ROOT', $array_data );

    if( ! empty( $array_data['data'] ) )
    {

        foreach( $array_data['data'] as $data_i )
        {
            $data_i['postion'] = ( $data_i['agencyid']> 0 && isset( $array_agency[$data_i['agencyid']] ) )? $array_agency[$data_i['agencyid']]['title'] : $array_possiton[$data_i['possitonid']]['title'];
            $data_i['checkss'] = md5($data_i['userid'] . $global_config['sitekey'] . session_id());
            if( isset( $data_i['possitonid'] ) && $data_i['possitonid'] == 0){
                $data_i['link_edit'] = $link_affiliate . 'register&userid=' . $data_i['userid'] . '&checkss=' . md5($data_i['userid'] . $global_config['sitekey'] . session_id());
                $data_i['link_warehouse'] = $link_warehouse . $data_i['userid'] . '&checkss=' . md5($data_i['userid'] . $global_config['sitekey'] . session_id());
            }else{
                if( $user_info['userid'] == $data_i['userid'] ){
                    $data_i['link_edit'] = $link_affiliate . 'editinfo';
                }else{
                    $data_i['link_edit'] = $data_i['lang_edit'] = '';
                }

                $data_i['link_warehouse'] = $link_doanhso . $data_i['userid'] . '&checkss=' . md5($data_i['userid'] . $global_config['sitekey'] . session_id());
            }
            if( $data_i['numsubcat'] > 0 ){
                $data_i['hasnumsubcat'] = ' class=hasnumsubcat';
            }else{
                $data_i['hasnumsubcat'] = '';
            }

            $data_i['province_name'] = isset( $array_province[$data_i['provinceid']] )? $array_province[$data_i['provinceid']]['title'] : '';
            $data_i['fullname'] = nv_show_name_user( $data_i['first_name'] , $data_i['last_name'] , $data_i['username']  );
            $data_i['pendingdelete_text'] = date('H:i, d/m/Y', $data_i['pendingdelete'] );
            $xtpl->assign( "SUBITEM", $data_i );
            if( $user_data_affiliate['permission'] == 1 ){
                if( $data_i['status'] == 0 ){
                    $xtpl->parse( 'main.subitem.permission.active' );
                }
                if( $data_i['pendingdelete'] > 0 ){
                    $xtpl->parse( 'main.subitem.permission.pendingdelete' );
                }else{
                    $xtpl->parse( 'main.subitem.permission.nopendingdelete' );
                }
                $xtpl->parse( 'main.subitem.permission' );
            }

            $xtpl->parse( 'main.subitem' );
        }
    }

    $xtpl->parse( 'main' );
    $content_i = $xtpl->text( 'main' );
    return $content_i;
}


function nv_affilate_maps_sub( $array_data )
{
    global $module_info, $module_file, $array_agency, $array_possiton, $global_config, $module_name, $lang_module, $user_info, $array_province, $user_data_affiliate, $op;


    $link_warehouse = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=sm&' . NV_OP_VARIABLE . '=warehouse_logs&userid=';
    $link_doanhso = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=sm&' . NV_OP_VARIABLE . '=doanhso&userid=';
    $link_affiliate = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=';
    $link_export = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=export';
    $link_load_sub = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&loadsub=1';
    $xtpl = new XTemplate( 'maps.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );

    $xtpl->assign( "LANG", $lang_module );
    $xtpl->assign( "LOADSUB", $link_load_sub );

    if( ! empty( $array_data ) )
    {
        foreach( $array_data as $data_i )
        {
            $data_i['postion'] = ( $data_i['agencyid']> 0 && isset( $array_agency[$data_i['agencyid']] ) )? $array_agency[$data_i['agencyid']]['title'] : $array_possiton[$data_i['possitonid']]['title'];
            $data_i['checkss'] = md5($data_i['userid'] . $global_config['sitekey'] . session_id());
            if( isset( $data_i['possitonid'] ) && $data_i['possitonid'] == 0){
                $data_i['link_edit'] = '';
                $data_i['link_warehouse'] = $link_warehouse . $data_i['userid'] . '&checkss=' . md5($data_i['userid'] . $global_config['sitekey'] . session_id());
            }else{
                if( $user_info['userid'] == $data_i['userid'] ){
                    $data_i['link_edit'] = $link_affiliate . 'editinfo';
                }else{
                    $data_i['link_edit'] = $data_i['lang_edihasnumsubcatt'] = '';
                }

                $data_i['link_warehouse'] = $link_doanhso . $data_i['userid'] . '&checkss=' . md5($data_i['userid'] . $global_config['sitekey'] . session_id());
            }
            if( $data_i['numsubcat'] > 0 ){
                $data_i['hasnumsubcat'] = ' class=hasnumsubcat';
            }else{
                $data_i['hasnumsubcat'] = '';
            }
            $data_i['pendingdelete_text'] = date('H:i, d/m/Y', $data_i['pendingdelete'] );
            $data_i['province_name'] = isset( $array_province[$data_i['provinceid']] )? $array_province[$data_i['provinceid']]['title'] : '';
            $data_i['fullname'] = nv_show_name_user( $data_i['first_name'] , $data_i['last_name'] , $data_i['username']  );
            $xtpl->assign( "SUBITEM", $data_i );
            if( $user_data_affiliate['permission'] == 1 ){
                if( $data_i['status'] == 0 ){
                    $xtpl->parse( 'tree.subitem.permission.active' );
                }
                if( $data_i['pendingdelete'] > 0 ){
                    $xtpl->parse( 'tree.subitem.permission.pendingdelete' );
                }else{
                    $xtpl->parse( 'tree.subitem.permission.nopendingdelete' );
                }
                $xtpl->parse( 'tree.subitem.permission' );
            }
            $xtpl->parse( 'tree.subitem' );
        }
    }

    $xtpl->parse( 'tree' );
    $content_i = $xtpl->text( 'tree' );
    return $content_i;
}