<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jul 11, 2010 8:43:46 PM
 */

if (!defined('NV_IS_MOD_SM')) {
    die('Stop!!!');
}


/**
 * nv_theme_reg_main()
 *
 * @param mixed $data_content
 * @param string $html_pages
 * @return
 */
function nv_theme_calendar_main( $array_data_by_cat, $array_info_week )
{
    global $module_info, $op, $lang_module, $module_name, $module_file, $client_info;


    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('TEMPLATE', $module_info['template']);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('OP', $op);
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


    $xtpl->parse('main');
    return $xtpl->text('main');
}
