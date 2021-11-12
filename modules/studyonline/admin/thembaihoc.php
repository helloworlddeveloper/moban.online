<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2015 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Fri, 20 Mar 2015 02:51:05 GMT
 */

if(! defined('NV_IS_FILE_ADMIN'))
    die('Stop!!!');

if($nv_Request->isset_request('get_alias_title', 'post'))
{
    $alias = $nv_Request->get_title('get_alias_title', 'post', '');
    $alias = change_alias($alias);
    die($alias);
}
$row = array();
$error = array();
$row['id'] = $nv_Request->get_int('id', 'post,get', 0);
$row['khoahocid'] = $nv_Request->get_int('khoahocid', 'post,get', 0);
$row['numviewtime'] = 10;
$db->sqlreset()->select('*')->from(NV_PREFIXLANG . '_' . $module_data . '_khoahoc')->where('id=' . $row['khoahocid']);
$data_khoahoc = $db->query($db->sql())->fetch();
if(empty($data_khoahoc))
{
    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=khoahoc');
    die();
}
$page_title = $lang_module['thembaihocchokhoahoc'] . ': ' . $data_khoahoc['title'];
$dir_khoahoc = $module_upload . '/khoahoc/khoa' . $data_khoahoc['id'];
if(file_exists(NV_UPLOADS_REAL_DIR . '/' . $dir_khoahoc))
{
    $upload_real_dir_page = NV_UPLOADS_REAL_DIR . '/' . $dir_khoahoc;
}
else
{
    $upload_real_dir_page = NV_UPLOADS_REAL_DIR . '/' . $module_upload;
    $e = explode('/', $dir_khoahoc);
    if(! empty($e))
    {
        $cp = '';
        foreach($e as $p)
        {
            if(! empty($p) and ! is_dir(NV_UPLOADS_REAL_DIR . '/' . $cp . $p))
            {
                $mk = nv_mkdir(NV_UPLOADS_REAL_DIR . '/' . $cp, $p);
                if($mk[0] > 0)
                {
                    $upload_real_dir_page = $mk[2];
                    try
                    {
                        $db->query("INSERT INTO " . NV_UPLOAD_GLOBALTABLE . "_dir (dirname, time) VALUES ('" . NV_UPLOADS_DIR . "/" . $cp . $p . "', 0)");
                    }
                    catch (PDOException $e)
                    {
                        trigger_error($e->getMessage());
                    }
                }
            }
            elseif(! empty($p))
            {
                $upload_real_dir_page = NV_UPLOADS_REAL_DIR . '/' . $cp . $p;
            }
            $cp .= $p . '/';
        }
    }
    $upload_real_dir_page = str_replace('\\', '/', $upload_real_dir_page);
}
$currentpath = str_replace(NV_ROOTDIR . '/', '', $upload_real_dir_page);

if($nv_Request->isset_request('submit', 'post'))
{
    $row['title'] = $nv_Request->get_title('title', 'post', '');
    $row['image'] = $nv_Request->get_title('image', 'post', '');
    $row['fileaddtack'] = $nv_Request->get_title('fileaddtack', 'post', '');
    $row['description'] = $nv_Request->get_editor('description', '', NV_ALLOWED_HTML_TAGS);
    $row['titleseo'] = $nv_Request->get_title('titleseo', 'post', '');
    if($row['titleseo'] == '')
    {
        $row['titleseo'] = $row['title'];
    }
    $row['alias'] = $nv_Request->get_title('alias', 'post', '');
    $row['alias'] = (empty($row['alias'])) ? change_alias($row['titleseo']) : change_alias($row['alias']);
    $row['numviewtime'] = $nv_Request->get_int('numviewtime', 'post', 0);
    $row['price'] = $nv_Request->get_float('price', 'post', 0);
    $row['timephathanh'] = $nv_Request->get_title('timephathanh', 'post', '');
    $row['video_title'] = $nv_Request->get_array('video_title', 'post');
    $row['video_path'] = $nv_Request->get_array('video_path', 'post');
    $row['status'] = $nv_Request->get_int('status', 'post', 0);
    $row['timeamount'] = $nv_Request->get_int('timeamount', 'post', 0);
    $row['list_video'] = array();
    foreach($row['video_title'] as $key => $title_video)
    {
        $video_path = $row['video_path'][$key];
        if(! empty($title_video) && nv_is_url($video_path))
        {
            $row['list_video'][] = array('video_title' => $title_video, 'video_path' => $video_path);
        }
        elseif(! empty($title_video) && is_file(NV_DOCUMENT_ROOT . $video_path))
        {
            $video_path = substr($video_path, strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/'));
            $row['list_video'][] = array('video_title' => $title_video, 'video_path' => $video_path);
        }
    }
    $row['list_video'] = serialize($row['list_video']);

    if(is_file(NV_DOCUMENT_ROOT . $row['image']))
    {
        $row['image'] = substr($row['image'], strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/'));
    }
    else
    {
        $row['image'] = '';
    }
    if(is_file(NV_DOCUMENT_ROOT . $row['fileaddtack']))
    {
        $row['fileaddtack'] = substr($row['fileaddtack'], strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/'));
    }
    else
    {
        $row['fileaddtack'] = '';
    }

    if(empty($row['title']))
    {
        $error[] = $lang_module['error_required_baihoc_name'];
    }
    if(empty($row['description']))
    {
        $error[] = $lang_module['error_required_baihoc_description'];
    }
    if($row['numviewtime'] == 0)
	{
		$error[] = $lang_module['error_required_numviewtime'];
	}
    if(preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string('timephathanh', 'post'), $m))
    {
        $ehour = $nv_Request->get_int('ehour', 'post', 0);
        $emin = $nv_Request->get_int('emin', 'post', 0);
        
        $nv_Request->set_Session('ehour', $ehour);
        $nv_Request->set_Session('emin', $emin);
        
        $row['timephathanh'] = mktime($ehour, $emin, 0, $m[2], $m[1], $m[3]);
    }
    else
    {
        $error[] = $lang_module['error_required_timephathanh'];
    }
    if(empty($row['list_video']))
    {
        $error[] = $lang_module['error_required_list_video'];
    }
    if(empty($error))
    {
        try
        {
            if($row['id'] == 0)
            {
                $weight = $db->query('SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_baihoc WHERE khoahocid=' . $row['khoahocid'])->fetchColumn();
                $weight = intval($weight) + 1;

                $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_baihoc 
                (khoahocid, title, image, titleseo, alias, description, list_video, fileaddtack, timeamount, numviewtime, price, timephathanh, numview, numlike, numbuy, weight, status, addtime) 
                VALUES ( ' . intval($row['khoahocid']) . ', ' . $db->quote($row['title']) . ', ' . $db->quote($row['image']) . ', ' . $db->quote($row['titleseo']) . ', ' . $db->quote($row['alias']) . ', ' . $db->quote($row['description']) . ',
                ' . $db->quote($row['list_video']) . ', ' . $db->quote($row['fileaddtack']) . ', ' . $row['timeamount'] . ',  ' . $row['numviewtime'] . ', ' . floatval($row['price']) . ', ' . $row['timephathanh'] . ',0,0,0, ' . $weight . ', ' . $row['status'] . ', ' . NV_CURRENTTIME . ')';
                $row['id'] = $db->insert_id($sql);
                if($row['id'] > 0)
                {
                    $nv_Cache->delMod($module_name);
                    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=baihoc&khoahocid=' . $row['khoahocid']);
                    die();
                }
            }
            else
            {
                $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_baihoc SET 
                title=:title, image=:image, titleseo=:titleseo, alias=:alias, description=:description, list_video=:list_video, fileaddtack=:fileaddtack, timeamount=:timeamount, numviewtime=:numviewtime, price=:price, timephathanh=:timephathanh, status=:status WHERE id=' . $row['id']);
                $stmt->bindParam(':title', $row['title'], PDO::PARAM_STR);
                $stmt->bindParam(':image', $row['image'], PDO::PARAM_STR);
                $stmt->bindParam(':titleseo', $row['titleseo'], PDO::PARAM_STR);
                $stmt->bindParam(':alias', $row['alias'], PDO::PARAM_STR);
                $stmt->bindParam(':description', $row['description'], PDO::PARAM_STR, strlen($row['description']));
                $stmt->bindParam(':list_video', $row['list_video'], PDO::PARAM_STR, strlen($row['list_video']));
                $stmt->bindParam(':fileaddtack', $row['fileaddtack'], PDO::PARAM_STR);
                $stmt->bindParam(':timeamount', $row['timeamount'], PDO::PARAM_INT);
                $stmt->bindParam(':numviewtime', $row['numviewtime'], PDO::PARAM_INT);
                $stmt->bindParam(':price', $row['price'], PDO::PARAM_INT);
                $stmt->bindParam(':timephathanh', $row['timephathanh'], PDO::PARAM_INT);
                $stmt->bindParam(':status', $row['status'], PDO::PARAM_INT);
                $exc = $stmt->execute();
                if($exc)
                {
                    $nv_Cache->delMod($module_name);
                    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=baihoc&khoahocid=' . $row['khoahocid']);
                    die();
                }
            }

        }
        catch (PDOException $e)
        {
            $error[] = $e->getMessage();
        }
    }
}
elseif($row['id'] > 0)
{
    $row = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_baihoc WHERE id=' . $row['id'])->fetch();
    if(empty($row))
    {
        Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&khoahocid=' . $row['khoahocid']);
        die();
    }
    $page_title = $lang_module['edit_baihoc'] . ' ' . $row['title'];
}
else
{

    $row['id'] = $row['timephathanh'] = 0;
    $row['status'] = 1;
    $row['list_video'] = serialize(array(0 => array('video_title' => '', 'video_path' => '')));
}

if(! empty($row['image']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $row['image']))
{
    $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $row['image'];
}
if(! empty($row['fileaddtack']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $row['fileaddtack']))
{
    $row['fileaddtack'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $row['fileaddtack'];
}

if($row['timephathanh'] == 0)
{
    $ehour = $nv_Request->get_int('ehour', 'session', 0);
    $emin = $nv_Request->get_int('emin', 'session', 0);
    $row['timephathanh'] = '';
}
else
{
    $tdate = date('H|i', $row['timephathanh']);
    list($ehour, $emin) = explode('|', $tdate);
    $row['timephathanh'] = date('d/m/Y', $row['timephathanh']);
}

$row['list_video'] = unserialize($row['list_video']);

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('module_file', $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('ROW', $row);
$xtpl->assign('currentpath', $currentpath);
if(! empty($error))
{
    $xtpl->assign('ERROR', implode('<br />', $error));
    $xtpl->parse('main.error');
}

$select = '';
for ($i = 0; $i <= 23; ++$i) {
    $select .= "<option value=\"" . $i . "\"" . (($i == $ehour) ? ' selected="selected"' : '') . ">" . str_pad($i, 2, "0", STR_PAD_LEFT) . "</option>\n";
}
$xtpl->assign('ehour', $select);
$select = '';
for ($i = 0; $i < 60; $i+=15) {
    $select .= "<option value=\"" . $i . "\"" . (($i == $emin) ? ' selected="selected"' : '') . ">" . str_pad($i, 2, "0", STR_PAD_LEFT) . "</option>\n";
}
$xtpl->assign('emin', $select);

$items = 1;
foreach($row['list_video'] as $video_info)
{
    if(! empty($video_info['video_path']))
    {
        if(is_file(NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $video_info['video_path']))
        {
            $video_info['video_path'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $video_info['video_path'];
            $video_info['stt'] = $items;
            $xtpl->assign('ITEM', $video_info);
            $xtpl->parse('main.itemvideo');
            $items++;
        }
        elseif(nv_is_url($video_info['video_path']))
        {
            $video_info['stt'] = $items;
            $xtpl->assign('ITEM', $video_info);
            $xtpl->parse('main.itemvideo');
            $items++;
        }
    }
    $xtpl->assign('NEW_ITEM_NUM', $items);
}
$array_select_status = array();
$array_select_status[0] = $lang_module['status_0'];
$array_select_status[1] = $lang_module['status_1'];
foreach($array_select_status as $key => $title)
{
    $xtpl->assign('OPTION', array(
        'key' => $key,
        'title' => $title,
        'selected' => ($key == $row['status']) ? ' selected="selected"' : ''));
    $xtpl->parse('main.select_status');
}

if(empty($row['id']))
{
    $xtpl->parse('main.auto_get_alias');
}
$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
