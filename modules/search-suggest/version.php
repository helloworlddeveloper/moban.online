<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 05/07/2010 09:47
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$module_version = array(
    'name' => 'Search Suggets', // Tieu de module
    'modfuncs' => 'main', // Cac function co block
    'is_sysmod' => 1, // 1:0 => Co phai la module he thong hay khong
    'virtual' => 0, // 1:0 => Co cho phep ao hao module hay khong
    'version' => '4.3.00', // Phien ban cua modle
    'date' => 'Thu, 9 Nov 2017 09:00:00 GMT', // Ngay phat hanh phien ban
    'author' => 'Mr.A <anvh.ceo@gmail.com>', // Tac gia
    'note' => '', // Ghi chu
    'uploads_dir' => array(
        $module_upload
    )
);