<?php

/**
 * @Project NUKEVIET 3.1
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES.,JSC. All rights reserved
 * @Createdate 24-06-2011 10:35
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if( $nv_Request->isset_request( 'save', 'post' ) )
{
	$note = $nv_Request->get_string( 'note', 'post', '' );
    $customerid = $nv_Request->get_int( 'customerid', 'post', '' );
	$measureid = $nv_Request->get_int( 'measureid', 'post', '' );
	$eventtype = $nv_Request->get_int( 'eventtype', 'post', '' );
    $status_accept = $nv_Request->get_int( 'status_accept', 'post', 0 );
	$res = 'NO';
	try
	{

		$stmt = $db->prepare( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_events (customerid, measureid, adminid, addtime, eventtype, content) VALUES ( :customerid, :measureid, :adminid, :addtime, :eventtype, :content)' );

		$addtime = NV_CURRENTTIME;
		$stmt->bindParam( ':addtime', $addtime, PDO::PARAM_INT );
		$stmt->bindParam( ':customerid', $customerid, PDO::PARAM_STR );
		$stmt->bindParam( ':measureid', $measureid, PDO::PARAM_STR );
		$stmt->bindParam( ':adminid', $admin_info['userid'], PDO::PARAM_INT );
		$stmt->bindParam( ':eventtype', $eventtype, PDO::PARAM_INT );
		$stmt->bindParam( ':content', $note, PDO::PARAM_STR, strlen( $note ) );

		$exc = $stmt->execute();
		if( $exc )
		{
		    if( $status_accept == 1 ){

                $data_customer = $db->query( 'SELECT * FROM  ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $customerid )->fetch();

		        //khach hang dong y thi chuyen contact cho cac dau nhanh
                //cap nhat data ve 0 neu co ban ghi chuyen contact nay cho ai do roi
                $db->query( "UPDATE " . NV_PREFIXLANG . '_' . $module_data . "_usersend SET status=0 WHERE mktid=	" . $customerid );
                //lay ra user co sl giao nho nhat de chueyn contact

                $sql = 'SELECT t1.userid, t1.code, t1.mobile FROM ' . NV_TABLE_AFFILIATE . '_users t1 LEFT JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_statisticuser t2 ON t1.userid=t2.adminid WHERE t1.lev=1 AND t1.status=1 ORDER BY total ASC LIMIT 1';
                list( $userid, $code, $mobile ) = $db->query( $sql )->fetch(3);
                //luu vao bang giao users
                $sql = "INSERT INTO " . NV_PREFIXLANG . '_' . $module_data . "_usersend (mktid, adminid, addtime, status) 
                    VALUES (" . intval( $customerid ) . ", " . intval( $userid ) . ", " . NV_CURRENTTIME . ", 1)";
                if ($db->query($sql)) {
                    update_statisticuser($userid);//cap nhat so luong chuyen
                    $stmt = $db->prepare( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_events (customerid, measureid, adminid, addtime, eventtype, content) VALUES ( :customerid, 0, 0, :addtime, 0, :content)' );
                    $note = 'Đã chuyển contact xuống cho NPP mã: ' . $code;
                    $stmt->bindParam( ':addtime', $addtime, PDO::PARAM_INT );
                    $stmt->bindParam( ':customerid', $customerid, PDO::PARAM_STR );
                    $stmt->bindParam( ':content', $note, PDO::PARAM_STR, strlen( $note ) );

                    $exc = $stmt->execute();
                    //gui sms
                    $apikey = $module_config[$module_name]['apikey'];
                    $secretkey = $module_config[$module_name]['secretkey'];
                    $sms_type = $module_config[$module_name]['sms_type'];
                    $url = '';
                    if( $sms_type == 2 ){
                        $url = '&Brandname=' . $module_config[$module_name]['brandname'];
                    }
                    $list_from = nv_From();
                    $from_by = $list_from[$data_customer['from_by']]['title'];
                    $content = 'Ban duoc cty giao khach hang ' . $from_by . ':' . $data_customer['full_name'] . ' ' . $data_customer['mobile'] . ' ' . $data_customer['address'];

                    $content = urlencode($content);
                    $data = 'http://rest.esms.vn/MainService.svc/json/SendMultipleMessage_V4_get?Phone=' . $mobile . '&ApiKey=' . $apikey . '&SecretKey=' . $secretkey . '&Content=' . $content . '&SmsType=' . $sms_type . $url;

                    $curl = curl_init($data);
                    curl_setopt($curl, CURLOPT_FAILONERROR, true);
                    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_exec($curl);

                }
            }
			$res = 'OK';
		}

	}
	catch ( PDOException $e )
	{
		$res = $e->getMessage();
	}
	exit( $res );
}

// Page title collum
$page = $nv_Request->get_int( 'page', 'get', 1 );
$per_page = 30;
$array = array();

$id = $nv_Request->get_int( 'id', 'get', 0 );
if( $id > 0 )
{
	$sql = "SELECT * FROM " . NV_PREFIXLANG . '_' . $module_data . " WHERE id=" . $id;
	$result = $db->query( $sql );
	$data_info = $result->fetch();
	if( empty( $data_info ) )
	{
		Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=student" );
		exit();
	}
	if( $data_info['birthday'] > 0 )
	{
		$data_info['birthday'] = date( 'd/m/Y', $data_info['birthday'] );
	}
	else
	{
		$data_info['birthday'] = '';
	}
	$data_info['sex'] = $lang_module['sex_' . $data_info['sex']];
	$page_title = $lang_module['history_student_pagetitle'] . ' ' . $data_info['full_name'];

	$list_from = nv_From();
	$data_info['from_by'] = $list_from[$data_info['from_by']]['title'];
	$data_info['status_text'] = $array_student_status[$data_info['status']];
	$sql = NV_PREFIXLANG . '_' . $module_data . "_events WHERE customerid=" . $id;
}
else
{
	$page_title = $lang_module['event_content'];
	$data_info = array();
	$sql = NV_PREFIXLANG . '_' . $module_data . "_events";
}

$sql_mod = "SELECT userid, username, email, first_name, last_name FROM " . NV_USERS_GLOBALTABLE . " WHERE in_groups	LIKE('%" . $group_allow_eventcontent . "%')";

$result = $db->query( $sql_mod );
while( $row = $result->fetch() )
{
	$row['full_name'] = nv_show_name_user( $row['first_name'], $row['last_name']);
	$array_mods[$row['userid']] = $row;
}

$list_event = nv_Eventtype();
$list_measure = nv_measure();

// Base data
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;

// Get num row
$db->sqlreset()->select( 'COUNT(*)' )->from( $sql );

$sth = $db->prepare( $db->sql() );
$sth->execute();
$num_items = $sth->fetchColumn();

// Build data
$db->select( '*' )->order( 'addtime DESC' )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );
$sth = $db->prepare( $db->sql() );

$sth->execute();
$array_studentid = array();
while( $row = $sth->fetch() )
{
	$row['adminid'] = isset( $array_mods[$row['adminid']] ) ? $array_mods[$row['adminid']]['full_name'] : 'System';
	$row['addtime'] = date( 'd.m.Y H:i', $row['addtime'] );
    $row['color'] = isset( $list_event[$row['eventtype']] ) ? $list_event[$row['eventtype']]['color'] : '';
	$row['eventtype'] = isset( $list_event[$row['eventtype']] ) ? $list_event[$row['eventtype']]['title'] : 'N/A';
	$row['measureid'] = isset( $list_measure[$row['measureid']] ) ? $list_measure[$row['measureid']]['title'] : 'N/A';
	if( $row['customerid'] > 0 )
	{
		$array_studentid[] = $row['customerid'];
		$row['link_users'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=eventcontent&id=" . $row['customerid'];
	}
	$array[] = $row;
}

$array_studentid = array_unique( $array_studentid );
if( !empty( $array_studentid )){
    $sql_mod = "SELECT id, full_name, mkt_time, remkt_time FROM " . NV_PREFIXLANG . '_' . $module_data . " WHERE id IN (" . implode(',', $array_studentid) . ")";

    $result = $db->query( $sql_mod );
    while( $row = $result->fetch() )
    {
        $array_studentid[$row['id']] = $row;
    }
}

$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page );

$xtpl = new XTemplate( $op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'module_file', $module_file );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'id', $id );
$xtpl->assign( 'CHECKSESS', md5( "course_" . $admin_info['userid'] . "_" . session_id() ) );
$xtpl->assign( 'ROW', $data_info );

$array_status_accept = array(
    $lang_module['status_accept_0'],
    $lang_module['status_accept_1']
);
foreach( $array_status_accept as $key => $status_accept )
{
	$xtpl->assign( 'STATUS', array('key' => $key, 'value' => $status_accept ) );
	$xtpl->parse( 'main.allow_add.status_accept' );
}
foreach( $list_event as $event )
{
    $xtpl->assign( 'EVENT', $event );
    $xtpl->parse( 'main.allow_add.eventtype' );
}
foreach( $list_measure as $measure )
{
	$xtpl->assign( 'MEASURE', $measure );
	$xtpl->parse( 'main.allow_add.measure' );
}
foreach( $array as $data )
{
    $data['full_name'] = $array_studentid[$data['customerid']]['full_name'];
    $data['mkt_time'] = $array_studentid[$data['customerid']]['mkt_time'];
    $data['remkt_time'] = $array_studentid[$data['customerid']]['remkt_time'];

    $data['mkt_time'] = date('d/m/Y', $data['mkt_time'] );
    $data['remkt_time'] = ( $data['remkt_time'] > 0)? date('d/m/Y', $data['remkt_time'] ) : '';
	$xtpl->assign( 'VIEW', $data );
	$xtpl->parse( 'main.loop' );
}
if( ! empty( $generate_page ) )
{
	$xtpl->assign( 'GENERATE_PAGE', $generate_page );
	$xtpl->parse( 'main.generate_page' );
}
if( $permissions_users[$admin_info['admin_id']][$op]['add'] == 1 && ( $id > 0 ) )
{
	$xtpl->parse( 'main.allow_add' );
}
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
