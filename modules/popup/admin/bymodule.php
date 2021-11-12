<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Mr.Thang (kid.apt@gmail.com)
 * @Copyright (C) 2012 Mr.Thang. All rights reserved
 * @Createdate 2-10-2010 20:59
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) )
    die( 'Stop!!!' );

if( $nv_Request->isset_request( 'get_alias_title', 'post' ) )
{
    $get_alias_title = $nv_Request->get_title( 'get_alias_title', 'post', '', 1 );
    $get_alias_title = change_alias( strtolower( $get_alias_title ) );
    exit( $get_alias_title );
}
elseif( $nv_Request->isset_request( 'loadfuntion', 'post' ) )
{
    $mod_name = $nv_Request->get_title( 'module_name', 'post', '', 1 );
    $fun_op = $nv_Request->get_title( 'function_op', 'post', '', 1 );
    $html = '<option value="">----------</option>';
    foreach( $site_mods[$mod_name]['funcs'] as $key => $function_op )
    {
        $sl = ( $key == $fun_op ) ? ' selected=selected' : '';
        $html .= '<option' . $sl . ' value=' . $key . '>' . $function_op['func_custom_name'] . '</option>';
    }
    exit( $html );
}
elseif( $nv_Request->isset_request( 'loaddatabase', 'post' ) )
{
    $mod_name = $nv_Request->get_title( 'module_name', 'post', '', 1 );
    $table_sl = $nv_Request->get_title( 'table', 'post', '', 1 );
    if( isset( $site_mods[$mod_name] ) )
    {
        if( file_exists( NV_ROOTDIR . '/modules/' . $site_mods[$mod_name]['module_file'] . '/popup_connect.php' ) )
        {
            $html = '<option value="">----------</option>';
            include NV_ROOTDIR . '/modules/' . $site_mods[$mod_name]['module_file'] . '/popup_connect.php';
            $array_table_key_name = nv_popup_get_table( $site_mods[$mod_name]['module_data'] );
            foreach( $array_table_key_name as $table => $data )
            {
                $sl = ( $table == $table_sl ) ? ' selected=selected' : '';
                $html .= '<option' . $sl . ' value=' . $table . '>' . $table . '</option>';
            }
            exit( $html );
        }
    }
    exit( '' );

}

elseif( $nv_Request->isset_request( 'search_itemid', 'get' ) )
{
    $table_mysql = $nv_Request->get_title( 'table_mysql', 'get', '', 1 );
    $mod_name = $nv_Request->get_title( 'module_name', 'get', '', 1 );
    if( isset( $site_mods[$mod_name] ) )
    {
        if( file_exists( NV_ROOTDIR . '/modules/' . $site_mods[$mod_name]['module_file'] . '/popup_connect.php' ) )
        {
            include NV_ROOTDIR . '/modules/' . $site_mods[$mod_name]['module_file'] . '/popup_connect.php';
            $array_table_key_name = nv_popup_get_table( $site_mods[$mod_name]['module_data'] );
            $q = $nv_Request->get_title( 'term', 'get', '', 1 );
            if( empty( $q ) && empty( $table_mysql ) )
                return;
            $colum_key = $array_table_key_name[$table_mysql]['keycolumn'];
            $colum_title = $array_table_key_name[$table_mysql]['keytitle'];
            $db->sqlreset()->select( $colum_key . ',' . $colum_title )->from( $table_mysql )->where( $colum_title . " LIKE '%" . $q . "%'" )->limit( 20 );
            $sth = $db->prepare( $db->sql() );
            $sth->execute();

            $array_data = array();
            while( list( $catid, $title ) = $sth->fetch( 3 ) )
            {
                $array_data[] = array( 'key' => $catid, 'value' => $title );
            }

            header( 'Cache-Control: no-cache, must-revalidate' );
            header( 'Content-type: application/json' );

            ob_start( 'ob_gzhandler' );
            echo json_encode( $array_data );
        }
    }

    exit();
}

$action = $nv_Request->get_title( 'action', 'get', '' );

if( $nv_Request->isset_request( 'delete_id', 'get' ) and $nv_Request->isset_request( 'delete_checkss', 'get' ) )
{
    $delete_id = $nv_Request->get_int( 'delete_id', 'get' );
    $delete_checkss = $nv_Request->get_string( 'delete_checkss', 'get' );
    if( $delete_id > 0 and $delete_checkss == md5( $delete_id . NV_CACHE_PREFIX . $client_info['session_id'] ) )
    {
        $db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_bymodule  WHERE id = ' . $db->quote( $delete_id ) );
        Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
        die();
    }
}

$row = array();
$error = array();
$row['id'] = $nv_Request->get_int( 'id', 'post,get', 0 );
if( $nv_Request->isset_request( 'save_only', 'post' ) )
{
    $row['title'] = $nv_Request->get_title( 'title', 'post', '' );
    $row['alias'] = $nv_Request->get_title( 'alias', 'post', '' );
    $row['image'] = $nv_Request->get_title( 'image', 'post', '' );
    $row['module_name'] = $nv_Request->get_title( 'module_name', 'post', '' );
    $row['op_name'] = $nv_Request->get_title( 'op_name', 'post', '' );
    $row['table_mysql'] = $nv_Request->get_title( 'table_mysql', 'post', '' );
    $row['itemid'] = $nv_Request->get_int( 'itemid', 'post', 0 );
    $row['description'] = $nv_Request->get_string( 'description', 'post', '' );
    $row['showtype'] = $nv_Request->get_int( 'showtype', 'post', 0 );
    $row['link_download'] = $nv_Request->get_title( 'link_download', 'post', '' );
    $row['popup_on'] = $nv_Request->get_int( 'popup_on', 'post', 0 );
    $row['action_popup'] = $nv_Request->get_string( 'action_popup', 'post', '' );
    if( ! nv_is_url( $row['image'] ) and is_file( NV_DOCUMENT_ROOT . $row['image'] ) )
    {
        $lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' );
        $row['image'] = substr( $row['image'], $lu );
    }
    else
    {
       $row['image'] = '';
    }

    if( ! nv_is_url( $row['link_download'] ) and is_file( NV_DOCUMENT_ROOT . $row['link_download'] ) )
    {
        $lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' );
        $row['link_download'] = substr( $row['link_download'], $lu );
    }
    elseif( ! nv_is_url( $row['link_download'] ) )
    {
        $row['link_download'] = '';
    }

    if( empty( $row['title'] ) )
    {
        $error[] = $lang_module['error_required_title'];
    }
    if( empty( $row['image'] ) )
    {
        $error[] = $lang_module['error_required_image'];
    }
    if( empty( $error ) )
    {
        $row['add_time'] = NV_CURRENTTIME;
        $row['edit_time'] = NV_CURRENTTIME;
        $row['status'] = 1;
        try
        {
            $insert = 1;
            if( empty( $row['id'] ) )
            {
                $stmt = $db->prepare( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_bymodule (title, alias, image, description, module_name, op_name, table_show, contentid, showtype, link_download, numview, numclick, numdownload, popup_on, action_popup, add_time, edit_time, status) VALUES (:title, :alias, :image, :description, :module_name, :op_name, :table_show, :contentid, :showtype, :link_download, 0, 0, 0, :popup_on, :action_popup, :add_time, :edit_time, :status)' );
                $stmt->bindParam( ':add_time', $row['add_time'], PDO::PARAM_INT );
            }
            else
            {
                $insert = 0;
                $stmt = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_bymodule SET title=:title, alias=:alias, image=:image, description=:description, module_name=:module_name, op_name=:op_name, table_show=:table_show, contentid=:contentid, showtype=:showtype, link_download=:link_download, popup_on=:popup_on, action_popup=:action_popup, edit_time=:edit_time, status=:status WHERE id=' . $row['id'] );
            }
            $stmt->bindParam( ':title', $row['title'], PDO::PARAM_STR );
            $stmt->bindParam( ':alias', $row['alias'], PDO::PARAM_STR );
            $stmt->bindParam( ':image', $row['image'], PDO::PARAM_STR );
            $stmt->bindParam( ':description', $row['description'], PDO::PARAM_STR, strlen( $row['description'] ) );
            $stmt->bindParam( ':module_name', $row['module_name'], PDO::PARAM_STR );
            $stmt->bindParam( ':op_name', $row['op_name'], PDO::PARAM_STR );
            $stmt->bindParam( ':table_show', $row['table_mysql'], PDO::PARAM_STR );
            $stmt->bindParam( ':contentid', $row['itemid'], PDO::PARAM_INT );
            $stmt->bindParam( ':showtype', $row['showtype'], PDO::PARAM_INT );
            $stmt->bindParam( ':link_download', $row['link_download'], PDO::PARAM_STR );
            $stmt->bindParam( ':popup_on', $row['popup_on'], PDO::PARAM_STR );
            $stmt->bindParam( ':action_popup', $row['action_popup'], PDO::PARAM_STR );
            $stmt->bindParam( ':edit_time', $row['edit_time'], PDO::PARAM_INT );
            $stmt->bindParam( ':status', $row['status'], PDO::PARAM_INT );
            $exc = $stmt->execute();
            if( $exc )
            {
                Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
                die();
            }
        }
        catch ( PDOException $e )
        {
            trigger_error( $e->getMessage() );
            $error[] = $e->getMessage();
        }
    }
}
elseif( $row['id'] > 0 )
{
    $row = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_bymodule WHERE id=' . $row['id'] )->fetch();
    if( empty( $row ) )
    {
        Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
        die();
    }
    $row['itemid'] = $row['contentid'];
    $row['table_mysql'] = $row['table_show'];
}
else
{
    $row['title'] = $row['alias'] = $row['image'] = $row['link_download'] = $row['module_name'] = $row['op_name'] = $row['table_mysql'] = $row['description'] = '';
    $row['itemid'] = $row['forclass'] = $row['popup_on'] = $row['showtype'] = 0;
}
if( ! empty( $row['image'] ) )
{
    $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $row['image'];
    
}
if( ! empty( $row['link_download'] ) && ! nv_is_url( $row['link_download'] ) )
{
    $row['link_download'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $row['link_download'];
}
$search_ajax = array();
if( $row['itemid'] > 0 )
{
    if( isset( $site_mods[$row['module_name']] ) )
    {
        if( file_exists( NV_ROOTDIR . '/modules/' . $site_mods[$row['module_name']]['module_file'] . '/popup_connect.php' ) )
        {
            $mod_name = $site_mods[$row['module_name']]['module_data'];
            include NV_ROOTDIR . '/modules/' . $site_mods[$row['module_name']]['module_file'] . '/popup_connect.php';

            $array_table_key_name = nv_popup_get_table( $mod_name );
            $info_query = $array_table_key_name[$row['table_mysql']];
            list( $search_ajax['itemid'], $search_ajax['title'] ) = $db->query( 'SELECT ' . $info_query['keycolumn'] . ', ' . $info_query['keytitle'] . ' FROM ' . $row['table_mysql'] . ' WHERE ' . $info_query['keycolumn'] . '=' . $row['itemid'] )->fetch( 3 );

        }
    }

}

$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'MODULE_FILE', $module_file );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'action', $action );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'ROW', $row );
$xtpl->assign( 'addschool', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&action=add' );
$xtpl->assign( 'UPLOADS_DIR', NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name );

if( $row['id'] > 0 or $action == 'add' )
{
    if( defined( 'NV_EDITOR' ) )
    {
        require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
    }

    if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
    {
        $edits = nv_aleditor( 'description', '100%', '300px', $row['description'] );
    }
    else
    {
        $edits = "<textarea style=\"width: 100%\" name=\"description\" id=\"description\" cols=\"20\" rows=\"15\">" . $row['description'] . "</textarea>";
    }
    if( ! empty( $search_ajax ) )
    {
        $xtpl->assign( 'SEARCH', $search_ajax );
        $xtpl->parse( 'main.add_row.search_ajax' );
    }

    $xtpl->assign( 'description', $edits );

    if( ! empty( $error ) )
    {
        $xtpl->assign( 'ERROR', implode( '<br />', $error ) );
        $xtpl->parse( 'main.add_row.error' );
    }

    foreach( $site_mods as $modules => $mod_info )
    {
        if( file_exists( NV_ROOTDIR . '/modules/' . $mod_info['module_file'] . '/popup_connect.php' ) )
        {
            $sl = ( $row['module_name'] == $modules ) ? ' selected=selected' : '';
            $xtpl->assign( 'MODULE_INFO', array(
                'module_name' => $modules,
                'module_tilte' => $mod_info['custom_title'],
                'sl' => $sl ) );
            $xtpl->parse( 'main.add_row.mod_info' );
        }
    }
    if( $row['id'] == 0 )
    {
        $xtpl->parse( 'main.add_row.auto_get_alias' );
    }
    if( ! empty( $row['module_name'] ) )
    {
        $xtpl->assign( 'mod_name', $row['module_name'] );
        $xtpl->parse( 'main.add_row.load_data_show' );
    }
    $array_popup_on = array(
        0 => $lang_module['popup_on_0'],
        1 => $lang_module['popup_on_1'],
        2 => $lang_module['popup_on_2'],
        );
    foreach( $array_popup_on as $key => $title )
    {
        $sl = ( $key == $row['popup_on'] ) ? ' selected=selected' : '';
        $xtpl->assign( 'POPUP_ON', array(
            'key' => $key,
            'title' => $title,
            'sl' => $sl ) );
        $xtpl->parse( 'main.add_row.popup_on' );
    }
    $array_showtype = array(
        0 => $lang_module['showtype_0'],
        1 => $lang_module['showtype_1'],
        2 => $lang_module['showtype_2'],
    );
    foreach( $array_showtype as $key => $title )
    {
        $sl = ( $key == $row['showtype'] ) ? ' selected=selected' : '';
        $xtpl->assign( 'SHOWTYPE', array(
            'key' => $key,
            'title' => $title,
            'sl' => $sl ) );
        $xtpl->parse( 'main.add_row.showtype' );
    }
    $xtpl->parse( 'main.add_row' );

}
else
{
    $q = $nv_Request->get_title( 'q', 'post,get' );
    $module_search = $nv_Request->get_title( 'module_search', 'post,get' );
    $status = $nv_Request->get_int( 'status', 'post,get', -1 );
    $xtpl->assign( 'Q', $q );

    $per_page = 30;
    $page = $nv_Request->get_int( 'page', 'post,get', 1 );
    $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

    $db->sqlreset()->select( 'COUNT(*)' )->from( '' . NV_PREFIXLANG . '_' . $module_data . '_bymodule' );
    $sql_where = '';
    if( ! empty( $q ) )
    {
        $sql_where = '(title LIKE :q_title OR description LIKE :q_description)';
        $base_url .= '&q=' . $q;
    }
    if( ! empty( $module_search ) )
    {
        $sql_where .= ( ! empty( $sql_where ) ) ? ' AND module_name=' . $db->quote( $module_search ) : 'module_name=' . $db->quote( $module_search );
        $base_url .= '&module_name=' . $module_search;
    }
    if( $status >= 0 )
    {
        $sql_where .= ( ! empty( $sql_where ) ) ? ' AND status=' . $status : 'status=' . $status;
        $base_url .= '&status=' . $status;
    }
    $db->where( $sql_where );

    $sth = $db->prepare( $db->sql() );

    if( ! empty( $q ) )
    {
        $sth->bindValue( ':q_title', '%' . $q . '%' );
        $sth->bindValue( ':q_description', '%' . $q . '%' );
    }
    $sth->execute();
    $num_items = $sth->fetchColumn();

    $db->select( '*' )->order( 'add_time DESC' )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );
    $sth = $db->prepare( $db->sql() );

    if( ! empty( $q ) )
    {
        $sth->bindValue( ':q_title', '%' . $q . '%' );
        $sth->bindValue( ':q_description', '%' . $q . '%' );
    }
    $sth->execute();

    $page_title = $lang_module['bymodule'];

    $xtpl->assign( 'NV_GENERATE_PAGE', nv_generate_page( $base_url, $num_items, $per_page, $page ) );
    while( $view = $sth->fetch() )
    {
        $view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $view['id'];
        $view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_id=' . $view['id'] . '&amp;delete_checkss=' . md5( $view['id'] . NV_CACHE_PREFIX . $client_info['session_id'] );
        $view['add_time'] = date( 'd/m/Y H:i', $view['add_time'] );
        $view['edit_time'] = date( 'd/m/Y H:i', $view['edit_time'] );
        $view['status'] = $lang_module['bymodule_status_' . $view['status']];
        $xtpl->assign( 'VIEW', $view );

        $xtpl->parse( 'main.view.loop' );
    }

    $array_status = array(
        1 => $lang_module['bymodule_status_1'],
        0 => $lang_module['bymodule_status_0'],
        );
    foreach( $array_status as $key => $title )
    {
        $sl = ( $key == $status ) ? ' selected=selected' : '';
        $xtpl->assign( 'STATUS', array(
            'key' => $key,
            'title' => $title,
            'sl' => $sl ) );
        $xtpl->parse( 'main.view.status_select' );
    }
    
    foreach( $site_mods as $key => $mods )
    {
        if( file_exists( NV_ROOTDIR . '/modules/' . $mods['module_file'] . '/popup_connect.php' ) )
        {
            $sl = ( $key == $module_search ) ? ' selected=selected' : '';
            $xtpl->assign( 'MODULE', array(
                'key' => $key,
                'title' => $mods['custom_title'],
                'sl' => $sl ) );
            $xtpl->parse( 'main.view.module_search' );
        }

    }
    $xtpl->parse( 'main.view' );
}
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
