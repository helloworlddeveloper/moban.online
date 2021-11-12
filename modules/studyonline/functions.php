<?php

/**
 * @Project NUKEVIET 4.x
 * @Author EDUSGROUP.JSC (thangbv@edus.vn)
 * @Copyright (C) 2014 EDUSGROUP.JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 14 Mar 2017 10:56:01 GMT
 */

if(! defined('NV_SYSTEM'))
    die('Stop!!!');

require_once NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

$classid = 0;
$alias_cat_url = isset($array_op[0]) ? $array_op[0] : '';
foreach($array_class as $class)
{
    if($alias_cat_url == $class['alias'])
    {
        $classid = $class['id'];
    }
}
define('NV_IS_GROUP_SUPPORT', 13); //id nhom ho tro hoc sinh
define('NV_PREG_URL_YOUTUBE', "/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/");
define('NV_IS_MOD_STUDYONLINE', true);

if(!defined('NV_IS_USER') )
{
    $redirect = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;
    Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=login&nv_redirect=' . nv_redirect_encrypt($redirect));
    die();
}

// module taikhoan
$taikhoan_array_money = array();
$taikhoan_module_name = 'taikhoan';
if(defined('NV_IS_USER') && $site_mods[$taikhoan_module_name])
{
    $taikhoan_module_data = $site_mods[$taikhoan_module_name]['module_data'];

    $_sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $taikhoan_module_data . '_money WHERE status=1 AND userid=' . $user_info['userid'];
    $taikhoan_array_money = $db->query($_sql)->fetch();
    if(! empty($taikhoan_array_money))
    {
        define('NV_TAIKHOAN', true);
    }
}
$msystem = array();
if( isset($site_mods[$taikhoan_module_name])){

    $sql = 'SELECT id, mcountry, symbol_inter, symbol, icon FROM ' . $db_config['prefix'] . '_' . $taikhoan_module_name . '_msystem WHERE status = 1';
    $array_msystem = $nv_Cache->db($sql, 'id', $taikhoan_module_name);
    $msystem = $array_msystem[$module_config['taikhoan']['msystem_default']];
    $msystem['icon'] = !empty( $msystem['icon'] )? NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $taikhoan_module_name . '/' . $msystem['icon'] : '';

}

$show_no_image = $module_config[$module_name]['show_no_image'];
$page = 1;
$per_page = $module_config[$module_name]['per_page'];
$count_op = sizeof($array_op);
if(! empty($array_op) and $op == 'main')
{
    $op = 'main';
    if($count_op == 1 or substr($array_op[1], 0, 5) == 'page-')
    {
        if($count_op > 1 or $classid > 0)
        {
            $op = 'viewcat';
            if(isset($array_op[1]) and substr($array_op[1], 0, 5) == 'page-')
            {
                $page = intval(substr($array_op[1], 5));
            }
        }
        elseif($classid == 0)
        {
            if(isset($array_op[0]) and substr($array_op[0], 0, 5) == 'page-')
            {
                $page = intval(substr($array_op[0], 5));
            }
        }
    }
    elseif($count_op == 2)
    {
        $array_page = explode('-', $array_op[1]);
        $id = intval(end($array_page));
        $number = strlen($id) + 1;
        $alias_url = substr($array_op[1], 0, -$number);
        if($id > 0 and $alias_url != '')
        {
            if($classid > 0)
            {
                $op = 'detail';
            }
            else
            {
                //muc tieu neu xoa chuyen muc cu hoac doi ten alias chuyen muc thi van rewrite duoc bai viet
                $_row = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_khoahoc WHERE id = ' . $id)->fetch();
                if(! empty($_row) and isset($array_class[$_row['classid']]))
                {
                    $url_Permanently = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $array_class[$_row['classid']]['alias'] . '/' . $_row['alias'] . '-' . $_row['id'] . $global_config['rewrite_exturl'], true);
                    header("HTTP/1.1 301 Moved Permanently");
                    header('Location:' . $url_Permanently);
                    exit();
                }
            }
        }
        elseif($classid > 0)
        {
            $subjectid = 0;
            $alias_cat_url = isset($array_op[1]) ? $array_op[1] : '';
            foreach($array_subject as $subject)
            {
                if($alias_cat_url == $subject['alias'])
                {
                    $subjectid = $subject['id'];
                }
            }
            if($subjectid > 0)
            {
                $op = 'mon';
            }
        }
    }
    elseif($count_op == 3)
    {
        $array_page = explode('-', $array_op[1]);
        $id = intval(end($array_page));
        $number = strlen($id) + 1;
        $alias_url = substr($array_op[1], 0, -$number);
        if($id > 0 and $alias_url != '')
        {
            $array_page = explode('-', $array_op[2]);
            $id_baihoc = intval(end($array_page));
            $number = strlen($id) + 1;
            $alias_url = substr($array_op[2], 0, -$number);

            if($classid > 0)
            {
                $op = 'xembaigiang';
            }
            else
            {
                //muc tieu neu xoa chuyen muc cu hoac doi ten alias chuyen muc thi van rewrite duoc bai viet
                $_row = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_khoahoc WHERE id = ' . $id)->fetch();
                if(! empty($_row) and isset($array_class[$_row['classid']]))
                {
                    if($id_baihoc > 0 and $alias_url != '')
                    {
                        $_row_i = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_baihoc WHERE id = ' . $id_baihoc)->fetch();
                        if(! empty($_row_i))
                        {
                            $url_Permanently = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $array_class[$_row['classid']]['alias'] . '/' . $_row['alias'] . '-' . $_row['id'] . '/' . $_row_i['alias'] . '-' . $_row_i['id'] . $global_config['rewrite_exturl'], true);
                            header("HTTP/1.1 301 Moved Permanently");
                            header('Location:' . $url_Permanently);
                            exit();
                        }
                    }
                    $url_Permanently = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $array_class[$_row['classid']]['alias'] . '/' . $_row['alias'] . '-' . $_row['id'] . $global_config['rewrite_exturl'], true);
                    header("HTTP/1.1 301 Moved Permanently");
                    header('Location:' . $url_Permanently);
                    exit();
                }
            }
        }
        $op = 'xembaigiang';
    }
    if(isset($array_class[$classid]))
    {
        $array_cat_i = $array_class[$classid];
        $array_mod_title[] = array(
            'catid' => $classid,
            'title' => $array_cat_i['title'],
            'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $array_cat_i['alias']);
        krsort($array_mod_title, SORT_NUMERIC);
    }

}

function nv_buy_action($module_send, $khoahocid, $baihocid, $contentid, $istype, $money, $pricebefor, $priceafter, $notice_transaction)
{

    global $module_data, $user_info, $db;
    require (NV_ROOTDIR . "/modules/taikhoan/check.transaction.class.php");

    $tk_check = new TK_check_transaction();
    $money_unit = NV_IS_MONEY_UNIT;
    $array_return = $tk_check->save_transaction($contentid, $notice_transaction, $module_send, $money, $money_unit);
    if($array_return['status'] == 200)
    {
        //cap nhat luot mua
        if($istype == 1)
        {
            $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_baihoc SET numbuy=numbuy+1 WHERE id=' . $baihocid);

            //ghi vao bang luot xem
            $query = "INSERT INTO " . NV_PREFIXLANG . '_' . $module_data . "_viewhistory VALUES (" . $user_info['userid'] . "," . $khoahocid . "," . $baihocid . ",0,0,0);";
            $db->query($query);
        }
        else
        {
            $_query = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_baihoc WHERE status=1 AND khoahocid=' . $khoahocid);
            while($row = $_query->fetch())
            {
                //ghi vao bang luot xem
                $db->query("INSERT INTO " . NV_PREFIXLANG . '_' . $module_data . "_viewhistory VALUES (" . $user_info['userid'] . "," . $khoahocid . "," . $row['id'] . ",0,0,0);");
            }
            $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_khoahoc SET numbuy=numbuy+1 WHERE id=' . $khoahocid);
        }

        //luu log o module
        $query = "INSERT INTO " . NV_PREFIXLANG . '_' . $module_data . "_buyhistory VALUES (NULL," . $user_info['userid'] . "," . $istype . "," . $contentid . "," . NV_CURRENTTIME . "," . $pricebefor . "," . $priceafter . ", 0);";
        $db->query($query);
    }
    return $array_return;
}
