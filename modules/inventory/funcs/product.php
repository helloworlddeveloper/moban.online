<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Mr.Thang <contact@vinades.vn>
 * @Copyright (C) 2010 - 2014 Mr.Thang. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 08 Apr 2012 00:00:00 GMT
 */
if (!defined('NV_IS_MOD_WORKFORCE')) die('Stop!!!');

if (!nv_user_in_groups($array_config['group_add_workforce'])) {
    Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=main');
    die();
}
if ($nv_Request->isset_request('export', 'get,post')) {
    
    if (!file_exists(NV_ROOTDIR . '/includes/class/PHPExcel.php')) {
        die(strip_tags($lang_module['required_phpexcel']));
    }
    require_once NV_ROOTDIR . '/includes/class/PHPExcel.php';
    $excel_ext = "xlsx";
    $writerType = 'Excel2007';
    
    $data_field = array();
    $step = $nv_Request->get_int('step', 'get,post', 1);
    $act = $nv_Request->get_title('act', 'post', '');
    $time_export = $nv_Request->get_title('time_export', 'post', '');
    if ($step == 1) {
        $array_reponse = array();
        if (preg_match('/^([0-9]{1,2})\/([0-9]{4})$/', $time_export, $m)) {
            if ($m[1] > 12) {
                $array_reponse['status'] = 'error';
                $array_reponse['mess'] = $lang_module['error_time_export'];
                nv_jsonOutput($array_reponse);
            }
            $max_day = cal_days_in_month(CAL_GREGORIAN, $m[1], $m[2]);
            $time_export = mktime(0, 0, 0, $m[1], $max_day, $m[2]);
        } else {
            $array_reponse['status'] = 'error';
            $array_reponse['mess'] = $lang_module['error_time_export'];
            nv_jsonOutput($array_reponse);
        }
        if ($time_export == 0) {
            $array_reponse['status'] = 'error';
            $array_reponse['mess'] = $lang_module['error_time_export'];
            nv_jsonOutput($array_reponse);
        }
        if ($act == 'khauhaotaisan') {
            
            // Create new PHPExcel object
            $objPHPExcel = PHPExcel_IOFactory::load(NV_ROOTDIR . '/modules/' . $module_file . '/libs/bangkhauhaotaisan.' . $excel_ext);
            $objWorksheet = $objPHPExcel->getActiveSheet();
            $page_title = $lang_module['page_title_khauhaotaisan'] . ' ' . date('m-Y', $time_export);
            // Rename sheet
            
            $objWorksheet->setTitle(nv_clean60($page_title, 30));

            if( !empty( $module_config[$module_name]['headerfile']) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $module_config[$module_name]['headerfile'])){
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setName('Logo');
                $objDrawing->setDescription('Logo');
                $objDrawing->setPath(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $module_config[$module_name]['headerfile']);
                $objDrawing->setOffsetX(8);    // setOffsetX works properly
                $objDrawing->setOffsetY(0);  //setOffsetY has no effect
                $objDrawing->setCoordinates( 'A1' );
                $objDrawing->setHeight(100); // thumb height
                $objDrawing->setWidth(100); // thumb height
                $objDrawing->setWorksheet($objWorksheet);
            }
            //cong ty
            $objWorksheet->setCellValue( 'C1', $module_config[$module_name]['companyname'] );
            $objWorksheet->setCellValue( 'C2', $module_config[$module_name]['address'] );

            // Setting a spreadsheet’s metadata
            $objPHPExcel->getProperties()->setCreator("NukeViet CMS");
            $objPHPExcel->getProperties()->setLastModifiedBy("NukeViet CMS");
            $objPHPExcel->getProperties()->setTitle($page_title);
            $objPHPExcel->getProperties()->setSubject($page_title);
            $objPHPExcel->getProperties()->setDescription($page_title);
            $objPHPExcel->getProperties()->setKeywords($page_title);
            $objPHPExcel->getProperties()->setCategory($module_name);
            
            // Set page orientation and size
            $objWorksheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
            $objWorksheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
            $objWorksheet->getPageSetup()->setHorizontalCentered(true);
            $objWorksheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 3);
            
            //thoi gian kiem ke tai san
            $objWorksheet->setCellValue('A6', $lang_module['month'] . ' ' . date('m/Y', $time_export));
            
            $i = 9;
            
            $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_product WHERE time_in<=' . $time_export;
            $stt = 1;
            
            $result = $db->query($sql);
            $numrow = $result->rowCount();
            $field = array(
                '0' => 'tt',
                '1' => 'time_in',
                '2' => 'title',
                '3' => 'price',
                '4' => 'tiendakhauhao',
                '5' => 'price_conlai',
                '6' => 'time_depreciation',
                '7' => 'tienkhauhaothang',
                '8' => '',
                '9' => ''
            );
            $stt = 1;
            $total_giatri = $total_dakhauhao = $total_conlai = 0;
            $array_data = array();
            while ($row = $result->fetch()) {
                $row['tt'] = $stt++;
                
                $timekhauhao = $time_export - $row['time_in'];
                $giatrikhauhaongay = $row['price'] / $row['time_depreciation'] / 30;
                $row['tienkhauhaothang'] = $row['price'] / $row['time_depreciation'];
                $timekhauhao = ceil($timekhauhao / 86400); //quy doi ra ngay
                $row['tiendakhauhao'] = ($giatrikhauhaongay * $timekhauhao);
                $row['price_conlai'] = $row['price'] - $row['tiendakhauhao'];
                $row['time_depreciation'] = $row['time_depreciation'] / 12;
                $total_giatri += $row['price'];
                $total_dakhauhao += $row['tiendakhauhao'];
                $total_conlai += $row['price_conlai'];
                
                if (isset($array_data[$row['producttypeid']]['total']['price'])) {
                    $array_data[$row['producttypeid']]['total']['price'] += $row['price'];
                    $array_data[$row['producttypeid']]['total']['tiendakhauhao'] += $row['tiendakhauhao'];
                    $array_data[$row['producttypeid']]['total']['price_conlai'] += $row['price_conlai'];
                    $array_data[$row['producttypeid']]['total']['tienkhauhaothang'] += $row['tienkhauhaothang'];
                } else {
                    $array_data[$row['producttypeid']]['total']['price'] = $row['price'];
                    $array_data[$row['producttypeid']]['total']['tiendakhauhao'] = $row['tiendakhauhao'];
                    $array_data[$row['producttypeid']]['total']['price_conlai'] = $row['price_conlai'];
                    $array_data[$row['producttypeid']]['total']['tienkhauhaothang'] = $row['tienkhauhaothang'];
                }
                
                $row['tienkhauhaothang'] = number_format($row['tienkhauhaothang'], 0, '.', '');
                $row['tiendakhauhao'] = number_format($row['tiendakhauhao'], 0, '.', '');
                $row['price_conlai'] = number_format($row['price_conlai'], 0, '.', '');
                $row['price'] = number_format($row['price'], 0, '.', '');
                $row['amount'] = number_format($row['amount'], 0, '.', '');
                $row['time_in'] = date('d/m/Y', $row['time_in']);
                $array_data[$row['producttypeid']]['data'][] = $row;
            }
            
            //tao dong trong bang truoc khi import
            if ($numrow > 0) {
                $numrow--;
                $numrow = $numrow + count($array_data);
                $num_row_insert = $i+1;
                $objPHPExcel->getActiveSheet()->insertNewRowBefore($num_row_insert, $numrow);
            }
            $style = array(
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT
                ),
                'font' => array(
                    'bold' => true
                )
            );
            foreach ($array_data as $producttype => $data_type) {
                $objWorksheet->setCellValue('A' . $i, $array_producttype[$producttype]['title']);
                
                $row_merge_cells = 'A' . $i . ":" . 'C' . $i;
                $objWorksheet->getStyle($row_merge_cells)->applyFromArray($style);
                $objWorksheet->mergeCells($row_merge_cells);
                
                $objWorksheet->setCellValue('D' . $i, $data_type['total']['price']);
                $objWorksheet->setCellValue('E' . $i, $data_type['total']['tiendakhauhao']);
                $objWorksheet->setCellValue('F' . $i, $data_type['total']['price_conlai']);
                $objWorksheet->setCellValue('H' . $i, $data_type['total']['tienkhauhaothang']);
                
                $i++;
                foreach ($data_type['data'] as $data) {
                    $data['stt'] = $stt++;
                    foreach ($field as $key => $column) {
                        $col = PHPExcel_Cell::stringFromColumnIndex($key);
                        $objWorksheet->setCellValue($col . $i, $data[$column]);
                    }
                    $i++;
                }
            }
            
            //tong gia tri
            $objWorksheet->setCellValue('D' . $i, $total_giatri);
            $objWorksheet->setCellValue('E' . $i, $total_dakhauhao);
            $objWorksheet->setCellValue('F' . $i, $total_conlai);

            $i = $i + 8;
            //cong ty
            $objWorksheet->setCellValue( 'A' . $i, $module_config[$module_name]['leader_name'] );
            $objWorksheet->setCellValue( 'D' . $i, $module_config[$module_name]['accountant_name'] );
            $objWorksheet->setCellValue( 'H' . $i, $module_config[$module_name]['creators_name'] );


            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $writerType);
            
            $file_name = change_alias($page_title);
            $nv_Request->set_Session($module_data . '_export_filename', $file_name);
            $objWriter->save(NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $file_name . "." . $excel_ext);
            $array_reponse['status'] = 'OK';
            $array_reponse['mess'] = 'OK';
            nv_jsonOutput($array_reponse);
        } elseif ($act == 'phanboccdc') {
            // Create new PHPExcel object
            $objPHPExcel = PHPExcel_IOFactory::load(NV_ROOTDIR . '/modules/' . $module_file . '/libs/banphanbodungcu.' . $excel_ext);
            $objWorksheet = $objPHPExcel->getActiveSheet();
            $page_title = $lang_module['page_title_banphanbodungcu'] . ' ' . date('m-Y', $time_export);
            // Rename sheet
            $objWorksheet->setTitle(nv_clean60($page_title, 30));

            if( !empty( $module_config[$module_name]['headerfile']) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $module_config[$module_name]['headerfile'])){
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setName('Logo');
                $objDrawing->setDescription('Logo');
                $objDrawing->setPath(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $module_config[$module_name]['headerfile']);
                $objDrawing->setOffsetX(8);    // setOffsetX works properly
                $objDrawing->setOffsetY(0);  //setOffsetY has no effect
                $objDrawing->setCoordinates( 'A1' );
                $objDrawing->setHeight(100); // thumb height
                $objDrawing->setWidth(100); // thumb height
                $objDrawing->setWorksheet($objWorksheet);
            }
            //cong ty
            $objWorksheet->setCellValue( 'C1', $module_config[$module_name]['companyname'] );
            $objWorksheet->setCellValue( 'C2', $module_config[$module_name]['address'] );

            // Setting a spreadsheet’s metadata
            $objPHPExcel->getProperties()->setCreator("NukeViet CMS");
            $objPHPExcel->getProperties()->setLastModifiedBy("NukeViet CMS");
            $objPHPExcel->getProperties()->setTitle($page_title);
            $objPHPExcel->getProperties()->setSubject($page_title);
            $objPHPExcel->getProperties()->setDescription($page_title);
            $objPHPExcel->getProperties()->setKeywords($page_title);
            $objPHPExcel->getProperties()->setCategory($module_name);
            
            // Set page orientation and size
            $objWorksheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
            $objWorksheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
            $objWorksheet->getPageSetup()->setHorizontalCentered(true);
            $objWorksheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 3);
            
            //thoi gian kiem ke tai san
            $objWorksheet->setCellValue('A6', $lang_module['month'] . ' ' . date('m/Y', $time_export));
            
            $i = 9;
            
            $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_product WHERE time_in<=' . $time_export;
            $stt = 1;
            
            $result = $db->query($sql);
            $numrow = $result->rowCount();
            $field = array(
                '0' => 'tt',
                '1' => 'time_in',
                '2' => 'code',
                '3' => 'title',
                '4' => 'price',
                '5' => 'tiendakhauhao',
                '6' => 'price_conlai',
                '7' => 'time_depreciation',
                '8' => 'tienkhauhaothang',
                '9' => '',
                '10' => ''
            );
            $stt = 1;
            $total_giatri = $total_dakhauhao = $total_conlai = $total_phanbohangthang = 0;
            $array_data = array();
            while ($row = $result->fetch()) {
                $row['tt'] = $stt++;
                
                $timekhauhao = $time_export - $row['time_in'];
                $giatrikhauhaongay = $row['price'] / $row['time_depreciation'] / 30;
                $row['tienkhauhaothang'] = $row['price'] / $row['time_depreciation'];
                
                $timekhauhao = ceil($timekhauhao / 86400); //quy doi ra ngay
                $row['tiendakhauhao'] = ($giatrikhauhaongay * $timekhauhao);
                $row['price_conlai'] = $row['price'] - $row['tiendakhauhao'];
                
                $total_giatri += $row['price'];
                $total_dakhauhao += $row['tiendakhauhao'];
                $total_conlai += $row['price_conlai'];
                $total_phanbohangthang += $row['tienkhauhaothang'];
                if (isset($array_data[$row['producttypeid']]['total']['price'])) {
                    $array_data[$row['producttypeid']]['total']['price'] += $row['price'];
                    $array_data[$row['producttypeid']]['total']['tiendakhauhao'] += $row['tiendakhauhao'];
                    $array_data[$row['producttypeid']]['total']['price_conlai'] += $row['price_conlai'];
                    $array_data[$row['producttypeid']]['total']['tienkhauhaothang'] += $row['tienkhauhaothang'];
                } else {
                    $array_data[$row['producttypeid']]['total']['price'] = $row['price'];
                    $array_data[$row['producttypeid']]['total']['tiendakhauhao'] = $row['tiendakhauhao'];
                    $array_data[$row['producttypeid']]['total']['price_conlai'] = $row['price_conlai'];
                    $array_data[$row['producttypeid']]['total']['tienkhauhaothang'] = $row['tienkhauhaothang'];
                }
                
                $row['tienkhauhaothang'] = number_format($row['tienkhauhaothang'], 0, '.', '');
                $row['tiendakhauhao'] = number_format($row['tiendakhauhao'], 0, '.', '');
                $row['price_conlai'] = number_format($row['price_conlai'], 0, '.', '');
                $row['price'] = number_format($row['price'], 0, '.', '');
                $row['amount'] = number_format($row['amount'], 0, '.', '');
                $row['time_in'] = date('d/m/Y', $row['time_in']);
                $array_data[$row['producttypeid']]['data'][] = $row;
            }
            
            //tao dong trong bang truoc khi import
            if ($numrow > 0) {
                $numrow--;
                $numrow = $numrow + count($array_data);
                $num_row_insert = $i + 1;
                $objPHPExcel->getActiveSheet()->insertNewRowBefore($num_row_insert, $numrow);
            }
            $style = array(
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT
                ),
                'font' => array(
                    'bold' => true
                )
            );
            foreach ($array_data as $producttype => $data_type) {
                $objWorksheet->setCellValue('A' . $i, $array_producttype[$producttype]['title']);
                
                $row_merge_cells = 'A' . $i . ":" . 'D' . $i;
                $objWorksheet->mergeCells($row_merge_cells);
                $objWorksheet->getStyle($row_merge_cells)->applyFromArray($style);
                
                $objWorksheet->setCellValue('E' . $i, $data_type['total']['price']);
                $objWorksheet->setCellValue('F' . $i, $data_type['total']['tiendakhauhao']);
                $objWorksheet->setCellValue('G' . $i, $data_type['total']['price_conlai']);
                $objWorksheet->setCellValue('I' . $i, $data_type['total']['tienkhauhaothang']);
                
                $i++;
                foreach ($data_type['data'] as $data) {
                    $data['stt'] = $stt++;
                    foreach ($field as $key => $column) {
                        $col = PHPExcel_Cell::stringFromColumnIndex($key);
                        $objWorksheet->setCellValue($col . $i, $data[$column]);
                    }
                    $i++;
                }
            }
            
            //tong gia tri
            $objWorksheet->setCellValue('E' . $i, $total_giatri);
            $objWorksheet->setCellValue('F' . $i, $total_dakhauhao);
            $objWorksheet->setCellValue('G' . $i, $total_conlai);
            $objWorksheet->setCellValue('I' . $i, $total_phanbohangthang);

            $i = $i + 9;
            //cong ty
            $objWorksheet->setCellValue( 'A' . $i, $module_config[$module_name]['leader_name'] );
            $objWorksheet->setCellValue( 'D' . $i, $module_config[$module_name]['accountant_name'] );
            $objWorksheet->setCellValue( 'H' . $i, $module_config[$module_name]['creators_name'] );

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $writerType);
            
            $file_name = change_alias($page_title);
            $nv_Request->set_Session($module_data . '_export_filename', $file_name);
            $objWriter->save(NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $file_name . "." . $excel_ext);
            $array_reponse['status'] = 'OK';
            $array_reponse['mess'] = 'OK';
            nv_jsonOutput($array_reponse);
        }
    } elseif ($step == 2 and $nv_Request->isset_request($module_data . '_export_filename', 'session')) {
        
        $file_name = $nv_Request->get_string($module_data . '_export_filename', 'session', '');
        if (!empty($file_name) and file_exists(NV_ROOTDIR . '/' . NV_CACHEDIR . '/' . $file_name . '.' . $excel_ext)) {
            $download = new NukeViet\Files\Download(NV_ROOTDIR . '/' . NV_CACHEDIR . '/' . $file_name . '.' . $excel_ext, NV_ROOTDIR . '/' . NV_CACHEDIR, $file_name . '.' . $excel_ext);
            $download->download_file();
            exit();
        }
    }
}

if ($nv_Request->isset_request('delete_id', 'get') and $nv_Request->isset_request('delete_checkss', 'get')) {
    $id = $nv_Request->get_int('delete_id', 'get');
    $delete_checkss = $nv_Request->get_string('delete_checkss', 'get');
    
    $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_product WHERE id=' . $id;
    list ($id_delete) = $db->query($sql)->fetch(3);
    
    if ($id > 0 and $id_delete > 0 and $delete_checkss == md5($id . NV_CACHE_PREFIX . $client_info['session_id'])) {
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_product  WHERE id = ' . $db->quote($id));
        $nv_Cache->delMod($module_name);
        Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
        die();
    }
}

$page_title = $lang_module['product_list'];
$sstatus = $nv_Request->get_int('sstatus', 'get', -1);
$departmentid = $nv_Request->get_int('departmentid', 'get', 0);
$per_page_old = $nv_Request->get_int('per_page', 'cookie', 50);
$per_page = $nv_Request->get_int('per_page', 'get', $per_page_old);
$num_items = $nv_Request->get_int('num_items', 'get', 0);

if ($per_page < 1 and $per_page > 500) {
    $per_page = 50;
}
if ($per_page_old != $per_page) {
    $nv_Request->set_Cookie('per_page', $per_page, NV_LIVE_COOKIE_TIME);
}

$q = $nv_Request->get_title('q', 'get', '');
$q = str_replace('+', ' ', $q);
$qhtml = nv_htmlspecialchars($q);

if ($sstatus < 0 or $sstatus > 1) {
    $sstatus = -1;
}

$from = NV_PREFIXLANG . '_' . $module_data . '_product';

$where = array();
$page = $nv_Request->get_int('page', 'get', 1);
$checkss = $nv_Request->get_string('checkss', 'get', '');

if ($checkss == NV_CHECK_SESSION) {
    if ($departmentid > 0) {
        $where[] = " departmentid = " . $departmentid;
    }
    if ($sstatus != -1) {
        $where[] = ' status = ' . $sstatus;
    }
    if (!empty($q)) {
        $where[] = "title LIKE '%" . $db_slave->dblikeescape($qhtml) . "%'";
    }
}

$db_slave->sqlreset()
    ->select('COUNT(*)')
    ->from($from)
    ->where(implode(' AND ', $where));

$_sql = $db_slave->sql();

$num_checkss = md5($num_items . NV_CHECK_SESSION . $_sql);
if ($num_checkss != $nv_Request->get_string('num_checkss', 'get', '')) {
    $num_items = $db_slave->query($_sql)->fetchColumn();
    $num_checkss = md5($num_items . NV_CHECK_SESSION . $_sql);
}
$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;per_page=' . $per_page;
if ($departmentid) {
    $base_url .= '&amp;departmentid=' . $departmentid;
}
if (!empty($q)) {
    $base_url .= '&amp;q=' . $q . '&amp;checkss=' . $checkss;
}
$base_url .= '&amp;num_items=' . $num_items . '&amp;num_checkss=' . $num_checkss;

for ($i = 0; $i <= 1; $i++) {
    $sl = ($i == $sstatus) ? ' selected="selected"' : '';
    $search_status[] = array(
        'key' => $i,
        'value' => $lang_module['status_' . $i],
        'selected' => $sl
    );
}

$i = 5;
$search_per_page = array();
while ($i <= 500) {
    $search_per_page[] = array(
        'page' => $i,
        'selected' => ($i == $per_page) ? ' selected="selected"' : ''
    );
    $i = $i + 5;
}

$generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('addproduct', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=addproduct');
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_SITEURL);
$xtpl->assign('NV_ASSETS_DIR', NV_ASSETS_DIR);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('TEMPLATE', $module_info['template']);
$xtpl->assign('OP', $op);
$xtpl->assign('Q', $qhtml);

$db_slave->select('*')
    ->order('addtime DESC')
    ->limit($per_page)
    ->offset(($page - 1) * $per_page);

$result = $db_slave->query($db_slave->sql());
while ($row = $result->fetch()) {
    $row['addtime'] = nv_date('H:i d/m/y', $row['addtime']);
    $row['department'] = $array_department[$row['departmentid']]['title'];
    $row['status'] = $lang_module['status_' . $row['status']];
    $row['price'] = number_format($row['price'], 0, ',', '.');
    $row['amount'] = number_format($row['amount'], 0, ',', '.');
    $row['time_in'] = date('d/m/Y', $row['time_in']);
    $row['link_edit'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=addproduct&id=' . $row['id'];
    $row['link_delete'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_id=' . $row['id'] . '&amp;delete_checkss=' . md5($row['id'] . NV_CACHE_PREFIX . $client_info['session_id']);
    $xtpl->assign('ROW', $row);
    
    $xtpl->parse('main.loop');
}

foreach ($array_department as $cat) {
    $cat['selected'] = ($cat['id'] == $departmentid) ? ' selected=selected' : '';
    $xtpl->assign('CAT_CONTENT', $cat);
    $xtpl->parse('main.cat_content');
}

foreach ($search_per_page as $s_per_page) {
    $xtpl->assign('SEARCH_PER_PAGE', $s_per_page);
    $xtpl->parse('main.s_per_page');
}

foreach ($search_status as $status_view) {
    $xtpl->assign('SEARCH_STATUS', $status_view);
    $xtpl->parse('main.search_status');
}

if (!empty($generate_page)) {
    $xtpl->assign('GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.generate_page');
}
$xtpl->parse('main');
$contents = $xtpl->text('main');

// $xtpl->parse( 'main' );
// $contents = nv_theme_workforce_control( $array_control );
// $contents .= $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
