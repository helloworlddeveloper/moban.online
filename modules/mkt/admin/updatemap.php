<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 24-03-2011 20:08
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$file_name_save = NV_ROOTDIR . '/modules/' . $module_file . '/id_student.txt';
if( $nv_Request->isset_request( 'save_maps', 'post' ) )
{
	$studentid = $nv_Request->get_int( 'studentid', 'post', 0 );
	$gmap_lat = $nv_Request->get_float( 'gmap_lat', 'post', 0 );
	$gmap_lng = $nv_Request->get_float( 'gmap_lng', 'post', 0 );

	if( $studentid > 0 && $gmap_lat > 0 )
	{
		$db->query( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_student SET gmap_lat=" . $gmap_lat . ", gmap_lng=" . $gmap_lng . " WHERE studentid=" . $studentid );
	}

	$sql = "SELECT studentid, address FROM " . NV_PREFIXLANG . "_" . $module_data . "_student WHERE studentid>" . $studentid . " AND address!='' AND gmap_lat = 0 ORDER BY studentid ASC LIMIT 1";
	$result = $db->query( $sql );
	list( $studentid, $address ) = $result->fetch( 3 );

	file_put_contents( $file_name_save, $studentid . '[NV4]' . $address );

	exit( 'OK[NV4]' . $studentid . '[NV4]' . $address );
}

$content_save = '';
if( file_exists( $file_name_save ) )
{
	$content_save = file_get_contents( $file_name_save );
}

if( empty( $content_save ) )
{
	$sql = "SELECT studentid, address FROM " . NV_PREFIXLANG . "_" . $module_data . "_student WHERE address!='' ORDER BY studentid DESC LIMIT 1";
	$result = $db->query( $sql );
	while( $row = $result->fetch() )
	{
		$content_save = $row['studentid'] . '[NV4]' . $row['address'];
	}
}
$content_save = explode( '[NV4]', $content_save );


$xtpl = new XTemplate( $op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'studentid', $content_save[0] );
$xtpl->assign( 'address', $content_save[1] );


$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';

?>