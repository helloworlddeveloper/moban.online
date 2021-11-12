<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 19 Jun 2014 09:53:21 GMT
 */

if ( ! defined( 'NV_IS_MOD_REGSITE' ) ) die( 'Stop!!!' );

if (!defined('NV_IS_USER') ) {
    $redirect = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;
    Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=login&nv_redirect=' . nv_redirect_encrypt($redirect));
    die();
}


$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

$mobile = $nv_Request->get_title('mobile', 'get', '');
if( $mobile != ''){
    $nv_Request->set_Session($module_data . '_mobile_refer', $mobile );
}
$error = $array_data = array();

if( $nv_Request->isset_request('submit', 'post')){


    $array_data['id'] = $nv_Request->get_int('id', 'post', 0);
    $array_data_old = $db->query('SELECT *FROM ' . $db_config['prefix'] . '_' . $module_data . ' WHERE userid = ' . $user_info['userid'] . ' AND id=' . $array_data['id'] )->fetch();

    $array_data['site_title'] = $nv_Request->get_title('site_title', 'post');
    $array_data['site_email'] = $nv_Request->get_title('site_email', 'post');
    $array_data['facebook_link'] = $nv_Request->get_title('facebook_link', 'post', '');
    $array_data['zalo'] = $nv_Request->get_title('zalo', 'post', '');
    $array_data['youtube'] = $nv_Request->get_title('youtube', 'post', '');
    $array_data['instagram'] = $nv_Request->get_title('instagram', 'post', '');
    $array_data['image_site'] = $array_data['banner_site'] = '';
    $array_data['facebook_link'] = empty( $array_data['facebook_link'] )? 'https://www.facebook.com/cash13group/' : $array_data['facebook_link'];
    if( empty( $array_data_old ) ){
        $error[] = 'Bạn không có quyền truy cập chức năng này!';
    }
    if( empty($array_data['site_title'] )){
        $error[] = 'Bạn cần nhập tiêu đề website.';
    }
    if( empty( $error )){

        if (isset($_FILES['upload_site_image']) and is_uploaded_file($_FILES['upload_site_image']['tmp_name'])) {
            $file_allowed_ext = array('images');

            $upload = new NukeViet\Files\Upload($file_allowed_ext, $global_config['forbid_extensions'], $global_config['forbid_mimes'], 0, NV_MAX_WIDTH, NV_MAX_HEIGHT);
            $upload->setLanguage($lang_global);

            $upload_info = $upload->save_file($_FILES['upload_site_image'], NV_UPLOADS_REAL_DIR . '/' . $module_upload, false);

            @unlink($_FILES['upload_site_image']['tmp_name']);
            if (empty($upload_info['error'])) {
                mt_srand(( double )microtime() * 1000000);
                $maxran = 1000000;
                $random_num = mt_rand(0, $maxran);
                $random_num = md5($random_num);
                $nv_pathinfo_filename = nv_pathinfo_filename($upload_info['name']);
                $new_name = NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $nv_pathinfo_filename . '.' . $random_num . '.' . $upload_info['ext'];
                $rename = nv_renamefile($upload_info['name'], $new_name);
                if ($rename[0] == 1) {
                    $fileupload = $new_name;
                } else {
                    $fileupload = $upload_info['name'];
                }
                @chmod($fileupload, 0644);
                $array_data['image_site'] = str_replace(NV_ROOTDIR . '/' . NV_UPLOADS_DIR, '', $fileupload);
                $image_site = str_replace(NV_ROOTDIR . '/' . NV_UPLOADS_DIR, '', $fileupload);
                $array_data['image_site'] = NV_UPLOADS_DIR . str_replace($module_name . '/', '', $array_data['image_site'] );
                if( nv_copyfile( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . $image_site, NV_ROOTDIR . '/' . $array_data['image_site'] ) ){
                    $array_data['image_site'] = NV_BASE_SITEURL . $array_data['image_site'];
                    nv_deletefile( $fileupload );
                    nv_deletefile( NV_ROOTDIR . '/' . $array_data_old['image_site'] );//xoa anh cu
                }

            } else {
                $is_error = true;
                $error[] = $lang_module['site_image'] . ' ' . $upload_info['error'];
            }
            unset($upload, $upload_info);
        }else{
            $array_data['image_site'] = $array_data_old['image_site'];
        }

        if (isset($_FILES['upload_fileupload']) and is_uploaded_file($_FILES['upload_fileupload']['tmp_name'])) {
            $file_allowed_ext = array('images');
            $upload = new NukeViet\Files\Upload($file_allowed_ext, $global_config['forbid_extensions'], $global_config['forbid_mimes'], 0, NV_MAX_WIDTH, NV_MAX_HEIGHT);
            $upload->setLanguage($lang_global);

            $upload_info = $upload->save_file($_FILES['upload_fileupload'], NV_UPLOADS_REAL_DIR . '/' . $module_upload, false);
            @unlink($_FILES['upload_fileupload']['tmp_name']);
            if (empty($upload_info['error'])) {
                mt_srand(( double )microtime() * 1000000);
                $maxran = 1000000;
                $random_num = mt_rand(0, $maxran);
                $random_num = md5($random_num);
                $nv_pathinfo_filename = nv_pathinfo_filename($upload_info['name']);
                $new_name = NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $nv_pathinfo_filename . '.' . $random_num . '.' . $upload_info['ext'];
                $rename = nv_renamefile($upload_info['name'], $new_name);
                if ($rename[0] == 1) {
                    $fileupload = $new_name;
                } else {
                    $fileupload = $upload_info['name'];
                }
                @chmod($fileupload, 0644);
                $banner_site = str_replace(NV_ROOTDIR . '/' . NV_UPLOADS_DIR, '', $fileupload);
                $array_data['banner_site'] = NV_UPLOADS_DIR . str_replace($module_name . '/', '', $banner_site );
                if( nv_copyfile( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . $banner_site, NV_ROOTDIR . '/' . $array_data['banner_site'] ) ){
                    $array_data['banner_site'] = NV_BASE_SITEURL . $array_data['banner_site'];
                    nv_deletefile( $fileupload );
                    nv_deletefile( NV_ROOTDIR . '/' . $array_data_old['banner_site'] );//xoa anh cu
                }
            } else {
                $is_error = true;
                $error[] = $lang_module['site_banner'] . ' ' . $upload_info['error'];
            }
            unset($upload, $upload_info);
        }else{
            $array_data['banner_site'] = $array_data_old['banner_site'];
        }

        if (empty( $error )){
            $array_data['domain_name'] = $array_data_old['domain'];
            $sth = $db->prepare(  "UPDATE " . $db_config['prefix'] . "_" . $module_data . " 
            SET title=:title, email=:email, image_site=:image_site, banner_site=:banner_site, facebook=:facebook, zalo=:zalo, youtube=:youtube, instagram=:instagram WHERE id =" . $array_data['id'] );
            $sth->bindParam( ':title', $array_data['site_title'], PDO::PARAM_STR );
            $sth->bindParam( ':email', $array_data['site_email'], PDO::PARAM_STR );
            $sth->bindParam( ':image_site', $array_data['image_site'], PDO::PARAM_STR );
            $sth->bindParam( ':banner_site', $array_data['banner_site'], PDO::PARAM_STR );
            $sth->bindParam( ':facebook', $array_data['facebook_link'], PDO::PARAM_STR );
            $sth->bindParam( ':zalo', $array_data['zalo'], PDO::PARAM_STR );
            $sth->bindParam( ':youtube', $array_data['youtube'], PDO::PARAM_STR );
            $sth->bindParam( ':instagram', $array_data['instagram'], PDO::PARAM_STR );

            if( $sth->execute() )
            {
                $contents = nv_theme_update_ok( $array_data );
                $nv_Request->unset_request($module_data . '_mobile', 'session');

                include NV_ROOTDIR . '/includes/header.php';
                echo nv_site_theme( $contents );
                include NV_ROOTDIR . '/includes/footer.php';
                exit();

            }else{
                //die('dfgdfg');
            }
        }
    }
}else{
    $array_data = $db->query('SELECT *FROM ' . $db_config['prefix'] . '_' . $module_data . ' WHERE userid = ' . $user_info['userid'] )->fetch();
}

if (empty($user_info['photo'])) {
    $array_data['photo'] = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/users/no_avatar.png';
    $array_data['imgDisabled'] = " disabled=\"disabled\"";
} else {
    $size = @getimagesize(NV_ROOTDIR . '/' . $user_info['photo']);
    $array_data['imgDisabled'] = '';
    $array_data['photo'] = NV_BASE_SITEURL . $user_info['photo'];
}

if (empty($array_data['image_site'])) {
    $array_data['image_site'] = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/users/no_avatar.png';
    $array_data['image_siteDisabled'] = " disabled=\"disabled\"";
} else {
    $size = @getimagesize(NV_ROOTDIR . '/' . $array_data['image_site']);
    $array_data['image_siteDisabled'] = '';
    $array_data['image_site'] = NV_BASE_SITEURL . $array_data['image_site'];
}

$contents = nv_theme_reg_update( $array_data, $error );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
