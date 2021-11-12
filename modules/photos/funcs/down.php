<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:30
 */

if (! defined('NV_IS_MOD_PHOTO')) {
    die('Stop!!!');
}

$row_id = $nv_Request->get_int( 'row_id', 'get', 0 );
$token = $nv_Request->get_title( 'token', 'get', '' );
if( $token != md5( NV_CHECK_SESSION . $row_id ) )
{
    die('Wrong URL');
}

$row = $db->query("SELECT * FROM " . TABLE_PHOTO_NAME . "_rows WHERE row_id=" . $row_id)->fetch();
if (empty($row)) {
    die('Wrong URL');
}

if (empty($row['file']) or ! file_exists( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_file . '/images/' . $row['file'] )) {
    die('Wrong URL');
}

$upload_dir = 'files';
$is_zip = false;
$is_resume = false;
$max_speed = 0;

$_setdown = $nv_Request->get_int($module_data . '_' . $op . '_setdown_' . $row_id, 'session');
if (empty($_setdown)) {
    $nv_Request->set_Session($module_data . '_' . $op . '_setdown_' . $row_id, NV_CURRENTTIME);
    $db->query('UPDATE ' . TABLE_PHOTO_NAME . '_rows SET download=download+1 WHERE row_id=' . $row['row_id']);
}


$file_src = NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_file . '/images/' . $row['file'];
$file_basename = $row['name'];
$directory = NV_UPLOADS_REAL_DIR;

if ($is_zip) {
    $subfile = nv_pathinfo_filename($filename);
    $tem_file = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . $subfile;

    $file_exists = file_exists($tem_file);

    if ($file_exists and filemtime($tem_file) > NV_CURRENTTIME - 600) {
        $file_src = $tem_file;
        $file_basename = $subfile . '.zip';
        $directory = NV_ROOTDIR . '/' . NV_TEMP_DIR;
    } else {
        if ($file_exists) {
            @nv_deletefile($tem_file);
        }

        $zip = new PclZip($tem_file);

        $paths = explode('/', $file_src);
        array_pop($paths);
        $paths = implode('/', $paths);
        $zip->add($file_src, PCLZIP_OPT_REMOVE_PATH, $paths);

        if (isset($global_config['site_logo']) and ! empty($global_config['site_logo']) and file_exists(NV_ROOTDIR . '/' . $global_config['site_logo'])) {
            $paths = explode('/', $global_config['site_logo']);
            array_pop($paths);
            $paths = implode('/', $paths);
            $zip->add(NV_ROOTDIR . '/' . $global_config['site_logo'], PCLZIP_OPT_REMOVE_PATH, NV_ROOTDIR . '/' . $paths);
        }

        if (file_exists(NV_ROOTDIR . '/' . NV_DATADIR . '/README.txt')) {
            $zip->add(NV_ROOTDIR . '/' . NV_DATADIR . '/README.txt', PCLZIP_OPT_REMOVE_PATH, NV_ROOTDIR . '/' . NV_DATADIR);
        }

        if (file_exists($tem_file)) {
            $file_src = $tem_file;
            $file_basename = $subfile . '.zip';
            $directory = NV_ROOTDIR . '/' . NV_TEMP_DIR;
        }
    }
}

$download = new NukeViet\Files\Download($file_src, $directory, $file_basename, $is_resume, $max_speed);
if ($is_zip) {
    $mtime = ($mtime = filemtime($session_files['fileupload'][$filename]['src'])) > 0 ? $mtime : NV_CURRENTTIME;
    $download->set_property('mtime', $mtime);
}
$download->download_file();
exit();