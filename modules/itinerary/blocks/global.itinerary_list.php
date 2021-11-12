<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sat, 10 Dec 2011 06:46:54 GMT
 */

if (! defined('NV_MAINFILE')) {
    die('Stop!!!');
}


if (! nv_function_exists('nv_itinerary_list_info')) {

    function nv_itinerary_list_info($module)
    {
        global $db, $site_mods, $global_config, $module_name, $lang_module;
        if (isset($site_mods[$module])) {
            if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $site_mods[$module]['module_file'] . '/block.itinerary_list.tpl')) {
                $block_theme = $global_config['module_theme'];
            } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/' . $site_mods[$module]['module_file'] . '/block.itinerary_list.tpl')) {
                $block_theme = $global_config['site_theme'];
            } else {
                $block_theme = 'default';
            }

            $sql = 'SELECT t1.*, t2.car_number_plate, t2.mobilephone, t2.number_seats  FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . ' AS t1 INNER JOIN ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_vehicle AS t2 ON t1.vehicle=t2.id WHERE t1.time_end>=' . NV_CURRENTTIME . ' ORDER BY t1.time_start ASC';

            $result = $db->query( $sql );
            $_array_itinerary = array();
            while ($row = $result->fetch()){
                $_array_itinerary[] = $row;
            }
            if (empty($_array_itinerary)) {
                return '';
            }

            if( $module_name != $module ){
                // Language
                if (file_exists(NV_ROOTDIR . '/modules/' . $site_mods[$module]['module_file'] . '/language/' . NV_LANG_DATA . '.php')) {
                    require_once NV_ROOTDIR . '/modules/' . $site_mods[$module]['module_file'] . '/language/' . NV_LANG_DATA . '.php';
                }
            }
            $array_localtion = array();
            $_sql = 'SELECT id,title FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_location';
            $_query = $db->query($_sql);
            while ($_row = $_query->fetch()) {
                $array_localtion[$_row['id']] = $_row;
            }

            $xtpl = new XTemplate('block.itinerary_list.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $site_mods[$module]['module_file']);
            $xtpl->assign('BLANG', $lang_module);
            $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
            $xtpl->assign('TEMPLATE', $block_theme);
            $xtpl->assign('MODULE', $module);
            $stt = 1;
            foreach ($_array_itinerary as $itinerary ) {
                $itinerary['stt'] = $stt++;
                $itinerary['time_start'] = (empty($itinerary['time_start'])) ? '' : nv_date('H:i d/m', $itinerary['time_start']);
                $itinerary['time_end'] = (empty($itinerary['time_end'])) ? '' : nv_date('H:i d/m', $itinerary['time_end']);
                $itinerary['localtion_start'] = $array_localtion[$itinerary['localtion_start']]['title'];
                $itinerary['localtion_end'] = $array_localtion[$itinerary['localtion_end']]['title'];
                $xtpl->assign('ITINERARY', $itinerary );
                $xtpl->parse('main.loop');
            }
            $xtpl->parse('main');
            return $xtpl->text('main');
        }
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_itinerary_list_info( $block_config['module'] );
}