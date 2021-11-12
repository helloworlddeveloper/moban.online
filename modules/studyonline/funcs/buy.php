<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2015 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Fri, 20 Mar 2015 02:51:05 GMT
 */

$baihocid = $nv_Request->get_int('lesson_id', 'post', 0);
$khoahocid = $nv_Request->get_int('khoahocid', 'post', 0);
$array_return = array();
if(! defined('NV_IS_USER'))
{
    $array_return['status'] = 0;
    $array_return['message'] = $lang_module['message_reponse_0'];
}
elseif($baihocid > 0)
{
    $iproduct = $baihocid;
    if($db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_khoahoc WHERE id=' . $khoahocid)->fetchColumn() > 0)
    {
        if(($result = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_baihoc WHERE id=' . $baihocid)) !== false)
        {
            $news_contents = $result->fetch();

            //kiem tra xem bai nay da duoc mua hay chua.
            $data_check = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_buyhistory WHERE istype=1 AND idbuy=' . $baihocid . ' AND userid=' . $user_info['userid'] . ' AND numview<' . $news_contents['numviewtime'])->fetch();
            if(! empty($data_check))
            {
                $array_return['status'] = 201;
                $array_return['message'] = $lang_module['message_reponse_201'];
            }
            else
            {
                // taikhoan
                if($news_contents['price'] > 0)
                {
                    $istype = 1; //1= tung bai
                    $pricebefor = $priceafter = $news_contents['price'];
                    $notice_transaction = sprintf($lang_module['transaction_note_baihoc'], $news_contents['title']);
                    $array_return = nv_buy_action($module_name, $khoahocid, $baihocid, $iproduct, $istype, $news_contents['price'], $pricebefor, $priceafter, $notice_transaction);
                }
            }
        }
        else
        {
            $array_return['status'] = 1;
            $array_return['message'] = $lang_module['message_reponse_1'];
        }
    }
    else
    {
        $array_return['status'] = 1;
        $array_return['message'] = $lang_module['message_reponse_1'];
    }
}
elseif($khoahocid > 0)
{
    if(($result = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_khoahoc WHERE id=' . $khoahocid)) !== false)
    {
        $iproduct = $khoahocid;
        $news_contents = $result->fetch();

        //kiem tra xem bai nay da duoc mua hay chua.
        $data_check = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_buyhistory WHERE istype=2 AND idbuy=' . $khoahocid . ' AND userid=' . $user_info['userid'] . ' AND numview<' . $news_contents['numviewtime'])->fetch();
        if(! empty($data_check))
        {
            $array_return['status'] = 201;
            $array_return['message'] = $lang_module['message_khoahoc_reponse_201'];
        }
        else
        {
            $istype = 2; //2= ca khoa
            $pricebefor = $priceafter = $news_contents['price'];
            $notice_transaction = sprintf($lang_module['transaction_note_khoahoc'], $news_contents['title']);
            $array_return = nv_buy_action($module_name, $khoahocid, 0, $iproduct, $istype, $news_contents['price'], $pricebefor, $priceafter, $notice_transaction);
        }
    }
    else
    {
        $array_return['status'] = 1;
        $array_return['message'] = $lang_module['message_khoahoc_reponse_1'];
    }
}
else
{
    $array_return['status'] = 1;
    $array_return['message'] = $lang_module['message_reponse_1'];
}
if($array_return['status'] == 200)
{
    if($istype == 1)
    {
        //cap nhat vao danh sach thong bao khi bai giang phat hanh neu ton tai ban ghi nay
        $query = $db_slave->query('SELECT * FROM ' . NV_PREFIXLANG . '_emailmarketing_rows WHERE baigiangid = ' . $baihocid);
        $data_mail_contents = $query->fetch();
        if(! empty($data_mail_contents))
        {
            $data_mail_contents['emaillist'] = $data_mail_contents['emaillist'] . '<br />' . $user_info['email'];
            $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_emailmarketing_rows SET emaillist = :emaillist WHERE id=' . $data_mail_contents['id']);
            $stmt->bindParam(':emaillist', $data_mail_contents['emaillist'], PDO::PARAM_STR, strlen($data_mail_contents['emaillist']));
            $stmt->execute();
        }
    }
    elseif($istype == 2)
    {
        //neu mua ca khoa hoc
        $_query = $db_slave->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_baihoc WHERE status=1 AND khoahocid=' . $khoahocid);
        while($row = $_query->fetch())
        {
            $query = $db_slave->query('SELECT * FROM ' . NV_PREFIXLANG . '_emailmarketing_rows WHERE baigiangid = ' . $row['id']);
            $data_mail_contents = $query->fetch();
            if(! empty($data_mail_contents))
            {
                if(! empty($data_mail_contents['emaillist']))
                {
                    $data_mail_contents['emaillist'] = $data_mail_contents['emaillist'] . '<br />' . $user_info['email'];
                }
                else
                {
                    $data_mail_contents['emaillist'] = $user_info['email'];
                }

                $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_emailmarketing_rows SET emaillist = :emaillist WHERE id=' . $data_mail_contents['id']);
                $stmt->bindParam(':emaillist', $data_mail_contents['emaillist'], PDO::PARAM_STR, strlen($data_mail_contents['emaillist']));
                $stmt->execute();
            }
        }
    }
}
exit(json_encode($array_return, true));
