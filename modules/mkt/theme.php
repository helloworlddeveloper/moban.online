<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 18 Nov 2014 01:50:26 GMT
 */

if( ! defined( 'NV_IS_MOD_RM' ) ) die( 'Stop!!!' );
/**
 * nv_theme_rm_main()
 * 
 * @param mixed $array_data
 * @return
 */
function nv_theme_mkt_register( $array_listevents, $array_province, $eventid )
{
	global  $module_name, $module_file, $lang_module, $module_info, $op, $flag_allow;

	$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'MODULE_FILE', $module_file );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'OP', $op );

	if( $flag_allow == 1 ){
        $xtpl->parse( 'main.data.user_refer' );
    }

    if( !empty( $array_listevents )){
        foreach ( $array_listevents as $events ){
            $events['ck'] = ( $eventid == $events['id'] )? ' checked=checked' : '';
            $events['province'] = $array_province[$events['provinceid']]['title'];
            $events['timeevent'] = date('H:i d/m/Y', $events['timeevent'] );
            $xtpl->assign( 'EVENT', $events );
            $xtpl->parse( 'main.data.events' );
        }
        foreach ( $array_province as $province ){
            $xtpl->assign( 'PROVINCE', $province );
            $xtpl->parse( 'main.data.province' );
        }
        $xtpl->parse( 'main.data' );
    }else{
        $xtpl->parse( 'main.nodata' );
    }

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}
function nv_theme_mkt_school_list( $array_data, $khoangcach, $center_address, $array_total_sum, $maps_config )
{
	global $global_config, $module_name, $module_file, $lang_module, $module_config, $module_info, $op, $array_school_type;

	$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$lang_module['title_school_list'] = sprintf( $lang_module['title_school_list'], $khoangcach, $center_address );

	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'MODULE_FILE', $module_file );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'OP', $op );
	$xtpl->assign( 'khoangcach', $khoangcach );
	$xtpl->assign( 'TOTAL', $array_total_sum );
	if( ! empty( $array_data ) )
	{
		$stt = 1;
		;
		foreach( $array_data as $data )
		{
			$data['stt'] = $stt++;
			$xtpl->assign( 'DATA', $data );
			$xtpl->parse( 'main.loop' );
		}
	}
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}
function nv_theme_mkt_student( $array_data, $khoangcach, $schooltype, $maps_config )
{
	global $global_config, $module_name, $module_file, $lang_module, $module_config, $module_info, $op, $array_school_type;

	$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'MODULE_FILE', $module_file );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'OP', $op );
	$xtpl->assign( 'khoangcach', $khoangcach );

	$xtpl->assign( 'MAPS_CONFIG', $maps_config );

	foreach( $array_school_type as $key => $school_type )
	{
		$ck = in_array( $key, $schooltype ) ? ' checked=checked' : '';
		$xtpl->assign( 'SCHOOLTYPE', array(
			'ck' => $ck,
			'key' => $key,
			'title' => $school_type ) );
		$xtpl->parse( 'main.schooltype' );
	}

	if( ! empty( $array_data ) )
	{
		foreach( $array_data as $data )
		{
			$data['address'] = nv_nl2br( $data['address'] );
			$xtpl->assign( 'VIEW', $data );
			$xtpl->parse( 'main.map_point' );
			$xtpl->parse( 'main.map_info' );
		}
	}
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}
function nv_theme_mkt_student_list( $array_data, $khoangcach, $center_address, $array_total_sum, $maps_config )
{
	global $global_config, $module_name, $module_file, $lang_module, $module_config, $module_info, $op, $array_school_type;

	$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$lang_module['title_school_list'] = sprintf( $lang_module['title_school_list'], $khoangcach, $center_address );

	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'MODULE_FILE', $module_file );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'OP', $op );
	$xtpl->assign( 'khoangcach', $khoangcach );
	$xtpl->assign( 'TOTAL', $array_total_sum );
	if( ! empty( $array_data ) )
	{
		$stt = 1;
		;
		foreach( $array_data as $data )
		{
			$data['stt'] = $stt++;
			$xtpl->assign( 'DATA', $data );
			$xtpl->parse( 'main.loop' );
		}
	}
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function nv_theme_mkt_statistic( $array_data, $array_classes, $array_total_sum )
{
	global $global_config, $module_name, $module_file, $lang_module, $module_config, $module_info, $op, $array_school_type;

	$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );

	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'MODULE_FILE', $module_file );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'OP', $op );
	$xtpl->assign( 'TOTAL', $array_total_sum );
	if( ! empty( $array_data ) )
	{
		$stt = 1;
		foreach( $array_data as $data )
		{
			$data['stt'] = $stt++;
			$xtpl->assign( 'DATA', $data );
			$xtpl->parse( 'main.school.loop' );
		}
		$xtpl->parse( 'main.school' );
	}
	if( ! empty( $array_classes ) )
	{
		$stt = 1;
		foreach( $array_classes as $data )
		{
			$data['stt'] = $stt++;
			$xtpl->assign( 'DATA', $data );
			$xtpl->parse( 'main.classes.loop' );
		}
		$xtpl->parse( 'main.classes' );
	}
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function nv_theme_mkt_eventcontent( $id, $eventid )
{
	global $lang_global, $user_info, $module_name, $module_file, $lang_module, $module_info, $op;

	$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );

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
	$xtpl->assign( 'CHECKSESS', md5( $module_name . "_" . $user_info['userid'] . "_" . session_id() ) );
    if( $id != ''){
        $xtpl->assign( 'id', $id );
        $xtpl->assign( 'eventid', $eventid );
        $xtpl->parse( 'main.search' );
    }
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function nv_theme_mkt_sendmail( $array_statistic, $array_events, $array_student, $array_parent, $array_data_school_by_user, $array_data_events, $array_data_nhan_xet, $array_by_users, $list_nv_measure, $timesearch )
{
	global $global_config, $module_name, $module_file, $lang_module, $module_config, $module_info, $op, $global_array_room, $array_me;

	$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
	$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'NV_MY_DOMAIN', NV_MY_DOMAIN );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'STATISTIC', $array_statistic );
	$xtpl->assign( 'timesearch', $timesearch );
    
    if( isset( $array_statistic['total_student_new_to_current'] )){
       $xtpl->parse( 'main.tong_hop_tuan' );
    }
    
	if( ! empty( $array_data_school_by_user ) )
	{
		foreach( $array_data_school_by_user as $school_by_user )
		{
			$xtpl->assign( 'SCHOOL', $school_by_user );
			$xtpl->parse( 'main.school.loop' );
		}
		$xtpl->parse( 'main.school' );
	}
	if( ! empty( $array_data_events ) )
	{
		foreach( $array_by_users as $by_users )
		{
			$xtpl->assign( 'admin_name', $by_users['admin_name'] );
			$xtpl->parse( 'main.data_events.user' );
            $xtpl->assign( 'array_sum', array_sum( $by_users['count'] ) );
            $xtpl->parse( 'main.data_events.usertotal' );
		}
        $total_sum_cham_soc = 0;
		foreach( $array_data_events as $data_events )
		{
			foreach( $array_by_users as $by_users )
			{
				$xtpl->assign( 'admin_name', $by_users['count'][$data_events['measureid']] );
				$xtpl->parse( 'main.data_events.loop.user' );
			}
            $total_sum_cham_soc += $data_events['total'];
			$xtpl->assign( 'EVENT', $data_events );
			$xtpl->parse( 'main.data_events.loop' );
		}
        $xtpl->assign( 'total_sum_cham_soc', number_format( $total_sum_cham_soc, 0, ' ', '.') );
		$xtpl->parse( 'main.data_events' );
	}
	if( ! empty( $array_data_nhan_xet ) )
	{
		foreach( $array_data_nhan_xet as $nhan_xet )
		{
			$nhan_xet['info_student'] = ( isset( $array_student[$nhan_xet['userid']] ) ) ? 'HS: ' . $array_student[$nhan_xet['userid']] : 'N/A';
			$xtpl->assign( 'NHAN_XET', $nhan_xet );
			$xtpl->parse( 'main.nhan_xet.loop' );
		}
		$xtpl->parse( 'main.nhan_xet' );
	}

	if( ! empty( $array_events ) )
	{
		foreach( $array_events as $events )
		{
			if( $events['studentid'] > 0 )
			{
				$events['info_customer'] = ( isset( $array_student[$events['studentid']] ) ) ? 'HS: ' . $array_student[$events['studentid']] : 'N/A';
			}
			elseif( $events['parentid'] > 0 )
			{
				$events['info_customer'] = ( isset( $array_parent[$events['parentid']] ) ) ? 'PH: ' . $array_parent[$events['parentid']] : 'N/A';
			}
			$xtpl->assign( 'EVENTS', $events );
			$xtpl->parse( 'main.eventcontent.loop' );
		}
		$xtpl->parse( 'main.eventcontent' );
	}
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}
