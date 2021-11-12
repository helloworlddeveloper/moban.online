<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2016 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 14 Apr 2011 12:01:30 GMT
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) )
	die( 'Stop!!!' );

$catList = nv_catList();
$nv_row = nv_rowList();
$page_title = $lang_module['main'];
$contents = "";

$sql = "SELECT COUNT(*) as count FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows";
$result = $db->query( $sql );
$count = $result->fetch();

if( empty( $count['count'] ) and ! $nv_Request->isset_request( 'add', 'get' ) )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&add" );
	die();
}

$xtpl = new XTemplate( $op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'MODULE_URL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE );
$xtpl->assign( 'UPLOADS_DIR_USER', NV_UPLOADS_DIR . '/' . $module_name );
$xtpl->assign( 'UPLOAD_CURRENT', NV_UPLOADS_DIR . '/' . $module_name );
$xtpl->assign( 'NV_ADMIN_THEME', $global_config['module_theme'] );
$xtpl->assign( 'module', $module_data );

if( $nv_Request->isset_request( 'add', 'get' ) or $nv_Request->isset_request( 'edit, id', 'get' ) )
{

	if( defined( 'NV_EDITOR' ) )
	{
		require_once (NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php');
	}

	$post = array();
	$is_error = false;
	$info = "";

	if( $nv_Request->isset_request( 'edit, id', 'get' ) )
	{
		$post['id'] = $nv_Request->get_int( 'id', 'get', 0 );

		$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE id=" . $post['id'];
		$result = $db->query( $sql );
		$num = $result->rowCount();
		if( $num != 1 )
		{
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
			die();
		}

		$row = $result->fetch();
	}

	if( $nv_Request->isset_request( 'submit', 'post' ) )
	{
		$post['title'] = $nv_Request->get_title( 'title', 'post', '', 1 );
		
		$post['link'] = $nv_Request->get_title( 'link', 'post', '', 1 );
		$post['html'] = $nv_Request->get_editor( 'html', '', NV_ALLOWED_HTML_TAGS );
		$post['catid'] = $nv_Request->get_int( 'catid', 'post', 0 );

		$publ_date = $nv_Request->get_title( 'pubtime', 'post', '' );
		if( ! empty( $publ_date ) and preg_match( "/^([0-9]{1,2})\\/([0-9]{1,2})\/([0-9]{4})$/", $publ_date, $m ) )
		{
			$phour = $nv_Request->get_int( 'phour', 'post', 0 );
			$pmin = $nv_Request->get_int( 'pmin', 'post', 0 );
			$post['pubtime'] = mktime( $phour, $pmin, 0, $m[2], $m[1], $m[3] );
		}
		else
		{
			$post['pubtime'] = NV_CURRENTTIME;
		}
		$exp_date = $nv_Request->get_title( 'exptime', 'post', '' );
		if( ! empty( $exp_date ) and preg_match( "/^([0-9]{1,2})\\/([0-9]{1,2})\/([0-9]{4})$/", $exp_date, $m ) )
		{
			$ehour = $nv_Request->get_int( 'ehour', 'post', 0 );
			$emin = $nv_Request->get_int( 'emin', 'post', 0 );
			$post['exptime'] = mktime( $ehour, $emin, 0, $m[2], $m[1], $m[3] );
		}
		else
		{
			$post['exptime'] = 0;
		}
		if( empty( $post['title'] ) )
		{
			$info = $lang_module['errorTitleEmpty'];
			$is_error = true;
		}

		if( ! $is_error )
		{
			$test_content = strip_tags( $post['html'] );
			$test_content = trim( $test_content );
			$post['html'] = ! empty( $test_content ) ? nv_editor_nl2br( $post['html'] ) : "";

			if( isset( $post['id'] ) )
			{
				$query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_rows SET 
                catid=" . $post['catid'] . ",                
                title=" . $db->quote( $post['title'] ) . ", 
                link=" . $db->quote( $post['link'] ) . ", 
               
                html=" . $db->quote( $post['html'] ) . ", 
                exptime=" . $post['exptime'] . ", 
                pubtime=" . $post['pubtime'] . "                
                WHERE id=" . $post['id'];			
				$db->query( $query );
				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['editNews'], "Id: " . $post['id'], $admin_info['userid'] );
			}
			else
			{
				$sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows";
				$result = $db->query( $sql );
				$weight = $result->rowCount();
				$weight++;
				$query = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_rows VALUES 
                (NULL, " . $post['catid'] . ", " . $db->quote( $post['title'] ) . ", " . $db->quote( $post['html'] ) . ", " . $db->quote( $post['link'] ) . ", 
                 " . $post['pubtime'] . ", " . $post['exptime'] . ", " . $admin_info['userid'] . ", " . $weight . ", 1);";

				$_id = $db->insert_id( $query );
				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['addNews'], "Id: " . $_id, $admin_info['userid'] );
			}
            $nv_Cache->delMod($module_name);
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
			die();
		}

	}
	elseif( isset( $post['id'] ) )
	{
		$post = $row;
		$post['html'] = nv_editor_br2nl( $post['html'] );

	}
	else
	{
		$post['title'] = $post['html'] = $post['link'] = "";
		$post['catid'] = $post['exptime'] = $post['pubtime'] = 0;

	}

	if( ! empty( $post['html'] ) )
		$post['html'] = nv_htmlspecialchars( $post['html'] );

	$xtpl->assign( 'ERROR_INFO', $info );

	if( isset( $post['id'] ) )
	{
		$post['action'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&edit&id=" . $post['id'];
		$informationtitle = $lang_module['editNews'];
	}
	else
	{
		$post['action'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&add";
		$informationtitle = $lang_module['addNews'];
	}
	$xtpl->assign( 'INFO_TITLE', $informationtitle );
	$xtpl->assign( 'POST', $post );

	if( $post['pubtime'] == 0 )
	{
		$post['pubtime'] = NV_CURRENTTIME;
	}

	$publ_date = nv_date( "d/m/Y", $post['pubtime'] );
	$xtpl->assign( 'pubtime', $publ_date );
	$tdate = date( "H|i", $post['pubtime'] );
	list( $phour, $pmin ) = explode( "|", $tdate );
	$select = '';
	for( $i = 0; $i <= 23; ++$i )
	{
		$select .= "<option value=\"" . $i . "\"" . (($i == $phour) ? " selected=\"selected\"" : "") . ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
	}$xtpl->assign( 'phour', $select );
	$select = "";
	for( $i = 0; $i < 60; ++$i )
	{
		$select .= "<option value=\"" . $i . "\"" . (($i == $pmin) ? " selected=\"selected\"" : "") . ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
	}
	$xtpl->assign( 'pmin', $select );

	if( $post['exptime'] != 0 )
	{
		$exp_date = nv_date( "d/m/Y", $post['exptime'] );
		$xtpl->assign( 'exp_date', $exp_date );
		$tdates = nv_date( "H|i", $post['exptime'] );

		list( $ehour, $emin ) = explode( "|", $tdate );
	}
	else
	{
		$ehour = $emin = 0;
	}

	$select = "";
	for( $i = 0; $i <= 23; ++$i )
	{
		$select .= "<option value=\"" . $i . "\"" . (($i == $ehour) ? " selected=\"selected\"" : "") . ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
	}$xtpl->assign( 'ehour', $select );
	$select = "";
	for( $i = 0; $i < 60; ++$i )
	{
		$select .= "<option value=\"" . $i . "\"" . (($i == $emin) ? " selected=\"selected\"" : "") . ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
	}$xtpl->assign( 'emin', $select );

	foreach( $catList as $_catid => $_value )
	{
		$option = array(
			'value' => $_catid,
			'name' => $_value['title'],
			'selected' => $_catid == $post['catid'] ? " selected=\"selected\"" : ""
		);
		$xtpl->assign( 'OPTION', $option );
		$xtpl->parse( 'add.option' );
	}

	if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
	{
		$_cont = nv_aleditor( 'html', '100%', '300px', $post['html'] );
	}
	else
	{
		$_cont = "<textarea style=\"width:100%;height:300px\" name=\"html\" id=\"html\">" . $post['html'] . "</textarea>";
	}
	$xtpl->assign( 'CONTENT', $_cont );

	$xtpl->parse( 'add' );
	$contents = $xtpl->text( 'add' );

	$my_head = "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.css\" />\n";
	$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.js\"></script>\n";
	$my_head .= "<script type=\"text/javascript\">\n";
	$my_head .= "Shadowbox.init();\n";
	$my_head .= "</script>\n";

	include (NV_ROOTDIR . "/includes/header.php");
	echo nv_admin_theme( $contents );
	include (NV_ROOTDIR . "/includes/footer.php");
}

if( $nv_Request->isset_request( 'changeStatus', 'post' ) )
{
	$id = $nv_Request->get_int( 'changeStatus', 'post', 0 );
	$sql = "SELECT status FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE id=" . $id;
	$result = $db->query( $sql );
	$status = $result->fetchColumn();

	$newStatus = $status ? 0 : 1;
	$query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_rows SET status=" . $newStatus . " WHERE id=" . $id;
	$db->query( $query );

	nv_del_moduleCache( $module_name );
	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['cstatus'], "Id: " . $id, $admin_info['userid'] );

	$alt = $newStatus ? $lang_module['status1'] : $lang_module['status0'];
	$icon = $newStatus ? "enabled" : "disabled";

	die( "<img style=\"vertical-align:middle;margin-right:10px\" alt=\"" . $alt . "\" title=\"" . $alt . "\" src=\"" . NV_BASE_SITEURL . "themes/" . $global_config['module_theme'] . "/images/" . $module_data . "/" . $icon . ".png\" width=\"12\" height=\"12\" />" );
}
if( $nv_Request->isset_request( 'del', 'post' ) )
{
	$id = $nv_Request->get_int( 'del', 'post', 0 );
	$query = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE id = " . $id;
	$db->query( $query );
	fix_rowWeight();
    $nv_Cache->delMod($module_name);
	die( 'OK' );
}
if ( $nv_Request->isset_request( 'cWeight, id', 'post' ) )
{
    $id = $nv_Request->get_int( 'id', 'post' );
    $cWeight = $nv_Request->get_int( 'cWeight', 'post' );
    if ( ! isset( $nv_row[$id] ) ) die( "ERROR" );

    if ( $cWeight > ( $count = count( $nv_row ) ) ) $cWeight = $count;

    $sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE id!=" . $id . " ORDER BY weight ASC";
    $result = $db->query( $sql );
    $weight = 0;
    while ( $row = $result->fetch() )
    {
        $weight++;
        if ( $weight == $cWeight ) $weight++;
        $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_rows SET weight=" . $weight . " WHERE id=" . $row['id'];
        $db->query( $query );
    }
    $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_rows SET weight=" . $cWeight . " WHERE id=" . $id;
    $db->query( $query );
    $nv_Cache->delMod($module_name);
    nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['logChangeWeight'], "Id: " . $id, $admin_info['userid'] );
    die( 'OK' );
}
if( $nv_Request->isset_request( 'list', 'get' ) )
{
	$where = "";
	$base_url = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&list";
	if( $nv_Request->isset_request( 'cat', 'get' ) )
	{
		$cat = $nv_Request->get_int( 'cat', 'get', 0 );
		if( isset( $catList[$cat] ) )
		{
			$where .= " WHERE catid=" . $cat;
			$base_url .= "&cat=" . $cat;
		}
	}
	
	$sql = "SELECT COUNT(*) as ccount FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows" . $where;
	$result = $db->query( $sql );
	$all_page = $result->fetch();
	$all_page = $all_page['ccount'];

	$page = $nv_Request->get_int( 'page', 'get', 0 );
	$per_page = 50;

	$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows" . $where . " ORDER BY weight ASC LIMIT " . $page . "," . $per_page;
	$result = $db->query( $sql );

	$a = 0;
	while( $row = $result->fetch() )
	{
		$xtpl->assign( 'CLASS', $a % 2 ? " class=\"second\"" : "" );
		
		$count = count( $nv_row );
		for ( $i = 1; $i <= $count; $i++ )
        {
            $opt = array( 'value' => $i, 'selected' => $i == $row['weight'] ? " selected=\"selected\"" : "" );
            $xtpl->assign( 'NEWWEIGHT', $opt );
            $xtpl->parse( 'list.loop.option' );
        }
		$row['pubtime'] = date( "d-m-Y H:i", $row['pubtime'] );
		$row['catname'] = $catList[$row['catid']]['title'];
		$row['icon'] = $row['status'] ? "enabled" : "disabled";
		$row['status'] = $row['status'] ? $lang_module['status1'] : $lang_module['status0'];
		$xtpl->assign( 'DATA', $row );
		$xtpl->parse( 'list.loop' );
		$a++;
	}

	$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );

	$xtpl->assign( 'NV_GENERATE_PAGE', $generate_page );
	$xtpl->parse( 'list' );
	$xtpl->out( 'list' );
	exit();
}

foreach( $catList as $id => $name )
{
	$option = array(
		'id' => $id . "|" . $name['title'],
		'name' => $name['title']
	);
	$xtpl->assign( 'OPTION2', $option );
	$xtpl->parse( 'main.psopt2' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include (NV_ROOTDIR . "/includes/header.php");
echo nv_admin_theme( $contents );
include (NV_ROOTDIR . "/includes/footer.php");