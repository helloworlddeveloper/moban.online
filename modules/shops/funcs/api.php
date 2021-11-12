<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if(! defined('NV_IS_MOD_SHOPS'))
{
    die('Stop!!!');
}

function nv_get_base_url($urlinfo)
{
    global $client_info;
    if(empty($urlinfo))
        $urlinfo = $client_info['selfurl'];
    $pu = parse_url($urlinfo);
    return $pu["host"];
}
function nvGetProductInfo( $prodctid )
{
    global $db, $db_config, $module_data, $module_upload, $module_file, $module_info, $module_name, $global_array_shops_cat;
    $sql = 'SELECT id, catid, publtime, ' . NV_LANG_DATA . '_title AS title, ' . NV_LANG_DATA . '_alias AS alias, ' . NV_LANG_DATA . '_hometext AS hometext, homeimgalt, homeimgfile, homeimgthumb, product_code, product_number, product_price, money_unit, discount_id, showprice,' . NV_LANG_DATA . '_gift_content, gift_from, gift_to FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE id=' . $prodctid;
    $data_product = $db->query( $sql) ->fetch();
    if( !empty( $data_product )){
        $price = nv_get_price($data_product['id'], $data_product['money_unit']);
        $data_product['price_info'] = $price;
    }
    if ($data_product['homeimgthumb'] == 1) {
        //image thumb
        $data_product['imgfile'] = NV_MY_DOMAIN . NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $data_product['homeimgfile'];
    } elseif ($data_product['homeimgthumb'] == 2) {
        //image file
        $data_product['imgfile'] = NV_MY_DOMAIN . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $data_product['homeimgfile'];
    } elseif ($data_product['homeimgthumb'] == 3) {
        //image url
        $data_product['imgfile'] = $data_product['homeimgfile'];
    } else {
        //no image
        $data_product['imgfile'] = NV_MY_DOMAIN . NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/no-image.jpg';
    }
    $data_product['link'] = NV_MY_DOMAIN . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_shops_cat[$data_product['catid']]['alias'] . '/' . $data_product['alias'] . $global_config['rewrite_exturl'], true);
    return $data_product;
}

$mydomain = $nv_Request->get_title('mydomain', 'get', '');
$domain_refer = nv_get_base_url($mydomain);

$array_domain_allow = explode(',', $module_config[$module_name]['domain_accept']);
if(! in_array($domain_refer, $array_domain_allow))
{
    die($domain_refer);
}
else
{
    $myFunction = $nv_Request->get_title('function', 'get', '');
    if($myFunction == 'nvGetProductInfo')
    {
        $arr_param = array(
            'mydomain' => $nv_Request->get_title('mydomain', 'get', ''),
            'function' => $myFunction,
            'productid' => $nv_Request->get_int('productid', 'get', 0)
        );
        $secure_code = $nv_Request->get_title('secure_code', 'get', '');
        $secure_code_check = md5(implode(' ', $arr_param) . ' ' . $module_config[$module_name]['apikey']);
        
        if($secure_code_check == $secure_code)
        {
            $data = call_user_func($myFunction, $arr_param['productid']);
            print_r(json_encode($data));
        }
    }

}
