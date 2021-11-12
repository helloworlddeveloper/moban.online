<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Mr.Thang <contact@vinades.vn>
 * @Copyright (C) 2014 Mr.Thang. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (!defined('NV_IS_MOD_WORKFORCE')) die('Stop!!!');

if (!nv_user_in_groups($array_config['group_add_workforce'])) {
    Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=main');
    die();
}

$rowcontent = array(
    'id' => '',
    'producerid' => 0,
    'unitid' => 0,
    'producttypeid' => 0,
    'departmentid' => 0,
    'title' => '',
    'time_in' => '',
    'status' => 1 );

$page_title = $lang_module['product_add'];
$error = array();

$currentpath = NV_UPLOADS_DIR . '/' . $module_upload . '/';
$rowcontent['id'] = $nv_Request->get_int( 'id', 'get,post', 0 );
if( $rowcontent['id'] > 0 )
{
    $rowcontent = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_product where id=' . $rowcontent['id'] )->fetch();
    if( !empty( $rowcontent['id'] ) )
    {
        $rowcontent['mode'] = 'edit';
    }
    $page_title = $lang_module['product_edit'];

    $rowcontent['amount'] = number_format( $rowcontent['amount'], 0, ',', '.');
    $rowcontent['price'] = number_format( $rowcontent['price'], 0, ',', '.');
}

if( $nv_Request->get_int( 'save', 'post' ) == 1 )
{
    $rowcontent['producerid'] = $nv_Request->get_int( 'producerid', 'post', 0 );
    $rowcontent['unitid'] = $nv_Request->get_int( 'unitid', 'post', 0 );
    $rowcontent['producttypeid'] = $nv_Request->get_int( 'producttypeid', 'post', 0 );
    $rowcontent['departmentid'] = $nv_Request->get_int( 'departmentid', 'post', 0 );
    $rowcontent['room_use'] = nv_substr($nv_Request->get_title('room_use', 'post', '', 1), 0, 250);
    $rowcontent['code'] = nv_substr($nv_Request->get_title('code', 'post', '', 1), 0, 250);
    $rowcontent['status'] = $nv_Request->get_int( 'status', 'post', 0 );
    $rowcontent['title'] = $nv_Request->get_title( 'title', 'post', '', 1 );
    $rowcontent['time_in'] = $nv_Request->get_title( 'time_in', 'post', '' );
    $rowcontent['time_depreciation'] = $nv_Request->get_int( 'time_depreciation', 'post', 0 );
    $rowcontent['amount'] = $nv_Request->get_title( 'amount', 'post', 0);
    $rowcontent['amount'] = floatval(preg_replace('/[^0-9\,]/', '', $rowcontent['amount']));
    $rowcontent['price'] = $nv_Request->get_title( 'price', 'post', '' );
    $rowcontent['price'] = floatval(preg_replace('/[^0-9\,]/', '', $rowcontent['price']));

    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $rowcontent['time_in'], $m)) {
        $rowcontent['time_in'] = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
    } else {
        $rowcontent['time_in'] = 0;
    }

    // Kiem tra ma san pham trung
    $error_product_code = false;
    if (!empty($rowcontent['product_code'])) {
        $stmt = $db->prepare('SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_product WHERE code= :code AND id!=' . $rowcontent['id']);
        $stmt->bindParam(':code', $rowcontent['code'], PDO::PARAM_STR);
        $stmt->execute();
        $id_err = $stmt->rowCount();

        $stmt = $db->prepare('SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE code= :code');
        $stmt->bindParam(':code', $rowcontent['code'], PDO::PARAM_STR);
        $stmt->execute();
        if ($rowcontent['id'] == 0 and $stmt->rowCount()) {
            $error_product_code = true;
        } elseif ($id_err) {
            $error_product_code = true;
        }
    }

    if ($error_product_code) {
        $error = $lang_module['error_product_code'];
    }
    if( !$rowcontent['title']){
        $error[] = $lang_module['error_title_product'];
    }if ($rowcontent['producerid'] == 0){
        $error[] = $lang_module['error_producerid'];
    }if ($rowcontent['producttypeid'] == 0){
        $error[] = $lang_module['error_producttypeid'];
    }
    if ($rowcontent['departmentid'] == 0){
        $error[] = $lang_module['error_departmentid'];
    }
    if ($rowcontent['unitid'] == 0){
        $error[] = $lang_module['error_unitid'];
    }
    if ($rowcontent['time_in'] == 0){
        $error[] = $lang_module['error_time_in'];
    }
    if ($rowcontent['time_depreciation'] == 0){
        $error[] = $lang_module['error_time_depreciation'];
    }
    if ($rowcontent['amount'] == 0){
        $error[] = $lang_module['error_amount'];
    }
    if ($rowcontent['price'] == 0){
        $error[] = $lang_module['error_price'];
    }

    if( empty( $error ) )
    {
        if( $rowcontent['id'] == 0 )
        {
            $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_product (
                producerid, departmentid, producttypeid, unitid, room_use, code, title, time_in, time_depreciation, price, amount, addtime, status
            ) VALUES (
                 ' . intval( $rowcontent['producerid'] ) . ',
                 ' . intval( $rowcontent['departmentid'] ) . ',
                 ' . intval( $rowcontent['producttypeid'] ) . ',
                 ' . intval( $rowcontent['unitid'] ) . ',
                 :room_use,
                 :code,
                 :title,
                 ' . intval( $rowcontent['time_in'] ) . ',
                 ' . intval( $rowcontent['time_depreciation'] ) . ',
                 ' . floatval( $rowcontent['price'] ) . ',
                 ' . intval( $rowcontent['amount'] ) . ',
                 ' . NV_CURRENTTIME . ',
                 ' . floatval( $rowcontent['status'] ) . '
            )';

            $data_insert = array();
            $data_insert['code'] = $rowcontent['code'];
            $data_insert['title'] = $rowcontent['title'];
            $data_insert['room_use'] = $rowcontent['room_use'];

            $rowcontent['id'] = $db->insert_id( $sql, 'id', $data_insert );
            if( $rowcontent['id'] > 0 )
            {
                $nv_Cache->delMod( $module_name );
            }
            else
            {
                $error[] = $lang_module['errorsave'];
            }
        }
        else
        {
            try
            {
                $sth = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_product SET
                producerid=' . intval( $rowcontent['producerid'] ) . ',
                departmentid=' . $rowcontent['departmentid'] . ',
                producttypeid=' . $rowcontent['producttypeid'] . ',
                unitid=' . $rowcontent['unitid'] . ',
                status=' . $rowcontent['status'] . ',
                code=:code,
                title=:title,
                room_use=:room_use,
                time_in=' . intval( $rowcontent['time_in'] ) . ',
                time_depreciation=' . intval( $rowcontent['time_depreciation'] ) . ',
                price=' . floatval( $rowcontent['price'] ) . ',
                amount=' . intval( $rowcontent['amount'] ) . '
                WHERE id =' . $rowcontent['id'] );

                $sth->bindParam( ':code', $rowcontent['code'], PDO::PARAM_STR );
                $sth->bindParam( ':title', $rowcontent['title'], PDO::PARAM_STR );
                $sth->bindParam( ':room_use', $rowcontent['room_use'], PDO::PARAM_STR );

                if( $sth->execute() )
                {
                    $nv_Cache->delMod( $module_name );
                }
                else
                {
                    $error[] = $lang_module['errorsave'];
                }
            }
            catch ( PDOException $Exception )
            {
                // Note The Typecast To An Integer!
                die( $Exception->getMessage() );
            }

        }
        if( empty( $error ) )
        {
            Header( 'Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=product' );
            die();
        }
    }
}


if(  $rowcontent['time_in'] > 0 )
{
    $rowcontent['time_in'] = date('d/m/Y', $rowcontent['time_in'] );
}

if( empty( $array_global_cat ) )
{
    $redirect = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=producer';
    $contents = nv_theme_alert( $lang_module['note_cat_title'], $lang_module['note_cat_content'], 'warning', $redirect, $lang_module['categories'] );

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme( $contents );
    include NV_ROOTDIR . '/includes/footer.php';
}
$contents = '';
if( $rowcontent['id'] > 0 ){
    $rowcontent['link_warehouse'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=warehouse&listid=' . $rowcontent['id'];
}else{
    $rowcontent['link_warehouse'] = 'javascript:void(0);';
}

$xtpl = new XTemplate( $op . '.tpl',  NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'rowcontent', $rowcontent );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'MODULE_FILE', $module_file );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
if( !empty( $error ) )
{
    $xtpl->assign( 'ERROR', implode( '<br />', $error ) );
    $xtpl->parse( 'main.error' );
}

$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'module_name', $module_name );

foreach( $array_global_cat as $cat )
{
    $cat['sl'] = ( $cat['id'] == $rowcontent['producerid'] ) ? ' selected="selected"' : '';
    $xtpl->assign( 'CATS', $cat );
    $xtpl->parse( 'main.select_cat' );
}

foreach( $array_units as $units )
{
    $units['sl'] = ( $units['id'] == $rowcontent['unitid'] ) ? ' selected="selected"' : '';
    $xtpl->assign( 'UNITS', $units );
    $xtpl->parse( 'main.select_unit' );
}

foreach( $array_department as $department )
{
    $department['sl'] = ( $department['id'] == $rowcontent['departmentid'] ) ? ' selected="selected"' : '';
    $xtpl->assign( 'DEPARMENT', $department );
    $xtpl->parse( 'main.select_department' );
}
foreach( $array_producttype as $producttype )
{
    $producttype['sl'] = ( $producttype['id'] == $rowcontent['producttypeid'] ) ? ' selected="selected"' : '';
    $xtpl->assign( 'PRODUCTTYPE', $producttype );
    $xtpl->parse( 'main.select_producttype' );
}

$array_status = array( '0' => $lang_module['status_0'], '1' => $lang_module['status_1'] );
foreach( $array_status as $key => $_status )
{
    $sl = ( $key == $rowcontent['status'] ) ? ' selected="selected"' : '';
    $xtpl->assign( 'STATUS', array(
        'sl' => $sl,
        'key' => $key,
        'title' => $_status ) );
    $xtpl->parse( 'main.status' );
}

if( empty( $rowcontent['alias'] ) )
{
    $xtpl->parse( 'main.getalias' );
}
$xtpl->assign( 'UPLOADS_DIR', $currentpath );

$xtpl->parse('main');
$contents = $xtpl->text('main');

// $xtpl->parse( 'main' );
// $contents = nv_theme_workforce_control( $array_control );
// $contents .= $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
