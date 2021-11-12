<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 18 Nov 2014 04:27:19 GMT
 */

if( $nv_Request->isset_request( 'loaddistrict', 'post' ) )
{
    $provinceid = $nv_Request->get_int( 'provinceid', 'post', 0 );
    $districtid = $nv_Request->get_int( 'districtid', 'post', 0 );

    $html = '<select style="width: 100%;" class="form-control" name="districtid">';
    $html .= '<option value="0">---------</option>';
    if( $provinceid > 0 )
    {
        $sql = "SELECT * FROM " . NV_PREFIXLANG . '_' . $module_data . "_district WHERE status=1 AND idprovince=" . $provinceid . " ORDER BY weight ASC";
        $result = $db->query( $sql );
        $list = array();
        while( $row = $result->fetch() )
        {
            $sl = ( $row['id'] == $districtid ) ? ' selected="selected"' : '';
            $html .= '<option value="' . $row['id'] . '" ' . $sl . '>' . $row['title'] . '</option>';
        }
    }
    $html .= '</select>';
    exit( $html );
}
elseif( $nv_Request->isset_request( 'submit', 'post' ) )
{
    $provinceid = $nv_Request->get_int( 'provinceid', 'post', 0 );
    $districtid = $nv_Request->get_int( 'districtid', 'post', 0 );
    $fullname = $nv_Request->get_title( 'fullname', 'post', '' );
    $phone = $nv_Request->get_title( 'phone', 'post', '' );
   
    $url_reg = $client_info['referer'];
    $email = $nv_Request->get_title( 'email', 'post', '' );
    $address = $nv_Request->get_title( 'address', 'post', '' );
    $facebook = $nv_Request->get_title( 'facebook', 'post', '' );
    $birthday = $nv_Request->get_title( 'birthday', 'post', '' );
    $byid = $nv_Request->get_int( 'byid', 'post', 0 );
    $idpost = $nv_Request->get_int( 'idpost', 'post', 0 );
    $modulename = $nv_Request->get_title( 'modulename', 'post', '' );
    $class_study = $nv_Request->get_int( 'class_study', 'post', 0 );
    if( ! empty( $birthday ) )
    {
        $birthday = mktime( 0, 0, 0, 1, 1, $birthday );
    }
    else
    {
        $birthday = date( 'Y', NV_CURRENTTIME ) - ( $class_study + 6 ); //lay nam
        $birthday = mktime( 0, 0, 0, 1, 1, $birthday );
    }

    $status = $sex = 0;
    try
    {
        $stmt = $db->prepare( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_data (byid, idpost, modulename, provinceid, districtid, student_name, birthday, sex, url_reg, address, email, mobile, facebook, add_time, edit_time, status) VALUES (:byid, :idpost, :modulename, :provinceid, :districtid, :student_name, :birthday, :sex, :url_reg, :address, :email, :mobile, :facebook, :add_time, :edit_time, :status)' );

        $stmt->bindParam( ':byid', $byid, PDO::PARAM_INT );
        $stmt->bindParam( ':idpost', $idpost, PDO::PARAM_INT );
        $stmt->bindParam( ':modulename', $modulename, PDO::PARAM_STR );
        $stmt->bindParam( ':provinceid', $provinceid, PDO::PARAM_INT );
        $stmt->bindParam( ':districtid', $districtid, PDO::PARAM_INT );
        $stmt->bindParam( ':student_name', $fullname, PDO::PARAM_STR );
        $stmt->bindParam( ':birthday', $birthday, PDO::PARAM_INT );
        $stmt->bindParam( ':sex', $sex, PDO::PARAM_INT );
        $stmt->bindParam( ':url_reg', $url_reg, PDO::PARAM_STR );
        $stmt->bindParam( ':address', $address, PDO::PARAM_STR );
        $stmt->bindParam( ':email', $email, PDO::PARAM_STR );
        $stmt->bindParam( ':mobile', $phone, PDO::PARAM_STR );
        $stmt->bindParam( ':facebook', $facebook, PDO::PARAM_STR );
        $stmt->bindValue( ':add_time', NV_CURRENTTIME, PDO::PARAM_INT );
        $stmt->bindValue( ':edit_time', NV_CURRENTTIME, PDO::PARAM_INT );
        $stmt->bindParam( ':status', $status, PDO::PARAM_INT );

        $exc = $stmt->execute();
        if( $exc )
        {
            if( isset( $site_mods[$modulename] ) )
            {
                $nv_Request->set_Cookie( 'popup_site_' . $site_mods[$modulename]['module_data'], '1', 5184000 ); //5184000 = 2 thang
            }
            //  @nv_sendmail( $global_config['site_email'], 'thangbv@edus.vn', 'Có don dang ký h?c m?i c?a b?n ' . $row['fullname'] . ' t?i daytot.vn', 'Có don dang ký h?c m?i t?i trang daytot.vn' );
            exit( 'OK_' . md5( session_id() . $byid ) );
        }
    }
    catch ( PDOException $Exception )
    {
        exit( 'OK_' . md5( session_id() . $byid ) );
    }
}
elseif( $nv_Request->isset_request( 'click', 'post' ) )
{
    $byid = $nv_Request->get_int( 'byid', 'post', 0 );
    if( $byid > 0 )
    {
        $db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_bymodule SET numclick = numclick+1 WHERE id=' . $byid );
    }

}
elseif( $nv_Request->isset_request( 'downloadid', 'get' ) )
{
    $downloadid = $nv_Request->get_title( 'downloadid', 'get', '' );
    $byid = $nv_Request->get_int( 'byid', 'get', 0 );
    if( $byid > 0 && md5( session_id() . $byid ) == $downloadid )
    {
        $data_download = $db->query( "SELECT link_download FROM " . NV_PREFIXLANG . '_' . $module_data . "_bymodule WHERE id = " . $byid )->fetch();
        if( ! empty( $data_download ) )
        {
            if( ! nv_is_url( $data_download['link_download'] ) && file_exists( NV_ROOTDIR . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $data_download['link_download'] ) )
            {
                $upload_dir = 'files';
                $is_zip = false;
                $is_resume = true;
                $max_speed = 0;

                $file_src = NV_ROOTDIR . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $data_download['link_download'];
                $file_basename = basename( $data_download['link_download'] );
                $directory = NV_UPLOADS_REAL_DIR;
                
                $download = new NukeViet\Files\Download($file_src, $directory, $file_basename, $is_resume, $max_speed);
                $db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_bymodule SET numdownload = numdownload+1 WHERE id=' . $byid );
                $download->download_file();
                exit();
            }
            else
            {
                Header( 'Location: ' . $data_download['link_download'] );
                die();
            }
        }
    }
    else
    {
        $contents = $lang_module['error_download'];
        include NV_ROOTDIR . '/includes/header.php';
        echo nv_site_theme( $contents );
        include NV_ROOTDIR . '/includes/footer.php';
    }
}
