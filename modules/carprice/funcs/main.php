<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if (!defined('NV_IS_MOD_SM')) {
    die('Stop!!!');
}
$cache_file = '';
if (!defined('NV_IS_MODADMIN')) {
    $cache_file = NV_LANG_DATA . '_' . $module_info['template'] . '-' . $op . '-' . $page . '-' . NV_CACHE_PREFIX . '.cache';
    if (($cache = $nv_Cache->getItem($module_name, $cache_file, 3600)) != false) {
        $contents = $cache;
    }
}
if (empty($contents)) {
    $array_content_show = array();
    $array_location_fee_show = array();
    foreach ( $array_temcar as $temcar ){
        $array_content_show[$temcar['producerid']]['name'] = $array_producer[$temcar['producerid']]['title'];
        $array_content_show[$temcar['producerid']]['cars'][$temcar['id']]['car_id'] = $temcar['id'];
        $array_content_show[$temcar['producerid']]['cars'][$temcar['id']]['car_name'] = $temcar['title'];
        $array_content_show[$temcar['producerid']]['cars'][$temcar['id']]['car_type'] = $temcar['typecarid'];
        $array_content_show[$temcar['producerid']]['cars'][$temcar['id']]['car_brand'] = $array_producer[$temcar['producerid']]['title'];
        $array_content_show[$temcar['producerid']]['cars'][$temcar['id']]['car_price'] = $temcar['price_negotiate']/1000000;
        $array_content_show[$temcar['producerid']]['cars'][$temcar['id']]['car_image'] = empty( $temcar['image']  ) ? '' : NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $temcar['image'];
        $array_content_show[$temcar['producerid']]['cars'][$temcar['id']]['negotation_price'] = $temcar['price_negotiate'];
        $array_content_show[$temcar['producerid']]['cars'][$temcar['id']]['car_seats'] = $temcar['numseats'];
    }

    foreach ( $array_location_fee as $location_fee ){
        $array_location_fee_show[$location_fee['locationid']][$location_fee['typecarid']]['registration_fee'] = $location_fee['registration_fee'];
        $array_location_fee_show[$location_fee['locationid']][$location_fee['typecarid']]['license_plate_fee'] = $location_fee['license_plate_fee'];
    }

    $config_module = $module_config[$module_name];
    $contents = nv_carprice_main( $array_content_show, $array_location_fee_show, $config_module );
    if (!defined('NV_IS_MODADMIN') and $contents != '' and $cache_file != '') {
        $nv_Cache->setItem($module_name, $cache_file, $contents);
    }
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';