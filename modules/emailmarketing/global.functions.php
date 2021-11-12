<?php

/**
 * @Project NUKEVIET 4.x
 * @Author mynukeviet (contact@mynukeviet.net)
 * @Copyright (C) 2016 mynukeviet. All rights reserved
 * @Createdate Sat, 15 Oct 2016 03:30:10 GMT
 */
if (!defined('NV_MAINFILE')) die('Stop!!!');

$array_config = $module_config[$module_name];

$array_personal = array(
    '[CONTENT]' => $lang_module['content_note_content'],
    '[FULLNAME]' => $lang_module['content_note_fullname'],
    '[EMAIL]' => $lang_module['content_note_email'],
    '[ALIAS]' => $lang_module['content_note_alias'],
    '[SITE_NAME]' => sprintf($lang_module['content_note_site_name'], $global_config['site_name']),
    '[SITE_DOMAIN]' => sprintf($lang_module['content_note_site_domain'], NV_MY_DOMAIN),
    '[SITE_DESCRIPTION]' => $lang_module['content_note_site_description'],
    '[LOGO_SRC]' => $lang_module['content_note_logo'],
    '[LOGO_WIDTH]' => $lang_module['content_note_logo_width'],
    '[LOGO_HEIGHT]' => $lang_module['content_note_logo_height'],
    '[URL_DECLINED]' => $lang_module['content_note_url_declined']
);

$array_personal_sms = array(
    '[FULLNAME]' => $lang_module['content_note_fullname'],
    '[MOBILE]' => $lang_module['content_note_phone'],
    '[ALIAS]' => $lang_module['content_note_alias'],
    '[SITE_NAME]' => sprintf($lang_module['content_note_site_name'], $global_config['site_name']),
    '[SITE_DOMAIN]' => sprintf($lang_module['content_note_site_domain'], NV_MY_DOMAIN)
);

function nv_listmail_content($rowid, $ignoredie = 1, $ignoredeclined = 1)
{
    global $db, $module_data, $lang_module, $nv_Cache, $module_name;
    
    $array_email = $array_email_tmp = array();
    
    $rows = $db->query('SELECT usergroup, customergroup, emaillist FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $rowid)->fetch();
    if (!$rows) {
        return $array_email;
    }
    
    // Danh sach email loi
    $array_email_die = array();
    if ($ignoredie) {
        $result = $db->query('SELECT email FROM ' . NV_PREFIXLANG . '_' . $module_data . '_customer WHERE is_die=1');
        while (list ($email) = $result->fetch(3)) {
            $array_email_die[] = $email;
        }
        
        $result = $db->query('SELECT email FROM ' . NV_PREFIXLANG . '_' . $module_data . '_die');
        while (list ($email) = $result->fetch(3)) {
            $array_email_die[] = $email;
        }
        
        $array_email_die = array_unique($array_email_die);
    }
    
    // Danh sach email tu choi nhan thu
    $array_email_declined = array();
    if ($ignoredeclined) {
        $result = $db->query('SELECT email FROM ' . NV_PREFIXLANG . '_' . $module_data . '_customer WHERE is_declined=1');
        while (list ($email) = $result->fetch(3)) {
            $array_email_declined[] = $email;
        }
        
        $result = $db->query('SELECT email FROM ' . NV_PREFIXLANG . '_' . $module_data . '_declined');
        while (list ($email) = $result->fetch(3)) {
            $array_email_declined[] = $email;
        }
        
        $array_email_declined = array_unique($array_email_declined);
    }
    
    if (!empty($rows['customergroup'])) {
        $rows['customergroup'] = unserialize($rows['customergroup']);
        $result = $db->query('SELECT customerid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_customer_groups WHERE groupid IN (' . implode(',', $rows['customergroup']) . ')');
        $where = $ignoredie ? ' AND is_die=0' : '';
        $where .= $ignoredeclined ? ' AND is_declined=0' : '';
        while (list ($customerid) = $result->fetch(3)) {
            $customer_info = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_customer WHERE status=1 AND id=' . $customerid . $where)->fetch();
            if ($customer_info) {
                if (!in_array($customer_info['email'], $array_email_tmp)) {
                    $array_email[] = array(
                        'customerid' => $customer_info['id'],
                        'email' => $customer_info['email'],
                        'fullname' => $customer_info['fullname'],
                        'gender' => $customer_info['gender'],
                        'birthday' => $customer_info['birthday'],
                        'phone' => $customer_info['phone'],
                        'is_check' => 0
                    );
                    $array_email_tmp[] = $customer_info['email'];
                }
            }
        }
    }
    
    if (!empty($rows['usergroup'])) {
        $rows['usergroup'] = unserialize($rows['usergroup']);
        $result = $db->query('SELECT email, first_name, last_name, username, gender, birthday FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid IN ( SELECT userid FROM ' . NV_GROUPS_GLOBALTABLE . '_users WHERE group_id IN ( ' . implode(',', $rows['usergroup']) . ' ) )');
        while ($_row = $result->fetch()) {
            if (in_array($_row['email'], $array_email_tmp) or in_array($_row['email'], $array_email_die) or in_array($_row['email'], $array_email_declined)) {
                continue;
            }
            $array_email_tmp[] = $_row['email'];
            $array_email[] = array(
                'customerid' => 0,
                'email' => $_row['email'],
                'fullname' => nv_show_name_user($_row['first_name'], $_row['last_name'], $_row['username']),
                'gender' => $_row['gender'] == 'M' ? 1 : ($_row['gender'] == 'F' ? 0 : 2),
                'birthday' => $_row['birthday'],
                'phone' => ''
            );
        }
    }
    
    if (!empty($rows['emaillist'])) {
        $rows['emaillist'] = explode('<br />', $rows['emaillist']);
        if (!empty($rows['emaillist'])) {
            foreach ($rows['emaillist'] as $email) {
                if (in_array($email, $array_email_tmp) or in_array($email, $array_email_die) or in_array($email, $array_email_declined)) {
                    continue;
                }
                $array_email[] = array(
                    'customerid' => 0,
                    'email' => $email,
                    'fullname' => '',
                    'gender' => 2,
                    'birthday' => 0,
                    'phone' => ''
                );
                $array_email_tmp[] = $email;
            }
        }
    }
    
    unset($array_email_tmp);
    
    return $array_email;
}

function nv_sendmail_action($customer, $rows, $json = 1)
{
    global $db, $module_data, $array_config, $global_config;
    
    if (!empty($customer)) {
        if (in_array($customer['customerid'], $rows['sendlist'])) {
            $from = array(
                $global_config['site_name'],
                $global_config['site_email']
            );
            
            $sender = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sender WHERE status=1 AND id=' . $rows['idsender'])->fetch();
            if ($sender) {
                $from = array(
                    $sender['name'],
                    $sender['email']
                );
            }
            
            $_replyto = $from;
            if (!empty($rows['idreplyto'])) {
                $_replyto = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sender WHERE status=1 AND id=' . $rows['idreplyto'])->fetch();
                if ($_replyto) {
                    $replyto = array(
                        $_replyto['name'],
                        $_replyto['email']
                    );
                }
            }
            
            $title = nv_build_title($rows['title'], $customer);
            $content = nv_build_content($rows['id'], $rows['content'], $customer, $rows['template'], $rows['linkstatics'], $rows['openstatics']);
            $result = nv_emailmarketing_sendmail($rows['id'], $from, $customer['email'], $title, $content, $replyto);
            $totalsend = $exit = 0;
            $status = 'success';
            $messenger = '';
            
            if (empty($result)) {
                // Cap nhat danh sach khach hang da gui thanh cong
                $rows['sendedlist'][] = $customer['customerid'];
                $rows['sendedlist'] = array_unique($rows['sendedlist']);
                $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET sendedlist=' . $db->quote(implode(',', $rows['sendedlist'])) . ' WHERE id=' . $rows['id']);
                $totalsend += count($rows['sendedlist']);
            } else {
                // Cap nhat danh sach khach hang loi
                $rows['errorlist'][] = $customer['customerid'];
                $rows['errorlist'] = array_unique($rows['errorlist']);
                $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET errorlist=' . $db->quote(implode(',', $rows['errorlist'])) . ' WHERE id=' . $rows['id']);
                $totalsend += count($rows['errorlist']);
                $status = 'error';
                $messenger = $result;
                
                if ($array_config['stoperror']) {
                    $exit = 0;
                }
            }
            
            $totalsuccess = count($rows['sendedlist']);
            
            // Xu ly sau khi gui thanh cong
            if (empty($customer['nextcustomerid'])) {
                unset($_SESSION[$module_data . '_mailserver'][$rows['id']]);
                $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET totalemailsend = ' . $totalsend . ', totalmailsuccess = ' . $totalsuccess . ', endtime = ' . NV_CURRENTTIME . ', sendstatus=1 WHERE id=' . $rows['id']);
            }
            
            if ($json) {
                die(json_encode(array(
                    'rowsid' => $rows['id'],
                    'status' => $status,
                    'messenger' => $messenger,
                    'customerid' => $customer['customerid'],
                    'totalsend' => $totalsend,
                    'countsuccess' => $totalsuccess,
                    'counterror' => count($rows['errorlist']),
                    'nextcustomerid' => $customer['nextcustomerid'],
                    'exit' => $exit
                )));
            } else {
                return $status == 'success' ? true : false;
            }
        }
    }
    return false;
}

function nv_sendphone_action($customer, $rows, $json = 1)
{
    global $db, $module_data, $array_config, $global_config, $module_config, $module_name;
    
    if (!empty($customer)) {
        if (in_array($customer['customerid'], $rows['sendlist'])) {
            $content = nv_build_content_sms($rows['id'], $rows['content'], $customer, $rows['linkstatics'], $rows['openstatics']);
            
            $apikey = $module_config[$module_name]['apikey'];
            $secretkey = $module_config[$module_name]['secretkey'];
            $sms_type = $module_config[$module_name]['sms_type'];
            $content = urlencode($content);
            
            $data = 'http://rest.esms.vn/MainService.svc/json/SendMultipleMessage_V4_get?Phone=' . $customer['phone'] . '&ApiKey=' . $apikey . '&SecretKey=' . $secretkey . '&Content=' . $content . '&SmsType=' . $sms_type;
            $curl = curl_init($data);
            curl_setopt($curl, CURLOPT_FAILONERROR, true);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($curl);
            
            $obj = json_decode($result, true);
            $totalsend = $exit = 0;
            $status = 'success';
            $messenger = '';
            
            if ($obj['CodeResult'] == 100) {
                $rows['sendedlist'][] = $customer['customerid'];
                $rows['sendedlist'] = array_unique($rows['sendedlist']);
                $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_smsrows SET sendedlist=' . $db->quote(implode(',', $rows['sendedlist'])) . ' WHERE id=' . $rows['id']);
                $totalsend += count($rows['sendedlist']);
            } else {
                $rows['errorlist'][] = $customer['customerid'];
                $rows['errorlist'] = array_unique($rows['errorlist']);
                $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_smsrows SET errorlist=' . $db->quote(implode(',', $rows['errorlist'])) . ' WHERE id=' . $rows['id']);
                $totalsend += count($rows['errorlist']);
                $status = 'error';
                $messenger = $obj['ErrorMessage'];
                
                if ($array_config['stoperror']) {
                    $exit = 0;
                }
            }
            
            $totalsuccess = count($rows['sendedlist']);
            
            // Xu ly sau khi gui thanh cong
            if (empty($customer['nextcustomerid'])) {
                $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_smsrows SET totalsmssend = ' . $totalsend . ', totalsmssuccess = ' . $totalsuccess . ', endtime = ' . NV_CURRENTTIME . ', sendstatus=1 WHERE id=' . $rows['id']);
            }
            
            if ($json) {
                die(json_encode(array(
                    'rowsid' => $rows['id'],
                    'status' => $status,
                    'messenger' => $messenger,
                    'customerid' => $customer['customerid'],
                    'totalsend' => $totalsend,
                    'countsuccess' => $totalsuccess,
                    'counterror' => count($rows['errorlist']),
                    'nextcustomerid' => $customer['nextcustomerid'],
                    'exit' => $exit
                )));
            } else {
                return $status == 'success' ? true : false;
            }
        }
    }
    return false;
}

function nv_build_title($title, $customer)
{
    global $module_name, $module_info, $global_config, $module_data, $lang_module;
    
    $title = nv_unhtmlspecialchars($title);
    
    // Thay the bien trong tieu de
    $array_replace = array(
        '[FULLNAME]' => !empty($customer['fullname']) ? $customer['fullname'] : $lang_module['customers'],
        '[EMAIL]' => $customer['email'],
        '[ALIAS]' => $lang_module['alias_' . $customer['gender']],
        '[SITE_NAME]' => $global_config['site_name'],
        '[SITE_DOMAIN]' => NV_MY_DOMAIN,
        '[SITE_DESCRIPTION]' => $global_config['site_description']
    );
    
    foreach ($array_replace as $index => $value) {
        $title = str_replace($index, $value, $title);
    }
    
    return $title;
}

function nv_build_content($rowsid, $content, $customer, $idtemplate = 0, $linkstatics = 1, $openstatics = 1)
{
    global $db, $module_name, $module_info, $global_config, $module_data, $lang_module;
    
    $idcustomer = $customer['customerid'];
    $content = nv_unhtmlspecialchars($content);
    
    // Theo doi click vao link trong noi dung
    if ($linkstatics) {
        $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
        if (preg_match_all("/$regexp/siU", $content, $matches)) {
            foreach ($matches[2] as $link) {
                $replace = NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['statics'] . '&amp;action=openlink&amp;rowsid=' . $rowsid . '&amp;customer=' . $idcustomer . '&amp;link=' . base64_encode($link) . '&amp;linkmd5=' . md5($link) . '&amp;checksum=' . md5($global_config['sitekey'] . '-' . $idcustomer . '-' . $rowsid);
                $content = str_replace($link, $replace, $content);
            }
        }
    }
    
    // Xac dinh giao dien
    $html = '';
    if ($idtemplate > 0) {
        if (file_exists(NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/' . $module_name . '/template_' . $idtemplate . '.html')) {
            $html = file_get_contents(NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/' . $module_name . '/template_' . $idtemplate . '.html');
        }
    }
    
    if (empty($html)) {
        $html = $content;
    }
    
    // Xac dinh logo
    $size = @getimagesize(NV_ROOTDIR . '/' . $global_config['site_logo']);
    $logo = preg_replace('/\.[a-z]+$/i', '.svg', $global_config['site_logo']);
    if (!file_exists(NV_ROOTDIR . '/' . $logo)) {
        $logo = $global_config['site_logo'];
    }
    
    // Thay the bien noi dung
    $array_replace = array(
        '[FULLNAME]' => !empty($customer['fullname']) ? $customer['fullname'] : $lang_module['customers'],
        '[EMAIL]' => $customer['email'],
        '[ALIAS]' => $lang_module['alias_' . $customer['gender']],
        '[SITE_NAME]' => $global_config['site_name'],
        '[SITE_DOMAIN]' => NV_MY_DOMAIN,
        '[SITE_DESCRIPTION]' => $global_config['site_description'],
        '[LOGO_SRC]' => NV_MY_DOMAIN . NV_BASE_SITEURL . $logo,
        '[LOGO_WIDTH]' => $size[0],
        '[LOGO_HEIGHT]' => $size[1],
        '[URL_DECLINED]' => NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['statics'] . '&amp;action=declined&amp;rowsid=' . $rowsid . '&amp;customer=' . $idcustomer . '&amp;checksum=' . md5($global_config['sitekey'] . '-' . $idcustomer . '-' . $rowsid)
    );
    
    foreach ($array_replace as $index => $value) {
        $html = str_replace($index, $value, $html);
        $content = str_replace($index, $value, $content);
    }
    
    $html = str_replace('[CONTENT]', $content, $html);
    
    // Theo doi khach mo thu
    if ($openstatics) {
        $replace = NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['statics'] . '&amp;action=openmail&amp;rowsid=' . $rowsid . '&amp;customer=' . $idcustomer . '&amp;checksum=' . md5($global_config['sitekey'] . '-' . $idcustomer . '-' . $rowsid);
        $html = $html . '<img src="' . $replace . '" style="display: none" />';
    }
    
    return $html;
}

function nv_build_content_sms($rowsid, $content, $customer, $linkstatics = 1, $openstatics = 1)
{
    global $db, $module_name, $module_info, $global_config, $module_data, $lang_module;
    
    $idcustomer = $customer['customerid'];
    $content = nv_unhtmlspecialchars($content);
    
    // Theo doi click vao link trong noi dung
    if ($linkstatics) {
        $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
        if (preg_match_all("/$regexp/siU", $content, $matches)) {
            foreach ($matches[2] as $link) {
                $replace = NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['statics'] . '&amp;action=openlink&amp;rowsid=' . $rowsid . '&amp;customer=' . $idcustomer . '&amp;link=' . base64_encode($link) . '&amp;linkmd5=' . md5($link) . '&amp;checksum=' . md5($global_config['sitekey'] . '-' . $idcustomer . '-' . $rowsid);
                $content = str_replace($link, $replace, $content);
            }
        }
    }
    
    // Thay the bien noi dung
    $array_replace = array(
        '[FULLNAME]' => !empty($customer['fullname']) ? $customer['fullname'] : $lang_module['customers'],
        '[EMAIL]' => $customer['email'],
        '[MOBILE]' => $customer['phone'],
        '[ALIAS]' => $lang_module['alias_' . $customer['gender']],
        '[SITE_NAME]' => $global_config['site_name'],
        '[SITE_DOMAIN]' => NV_MY_DOMAIN
    );
    
    foreach ($array_replace as $index => $value) {
        $html = str_replace($index, $value, $html);
        $content = str_replace($index, $value, $content);
    }
    return $content;
}

function nv_check_customer($customer)
{
    global $db, $module_data, $array_config;
    
    // Them vao vao bang khach hang neu chua co
    if (empty($customer['customerid'])) {
        $count = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_customer WHERE email=' . $db->quote($customer['email']))
            ->fetchColumn();
        if (empty($count)) {
            $new_customer_group = explode(',', $array_config['new_customer_group']);
            $customer['customerid'] = nv_add_customer($customer, $new_customer_group);
        } else {
            $customer['customerid'] = $db->query('SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_customer WHERE email=' . $db->quote($customer['email']))
                ->fetchColumn();
        }
    }
    return $customer['customerid'];
}

function nv_check_customer_by_phone($customer)
{
    global $db, $module_data, $array_config;
    
    // Them vao vao bang khach hang neu chua co
    if (empty($customer['customerid'])) {
        $count = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_customer WHERE phone=' . $db->quote($customer['phone']))
            ->fetchColumn();
        if (empty($count)) {
            $new_customer_group = explode(',', $array_config['new_customer_group']);
            $customer['customerid'] = nv_add_customer($customer, $new_customer_group);
        } else {
            $customer['customerid'] = $db->query('SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_customer WHERE phone=' . $db->quote($customer['phone']))
                ->fetchColumn();
        }
    }
    return $customer['customerid'];
}

function nv_emailmarketing_sendmail($rowsid, $from, $to, $subject, $message, $replyto, $files = '')
{
    global $db, $module_name, $module_data, $global_config, $nv_Cache, $lang_module, $crypt;
    
    $mailserver = nv_get_mailserver($rowsid);
    
    if (!empty($mailserver)) {
        try {
            $mail = new PHPMailer\PHPMailer\PHPMailer();
            $mail->SetLanguage(NV_LANG_INTERFACE);
            $mail->CharSet = $global_config['site_charset'];
            
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->Port = $mailserver['smtp_port'];
            $mail->Host = $mailserver['smtp_host'];
            $mail->Username = $mailserver['smtp_username'];
            $mail->Password = $crypt->aes_decrypt(nv_base64_decode($mailserver['smtp_password']));
            
            $SMTPSecure = intval($mailserver['smtp_encrypted']);
            switch ($SMTPSecure) {
                case 1:
                    $mail->SMTPSecure = 'ssl';
                    break;
                case 2:
                    $mail->SMTPSecure = 'tls';
                    break;
                default:
                    $mail->SMTPSecure = '';
            }
            
            $message = nv_url_rewrite($message);
            $message = nv_change_buffer($message);
            $message = nv_unhtmlspecialchars($message);
            
            if (is_array($from)) {
                $mail->From = $from[1];
                $mail->FromName = $from[0];
            } else {
                $mail->From = $from[1];
            }
            
            if (is_array($replyto)) {
                $mail->addReplyTo($replyto[1], $replyto[0]);
            } else {
                $mail->addReplyTo($replyto);
            }
            
            if (empty($to)) {
                return $lang_module['error_empty_to'];
            }
            
            if (!is_array($to)) {
                $to = array(
                    $to
                );
            }
            
            foreach ($to as $_to) {
                $mail->addAddress($_to);
            }
            
            $mail->Subject = nv_unhtmlspecialchars($subject);
            $mail->WordWrap = 120;
            $mail->Body = $message;
            $mail->AltBody = strip_tags($message);
            $mail->IsHTML(true);
            
            if (!empty($files)) {
                $files = array_map('trim', explode(',', $files));
                
                foreach ($files as $file) {
                    $mail->addAttachment($file);
                }
            }
            
            if (!$mail->Send()) {
                trigger_error($mail->ErrorInfo, E_USER_WARNING);
                return $mail->ErrorInfo;
            }
            
            $_SESSION[$module_data . '_mailserver'][$rowsid][$mailserver['id']]++;
        } catch (phpmailerException $e) {
            trigger_error($e->errorMessage(), E_USER_WARNING);
            return $e->errorMessage();
        }
    } else {
        return $lang_module['error_empty_mailserver'];
    }
}

function nv_get_mailserver($rowsid)
{
    global $db, $module_data, $module_name, $nv_Cache;
    
    $_sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_mailserver WHERE status=1';
    $array_mailserver = $nv_Cache->db($_sql, '', $module_name);
    
    $mailserver = array();
    
    if (!empty($array_mailserver)) {
        $i = 1;
        foreach ($array_mailserver as $mailserver_info) {
            
            if (!empty($mailserver_info['sendlimit'])) {
                if (!isset($_SESSION[$module_data . '_mailserver'][$rowsid][$mailserver_info['id']])) {
                    $_SESSION[$module_data . '_mailserver'][$rowsid][$mailserver_info['id']] = 0;
                }
                
                if ($_SESSION[$module_data . '_mailserver'][$rowsid][$mailserver_info['id']] < $mailserver_info['sendlimit']) {
                    $mailserver = $mailserver_info;
                    $_SESSION[$module_data . '_mailserver'][$rowsid][$mailserver_info['id']]++;
                    break;
                } else {
                    $i++;
                }
                
                if ($i > count($array_mailserver)) {
                    unset($_SESSION[$module_data . '_mailserver'][$rowsid]);
                    return nv_get_mailserver();
                } else {
                    continue;
                }
            } else {
                $mailserver = $mailserver_info;
                break;
            }
        }
    }
    
    return $mailserver;
}

function nv_add_customer($customer, $customer_group)
{
    global $module_data, $db, $array_config;
    
    $_sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_customer(fullname, gender, birthday, email, phone, groups, addtime) VALUES(:fullname, :gender, :birthday, :email, :phone, :groups, ' . NV_CURRENTTIME . ')';
    $data_insert = array();
    $data_insert['fullname'] = $customer['fullname'];
    $data_insert['gender'] = $customer['gender'];
    $data_insert['birthday'] = $customer['birthday'];
    $data_insert['email'] = empty($customer['email']) ? $customer['phone'] . '@gmail.com' : $customer['email'];
    $data_insert['phone'] = $customer['phone'];
    $data_insert['groups'] = implode(',', $customer_group);
    $new_id = $db->insert_id($_sql, 'id', $data_insert);
    
    if (!empty($new_id)) {
        $sth = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_customer_groups (customerid, groupid) VALUES(:customerid, :groupid)');
        foreach ($customer_group as $groupid) {
            $sth->bindParam(':customerid', $new_id, PDO::PARAM_INT);
            $sth->bindParam(':groupid', $groupid, PDO::PARAM_INT);
            $sth->execute();
        }
        return $new_id;
    }
}

function nv_listphone_content($rowid, $ignoredie = 1, $ignoredeclined = 1)
{
    global $db, $module_data, $lang_module, $nv_Cache, $module_name;
    
    $array_phone = $array_phone_tmp = array();
    $rows = $db->query('SELECT customergroup, phonelist FROM ' . NV_PREFIXLANG . '_' . $module_data . '_smsrows WHERE id=' . $rowid)->fetch();
    if (!$rows) {
        return $array_phone;
    }
    
    // Danh sach phone loi
    $array_phone_die = array();
    if ($ignoredie) {
        $result = $db->query('SELECT phone FROM ' . NV_PREFIXLANG . '_' . $module_data . '_customer WHERE is_die=1');
        while (list ($phone) = $result->fetch(3)) {
            $array_phone_die[] = $phone;
        }
        
        $result = $db->query('SELECT mobile FROM ' . NV_PREFIXLANG . '_' . $module_data . '_diemobile');
        while (list ($mobile) = $result->fetch(3)) {
            $array_phone_die[] = $mobile;
        }
        
        $array_phone_die = array_unique($array_phone_die);
    }
    if (!empty($rows['customergroup'])) {
        $rows['customergroup'] = unserialize($rows['customergroup']);
        $result = $db->query('SELECT customerid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_customer_groups WHERE groupid IN (' . implode(',', $rows['customergroup']) . ')');
        $where = $ignoredie ? ' AND is_die=0' : '';
        $where .= $ignoredeclined ? ' AND is_declined=0' : '';
        while (list ($customerid) = $result->fetch(3)) {
            $customer_info = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_customer WHERE status=1 AND id=' . $customerid . $where)->fetch();
            if ($customer_info) {
                if (!in_array($customer_info['phone'], $array_phone_tmp)) {
                    $array_phone[] = array(
                        'customerid' => $customer_info['id'],
                        'email' => $customer_info['email'],
                        'phone' => $customer_info['phone'],
                        'fullname' => $customer_info['fullname'],
                        'gender' => $customer_info['gender'],
                        'birthday' => $customer_info['birthday'],
                        'is_check' => 0
                    );
                    $array_phone_tmp[] = $customer_info['phone'];
                }
            }
        }
    }
    
    if (!empty($rows['phonelist'])) {
        $rows['phonelist'] = explode('<br />', $rows['phonelist']);
        if (!empty($rows['phonelist'])) {
            foreach ($rows['phonelist'] as $phone) {
                if (in_array($phone, $array_phone_tmp) or in_array($phone, $array_phone_die)) {
                    continue;
                }
                $array_phone[] = array(
                    'customerid' => 0,
                    'phone' => $phone,
                    'fullname' => '',
                    'gender' => 2,
                    'birthday' => 0
                );
                $array_phone_tmp[] = $phone;
            }
        }
    }
    
    unset($array_phone_tmp);
    
    return $array_phone;
}