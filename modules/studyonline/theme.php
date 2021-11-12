<?php

/**
 * @Project NUKEVIET 4.x
 * @Author EDUSGROUP.JSC (thangbv@edus.vn)
 * @Copyright (C) 2014 EDUSGROUP.JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 14 Mar 2017 10:56:01 GMT
 */

if(! defined('NV_IS_MOD_STUDYONLINE'))
    die('Stop!!!');

function nv_theme_main_studyonline($array_data, $generate_page)
{
    global $module_name, $module_file, $lang_module, $module_info, $op, $array_class, $msystem;

    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
    $xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
    $xtpl->assign('MODULE_NAME', $module_name);
    if(empty($array_data))
    {
        $xtpl->parse('main.empty');
    }
    elseif(! empty($array_data))
    {
        $xtpl->assign('MSYSTEM', $msystem);
        foreach($array_class as $classid => $class)
        {
            if(isset($array_data[$classid]))
            {
                $xtpl->assign('CLASS', $class);
                $array_data_by_class = $array_data[$classid];
                foreach($array_data_by_class as $data)
                {
                    $xtpl->assign('ROW', $data);

                    if( $data['price'] > 0 ){
                        if( !empty( $msystem['icon'] )){
                            $xtpl->parse('main.data.class.loop.money_icon');
                        }else{
                            $xtpl->parse('main.data.class.loop.money_text');
                        }
                    }
                    foreach($data['teacher_info'] as $teacher_info)
                    {
                        $xtpl->assign('TEACHER_INFO', $teacher_info);
                        $xtpl->parse('main.data.class.loop.teacher_info');
                    }
                    if(! empty($l['thumb']))
                    {
                        $xtpl->parse('main.data.class.loop.img');
                    }
                    $xtpl->parse('main.data.class.loop');
                }
                unset($array_data_by_class);
                $xtpl->parse('main.data.class');
            }

        }
        if(! empty($generate_page))
        {
            $xtpl->assign('GENERATE_PAGE', $generate_page);
            $xtpl->parse('main.data.generate_page');
        }
        $xtpl->parse('main.data');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

function nv_theme_mon_studyonline($array_data, $page_title, $subjectid, $generate_page)
{
    global $module_name, $module_file, $lang_module, $module_info, $op, $array_subject;

    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
    $xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('page_title', $page_title);

    if(empty($array_data))
    {
        $xtpl->parse('main.empty');
    }
    elseif(! empty($array_data))
    {

        $xtpl->assign('SUBJECT', $array_subject[$subjectid]);
        foreach($array_data as $data)
        {
            $xtpl->assign('ROW', $data);
            foreach($data['teacher_info'] as $teacher_info)
            {
                $xtpl->assign('TEACHER_INFO', $teacher_info);
                $xtpl->parse('main.data.loop.teacher_info');
            }
            if(! empty($l['thumb']))
            {
                $xtpl->parse('main.data.loop.img');
            }
            $xtpl->parse('main.data.loop');
        }

        if(! empty($generate_page))
        {
            $xtpl->assign('GENERATE_PAGE', $generate_page);
            $xtpl->parse('main.data.generate_page');
        }
        $xtpl->parse('main.data');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

function nv_theme_teacher_studyonline($array_data, $teacher_info, $content_comment)
{
    global $module_name, $module_file, $lang_module, $module_info, $op, $array_subject, $msystem;

    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
    $xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('MSYSTEM', $msystem);

    if(! empty($content_comment))
    {
        $xtpl->assign('CONTENT_COMMENT', $content_comment);
        $xtpl->parse('main.comment');
    }

    $teacher_info['subjectlist'] = explode(',', $teacher_info['subjectlist']);

    foreach($teacher_info['subjectlist'] as $subjectid)
    {
        if(isset($array_subject[$subjectid]))
        {
            $teacher_info['subject'][] = $array_subject[$subjectid]['title'];
        }
    }
    if(! empty($teacher_info['subject']))
    {
        $teacher_info['subject'] = implode(', ', $teacher_info['subject']);
    }

    if($teacher_info['avatar'] != '' && file_exists(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . '/' . $teacher_info['avatar']))
    {
        $teacher_info['avatar'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $teacher_info['avatar'];
    }
    else
    {
        $teacher_info['avatar'] = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_name . '/teacher.png';
    }
    $xtpl->assign('TEACHER_INFO', $teacher_info);

    if(empty($array_data))
    {
        $xtpl->parse('main.empty');
    }
    elseif(! empty($array_data))
    {
        foreach($array_data as $data)
        {
            $xtpl->assign('ROW', $data);
            if( $data['price'] > 0 ){
                if( !empty( $msystem['icon'] )){
                    $xtpl->parse('main.data.khoahoc.loop.money_icon');
                }else{
                    $xtpl->parse('main.data.khoahoc.loop.money_text');
                }
            }
            if(! empty($l['thumb']))
            {
                $xtpl->parse('main.data.khoahoc.loop.img');
            }
            $xtpl->parse('main.data.khoahoc.loop');
        }
        $xtpl->parse('main.data.khoahoc');

        $xtpl->parse('main.data');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * studyonline_detail_theme()
 * 
 * @param mixed $news_contents
 * @param mixed $array_keyword
 * @param mixed $content_comment
 * @return
 */
function studyonline_detail_theme($news_contents, $num_view_baigiang, $array_baihoc, $array_baihoc_mienphi, $array_baihoc_da_mua, $array_khoahoc_da_mua, $array_keyword, $content_comment)
{
    global $global_config, $module_info, $lang_module, $module_name, $module_config, $lang_global, $user_info, $admin_info, $client_info, $msystem;

    $xtpl = new XTemplate('detail.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG_GLOBAL', $lang_global);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('TEMPLATE', $global_config['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('module_theme', $module_info['module_theme']);
    $news_contents['addtime'] = nv_date('d/m/Y h:i:s', $news_contents['addtime']);

    $xtpl->assign('NEWSID', $news_contents['id']);
    $xtpl->assign('NEWSCHECKSS', $news_contents['newscheckss']);
    $xtpl->assign('DETAIL', $news_contents);
    $xtpl->assign('SELFURL', $client_info['selfurl']);

    $xtpl->assign('LANGSTAR', $news_contents['langstar']);
    $xtpl->assign('STRINGRATING', $news_contents['stringrating']);
    $xtpl->assign('NUMBERRATING', $news_contents['numberrating']);

    if( $num_view_baigiang > 0 ){
        $xtpl->parse('main.popupreview');
    }

    if( $news_contents['price'] == 0){
        if( empty( $array_khoahoc_da_mua )){
            $xtpl->parse('main.khoahocmienphi.dangkyngay');
        }else{
            $xtpl->parse('main.khoahocmienphi.dadangky');
        }
        $xtpl->parse('main.khoahocmienphi');
    }
    elseif( empty( $array_khoahoc_da_mua )){
        $xtpl->assign('MSYSTEM', $msystem);
        if( !empty( $msystem['icon'] )){
            $xtpl->parse('main.muakhoahoc.money_icon');
        }else{
            $xtpl->parse('main.muakhoahoc.money_text');
        }
        $xtpl->parse('main.muakhoahoc');
    }else{
        $xtpl->parse('main.damuakhoahoc');
    }
    
    if(! empty($news_contents['teacherinfo']))
    {
        foreach($news_contents['teacherinfo'] as $teacherinfo)
        {
            $xtpl->assign('TEACHERINFO', $teacherinfo);
            $xtpl->parse('main.teacherinfo.loop');
        }
        $xtpl->parse('main.teacherinfo');
    }
    if(! empty($array_baihoc_mienphi))
    {
        foreach($array_baihoc_mienphi as $baihoc)
        {
            $xtpl->assign('BAIHOC', $baihoc);
            if(! empty($baihoc['image']))
            {
                $xtpl->parse('main.baihoc_mienphi.loop.image');
            }
            if($baihoc['timephathanh'] > NV_CURRENTTIME)
            {
                $xtpl->parse('main.baihoc_mienphi.loop.loading');
            }
            else
            {
                $xtpl->parse('main.baihoc_mienphi.loop.vaohoc');
            }
            $xtpl->parse('main.baihoc_mienphi.loop');
        }
        $xtpl->parse('main.baihoc_mienphi');
    }
    if(! empty($array_baihoc))
    {
        foreach($array_baihoc as $baihoc)
        {
            $xtpl->assign('BAIHOC', $baihoc);
            if(! empty($baihoc['image']))
            {
                $xtpl->parse('main.baihoc.loop.image');
            }
            if($baihoc['timephathanh'] > NV_CURRENTTIME)
            {
                $xtpl->parse('main.baihoc.loop.loading');
            }
            if(! empty($array_khoahoc_da_mua))
            {
                if($baihoc['timephathanh'] <= NV_CURRENTTIME)
                {
                    $xtpl->parse('main.baihoc.loop.vaohoc');
                }
                else
                {
                    $xtpl->parse('main.baihoc.loop.damuabaihoc');
                }
            }
            elseif(isset($array_baihoc_da_mua[$baihoc['id']]))
            {
                //het luot xem phai mua lai
                if($array_baihoc_da_mua[$baihoc['id']]['numview'] >= $baihoc['numviewtime'])
                {
                    $xtpl->parse('main.baihoc.loop.buybaihoc');
                }
                else
                {
                    if($baihoc['timephathanh'] <= NV_CURRENTTIME)
                    {
                        $xtpl->parse('main.baihoc.loop.vaohoc');
                    }
                    else
                    {
                        $xtpl->parse('main.baihoc.loop.damuabaihoc');
                    }
                }
            }
            else
            {
                $xtpl->parse('main.baihoc.loop.buybaihoc');
            }
            $xtpl->assign('MSYSTEM', $msystem);
            if( !empty( $msystem['icon'] )){
                $xtpl->parse('main.baihoc.loop.money_icon');
            }else{
                $xtpl->parse('main.baihoc.loop.money_text');
            }
            $xtpl->parse('main.baihoc.loop');
        }
        $xtpl->parse('main.baihoc');
    }
    if($news_contents['disablerating'] == 1)
    {
        $xtpl->parse('main.allowed_rating.disablerating');
    }

    if($news_contents['numberrating'] >= $module_config[$module_name]['allowed_rating_point'])
    {
        $xtpl->parse('main.allowed_rating.data_rating');
    }

    $xtpl->parse('main.allowed_rating');

    if(! empty($news_contents['image']))
    {
        $xtpl->parse('main.image');
    }

    if(! empty($array_keyword))
    {
        $t = sizeof($array_keyword) - 1;
        foreach($array_keyword as $i => $value)
        {
            $xtpl->assign('KEYWORD', $value['keyword']);
            $xtpl->assign('LINK_KEYWORDS', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=tag/' . urlencode($value['alias']));
            $xtpl->assign('SLASH', ($t == $i) ? '' : ', ');
            $xtpl->parse('main.keywords.loop');
        }
        $xtpl->parse('main.keywords');
    }

    if($module_config[$module_name]['socialbutton'])
    {
        global $meta_property;

        if(! empty($module_config[$module_name]['facebookappid']))
        {
            $meta_property['fb:app_id'] = $module_config[$module_name]['facebookappid'];
            $meta_property['og:locale'] = (NV_LANG_DATA == 'vi') ? 'vi_VN' : 'en_US';
        }
        $module_config['taikhoan']['image'] = $meta_property['og:image'];
        $module_config['taikhoan']['content_transaction'] = 'Share: ' . $news_contents['title'];
        $module_config['taikhoan']['tokenkey'] = md5($user_info['userid'] .$news_contents['id'] . $module_config['taikhoan']['share_facebook'] . '');
        $xtpl->assign('CONFIG_TAIKHOAN', $module_config['taikhoan'] );

        if( isset($module_config['taikhoan']) && $module_config['taikhoan']['money_facebook'] == 1){
            $xtpl->parse('main.socialbutton.money_facebook');
        }else{
            $xtpl->parse('main.socialbutton.no_money_facebook');
        }
        $xtpl->parse('main.socialbutton');
    }
    if(! empty($content_comment))
    {
        $xtpl->assign('CONTENT_COMMENT', $content_comment);
        $xtpl->parse('main.comment');
    }

    if($news_contents['status'] != 1)
    {
        $xtpl->parse('main.no_public');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * studyonline_xembaihoc_theme()
 * 
 * @param mixed $news_contents
 * @param mixed $array_keyword
 * @param mixed $content_comment
 * @return
 */
function studyonline_xembaihoc_theme($news_contents, $baihoc_contents, $array_baihoc, $array_baihoc_mienphi, $array_baihoc_da_mua, $array_khoahoc_da_mua, $array_keyword, $content_comment)
{
    global $global_config, $module_info, $lang_module, $module_name, $module_config, $lang_global, $user_info, $client_info, $msystem;

    $xtpl = new XTemplate('xembaigiang.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG_GLOBAL', $lang_global);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('TEMPLATE', $global_config['module_theme']);
    $lang_module['chovaohoc'] = sprintf($lang_module['chovaohoc'], $baihoc_contents['timephathanh_text']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('NEWSID', $news_contents['id']);
    $xtpl->assign('NEWSCHECKSS', $news_contents['newscheckss']);
    $xtpl->assign('DETAIL', $news_contents);
    $xtpl->assign('SELFURL', $client_info['selfurl']);

    $xtpl->assign('LANGSTAR', $news_contents['langstar']);
    $xtpl->assign('STRINGRATING', $news_contents['stringrating']);
    $xtpl->assign('NUMBERRATING', $news_contents['numberrating']);
    $xtpl->assign('module_theme', $module_info['module_theme']);
    $news_contents['addtime'] = nv_date('d/m/Y h:i:s', $news_contents['addtime']);
    $xtpl->assign('MSYSTEM', $msystem);

    if( !empty( $msystem['icon'] )){
        $xtpl->parse('main.money_icon');
    }else{
        $xtpl->parse('main.money_text');
    }

    $xtpl->assign('CONTENT_BAIHOC', $baihoc_contents);
    
    if( $news_contents['price'] == 0){
        if( !empty( $array_khoahoc_da_mua )){
            $xtpl->parse('main.khoahocmienphi.dadangky');
        }elseif( !empty( $array_baihoc_da_mua ) && isset( $array_baihoc_da_mua[$news_contents['id']] )){
            $xtpl->parse('main.khoahocmienphi.dadangkybaihoc');
        }else{
            $xtpl->parse('main.khoahocmienphi.dangkyngay');
        }
        $xtpl->parse('main.khoahocmienphi');
    }
    elseif( empty( $array_khoahoc_da_mua )){
        if( !empty( $msystem['icon'] )){
            $xtpl->parse('main.muakhoahoc.money_icon');
        }else{
            $xtpl->parse('main.muakhoahoc.money_text');
        }
        $xtpl->parse('main.muakhoahoc');
    }else{
        $xtpl->parse('main.damuakhoahoc');
    }
    
    if( !empty( $array_khoahoc_da_mua ) or (isset($array_baihoc_da_mua[$baihoc_contents['id']]) and $array_baihoc_da_mua[$baihoc_contents['id']]['numview'] < $baihoc_contents['numviewtime'] ) or $baihoc_contents['price'] == 0)
    {
        if($baihoc_contents['timephathanh'] <= NV_CURRENTTIME)
        {
            $xtpl->parse('main.vaohoc');
        }
        else
        {
            $xtpl->parse('main.chuaphathanh');
        }
    }
    elseif($baihoc_contents['price'] > 0)
    {
        $xtpl->parse('main.muabaihoc');
    }

    if(! empty($baihoc_contents['image']))
    {
        $xtpl->parse('main.image');
    }
    if( empty( $array_khoahoc_da_mua ) and !isset($array_baihoc_da_mua[$baihoc_contents['id']]) && $baihoc_contents['tonggiatheobai'] > $news_contents['price'])
    {
        $tietkiem = $baihoc_contents['tonggiatheobai'] - $news_contents['price'];
        $fercent = round(($tietkiem / $baihoc_contents['tonggiatheobai']) * 100);
        $xtpl->assign('strong_note', sprintf($lang_module['strong_note_buy'], number_format($tietkiem, 0, ',', '.') . '&nbsp;' . $msystem['symbol'], $fercent . '%'));
        $xtpl->parse('main.strong_note');
    }
    
    if(! empty($news_contents['teacherinfo']))
    {
        foreach($news_contents['teacherinfo'] as $teacherinfo)
        {
            $xtpl->assign('TEACHERINFO', $teacherinfo);
            $xtpl->parse('main.teacherinfo.loop');
        }
        $xtpl->parse('main.teacherinfo');
    }
    if(! empty($array_baihoc_mienphi))
    {
        foreach($array_baihoc_mienphi as $baihoc)
        {
            $xtpl->assign('BAIHOC', $baihoc);
            if(! empty($baihoc['image']))
            {
                $xtpl->parse('main.baihoc_mienphi.loop.image');
            }
            if($baihoc['timephathanh'] > NV_CURRENTTIME)
            {
                $xtpl->parse('main.baihoc_mienphi.loop.loading');
            }
            else
            {
                $xtpl->parse('main.baihoc_mienphi.loop.vaohoc');
            }
            $xtpl->parse('main.baihoc_mienphi.loop');
        }
        $xtpl->parse('main.baihoc_mienphi');
    }
    if(! empty($array_baihoc))
    {
        foreach($array_baihoc as $baihoc)
        {
            $xtpl->assign('BAIHOC', $baihoc);
            if(! empty($baihoc['image']))
            {
                $xtpl->parse('main.baihoc.loop.image');
            }
            if($baihoc['timephathanh'] > NV_CURRENTTIME)
            {
                $xtpl->parse('main.baihoc.loop.loading');
            }
            if(! empty($array_khoahoc_da_mua))
            {
                if($baihoc['timephathanh'] <= NV_CURRENTTIME)
                {
                    $xtpl->parse('main.baihoc.loop.vaohoc');
                }
                else
                {
                    $xtpl->parse('main.baihoc.loop.damuabaihoc');
                }
            }
            elseif(isset($array_baihoc_da_mua[$baihoc['id']]))
            {
                //het luot xem phai mua lai
                if($array_baihoc_da_mua[$baihoc['id']]['numview'] >= $baihoc['numviewtime'])
                {
                    $xtpl->parse('main.baihoc.loop.buybaihoc');
                }
                else
                {
                    if($baihoc['timephathanh'] <= NV_CURRENTTIME)
                    {
                        $xtpl->parse('main.baihoc.loop.vaohoc');
                    }
                    else
                    {
                        $xtpl->parse('main.baihoc.loop.damuabaihoc');
                    }
                }
            }
            else
            {
                $xtpl->parse('main.baihoc.loop.buybaihoc');
            }
            if( !empty( $msystem['icon'] )){
                $xtpl->parse('main.baihoc.loop.money_icon');
            }else{
                $xtpl->parse('main.baihoc.loop.money_text');
            }
            $xtpl->parse('main.baihoc.loop');
        }
        $xtpl->parse('main.baihoc');
    }
    if($news_contents['disablerating'] == 1)
    {
        $xtpl->parse('main.allowed_rating.disablerating');
    }

    if($news_contents['numberrating'] >= $module_config[$module_name]['allowed_rating_point'])
    {
        $xtpl->parse('main.allowed_rating.data_rating');
    }

    $xtpl->parse('main.allowed_rating');

    if(! empty($array_keyword))
    {
        $t = sizeof($array_keyword) - 1;
        foreach($array_keyword as $i => $value)
        {
            $xtpl->assign('KEYWORD', $value['keyword']);
            $xtpl->assign('LINK_KEYWORDS', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=tag/' . urlencode($value['alias']));
            $xtpl->assign('SLASH', ($t == $i) ? '' : ', ');
            $xtpl->parse('main.keywords.loop');
        }
        $xtpl->parse('main.keywords');
    }

    if($module_config[$module_name]['socialbutton'])
    {
        global $meta_property;

        if(! empty($module_config[$module_name]['facebookappid']))
        {
            $meta_property['fb:app_id'] = $module_config[$module_name]['facebookappid'];
            $meta_property['og:locale'] = (NV_LANG_DATA == 'vi') ? 'vi_VN' : 'en_US';
        }
        $module_config['taikhoan']['image'] = $meta_property['og:image'];
        $module_config['taikhoan']['content_transaction'] = 'Share: ' . $news_contents['title'];
        $module_config['taikhoan']['tokenkey'] = md5($user_info['userid'] .$news_contents['id'] . $module_config['taikhoan']['share_facebook'] . '');
        $xtpl->assign('CONFIG_TAIKHOAN', $module_config['taikhoan'] );

        if( isset($module_config['taikhoan']) && $module_config['taikhoan']['money_facebook'] == 1){
            $xtpl->parse('main.socialbutton.money_facebook');
        }else{
            $xtpl->parse('main.socialbutton.no_money_facebook');
        }
        $xtpl->parse('main.socialbutton');
    }
    if(! empty($content_comment))
    {
        $xtpl->assign('CONTENT_COMMENT', $content_comment);
        $xtpl->parse('main.comment');
    }

    if($news_contents['status'] != 1)
    {
        $xtpl->parse('main.no_public');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}
/**
 * studyonline_detail_theme()
 * 
 * @param mixed $news_contents
 * @param mixed $array_keyword
 * @param mixed $content_comment
 * @return
 */

function studyonline_baihoc_theme($news_contents, $video_show, $damua_baihoc, $content_comment, $popup_login)
{
    global $global_config, $module_info, $lang_module, $module_name, $module_config, $lang_global, $user_info, $admin_info, $client_info, $module_file;

    $xtpl = new XTemplate('bai-giang.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG_GLOBAL', $lang_global);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('NV_MY_DOMAIN', NV_MY_DOMAIN);
    $xtpl->assign('MODULE_FILE', $module_file);
    $xtpl->assign('TEMPLATE', $module_info['template']);
    $lang_module['numview_time_user'] = sprintf($lang_module['numview_time_user'],  $news_contents['numviewtime'] - $damua_baihoc['numview'] );
    $xtpl->assign('LANG', $lang_module);
    if($popup_login == 1)
    {
        $xtpl->parse('main.popuplogin');
    }
    $xtpl->assign('SITE_NAME', $global_config['site_name']);
    $xtpl->assign('THEME_SITE_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA);
    $size = @getimagesize(NV_ROOTDIR . '/' . $global_config['site_logo']);
    $logo = preg_replace('/\.[a-z]+$/i', '.svg', $global_config['site_logo']);
    if(! file_exists(NV_ROOTDIR . '/' . $logo))
    {
        $logo = $global_config['site_logo'];
    }
    $xtpl->assign('LOGO_SRC', NV_BASE_SITEURL . $logo);
    $xtpl->assign('LOGO_WIDTH', $size[0]);
    $xtpl->assign('LOGO_HEIGHT', $size[1]);

    if(! empty($news_contents['fileaddtack']) && file_exists(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . '/' . $news_contents['fileaddtack']))
    {
        $news_contents['fileaddtack'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $news_contents['fileaddtack'];
    }
    else
    {
        $news_contents['fileaddtack'] = '';
    }
    $xtpl->assign('NEWSID', $news_contents['id']);
    $xtpl->assign('DETAIL', $news_contents);
    if(! empty($news_contents['fileaddtack']))
    {
        $xtpl->parse('main.fileaddtack');
    }

    $xtpl->assign('SELFURL', $client_info['selfurl']);

    if(! empty($news_contents['list_video']))
    {
        foreach($news_contents['list_video'] as $key => $video_info)
        {
            if(! empty($video_info['video_path']) && (file_exists(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . '/' . $video_info['video_path']) or nv_is_url($video_info['video_path'])))
            {
                $video_info['video_key'] = $key;
                $xtpl->assign('VIDEO_INFO', $video_info);
                $xtpl->parse('main.lesson.loop');
            }
        }
        $xtpl->parse('main.lesson');
    }

    if($module_config[$module_name]['streaming'] == 1)
    {
        $firt_video = $news_contents['list_video'][0];
        if(file_exists(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . '/' . $firt_video['video_path']))
        {
            $tmp = pathinfo(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . '/' . $firt_video['video_path']);
            $firt_video['extension'] = $tmp['extension'];
            $xtpl->assign('VIDEO_INFO', $firt_video);

            $xtpl->assign('server_streaming', $module_config[$module_name]['server_streaming']);
        }
        elseif(preg_match(NV_PREG_URL_YOUTUBE, $firt_video['video_path'], $matches))
        {
            $xtpl->assign('id_video_youtube', $matches[1]);
            $xtpl->parse('main.videoyoutube');
        }
    }
    else
    {
        //video youtube
        if(preg_match(NV_PREG_URL_YOUTUBE, $video_show, $matches))
        {
            $xtpl->assign('id_video_youtube', $matches[1]);
            $xtpl->parse('main.videoyoutube');
        }
        else
        {
            $firt_video = $news_contents['list_video'][0];
            if(file_exists(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . '/' . $firt_video['video_path']))
            {
                $tmp = pathinfo(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . '/' . $firt_video['video_path']);
                $firt_video['extension'] = $tmp['extension'];
                $firt_video['video_path'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $firt_video['video_path'];
                $xtpl->assign('VIDEO_INFO', $firt_video);
                $xtpl->assign('server_streaming', $module_config[$module_name]['server_streaming']);
            }
            $xtpl->assign('video_show', $video_show);
            $xtpl->parse('main.streamingphp');
        }
    }


    if($module_config[$module_name]['socialbutton'])
    {
        global $meta_property;
        if(! empty($module_config[$module_name]['facebookappid']))
        {
            $meta_property['fb:app_id'] = $module_config[$module_name]['facebookappid'];
            $meta_property['og:locale'] = (NV_LANG_DATA == 'vi') ? 'vi_VN' : 'en_US';
        }
        $xtpl->parse('main.socialbutton');
    }
    if(! empty($content_comment))
    {
        $xtpl->assign('CONTENT_COMMENT', $content_comment);
        $xtpl->parse('main.comment');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * no_permission()
 * 
 * @return
 */
function page_error_return($title_response, $description_response, $url_return, $num_error_reponse = 404 )
{
    global $module_info, $lang_module;

    $xtpl = new XTemplate('error_page.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('NV_MY_DOMAIN', NV_MY_DOMAIN);
    $xtpl->assign('MODULE_FILE', $module_file);
    $xtpl->assign('TEMPLATE', $module_info['template']);
    
    $xtpl->assign('title_response', $title_response);
    $xtpl->assign('description_response', $description_response);
    $xtpl->assign('num_error_reponse', $num_error_reponse);
    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * topic_theme()
 * 
 * @param mixed $topic_array
 * @param mixed $topic_other_array
 * @param mixed $generate_page
 * @param mixed $page_title
 * @param mixed $description
 * @param mixed $topic_image
 * @return
 */
function topic_theme($topic_array, $generate_page, $page_title, $description, $topic_image)
{
    global $lang_module, $module_info, $module_name, $topicalias, $module_config, $topicid;

    $xtpl = new XTemplate('topic.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('TOPPIC_TITLE', $page_title);
    $xtpl->assign('IMGWIDTH1', $module_config[$module_name]['homewidth']);
    if (! empty($description)) {
        $xtpl->assign('TOPPIC_DESCRIPTION', $description);
        if (!empty($topic_image)) {
            $xtpl->assign('HOMEIMG1', $topic_image);
            $xtpl->parse('main.topicdescription.image');
        }
        $xtpl->parse('main.topicdescription');
    }
    if (! empty($topic_array)) {
        foreach ($topic_array as $topic_array_i) {
            $xtpl->assign('ROW', $topic_array_i);
            $xtpl->parse('main.topic');
        }
    }

    if (! empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.generate_page');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

function studyonline_history_theme($array_khoahoc, $array_baihoc){
    global $lang_module, $module_info;

    $xtpl = new XTemplate('history.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);

    if (! empty($array_khoahoc)) {
        foreach ($array_khoahoc as $khoahoc) {
            $xtpl->assign('KHOAHOC', $khoahoc);
            $xtpl->parse('main.khoahoc.loop');
        }
        $xtpl->parse('main.khoahoc');
    }

    if (! empty($array_baihoc)) {
        foreach ($array_baihoc as $baihoc) {
            $xtpl->assign('BAIHOC', $baihoc);
            $xtpl->parse('main.baihoc.loop');
        }
        $xtpl->parse('main.baihoc');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}


/**
 * no_permission()
 *
 * @return
 */
function no_permission( $no_permission )
{
    global $module_info;

    $xtpl = new XTemplate('no_permission.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);

    $xtpl->assign('NO_PERMISSION', $no_permission );
    $xtpl->parse('main');
    return $xtpl->text('main');
}