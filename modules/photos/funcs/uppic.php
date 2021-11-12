<?php

/**
 * @Project PHOTOS 4.x
 * @Author KENNY NGUYEN (nguyentiendat713@gmail.com)
 * @Copyright (C) 2015 tradacongnghe.com. All rights reserved
 * @Based on NukeViet CMS
 * @License GNU/GPL version 2 or any later version
 * @Createdate  Fri, 18 Sep 2015 11:52:59 GMT
 */

if( ! defined( 'NV_IS_MOD_PHOTO' ) ) die( 'Stop!!!' );



$page_title = $lang_module['album'];

if( ACTION_METHOD == 'deleterows' )
{
    $info = array();
    $album_id = $nv_Request->get_int( 'album_id', 'post', 0 );
    $row_id = $nv_Request->get_int( 'row_id', 'post', 0 );
    $token = $nv_Request->get_string( 'token', 'post', '' );
    $token_thumb = $nv_Request->get_string( 'token_thumb', 'post', '' );
    $token_image = $nv_Request->get_string( 'token_image', 'post', '' );
    $thumb = $nv_Request->get_string( 'thumb', 'post', '' );
    $image_url = $nv_Request->get_string( 'image_url', 'post', '' );
    if(  $token == md5( $global_config['sitekey'] . session_id() . $row_id ) )
    {
        $data = $db->query( 'SELECT * FROM ' . TABLE_PHOTO_NAME . '_rows WHERE row_id=' . $row_id . ' AND album_id=' . $album_id )->fetch( );
        if( $data['row_id'] > 0 )
        {
            if( $db->query( 'DELETE FROM ' . TABLE_PHOTO_NAME . '_rows WHERE row_id = ' . $row_id . ' AND album_id=' . $album_id ) )
            {
                $db->query('UPDATE ' . TABLE_PHOTO_NAME . '_album SET num_photo = (SELECT COUNT(*) FROM ' . TABLE_PHOTO_NAME . '_rows WHERE album_id = '. $data['album_id'] .') AND author=' . $global_config['iddomain'] . ' WHERE album_id = '. $data['album_id'] );
                @nv_deletefile( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/images/' . $data['file'] );
                @nv_deletefile( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/thumbs/' . $data['thumb'] );
                $nv_Cache->delMod( $module_name );
                $info['success'] = $lang_module['photo_success_delete'];
            }
        }
    }
    elseif( empty( $row_id ) AND  $token_image == md5( $global_config['sitekey'] . session_id() . $image_url ) AND $token_thumb == md5( $global_config['sitekey'] . session_id() . $thumb ) )
    {
        @nv_deletefile( NV_ROOTDIR . $thumb );
        @nv_deletefile( NV_ROOTDIR . $image_url );
        $info['success'] = $lang_module['photo_success_delete'];
    }
    else
    {
        $info['error'] = $lang_module['photo_error_delete'];
    }

    header( 'Content-Type: application/json' );
    echo json_encode( $info );
    exit();

}

$db->sqlreset()
    ->select( 'COUNT(*)' )
    ->from( TABLE_PHOTO_NAME . '_category' );
$find_cate = $db->query( $db->sql() )->fetchColumn();

if( ACTION_METHOD == 'add' || ACTION_METHOD == 'edit'  )
{
    if( empty($find_cate))
    {
        Header( 'Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&op=category' );
        die();
    }

    $array_structure_image = array();
    $array_structure_image[''] = $module_upload . '/images';
    $array_structure_image['Y'] = $module_upload . '/images/' . date( 'Y' );
    $array_structure_image['Ym'] = $module_upload . '/images/' . date( 'Y_m' );
    $array_structure_image['Y_m'] = $module_upload . '/images/' . date( 'Y/m' );
    $array_structure_image['Ym_d'] = $module_upload . '/images/' . date( 'Y_m/d' );
    $array_structure_image['Y_m_d'] = $module_upload . '/images/' . date( 'Y/m/d' );

    $structure_upload = isset( $module_config[$module_name]['structure_upload'] ) ? $module_config[$module_name]['structure_upload'] : 'Ym';
    $currentpath = isset( $array_structure_image[$structure_upload] ) ? $array_structure_image[$structure_upload] : '';

    if( file_exists( NV_UPLOADS_REAL_DIR . '/' . $currentpath ) )
    {
        $upload_real_dir_page = NV_UPLOADS_REAL_DIR . '/' . $currentpath;
    }
    else
    {
        $upload_real_dir_page = NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/images';
        $e = explode( '/', $currentpath );
        if( ! empty( $e ) )
        {
            $cp = '';
            foreach( $e as $p )
            {
                if( ! empty( $p ) and ! is_dir( NV_UPLOADS_REAL_DIR . '/' . $cp . $p ) )
                {
                    $mk = nv_mkdir( NV_UPLOADS_REAL_DIR . '/' . $cp, $p );
                    if( $mk[0] > 0 )
                    {
                        $upload_real_dir_page = $mk[2];
                        $db->query( "INSERT IGNORE INTO " . NV_UPLOAD_GLOBALTABLE . "_dir (dirname, time) VALUES ('" . NV_UPLOADS_DIR . "/" . $cp . $p . "', 0)" );
                    }
                }
                elseif( ! empty( $p ) )
                {
                    $upload_real_dir_page = NV_UPLOADS_REAL_DIR . '/' . $cp . $p;
                }
                $cp .= $p . '/';
            }
        }
        $upload_real_dir_page = str_replace( '\\', '/', $upload_real_dir_page );
    }

    $currentpath = str_replace( NV_ROOTDIR . '/', '', $upload_real_dir_page );
    $imagepath = str_replace( NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/images/', '', $upload_real_dir_page );

    //Folder thumb
    $array_structure_thumb = array();
    $array_structure_thumb[''] = $module_upload . '/thumbs';
    $array_structure_thumb['Y'] = $module_upload . '/thumbs/' . date( 'Y' );
    $array_structure_thumb['Ym'] = $module_upload . '/thumbs/' . date( 'Y_m' );
    $array_structure_thumb['Y_m'] = $module_upload . '/thumbs/' . date( 'Y/m' );
    $array_structure_thumb['Ym_d'] = $module_upload . '/thumbs/' . date( 'Y_m/d' );
    $array_structure_thumb['Y_m_d'] = $module_upload . '/thumbs/' . date( 'Y/m/d' );

    $structure_upload = isset( $module_config[$module_name]['structure_upload'] ) ? $module_config[$module_name]['structure_upload'] : 'Ym';
    $currentpaththumb = isset( $array_structure_thumb[$structure_upload] ) ? $array_structure_thumb[$structure_upload] : '';

    if( file_exists( NV_UPLOADS_REAL_DIR . '/' . $currentpaththumb ) )
    {
        $upload_real_dir_page = NV_UPLOADS_REAL_DIR . '/' . $currentpaththumb;
    }
    else
    {
        $upload_real_dir_page = NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/images';
        $e = explode( '/', $currentpaththumb );
        if( ! empty( $e ) )
        {
            $cp = '';
            foreach( $e as $p )
            {
                if( ! empty( $p ) and ! is_dir( NV_UPLOADS_REAL_DIR . '/' . $cp . $p ) )
                {
                    $mk = nv_mkdir( NV_UPLOADS_REAL_DIR . '/' . $cp, $p );
                    if( $mk[0] > 0 )
                    {
                        $upload_real_dir_page = $mk[2];
                        $db->query( "INSERT IGNORE INTO " . NV_UPLOAD_GLOBALTABLE . "_dir (dirname, time) VALUES ('" . NV_UPLOADS_DIR . "/" . $cp . $p . "', 0)" );
                    }
                }
                elseif( ! empty( $p ) )
                {
                    $upload_real_dir_page = NV_UPLOADS_REAL_DIR . '/' . $cp . $p;
                }
                $cp .= $p . '/';
            }
        }
        $upload_real_dir_page = str_replace( '\\', '/', $upload_real_dir_page );
    }

    $currentpaththumb = str_replace( NV_ROOTDIR . '/', '', $upload_real_dir_page );

    $data = array(
        'album_id' => 0,
        'category_id' => 0,
        'name' => '',
        'alias' => '',
        'description' => '',
        'meta_title' => '',
        'meta_description' => '',
        'meta_keyword' => '',
        'model' => '',
        'capturedate' => 0,
        'capturelocal' => '',
        'folder' => '',
        'thumb' => '',
        'layout' => 'default',
        'num_photo' => 0,
        'viewed' => 0,
        'weight' => '',
        'allow_rating' => 1,
        'total_rating' => 0,
        'click_rating' => 0,
        'status' => 1,
        'groups_view' => 6,
        'allow_comment' => 6,
        'date_added' => NV_CURRENTTIME,
        'date_modified' => NV_CURRENTTIME,
        'albums' => array(),
    );
    $error = array();

    $data['album_id'] = $nv_Request->get_int( 'album_id', 'get,post', 0 );
    if( $data['album_id'] > 0 )
    {
        $data = $db->query( 'SELECT * FROM ' . TABLE_PHOTO_NAME . '_album  WHERE album_id=' . $data['album_id'] )->fetch();
        $data['old_category_id'] = $data['category_id'];
        $folder = explode('/', $data['folder']);
        $data['folder'] = end( $folder );

        $array_photo = $db->query( 'SELECT * FROM ' . TABLE_PHOTO_NAME . '_rows WHERE author = ' . $global_config['iddomain']. ' AND album_id=' . $data['album_id'] )->fetchAll();
        foreach( $array_photo as $photo )
        {
            $data['albums'][] =  array(
                'row_id'=> $photo['row_id'],
                'token'=>  md5( $global_config['sitekey'] . session_id() . $photo['row_id'] ),
                'token_image'=> '',
                'token_thumb'=> '',
                'basename'=> '',
                'filePath'=> '',
                'thumb'=> NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/thumbs/' . $photo['thumb'],
                'image_url'=> NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/images/' . $photo['file'],
                'name'=> $photo['name'],
                'description'=> $photo['description'],
            );
        }
        $caption = $lang_module['album_edit'] . ': ' . $data['name'];
    }
    else
    {
        $caption = $lang_module['album_add'];
    }

    if( $nv_Request->get_int( 'save', 'post' ) == 1 )
    {

        $data['albums'] = $nv_Request->get_typed_array( 'albums', 'post', '', array() );

        $array_checkPath = array();
        $array_checkPath[''] = '';
        $array_checkPath['Y'] =  date( 'Y' );
        $array_checkPath['Ym'] =  date( 'Y_m' );
        $array_checkPath['Y_m'] =  date( 'Y/m' );
        $array_checkPath['Ym_d'] =  date( 'Y_m/d' );
        $array_checkPath['Y_m_d'] =  date( 'Y/m/d' );

        $folderPath = isset( $module_config[$module_name]['structure_upload'] ) ? $module_config[$module_name]['structure_upload'] : 'Ym';
        $check_path = isset( $array_checkPath[$folderPath] ) ? $array_checkPath[$folderPath] : '';

        $_nb = $db->query( 'SELECT COUNT(*) FROM ' . TABLE_PHOTO_NAME . '_album WHERE album_id != ' . $data['album_id'] . ' AND folder=' . $db->quote($check_path . '/' . $data['folder']) )->fetchColumn();
        if( ! empty( $_nb ) ){
            $nb = $db->query( 'SELECT MAX(album_id) FROM ' . TABLE_PHOTO_NAME . '_album' )->fetchColumn();
            $data['folder'] .= '-' . ( intval( $nb ) + 1 );
        }
        if( !empty( $data['folder'] ) AND ! is_dir( NV_ROOTDIR . '/' . $currentpath . '/'. $data['folder'] ) )
        {
            $mkdir = nv_mkdir( NV_ROOTDIR . '/' . $currentpath, $data['folder'] );
            if( $mkdir[0] == 0 )
            {
                $error['warning'] = $lang_module['album_error_create_folder'];
            }else
            {
                $db->query( "INSERT IGNORE INTO " . NV_UPLOAD_GLOBALTABLE . "_dir (dirname, time) VALUES ('" . $currentpath . '/'. $data['folder'] . "', 0)" );
            }
        }

        $mime = nv_parse_ini_file( NV_ROOTDIR . '/includes/ini/mime.ini', true );

        try
        {
            $count = 0;
            foreach( $data['albums'] as $key => $photo )
            {
                $photo['row_id'] = isset( $photo['row_id'] ) ? $photo['row_id'] : 0;
                $photo['name'] = isset( $photo['name'] ) ? $photo['name'] : '';
                $photo['filePath'] = isset( $photo['filePath'] ) ? $photo['filePath'] : $photo['filePath'];
                $photo['image_url'] = isset( $photo['image_url'] ) ? $photo['image_url'] : $photo['image_url'];
                $photo['thumb'] = isset( $photo['thumb'] ) ? $photo['thumb'] : $photo['thumb'];

                $photo['description'] = isset( $photo['description'] ) ? $photo['description'] : '';
                $photo['description'] = nv_nl2br( nv_htmlspecialchars( strip_tags( $photo['description'] ) ), '<br />' );
                $photo['defaults'] = isset( $photo['defaults'] ) ? $photo['defaults'] : 0;

                if( $photo['row_id'] == 0 )
                {
                    // Kiem tra anh hop le
                    $image_info = nv_is_image( NV_ROOTDIR . '/' . NV_TEMP_DIR . '/'  . $photo['basename'] );

                    if( empty( $image_info ) or ! isset( $mime['images'][$image_info['ext']] ) )
                    {
                        $error['error_image'][] = $lang_module['album_error_mime'] . ' ' . $photo['basename'];

                        @nv_deletefile( NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $photo['basename'] );

                        unset( $data['albums'][$key] );
                    }
                    else
                    {
                        $photo['width'] = $image_info['width'];
                        $photo['height'] = $image_info['height'];
                        $photo['mime'] = $image_info['mime'];
                        $photo['size'] = filesize( $image_info['src'] );
                    }

                    $folder_album = NV_ROOTDIR . '/' . $currentpath . '/'. $data['folder'];

                    if( is_dir( $folder_album ) )
                    {
                        // Copy file anh goc
                        $basename = basename( $photo['basename'] );
                        $basename2 = $basename;
                        $i = 1;
                        while ( file_exists( NV_ROOTDIR . '/' . $currentpath . '/' . $basename2 ) )
                        {
                            $basename2 = preg_replace( '/(.*)(\.[a-zA-Z0-9]+)$/', '\1_' . $i . '\2', $basename );
                            ++$i;
                        }
                        $basename = $basename2;
                        $filePath = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $photo['basename'];
                        $newFilePath = $folder_album . '/' . strtolower ($basename);

                        $rename = nv_copyfile( $filePath, $newFilePath );

                        if( file_exists($newFilePath) OR $rename ){
                            // Xoa anh tam
                            @nv_deletefile( $filePath );

                            $photo['file'] = substr( $newFilePath, strlen( NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/images/' ) );

                            // Copy file thumb
                            //$thum_folder  = floor( $data['album_id'] / 1000 );
                            $thumbName = $fileName = substr( $photo['thumb'], strlen( NV_BASE_SITEURL . NV_TEMP_DIR . '/' ) );
                            $fileName2 = $fileName;
                            $i = 1;
                            while ( file_exists( NV_ROOTDIR . '/' . $currentpaththumb . '/' .  $fileName2 ) )
                            {
                                $fileName2 = preg_replace( '/(.*)(\.[a-zA-Z0-9]+)$/', '\1_' . $i . '\2', $fileName );
                                ++$i;
                            }
                            $fileName = $fileName2;
                            $filePath = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $thumbName;
                            $newFilePath = NV_ROOTDIR . '/' . $currentpaththumb . '/' . $fileName;

                            $rename = nv_copyfile( $filePath, $newFilePath );
                            if( ! $rename )
                            {
                                $error .= $lang_module['album_error_copy_photo'] . basename( $filePath ) ;
                                unset( $data['albums'][$key] );
                            }
                            else
                            {
                                // Xoa anh tam
                                @nv_deletefile( $filePath );
                                $photo['thumb'] = substr( $newFilePath, strlen( NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/thumb/' ) );

                                $sth = $db->prepare( 'INSERT INTO ' . TABLE_PHOTO_NAME . '_rows SET 
													album_id = ' . (int)$data['album_id'] . ', 
													size = ' . (int)$photo['size'] . ', 
													width = ' . (int)$photo['width'] . ', 
													height = ' . (int)$photo['height'] . ', 
													status=0, 
													date_added=' . intval( NV_CURRENTTIME ) . ',  
													date_modified=' . intval( NV_CURRENTTIME ) . ', 
													author=' . intval( $global_config['iddomain'] ) . ',
													download=0,
													name = :name,
													description = :description,
													file = :file,
													thumb = :thumb,
													mime = :mime' );

                                $sth->bindParam( ':name', $photo['name'], PDO::PARAM_STR );
                                $sth->bindParam( ':description', $photo['description'], PDO::PARAM_STR );
                                $sth->bindParam( ':file', $photo['file'], PDO::PARAM_STR );
                                $sth->bindParam( ':thumb', $photo['thumb'], PDO::PARAM_STR );
                                $sth->bindParam( ':mime', $photo['mime'], PDO::PARAM_STR );
                                $sth->execute();
                                $sth->closeCursor();
                                ++$count;
                            }
                        }else{
                            $error['warning'] = $lang_module['album_error_copy_photo'] . basename( $filePath );
                            unset( $data['albums'][$key] );
                        }
                    }
                }
                else
                {
                    $sth = $db->prepare( 'UPDATE ' . TABLE_PHOTO_NAME . '_rows SET 
                            name = :name,
                            description = :description 
                            WHERE row_id=' . $photo['row_id'] );

                    $sth->bindParam( ':name', $photo['name'], PDO::PARAM_STR );
                    $sth->bindParam( ':description', $photo['description'], PDO::PARAM_STR );
                    $sth->execute();
                    $sth->closeCursor();
                    ++$count;
                }
            }
        }
        catch ( PDOException $e )
        {
            $error['warning'] = $lang_module['album_error_save'];
        }

        if( empty( $error ) )
        {
            $nv_Cache->delMod( $module_name );
            Header( 'Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=uppic&action=view&album_id=' . $data['album_id'] );
            die();
        }
    }

    $data['capturedate'] = !empty( $data['capturedate'] ) ? date('d/m/Y', $data['capturedate']) : '';

    $xtpl = new XTemplate('album_add.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_file']);
    $xtpl->assign( 'LANG', $lang_module );
    $xtpl->assign( 'GLANG', $lang_global );
    $xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
    $xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
    $xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
    $xtpl->assign( 'THEME', $global_config['site_theme'] );
    $xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
    $xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
    $xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
    $xtpl->assign( 'MODULE_FILE', $module_file );
    $xtpl->assign( 'MODULE_NAME', $module_name );
    $xtpl->assign( 'OP', $op );
    $xtpl->assign( 'CAPTION', $caption );
    $xtpl->assign( 'DATA', $data );
    $xtpl->assign( 'MAXUPLOAD', $module_config[$module_name]['maxupload'] );
    $xtpl->assign( 'ORIGIN_WIDTH', $module_config[$module_name]['origin_size_width'] );
    $xtpl->assign( 'ORIGIN_HEIGHT', $module_config[$module_name]['origin_size_height'] );
    $xtpl->assign( 'CANCEL', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
    $xtpl->assign( 'UPLOAD_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=upload&token=' . md5( $nv_Request->session_id . $global_config['sitekey'] ) );

    $num_row = 0;
    if( !empty( $data['albums'] ) )
    {
        foreach( $data['albums'] as $key => $photo )
        {
            $photo['key'] = $key;
            $xtpl->assign( 'PHOTO', $photo );
            $xtpl->parse( 'main.photo' );

            ++$num_row;
        }
    }
    $xtpl->assign( 'num_row', $num_row );

    if( empty( $data['alias'] ) )
    {
        $xtpl->parse( 'main.getalias' );
    }
    if(isset($module_config[$module_name]['origin_size_width']) AND isset($module_config[$module_name]['origin_size_height']) AND ($module_config[$module_name]['origin_size_width'] > 0) AND ($module_config[$module_name]['origin_size_height'] > 0))
    {
        $xtpl->parse( 'main.resize_at_browser' );
    }
    $xtpl->parse( 'main' );
    $contents = $xtpl->text( 'main' );
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme( $contents );
    include NV_ROOTDIR . '/includes/footer.php';

    exit();
}
if( ACTION_METHOD == 'view' )
{
    $data['album_id'] = $nv_Request->get_int( 'album_id', 'get,post', 0 );
    $array_photo  = array();
    if( $data['album_id'] > 0 )
    {
        $data = $db->query( 'SELECT * FROM ' . TABLE_PHOTO_NAME . '_album  WHERE album_id=' . $data['album_id'] )->fetch();
        $array_photo = $db->query( 'SELECT * FROM ' . TABLE_PHOTO_NAME . '_rows WHERE author = ' . $global_config['iddomain'] . ' AND album_id=' . $data['album_id'] . ' ORDER BY status ASC, date_modified DESC' )->fetchAll();
    }

    $xtpl = new XTemplate('view.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_file']);
    $xtpl->assign( 'LANG', $lang_module );
    $xtpl->assign( 'GLANG', $lang_global );
    $xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
    $xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
    $xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
    $xtpl->assign( 'THEME', $global_config['site_theme'] );
    $xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
    $xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
    $xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
    $xtpl->assign( 'MODULE_FILE', $module_file );
    $xtpl->assign( 'MODULE_NAME', $module_name );
    $xtpl->assign( 'OP', $op );
    $xtpl->assign( 'DATA', $data );

    $num_row = 0;
    $data['description'] = htmlspecialchars( nv_editor_br2nl( $data['description'] ) );

    if( !empty( $array_photo ) )
    {
        foreach( $array_photo as $photo )
        {
            $photo['thumb'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/thumbs/' . $photo['thumb'];
            $photo['image_url'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/images/' . $photo['file'];
            $photo['status_title'] = $lang_module['status_' . $photo['status']];
            $photo['date_modified'] = date('d/m/Y', $photo['date_modified'] );
            $xtpl->assign( 'PHOTO', $photo );
            $xtpl->parse( 'main.photo' );
        }
    }
    $xtpl->assign( 'num_row', $num_row );

    $xtpl->parse( 'main' );
    $contents = $xtpl->text( 'main' );
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme( $contents );
    include NV_ROOTDIR . '/includes/footer.php';

    exit();
}
if( ACTION_METHOD == 'get_album' )
{
    $name = $nv_Request->get_string( 'filter_name', 'get', '' );
    $info = array();

    $and = '';
    if( ! empty( $name ) )
    {
        $and .= ' AND name LIKE :name ';
    }

    $sql = 'SELECT album_id, name FROM ' . TABLE_PHOTO_NAME . '_album  
	WHERE 1 ' . $and . '
	ORDER BY name DESC LIMIT 0, 10';

    $sth = $db->prepare( $sql );

    if( ! empty( $name ) )
    {
        $sth->bindValue( ':name', '%' . $name . '%' );
    }
    $sth->execute();
    while( list( $album_id, $name ) = $sth->fetch( 3 ) )
    {
        $info[] = array( 'album_id' => $album_id, 'name' => nv_htmlspecialchars( $name ) );
    }
    header( 'Content-Type: application/json' );
    echo json_encode( $info );
    exit();
}

//show list album

$per_page = 50;

$page = $nv_Request->get_int( 'page', 'get', 1 );

$data['filter_status'] = $nv_Request->get_string( 'filter_status', 'get', '' );
$data['filter_name'] = strip_tags( $nv_Request->get_string( 'filter_name', 'get', '' ) );
$data['filter_date_added'] = $nv_Request->get_string( 'filter_date_added', 'get', '' );
$data['filter_category'] = $nv_Request->get_int( 'filter_category', 'get', 0 );

$sort = $nv_Request->get_string( 'sort', 'get', '' );
$order = $nv_Request->get_string( 'order', 'get' ) == 'desc' ? 'desc' : 'asc';

$sql = TABLE_PHOTO_NAME . '_album WHERE 1';

if( ! empty( $data['filter_name'] ) )
{
    $sql .= " AND name LIKE '" . $db->dblikeescape( $data['filter_name'] ) . "%'";
}

if( $data['filter_category'] > 0 )
{
    $sql .= " AND category_id = " . ( int )$data['filter_category'];
}

if( isset( $data['filter_status'] ) AND is_numeric( $data['filter_status'] ) )
{
    $sql .= " AND status = " . ( int )$data['filter_status'];
}

if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $data['filter_date_added'], $m ) )
{
    $date_added_start = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
    $date_added_end = $date_added_start + 86399;

    $sql .= " AND date_added BETWEEN " . $date_added_start . " AND " . $date_added_end . "";
}
$sort_data = array( 'name', 'category_id', 'date_added' );
if( isset( $sort ) AND in_array( $sort, $sort_data ) )
{

    $sql .= " ORDER BY " . $sort;
}
else
{
    $sql .= " ORDER BY date_added";
}

if( isset( $order ) AND ( $order == 'desc' ) )
{
    $sql .= " DESC";
}
else
{
    $sql .= " ASC";
}

$num_items = $db->query( 'SELECT COUNT(*) FROM ' . $sql )->fetchColumn();

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=uppic&amp;sort=' . $sort . '&amp;order=' . $order . '&amp;per_page=' . $per_page;

$db->sqlreset()->select( '*' )->from( $sql )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );

$result = $db->query( $db->sql() );

$array = array();
while( $rows = $result->fetch() )
{
    $array[] = $rows;
}

$xtpl = new XTemplate('uppic.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_file']);
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'THEME', $global_config['site_theme'] );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'MODULE_FILE', $module_file );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'DATA', $data );
$xtpl->assign( 'MAXUPLOAD', $module_config[$module_name]['maxupload'] );
$xtpl->assign( 'TOKEN', md5( $global_config['sitekey'] . session_id() ) );
$xtpl->assign( 'URL_SEARCH', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&action=get_album' );

$order2 = ( $order == 'asc' ) ? 'desc' : 'asc';
$xtpl->assign( 'URL_NAME', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=name&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_WEIGHT', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=weight&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_category', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=category_id&amp;order=' . $order2 . '&amp;per_page=' . $per_page );

$xtpl->assign( 'ADD_NEW', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&action=add" );

/*search*/

foreach( $global_photo_cat as $key => $value )
{
    $xtitle_i = '';
    if( $value['lev'] > 0 )
    {
        $xtitle_i .= '&nbsp;&nbsp;&nbsp;|';
        for( $i = 1; $i <= $value['lev']; ++$i )
        {
            $xtitle_i .= '---';
        }
        $xtitle_i .= '>&nbsp;';
    }
    $xtitle_i .= $value['name'];
    $xtpl->assign( 'category', array( 'key'=> $key, 'name'=> $xtitle_i, 'selected'=> ( $key == $data['filter_category'] ) ? 'selected="selected"': '' ) );
    $xtpl->parse( 'main.filter_category' );

}

if( ! empty( $array ) )
{
    foreach( $array as $item )
    {

        $sql = 'SELECT userid, username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE active=1 AND userid= '. $item['author'];
        $array_user = $nv_Cache->db( $sql, 'userid', $module_name );
        if( !empty($array_user))
        {
            foreach ( $array_user as $array_user_i )
            {
                $item['author_upload'] = $array_user_i['username'];
            }
        }

        $item['category'] = isset( $global_photo_cat[$item['category_id']] ) ? $global_photo_cat[$item['category_id']]['name'] : 'N/A';
        $item['category_link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=uppic&filter_category=" . $item['category_id'];
        $item['category_link_out'] = $global_photo_cat[$item['category_id']]['link'];
        $item['date_added'] = nv_date( 'd/m/Y', $item['date_added'] );
        $item['token'] = md5( $global_config['sitekey'] . session_id() . $item['album_id'] );

        $item['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=uppic&action=view&token=" . $item['token'] . "&album_id=" . $item['album_id'];
        $item['link_out'] = $global_photo_cat[$item['category_id']]['link'] . '/' . $item['alias'] . '-' . $item['album_id'];
        $item['edit'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=uppic&action=edit&token=" . $item['token'] . "&album_id=" . $item['album_id'];

        $xtpl->assign( 'LOOP', $item );

        $xtpl->parse( 'main.loop' );
    }
}

$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page );
if( ! empty( $generate_page ) )
{
    $xtpl->assign( 'GENERATE_PAGE', $generate_page );
    $xtpl->parse( 'main.generate_page' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
