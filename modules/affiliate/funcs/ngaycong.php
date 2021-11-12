<?php

/**
 * @Project NUKEVIET 4.x
 * @Author EDUSGROUP.JSC (thangbv@edus.vn)
 * @Copyright (C) 2014 EDUSGROUP.JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 24 Dec 2014 08:22:22 GMT
 */

if(! defined('NV_IS_MOD_AFFILIATE'))
{
    die('Stop!!!');
}

if($nv_Request->isset_request('submit', 'post'))
{
    if(isset($_FILES['upload_fileupload']) and is_uploaded_file($_FILES['upload_fileupload']['tmp_name']))
    {

        $upload = new NukeViet\Files\Upload($global_config['file_allowed_ext'], $global_config['forbid_extensions'], $global_config['forbid_mimes'], $download_config['maxfilesize'], NV_MAX_WIDTH, NV_MAX_HEIGHT);
        $upload->setLanguage($lang_global);
        $upload_info = $upload->save_file($_FILES['upload_fileupload'], NV_UPLOADS_REAL_DIR . '/' . $module_upload, false);

        @unlink($_FILES['upload_fileupload']['tmp_name']);
        if(empty($upload_info['error']))
        {
            $file_read = $upload_info['name'];
            nv_read_data_from_excel($file_read);
            Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
            die();
        }
        else
        {
            $is_error = true;
            $error = $upload_info['error'];
        }
        unset($upload, $upload_info);
    }
    else
    {
        $error = $lang_module['upload_fileupload_empty'];
    }
}

// Fetch Limit
$show_view = false;
if(! $nv_Request->isset_request('import', 'post,get'))
{

    $array_search['status'] = $array_search['teacher'] = $array_search['type'] = array();
    $array_search['starttime'] = '01/' . date('m/Y', NV_CURRENTTIME);
    $array_search['endtime'] = date('d/m/Y', NV_CURRENTTIME);
    $sql_where = array();

    $array_search['status'] = $nv_Request->get_array('status', 'get', array());
    $array_search['teacher'] = $nv_Request->get_array('teacher', 'get', array());
    $array_search['type'] = $nv_Request->get_array('type', 'get', array());
    $array_search['starttime'] = $nv_Request->get_title('starttime', 'get', $array_search['starttime']);
    $array_search['endtime'] = $nv_Request->get_title('endtime', 'get', $array_search['endtime']);
    if(preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $array_search['starttime'], $m))
    {
        $starttime = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
    }
    else
    {
        $starttime = 0;
    }
    if(preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $array_search['endtime'], $m))
    {
        $endtime = mktime(23, 0, 0, $m[2], $m[1], $m[3]);
    }
    else
    {
        $endtime = 0;
    }

    if(! empty($array_search['status']))
    {
        $sql_where[] = ' status IN (' . implode(',', $array_search['status']) . ')';
    }
    if(! empty($array_search['teacher']))
    {
        $sql_where[] = ' teacherid IN (' . implode(',', $array_search['teacher']) . ')';
    }
    if(! empty($array_search['type']))
    {
        $tmp = array();
        foreach($array_search['type'] as $type)
        {
            if($type == 1)
            {
                $tmp[] = ' dimuon =1';
            }
            elseif($type == 2)
            {
                $tmp[] = ' vesom =1';
            }
        }
        if(count($tmp) == 2)
        {
            $sql_where[] = ' ( ' . implode(' OR ', $tmp) . ')';
        }
        else
        {
            $sql_where[] = implode(' ', $tmp);
        }
    }
    if($starttime > 0 && $endtime > 0)
    {
        $sql_where[] = ' datetime >=' . $starttime . ' AND datetime<=' . $endtime;
    }
    elseif($starttime > 0 && $endtime == 0)
    {
        $sql_where[] = ' datetime >=' . $starttime;
    }
    elseif($starttime == 0 && $endtime > 0)
    {
        $sql_where[] = ' datetime <=' . $endtime;
    }
    //gan phan quyen vao day
    /*
    if(!nv_user_in_groups($module_config[$module_name]['nhansu_group_edit_ngaycong']))
    {
        $sql_where[] = ' teacherid =' . $user_info['userid'];
    }
    */
    if( !defined('NV_IS_ADMIN') ){
        $list_id = nvGetUseridInParent($user_data_affiliate['userid'], $user_data_affiliate['subcatid'], $checksub = true, $ispossiton = true);
        if( !empty( $list_id )){
            $sql_where[] = ' teacherid IN (' . implode(',' , $list_id ) . ')';
        }else{
            $sql_where[] = ' teacherid =' . $user_info['userid'];
        }
    }


    $show_view = true;
    $page = $nv_Request->get_int('page', 'post,get', 1);
    $db->sqlreset()->select('COUNT(*)')->from('' . NV_PREFIXLANG . '_' . $module_data . '_chamcong');
    if(! empty($sql_where))
    {
        $db->where(implode(' AND ', $sql_where));
    }
    $sth = $db->prepare($db->sql());
    $sth->execute();
    $num_items = $sth->fetchColumn();

    $db->select('*')->order('teacherid ASC, datetime ASC');
    $sth = $db->prepare($db->sql());
    $sth->execute();
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('TEMPLATE', $module_info['template']);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('ROW', $row);
$xtpl->assign('DATA_SEARCH', $array_search);
$xtpl->assign('import_ngaycong', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;import=1');
$xtpl->assign('tonghop_ngaycong', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=tinhcong');
if($show_view)
{
    $edit_status = $edit_ngaycong = $edit_phep = 0;
    //gan phan quyen vao day
    if(nv_user_in_groups($module_config[$module_name]['nhansu_group_edit_ngaycong']))
    {
        $edit_status = $edit_ngaycong = $edit_phep = 1;
        $xtpl->parse('main.view.import_ngaycong');
    }
    $array_info_chamcong = array();
    while($view = $sth->fetch())
    {
        $view['teacher'] = $list_userdata[$view['teacherid']];
        $view['status_checked'] = ($view['status'] == 1) ? ' checked=checked' : '';
        $view['status_text'] = $lang_module['chamcong_status_' . $view['status']];
        $view['datetime_key'] = $view['datetime'];
        $view['datetime'] = date('d/m/Y', $view['datetime']);
        $view['dimuoncophep'] = ($view['dimuoncophep'] == 1) ? ' checked=checked' : '';
        $view['vesomcophep'] = ($view['vesomcophep'] == 1) ? ' checked=checked' : '';
        $view['link_edit'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $view['id'];
        $view['infocheck'] = unserialize( $view['infocheck'] );
        $view['infocheck']['giovao_1'] = implode(', ', $view['infocheck']['giovao'] );
        $view['infocheck']['giora_1'] = implode(', ', $view['infocheck']['giora'] );
        $view['total_row'] = count($view['infocheck']['giovao']);
        $view['total_row'] = ( $view['total_row'] == 0 )?  1 : $view['total_row'];
        $array_info_chamcong[$view['teacherid']][$view['datetime_key']] =$view;
    }

    foreach($list_userdata as $userdata)
    {
        if( empty( $array_search['teacher'] ) || (!empty( $array_search['teacher'] ) && in_array($userdata['userid'], $array_search['teacher'] ))){
            if( empty( $list_id ) || (!empty( $list_id ) && in_array( $userdata['userid'], $list_id ))){
                $i = $starttime;
                while ( $i <= $endtime ){
                    $view = array();
                    if( isset( $array_info_chamcong[$userdata['userid']][$i] )){
                        $view = $array_info_chamcong[$userdata['userid']][$i];
                    }else{
                        $view['id'] = 0;
                        $view['teacher'] = $userdata;
                        $view['teacherid'] = $userdata['userid'];
                        $view['dimuon'] = 0;
                        $view['vesom'] = 0;
                        $view['datetime_key'] = $i;
                        $view['datetime'] = date('d/m/Y', $i);
                        $view['infocheck']['giovao'][] = '';
                        $view['infocheck']['giora'][] = '';
                    }
                    $xtpl->assign('VIEW', $view);

                    //chua lam phan quyen
                    if($view['dimuon'] == 1 && $edit_phep == 1)
                    {
                        $xtpl->parse('main.view.loop.check_info1.dimuon');
                    }

                    if($view['vesom'] == 1 && $edit_phep == 1)
                    {
                        $xtpl->parse('main.view.loop.check_info1.vesom');
                    }
                    if($edit_status == 1)
                    {
                        $xtpl->parse('main.view.loop.allow_status');
                    }
                    else
                    {
                        $xtpl->parse('main.view.loop.noallow_status');
                    }
                    if($edit_ngaycong == 1)
                    {
                        $xtpl->parse('main.view.loop.allow');
                    }
                    else
                    {
                        $xtpl->parse('main.view.loop.notallow');
                    }
                    if(! empty($view['note']))
                    {
                        $xtpl->parse('main.view.loop.note');
                    }
                    if( !empty( $view['infocheck']['giovao'] )){
                        foreach ($view['infocheck']['giovao'] as $key => $check_info )
                        {
                            $xtpl->assign('CHECK_INFO_OUT', $view['infocheck']['giora'][$key]);
                            $xtpl->assign('CHECK_INFO_IN', $view['infocheck']['giovao'][$key]);
                            if($key > 0){
                                $xtpl->parse('main.view.loop.check_info2');
                            }else{
                                $xtpl->parse('main.view.loop.check_info1');
                            }
                        }
                    }else{
                        $xtpl->assign('CHECK_INFO_OUT', '');
                        $xtpl->assign('CHECK_INFO_IN', '');
                        $xtpl->parse('main.view.loop.check_info1');
                    }

                    $xtpl->parse('main.view.loop');
                    $i = $i + 86400;
                }
            }
        }

    }

    $array_status = array(
        0 => $lang_module['chamcong_status_0'],
        1 => $lang_module['chamcong_status_1'],
        );
    foreach($array_status as $key => $status)
    {
        $sl = in_array($key, $array_search['status']) ? ' selected=selected' : '';
        $xtpl->assign('STATUS', array(
            'sl' => $sl,
            'key' => $key,
            'title' => $status));
        $xtpl->parse('main.view.status');
    }
    $array_type = array(
        1 => $lang_module['type_1'],
        2 => $lang_module['type_2'],
        );
    foreach($array_type as $key => $type)
    {
        $sl = in_array($key, $array_search['type']) ? ' selected=selected' : '';
        $xtpl->assign('TYPE', array(
            'sl' => $sl,
            'key' => $key,
            'title' => $type));
        $xtpl->parse('main.view.type');
    }
    foreach($list_userdata as $userdata)
    {
        if( empty( $list_id ) || (!empty( $list_id ) && in_array( $userdata['userid'], $list_id ))){
            $userdata['full_name'] = nv_show_name_user( $userdata['first_name'], $userdata['last_name'], $userdata['username'] );
            $userdata['sl'] = in_array($userdata['userid'], $array_search['teacher']) ? ' selected=selected' : '';
            $xtpl->assign('TEACHER', $userdata);
            $xtpl->parse('main.view.teacher');
        }
    }

    $xtpl->parse('main.view');
}
else
{
    if(! empty($error))
    {
        $xtpl->assign('ERROR', $error);
        $xtpl->parse('main.addnew.error');
    }
    if(! file_exists(NV_ROOTDIR . '/includes/plugin/PHPExcel.php'))
    {
        $xtpl->parse('main.addnew.importexcel');
    }
    $allow_upload = 'xls, xlsx';
    $xtpl->assign('EXT_ALLOWED', $allow_upload);
    $xtpl->parse('main.addnew');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['ngaycong'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
