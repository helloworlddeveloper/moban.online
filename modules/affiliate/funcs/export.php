<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 3, 2010 11:33:22 AM
 */

if( ! defined( 'NV_IS_MOD_AFFILIATE' ) )
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

$arrray_color = array('7FFF00', 'D2691E', 'FF7F50', '6495ED', 'FFF8DC', 'DC143C', '00FFFF', '00008B', '008B8B', 'B8860B', 'A9A9A9', 'A9A9A9', '006400', 'BDB76B', '8B008B', '556B2F', 'FF8C00', '9932CC', '8B0000', 'E9967A', '8FBC8F', '483D8B', '2F4F4F', '2F4F4F', '00CED1', '9400D3', 'FF1493', '00BFFF', '696969', '696969', '1E90FF', 'B22222', 'FFFAF0', '228B22', 'FF00FF', 'DCDCDC', 'F8F8FF', 'FFD700', 'DAA520', '808080', '808080', '008000', 'ADFF2F', 'F0FFF0', 'FF69B4', 'CD5C5C', '4B0082', 'FFFFF0', 'F0E68C', 'E6E6FA', 'FFF0F5', '7CFC00', 'FFFACD', 'ADD8E6', 'F08080', 'E0FFFF', 'FAFAD2', 'D3D3D3', 'D3D3D3', '90EE90', 'FFB6C1', 'FFA07A', '20B2AA', '87CEFA', '778899', '778899', 'B0C4DE', 'FFFFE0', '00FF00', '32CD32', 'FAF0E6', 'FF00FF', '800000', '66CDAA', '0000CD', 'BA55D3', '9370DB', '3CB371', '7B68EE', '00FA9A', '48D1CC', 'C71585', '191970', 'F5FFFA', 'FFE4E1', 'FFE4B5', 'FFDEAD', '000080', 'FDF5E6', '808000', '6B8E23', 'FFA500', 'FF4500', 'DA70D6', 'EEE8AA', '98FB98', 'AFEEEE', 'DB7093', 'FFEFD5', 'FFDAB9', 'CD853F', 'FFC0CB', 'DDA0DD', 'B0E0E6', '800080', '663399', 'FF0000', 'BC8F8F', '4169E1', '8B4513', 'FA8072', 'F4A460', '2E8B57', 'FFF5EE', 'A0522D', 'C0C0C0', '87CEEB', '6A5ACD', '708090', '708090',
    'FFFAFA', '00FF7F', '4682B4', 'D2B48C', '008080', 'D8BFD8', 'FF6347', '40E0D0', 'EE82EE', 'F5DEB3', 'FFFFFF', 'F5F5F5', 'FFFF00', '9ACD32');

$data_field = array();
$step = $nv_Request->get_int( 'step', 'get,post', 1 );
$userid = $nv_Request->get_int( 'userid', 'get,post', 0 );
$checkss = $nv_Request->get_title( 'checkss', 'get,post', '' );
if( $userid > 0 && $checkss != md5($userid . $global_config['sitekey'] . session_id()) ){
    exit('ERROR');
}
if( $step == 1 )
{
    function getNameFromNumber($num) {
        $numeric = ($num - 1) % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval(($num - 1) / 26);
        if ($num2 > 0) {
            return getNameFromNumber($num2) . $letter;
        } else {
            return $letter;
        }
    }
    //bo khung san pham
    $BStyle = array(
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
            )
        )
    );
    function buld_data_to_excel($array_data, $i, $column_num, $num_user) {
        global $objWorksheet, $arrray_color, $BStyle;
        $column_num++;
        $row_old = $i;

        foreach( $array_data as $data )
        {
            if( !empty( $data )){
                $CellValue = $data['title_show'] . ' - ' . nv_show_name_user( $data['first_name'], $data['last_name'], $data['username'] ) . ' - ' . $data['mobile'] . ' - ' . $data['domain'];
                if( $data['haveorder'] == 1 ){
                    $CellValue .= ' [OK]';
                }

                $column_name = getNameFromNumber($column_num);
                $objWorksheet->setCellValue( $column_name . $i, $CellValue );

                $i++;
                if( !empty( $data['data'] )){
                    $info = buld_data_to_excel( $data['data'], $i, $column_num, $num_user );
                    $i = $info['row'];
                    $column = $info['column'];
                    if( isset( $info )){
                        $col_row_begin = $column . $row_old;
                        $col_row_end = $column . '' . $info['row'];

                        //$objWorksheet->getStyle($col_row_begin . ':' . $col_row_end)->applyFromArray($BStyle);//bi loi limit timeout
                        //$objWorksheet->mergeCells($col_row_begin . ':' . $col_row_end);
                    }
                }
            }
        }
        return array('row' => $i, 'column' => $column_name);
    }

    $array_reponse = array();
    global $objWorksheet;
    // Create new PHPExcel object
    $objPHPExcel = PHPExcel_IOFactory::load( NV_ROOTDIR . '/modules/' . $module_file . '/libs/so-do-he-thong.' . $excel_ext );
    $objWorksheet = $objPHPExcel->getActiveSheet();
    $page_title = $lang_module['so_do_he_thong_page_title'];
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


    $datanext = $_POST['datanext'];

    if( !empty( $datanext )){
        $session_data = $nv_Request->get_string($module_data . '_data_tree', 'session', '');
        $session_statistic = $nv_Request->get_string($module_data . '_data_statistic', 'session', '');
        if( !empty( $session_data )){
            $data_tree = unserialize( $session_data );
        }
        if( !empty( $session_statistic )){
            $array_statistic = unserialize( $session_statistic );
        }
        // $datanext = json_decode( $datanext );
        $datanext = unserialize( $datanext );
        $array_next = $datanext;
        foreach( $datanext as $key => $userid )
        {
            $tmp_tree[$userid] = nv_viewdirtree_toexcel( $userid, $config_data );
            $data_tree[$userid]['data'] = $tmp_tree;
            $nv_Request->set_Session($module_data . '_data_tree', serialize($data_tree) );
            $array_reponse['status'] = 'NEXT';
            unset( $array_next[$key] );
            if( !empty( $array_next  )){
                $array_reponse['mess'] = serialize( $array_next );
                nv_jsonOutput( $array_reponse );
            }
        }
    }else{
        $data_tree = array();
        $array_statistic = array();
        $nv_Request->unset_request($module_data . '_data_tree', 'session');
        $nv_Request->unset_request($module_data . '_data_statistic', 'session');
        $statistic_all = 0;
        if( $userid > 0 ){
            $sql = 'SELECT t1.*, t2.code, t2.numsubcat, t2.subcatid, t2.possitonid, t2.lev, t2.agencyid, t2.provinceid, t2.mobile FROM ' . NV_USERS_GLOBALTABLE . ' AS t1 INNER JOIN ' . $db_config['prefix'] . '_' . $module_data . '_users AS t2 ON t1.userid=t2.userid WHERE t2.status=1 AND t2.userid=' . $userid . ' ORDER BY sort';
        }
        else{
            $sql = 'SELECT t1.*, t2.code, t2.numsubcat, t2.subcatid, t2.possitonid, t2.lev, t2.agencyid, t2.provinceid, t2.mobile FROM ' . NV_USERS_GLOBALTABLE . ' AS t1 INNER JOIN ' . $db_config['prefix'] . '_' . $module_data . '_users AS t2 ON t1.userid=t2.userid WHERE t2.status=1 AND t2.userid=' . $user_info['userid'] . ' ORDER BY sort';
        }

        $res = $db->query( $sql );

        while ($array_data = $res->fetch()){
            if( $array_data['possitonid'] > 0 ){
                $array_data['title_show'] = isset( $array_possiton[$array_data['possitonid']] )? $array_possiton[$array_data['possitonid']]['title'] : 'N/A';
            }else{
                $array_data['title_show'] = isset( $array_agency[$array_data['agencyid']] )? $array_agency[$array_data['agencyid']]['title'] : 'N/A';
                if( $statistic_all == 1 ){
                    if( !isset( $array_statistic['type'][$array_data['agencyid']] )){
                        $array_statistic['type'][$array_data['agencyid']] = 1;
                    }else{
                        $array_statistic['type'][$array_data['agencyid']]++;
                    }
                    if( !isset( $array_statistic['level'][$array_data['lev']] )){
                        $array_statistic['level'][$array_data['lev']] = 1;
                    }else{
                        $array_statistic['level'][$array_data['lev']]++;
                    }
                }
            }

            $nv_Request->set_Session($module_data . '_data_statistic', serialize($array_statistic) );
            $data_tree[$array_data['userid']] = $array_data;
            $data_tree[$array_data['userid']]['data'] = array();

            if( $array_data['numsubcat'] > 0){
                $config_data = $module_config[$module_name];
                $config_data['config_postion'] = unserialize( $config_data['config_postion'] );

                $subcatid = explode(',', $array_data['subcatid'] );
                $array_next = $subcatid;
                $tmp_tree = array();
                foreach( $subcatid as $key => $userid )
                {
                    $tmp_tree[$userid] = nv_viewdirtree_toexcel( $userid, $config_data );
                    $data_tree[$array_data['userid']]['data'] = $tmp_tree;
                    $nv_Request->set_Session($module_data . '_data_tree', serialize($data_tree) );
                    $array_reponse['status'] = 'NEXT';
                    unset( $array_next[$key] );
                    if( !empty( $array_next  )){
                        $array_reponse['mess'] = serialize( $array_next );
                        nv_jsonOutput( $array_reponse );
                    }
                }

            }

        }
    }

    $i=4;
    $stt = 1;

    $style = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
        ),
        'font' => array(
            'bold' => true
        )
    );

    $column_num = 1;
    foreach( $data_tree as $data )
    {
        $CellValue = $data['title_show'] . ' - ' . nv_show_name_user( $data['first_name'], $data['last_name'], $data['username'] ) . ' - ' . $data['mobile'];
        $column_name = getNameFromNumber($column_num);
        $objWorksheet->setCellValue( $column_name . $i, $CellValue );
        $i++;
        $num_user = 0;
        if( !empty( $data['data'] )){
            $info = buld_data_to_excel( $data['data'], $i, $column_num, $num_user );
            $i = $info['row'];
            $column = $info['column'];
        }
    }
    $total = 0;
    $value_string = '';
    foreach ( $array_statistic['type'] as $key => $val ){
        $value_string .= $array_agency[$key]['title'] . ':' . $val . ' - ';
        $total+=$val;
    }
    $value_string .= ' Tổng: ' . $total;
    $objWorksheet->setCellValue( 'B2', $value_string );

    $value_string = '';
    $level_show = 1;
    //$total = 0;
    foreach ( $array_statistic['level'] as $key => $val ){
        $value_string .= 'Cấp ' . $level_show . ':' . $val . ' - ';
        $level_show++;
        // $total +=$val;
    }
    //$value_string .= ' Tổng: ' . $total;
    $objWorksheet->setCellValue( 'B3', $value_string );

    $col_row_begin = 'A4';
    $col_row_end = $column . '' . $i;
    //bo khung san pham
    $BStyle = array(
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
            )
        )
    );

    $objWorksheet->getStyle($col_row_begin . ':' . $col_row_end)->applyFromArray($BStyle);
    $objPHPExcel->getActiveSheet()->mergeCells('A2' . ':' . 'A'. $i);


    $objWriter = PHPExcel_IOFactory::createWriter( $objPHPExcel, $writerType );
    $file_name = strtolower( change_alias( $page_title ) );
    $nv_Request->set_Session( $module_data . '_export_filename', $file_name );
    try{
        $objWriter->save( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $file_name . "." . $excel_ext );
    } catch (Exception $e) {
        die($e->getMessage());
    }

    $array_reponse['status'] = 'OK';
    $array_reponse['mess'] = 'Xuất dữ liệu thành công!';
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

