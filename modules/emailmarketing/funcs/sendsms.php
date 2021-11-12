<?php

/**
 * @Project NUKEVIET 4.x
 * @Author mynukeviet (contact@mynukeviet.net)
 * @Copyright (C) 2016 mynukeviet. All rights reserved
 * @Createdate Sat, 15 Oct 2016 03:30:10 GMT
 */
if (!defined('NV_IS_MOD_EMAILMARKETING')) die('Stop!!!');

$mod = $nv_Request->get_title('mod', 'get', '');
if ($mod == 'queue') {
    
    set_time_limit(0);
    
    $result = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_smsqueue');
    while ($row = $result->fetch()) {
        
        $apikey = $module_config[$module_name]['apikey'];
        $secretkey = $module_config[$module_name]['secretkey'];
        $sms_type = $module_config[$module_name]['sms_type'];
        $content = urlencode($row['content']);
        
        $data = 'http://rest.esms.vn/MainService.svc/json/SendMultipleMessage_V4_get?Phone=' . $row['to_phone'] . '&ApiKey=' . $apikey . '&SecretKey=' . $secretkey . '&Content=' . $content . '&SmsType=' . $sms_type;
        $curl = curl_init($data);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        $result = curl_exec($curl);
        $obj = json_decode($result, true);
        
        if ($obj['CodeResult'] == '100') {
            $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_smsqueue WHERE id=' . $row['id']);
        }
    }
} elseif ($mod == 'campaign' and $array_config['allow_cronjobs']) {
    
    set_time_limit(0);
    
    $rows = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_smsrows WHERE sendstatus=0 AND typetime=1 AND begintime<=' . NV_CURRENTTIME)->fetch();
    if ($rows) {
        $rows['sendlist'] = !empty($rows['sendlist']) ? explode(',', $rows['sendlist']) : array();
        $rows['sendlist'] = array_map('intval', $rows['sendlist']);
        $rows['sendedlist'] = !empty($rows['sendedlist']) ? explode(',', $rows['sendedlist']) : array();
        $rows['sendedlist'] = array_map('intval', $rows['sendedlist']);
        $rows['errorlist'] = !empty($rows['errorlist']) ? explode(',', $rows['errorlist']) : array();
        $rows['errorlist'] = array_map('intval', $rows['errorlist']);
        $rows['openedlist'] = !empty($rows['openedlist']) ? explode(',', $rows['openedlist']) : array();
        $rows['openedlist'] = array_map('intval', $rows['openedlist']);
        
        if ($module_config[$module_name]['sms_on'] == 1) {
            $array_send = array_merge($rows['sendedlist'], $rows['errorlist']);
            $array_send = array_diff($rows['sendlist'], $array_send);
            asort($array_send);
            if (!empty($array_send)) {
                $i = 1;
                foreach ($array_send as $index => $customerid) {
                    if ($i <= $array_config['numsend']) {
                        $result = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_customer WHERE id=' . $customerid)->fetch();
                        if ($result) {
                            $customer = array(
                                'customerid' => $result['id'],
                                'nextcustomerid' => isset($array_send[$index + 1]) ? $array_send[$index + 1] : 0,
                                'phone' => $result['phone'],
                                'fullname' => $result['fullname'],
                                'gender' => $result['gender']
                            );
                            nv_sendphone_action($customer, $rows);
                            $i++;
                        }
                    } else {
                        break;
                    }
                }
            }
        }
        
        $lang_module['send'] = sprintf($lang_module['sendmail_s'], $rows['content']);
        
        // Cap nhat danh sach khach hang nhan mail
        if ($rows['sendstatus'] == 0) {
            $array_phone = nv_listphone_content($id);
            
            $sendlist = array();
            foreach ($array_phone as $data) {
                $sendlist[] = nv_check_customer_by_phone($data);
            }
            
            $count = count($sendlist);
            if ($rows['sendlist'] != $sendlist) {
                asort($sendlist);
                $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_smsrows SET sendlist=' . $db->quote(implode(',', $sendlist)) . ', totalsmssend = ' . $count . ' WHERE id=' . $id);
                $rows['sendlist'] = $sendlist;
            }
        }
        
        // Danh sach link trong noi dung
        $array_link = array();
        if ($rows['linkstatics']) {
            $result = $db->query('SELECT link, countclick FROM ' . NV_PREFIXLANG . '_' . $module_data . '_smsrows_link WHERE rowsid=' . $id);
            while ($_row = $result->fetch()) {
                $array_link[] = $_row;
            }
        }
    } else {
        die(json_encode(array(
            'status' => 'exit'
        )));
    }
} else {
    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    die();
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('TEMPLATE', $global_config['module_theme']);

$array_phone = array();
if (!empty($rows['sendlist'])) {
    $result = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_customer WHERE id IN(' . implode(',', $rows['sendlist']) . ') ORDER BY id');
    while ($_row = $result->fetch()) {
        $array_phone[] = $_row;
    }
}
if (!empty($array_phone)) {
    $number = 1;
    $firstcustomerid = 0;
    foreach ($array_phone as $data) {
        if ($number == 1) {
            $firstcustomerid = $data['id'];
        }
        $data['number'] = $number++;
        if (in_array($data['id'], $rows['sendedlist'])) {
            $data['sendstatus'] = 1;
        } elseif (in_array($data['id'], $rows['errorlist'])) {
            $data['sendstatus'] = 3;
        } else {
            $data['sendstatus'] = 0;
        }
        $data['sendstatus_str'] = $lang_module['sendstatus_' . $data['sendstatus']];
        $xtpl->assign('DATA', $data);
        
        if ($data['sendstatus'] == 1) {
            $xtpl->parse('main.loop.sendsuccess');
        } elseif ($data['sendstatus'] == 3) {
            $xtpl->parse('main.loop.senderror');
        }
        
        $xtpl->parse('main.loop');
    }
    $xtpl->assign('ROWSID', $id);
    $xtpl->assign('TOTAL', count($array_phone));
    $xtpl->assign('TOTALSENDER', count($rows['sendedlist']));
    $xtpl->assign('PERCENT', (count($rows['sendedlist']) * 100) / count($array_phone));
    $xtpl->assign('FIRSTCUSTOMERID', $firstcustomerid);
    $xtpl->assign('COUNTSUCCESS', count($rows['sendedlist']));
    $xtpl->assign('COUNTERROR', count($rows['errorlist']));
    
    if (!empty($rows['sendedlist']) and $rows['sendstatus'] == 1) {
        $nextcustomerid = end($rows['sendedlist']);
        $nextcustomerid = array_search($nextcustomerid, $rows['sendlist']);
        $nextcustomerid = isset($rows['sendlist'][$nextcustomerid + 1]) ? $rows['sendlist'][$nextcustomerid + 1] : 'undefined';
    } else {
        $nextcustomerid = 'undefined';
    }
    $xtpl->assign('NEXTCUSTOMERID', $nextcustomerid);
    
    if ($rows['sendstatus'] == 0) {
        $xtpl->parse('main.btn_control');
    }
    
    if ($rows['openstatics']) {
        $xtpl->parse('main.openstatics');
    }
    
    if ($rows['linkstatics'] and !empty($array_link)) {
        $number = 1;
        foreach ($array_link as $index => $link) {
            $link['number'] = $number++;
            $link['index'] = $index;
            $xtpl->assign('LINK', $link);
            $xtpl->parse('main.linkstatics.loop');
        }
        $xtpl->parse('main.linkstatics');
    }
}

$array_button = array(
    'edit' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=contentsms&amp;id=' . $rows['id'] . '&amp;redirect=' . nv_redirect_encrypt($client_info['selfurl']),
    'delete' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=main&amp;delete_id=' . $rows['id'] . '&amp;delete_checkss=' . md5($rows['id'] . NV_CACHE_PREFIX . $client_info['session_id'])
);
$xtpl->assign('BUTTON', $array_button);

if ($rows['sendstatus'] == 1) {
    $xtpl->parse('main.sendstatus_disabled');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$set_active_op = 'content';
$page_title = $lang_module['send'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';