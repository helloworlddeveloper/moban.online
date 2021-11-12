<?php

/**
 * @Project NUKEVIET 3.1
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES.,JSC. All rights reserved
 * @Createdate 24-06-2011 10:35
 */

if( ! defined( 'NV_IS_MOD_RM' ) ) die( 'Stop!!!' );

if( $nv_Request->isset_request( 'search', 'post,get' ) )
{
	$inputmobile = $nv_Request->get_title( 'inputmobile', 'post,get', 0 );
    $eventid = $nv_Request->get_int( 'eventid', 'post,get', 0 );
    $id = $nv_Request->get_int( 'id', 'post,get', 0 );

    if( $inputmobile == '' )
	{
		exit( $lang_module['no_data'] );
	}
	if( $id > 0 && $flag_allow == 1 ){
        $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id= " . $id;
    }
    elseif( $flag_allow == 0 ){
        $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE adminid= " . $user_info['userid'] . " AND mobile =" . $db->quote( $inputmobile );
    }
    else{
        $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE mobile =" . $db->quote( $inputmobile );
    }

    $result = $db->query( $sql );
    $data_info = $result->fetch();
    if( empty( $data_info ) )
    {
        exit( $lang_module['no_data'] );
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

    $data_events = array();
    $content_note = '';
    if( $eventid > 0 ){
        $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_listevents WHERE id =" .  $eventid;
       $data_events = $db->query( $sql )->fetch();
       $content_note = sprintf( $lang_module['content_note_event'], $data_events['title'], date('d/m/Y', $data_events['timeevent']), $data_events['addressevent'] );
    }
    $list_from = nv_From();
    $data_info['from_by'] = $list_from[$data_info['from_by']]['title'];
    $data_info['status_text'] = $array_student_status[$data_info['status']];
    $sql = "FROM " . NV_PREFIXLANG . '_' . $module_data . "_events WHERE customerid=" . $data_info['id'];


	$xtpl = new XTemplate( $op . ".tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
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
    $xtpl->assign( 'USER_EVENT', $data_events );
    $xtpl->assign( 'CONTENT_NOTE', $content_note );

	$link_edit = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=student&amp;studentid=' . $studentid;

    $xtpl->assign( 'LINK_EDIT', $link_edit );
	$xtpl->assign( 'parentid', $parentid );
	$xtpl->assign( 'studentid', $studentid );

    $data_refer = array();
	if( $data_info['adminid'] > 0 ){
        $sql_i = "SELECT t1.code, t1.mobile, t2.first_name, t2.last_name FROM " . $db_config['prefix'] . "_affiliate_users t1, " . NV_USERS_GLOBALTABLE . " t2 WHERE t1.userid=t2.userid AND t1.userid =" .  $data_info['adminid'];

        $data_refer = $db->query( $sql_i )->fetch();
        $data_refer['fullname'] = nv_show_name_user( $data_refer['first_name'], $data_refer['last_name'] );
    }
    $xtpl->assign( 'DATA_REFER', $data_refer );
	$xtpl->assign( 'ROW', $data_info );

    $xtpl->parse( 'data_show.student_info' );

	$list_event = nv_Eventtype();
	$list_measure = nv_measure();

	foreach( $list_event as $event )
	{
		$xtpl->assign( 'EVENT', $event );
		$xtpl->parse( 'data_show.eventtype' );
	}
	foreach( $list_measure as $measure )
	{
		$xtpl->assign( 'MEASURE', $measure );
		$xtpl->parse( 'data_show.measure' );
	}
    if( !empty( $data_events )){
        $array_student_status = array(1 => $lang_module['customer_event_1'], 2=> $lang_module['customer_event_2'], 3 => $lang_module['customer_event_3']);
    }
    if( $eventid > 0 ){
        foreach( $array_student_status as $key => $status )
        {
            $sl = ( $data_info['status'] == $key ) ? 'selected=selected' : '';
            $xtpl->assign( 'STATUS_ACTION', array(
                'key' => $key,
                'title' => $status,
                'sl' => $sl ) );
            $xtpl->parse( 'data_show.showstatus.status_action' );
        }
        $xtpl->parse( 'data_show.showstatus' );
    }

    $sql_admin = "SELECT t1.userid, t1.username, t1.email, t1.first_name, t1.last_name FROM " . NV_USERS_GLOBALTABLE . " AS t1 INNER JOIN " . NV_AUTHORS_GLOBALTABLE . " AS t2 ON t1.userid=t2.admin_id WHERE t1.active=1";
	$result = $db->query( $sql_admin );
	while( $users = $result->fetch() )
	{
		$users['full_name'] = nv_show_name_user( $users['first_name'], $users['last_name'] );
		$array_mods[$users['userid']] = $users;
	}
    
	$sql = "SELECT * " . $sql . " ORDER BY addtime DESC";
	$result = $db->query( $sql );
	while( $row = $result->fetch() )
	{
		$row['addtime'] = date( 'd.m.Y H:i', $row['addtime'] );
        $row['adminid'] = isset( $array_mods[$row['adminid']] ) ? $array_mods[$row['adminid']]['full_name'] : 'N/A';
		$row['eventtype'] = isset( $list_event[$row['eventtype']] ) ? $list_event[$row['eventtype']]['title'] : 'N/A';
		$row['measureid'] = isset( $list_measure[$row['measureid']] ) ? $list_measure[$row['measureid']]['title'] : 'N/A';
		$xtpl->assign( 'VIEW', $row );
		$xtpl->parse( 'data_show.loop' );
	}

	$xtpl->parse( 'data_show' );
	$contents = $xtpl->text( 'data_show' );
	exit( $contents );
}

if( $nv_Request->isset_request( 'save', 'post' ) )
{
	$note = $nv_Request->get_string( 'note', 'post', '' );
	$customerid = $nv_Request->get_int( 'customerid', 'post', '' );
    $eventid = $nv_Request->get_int( 'eventid', 'post', '' );
	$measureid = $nv_Request->get_int( 'measureid', 'post', '' );
	$eventtype = $nv_Request->get_int( 'eventtype', 'post', '' );
	$status_action = $nv_Request->get_int( 'status_action', 'post', '' );
	$remkt_time = $nv_Request->get_title( 'remkt_time', 'post', '' );
	if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $remkt_time, $m ) )
	{
		$remkt_time = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$remkt_time = 0;
	}
	$res = 'NO';
	try
	{
		$stmt = $db->prepare( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_events (customerid, measureid, adminid, addtime, eventtype, content) VALUES ( :customerid, :measureid, :adminid, :addtime, :eventtype, :content)' );

		$addtime = NV_CURRENTTIME;
		$stmt->bindParam( ':addtime', $addtime, PDO::PARAM_INT );
		$stmt->bindParam( ':customerid', $customerid, PDO::PARAM_STR );
		$stmt->bindParam( ':measureid', $measureid, PDO::PARAM_STR );
		$stmt->bindParam( ':adminid', $user_info['userid'], PDO::PARAM_INT );
		$stmt->bindParam( ':eventtype', $eventtype, PDO::PARAM_INT );
		$stmt->bindParam( ':content', $note, PDO::PARAM_STR, strlen( $note ) );

		$exc = $stmt->execute();
		if( $exc )
		{
			$sql_remkt_time = '';
			if( $remkt_time > 0 )
			{
				$sql_remkt_time = ', remkt_time=' . $remkt_time;
			}
			$db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET mkt_time=' . NV_CURRENTTIME . $sql_remkt_time . ' WHERE id=' . $customerid );

			//sua  trang thai
            if( $eventid > 0 )
            {
                $db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_usersevents SET status=' . $status_action . ' WHERE customerid=' . $customerid . ' AND eventid=' . $eventid);
            }
            /*
            else
            {
                $db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET status=' . $status_action . ' WHERE id=' . $customerid );
            }
            */
			$res = 'OK';
		}
	}
	catch ( PDOException $e )
	{
		$res = $e->getMessage();
	}
	exit( $res );
}

$id = $nv_Request->get_title( 'id', 'get', '' );
$eventid = $nv_Request->get_int( 'eventid', 'get', 0 );
$contents = nv_theme_mkt_eventcontent( $id, $eventid );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>