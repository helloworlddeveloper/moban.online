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
    'name' => 'Shops Manager',
    'modfuncs' => 'main,rss,book-order,return-order,order,or_view,print,warehouse_logs,doanhso,payment,return-or-view,importplan,sendsms,export',
    'is_sysmod' => 0,
    'virtual' => 1,
    'version' => '4.3.00',
    'date' => 'Thu, 9 Nov 2017 09:00:00 GMT',
    'author' => 'Mr.An <anvh.ceo@gmail.com>',
    'note' => '',
    'uploads_dir' => array(
        $module_upload
    )
);