<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2016 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Fri, 01 Feb 2013 04:11:16 GMT
 */

if( ! defined( 'NV_IS_MOD_NOTICE' ) )
	die( 'Stop!!!' );

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];
$cat_list = nv_catList();
$array_data = array();
$page = 0;
$per_page = 10;

if( ! empty( $array_op[1] ) )
{
	if( substr( $array_op[1], 0, 5 ) == "page-" )
	{
		$page = intval( substr( $array_op[1], 5 ) );
	}
}
$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=main";
$html_pages = "";
//SQL liet ke cac ban tin thong bao da dang len
//$sql = "SELECT SQL_CALC_FOUND_ROWS *  FROM  " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE status=1 AND pubtime < " . NV_CURRENTTIME . " AND (exptime=0 OR exptime>" . NV_CURRENTTIME . ") ORDER BY ID DESC LIMIT " . $page . "," . $per_page . "";
$sql = "SELECT SQL_CALC_FOUND_ROWS *  FROM  " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE status=1 AND pubtime < " . NV_CURRENTTIME . " ORDER BY ID DESC LIMIT " . $page . "," . $per_page . "";
$result = $db->query( $sql );

$result_page = $db->query( "SELECT FOUND_ROWS()" );

$numf = $result_page->fetchColumn();
$all_page = ($numf) ? $numf : 1;
$i = $page;
while( $row = $result->fetch() )
{
	$i++;
	$cls = ($row['catid'] != 0) ? "class='catnotic" . $cat_list[$row['catid']]['id'] . "'" : "";
	$array_data[$row['id']] = array(
		'STT' => $i,
		'id' => $row['id'],
		'title' => $row['title'],
		'html' => $row['html'],
		'link' => $row['link'],
		'pubtime' => $row['pubtime'],
		'exptime' => $row['exptime'],
		'weight' => $row['weight'],
		'userid' => $row['userid'],
		'class' => $cls
	);
}

$html_pages = nv_products_page( $base_url, $all_page, $per_page, $page );

$contents = nv_theme_notice_main( $array_data, $html_pages );

include (NV_ROOTDIR . "/includes/header.php");
echo nv_site_theme( $contents );
include (NV_ROOTDIR . "/includes/footer.php");