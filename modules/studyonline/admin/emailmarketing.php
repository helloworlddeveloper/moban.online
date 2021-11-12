<?php

/**
 * @Project NUKEVIET 4.x
 * @Author EDUSGROUP.JSC (thangbv@edus.vn)
 * @Copyright (C) 2015 EDUSGROUP.JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 03 Mar 2015 14:33:58 GMT
 */

if(! defined('NV_IS_FILE_ADMIN'))
    die('Stop!!!');

$id = $nv_Request->get_int('id', 'post', 0);

$query = $db_slave->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_khoahoc WHERE id = ' . $id);
$news_contents = $query->fetch();
if(isset($site_mods['emailmarketing']))
{
    if($news_contents['id'] > 0)
    {
        if(! empty($news_contents['image']) && file_exists(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $news_contents['image']))
        {
            $news_contents['image'] = NV_MY_DOMAIN . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $news_contents['image'];
        }
        else
        {
            $news_contents['image'] = '';
        }
        
        $link_xem_baigiang = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $array_class[$news_contents['classid']]['alias'] . '/' . $news_contents['alias'] . '-' . $news_contents['id'];
        
        $_query = $db_slave->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_baihoc WHERE status=1 AND khoahocid=' . $news_contents['id']);
        while($row = $_query->fetch())
        {
            $db->query('DELETE FROM ' . NV_PREFIXLANG . '_emailmarketing_rows WHERE baigiangid = ' . $row['id']);

            if(! empty($row['image']) && file_exists(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image']))
            {
                $row['image'] = NV_MY_DOMAIN . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image'];
            }
            else
            {
                $row['image'] = $news_contents['image'];
            }
            $row['timephathanh_text'] = date('d/m/Y H:i', $row['timephathanh']);
            $row['title_mail'] = $lang_module['send_mail_baihoc_title'] . $row['title'] . ' - ' . $global_config['site_name'];
            $row['title'] = sprintf($lang_module['title_send_mail_baihoc'], $row['title']);
            //chi inset cac ban ghi co thoi gian phat hanh lon hon hien tai
            if($row['timephathanh'] > NV_CURRENTTIME)
            {
                $row['linkbaihoc'] = NV_MY_DOMAIN . nv_url_rewrite($link_xem_baigiang . '/' . $row['alias'] . '-' . $row['id'] . $global_config['rewrite_exturl'], true);
                $xtpl = new XTemplate('template_mail.tpl', NV_ROOTDIR . '/modules/' . $module_file);
                $xtpl->assign('LANG', $lang_module);
                $xtpl->assign('DATA', $row);
                $xtpl->parse('main');
                $contents = $xtpl->text('main');

                $_sql = 'INSERT INTO ' . NV_PREFIXLANG . '_emailmarketing_rows (idsender, idreplyto, title, content, files, usergroup, customergroup, emaillist, addtime, typetime, begintime, endtime, template, linkstatics, openstatics, sendstatus, baigiangid) VALUES (:idsender, :idreplyto, :title, :content, :files, :usergroup, :customergroup, :emaillist, ' . NV_CURRENTTIME . ', :typetime, :begintime, :endtime, :template, :linkstatics, :openstatics, :sendstatus, ' . $row['id'] . ')';
                $data_insert = array();
                $data_insert['idsender'] = 1;
                $data_insert['idreplyto'] = 1;
                $data_insert['title'] = $row['title_mail'];
                $data_insert['content'] = $contents;
                $data_insert['files'] = '';
                $data_insert['usergroup'] = '';
                $data_insert['customergroup'] = '';
                $data_insert['emaillist'] = '';
                $data_insert['typetime'] = 1;
                $data_insert['begintime'] = $row['timephathanh'] - 600; //gui truoc 10'
                $data_insert['endtime'] = 0;
                $data_insert['template'] = 1;
                $data_insert['linkstatics'] = 1;
                $data_insert['openstatics'] = 1;
                $data_insert['sendstatus'] = 0;

                $new_id = $db->insert_id($_sql, 'id', $data_insert);
                if( $new_id > 0 ){
                    //kiem tra cac user da dang ky bai hoc nay
                    $data_user = array();
                    $query = $db_slave->query('SELECT t2.first_name, t2.last_name, t2.username, t2.email FROM ' . NV_PREFIXLANG . '_' . $module_data . '_buyhistory AS t1 INNER JOIN ' . NV_USERS_GLOBALTABLE . ' AS t2 ON t1.userid=t2.userid  WHERE (t1.idbuy = ' . $row['id'] . ' AND t1.istype=1) OR (t1.idbuy = ' . $news_contents['id'] . ' AND t1.istype=2)');
                    while($data_mail = $query->fetch()){
                     $data_user[] = $data_mail['email'];   
                    }
                    if( !empty( $data_user )){
                        $data_user = implode('<br />', $data_user);   
                        $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_emailmarketing_rows SET emaillist = :emaillist WHERE id=' . $new_id);
                        $stmt->bindParam(':emaillist', $data_user, PDO::PARAM_STR, strlen($data_user));
                        $stmt->execute();
                    }
                }
            }
        }
        exit('OK_' . $news_contents['id']);
    }
}
