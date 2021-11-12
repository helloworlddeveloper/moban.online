<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 0:51
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

define('NV_TABLE_AFFILIATE', $db_config['prefix'] . '_affiliate');
define('NV_TABLE_AFFILIATE_LANG', NV_PREFIXLANG . '_affiliate');
define('NV_TABLE_NOTIFICATION', NV_PREFIXLANG . '_notification');
define('NV_EVENT_ID_REGISTER', 1);
define('NV_MEASURE_ID_ACCEPT', 1);


$array_select_status = array();
$array_select_status[0] = $lang_module['status_0'];
$array_select_status[1] = $lang_module['status_1'];

$array_student_status = array(
    '0' => $lang_module['customer_status_0'],
    '1' => $lang_module['customer_status_1'],
    '2' => $lang_module['customer_status_2']);

$array_personal_sms = array(
    '[EVENTNAME]' => $lang_module['content_note_eventname'],
    '[TIMEEVENT]' => $lang_module['content_note_timeevent'],
    '[FULLNAME]' => $lang_module['content_note_fullname'],
    '[MOBILE]' => $lang_module['content_note_phone'],
    '[FIRSTNAME]' => $lang_module['content_note_first_name'],
    '[EMAIL]' => $lang_module['content_note_email'],
    '[LASTNAME]' => $lang_module['content_note_last_name'],
    '[ADDRESS]' => $lang_module['content_note_address'],
    '[ALIAS]' => $lang_module['content_note_alias']
);

function update_statisticuser($adminid){

    global $module_data, $db;
    $sql = 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_statisticuser WHERE adminid=' . $adminid;

    $check_exits = $db->query( $sql )->fetchColumn();
    if( $check_exits == 0 ){
        $db->query('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_statisticuser( adminid, total ) VALUES ( ' . $adminid . ',1)');
    }
    else{
        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_statisticuser SET total = total+1 WHERE adminid =' . $adminid );
    }
}

function nv_build_content_customer( $content, $customer)
{
    global $global_config, $lang_module;

    $pos = strrpos( $customer['full_name'], ' ' );
    if( $pos === false )
    {
        $customer['first_name'] = '';
        $customer['last_name'] = $customer['full_name'];
    }
    else
    {
        $customer['first_name'] = trim( substr( $customer['full_name'], 0, $pos + 1 ) );
        $customer['last_name'] = trim( substr( $customer['full_name'], $pos ) );
    }
    $content = nv_unhtmlspecialchars($content);
    // Thay the bien noi dung
    $array_replace = array(
        '[FULLNAME]' => !empty($customer['full_name']) ? $customer['full_name'] : $lang_module['customers'],
        '[FIRSTNAME]' => !empty($customer['first_name']) ? $customer['first_name'] : $lang_module['customers'],
        '[LASTNAME]' => !empty($customer['last_name']) ? $customer['last_name'] : $lang_module['customers'],
        '[MOBILE]' => $customer['mobile'],
        '[EMAIL]' => $customer['email'],
        '[ADDRESS]' => $customer['addressevent'],
        '[ALIAS]' => $lang_module['alias_' . $customer['sex']],
        '[EVENTNAME]' => $customer['eventname'],
        '[TIMEEVENT]' => date('H:i - d/m/Y', $customer['timeevent'] )
    );
    $html = '';
    foreach ($array_replace as $index => $value) {
        $html = str_replace($index, $value, $html);
        $content = str_replace($index, $value, $content);
    }
    return $content;
}

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . "_" . $module_data . '_listevents WHERE status=1';
$array_listevents = $nv_Cache->db($sql, 'id', $module_name );

function nv_Province()
{
	global $db, $module_data;

	$sql = "SELECT * FROM " . NV_TABLE_AFFILIATE_LANG . "_province WHERE status=1 ORDER BY weight ASC";
	$result = $db->query( $sql );
	$list = array();
	while( $row = $result->fetch() )
	{
		$list[$row['id']] = array( //
				'id' => $row['id'], //
                'title' => $row['title'], //
				'weight' => ( int )$row['weight'] //
				);
	}

	return $list;
}


function nv_District()
{
	global $db, $module_data;

	$sql = "SELECT * FROM " . NV_TABLE_AFFILIATE_LANG . "_district WHERE status=1 ORDER BY weight ASC";
	$result = $db->query( $sql );
	$list = array();
	while( $row = $result->fetch() )
	{
		$list[$row['id']] = array( //
			'id' => $row['id'], //
            'idprovince' => $row['idprovince'], //
			'title' => $row['title'], //
			'weight' => ( int )$row['weight'] //
				);
	}
	return $list;
}

function nv_From()
{
	global $db, $module_data;

	$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_from WHERE status=1 ORDER BY weight ASC";
	$result = $db->query( $sql );
	$list = array();
	while( $row = $result->fetch() )
	{
		$list[$row['from_id']] = array( //
				'id' => $row['from_id'], //
                'title' => $row['from_name'], //
				'weight' => ( int )$row['weight'] //
				);
	}
	return $list;
}

function nv_Eventtype()
{
	global $db, $module_data;

	$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_eventtype WHERE status=1 ORDER BY weight ASC";
	$result = $db->query( $sql );
	$list = array();
	while( $row = $result->fetch() )
	{
		$list[$row['eventtype_id']] = array( //
				'id' => $row['eventtype_id'], //
                'title' => $row['eventtype_name'], //
                'color' => $row['color'], //
				'weight' => ( int )$row['weight'] //
				);
	}
	return $list;
}
function nv_measure()
{
	global $db, $module_data;

	$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_measure WHERE status=1 ORDER BY weight ASC";
	$result = $db->query( $sql );
	$list = array();
	while( $row = $result->fetch() )
	{
		$list[$row['measure_id']] = array( //
				'id' => $row['measure_id'], //
                'title' => $row['measure_name'], //
				'weight' => ( int )$row['weight'] //
				);
	}
	return $list;
}

function save_eventcontent( $customerid, $measureid, $eventtype, $note )
{
    global $db, $user_info, $module_data;
    if( $customerid > 0 && ! empty( $note ) )
    {
        try
        {
            $stmt = $db->prepare( 'INSERT INTO ' . NV_PREFIXLANG . "_" . $module_data . '_events (customerid, measureid, adminid, addtime, eventtype, content) VALUES ( :customerid, :measureid, :adminid, :addtime, :eventtype, :content)' );

            $addtime = NV_CURRENTTIME;
            $stmt->bindParam( ':addtime', $addtime, PDO::PARAM_INT );
            $stmt->bindParam( ':customerid', $customerid, PDO::PARAM_STR );
            $stmt->bindParam( ':measureid', $measureid, PDO::PARAM_STR );
            $stmt->bindParam( ':adminid', intval( $user_info['userid'] ), PDO::PARAM_INT );
            $stmt->bindParam( ':eventtype', $eventtype, PDO::PARAM_INT );
            $stmt->bindParam( ':content', $note, PDO::PARAM_STR, strlen( $note ) );

            $exc = $stmt->execute();
            if( $exc )
            {
                return 1;
            }

        }
        catch ( PDOException $e )
        {
            die($e->getMessage());
        }
        return 0;
    }
    return 0;
}