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
$to = $nv_Request->get_int( 'to', 'post', 3000 );
$from = $nv_Request->get_int( 'from', 'post', 0 );
$array_status = $nv_Request->get_array( 'status', 'post' );
$schooltype = $nv_Request->get_array( 'schooltype', 'post', array() );
$map_lat = $nv_Request->get_float( 'map_lat', 'post', $module_config[$module_name]['gmap_lat'] );
$map_lon = $nv_Request->get_float( 'map_lon', 'post', $module_config[$module_name]['gmap_lng'] );

$date_from = $nv_Request->get_title( 'date_from', 'post', '' );
$date_to = $nv_Request->get_title( 'date_to', 'post', '' );
$step = $nv_Request->get_int( 'step', 'get,post', 0 );

if( $step == 1 )
{
    $sql_where = '';
    $list_year_old = array();
    $year_current = date( 'Y', NV_CURRENTTIME );
    $month_current = date( 'm', NV_CURRENTTIME );
    if( $month_current > 5 && $month_current < 12 )
    {
        $year_current = $year_current + 1;
    }
    if( ! empty( $schooltype ) )
    {
        $schooltype = explode( ',', $schooltype[0] );
        foreach( $schooltype as $schooltype_i )
        {
            $list_year_old[] = $schooltype_i + 6;

        }
        $list_year_old = implode( ',', $list_year_old );
        $sql_where = ' AND ' . $year_current . ' - FROM_UNIXTIME(t1.birthday,"%Y") - t1.shool_year IN(' . $list_year_old . ')';
    }

    if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $date_from, $m ) )
    {
        $date_from = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
    }
    else
    {
        $date_from = 0;
    }

    if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $date_to, $m ) )
    {
        $date_to = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
    }
    else
    {
        $date_to = 0;
    }

    if( $date_from > 0 && $date_to > 0 )
    {
        $sql_where .= ' AND (t1.remkt_time>=' . $date_from . ' AND t1.remkt_time<=' . $date_to . ')';
    }
    elseif( $date_from > 0 )
    {
        $sql_where .= ' AND t1.remkt_time>=' . $date_from;
    }
    elseif( $date_to > 0 )
    {
        $sql_where .= ' AND t1.remkt_time<=' . $date_to;
    }
    $array_status = implode( ',', $array_status );
    if( $array_status != '' )
    {
        $sql_where .= ' AND t1.status IN(' . $array_status . ')';
    }
   // $sql_where .= ' AND t1.from_by=13';

    $event_content = 0; //lay hoc sinh chua tung cham soc lan nao
    if( $event_content == 1 )
    {
        $sql_where .= ' AND t1.studentid NOT IN ( SELECT studentid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_events )';
    }
    $db->sqlreset()->select( 't1.*, t2.mobile AS pmobile, SQRT(POWER(( t1.gmap_lat - ' . $map_lat . ' ),2) + POWER(( t1.gmap_lng - ' . $map_lon . ' ),2)) *  PI() * 6457444.65 / 180 AS khoangcach' )->from( NV_PREFIXLANG . '_' . $module_data AS t1' )->join('LEFT JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_parent AS t2 ON t1.parentid=t2.parentid')->where( 'POWER((' . $to . ' * 180 )/ (6457444.65 * PI()),2)  >= (POWER(( t1.gmap_lat - ' . $map_lat . ' ),2) + POWER(( t1.gmap_lng - ' . $map_lon . ' ),2)) AND POWER((' . $from . ' * 180 )/ (6457444.65 * PI()),2)  <= (POWER(( t1.gmap_lat - ' . $map_lat . ' ),2) + POWER(( t1.gmap_lng - ' . $map_lon . ' ),2))' . $sql_where )->order( 'khoangcach ASC' );
    $result = $db->query( $db->sql() );
    while( $item = $result->fetch() )
    {
        //xuat thong tin ngay nghi hoc cua hoc sinh
        $sql = "SELECT endtime FROM " . NV_TABLE_DAYTOT . "_student_in_class WHERE usserid=" . $item['studentid'] . ' ORDER BY endtime DESC LIMIT 1';
        $result_stcls = $db->query( $sql );
        list( $item['remkt_time'] ) = $result_stcls->fetch( 3 );
        
        $item['khoangcach'] = round($item['khoangcach'],0);
        //$item['pmobile'] = $item['email'];
        $item['mobile'] = str_replace( 'So PH: ', '', $item['mobile'] );
        $item['birthday'] = date( 'd/m/Y', $item['birthday'] );
        $item['remkt_time'] = ( $item['remkt_time'] > 0 ) ? date( 'd/m/Y', $item['remkt_time'] ) : '';
        $array_data[] = $item;
    }
    if( ! empty( $array_data ) )
    {
        $url_content = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $map_lat . ',' . $map_lon;
        $json = @file_get_contents( $url_content );
        $data = json_decode( $json );
        $center_address = $data->results[0]->formatted_address;

        $address_content = $result = save_student_to_excel( $array_data, $center_address, $to, $from, $excel_ext, $writerType );
        exit( $result );
    }
}
elseif( $step == 2 )
{
    if( $nv_Request->isset_request( $module_data . '_export_filename', 'session' ) )
    {
        $export_filename = $nv_Request->get_string( $module_data . '_export_filename', 'session', '' );
        //Download file
        require_once NV_ROOTDIR . '/includes/class/download.class.php';
        $download = new download( NV_ROOTDIR . "/" . NV_CACHEDIR . '/' . $export_filename . '.' . $excel_ext, NV_ROOTDIR . "/" . NV_CACHEDIR, $export_filename . '.' . $excel_ext );
        $download->download_file();
        exit();
    }
}

function save_student_to_excel( $array_data, $center_address, $to, $from, $excel_ext, $writerType, $exampid, $timeid, $subjectid )
{
    global $module_file, $lang_module, $module_data, $nv_Request, $module_name, $array_school_type;

    $field = array(
        '0' => 'stt',
        '1' => 'studentid',
        '2' => 'student_name',
        '3' => 'mobile',
        '4' => 'pmobile',
        '5' => 'address',
        '6' => 'khoangcach',
        '7' => 'remkt_time' );

    // Create new PHPExcel object
    $objPHPExcel = PHPExcel_IOFactory::load( NV_ROOTDIR . '/modules/' . $module_file . '/template-student.' . $excel_ext );
    $objWorksheet = $objPHPExcel->getActiveSheet();
    $page_title = $lang_module['export_student'];
    // Setting a spreadsheet’s metadata
    $objPHPExcel->getProperties()->setCreator( "EDUSGROUP" );
    $objPHPExcel->getProperties()->setLastModifiedBy( "EDUSGROUP" );
    $objPHPExcel->getProperties()->setTitle( $page_title );
    $objPHPExcel->getProperties()->setSubject( $page_title );
    $objPHPExcel->getProperties()->setDescription( $page_title );
    $objPHPExcel->getProperties()->setKeywords( $page_title );
    $objPHPExcel->getProperties()->setCategory( $module_name );

    //cap nhat title
    $col = PHPExcel_Cell::stringFromColumnIndex( 0 );
    $CellValue = nv_unhtmlspecialchars( $center_address . ' ' . $lang_module['khoangcach_tim'] . ': ' . $from . ' - ' . $to . 'M' );
    $objWorksheet->setCellValue( $col . 3, $CellValue );

    // Rename sheet
    $objWorksheet->setTitle( nv_clean60( $page_title, 30 ) );

    // Ghi d? li?u b?t d?u t? dòng th? $i
    $i = 6;
    $stt = 1;
    foreach( $array_data as $student )
    {
        $student['stt'] = $stt;
        foreach( $field as $col_index => $column_name )
        {
            $col = PHPExcel_Cell::stringFromColumnIndex( $col_index );
            $CellValue = nv_unhtmlspecialchars( ' ' . $student[$column_name] );
            $objWorksheet->setCellValue( $col . $i, $CellValue );
        }
        $stt++;
        $i++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter( $objPHPExcel, $writerType );

    $file_name = change_alias( $page_title );
    $nv_Request->set_Session( $module_data . '_export_filename', $file_name );

    $objWriter->save( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $file_name . "." . $excel_ext );

    return 'COMPLETE';
}
