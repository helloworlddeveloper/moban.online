<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 3, 2010 11:33:22 AM
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) )
    die( 'Stop!!!' );

if( ! file_exists( NV_ROOTDIR . '/includes/class/PHPExcel.php' ) )
{
    die( strip_tags( $lang_module['required_phpexcel'] ) );
}
require_once NV_ROOTDIR . '/includes/class/PHPExcel.php';
$excel_ext = "xls";
$writerType = 'Excel5';

$data_field = array();
$step = $nv_Request->get_int( 'step', 'get,post', 1 );
$nextid = $nv_Request->get_int( 'nextid', 'get,post', 1 );
$set_export = $nv_Request->get_int( 'set_export', 'get,post', 0 );
if( $set_export == 1 )
{
    $user_field_info = array();

    $data_money_users = array();
    $sql = 'SELECT userid, money FROM ' . $db_config['prefix'] . '_' . $module_data . '_money WHERE money>=' . $module_config[$module_name]['min_payment'];

    $result = $db->query( $sql );
    $list_user_id = array();
    while( $row = $result->fetch() )
    {
        $list_user_id[] = $row['userid'];
        $data_money_users[$row['userid']] = $row['money'];
    }
    $id_export = 0;
    $result_field = $db->query( 'SELECT t1.userid, t1.username, t1.first_name, t1.last_name, t1.email, t2.datatext FROM ' . NV_USERS_GLOBALTABLE . ' AS t1 INNER JOIN ' . $db_config['prefix'] . '_' . $module_data . '_users AS t2 ON t1.userid=t2.userid WHERE t1.userid IN ( ' . implode( ',', $list_user_id ) . ' )' );
    while( $row_field = $result_field->fetch() )
    {
        $row_field['fullname'] = nv_show_name_user( $row_field['first_name'], $row_field['last_name'], $row_field['username']);
        $row_field['datatext'] = unserialize( $row_field['datatext'] );
        $row_field['stknganhang'] = $row_field['datatext']['stknganhang'];
        $row_field['tennganhang'] = $row_field['datatext']['tennganhang'];
        $row_field['chinhanh'] = $row_field['datatext']['chinhanh'];
        $row_field['address'] = $row_field['datatext']['address'];
        $row_field['money_str'] = convert_number_to_string( $data_money_users[$row_field['userid']] );
        $row_field['money_int'] = $data_money_users[$row_field['userid']];
        $row_field['money'] = number_format( $data_money_users[$row_field['userid']], 0, '.', ',' );
        unset( $row_field['datatext'] );
        $user_field_info[] = $row_field;
    }

    if( ! empty( $user_field_info ) )
    {
        $nv_Request->set_Session( $module_data . '_data_user', serialize( $user_field_info ) );
        save_to_file_danhsach( $user_field_info, $excel_ext, $writerType, $id_export );
        $id_next = save_to_file( $user_field_info[$id_export], $excel_ext, $writerType, $id_export );
        if( isset( $user_field_info[$id_next] ) )
        {
            $result = "NEXT_" . $id_next;
            die( $result );
        }else{
            $result = "COMPLETE_1";
            die( $result );
        }
    }
    die( 'ERROR' );
}
elseif( $step == 1 )
{
    $data_user = $nv_Request->get_string( $module_data . '_data_user', 'session', '' );
    $data_user = unserialize( $data_user );
    if( isset( $data_user[$nextid] ) )
    {
        $id_next = save_to_file( $data_user[$nextid], $excel_ext, $writerType, $nextid );
        if( isset( $data_user[$id_next] ) )
        {
            $result = "NEXT_" . $id_next;
            die( $result );
        }
    }
    $result = "COMPLETE_1";
    die( $result );
}
elseif( $step == 2 and $nv_Request->isset_request( $module_data . '_export_filename', 'session' ) )
{

    $export_filename = $nv_Request->get_string( $module_data . '_export_filename', 'session', '' );
    $array_filename = explode( "@", $export_filename );
    $arry_file_zip = array();
    foreach( $array_filename as $file_name )
    {
        if( ! empty( $file_name ) and file_exists( NV_ROOTDIR . '/' . NV_CACHEDIR . '/' . $file_name . '.' . $excel_ext ) )
        {
            $arry_file_zip[] = NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $file_name . "." . $excel_ext;
        }
    }

    $file_src = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . change_alias( $lang_module['export'] ) . '_' . md5( nv_genpass( 10 ) . session_id() ) . '.zip';

    $zip = new PclZip( $file_src );
    $zip->create( $arry_file_zip, PCLZIP_OPT_REMOVE_PATH, NV_ROOTDIR . "/" . NV_CACHEDIR );
    $filesize = @filesize( $file_src );

    $nv_Request->unset_request( $module_data . '_export_filename', 'session' );
    $nv_Request->unset_request( $module_data . '_data_user', 'session' );

    foreach( $arry_file_zip as $file )
    {
        nv_deletefile( $file );
    }

    $download = new NukeViet\Files\Download( $file_src, NV_ROOTDIR . "/" . NV_TEMP_DIR, basename( change_alias( $lang_module['export'] ) . ".zip" ) );
    $download->download_file();
    exit();
}

function save_to_file_danhsach( $user_field_info, $excel_ext, $writerType, $id_export )
{
    global $module_file, $module_data, $nv_Request, $module_name;
    $noidungchuyen = NV_MY_DOMAIN . ' thanh toán tiền triết khấu tháng ' . date( 'm/Y', NV_CURRENTTIME );
    $page_title = 'Danh sách thanh toán';
    $field = array(
        '0' => 'stt',
        '1' => 'fullname',
        '2' => 'address',
        '3' => 'stknganhang',
        '4' => 'tennganhang',
        '5' => 'chinhanh',
        '6' => 'money',
        '7' => 'noidungchuyen' );
    // Create new PHPExcel object
    $objPHPExcel = PHPExcel_IOFactory::load( NV_ROOTDIR . '/modules/' . $module_file . '/libs/danh-sanh-thanh-toan.' . $excel_ext );
    $objWorksheet = $objPHPExcel->getActiveSheet();

    // Setting a spreadsheet’s metadata
    $objPHPExcel->getProperties()->setCreator( "NukeViet CMS" );
    $objPHPExcel->getProperties()->setLastModifiedBy( "NukeViet CMS" );
    $objPHPExcel->getProperties()->setTitle( $page_title );
    $objPHPExcel->getProperties()->setSubject( $page_title );
    $objPHPExcel->getProperties()->setDescription( $page_title );
    $objPHPExcel->getProperties()->setKeywords( $page_title );
    $objPHPExcel->getProperties()->setCategory( $module_name );

    // Rename sheet
    $objWorksheet->setTitle( nv_clean60( $page_title, 30 ) );

    // Set page orientation and size
    $objWorksheet->getPageSetup()->setOrientation( PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE );
    $objWorksheet->getPageSetup()->setPaperSize( PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4 );
    $objWorksheet->getPageSetup()->setHorizontalCentered( true );
    $objWorksheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd( 1, 3 );

    //goi class thuc hien ghi giao dich rut tien

    require NV_ROOTDIR . '/modules/' . $module_file . '/affiliate.class.php';
    // Ghi dữ liệu bắt đầu từ dòng thứ $i=3
    $i = 3;
    $stt = 1;
    foreach( $user_field_info as $data_user )
    {
        $data_user['noidungchuyen'] = $noidungchuyen;
        $data_user['stt'] = $stt;
        for( $j = 0; $j <= 8; $j++ )
        {
            $col = PHPExcel_Cell::stringFromColumnIndex( $j );
            $CellValue = nv_unhtmlspecialchars( ' ' . $data_user[$field[$j]] );
            $objWorksheet->setCellValue( $col . $i, $CellValue );
        }

        $affiliateClass = new moneyService( $data_user['userid'], $data_user['money_int'], $noidungchuyen, $module_name, 0 );
        $affiliateClass->callActionMoney(-1);
        $stt++;
        $i++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter( $objPHPExcel, $writerType );

    $file_name = change_alias( $page_title );
    $nv_Request->set_Session( $module_data . '_export_filename', $file_name );
    $objWriter->save( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $file_name . "." . $excel_ext );
    $id_export++;
    return $id_export;
}

function save_to_file( $data_user, $excel_ext, $writerType, $id_export )
{
    global $module_file, $module_data, $nv_Request, $module_name;
    $page_title = $data_user['username'];
    $data_user['nganhanggui'] = '';
    $data_user['tkgui'] = '';
    $data_user['chinhanhgui'] = 'Hà Nội';
    $data_user['noidungchuyen'] = NV_MY_DOMAIN . ' thanh toán tiền triết khấu tháng ' . date( 'm/Y', NV_CURRENTTIME );
    $field = array(
        '4' => 'nganhanggui',
        '5' => 'tkgui',
        '6' => 'chinhanhgui',
        '7' => 'money',
        '8' => 'money_str',
        '9' => 'fullname',
        '10' => 'stknganhang',
        '11' => 'cmnd',
        '12' => 'ngaycap',
        '13' => 'noicap',
        '14' => 'tennganhang',
        '15' => 'chinhanh',
        '16' => 'noidungchuyen' );

    // Create new PHPExcel object
    $objPHPExcel = PHPExcel_IOFactory::load( NV_ROOTDIR . '/modules/' . $module_file . '/libs/uynhiemchi.' . $excel_ext );
    $objWorksheet = $objPHPExcel->getActiveSheet();

    // Setting a spreadsheet’s metadata
    $objPHPExcel->getProperties()->setCreator( "NukeViet CMS" );
    $objPHPExcel->getProperties()->setLastModifiedBy( "NukeViet CMS" );
    $objPHPExcel->getProperties()->setTitle( $page_title );
    $objPHPExcel->getProperties()->setSubject( $page_title );
    $objPHPExcel->getProperties()->setDescription( $page_title );
    $objPHPExcel->getProperties()->setKeywords( $page_title );
    $objPHPExcel->getProperties()->setCategory( $module_name );

    // Rename sheet
    $objWorksheet->setTitle( nv_clean60( $page_title, 30 ) );

    // Set page orientation and size
    $objWorksheet->getPageSetup()->setOrientation( PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE );
    $objWorksheet->getPageSetup()->setPaperSize( PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4 );
    $objWorksheet->getPageSetup()->setHorizontalCentered( true );
    $objWorksheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd( 1, 3 );
    // Ghi dữ liệu bắt đầu từ dòng thứ $i=4
    $j = 1;
    for( $i = 4; $i <= 16; $i++ )
    {
        $col = PHPExcel_Cell::stringFromColumnIndex( $j );
        $CellValue = nv_unhtmlspecialchars( ' ' . $data_user[$field[$i]] );
        $objWorksheet->setCellValue( $col . $i, $CellValue );
    }
    $objWriter = PHPExcel_IOFactory::createWriter( $objPHPExcel, $writerType );

    $export_filename = $nv_Request->get_string( $module_data . '_export_filename', 'session', '' );

    $file_name = change_alias( $page_title );
    $nv_Request->set_Session( $module_data . '_export_filename', $export_filename . "@" . $file_name );

    $objWriter->save( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $file_name . "." . $excel_ext );
    $id_export++;
    return $id_export;
}
