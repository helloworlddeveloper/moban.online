<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/9/2010 23:25
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( ! nv_function_exists( 'nv_block_calendar' ) )
{
	function nv_block_calendar( $block_config )
	{
		global $db_slave, $module_info, $lang_module, $site_mods, $array_cat, $client_info;

		$module = $block_config['module'];
		$mod_file = $site_mods[$module]['module_file'];

        $array_info_week = getInfoWeeks(NV_CURRENTTIME);

        $array_search['starttime'] = mktime(0,0,0, $array_info_week['month_current'], 1, $array_info_week['year_current'] );
        $array_search['endtime'] = mktime(23,59,59, $array_info_week['month_current'], $array_info_week['maxday'], $array_info_week['year_current'] );

        $array_data_by_cat = array();

        foreach ( $array_cat as $catid => $cat_info){
            //lich co dinh
            $sql_catid = ' AND catid=' . $catid;
            $db_slave->sqlreset()
                ->select('*')
                ->from(NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'])
                ->where('status= 1 AND timefix=1' . $sql_catid)
                ->order('hour_minute_begin, timeevent_begin');
            $array_data_fix = array();
            $array_data = array();
            $result = $db_slave->query($db_slave->sql());
            while ($item = $result->fetch()) {

                $array_data_fix[] = $item;
            }

            foreach ( $array_data_fix as $data ){
                $day = date('d', $data['timeevent_begin']);
                for($i= 1; $i<= $array_info_week['week_num']; $i++ ){
                    $day_num = vsprintf('%02s', $day);
					//$data['date_current'] = mktime(0,0,0, $array_info_week['month_current'], $day_num, $array_info_week['year_current'] );
                    $key_day = $array_info_week['year_current'] . $array_info_week['month_current'] .$day_num;
                    $day = $day + 7;
					$data['date_next'] = $day_num . '/' . $array_info_week['month_current'] . '/' . $array_info_week['year_current'];
                    $array_data[$key_day][$data['hour_minute_begin']] = $data;
                }
            }
            //lich lam viec theo thoi gian
            $db_slave->sqlreset()
                ->select('*')
                ->from(NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'])
                ->where('status= 1 AND timeevent_begin>=' . $array_search['starttime'] . ' AND timeevent_end<=' . $array_search['endtime'] . $sql_catid )
                ->order('timeevent_begin');

            $result = $db_slave->query($db_slave->sql());
            while ($item = $result->fetch()) {
                $keydate = date('Ymd', $item['timeevent_begin']);
                $array_data[$keydate][$item['hour_minute_begin']] = $item;
            }

            //lay lich thay the neu co dang xy ly ghi de
            $db_slave->sqlreset()
                ->select('*')
                ->from(NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_other')
                ->where('status= 1 AND timeevent_begin>=' . $array_search['starttime'] . ' AND timeevent_end<=' . $array_search['endtime']  . $sql_catid)
                ->order('timeevent_begin');

            $result = $db_slave->query($db_slave->sql());
            while ($item = $result->fetch()) {
                $keydate = date('Ymd', $item['timeevent_begin']);
                $array_data[$keydate][$item['hour_minute_begin']] = $item;
            }

            $array_data_by_cat[] = array('cat' => $cat_info, 'data' => $array_data);
        }


        if( file_exists( NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $mod_file . '/block_calendar.tpl' ) )
        {
            $block_theme = $module_info['template'];
        }
        else
        {
            $block_theme = 'default';
        }

        $xtpl = new XTemplate( 'block_calendar.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $mod_file );
        $xtpl->assign('LANG', $lang_module);
        $xtpl->assign('mod_file', $mod_file);
        $xtpl->assign('TEMPLATE', $block_theme);
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
        $xtpl->assign('DATE_YEAR', date('m/Y', NV_CURRENTTIME));

        $stt = 1;
        if( $client_info['is_mobile'] == 0 ){
            foreach ( $array_data_by_cat as $data_array ){
                if( $stt == 1 ){
                    $xtpl->parse('main.desktop.tabs_title.active');
                    $xtpl->parse('main.desktop.tabs_content.active');
                    $stt = 2;
                }
                $xtpl->assign('TABS_TITLE', $data_array['cat']);
                $array_data = $data_array['data'];
                $col_begin_week = 0;
                for( $i = 1; $i<= $array_info_week['maxday']; $i++ ){
                    if( $array_info_week['beginweek'] <= $i ){
                        $day_num = vsprintf('%02s', $i);
                        $key_day = $array_info_week['year_current'] . $array_info_week['month_current'] . $day_num;

                        if( isset( $array_data[$key_day] )){
                            foreach ( $array_data[$key_day] as $key => $data ){
                                if( $key < 1200){
                                    $data['class'] = 'success';
                                }elseif( $key < 1800){
                                    $data['class'] = 'primary';
                                }else{
                                    $data['class'] = 'secondary';
                                }

                                $data['date_next'] = isset($data['date_next'] )? $data['date_next'] : date('H:i d/m/Y', $data['timeevent_end'] );
                                $data['timeevent_begin'] = date('H:i', $data['timeevent_begin'] );
                                $data['timeevent_end'] = date('H:i', $data['timeevent_end'] );
                                $xtpl->assign('CONTENT', $data);
                                $xtpl->parse('main.desktop.tabs_content.rows.loop.data');

                                if( $data['timefix']){
                                    $keydate = date('Ymd', $data['timeevent_begin'] + (7 * 86400));
                                    $array_data[$keydate][] = $data;
                                }
                            }
                        }
                        $currentday = '';
                        if( $array_info_week['currentday'] == $i ){
                            $currentday = ' fc-state-highlight';
                        }
                        $tmp = array('day' => $i, 'currentday' => $currentday);
                        $xtpl->assign('DATA', $tmp);
                        $col_begin_week++;
                    }
                    $xtpl->parse('main.desktop.tabs_content.rows.loop');
                    if( $col_begin_week % 7 == 0 ){
                        $col_begin_week = 0;
                        $xtpl->parse('main.desktop.tabs_content.rows');
                    }
                }

                if( $col_begin_week > 0 ){
                    $xtpl->parse('main.desktop.tabs_content.rows');
                }
                $xtpl->parse('main.desktop.tabs_content');
                $xtpl->parse('main.desktop.tabs_title');
            }
            $xtpl->parse('main.desktop');
        }
        else{
			
            foreach ( $array_data_by_cat as $data_array ){
                if( $stt == 1 ){
                    $xtpl->parse('main.mobile.tabs_title.active');
                    $xtpl->parse('main.mobile.tabs_content.active');
                    $stt = 2;
                }
                $xtpl->assign('TABS_TITLE', $data_array['cat']);
                $array_data = $data_array['data'];
                $col_begin_week = 0;
                for( $i = 1; $i<= $array_info_week['maxday']; $i++ ){
                    $is_data = 0;
                    if( $array_info_week['beginweek'] <= $i ){
                        $day_num = vsprintf('%02s', $i);
                        $key_day = $array_info_week['year_current'] . $array_info_week['month_current'] . $day_num;

                        if( isset( $array_data[$key_day] )){
                            $is_data = 1;
                            foreach ( $array_data[$key_day] as $key => $data ){
                                if( $key < 1200){
                                    $data['class'] = 'success';
                                }elseif( $key < 1800){
                                    $data['class'] = 'primary';
                                }else{
                                    $data['class'] = 'secondary';
                                }
                                $data['date_next'] = isset($data['date_next'] )? $data['date_next'] : date('H:i d/m/Y', $data['timeevent_end'] );
                                $data['timeevent_begin'] = date('H:i', $data['timeevent_begin'] );
								
								$time_current = mktime(0,0,0,$array_info_week['month_current'], $day_num, $array_info_week['year_current']);
                                $data['timeevent_mobile'] = nv_date('D d/m', $time_current );
                                $data['timeevent_end'] = date('H:i', $data['timeevent_end'] );
                                $xtpl->assign('CONTENT', $data);
                                $xtpl->parse('main.mobile.tabs_content.rows.loop.data');

                                if( $data['timefix']){
                                    $keydate = date('Ymd', $data['timeevent_begin'] + (7 * 86400));
                                    $array_data[$keydate][] = $data;
                                }
                            }
                        }
                        $currentday = '';
                        if( $array_info_week['currentday'] == $i ){
                            $currentday = ' fc-state-highlight';
                        }
                        $tmp = array('day' => $i, 'currentday' => $currentday);
                        $xtpl->assign('DATA', $tmp);
                        $col_begin_week++;
                    }
                    if( $is_data == 1){
                        $xtpl->parse('main.mobile.tabs_content.rows.loop');
                        if( $col_begin_week % 7 == 0 ){
                            $col_begin_week = 0;
                            $xtpl->parse('main.mobile.tabs_content.rows');
                        }
                    }

                }

                if( $col_begin_week > 0 ){
                    $xtpl->parse('main.mobile.tabs_content.rows');
                }
                $xtpl->parse('main.mobile.tabs_content');
                $xtpl->parse('main.mobile.tabs_title');
            }
            $xtpl->parse('main.mobile');
        }

		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}
}

if( defined( 'NV_SYSTEM' ) )
{
	global $site_mods, $array_cat, $array_group;
	$module = $block_config['module'];
	if( isset( $site_mods[$module] ) )
	{

	    if( $module != $module_name ){
            $sql = 'SELECT * FROM ' . NV_PREFIXLANG . "_" . $site_mods[$module]['module_data'] . '_cat';
            $array_cat = $nv_Cache->db($sql, 'id', $module );

            $sql = 'SELECT * FROM ' . NV_PREFIXLANG . "_" . $site_mods[$module]['module_data'] . '_group';
            $array_group = $nv_Cache->db($sql, 'id', $module );

            function getInfoWeeks($timestamp)
            {
                $array_reponsive = array();
                $array_reponsive['currentday'] = date("j",$timestamp);
                $array_reponsive['maxday'] = date("t",$timestamp);
                $array_reponsive['beginweek'] = date("w",mktime(0,0,0, date('m', $timestamp ), 1, date('y', $timestamp )));
                $array_reponsive['month_current'] = date('m', NV_CURRENTTIME);
                $array_reponsive['year_current'] = date('Y', NV_CURRENTTIME);

                $thismonth = getdate($timestamp);
                $timeStamp = mktime(0,0,0,$thismonth['mon'],1,$thismonth['year']);    //Create time stamp of the first day from the give date.
                $startday  = date('w',$timeStamp);    //get first day of the given month
                $weeks = 0;
                $week_num = 0;

                for ($i=0; $i<($array_reponsive['maxday']+$startday); $i++) {
                    if(($i % 7) == 0){
                        $weeks++;
                    }
                    if($array_reponsive['maxday'] == ($i - $startday + 1)){
                        $week_num = $weeks;
                    }
                }
                $array_reponsive['week_num'] = $week_num;
                return $array_reponsive;
            }
        }


        $content = nv_block_calendar( $block_config );
	}
}
