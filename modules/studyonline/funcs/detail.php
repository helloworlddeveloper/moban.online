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

$query = $db_slave->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_khoahoc WHERE id = ' . $id);
$news_contents = $query->fetch();

if($news_contents['id'] > 0)
{
    if( $news_contents['requirewatch'] > 0 ){
        $query = $db_slave->query('SELECT * FROM ' . NV_TABLE_AFFILIATE . '_users WHERE userid = ' . $user_info['userid']);
        $user_contents = $query->fetch();
        if(empty( $user_contents ) || $user_contents['numsubcat'] < $news_contents['requirewatch'] ){
            $no_permission = sprintf( $lang_module['no_permission'], $news_contents['requirewatch']);
            $contents = no_permission( $no_permission );
            include NV_ROOTDIR . '/includes/header.php';
            echo nv_site_theme($contents);
            include NV_ROOTDIR . '/includes/footer.php';
            exit;
        }
    }


    $base_url_rewrite = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $array_class[$news_contents['classid']]['alias'] . '/' . $news_contents['alias'] . '-' . $news_contents['id'] . $global_config['rewrite_exturl'], true);
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

    $show_no_image = $module_config[$module_name]['show_no_image'];

    if(defined('NV_IS_MODADMIN') or $news_contents['status'] == 1)
    {
        $time_set = $nv_Request->get_int($module_data . '_' . $op . '_' . $id, 'session');
        if(empty($time_set))
        {
            $nv_Request->set_Session($module_data . '_' . $op . '_' . $id, NV_CURRENTTIME);
            $query = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_khoahoc SET numview=numview+1 WHERE id=' . $id;
            $db->query($query);
        }
        $news_contents['showhometext'] = $module_config[$module_name]['showhometext'];
        if(! empty($news_contents['image']))
        {
            $src = $alt = $note = '';
            $width = $height = 0;
            $src = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $news_contents['image'];
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

        $file_info = @getimagesize($meta_property['og:image']);
        $meta_property['og:image:width'] = $file_info[0];
        $meta_property['og:image:height'] = $file_info[1];

        $publtime = intval($news_contents['addtime']);
        $meta_property['og:type'] = 'article';
        $meta_property['article:published_time'] = date('Y-m-dTH:i:s', $publtime);
        $meta_property['article:modified_time'] = date('Y-m-dTH:i:s', $news_contents['addtime']);
        if($news_contents['timeend'])
        {
            $meta_property['article:expiration_time'] = date('Y-m-dTH:i:s', $news_contents['timeend']);
        }
        $meta_property['article:section'] = $array_class[$news_contents['classid']]['title'];
    }

    if(defined('NV_IS_MODADMIN') and $news_contents['status'] != 1)
    {
        $alert = sprintf($lang_module['status_alert'], $lang_module['status_' . $news_contents['status']]);
        $my_footer .= "<script type=\"text/javascript\">alert('" . $alert . "')</script>";
        $news_contents['allowed_send'] = 0;
    }
}

if($news_contents['status'] == 0)
{
    $redirect = '<meta http-equiv="Refresh" content="3;URL=' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true) . '" />';
    nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] . $redirect, 404);
}

$news_contents['teacherid'] = explode(',', $news_contents['teacherid']);
foreach($news_contents['teacherid'] as $teacherid)
{
    if(isset($array_teacher[$teacherid]))
    {
        $news_contents['teacherinfo'][$teacherid]['teacher_name'] = $array_teacher[$teacherid]['title'];
        $news_contents['teacherinfo'][$teacherid]['teacher_facebooklink'] = $array_teacher[$teacherid]['facebooklink'];
        $news_contents['teacherinfo'][$teacherid]['teacher_link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=giao-vien/' . $array_teacher[$teacherid]['alias'];
    }
}
$news_contents['subject_name'] = $array_subject[$news_contents['subjectid']]['title'];
$news_contents['subject_link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $array_class[$news_contents['classid']]['alias'] . '/' . $array_subject[$news_contents['subjectid']]['alias'];

if($news_contents['price'] == 0)
{
    $news_contents['price_show'] = $lang_module['free'];
}
else
{
    $news_contents['price_show'] = number_format($news_contents['price'], 0, ',', '.');
}
//$news_contents['addtime'] = nv_date('l - d/m/Y H:i',$news_contents['addtime']);
$news_contents['timestudy'] = nv_date('l - d/m/Y', $news_contents['timestudy']);
$news_contents['timeend'] = ($news_contents['timeend'] > 0) ? nv_date('l - d/m/Y', $news_contents['timeend']) : $lang_global['khongcohansd'];

$news_contents['newscheckss'] = md5($news_contents['id'] . NV_CHECK_SESSION);

$time_set_rating = $nv_Request->get_int($module_name . '_' . $op . '_' . $news_contents['id'], 'cookie', 0);
if($time_set_rating > 0)
{
    $news_contents['disablerating'] = 1;
}
else
{
    $news_contents['disablerating'] = 0;
}
$news_contents['stringrating'] = sprintf($lang_module['stringrating'], $news_contents['total_rating'], $news_contents['click_rating']);
$news_contents['numberrating'] = ($news_contents['click_rating'] > 0) ? round($news_contents['total_rating'] / $news_contents['click_rating'], 1) : 0;
$news_contents['langstar'] = array(
    'note' => $lang_module['star_note'],
    'verypoor' => $lang_module['star_verypoor'],
    'poor' => $lang_module['star_poor'],
    'ok' => $lang_module['star_ok'],
    'good' => $lang_module['star_good}'],
    'verygood' => $lang_module['star_verygood']);

$array_keyword = array();
$key_words = array();
$_query = $db_slave->query('SELECT a1.keyword, a2.alias FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id a1 INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_tags a2 ON a1.tid=a2.tid WHERE a1.id=' . $news_contents['id']);
while($row = $_query->fetch())
{
    $array_keyword[] = $row;
    $key_words[] = $row['keyword'];
    $meta_property['article:tag'][] = $row['keyword'];
}
$lang_module['danhsachbaigiang'] = sprintf($lang_module['danhsachbaigiang'], $news_contents['title']);

$link_xem_baigiang = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $array_class[$news_contents['classid']]['alias'] . '/' . $news_contents['alias'] . '-' . $news_contents['id'];
$array_baihoc = $array_baihoc_mienphi = array();
$_query = $db_slave->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_baihoc WHERE status=1 AND khoahocid=' . $news_contents['id'] . ' ORDER BY weight');
while($row = $_query->fetch())
{
    if(! empty($row['image']) && file_exists(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image']))
    {
        $row['image'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $row['image'];
    }
    else
    {
        $row['image'] = '';
    }

    $row['link'] = $link_xem_baigiang . '/' . $row['alias'] . '-' . $row['id'] . $global_config['rewrite_exturl'];
    if($row['timephathanh'] > NV_CURRENTTIME)
    {
        $row['text_status_baigiang'] = $lang_module['sapphathanh'];
        $row['classcss_status_phathanh'] = '';
    }
    else
    {
        $row['text_status_baigiang'] = $lang_module['daphathanh'];
        $row['classcss_status_phathanh'] = ' percent-100';
        $row['linkbaigiang'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=bai-giang/' . $row['alias'] . '-' . $row['id'] . $global_config['rewrite_exturl'];
    }

    $row['timephathanh_text'] = ($row['timephathanh'] > 0) ? date('d/m/Y', $row['timephathanh']) : 'N/A';
    if($row['price'] == 0)
    {
        $row['price_format'] = $lang_module['free'];
        $array_baihoc_mienphi[] = $row;
    }
    else
    {
        $row['price_format'] = number_format($row['price'], 0, '.', ',') . '&nbsp';
        $array_baihoc[$row['id']] = $row;
    }
}
$news_contents['checkress'] = md5( $news_contents['id'] );
$array_baihoc_da_mua = $array_khoahoc_da_mua = array();
$num_view_baigiang = 0;

if(defined('NV_IS_USER'))
{
    //kiem tra xem mua ca khoa hoc chua
    $db_slave->sqlreset()
        ->select('COUNT(*)')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_buyhistory' )
        ->where('istype=2 AND userid=' . $user_info['userid'] . ' AND idbuy=' . $news_contents['id']);

    $num_items = $db_slave->query($db_slave->sql())->fetchColumn();
    if($num_items > 0 ){
        $db_slave->select('*');
        $array_khoahoc_da_mua[$news_contents['id']] = $db_slave->query($db_slave->sql())->fetch();
    }
    if(empty($array_khoahoc_da_mua))
    {
        $_query = $db_slave->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_viewhistory WHERE khoahocid =' . $news_contents['id'] . ' AND userid=' . $user_info['userid']);
        while($row_baihoc = $_query->fetch())
        {
            $array_baihoc_da_mua[$row_baihoc['baihocid']] = $row_baihoc;
        }
    }

    //kiem tra xem session hien tai da danh gia khoa hoc chua
    $checkrevc = $nv_Request->get_int($module_data . '_review_content', 'session', 0);
    if( $checkrevc == 0){
        $nv_Request->set_Session($module_data . '_review_content', 1);
        //kiem tra xem da danh gia khoa hoc chua
        $db_slave->sqlreset()
            ->select('COUNT(*)')
            ->from(NV_PREFIXLANG . '_' . $module_data . '_review' )
            ->where('userid=' . $user_info['userid'] . ' AND khoahocid=' . $news_contents['id']);
        $review_khoahoc = $db_slave->query($db_slave->sql())->fetchColumn();

    //neu chua danh gia thi kiem tra tiep xem da xem bai giang nao chua
        if( $review_khoahoc == 0){
            //kiem tra xem da tung xem bai giang chua
            $db_slave->sqlreset()
                ->select('COUNT(*)')
                ->from(NV_PREFIXLANG . '_' . $module_data . '_viewhistory' )
                ->where('numview>0 AND userid=' . $user_info['userid'] . ' AND khoahocid=' . $news_contents['id']);
            //muc dich neu da xem bai giang se hien thi popup review
            $num_view_baigiang = $db_slave->query($db_slave->sql())->fetchColumn();
        }
    }
    $news_contents['checkress'] = md5($user_info['userid'] . $news_contents['id'] );
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
$contents = studyonline_detail_theme($news_contents, $num_view_baigiang, $array_baihoc, $array_baihoc_mienphi, $array_baihoc_da_mua, $array_khoahoc_da_mua, $array_keyword, $content_comment);

$page_title = empty($news_contents['titlesite']) ? $news_contents['title'] : $news_contents['titlesite'];
$key_words = implode(',', $key_words);
$description = empty($news_contents['description']) ? nv_clean60(strip_tags($news_contents['hometext']), 160) : $news_contents['description'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
