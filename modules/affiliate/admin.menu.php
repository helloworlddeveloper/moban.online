<?php

/**
 * @Project NUKEVIET 4.x
 * @Author mynukeviet (contact@mynukeviet.com)
 * @Copyright (C) 2017 mynukeviet. All rights reserved
 * @Createdate Tue, 07 Nov 2017 07:57:51 GMT
 */

if (!defined('NV_ADMIN')) die('Stop!!!');

if(defined('NV_IS_SPADMIN')) {
    $submenu['main'] = $lang_module['main'];
    $submenu['transaction'] = $lang_module['transaction'];
    $submenu['users'] = $lang_module['users'];
    $submenu['usersp'] = $lang_module['usersp'];
    $submenu['agency'] = $lang_module['agency_title'];
    $submenu['possiton'] = $lang_module['possiton'];
    $submenu['jobs'] = $lang_module['jobs'];
    //$submenu['bonuses'] = $lang_module['bonuses'];
    $submenu['product'] = $lang_module['product'];
    $submenu['content-view'] = $lang_module['content_view'];
    $submenu['province'] = $lang_module['province'];
    $submenu['chart'] = $lang_module['chart'];
    $submenu['scanuser'] = $lang_module['scanuser'];
    $submenu['config'] = $lang_module['config'];
}else{
    $submenu['main'] = $lang_module['main'];
    $submenu['transaction'] = $lang_module['transaction'];
    $submenu['users'] = $lang_module['users'];
}