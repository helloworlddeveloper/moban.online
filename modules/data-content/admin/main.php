<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 19 Jun 2014 10:09:33 GMT
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if ( $nv_Request->isset_request( 'get_alias_title', 'post' ) )
{
	$alias = $nv_Request->get_title( 'get_alias_title', 'post', '' );
	$alias = change_alias( $alias );
	die( $alias );
}

if ( $nv_Request->isset_request( 'delete_id', 'get' ) and $nv_Request->isset_request( 'delete_checkss', 'get' ))
{
	$id = $nv_Request->get_int( 'delete_id', 'get' );
	$delete_checkss = $nv_Request->get_string( 'delete_checkss', 'get' );
	if( $id > 0 and $delete_checkss == md5( $id . NV_CACHE_PREFIX . $client_info['session_id'] ) )
	{
		$db->query('DELETE FROM ' . $db_config['dbsystem'] . '.' .  NV_PREFIXLANG . '_' . $module_data . '  WHERE id = ' . $db->quote( $id ) );
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
		die();
	}
}

$listcats = nv_catList();
$row = array();
$error = array();
$row['id'] = $nv_Request->get_int( 'id', 'post,get', 0 );
if ( $nv_Request->isset_request( 'submit', 'post' ) )
{
    $row['catid'] = $nv_Request->get_int( 'catid', 'post', 0 );
	$row['title'] = $nv_Request->get_title( 'title', 'post', '' );
	$row['link'] = $nv_Request->get_title( 'link', 'post', '' );
    $row['bodytext'] = $nv_Request->get_editor('bodytext', '', NV_ALLOWED_HTML_TAGS);
	$row['status'] = $nv_Request->get_int( 'status', 'post', 0 );

	if( empty( $row['title'] ) )
	{
		$error[] = $lang_module['error_required_title'];
	}
    if( $row['catid'] == 0 )
    {
        $error[] = $lang_module['error_required_catid'];
    }
	if( empty( $error ) )
	{
		try
		{
			if( empty( $row['id'] ) )
			{
				$stmt = $db->prepare( 'INSERT INTO ' . $db_config['dbsystem'] . '.' .  NV_PREFIXLANG . '_' . $module_data . ' (title, bodytext, catid, link, status, addtime, edittime, author, total_rating, click_rating) VALUES (:title, :bodytext, :catid, :link, :status, ' . NV_CURRENTTIME .', 0, ' . $admin_info['userid'] .', 0, 0)' );
			}
			else
			{
				$stmt = $db->prepare( 'UPDATE ' . $db_config['dbsystem'] . '.' .  NV_PREFIXLANG . '_' . $module_data . ' SET catid =:catid, title = :title, bodytext=:bodytext, link = :link, status = :status, edittime=' . NV_CURRENTTIME . ' WHERE id=' . $row['id'] );
			}
            $stmt->bindParam( ':catid', $row['catid'], PDO::PARAM_INT );
            $stmt->bindParam(':bodytext', $row['bodytext'], PDO::PARAM_STR, strlen($row['bodytext']));
			$stmt->bindParam( ':title', $row['title'], PDO::PARAM_STR );
			$stmt->bindParam( ':link', $row['link'], PDO::PARAM_STR );
			$stmt->bindParam( ':status', $row['status'], PDO::PARAM_INT );

			$exc = $stmt->execute();
			if( $exc )
			{
                $nv_Cache->delMod($module_name);
				Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
				die();
			}
		}
		catch( PDOException $e )
		{
			trigger_error( $e->getMessage() );
			die( $e->getMessage() ); //Remove this line after checks finished
		}
	}
}
elseif( $row['id'] > 0 )
{
	$row = $db->query( 'SELECT * FROM ' . $db_config['dbsystem'] . '.' .  NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $row['id'] )->fetch();
	if( empty( $row ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
		die();
	}
}
else
{
	$row['id'] = $row['catid'] = 0;
	$row['title'] = '';
	$row['link'] = '';
    $row['bodytext'] = '';
	$row['status'] = 1;
}

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$row['bodytext'] = htmlspecialchars(nv_editor_br2nl($row['bodytext']));
if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $row['bodytext'] = nv_aleditor('bodytext', '100%', '300px', $row['bodytext']);
} else {
    $row['bodytext'] = '<textarea style="width:100%;height:300px" name="bodytext">' . $row['bodytext'] . '</textarea>';
}

$q = $nv_Request->get_title( 'q', 'post,get' );
$status = $nv_Request->get_int( 'status', 'post,get', '-1' );
$catid = $nv_Request->get_int( 'catid', 'post,get', '-1' );
// Fetch Limit
$sql_where = array();
$show_view = false;
if ( ! $nv_Request->isset_request( 'id', 'post,get' ) && !$nv_Request->isset_request( 'add', 'post,get' ) )
{
    if( !defined('NV_IS_SPADMIN')){
      //  $sql_where[] = ' AND t1.author=' . $admin_info['userid'];
    }
    if( $status >= 0 ){
        $sql_where[] = ' AND t1.status=' . $status;
    }
    if( $catid > 0 ){
        $sql_where[] = ' AND t1.catid=' . $catid;
    }

    if( ! empty( $q ) )
    {
        $sql_where[] = ' AND (t1.title LIKE :q_title OR t1.bodytext LIKE :q_bodytext)';
    }
    $show_view = true;
    $per_page = 20;
    $page = $nv_Request->get_int( 'page', 'post,get', 1 );
    $db->sqlreset()
        ->select( 'COUNT(*)' )
        ->from( '' . NV_PREFIXLANG . '_' . $module_data . ' AS t1' )
        ->join('LEFT JOIN ' . NV_USERS_GLOBALTABLE . ' AS t2 ON t1.author=t2.userid')
        ->where( '1=1 ' .  implode(' ', $sql_where));

	$sth = $db->prepare( $db->sql() );

	if( ! empty( $q ) )
	{
		$sth->bindValue( ':q_title', '%' . $q . '%' );
		$sth->bindValue( ':q_bodytext', '%' . $q . '%' );
	}
	$sth->execute();
	$num_items = $sth->fetchColumn();

	$db->select( 't1.*, t2.username' )
		->order( 'edittime ASC' )
		->limit( $per_page )
		->offset( ( $page - 1 ) * $per_page );
	$sth = $db->prepare( $db->sql() );

	if( ! empty( $q ) )
	{
		$sth->bindValue( ':q_title', '%' . $q . '%' );
		$sth->bindValue( ':q_bodytext', '%' . $q . '%' );
	}
	$sth->execute();
}
$array_select_status = array();
$array_select_status[0] = $lang_module['status_0'];
$array_select_status[1] = $lang_module['status_1'];

$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'ROW', $row );
$xtpl->assign( 'content_add', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&add' );

$xtpl->assign( 'Q', $q );

if( $show_view )
{
	$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
	if( ! empty( $q ) )
	{
		$base_url .= '&q=' . $q;
	}
    if( $catid > 0  )
    {
        $base_url .= '&catid=' . $catid;
    }
    if( $status >= 0  )
    {
        $base_url .= '&status=' . $status;
    }

	$xtpl->assign( 'NV_GENERATE_PAGE', nv_generate_page( $base_url, $num_items, $per_page, $page) );

	$number = 0;
	while( $view = $sth->fetch() )
	{
		$view['number'] = ++$number;
		$view['addtime'] = date('d/m/Y H:i', $view['addtime'] );
        $view['edittime'] = ( $view['edittime'] == 0 )? '' : date('d/m/Y H:i', $view['edittime'] );
		$view['catid'] = isset( $listcats[$view['catid']])? $listcats[$view['catid']]['title'] : 'N/A';
		if( $view['total_rating'] > 0){
            $view['rate_total']  = $view['total_rating'] / $view['click_rating'] . '/5';
        }else{
            $view['rate_total']  = 'N/A';
        }

        $view['status'] = $lang_module['status_' . $view['status']];
		$view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $view['id'];
		$view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_id=' . $view['id'] . '&amp;delete_checkss=' . md5( $view['id'] . NV_CACHE_PREFIX . $client_info['session_id'] );
		$xtpl->assign( 'VIEW', $view );
		$xtpl->parse( 'main.view.loop' );
	}
    foreach( $array_select_status as $key => $title )
    {
        $xtpl->assign( 'OPTION', array(
            'key' => $key,
            'title' => $title,
            'selected' => ($key == $status ) ? ' selected="selected"' : ''
        ) );
        $xtpl->parse( 'main.view.select_status' );
    }
    foreach( $listcats as $cat )
    {
        $cat['selected'] = ( $catid == $cat['id'] ) ? ' selected=selected' : '';
        $xtpl->assign( 'LISTCATS', $cat );
        $xtpl->parse( 'main.view.select_catid' );
    }
	$xtpl->parse( 'main.view' );
}
else{

    foreach( $listcats as $cat )
    {
        $cat['selected'] = ( $row['catid'] == $cat['id'] ) ? ' selected=selected' : '';
        $xtpl->assign( 'LISTCATS', $cat );
        $xtpl->parse( 'main.add.catid' );
    }

    foreach( $array_select_status as $key => $title )
    {
        $xtpl->assign( 'OPTION', array(
            'key' => $key,
            'title' => $title,
            'selected' => ($key == $row['status']) ? ' selected="selected"' : ''
        ) );
        $xtpl->parse( 'main.add.select_status' );
    }

    if( !empty( $error )){
        $xtpl->assign( 'ERROR', implode('<br/>', $error ) );
        $xtpl->parse( 'main.add.error' );
    }
    $xtpl->parse( 'main.add' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

$page_title = $lang_module['main'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';