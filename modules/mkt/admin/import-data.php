<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 - 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 08 Apr 2012 00:00:00 GMT
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if( $nv_Request->isset_request( 'example', 'get' ) )
{
	//Download file
	require_once NV_ROOTDIR . '/includes/class/download.class.php';
	$download = new download( NV_ROOTDIR . '/' . SYSTEM_UPLOADS_DIR . '/' . $module_name . '/template/file-mau.xls', NV_ROOTDIR . '/' . SYSTEM_UPLOADS_DIR . '/' . $module_name, 'file-mau.xls' );
	$download->download_file();
	exit();
}
function nv_read_data_from_excel( $file_name, $subject_id, $time_id, $examp_id )
{
	global $global_config, $db, $client_info, $module_file, $module_data, $module_name, $lang_module, $array_subject, $array_examp, $array_time;

	require_once NV_ROOTDIR . '/includes/class/PHPExcel.php';

	$objPHPExcel = PHPExcel_IOFactory::load( NV_ROOTDIR . '/' . SYSTEM_UPLOADS_DIR . "/" . $module_name . "/" . $file_name );
	$objWorksheet = $objPHPExcel->getActiveSheet();

	$highestRow = $objWorksheet->getHighestRow();
	$highestColumn = $objWorksheet->getHighestColumn();
	$highestColumnIndex = PHPExcel_Cell::columnIndexFromString( $highestColumn );

	$user_field = array();
	$user_field['stt'] = array( 'col' => 0, 'title' => 'stt' );
	$user_field['school_id'] = array( 'col' => 1, 'title' => 'school_id' );
	$user_field['class_study'] = array( 'col' => 2, 'title' => 'class_study' );
	$user_field['student_name'] = array( 'col' => 3, 'title' => 'student_name' );
	$user_field['birthday'] = array( 'col' => 4, 'title' => 'birthday' );
	$user_field['sex'] = array( 'col' => 5, 'title' => 'sex' );
	$user_field['parent_name'] = array( 'col' => 6, 'title' => 'parent_name' );
	$user_field['parent_name2'] = array( 'col' => 7, 'title' => 'parent_name2' );
	$user_field['parent_name3'] = array( 'col' => 8, 'title' => 'parent_name3' );

	$user_field['mobile'] = array( 'col' => 9, 'title' => 'mobile' );
	$user_field['email'] = array( 'col' => 10, 'title' => 'email' );
	$user_field['address'] = array( 'col' => 11, 'title' => 'address' );

	$col = 13;

	$array_data_read = array();
	// read data
	for( $row = 3; $row <= $highestRow; ++$row )
	{
		foreach( $user_field as $field => $column )
		{
			$col = $column['col'];
			if( $field == 'birthday' )
			{
				$cellValue = $objWorksheet->getCellByColumnAndRow( $col, $row )->getCalculatedValue();
				$array_data_read[$row][$field] = PHPExcel_Shared_Date::ExcelToPHP( $cellValue );
			}
			else
			{
				$array_data_read[$row][$field] = $objWorksheet->getCellByColumnAndRow( $col, $row )->getCalculatedValue();
			}
		}

	}
	$data_no_insert = array();
	foreach( $array_data_read as $row )
	{
		$row['mobile'] = trim( $row['mobile'] );
		$_tmp_mobile = explode( '-', $row['mobile'] );

		foreach( $_tmp_mobile as $key => $mobile )
		{
			$mobile = trim( $mobile );
			$mobile = str_replace( ' ', '', $mobile );
			$mobile = str_replace( '.', '', $mobile );
			if( ! empty( $mobile ) && ( ! preg_match( '/^0/', $mobile ) ) )
			{
				$mobile = 0 . $mobile;
			}
			//kiem tra sdt co dung khong?
			if( ! empty( $mobile ) && ( strlen( $mobile ) > 11 || strlen( $mobile ) < 10 ) )
			{
				unset( $_tmp_mobile[$key] );
			}
			else
			{
				$_tmp_mobile[$key] = $mobile;
			}
		}
		$row['numberphone'] = '';
		$num_mobile = 1;
		foreach( $_tmp_mobile as $mobile )
		{
			if( $num_mobile == 1 )
			{
				$row['mobile'] = $mobile;
			}
			else
			{
				$row['numberphone'] = $mobile;
			}
			$num_mobile++;
		}
		if( ! empty( $row['sex'] ) )
		{
			$row['sex'] = ( $row['sex'] == 'Nam' ) ? 1 : 2;
		}
		else
		{
			$row['sex'] = 0;
		}
		$row['student_name'] = trim( $row['student_name'] );
		$row['parent_name'] = trim( $row['parent_name'] );

		if( ! empty( $row['mobile'] ) && ! empty( $row['student_name'] ) )
		{
			$data_student = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data WHERE student_name LIKE "%' . $row['student_name'] . '%" AND mobile LIKE "%' . $row['mobile'] . '%"' )->fetch();
			if( empty( $data_student ) )
			{
				$row['from_by'] = 10;
				$row['jobs'] = $row['birthday_parent'] = $row['provinceid'] = $row['districtid'] = $row['gmap_lat'] = $row['gmap_lng'] = 0;
				$row['income'] = 5;
				$row['status_parent'] = 1;
				$row['relationship'] = $row['status'] = 0;
				if( $row['school_id'] != '' )
				{
					$school_data = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_school WHERE school_title LIKE "%' . $row['school_id'] . '%"' )->fetch();
					if( ! empty( $school_data ) )
					{
						$row['school_id'] = $school_data['schoolid'];
						$row['provinceid'] = $school_data['provinceid'];
						$row['districtid'] = $school_data['districtid'];
						$row['gmap_lng'] = $school_data['gmap_lng'];
						$row['gmap_lat'] = $school_data['gmap_lat'];
					}
					else
					{
						$row['school_id'] = 0;
					}
				}
				else
				{
					$row['school_id'] = 0;
				}

				if( empty( $row['parent_name'] ) )
				{
					if( ! empty( $row['parent_name2'] ) )
					{
						$row['parent_name'] = trim( $row['parent_name2'] );
					}
					elseif( ! empty( $row['parent_name3'] ) )
					{
						$row['parent_name'] = trim( $row['parent_name3'] );
					}
					else
					{
						$row['parent_name'] = 'PH em: ' . $row['student_name'];
					}
				}

				$row['phone_student'] = $row['mobile'];

				if( ! empty( $row['birthday'] ) && intval( $row['birthday'] ) > 0 )
				{
					$row['shool_year'] = date( 'Y', NV_CURRENTTIME ) - 5 - $row['class_study'] - date( 'Y', $row['birthday'] );
				}
				else
				{
					$row['birthday'] = date( 'Y', NV_CURRENTTIME ) - $row['class_study'] - 5;
					$row['birthday'] = mktime( 0, 0, 0, 1, 1, $row['birthday'] );
                //   print_r($row); die( date('d/m/Y', $row['birthday'] ));
					$row['shool_year'] = 0;
				}
				$row['note'] = 'Du lieu nay khong co dia chi. Mac dinh lay kinh vi do la 51 Le Dai Hanh';
				$row['gmap_lat'] = 21.01177;
				$row['gmap_lng'] = 105.84780;
				$row['edit_time'] = $row['add_time'] = NV_CURRENTTIME;
				try
				{
					$data_parent = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_parent WHERE parent_name LIKE "%' . $row['parent_name'] . '%" AND mobile LIKE "%' . $row['mobile'] . '%"' )->fetch();
					if( empty( $data_parent ) )
					{
						$stmt = $db->prepare( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_parent (provinceid, districtid, parent_name, birthday, address, email, mobile, numberphone, jobs, gmap_lat, gmap_lng, from_by, income, add_time, edit_time, status) VALUES (:provinceid, :districtid, :parent_name, :birthday, :address, :email, :mobile, :numberphone, :jobs, :gmap_lat, :gmap_lng, :from_by, :income, :add_time, :edit_time, :status)' );
						$stmt->bindParam( ':add_time', $row['add_time'], PDO::PARAM_INT );
						$stmt->bindParam( ':provinceid', $row['provinceid'], PDO::PARAM_INT );
						$stmt->bindParam( ':districtid', $row['districtid'], PDO::PARAM_INT );
						$stmt->bindParam( ':parent_name', $row['parent_name'], PDO::PARAM_LOB );
						$stmt->bindParam( ':birthday', $row['birthday_parent'], PDO::PARAM_INT );
						$stmt->bindParam( ':address', $row['address'], PDO::PARAM_STR );
						$stmt->bindParam( ':mobile', $row['mobile'], PDO::PARAM_STR );
						$stmt->bindParam( ':email', $row['email'], PDO::PARAM_STR );
						$stmt->bindParam( ':numberphone', $row['numberphone'], PDO::PARAM_STR );
						$stmt->bindParam( ':jobs', $row['jobs'], PDO::PARAM_INT );
						$stmt->bindParam( ':from_by', $row['from_by'], PDO::PARAM_INT );
						$stmt->bindParam( ':gmap_lat', $row['gmap_lat'], PDO::PARAM_INT );
						$stmt->bindParam( ':gmap_lng', $row['gmap_lng'], PDO::PARAM_INT );
						$stmt->bindParam( ':income', $row['income'], PDO::PARAM_INT );
						$stmt->bindParam( ':status', $row['status_parent'], PDO::PARAM_INT );
						$stmt->bindParam( ':edit_time', $row['edit_time'], PDO::PARAM_INT );
						$exc = $stmt->execute();
						if( $exc )
						{
							$stmt = $db->prepare( 'SELECT MAX(parentid) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_parent' );
							$stmt->execute();
							$parentid = $stmt->fetchColumn();
						}
					}
					else
					{
						$parentid = $data_parent['parentid'];
					}
					if( $parentid > 0 )
					{
						$stmt = $db->prepare( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data (provinceid, districtid, parentid, student_name, birthday, shool_year, sex, school_id, address, email, mobile, relationship, gmap_lat, gmap_lng, from_by, add_time, edit_time, status, note) VALUES (:provinceid, :districtid, :parentid, :student_name, :birthday, :shool_year, :sex, :school_id, :address, :email, :mobile, :relationship, :gmap_lat, :gmap_lng, :from_by, :add_time, :edit_time, :status, :note)' );
						$stmt->bindParam( ':add_time', $row['add_time'], PDO::PARAM_INT );
						$stmt->bindParam( ':provinceid', $row['provinceid'], PDO::PARAM_INT );
						$stmt->bindParam( ':districtid', $row['districtid'], PDO::PARAM_INT );
						$stmt->bindParam( ':parentid', $parentid, PDO::PARAM_INT );
						$stmt->bindParam( ':student_name', $row['student_name'], PDO::PARAM_LOB );
						$stmt->bindParam( ':birthday', $row['birthday'], PDO::PARAM_INT );
						$stmt->bindParam( ':shool_year', $row['shool_year'], PDO::PARAM_INT );
						$stmt->bindParam( ':sex', $row['sex'], PDO::PARAM_INT );
						$stmt->bindParam( ':school_id', $row['school_id'], PDO::PARAM_INT );
						$stmt->bindParam( ':address', $row['address'], PDO::PARAM_STR );
						$stmt->bindParam( ':mobile', $row['phone_student'], PDO::PARAM_STR );
						$stmt->bindParam( ':email', $row['email'], PDO::PARAM_STR );
						$stmt->bindParam( ':relationship', $row['relationship'], PDO::PARAM_INT );
						$stmt->bindParam( ':gmap_lat', $row['gmap_lat'], PDO::PARAM_INT );
						$stmt->bindParam( ':gmap_lng', $row['gmap_lng'], PDO::PARAM_INT );
						$stmt->bindParam( ':from_by', $row['from_by'], PDO::PARAM_INT );
						$stmt->bindParam( ':status', $row['status'], PDO::PARAM_INT );
						$stmt->bindParam( ':note', $row['note'], PDO::PARAM_STR );
						$stmt->bindParam( ':edit_time', $row['edit_time'], PDO::PARAM_INT );
						$exc = $stmt->execute();
					}
				}
				catch ( PDOException $e )
				{
					print_r( $row );
					exit( $e->getMessage() );
				}
			}
			else
			{

				$school_data = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_school WHERE school_title LIKE "%' . $row['school_id'] . '%" AND provinceid =2' )->fetch();
				if( ! empty( $school_data ) )
				{
					$row['school_id'] = $school_data['schoolid'];
					$row['provinceid'] = $school_data['provinceid'];
					$row['districtid'] = $school_data['districtid'];
					$row['gmap_lng'] = $school_data['gmap_lng'];
					$row['gmap_lat'] = $school_data['gmap_lat'];
				}
				$db->query( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_student SET provinceid=" . intval( $row['provinceid'] ) . ", districtid=" . intval( $row['districtid'] ) . ", gmap_lat=" . intval( $row['gmap_lat'] ) . ", gmap_lng=" . intval( $row['gmap_lng'] ) . " WHERE studentid=" . $data_student['studentid'] );
			}
		}else{
		  //print_r($row);die;
		}
	}
}

$step = $nv_Request->get_int( 'step', 'get,post', 1 );
if( $step == 1 )
{
	if( file_exists( NV_ROOTDIR . '/includes/class/PHPExcel.php' ) )
	{
		$lang_module['import_note'] = sprintf( $lang_module['import_note'], NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;example=1", SYSTEM_UPLOADS_DIR . '/' . $module_name );
		$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
		$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op );
		$xtpl->assign( 'LANG', $lang_module );
		$xtpl->assign( 'OP', $op );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
		$array_file = nv_scandir( NV_ROOTDIR . '/' . SYSTEM_UPLOADS_DIR . "/" . $module_name, "/^([0-9A-Za-z\/\_\.\@\(\)\~\-\%\\s]+)\.(xls|xlsx)$/" );
		if( sizeof( $array_file ) )
		{
			foreach( $array_file as $file_name )
			{
				$file_size = filesize( NV_ROOTDIR . '/' . SYSTEM_UPLOADS_DIR . "/" . $module_name . "/" . $file_name );
				$array_data = array(
					'file_name' => $file_name,
					'file_size' => nv_convertfromBytes( $file_size ),
					'file_name_base64' => nv_base64_encode( $file_name ) );
				$xtpl->assign( 'DATA', $array_data );
				$xtpl->parse( 'main.read.loop' );
			}
			$xtpl->parse( 'main.read' );
		}

		$xtpl->parse( 'main' );
		$contents = $xtpl->text( 'main' );
	}
	else
	{
		$contents = $lang_module['required_phpexcel'];
	}

	$page_title = $lang_module['import'];
	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
}
elseif( $step == 2 )
{
	$listfile = $nv_Request->get_string( 'listfile', 'post', '', 0 );
	$subject_id = $nv_Request->get_int( 'subject_id', 'post', 0 );
	$time_id = $nv_Request->get_int( 'time_id', 'post', 0 );
	$examp_id = $nv_Request->get_int( 'examp_id', 'post', 0 );
	if( ! empty( $listfile ) )
	{
		$temp = explode( "@", $listfile );
		$arr_file = array();
		foreach( $temp as $fb )
		{
			if( ! empty( $fb ) )
			{
				$arr_file[] = nv_base64_decode( $fb );
			}
		}
		$nv_Request->set_Session( $module_data . '_listfile', implode( "@", $arr_file ) );
		$nv_Request->set_Session( $module_data . '_getfile', 0 );
		$getfile = 0;
	}
	else
	{
		$listfile = $nv_Request->get_string( $module_data . '_listfile', 'session' );
		$getfile = $nv_Request->get_int( $module_data . '_getfile', 'session', 0 );
		$arr_file = explode( "@", $listfile );
	}
	if( $getfile < count( $arr_file ) )
	{
		if( $sys_info['allowed_set_time_limit'] )
		{
			set_time_limit( 0 );
		}
		if( $sys_info['ini_set_support'] )
		{
			$memoryLimitMB = ( integer )ini_get( 'memory_limit' );
			if( $memoryLimitMB < 1024 )
			{
				ini_set( "memory_limit", "1024M" );
			}
		}
		asort( $arr_file );
		$data_insert = nv_read_data_from_excel( $arr_file[$getfile], $subject_id, $time_id, $examp_id );
		if( ! empty( $data_insert ) )
		{
			file_put_contents( NV_ROOTDIR . '/' . NV_CACHEDIR . '/' . $module_name . '/data_insert.cache', serialize( $data_insert ) );
		}
		else
		{
			file_put_contents( NV_ROOTDIR . '/' . NV_CACHEDIR . '/' . $module_name . '/data_insert.cache', '' );
		}

		$nv_Request->set_Session( $module_data . '_getfile', $getfile + 1 );
		die( "OK_GETFILE" );
	}
	else
	{
		$nv_Request->unset_request( $module_data . '_listfile', 'session' );
		$nv_Request->unset_request( $module_data . '_getfile', 'session' );
		die( "OK_COMPLETE" );
	}
}
elseif( $step == 3 )
{
	$data_insert = file_get_contents( NV_ROOTDIR . '/' . NV_CACHEDIR . '/' . $module_name . '/data_insert.cache' );
	if( ! empty( $data_insert ) )
	{
		$data_insert = unserialize( $data_insert );
		$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
		$xtpl->assign( 'LANG', $lang_module );
		$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
		$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
		$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
		$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
		$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
		$xtpl->assign( 'MODULE_NAME', $module_name );
		$xtpl->assign( 'OP', $op );
		$number = 1;
		foreach( $data_insert as $data )
		{
			$data['number'] = $number;
			$xtpl->assign( 'VIEW', $data );
			$xtpl->parse( 'main.view.loop' );
			$number++;
		}
		$xtpl->parse( 'main.view' );
		$contents = $xtpl->text( 'main.view' );
		include NV_ROOTDIR . '/includes/header.php';
		echo nv_admin_theme( $contents );
		include NV_ROOTDIR . '/includes/footer.php';
	}
	else
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
		die();
	}
}
