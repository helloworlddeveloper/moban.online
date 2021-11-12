<?php

/**
 * @Project PHOTOS 4.x
 * @Author KENNY NGUYEN (nguyentiendat713@gmail.com) 
 * @Copyright (C) 2015 tradacongnghe.com. All rights reserved
 * @Based on NukeViet CMS 
 * @License GNU/GPL version 2 or any later version
 * @Createdate  Fri, 18 Sep 2015 11:52:59 GMT
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if( ACTION_METHOD == 'delete' )
{
    $info = array();

    $row_id = $nv_Request->get_int( 'rowid', 'post', 0 );

    $data = $db->query( 'SELECT * FROM ' . TABLE_PHOTO_NAME . '_rows WHERE row_id=' . $row_id )->fetch( );
    if( $data['row_id'] > 0 )
    {
        if( $db->query( 'DELETE FROM ' . TABLE_PHOTO_NAME . '_rows WHERE row_id = ' . $row_id ) )
        {
            if( $data['status'] == 1 ){
                $db->query('UPDATE ' . TABLE_PHOTO_NAME . '_album SET num_photo = (SELECT COUNT(*) FROM ' . TABLE_PHOTO_NAME . '_rows WHERE album_id = '. $data['album_id'] .') WHERE album_id = '. $data['album_id'] );
            }

            @nv_deletefile( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/images/' . $data['file'] );
            @nv_deletefile( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/thumbs/' . $data['thumb'] );
            $nv_Cache->delMod( $module_name );
            $info['success'] = $lang_module['photo_success_delete'];
        }
    }else{
        $info['error'] = 'Khong tim thay anh de xoa';
    }

    header( 'Content-Type: application/json' );
    echo json_encode( $info );
    exit();
}
elseif( ACTION_METHOD == 'show' )
{
    $info = array();

    $row_id = $nv_Request->get_int( 'rowid', 'post', 0 );

    $data = $db->query( 'SELECT * FROM ' . TABLE_PHOTO_NAME . '_rows WHERE row_id=' . $row_id )->fetch( );
    if( $data['row_id'] > 0 )
    {

        if( $db->query( 'UPDATE ' . TABLE_PHOTO_NAME . '_rows SET status=1 WHERE row_id = ' . $row_id ) )
        {
            if( $data['status'] == 0 ){
                $db->query('UPDATE ' . TABLE_PHOTO_NAME . '_album SET num_photo = (SELECT COUNT(*) FROM ' . TABLE_PHOTO_NAME . '_rows WHERE album_id = '. $data['album_id'] .') WHERE album_id = '. $data['album_id'] );
            }
            $nv_Cache->delMod( $module_name );
            $info['success'] = $lang_module['photo_success_delete'];
        }
    }else{
        $info['error'] = 'Khong tim thay anh de duyet';
    }

    header( 'Content-Type: application/json' );
    echo json_encode( $info );
    exit();
}

$page = $nv_Request->get_int('page', 'get', 1);
$per_page = 30;
$array_photo = array();
$rowcontent['album_id'] = $nv_Request->get_int( 'album_id', 'get,post', 0 );
if( $rowcontent['album_id'] > 0 )
{
	$album = $db->query( 'SELECT * FROM ' . TABLE_PHOTO_NAME . '_album where album_id=' . $rowcontent['album_id'] )->fetch();

    if( $album['album_id'] > 0 )
    {
        $db_slave->sqlreset()
            ->select('COUNT(*)')
            ->from(TABLE_PHOTO_NAME . '_rows')
            ->where('status=0 AND album_id=' . $album['album_id']);

        $_sql = $db_slave->sql();
        $num_items = $db_slave->query($_sql)->fetchColumn();

        $db_slave->select('*')
            ->order('status ASC, date_modified DESC')
            ->limit($per_page)
            ->offset(($page - 1) * $per_page);
        $result = $db_slave->query($db_slave->sql());
        while ( $row = $result->fetch()) {
            $array_photo[] = $row;
        }
    }
}

$xtpl = new XTemplate( 'view.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=view&album_id=' . $rowcontent['album_id'];
if( !empty( $array_photo ) )
{
    foreach( $array_photo as $photo )
    {
        $photo['thumb'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/thumbs/' . $photo['thumb'];
        $photo['image_url'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/images/' . $photo['file'];
        $photo['status_title'] = $lang_module['status_' . $photo['status']];
        $photo['date_added'] = date('H:i - d/m/Y', $photo['date_added'] );
        $xtpl->assign( 'PHOTO', $photo );
        $xtpl->parse( 'main.photo.loop' );
    }
    $array_list_action = array(
        'delete' => $lang_global['delete'],
        'show' => $lang_module['show_image']
    );
    foreach ($array_list_action as $action_i => $title_i) {
        $action_assign = array(
            'value' => $action_i,
            'title' => $title_i
        );
        $xtpl->assign('ACTION', $action_assign);
        $xtpl->parse('main.photo.action');
    }
    $xtpl->parse( 'main.photo' );
    $generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);
    if( !empty( $generate_page )){
        $xtpl->assign( 'GENERATE_PAGE', $generate_page );
        $xtpl->parse( 'main.generate_page' );
    }
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
