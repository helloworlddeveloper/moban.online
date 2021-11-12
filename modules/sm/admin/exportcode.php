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
ini_set("display_errors", "On");
error_reporting(E_ALL ^ E_NOTICE);

$excel_ext = "xlsx";
$writerType = 'Excel2007';

$data_field = array();
$step = $nv_Request->get_int( 'step', 'get,post', 1 );
if( $step == 1 )
{
    $array_reponse = array();
    global $objWorksheet;
    // Create new PHPExcel object
    $objPHPExcel = PHPExcel_IOFactory::load( NV_ROOTDIR . '/modules/' . $module_file . '/libs/ma_the_cao.' . $excel_ext );
    $objWorksheet = $objPHPExcel->getActiveSheet();
    $page_title = $lang_module['gencode_list'];
    // Rename sheet

    $objWorksheet->setTitle(nv_clean60($page_title, 30));

    // Setting a spreadsheet’s metadata
    $objPHPExcel->getProperties()->setCreator( "KMVIP" );
    $objPHPExcel->getProperties()->setLastModifiedBy( "KMVIP" );
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

    $sql = "";
    $order_id = $nv_Request->get_int('orderid', 'post,get', 0);
    if ($order_id > 0) {//export theo id
        $sql = 'select * from ' . NV_PREFIXLANG . '_' . $module_data . '_barcode where ordercodeid = ' . $order_id;
    } else {//export theo sql
        $sql_show = $nv_Request->get_title( 'data', 'get,post', '' );
        $sql = base64_decode( $sql_show );
    }
    //echo 'abc:' . $sql;die();
    $array_product = array();
    $array_category = array();
    $list_productid = array();
    $list_categoryid = array();
    $res = $db->query( $sql );
    while ($row = $res->fetch()){
        $array_data[] = $row;
        if ($row['proid'] > 0 && !in_array($row['proid'], $list_productid))
            $list_productid[] = $row['proid'];
        if ($row['catid'] > 0 && !in_array($row['catid'], $list_categoryid))
            $list_categoryid[] = $row['catid'];
    }

    if( !empty( $list_productid )){
        $res = $db->query( 'select id, title from ' . NV_PREFIXLANG . '_' . $module_data . '_product WHERE id IN ( ' . implode(',', $list_productid ) . ')' );
        while ($row = $res->fetch()){
            $array_product[$row['id']] = $row['title'];
        }
    }
    if( !empty( $list_categoryid )){
        $res = $db->query( 'select id, title from ' . NV_PREFIXLANG . '_' . $module_data . '_product WHERE id IN ( ' . implode(',', $list_categoryid ) . ')' );
        while ($row = $res->fetch()){
            $array_category[$row['id']] = $row['title'];
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
    $i=2;//dong bat dau ghi du lieu
    $stt = 1;
    foreach( $array_data as $data )
    {
        $objWorksheet->setCellValue( 'A' . $i, $stt++ );
        $objWorksheet->setCellValue( 'B' . $i, ($data['proid'] > 0 ? $array_product[$data['proid']] : ($data['catid'] > 0 ? $array_category[$data['catid']] : 'Sản phẩm/nhóm sản phẩm chung') ) );
        $objWorksheet->setCellValue( 'C' . $i, $data['barcode'] );
        $objWorksheet->setCellValue( 'D' . $i, $data['bonus_point'] );
        $objWorksheet->setCellValue( 'E' . $i, nv_get_bonus_barcode($data['bonus_gift']) );

        $i++;
    }

    $col_row_begin = 'A2';
    $col_row_end = 'E' . $i;
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
    $array_reponse['mess'] = 'Kết xuất dữ liệu ra file excel thành công!';
    //print_r($array_reponse);die();
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

