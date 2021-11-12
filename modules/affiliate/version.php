<?php

/**
 * @Project NUKEVIET 4.x
 * @Author mynukeviet (contact@mynukeviet.com)
 * @Copyright (C) 2017 mynukeviet. All rights reserved
 * @Createdate Tue, 07 Nov 2017 07:57:51 GMT
 */

if (!defined('NV_MAINFILE')) die('Stop!!!');

$module_version = array(
    'name' => 'Affiliate',
    'modfuncs' => 'main,maps,product,register,editinfo,content-view,ngaycong,tinhcong,maps-search,scan-user,deleteuser',
    'change_alias' => 'main,maps,product',
    'submenu' => 'main',
    'is_sysmod' => 0,
    'virtual' => 1,
    'version' => '1.0.00',
    'date' => 'Tue, 7 Nov 2017 07:57:51 GMT',
    'author' => 'mynukeviet (contact@mynukeviet.com)',
    'uploads_dir' => array(
        $module_name
    ),
    'note' => ''
);