<?php

/**
 * @Project NUKEVIET 4.x
 * @Author EDUSGROUP.JSC (thangbv@edus.vn)
 * @Copyright (C) 2015 EDUSGROUP.JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 03 Mar 2015 14:33:58 GMT
 */

if(! defined('NV_IS_MOD_STUDYONLINE'))
{
    die('Stop!!!');
}

$contents = '';
$publtime = 0;

$array_page = explode('-', $array_op[1]);
$id = intval(end($array_page));
$number = strlen($id) + 1;
$alias_url = substr($array_op[1], 0, -$number);
if($id == 0 or $alias_url == '')
{
    $base_url_rewrite = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true);
    header('HTTP/1.1 301 Moved Permanently');
    Header('Location: ' . $base_url_rewrite);
    die();
}

$query = $db_slave->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_baihoc WHERE id = ' . $id);
$news_contents = $query->fetch();

if( empty( $news_contents )){
    $base_url_rewrite = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true);
    header('HTTP/1.1 301 Moved Permanently');
    Header('Location: ' . $base_url_rewrite);
    die();
}

$query = $db_slave->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_khoahoc WHERE id = ' . $news_contents['khoahocid']);
$khoahoc_contents = $query->fetch();
if( $khoahoc_contents['requirewatch'] > 0 ){
    $query = $db_slave->query('SELECT * FROM ' . NV_TABLE_AFFILIATE . '_users WHERE userid = ' . $user_info['userid']);
    $user_contents = $query->fetch();
    if(empty( $user_contents ) || $user_contents['numsubcat'] < $khoahoc_contents['requirewatch'] ){
        $no_permission = sprintf( $lang_module['no_permission'], $khoahoc_contents['requirewatch']);
        $contents = no_permission( $no_permission );
        include NV_ROOTDIR . '/includes/header.php';
        echo nv_site_theme($contents);
        include NV_ROOTDIR . '/includes/footer.php';
        exit;
    }
}

$numview_guest = 0;
if($nv_Request->isset_request($module_data . '_numview_guest', 'session'))
{
    $numview_guest = $nv_Request->get_int($module_data . '_numview_guest', 'session');
}
$popup_login = 0;

//kiem tra xem het quyen xem ma k can dang nhap chua?
if(! defined('NV_IS_USER') && $numview_guest > $module_config[$module_name]['numview_guest'])
{
    $popup_login = 1;
}

if($news_contents['id'] > 0)
{
    $page_title = empty($news_contents['titleseo']) ? $news_contents['title'] : $news_contents['titleseo'];
    $description = $news_contents['description'];
    
    if($news_contents['price'] > 0)
    {
        //kiem tra xem da mua bai giang chua

        $_query = $db_slave->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_viewhistory WHERE numview<= ' . $news_contents['numviewtime'] . ' AND userid=' . $user_info['userid'] . ' AND baihocid=' . $news_contents['id']);
        $damua_baihoc = $_query->fetch();
        //neu chua mua bai hoc hoac het luot xemse chuyen huong
        if(empty( $damua_baihoc ))
        {
            $title_response = sprintf($lang_module['reponse_chuathanhtoan_title'], $news_contents['title']);
            $description_response = sprintf($lang_module['reponse_chuathanhtoan_description'], number_format($news_contents['price'], 0, ',', '.') . '&nbsp;' . NV_IS_MONEY_UNIT);
            $num_error_reponse = 402;
            $url_return = ! empty($client_info['referer']) ? $client_info['referer'] : nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true);
            header("Refresh: 10; url=" . $url_return);
            $contents = page_error_return($title_response, $description_response, $url_return, $num_error_reponse);
            include NV_ROOTDIR . '/includes/header.php';
            echo nv_site_theme($contents, false);
            include NV_ROOTDIR . '/includes/footer.php';
            exit;
        }
    }
    if($news_contents['timephathanh'] > NV_CURRENTTIME)
    {
        $title_response = sprintf($lang_module['reponse_chuaphathanh_title'], $news_contents['title']);
        $description_response = sprintf($lang_module['reponse_chuaphathanh_description'], date('d/m/Y H:i', $news_contents['timephathanh']));
        $num_error_reponse = 503;
        $url_return = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true);
        header("Refresh: 10; url=" . $url_return);
        $contents = page_error_return($title_response, $description_response, $url_return, $num_error_reponse);
        include NV_ROOTDIR . '/includes/header.php';
        echo nv_site_theme($contents, false);
        include NV_ROOTDIR . '/includes/footer.php';
        exit;
    }
    $base_url_rewrite = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=bai-giang/' . $news_contents['alias'] . '-' . $news_contents['id'] . $global_config['rewrite_exturl'], true);
    if($_SERVER['REQUEST_URI'] == $base_url_rewrite)
    {
        $canonicalUrl = NV_MAIN_DOMAIN . $base_url_rewrite;
    }
    elseif(NV_MAIN_DOMAIN . $_SERVER['REQUEST_URI'] != $base_url_rewrite)
    {
        //chuyen huong neu doi alias
        header('HTTP/1.1 301 Moved Permanently');
        Header('Location: ' . $base_url_rewrite);
        die();
    }
    else
    {
        $canonicalUrl = $base_url_rewrite;
    }
    $canonicalUrl = str_replace('&', '&amp;', $canonicalUrl);

    $time_set = $nv_Request->get_int($module_data . '_' . str_replace('-', '_', $op ) . '_' . $id, 'session');
    if(empty($time_set))
    {
        //luot xem cua khach chua dang nhap
        $nv_Request->set_Session($module_data . '_numview_guest', $numview_guest + 1);
        
        $nv_Request->set_Session($module_data . '_' . str_replace('-', '_', $op ) . '_' . $id, NV_CURRENTTIME);
        $db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_baihoc SET numview=numview+1 WHERE id=' . $id);
        if( defined('NV_IS_USER') ){
         $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_viewhistory SET numview=numview+1,  timeupdate= ' . NV_CURRENTTIME . ' WHERE userid=' . $user_info['userid'] . ' AND baihocid=' . $news_contents['id'] );   
        }
    }
    if(! empty($news_contents['image']))
    {
        $src = $alt = $note = '';
        $width = $height = 0;
        $src = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $news_contents['image'];
        $news_contents['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $news_contents['image'];
        $width = $module_config[$module_name]['homewidth'];

        if(! empty($src))
        {
            $meta_property['og:image'] = (preg_match('/^(http|https|ftp|gopher)\:\/\//', $src)) ? $src : NV_MY_DOMAIN . $src;
        }
        elseif(! empty($show_no_image))
        {
            $meta_property['og:image'] = NV_MY_DOMAIN . NV_BASE_SITEURL . $show_no_image;
        }
    }
    elseif(! empty($show_no_image))
    {
        $meta_property['og:image'] = NV_MY_DOMAIN . NV_BASE_SITEURL . $show_no_image;
    }

    $publtime = intval($news_contents['timephathanh']);
    $meta_property['og:type'] = 'article';
    $meta_property['article:published_time'] = date('Y-m-dTH:i:s', $publtime);
    $meta_property['article:modified_time'] = date('Y-m-dTH:i:s', $news_contents['addtime']);

    //print_r($news_contents);die;
}

// comment
if(isset($site_mods['comment']) and isset($module_config[$module_name]['activecomm']))
{
    define('NV_COMM_ID', $id); //ID b�i viet
    define('NV_COMM_AREA', $module_info['funcs'][$op]['func_id']);
    //check allow comemnt
    $allowed = $module_config[$module_name]['allowed_comm'];
    if($allowed == '-1')
    {
        $allowed = 4;
    }
    require_once NV_ROOTDIR . '/modules/comment/comment.php';
    $area = (defined('NV_COMM_AREA')) ? NV_COMM_AREA : 0;
    $checkss = md5($module_name . '-' . $area . '-' . NV_COMM_ID . '-' . $allowed . '-' . NV_CACHE_PREFIX);

    $content_comment = nv_comment_module($module_name, $checkss, $area, NV_COMM_ID, $allowed, 1);
}
else
{
    $content_comment = '';
}
$news_contents['list_video'] = unserialize($news_contents['list_video']);
$video_show = '';
if($module_config[$module_name]['streaming'] == 0)
{
    foreach($news_contents['list_video'] as $key => $video_info)
    {
        if($key == 0 && ! empty($video_info['video_path']))
        {
            if(file_exists(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . '/' . $video_info['video_path']))
            {
                $userid_view = (isset($user_info)) ? $user_info['userid'] : 0;
                $file_name = md5($news_contents['id'] . '_' . $userid_view . '_' . NV_CURRENTTIME);

                $content_baigiang = array(
                    'numview' => 0,
                    'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                    'path_file' => NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $video_info['video_path'],
                    'sesionid' => NV_CHECK_SESSION);

                file_put_contents(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $module_name . '_' . $file_name, serialize($content_baigiang));
                $video_show = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=view/' . $file_name, true);
            }
            elseif(nv_is_url($video_info['video_path']))
            {
                $video_show = $video_info['video_path'];
            }
        }
    }
}

$contents = studyonline_baihoc_theme($news_contents, $video_show, $damua_baihoc, $content_comment, $popup_login);

$key_words = array();
$_query = $db_slave->query('SELECT a1.keyword, a2.alias FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id a1 INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_tags a2 ON a1.tid=a2.tid WHERE a1.id=' . $news_contents['khoahocid']);
while($row = $_query->fetch())
{
    $key_words[] = $row['keyword'];
    $meta_property['article:tag'][] = $row['keyword'];
}
$key_words = implode(', ', $key_words );
include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
