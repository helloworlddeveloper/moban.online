<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES (kid.apt@gmail.com)
 * @Copyright(C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/05/2010
 */
if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $table_caption = $lang_module['main_wallet'];

if ($nv_Request->isset_request('changestatus', 'post')) {
    $userid = $nv_Request->get_int('userid', 'post', 0);
    
    $sql = 'SELECT userid, status FROM ' . $db_config['prefix'] . "_" . $module_data . '_money WHERE userid=' . $userid;
    $row = $db->query($sql)->fetch();
    if (! empty($row)) {
        
        $status = $row['status'] ? 0 : 1;
        $sql = 'UPDATE ' . $db_config['prefix'] . "_" . $module_data . '_money SET status=' . $status . ' WHERE userid=' . $userid;
        $result = $db->query($sql);
        
        $note = ($status) ? $lang_module['active_users'] : $lang_module['unactive_users'];
        nv_insert_logs(NV_LANG_DATA, $module_name, $note, 'userid: ' . $userid, $admin_info['userid']);
        echo 'OK';
    }
    exit();
}

$usactive = ($global_config['idsite']) ? 3 : - 1;
$usactive_old = $nv_Request->get_int('usactive', 'cookie', $usactive);
$usactive = $nv_Request->get_int('usactive', 'post,get', $usactive_old);
$method = $nv_Request->isset_request('method', 'post') ? $nv_Request->get_string('method', 'post', '') : ($nv_Request->isset_request('method', 'get') ? urldecode($nv_Request->get_string('method', 'get', '')) : '');

if ($usactive_old != $usactive) {
    $nv_Request->set_Cookie('usactive', $usactive);
}
$_arr_where = array();
if ($usactive == - 3) {
    $_arr_where[] = 'group_id!=7';
} elseif ($usactive == - 2) {
    $_arr_where[] = 'group_id=7';
} else {
    if ($usactive > - 1) {
        $_arr_where[] = 'active=' . ($usactive % 2);
    }
    if ($usactive > 1) {
        $_arr_where[] = '(idsite=' . $global_config['idsite'] . ' OR t1.userid = ' . $admin_info['admin_id'] . ')';
    }
}

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&usactive=' . $usactive;

$methods = array(
    'userid' => array(
        'key' => 'userid',
        'sql' => 't1.userid',
        'value' => $lang_module['search_id'],
        'selected' => ''
    ),
    'username' => array(
        'key' => 'username',
        'sql' => 'username',
        'value' => $lang_module['search_account'],
        'selected' => ''
    ),
    'fullname' => array(
        'key' => 'fullname',
        'sql' => $global_config['name_show'] == 0 ? "concat(last_name,' ',first_name)" : "concat(first_name,' ',last_name)",
        'value' => $lang_module['search_name'],
        'selected' => ''
    ),
    'email' => array(
        'key' => 'email',
        'sql' => 'email',
        'value' => $lang_module['search_mail'],
        'selected' => ''
    )
);

$methodvalue = $nv_Request->isset_request('value', 'post') ? $nv_Request->get_string('value', 'post') : ($nv_Request->isset_request('value', 'get') ? urldecode($nv_Request->get_string('value', 'get', '')) : '');

$orders = array(
    't1.userid',
    't1.username',
    't1.email',
    't2.money_out',
    't2.money_in',
    't2.money'
);
$orderby = $nv_Request->get_string('sortby', 'get', 'userid');
$ordertype = $nv_Request->get_string('sorttype', 'get', 'DESC');
if ($ordertype != 'ASC') {
    $ordertype = 'DESC';
}
$method = (! empty($method) and isset($methods[$method])) ? $method : '';

if (! empty($methodvalue)) {
    if (empty($method)) {
        $array_like = array();
        foreach ($methods as $method_i) {
            $array_like[] = $method_i['sql'] . " LIKE '%" . $db->dblikeescape($methodvalue) . "%'";
        }
        $_arr_where[] = '(' . implode(' OR ', $array_like) . ')';
    } else {
        $_arr_where[] = " (" . $methods[$method]['sql'] . " LIKE '%" . $db->dblikeescape($methodvalue) . "%')";
        $methods[$method]['selected'] = ' selected="selected"';
    }
    $base_url .= '&amp;method=' . urlencode($method) . '&amp;value=' . urlencode($methodvalue);
    $table_caption = $lang_module['search_page_title'];
}

$page = $nv_Request->get_int('page', 'get', 1);
$per_page = 30;

$db->sqlreset()
    ->select('COUNT(*)')
    ->from(NV_USERS_GLOBALTABLE . ' AS t1')
    ->join('INNER JOIN ' . $db_config['prefix'] . "_" . $module_data . '_money AS t2 ON t1.userid=t2.userid');
if (! empty($_arr_where)) {
    $db->where(implode(' AND ', $_arr_where));
}

$num_items = $db->query($db->sql())
    ->fetchColumn();

$db->select('t1.userid, t1.first_name, t1.last_name, t1.username, t1.email, t2.money_in, t2.money_out, t2.money, t2.status')
    ->limit($per_page)
    ->offset(($page - 1) * $per_page);
if (! empty($orderby) and in_array($orderby, $orders)) {
    $orderby_sql = $orderby != 'full_name' ? $orderby : ($global_config['name_show'] == 0 ? "concat(t1.first_name,' ',t1.last_name)" : "concat(t1.last_name,' ',t1.first_name)");
    $db->order($orderby_sql . ' ' . $ordertype);
    $base_url .= '&amp;sortby=' . $orderby . '&amp;sorttype=' . $ordertype;
}

$result2 = $db->query($db->sql());

$users_list = array();
$admin_in = array();

while ($row = $result2->fetch()) {
    
    $users_list[$row['userid']] = array(
        'userid' => $row['userid'],
        'username' => $row['username'],
        'full_name' => nv_show_name_user($row['first_name'], $row['last_name'], $row['username']),
        'money_out' => nv_affiliate_number_format($row['money_out']),
        'money_in' => nv_affiliate_number_format($row['money_in']),
        'money' => nv_affiliate_number_format($row['money']),
        'email' => $row['email'],
        'checked' => $row['status'] ? ' checked="checked"' : '',
        'disabled' => ' onclick="nv_chang_status(' . $row['userid'] . ');"',
        'link_update' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=addacount&userid=' . $row['userid'],
        'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=transaction&userid=' . $row['userid']
    );
    $admin_in[] = $row['userid'];
}

if (! empty($admin_in)) {
    $admin_in = implode(',', $admin_in);
    $sql = 'SELECT admin_id, lev FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id IN (' . $admin_in . ')';
    $query = $db->query($sql);
    while ($row = $query->fetch()) {
        $users_list[$row['admin_id']]['is_delete'] = false;
        if ($row['lev'] == 1) {
            $users_list[$row['admin_id']]['level'] = $lang_global['level1'];
            $users_list[$row['admin_id']]['img'] = 'admin1';
        } elseif ($row['lev'] == 2) {
            $users_list[$row['admin_id']]['level'] = $lang_global['level2'];
            $users_list[$row['admin_id']]['img'] = 'admin2';
        } else {
            $users_list[$row['admin_id']]['level'] = $lang_global['level3'];
            $users_list[$row['admin_id']]['img'] = 'admin3';
        }
    }
}

$generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);

$head_tds = array();
$head_tds['t1.userid']['title'] = $lang_module['userid'];
$head_tds['t1.userid']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;sortby=t1.userid&amp;sorttype=ASC';
$head_tds['t1.username']['title'] = $lang_module['account'];
$head_tds['t1.username']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;sortby=t1.username&amp;sorttype=ASC';
$head_tds['t1.full_name']['title'] = $lang_module['full_name'];
$head_tds['t1.full_name']['href'] = 'javascript:void(0);';
$head_tds['t1.email']['title'] = $lang_module['email'];
$head_tds['t1.email']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;sortby=t1.email&amp;sorttype=ASC';

$head_tds['t2.money_in']['title'] = $lang_module['money_in'];
$head_tds['t2.money_in']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;sortby=t2.money_in&amp;sorttype=ASC';
$head_tds['t2.money_out']['title'] = $lang_module['money_out'];
$head_tds['t2.money_out']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;sortby=t2.money_out&amp;sorttype=ASC';
$head_tds['t2.money']['title'] = $lang_module['money'];
$head_tds['t2.money']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;sortby=t2.money&amp;sorttype=ASC';

foreach ($orders as $order) {
    if ($orderby == $order and $ordertype == 'ASC') {
        $head_tds[$order]['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;sortby=' . $order . '&amp;sorttype=DESC';
        $head_tds[$order]['title'] .= ' &darr;';
    } elseif ($orderby == $order and $ordertype == 'DESC') {
        $head_tds[$order]['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;sortby=' . $order . '&amp;sorttype=ASC';
        $head_tds[$order]['title'] .= ' &uarr;';
    }
}
$lang_module['note_export'] = sprintf( $lang_module['note_export'], number_format($module_config[$module_name]['min_payment'], 0, '.', ',') );
$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php');
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('SORTURL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
$xtpl->assign('SEARCH_VALUE', nv_htmlspecialchars($methodvalue));
$xtpl->assign('TABLE_CAPTION', $table_caption);

foreach ($methods as $m) {
    $xtpl->assign('METHODS', $m);
    $xtpl->parse('main.method');
}
$_bg = 1;
for ($i = 0; $i <= 1; $i++) {
    $m = array(
        'key' => $i,
        'selected' => ($i == $usactive) ? ' selected="selected"' : '',
        'value' => $lang_module['usactive_' . $i]
    );
    $xtpl->assign('USACTIVE', $m);
    $xtpl->parse('main.usactive');
}

foreach ($head_tds as $head_td) {
    $xtpl->assign('HEAD_TD', $head_td);
    $xtpl->parse('main.head_td');
}

foreach ($users_list as $u) {
    $xtpl->assign('CONTENT_TD', $u);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('NV_ADMIN_THEME', $global_config['admin_theme']);
    
    $xtpl->parse('main.xusers');
}

if (! empty($generate_page)) {
    $xtpl->assign('GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.generate_page');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
