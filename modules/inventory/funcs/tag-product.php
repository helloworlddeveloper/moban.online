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
    $id = $nv_Request->get_int('id', 'get,post', 0);
    if ($step == 1) {
        $array_reponse = array();

        // Create new PHPExcel object
        $objPHPExcel = PHPExcel_IOFactory::load(NV_ROOTDIR . '/modules/' . $module_file . '/libs/thetaisan.' . $excel_ext);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $page_title = $lang_module['page_title_thetaisan'];
        // Rename sheet

        $objWorksheet->setTitle(nv_clean60($page_title, 30));

        //cong ty
        $objWorksheet->setCellValue( 'A1', $module_config[$module_name]['companyname'] );
        $objWorksheet->setCellValue( 'A2', $module_config[$module_name]['address'] );

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

        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tscd WHERE id=' . $id;
        $result = $db->query($sql);
        $data_tscd = $result->fetch();
        if( !empty( $data_tscd )){
            $db_slave->sqlreset()->select( '*' )->from( NV_PREFIXLANG . '_' . $module_data . '_tscd_detail' )->order('id ASC')->where( 'tscdid=' . $id );
            $data_detail = $db->query( $db_slave->sql() )->fetchAll();

            $db_slave->sqlreset()->select( '*' )->from( NV_PREFIXLANG . '_' . $module_data . '_tscd_dungcu' )->order('id ASC')->where( 'tscdid=' . $id );
            $data_dungcu = $db->query( $db_slave->sql() )->fetchAll();
        }
        $field_detail = array(
            '0' => 'sochungtu',
            '1' => 'ngaynhap',
            '2' => 'price',
            '3' => 'namsudung',
            '4' => 'giatrihaomon',
            '5' => 'luyke'
        );
        $field_dungcu = array(
            '0' => 'stt',
            '1' => 'tencongcu',
            '2' => 'donvitinh',
            '3' => 'soluong',
            '4' => 'giatri'
        );
        $stt = 1;
        $i = 17;
        $lang_module['export_tscd_title_1'] = sprintf( $lang_module['export_tscd_title_1'], $data_tscd['sohopdong'], date('d', $data_tscd['timeinput']), date('m', $data_tscd['timeinput']), date('Y', $data_tscd['timeinput']) );
        $lang_module['export_tscd_title_2'] = sprintf( $lang_module['export_tscd_title_2'], $data_tscd['tenkyhieu'], $data_tscd['sokyhieu'] );
        $lang_module['export_tscd_title_3'] = sprintf( $lang_module['export_tscd_title_3'], $data_tscd['nuocsanxuat'], $data_tscd['namsanxuat'] );
        $lang_module['export_tscd_title_4'] = sprintf( $lang_module['export_tscd_title_4'], $data_tscd['bophanquanly'], $data_tscd['namsudung'] );
        $lang_module['export_tscd_title_5'] = sprintf( $lang_module['export_tscd_title_5'], $data_tscd['congsuat'] );
        $lang_module['export_tscd_title_6'] = sprintf( $lang_module['export_tscd_title_6'], date('d', $data_tscd['ngaydinhchi']), date('m', $data_tscd['ngaydinhchi']), date('Y', $data_tscd['ngaydinhchi']) );
        $lang_module['export_tscd_title_7'] = sprintf( $lang_module['export_tscd_title_7'], $data_tscd['lydodinhchi'] );
        $lang_module['export_tscd_title_8'] = sprintf( $lang_module['export_tscd_title_8'], $data_tscd['ghigiamtscd'], date('d', $data_tscd['ngayghigiamtscd']), date('m', $data_tscd['ngayghigiamtscd']), date('Y', $data_tscd['ngayghigiamtscd']) );
        $colfor_insert = 5;
        for( $col_for = 6; $col_for <= 12; $col_for++){
            $col_i = $col_for - $colfor_insert;
            $objWorksheet->setCellValue('A' . $col_for, $lang_module['export_tscd_title_' . $col_i] );
        }
        if( !empty( $data_detail )){
            $numrow = count($data_detail);
            //tao dong trong bang truoc khi import
            if ($numrow > 1) {
                $numrow--;
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
            foreach ($data_detail as $detail) {
                foreach ($field_detail as $key => $column) {
                    $col = PHPExcel_Cell::stringFromColumnIndex($key);
                    if( $column == 'ngaynhap'){
                        $detail[$column] = date('d/m/Y', $detail[$column]);
                    }
                    $objWorksheet->setCellValue($col . $i, $detail[$column]);
                }
                $i++;
            }
        }

        if( !empty( $data_dungcu )){
            $i+= 5;
            $numrow = count($data_dungcu);
            //tao dong trong bang truoc khi import
            if ($numrow > 1) {
                $numrow--;
                $num_row_insert = $i+1;
                $objPHPExcel->getActiveSheet()->insertNewRowBefore($num_row_insert, $numrow);
            }
            $style = array(
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
                )
            );
            foreach ($data_dungcu as $dungcu) {
                foreach ($field_dungcu as $key => $column) {
                    $col = PHPExcel_Cell::stringFromColumnIndex($key);
                    $objWorksheet->setCellValue($col . $i, $dungcu[$column]);
                }
                $row_merge_cells = 'E' . $i . ":" . 'F' . $i;
                $objWorksheet->mergeCells($row_merge_cells);
                $objWorksheet->getStyle($row_merge_cells)->applyFromArray($style);
                $i++;
            }
        }
        $i+=1;
        $objWorksheet->setCellValue('A' . $i, $lang_module['export_tscd_title_8'] );

        $i = $i + 6;
        //cong ty
        $objWorksheet->setCellValue( 'A' . $i, $module_config[$module_name]['creators_name'] );
        $objWorksheet->setCellValue( 'C' . $i, $module_config[$module_name]['accountant_name'] );
        $objWorksheet->setCellValue( 'E' . $i, $module_config[$module_name]['leader_name'] );

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $writerType);

        $file_name = change_alias($page_title);
        $nv_Request->set_Session($module_data . '_export_filename', $file_name);
        $objWriter->save(NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $file_name . "." . $excel_ext);
        $array_reponse['status'] = 'OK';
        $array_reponse['mess'] = 'OK';
        nv_jsonOutput($array_reponse);

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

    $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tscd WHERE id=' . $id;
    list ($id_delete) = $db->query($sql)->fetch(3);

    if ($id > 0 and $id_delete > 0 and $delete_checkss == md5($id . NV_CACHE_PREFIX . $client_info['session_id'])) {
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tscd  WHERE id = ' . $db->quote($id));
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tscd_detail  WHERE tscdid = ' . $db->quote($id));
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tscd_dungcu  WHERE tscdid = ' . $db->quote($id));
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

$from = NV_PREFIXLANG . '_' . $module_data . '_tscd';

$where = array();
$page = $nv_Request->get_int('page', 'get', 1);
$checkss = $nv_Request->get_string('checkss', 'get', '');

if ($checkss == NV_CHECK_SESSION) {
    if (!empty($q)) {
        $where[] = "(tenkyhieu LIKE '%" . $db_slave->dblikeescape($qhtml) . "%' OR sokyhieu LIKE '%" . $db_slave->dblikeescape($qhtml) . "%'  OR sohopdong LIKE '%" . $db_slave->dblikeescape($qhtml) . "%' )";
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
$xtpl->assign('addtag', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=content-tag');
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
    $row['bienbangiaonhan'] = $row['sohopdong'] . ' ' . nv_date('d/m/Y', $row['timeinput']);
    $row['link_edit'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=content-tag&id=' . $row['id'];
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
