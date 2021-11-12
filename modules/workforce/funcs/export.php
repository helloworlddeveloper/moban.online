<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 3, 2010 11:33:22 AM
 */

if( ! defined( 'NV_IS_MOD_WORKFORCE' ) )
    die( 'Stop!!!' );

if( ! file_exists( NV_ROOTDIR . '/includes/class/PHPExcel.php' ) )
{
    die( strip_tags( $lang_module['required_phpexcel'] ) );
}
require_once NV_ROOTDIR . '/includes/class/PHPExcel.php';
$excel_ext = "xlsx";
$writerType = 'Excel2007';

$data_field = array();
$step = $nv_Request->get_int( 'step', 'get,post', 1 );
$id = $nv_Request->get_int( 'id', 'post', 0 );
if( $step == 1 )
{
    $array_reponse = array();
    if( $id == 0 )
    {
        $array_reponse['status'] = 'error';
        $array_reponse['mess'] = 'ERROR_ID';
        nv_jsonOutput( $array_reponse );
    }

    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_inventory WHERE id=' . $id;
    $data_inventory = $db->query( $sql )->fetch();

//kiem tra xem co du quyen k
    $array_department_allow = array();
    if( !defined('NV_IS_ADMIN')){
        foreach ($array_department as $department ){
            if( $department['userid'] == $user_info['userid'] ){
                $array_department_allow[] = $department['id'];
            }
        }
        if( empty( $array_department_allow) || !in_array( $data_inventory['departmentid'], $array_department_allow)){
            $array_reponse['status'] = 'error';
            $array_reponse['mess'] = 'ERROR_NOT_ALLOW';
            nv_jsonOutput( $array_reponse );
        }
    }

    $data_inventory['hour'] = date( 'H', $data_inventory['time_inventory'] );
    $data_inventory['minute'] = date( 'i', $data_inventory['time_inventory'] );
    $data_inventory['day'] = date( 'd', $data_inventory['time_inventory'] );
    $data_inventory['month'] = date( 'm', $data_inventory['time_inventory'] );
    $data_inventory['year'] = date( 'Y', $data_inventory['time_inventory'] );

    $noidungchuyen = NV_MY_DOMAIN . ' thanh toán tiền triết khấu tháng ' . date( 'm/Y', NV_CURRENTTIME );


    // Create new PHPExcel object
    $objPHPExcel = PHPExcel_IOFactory::load( NV_ROOTDIR . '/modules/' . $module_file . '/libs/kiemketaisan.' . $excel_ext );
    $objWorksheet = $objPHPExcel->getActiveSheet();
    $page_title = sprintf( $lang_module['kiemketaisan_page_title'], $data_inventory['month'] . '-' . $data_inventory['year'] );
    // Rename sheet

    $objWorksheet->setTitle(nv_clean60($page_title, 30));

    // Setting a spreadsheet’s metadata
    $objPHPExcel->getProperties()->setCreator( "NukeViet CMS" );
    $objPHPExcel->getProperties()->setLastModifiedBy( "NukeViet CMS" );
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

    //don vi kiem ke tai san
    $objWorksheet->setCellValue( 'A7', $lang_module['select_unit'] . ' ' . $array_department[$data_inventory['departmentid']]['title'] );

    //thoi gian kiem ke tai san
    $title_time = sprintf( $lang_module['thoidiemkiemke'], $data_inventory['hour'], $data_inventory['minute'], $data_inventory['day'], $data_inventory['month'], $data_inventory['year'] );
    $objWorksheet->setCellValue( 'B8', $title_time );

    //thanh phan ban kiem ke tai san
    $sql = 'SELECT t1.*, t2.first_name, t2.last_name FROM ' . NV_PREFIXLANG . '_' . $module_data . '_inventory_users AS t1 INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . ' AS t2 ON t1.userid=t2.id WHERE iid=' . $id;
    $result = $db->query( $sql );
    $numrow = $result->rowCount();


    $i = 10;

    if( $numrow > 1 ){
        $numrow--;
        $objPHPExcel->getActiveSheet()->insertNewRowBefore($i,$numrow);
    }
    while( $row = $result->fetch() )
    {
        $objWorksheet->setCellValue( 'B' . $i, $lang_module['ongba'] . ':' . $row['last_name'] . ' ' . $row['first_name'] );
        $objWorksheet->setCellValue( 'E' . $i, $lang_module['chucvu'] . ':' . $row['postion_name'] );
        $i++;
    }
    $i+=4;

    $sql = 'SELECT t2.*, t1.price AS price_conlai, t2.amount as amount_inventory, t1.amount_broken, t1.amount_redundant, t1.amount_missing, t1.note FROM ' . NV_PREFIXLANG . '_' . $module_data . '_inventory_detail AS t1 INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_product AS t2 ON t1.pid=t2.id WHERE iid=' . $id;
    $stt = 1;
    $result = $db->query( $sql );
    $numrow = $result->rowCount();

    $field = array(
        '0' => 'stt',
        '1' => 'title',
        '2' => 'unitid',
        '3' => 'time_in',
        '4' => 'departmentid',
        '5' => 'amount',
        '6' => 'price',
        '7' => 'price_conlai',
        '8' => 'amount_inventory',
        '9' => 'amount_broken',
        '10' => 'amount_using',
        '11' => 'amount_redundant',
        '12' => 'amount_missing',
        '13' => 'note'
        );

    $array_data = array();
    while( $row = $result->fetch() )
    {
        $row['tt'] = $stt++;
        $row['unitid'] = $array_units[$row['unitid']]['title'];
        $row['time_in'] = date( 'Y', $row['time_in'] );
        $row['departmentid'] = $array_department[$row['departmentid']]['title'];
        $row['status'] = $lang_module['status_' . $row['status']];
        $row['amount_using'] = $row['amount_inventory'] - $row['amount_broken'];
        $array_data[$row['producttypeid']][] = $row;
    }
    $stt = 1;
    //tao dong trong bang truoc khi import
    if( $numrow > 1 ){
        $numrow--;
        $numrow = $numrow + count($array_data);
        $num_row_insert = $i + 1;
        $objPHPExcel->getActiveSheet()->insertNewRowBefore($num_row_insert,$numrow);
    }
    $style = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
        ),
        'font' => array(
            'bold' => true
        )
    );
    foreach( $array_data as $producttype => $data_type )
    {
        $CellValue = $data_type;
        $objWorksheet->setCellValue( 'A' . $i, $array_producttype[$producttype]['title'] );

        $row_merge_cells = 'A' . $i . ":" . 'N' . $i;
        $objWorksheet->getStyle($row_merge_cells)->applyFromArray($style);
        $objWorksheet->mergeCells($row_merge_cells );
        $i++;
        foreach ($data_type as $data){
            $data['stt'] = $stt++;
            foreach( $field as $key => $column )
            {
                $col = PHPExcel_Cell::stringFromColumnIndex( $key );
                $objWorksheet->setCellValue( $col . $i, $data[$column] );
            }
            $i++;
        }
    }

    $objWriter = PHPExcel_IOFactory::createWriter( $objPHPExcel, $writerType );

    $file_name = change_alias( $page_title );
    $nv_Request->set_Session( $module_data . '_export_filename', $file_name );
    $objWriter->save( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $file_name . "." . $excel_ext );
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

