<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:30
 */

if (! defined('NV_IS_MOD_VIDEOCLIPS')) {
    die('Stop!!!');
}

$id = $nv_Request->get_int( 'id', 'get', 0 );
$token = $nv_Request->get_title( 'token', 'get', '' );
if( $token != md5( NV_CHECK_SESSION . $id ) )
{
    die('Wrong URL');
}

$row = $db->query("SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_clip WHERE id=" . $id)->fetch();
if (empty($row)) {
    die('Wrong URL');
}

if (empty($row['internalpath']) or ! file_exists( NV_ROOTDIR . '/' . $row['internalpath'] )) {
    die('Wrong URL');
}
!empty($VideoData['internalpath']) ? NV_BASE_SITEURL . $VideoData['internalpath'] : $VideoData['externalpath'];
$is_resume = false;
$max_speed = 0;

$fileinfo = pathinfo( NV_ROOTDIR . '/' . $row['internalpath'] );

$file_src = NV_ROOTDIR . '/' . $row['internalpath'];
$file_basename = $row['title'] . '.' . $fileinfo['extension'];
$directory = NV_UPLOADS_REAL_DIR;

$download = new NukeViet\Files\Download($file_src, $directory, $file_basename, $is_resume, $max_speed);
$download->download_file();
exit();