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
    $objPHPExcel = PHPExcel_IOFactory::load( NV_ROOTDIR . '/modules/' . $module_file . '/libs/de_nghi_xuat_hang.' . $excel_ext );
    $objWorksheet = $objPHPExcel->getActiveSheet();
    $page_title = $lang_module['bangkedenghixuathang'];
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

    //them cac sp dang co trong he thong
    $i = 1;
    $j = 6;
    foreach($array_product as $product )
    {
        $col = PHPExcel_Cell::stringFromColumnIndex( $j );
        $objWorksheet->setCellValue( $col . $i, $product['title'] );
        $j++;
    }

    list( $subcatid ) = $db->query('SELECT subcatid FROM ' . NV_TABLE_AFFILIATE . '_users WHERE parentid=0')->fetch(3);
    $array_user = array();
    $sql = 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . ' t1, ' . NV_TABLE_AFFILIATE . '_users t2 WHERE t1.userid=t2.userid AND t2.shareholder=1';
    $res = $db->query( $sql );
    while ($data = $res->fetch()){
        $array_user[$data['userid']] = $data;
    }

    $listall = $nv_Request->get_title( 'listall', 'get,post', '' );
    if( !empty( $listall ) ){
        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . "_" . $module_data . '_orders WHERE order_id IN(' . $listall . ')';
    }
    else{
        $sql = $nv_Request->get_string('sql_export_' . $module_data, 'session');
    }

    $array_data = array();
    $res = $db->query( $sql );
    while ($data = $res->fetch()){
        $data['codong_name'] = nv_show_name_user( $array_user[$data['user_id']]['first_name'], $array_user[$data['user_id']]['last_name'] );
        $array_data[$data['user_id']][$data['order_id']] = $data;
        $array_data[$data['user_id']][$data['order_id']]['detail'] = array();

        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_orders_id WHERE order_id =' . $data['order_id'];
        $result = $db->query($sql);
        while ($row = $result->fetch()) {
            if( isset( $array_data[$data['user_id']][$data['order_id']]['detail'][$row['proid']] )){
                $array_data[$data['user_id']][$data['order_id']]['detail'][$row['proid']] += $row['num_com'];
            }else{
                $array_data[$data['user_id']][$data['order_id']]['detail'][$row['proid']] = $row['num_com'];
            }
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
    $array_sum = array();
    $i=2;
    $stt = 1;
    foreach( $array_data as $data_by_user )
    {
        foreach( $data_by_user as $data )
        {
            $objWorksheet->setCellValue( 'A' . $i, $stt++ );
            $objWorksheet->setCellValue( 'B' . $i, $data['codong_name'] );
            $objWorksheet->setCellValue( 'C' . $i, $data['order_name'] );
            $objWorksheet->setCellValue( 'D' . $i, $data['order_phone'] );
            $objWorksheet->setCellValue( 'E' . $i, nv_unhtmlspecialchars( $data['order_address'] ) );
            $objWorksheet->setCellValue( 'F' . $i, date('d/m/Y', $data['order_time'] ) );
            $j = 6;
            foreach($array_product as $productid => $product )
            {
                if( !isset( $array_sum[$productid] )){
                    $array_sum[$productid] = $data['detail'][$productid];
                }else{
                    $array_sum[$productid] += $data['detail'][$productid];
                }

                $col = PHPExcel_Cell::stringFromColumnIndex( $j );
                $objWorksheet->setCellValue( $col . $i, $data['detail'][$productid] );
                $j++;
            }
            $i++;
        }

    }
    $col_row_begin = 'A2';
    $col_row_end = $col . '' . $i;
    //bo khung san pham
    $BStyle = array(
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
            )
        )
    );
    $objWorksheet->setCellValue( 'A' . $i, 'Tổng cộng' );
    $objWorksheet->getStyle($col_row_begin . ':' . $col_row_end)->applyFromArray($BStyle);
    $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':' . 'F'. $i);

    $BStyle = array(
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
            )
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
        ),
        'font'  => array(
            'bold'  => true,
            'color' => array('rgb' => 'FF0000')
        )
    );
    $objWorksheet->getStyle('A' . $i )->applyFromArray($BStyle);
    $j = 6;
    foreach($array_sum as $total )
    {
        $col = PHPExcel_Cell::stringFromColumnIndex( $j );
        $objWorksheet->setCellValue( $col . $i, $total );
        $j++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter( $objPHPExcel, $writerType );
    $file_name = strtolower( change_alias( $page_title ) );
    $nv_Request->set_Session( $module_data . '_export_filename', $file_name );
    try{
        $objWriter->save( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $file_name . "." . $excel_ext );
    } catch (Exception $e) {
        die($e->getMessage());
    }

    $array_reponse['status'] = 'OK';
    $array_reponse['mess'] = 'Xuất dữ liệu thành công';
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

