<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2018 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Fri, 12 Jan 2018 07:59:54 GMT
 */

if (!defined('NV_IS_MOD_ITINERARY')) die('Stop!!!');

if ($nv_Request->isset_request('delete_id', 'get') and $nv_Request->isset_request('delete_checkss', 'get')) {
    $id = $nv_Request->get_int('delete_id', 'get');
    $delete_checkss = $nv_Request->get_string('delete_checkss', 'get');
    if ($id > 0 and $delete_checkss == md5($id . NV_CACHE_PREFIX . $client_info['session_id'])) {
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '  WHERE id = ' . $db->quote($id));
        $nv_Cache->delMod($module_name);
        Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
        die();
    }
}

$row = array();
$error = array();
$row['id'] = $nv_Request->get_int('id', 'post,get', 0);
if ($nv_Request->isset_request('submit', 'post')) {
    $row['title_itinerary'] = $nv_Request->get_title('title_itinerary', 'post', '');
    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string('date_start', 'post'), $m)) {
        $begintime = $nv_Request->get_string('time_start', 'post');
        $begintime = explode(':', $begintime);
        $row['time_start'] = mktime($begintime[0], $begintime[1], 0, $m[2], $m[1], $m[3]);
    } else {
        $row['time_start'] = 0;
    }
    
    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string('date_end', 'post'), $m)) {
        $exptime = $nv_Request->get_string('time_end', 'post');
        $exptime = explode(':', $exptime);
        $row['time_end'] = mktime($exptime[0], $exptime[1], 59, $m[2], $m[1], $m[3]);
    } else {
        $row['time_end'] = 0;
    }
    
    $row['localtion_start'] = $nv_Request->get_title('localtion_start', 'post', '');
    $row['localtion_end'] = $nv_Request->get_title('localtion_end', 'post', '');
    $row['vehicle'] = $nv_Request->get_int('vehicle', 'post', '');

    if (empty($row['title_itinerary'])) {
        $error[] = $lang_module['error_required_title_itinerary'];
    } elseif (empty($row['time_start'])) {
        $error[] = $lang_module['error_required_time_start'];
    } elseif (empty($row['time_end'])) {
        $error[] = $lang_module['error_required_time_end'];
    } elseif (empty($row['localtion_start'])) {
        $error[] = $lang_module['error_required_localtion_start'];
    } elseif (empty($row['localtion_end'])) {
        $error[] = $lang_module['error_required_localtion_end'];
    } elseif (empty($row['vehicle'])) {
        $error[] = $lang_module['error_required_vehicle'];
    }
    
    if (empty($error)) {
        try {
            if (empty($row['id'])) {
                $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . ' (title_itinerary, time_start, time_end, localtion_start, localtion_end, vehicle ) VALUES (:title_itinerary, :time_start, :time_end, :localtion_start, :localtion_end, :vehicle)');
            } else {
                $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET title_itinerary = :title_itinerary, time_start = :time_start, time_end = :time_end, localtion_start = :localtion_start, localtion_end = :localtion_end, vehicle = :vehicle WHERE id=' . $row['id']);
            }
            $stmt->bindParam(':title_itinerary', $row['title_itinerary'], PDO::PARAM_STR);
            $stmt->bindParam(':time_start', $row['time_start'], PDO::PARAM_INT);
            $stmt->bindParam(':time_end', $row['time_end'], PDO::PARAM_INT);
            $stmt->bindParam(':localtion_start', $row['localtion_start'], PDO::PARAM_STR);
            $stmt->bindParam(':localtion_end', $row['localtion_end'], PDO::PARAM_STR);
            $stmt->bindParam(':vehicle', $row['vehicle'], PDO::PARAM_STR);

            $exc = $stmt->execute();
            if ($exc) {
                $nv_Cache->delMod($module_name);
                Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
                die();
            }
        } catch (PDOException $e) {
            trigger_error($e->getMessage());
            die($e->getMessage()); //Remove this line after checks finished
        }
    }
} elseif ($row['id'] > 0) {
    $row = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $row['id'])->fetch();
    if (empty($row)) {
        Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
        die();
    }
} else {
    $row['id'] = 0;
    $row['title_itinerary'] = '';
    $row['time_start'] = 0;
    $row['time_end'] = 0;
    $row['localtion_start'] = '';
    $row['localtion_end'] = '';
    $row['vehicle'] = '';
}

if (empty($row['time_start'])) {
    $row['time_start'] = '';
} else {
    $row['date_start'] = date('d/m/Y', $row['time_start']);
    $row['time_start'] = date('h:i', $row['time_start']);
}

if (empty($row['time_end'])) {
    $row['time_end'] = '';
} else {
    $row['date_end'] = date('d/m/Y', $row['time_end']);
    $row['time_end'] = date('H:i', $row['time_end']);
}


$array_localtion = array();
$_sql = 'SELECT id,title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_location';
$_query = $db->query($_sql);
while ($_row = $_query->fetch()) {
    $array_localtion[$_row['id']] = $_row;
}

$array_vehicle_itinerary = array();
$_sql = 'SELECT id,car_number_plate FROM ' . NV_PREFIXLANG . '_' . $module_data . '_vehicle';
$_query = $db->query($_sql);
while ($_row = $_query->fetch()) {
    $array_vehicle_itinerary[$_row['id']] = $_row;
}

$q = $nv_Request->get_title('q', 'post,get');

// Fetch Limit
$show_view = false;
if (!$nv_Request->isset_request('id', 'post,get')) {
    $show_view = true;
    $per_page = 20;
    $page = $nv_Request->get_int('page', 'post,get', 1);
    $db->sqlreset()
        ->select('COUNT(*)')
        ->from( NV_PREFIXLANG . '_' . $module_data );
    
    if (!empty($q)) {
        $db->where('title_itinerary LIKE :q_title_itinerary OR time_start LIKE :q_time_start OR time_end LIKE :q_time_end OR localtion_start LIKE :q_localtion_start OR localtion_end LIKE :q_localtion_end OR vehicle LIKE :q_vehicle');
    }
    $sth = $db->prepare($db->sql());
    
    if (!empty($q)) {
        $sth->bindValue(':q_title_itinerary', '%' . $q . '%');
        $sth->bindValue(':q_time_start', '%' . $q . '%');
        $sth->bindValue(':q_time_end', '%' . $q . '%');
        $sth->bindValue(':q_localtion_start', '%' . $q . '%');
        $sth->bindValue(':q_localtion_end', '%' . $q . '%');
        $sth->bindValue(':q_vehicle', '%' . $q . '%');
    }
    $sth->execute();
    $num_items = $sth->fetchColumn();
    
    $db->select('*')
        ->order('id DESC')
        ->limit($per_page)
        ->offset(($page - 1) * $per_page);
    $sth = $db->prepare($db->sql());
    
    if (!empty($q)) {
        $sth->bindValue(':q_title_itinerary', '%' . $q . '%');
        $sth->bindValue(':q_time_start', '%' . $q . '%');
        $sth->bindValue(':q_time_end', '%' . $q . '%');
        $sth->bindValue(':q_localtion_start', '%' . $q . '%');
        $sth->bindValue(':q_localtion_end', '%' . $q . '%');
        $sth->bindValue(':q_vehicle', '%' . $q . '%');
    }
    $sth->execute();
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('MODULE_UPLOAD', $module_upload);
$xtpl->assign('OP', $op);
$xtpl->assign('ROW', $row);

foreach ($array_localtion as $value) {
    $xtpl->assign('OPTION', array(
        'key' => $value['id'],
        'title' => $value['title'],
        'selected' => ($value['id'] == $row['localtion_start']) ? ' selected="selected"' : ''
    ));
    $xtpl->parse('main.select_localtion_start');
}
foreach ($array_localtion as $value) {
    $xtpl->assign('OPTION', array(
        'key' => $value['id'],
        'title' => $value['title'],
        'selected' => ($value['id'] == $row['localtion_end']) ? ' selected="selected"' : ''
    ));
    $xtpl->parse('main.select_localtion_end');
}
foreach ($array_vehicle_itinerary as $value) {
    $xtpl->assign('OPTION', array(
        'key' => $value['id'],
        'title' => $value['car_number_plate'],
        'selected' => ($value['id'] == $row['vehicle']) ? ' selected="selected"' : ''
    ));
    $xtpl->parse('main.select_vehicle');
}
$xtpl->assign('Q', $q);

if ($show_view) {
    $base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
    if (!empty($q)) {
        $base_url .= '&q=' . $q;
    }
    $generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);
    if (!empty($generate_page)) {
        $xtpl->assign('NV_GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.view.generate_page');
    }
    $number = $page > 1 ? ($per_page * ($page - 1)) + 1 : 1;
    while ($view = $sth->fetch()) {
        $view['number'] = $number++;
        $view['time_start'] = (empty($view['time_start'])) ? '' : nv_date('H:i d/m/Y', $view['time_start']);
        $view['time_end'] = (empty($view['time_end'])) ? '' : nv_date('H:i d/m/Y', $view['time_end']);
        $view['localtion_start'] = $array_localtion[$view['localtion_start']]['title'];
        $view['localtion_end'] = $array_localtion[$view['localtion_end']]['title'];
        $view['vehicle'] = $array_vehicle_itinerary[$view['vehicle']]['car_number_plate'];
        $view['link_view'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=detail&amp;id=' . $view['id'];
        $view['link_edit'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $view['id'];
        $view['link_delete'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_id=' . $view['id'] . '&amp;delete_checkss=' . md5($view['id'] . NV_CACHE_PREFIX . $client_info['session_id']);
        $xtpl->assign('VIEW', $view);
        $xtpl->parse('main.view.loop');
    }
    $xtpl->parse('main.view');
}

if (!empty($error)) {
    $xtpl->assign('ERROR', implode('<br />', $error));
    $xtpl->parse('main.error');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['main'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';