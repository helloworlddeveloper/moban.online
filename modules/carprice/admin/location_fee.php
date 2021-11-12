<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}
$table_name = NV_PREFIXLANG . "_" . $module_data . "_location_fee";

$page_title = $lang_module['location_fee'];


if ($nv_Request->isset_request('savecat', 'post')) {
    $data['provinceid'] = $nv_Request->get_array('provinceid', 'post', array());
    $data['registration_fee'] = $nv_Request->get_array('registration_fee', 'post', array());
    $data['license_plate_fee'] = $nv_Request->get_array('license_plate_fee', 'post', array());
    try {
        foreach ($data['provinceid'] as $provinceid) {
            foreach ($array_typecar as $typecar) {
                if (isset($data['license_plate_fee'][$provinceid . '_' . $typecar['id']])) {
                    $registration_fee = $data['registration_fee'][$provinceid . '_' . $typecar['id']];
                    $license_plate_fee = $data['license_plate_fee'][$provinceid . '_' . $typecar['id']];
                    $license_plate_fee = floatval(preg_replace('/[^0-9\,]/', '', $license_plate_fee));

                    $db->query("DELETE FROM " . $table_name . " WHERE locationid=" . $provinceid . ' AND typecarid=' . $typecar['id']);
                    $db->query("INSERT INTO " . $table_name . " (locationid, typecarid, registration_fee, license_plate_fee) VALUES (" . intval($provinceid) . ", " . intval($typecar['id']) . ", " . intval($registration_fee) . ", " . intval($license_plate_fee) . ")");
                }
            }
        }
        $nv_Cache->delMod($module_name);
    }catch( PDOException $e )
    {
        trigger_error( $e->getMessage() );
        die( $e->getMessage() ); //Remove this line after checks finished
    }
}

$xtpl = new XTemplate($op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name );
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('OP', $op);

$result = $db->query("SELECT * FROM " . $table_name . " AS t1 RIGHT JOIN " . $db_config['prefix'] . "_location_province AS t2 ON t1.locationid=t2.provinceid ORDER BY t2.weight ASC");
$localtion = 0;
$array_data = array();
$total_typecarid = count( $array_typecar );
while ($row = $result->fetch()) {
    $array_data[$row['provinceid']]['info']['id'] = $row['provinceid'];
    $array_data[$row['provinceid']]['info']['title'] = $row['title'];
    foreach ($array_typecar as $typecar ) {
        if( $typecar['id'] == $row['typecarid'] ){
            $typecar['registration_fee'] = $row['registration_fee'];
            $typecar['license_plate_fee'] = $row['license_plate_fee'];
            $array_data[$row['provinceid']]['data'][$typecar['id']] = $typecar;
        }
    }
}

foreach ( $array_data as $province_data ){
    $xtpl->assign('PROVINCE', $province_data['info']);

    if( isset( $province_data['data'] )){
        foreach ($province_data['data'] as $typecar){
            $xtpl->assign('TYPECAR', $typecar);
            $xtpl->parse('main.loop.typecarid');
        }
    }else{
        foreach ($array_typecar as $typecar ) {
            $xtpl->assign('TYPECAR', $typecar);
            $xtpl->parse('main.loop.typecarid');
        }
    }

    $xtpl->parse('main.loop');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
