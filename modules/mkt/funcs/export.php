<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 3, 2010 11:33:22 AM
 */

if( ! defined( 'NV_IS_MOD_RM' ) )
    die( 'Stop!!!' );

if( ! file_exists( NV_ROOTDIR . '/includes/class/PHPExcel.php' ) )
{
    die( strip_tags( $lang_module['required_phpexcel'] ) );
}
require_once NV_ROOTDIR . '/includes/class/PHPExcel.php';
ini_set("display_errors", "On");
error_reporting(E_ALL ^ E_NOTICE);

$excel_ext = "xlsx";
$writerType = 'Excel2007';

$data_field = array();
$step = $nv_Request->get_int( 'step', 'get,post', 1 );
if( $step == 1 )
{

    $array_reponse = array();
    // Create new PHPExcel object
    $objPHPExcel = PHPExcel_IOFactory::load( NV_ROOTDIR . '/modules/' . $module_file . '/libs/danh-sach-khach-moi.' . $excel_ext );
    $objWorksheet = $objPHPExcel->getActiveSheet();
    $page_title = $lang_module['danh_sach_khach_moi'];
    // Rename sheet

    $objWorksheet->setTitle(nv_clean60($page_title, 30));

    // Setting a spreadsheet’s metadata
    $objPHPExcel->getProperties()->setCreator( "CASH 13" );
    $objPHPExcel->getProperties()->setLastModifiedBy( "CASH 13" );
    $objPHPExcel->getProperties()->setTitle( $page_title );
    $objPHPExcel->getProperties()->setSubject( $page_title );
    $objPHPExcel->getProperties()->setDescription( $page_title );
    $objPHPExcel->getProperties()->setKeywords( $page_title );
    $objPHPExcel->getProperties()->setCategory( $module_name );

    // Set page orientation and size
    $objWorksheet->getPageSetup()->setOrientation( PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE );
    $objWorksheet->getPageSetup()->setPaperSize( PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4 );
    $objWorksheet->getPageSetup()->setHorizontalCentered( true );
    $objWorksheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd( 1, 3 );

    $data_tree = array();
    $statistic_all = 0;

    $sql_show = $nv_Request->get_title( 'data', 'get,post', '' );
    $sql = base64_decode( $sql_show );

    $array_refer = array();
    $res = $db->query( $sql );
    $list_userid = array();
    while ($row = $res->fetch()){
        $row['status'] = $lang_module['customer_event_' . $row['statususer']];
        $array_data[] = $row;
        $list_userid[$row['adminid']] = $row['adminid'];
    }
    if( !empty( $list_userid )){
        $res = $db->query( 'SELECT userid, username, first_name, last_name FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid IN ( ' . implode(',', $list_userid ) . ')' );
        while ($row = $res->fetch()){
            $full_name = nv_show_name_user( $row['first_name'], $row['last_name'] );
            $array_refer[$row['userid']] = $full_name;
        }
    }

    $style = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
        ),
        'font' => array(
            'bold' => true
        )
    );
    $i=3;
    $stt = 1;
    foreach( $array_data as $data )
    {
        $objWorksheet->setCellValue( 'A' . $i, $stt++ );
        $objWorksheet->setCellValue( 'B' . $i, $data['first_name'] );
        $objWorksheet->setCellValue( 'C' . $i, $data['last_name'] );
        $objWorksheet->setCellValue( 'D' . $i, $data['mobile'] );
        $objWorksheet->setCellValue( 'E' . $i, $array_refer[$data['adminid']] );
        $objWorksheet->setCellValue( 'F' . $i, $data['address'] );
        $objWorksheet->setCellValue( 'G' . $i, $data['status'] );
        $i++;
    }

    $col_row_begin = 'A3';
    $col_row_end = 'H' . $i;
    //bo khung san pham
    $BStyle = array(
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
            )
        )
    );

    $objWorksheet->getStyle($col_row_begin . ':' . $col_row_end)->applyFromArray($BStyle);

    $objWriter = PHPExcel_IOFactory::createWriter( $objPHPExcel, $writerType );
    $file_name = strtolower( change_alias( $page_title ) );
    $nv_Request->set_Session( $module_data . '_export_filename', $file_name );
    try{
        $objWriter->save( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $file_name . "." . $excel_ext );
    } catch (Exception $e) {
        die($e->getMessage());
    }

    $array_reponse['status'] = 'OK';
    $array_reponse['mess'] = 'OK';
    nv_jsonOutput( $array_reponse );
}
elseif( $step == 2 and $nv_Request->isset_request( $module_data . '_export_filename', 'session' ) )
{

    $file_name = $nv_Request->get_string( $module_data . '_export_filename', 'session', '' );
    if( ! empty( $file_name ) and file_exists( NV_ROOTDIR . '/' . NV_CACHEDIR . '/' . $file_name . '.' . $excel_ext ) )
    {
        $download = new NukeViet\Files\Download( NV_ROOTDIR . '/' . NV_CACHEDIR . '/' . $file_name . '.' . $excel_ext, NV_ROOTDIR . '/' . NV_CACHEDIR, $file_name . '.' . $excel_ext );
        $download->download_file();
        exit();
    }

}

