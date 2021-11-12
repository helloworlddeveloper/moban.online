<?php

if( $nv_Request->isset_request( 'send_data', 'get' ) )
{
	$row['relationship'] = $row['school_id'] = $row['gmap_lat'] = $row['gmap_lng'] = $row['parentid'] = 0;
	$row['from_by'] = 4; //id kenh du lieu
	$row['provinceid'] = 2; //HN
	$row['districtid'] = 0;
	$row['status'] = 0;
	$db->sqlreset()->select( '*' )->from( NV_PREFIXLANG . '_diem_thi_rows_backup' );
	$result = $db->query( $db->sql() );
	while( $row = $result->fetch() )
	{
		//ghi du lieu vao module mkt
		if( isset( $site_mods['mkt'] ) )
		{
			$array_replace = array( '.', ' ', ',' );
			$row['phone_student'] = str_replace( $array_replace, '', $row['phone_student'] );
			//$row['phone_student'] = intval( $row['phone_student'] );

			$row['phone_parent'] = str_replace( $array_replace, '', $row['phone_parent'] );
			//$row['phone_parent'] = intval( $row['phone_parent'] );
			if( substr( $row['phone_student'], 0, 1 ) != 0 )
			{
				$row['phone_student'] = 0 . $row['phone_student'];
			}
			if( substr( $row['phone_parent'], 0, 1 ) != 0 )
			{
				$row['phone_parent'] = 0 . $row['phone_parent'];
			}
            $row['full_name'] = trim( $row['full_name'] );
			$sql = "SELECT COUNT(*) FROM " . NV_PREFIXLANG . '_' . $module_data . "_student WHERE mobile LIKE '%" . $row['phone_student'] . "%' AND student_name LIKE '%" . $row['full_name'] . "%'";
			
            $number = $db->query( $sql )->fetchColumn();
			if( $number == 0 )
			{   
				require_once NV_ROOTDIR . '/modules/mkt/eventcontent.php';

				$year_birthday = date( 'Y', $row['birthday'] );
				$shool_year = date( 'Y', NV_CURRENTTIME ) - $year_birthday - 16; //6 la tuoi di hoc lop 1 thong thuong

				$array_data_student = array(
					'student_name' => $row['full_name'],
					'school_id' => 0,
					'sex' => $row['sex'],
					'class' => 10,
					'address' => $row['address'],
					'mobile' => $row['phone_student'],
					'schoolid' => 0,
					'email' => $row['email'] . ' - ' . $row['school1'],
					'facebook' => $row['facebook'],
					'birthday' => $row['birthday'],

					'provinceid' => 0,
					'districtid' => 0,
					'parentid' => 0,
					'shool_year' => $shool_year, //
					'relationship' => 0, //0
					'gmap_lat' => 0,
					'gmap_lng' => 0,
					'from_by' => 9 // 9 la id thi thu,
						);

				$array_data_parent = array(
					'parent_name' => empty( $row['parent_name'] ) ? 'PH: ' . $row['full_name'] : $row['parent_name'],
					'address' => $row['address'],
					'mobile' => $row['phone_parent'],
					'birthday' => 0,
					'address' => $row['address'],
					'provinceid' => 0,
					'districtid' => 0,
					'email' => $row['email'],
					'numberphone' => '',
					'jobs' => 0,
					'from_by' => 9, // 4 la id thi thu
					'gmap_lat' => 0,
					'gmap_lng' => 0,
					'income' => 5,
					'status' => 1 );

				$studentid = check_data_info( $row['full_name'], $row['phone_student'], 'student' );
				if( $studentid == 0 )
				{
					$tablefrom = 'diem_thi_rows';
					$cloumnfrom = 'id';
					$array_data_student['parentid'] = check_data_info( $row['parent_name'], $row['phone_parent'], 'parent' );
					if( $array_data_student['parentid'] == 0 )
					{
						$array_data_student['parentid'] = save_data_parent_info( $array_data_parent );
						if( $array_data_student['parentid'] > 0 )
						{
							$tableto = 'mkt_parent';
							$cloumnto = 'parentid';
							$line = 0;
							save_data_connect( $row['id'], $array_data_student['parentid'], $tablefrom, $tableto, $cloumnfrom, $cloumnto, $line );
						}
					}
					$studentid = save_data_student_info( $array_data_student );
					if( $studentid > 0 )
					{
						$tableto = 'mkt_student';
						$cloumnto = 'studentid';
						$line = 0;
						save_data_connect( $row['id'], $studentid, $tablefrom, $tableto, $cloumnfrom, $cloumnto, $line );
					}

				}
				if( $studentid > 0 )
				{
					$note = 'Đăng ký nhận điểm thi vào lớp 10 năm học 2015 - 2016';
					$measureid = 0;
					$eventtype = 0;

					$res = save_eventcontent( $studentid, 0, $measureid, $eventtype, $note );
				}
			}
		}
	}
	die( 'OK' );
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( 'Ban Chua gui du lieu' );
include NV_ROOTDIR . '/includes/footer.php';
