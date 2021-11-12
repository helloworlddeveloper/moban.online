<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$id = $nv_Request->get_int('id', 'post,get', 0);
$serviceid = $nv_Request->get_int('serviceid', 'post,get', 0);
if( $serviceid == 0 ){
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=product');
}else{
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_service WHERE id=' . $serviceid;
    $data_service = $db->query($sql)->fetch();
    if (empty($data_service)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=product');
    }
}
$array_file = array();
if ($id) {
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $id;
    $row = $db->query($sql)->fetch();

    if (empty($row)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=diary&serviceid=' . $id);
    }

    $sql = 'SELECT id, filename, pathfile, filesize, filetype FROM ' . NV_PREFIXLANG . '_' . $module_data . '_file WHERE rid=' . $id;
    $array_file_old = $array_file = $db->query($sql)->fetchAll();
    $page_title = $lang_module['edit_diary'];
    $action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;serviceid=' . $serviceid . '&amp;id=' . $id;
} else {
    $page_title = $lang_module['add_diary'];
    $action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;serviceid=' . $serviceid;
}

$error = '';

if ($nv_Request->get_int('save', 'post') == '1') {
    $row['title'] = $nv_Request->get_title('title', 'post', '', 1);
    $row['description'] = $nv_Request->get_string('description', 'post', '');
    $row['timeuse'] = $nv_Request->get_title('timeuse', 'post', '' );
    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $row['timeuse'], $m)) {
        $row['timeuse'] = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
    } else {
        $row['timeuse'] = NV_CURRENTTIME;
    }

    // Xu ly file
    $file = $nv_Request->get_typed_array('file', 'post', 'string');

    $array_file = array( );
    foreach ($file as $file_i) {
        if (!nv_is_url($file_i) and file_exists(NV_DOCUMENT_ROOT . $file_i)) {
            $lu = strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/');
            $otherimage_i = substr($file_i, $lu);
            $pathinfo = pathinfo( $file_i );

           $file_info = nv_getFileInfo($pathinfo['dirname'], $pathinfo['basename']);

            $array_file[md5($otherimage_i)] = array( 'filename' => $file_info['name'], 'file' => $otherimage_i, 'filesize' => $file_info['filesize'], 'filetype' => $file_info['type']);
        }
    }

    if (empty($row['title'])) {
        $error = $lang_module['empty_title_diary'];
    } elseif (empty($row['description'])) {
        $error = $lang_module['empty_description_diary'];
    }  elseif (empty($array_file)) {
        $error = $lang_module['empty_image_diary'];
    }  elseif (empty($row['timeuse'])) {
        $error = $lang_module['empty_timeuse_diary'];
    } else {
        $status = 1;
        $is_update = $is_insert = 0;
        try {
            if (!$id) {
                $_sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '
                    (serviceid, timeuse, addtime, edittime, status, title, description) VALUES
                    (' . $serviceid . ',' . $row['timeuse'] . ', ' . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ', ' . $status . ', :title, :description)';

                $data_insert = array();
                $data_insert['title'] = $row['title'];
                $data_insert['description'] = $row['description'];

                $id = $db->insert_id( $_sql, 'id', $data_insert );
                if( $id  > 0){
                    $is_insert = 1;
                }
            }else {
                $_sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET serviceid=' . $serviceid . ', timeuse=' . $row['timeuse'] . ', edittime=' . NV_CURRENTTIME . ', status=' . $status . ', title=:title, description=:description WHERE id =' . $id;
                $sth = $db->prepare($_sql);
                $sth->bindParam(':title', $row['title'], PDO::PARAM_STR);
                $sth->bindParam(':description', $row['description'], PDO::PARAM_STR);

                $sth->execute();
                $is_update = 1;
            }
            if( $is_insert == 1 || $is_update == 1 ){
                if( !empty( $array_file ) )
                {
                    $array_file_del = $array_file_old;
                    foreach( $array_file as $file_info )
                    {
                        $add_file = 1;
                        if( $is_update == 1 ){
                            foreach ( $array_file_old as $key => $file_old ){
                                if( strcmp( $file_old['pathfile'], $file_info['file']) == 0 ){
                                    $add_file = 0;
                                    unset( $array_file_del[$key] );//loai bo file dc dung trong csdl
                                }
                            }
                        }else{
                            $add_file = 1;
                        }

                        if( $add_file == 1) {
                            $_sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_file (rid, filename, pathfile, filesize, filetype, totalview, status, add_time) VALUES (" . $id . ", :filename, :pathfile, :filesize, :filetype, 0, :status, " . NV_CURRENTTIME . ")";
                            $data_insert = array();
                            $data_insert['filename'] = $file_info['filename'];
                            $data_insert['pathfile'] = $file_info['file'];
                            $data_insert['filesize'] = $file_info['filesize'];
                            $data_insert['filetype'] = $file_info['filetype'];
                            $data_insert['status'] = $status;
                            $db->insert_id( $_sql, 'id', $data_insert );
                        }
                    }
                    //xoa cac file khi update sau khi da check xem con dung tiep hay khong
                    if( !empty( $array_file_del )){
                        foreach ( $array_file_del as $key => $file_old ){
                            $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_file WHERE id = ' . $file_old['id'] );
                        }
                    }
                }
            }
            $nv_Cache->delMod($module_name);
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=diary&serviceid=' .$serviceid );

        } catch (PDOException $e) {
            $error = $lang_module['errorsave'];
        }
    }
}

if( $row['timeuse'] > 0 ){
    $row['timeuse'] = date('d/m/Y', $row['timeuse'] );
}

$xtpl = new XTemplate('content.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('FORM_ACTION', $action);
$xtpl->assign('DATA', $row);
$xtpl->assign('UPLOADS_DIR_USER', NV_UPLOADS_DIR . '/' . $module_upload);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('MODULE_UPLOAD', $module_upload);
$xtpl->assign('CURRENT', NV_UPLOADS_DIR . '/' . $module_upload);
if ($error) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

// Other image
$items = 0;
if (!empty($array_file)) {
    foreach ($array_file as $file_info) {
        if (!empty($file_info['pathfile']) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $file_info['pathfile'])) {
            $file_i = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $file_info['pathfile'];
            $data_otherimage_i = array(
                'id' => $items,
                'value' => $file_i
            );
            $xtpl->assign('DATA_FILE', $data_otherimage_i);
            $xtpl->parse('main.otherfile');
            ++$items;
        }
    }
}
$xtpl->assign('FILE_ITEMS', $items);

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
