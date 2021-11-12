<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

if( empty( $array_producer )){
    Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=producer");
    die();
}
$table_name = NV_PREFIXLANG . "_" . $module_data . "_temcar";
if( $nv_Request->isset_request('delete', 'get', 0) ){

    $id = $nv_Request->get_int('id', 'post,get', 0);
    $contents = "NO_" . $id;

    if ($id > 0) {
        $sql = "DELETE FROM " . $table_name . " WHERE id=" . $id;
        if ($db->exec($sql)) {
            $contents = "OK_" . $id;
        }
    } else {
        $listall = $nv_Request->get_string('listall', 'post,get');
        $array_id = explode(',', $listall);
        $array_id = array_map("intval", $array_id);

        foreach ($array_id as $id) {
            if ($id > 0) {
                $sql = "DELETE FROM " . $table_name . " WHERE id=" . $id;
                $db->query($sql);
            }
        }
        $contents = "OK_0";
    }
    die($contents);
}

$page_title = $lang_module['temcar'];

$error = "";
$savecat = 0;

$data = array( "title" => "", 'note' => "", 'producerid' => 0, 'typecarid' => 0, 'price_listing' => '', 'price_negotiate' => '' );
$data['id'] = $nv_Request->get_int('id', 'post,get', 0);
$savecat = $nv_Request->get_int('savecat', 'post', 0);

if (! empty($savecat)) {
    $data['title'] = nv_substr($nv_Request->get_title('title', 'post', '', 1), 0, 255);
    $data['note'] = $nv_Request->get_title('note', 'post', '', 1);
    $data['producerid'] = $nv_Request->get_int('producerid', 'post',0);
    $data['typecarid'] = $nv_Request->get_int('typecarid', 'post',0);
    $data['numseats'] = $nv_Request->get_int('numseats', 'post',0);
    $data['price_listing'] = $nv_Request->get_title('price_listing', 'post',0);
    $data['price_listing'] = floatval(preg_replace('/[^0-9\,]/', '', $data['price_listing']));

    $data['price_negotiate'] = $nv_Request->get_title('price_negotiate', 'post',0);
    $data['price_negotiate'] = floatval(preg_replace('/[^0-9\,]/', '', $data['price_negotiate']));
    $data['image'] = $nv_Request->get_title('image', 'post', '');
    if (is_file(NV_DOCUMENT_ROOT . $data['image'])) {
        $data['image'] = substr($data['image'], strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/'));
    } else {
        $data['image'] = '';
    }
    if( $data['producerid'] == 0 ){
        $error = $lang_module['error_producerid'];
    }
    if( empty( $error )){
        if ($data['id'] == 0) {

            $sql = "INSERT INTO " . $table_name . " (id, producerid, typecarid, numseats, price_listing, price_negotiate, title, image, note) VALUES (NULL, " . $data['producerid'] . ",  " . $data['typecarid'] . "," . $data['numseats'] . ", " . $data['price_listing'] . ", " . $data['price_negotiate'] . ", " . $db->quote( $data['title'] ) . ", " . $db->quote( $data['image'] ) . ", " . $db->quote( $data['note'] ) . ")";

            if ($db->insert_id($sql)) {
                $nv_Cache->delMod($module_name);

                Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
                die();
            } else {
                $error = $lang_module['errorsave'];
            }
        } else {
            $stmt = $db->prepare("UPDATE " . $table_name . " SET title= :title, note = :note, producerid=:producerid, typecarid=:typecarid, numseats=:numseats, price_listing=:price_listing, price_negotiate=:price_negotiate, image=:image WHERE id =" . $data['id']);
            $stmt->bindParam(':title', $data['title'], PDO::PARAM_STR);
            $stmt->bindParam(':note', $data['note'], PDO::PARAM_STR);
            $stmt->bindParam(':image', $data['image'], PDO::PARAM_STR);
            $stmt->bindParam(':producerid', $data['producerid'], PDO::PARAM_INT);
            $stmt->bindParam(':typecarid', $data['typecarid'], PDO::PARAM_INT);
            $stmt->bindParam(':numseats', $data['numseats'], PDO::PARAM_INT);
            $stmt->bindParam(':price_listing', $data['price_listing'], PDO::PARAM_INT);
            $stmt->bindParam(':price_negotiate', $data['price_negotiate'], PDO::PARAM_INT);
            if ($stmt->execute()) {
                $error = $lang_module['saveok'];

                $nv_Cache->delMod($module_name);
                Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
                die();
            } else {
                $error = $lang_module['errorsave'];
            }
        }
    }

} else {
    if ($data['id'] > 0) {
        $data_old = $db->query("SELECT * FROM " . $table_name . " WHERE id=" . $data['id'])->fetch();
        $data = array(
            "id" => $data_old['id'],
            "title" => $data_old['title'],
            "note" => $data_old['note'],
            "numseats" => $data_old['numseats'],
            "typecarid" => $data_old['typecarid'],
            "producerid" => $data_old['producerid'],
            "image" => $data_old['image'],
            "price_listing" => $data_old['price_listing'],
            "price_negotiate" => $data_old['price_negotiate'],
        );

    }
}
if( $data['price_listing'] != ''){
    $data['price_listing'] = number_format( $data['price_listing'], 0, ',', '.');
}
if( $data['price_negotiate'] != ''){
    $data['price_negotiate'] = number_format( $data['price_negotiate'], 0, ',', '.');
}

if (!empty($data['image']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $data['image'])) {
    $data['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $data['image'];
}


$page = $nv_Request->get_int( 'page', 'get', 1 );
$data['producerid'] = $nv_Request->get_int( 'producerid', 'get', 0 );
$data['typecarid'] = $nv_Request->get_int( 'typecarid', 'get', 0 );
$qhtml = $nv_Request->get_title( 'q', 'get', '' );


if( $data['producerid'] > 0 )
{
    $where[] = " producerid = " .$data['producerid'];
    $base_url .= '&producerid=' . $data['producerid'];
}
if( $data['typecarid'] > 0 )
{
    $where[] = ' typecarid = ' . $data['typecarid'];
    $base_url .= '&typecarid=' . $data['typecarid'];
}
if( !empty( $qhtml ) )
{
    $where[] = "title LIKE '%" . $db_slave->dblikeescape( $qhtml ) . "%'";
    $base_url .= '&q=' . $qhtml;
}


$xtpl = new XTemplate($op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('DATA_POST', $data);
$xtpl->assign('NV_UPLOADS_DIR', NV_UPLOADS_DIR);
$xtpl->assign('MODULE_UPLOAD', $module_upload);
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'Q', $qhtml );

if( !empty( $error )){
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}
$count = 0;


$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
$per_page = 30;
$where[] = '1=1';
$db_slave->sqlreset()->select( 'COUNT(*)' )->from( $table_name  )->where( implode(' AND ', $where) );
$_sql = $db_slave->sql();
$num_items = $db_slave->query( $_sql )->fetchColumn();

$db_slave->select( '*' )->order( 'id DESC' )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );

$result = $db_slave->query( $db_slave->sql() );
while( $row = $result->fetch() )
{
    $row['producerid'] = isset( $array_producer[$row['producerid']])? $array_producer[$row['producerid']]['title'] : 'N/A';
    $row['typecarid'] = isset( $array_typecar[$row['typecarid']])? $array_typecar[$row['typecarid']]['title'] : 'N/A';
    $row['price_listing'] = number_format( $row['price_listing'], 0, ',', '.');
    $row['price_negotiate'] = number_format( $row['price_negotiate'], 0, ',', '.');
    $xtpl->assign('DATA', $row);
    $xtpl->assign('link_edit', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&id=" . $row['id']);
    $xtpl->assign('link_del', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&delete=1&id=" . $row['id']);

    $xtpl->parse('main.data.row');
    ++$count;
}
$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page );

if( !empty( $generate_page ) )
{
    $xtpl->assign( 'GENERATE_PAGE', $generate_page );
    $xtpl->parse( 'main.data.generate_page' );
}

foreach ( $array_producer as $producer ){
    $producer['sl'] = ( $data['producerid'] == $producer['id'])? ' selected=selected' : '';
    $xtpl->assign('PROCUDER', $producer );
    $xtpl->parse('main.producer');
    $xtpl->parse('main.data.producer');
}

foreach ( $array_typecar as $typecar ){
    $typecar['sl'] = ( $data['typecarid'] == $typecar['id'])? ' selected=selected' : '';
    $xtpl->assign('TYPECAR', $typecar );
    $xtpl->parse('main.typecar');
    $xtpl->parse('main.data.typecar');
}


$xtpl->assign('URL_DEL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . '&delete=1');
$xtpl->assign('URL_DEL_BACK', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);

if ($count > 0) {
    $xtpl->parse('main.data');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
