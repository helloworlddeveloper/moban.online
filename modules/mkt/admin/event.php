<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}


if( $nv_Request->isset_request('change_weight', 'post', 0) ){

    $id = $nv_Request->get_int('id', 'post', 0);

    $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_listevents WHERE id=' . $id;
    $id = $db->query($sql)->fetchColumn();
    if (empty($id)) {
        die('NO_' . $id);
    }

    $new_weight = $nv_Request->get_int('new_weight', 'post', 0);
    if (empty($new_weight)) {
        die('NO_' . $mod);
    }

    $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_listevents WHERE id!=' . $id . ' ORDER BY weight ASC';
    $result = $db->query($sql);

    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        if ($weight == $new_weight) {
            ++$weight;
        }

        $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_listevents SET weight=' . $weight . ' WHERE id=' . $row['id'];
        $db->query($sql);
    }

    $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_listevents SET weight=' . $new_weight . ' WHERE id=' . $id;
    $db->query($sql);

    $nv_Cache->delMod($module_name);

    include NV_ROOTDIR . '/includes/header.php';
    echo 'OK_' . $id;
    include NV_ROOTDIR . '/includes/footer.php';
    exit();
}else if( $nv_Request->isset_request('change_status', 'post', 0) ){

    $id = $nv_Request->get_int('id', 'post', 0);

    $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_listevents WHERE id=' . $id;
    $id = $db->query($sql)->fetchColumn();
    if (empty($id)) {
        die('NO_' . $id);
    }

    $new_status = $nv_Request->get_bool('new_status', 'post');
    $new_status = ( int )$new_status;

    $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_listevents SET status=' . $new_status . ' WHERE id=' . $id;
    $db->query($sql);
    $nv_Cache->delMod($module_name);

    include NV_ROOTDIR . '/includes/header.php';
    echo 'OK_' . $id;
    include NV_ROOTDIR . '/includes/footer.php';
    exit();
}else if( $nv_Request->isset_request('del', 'post', 0) ){
    $id = $nv_Request->get_int('id', 'post', 0);
    $checkss = $nv_Request->get_string('checkss', 'post', '');

    if (md5($id . NV_CHECK_SESSION) == $checkss) {
        $content = 'NO_' . $id;

        $sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_listevents WHERE id = ' . $id;
        if ($db->exec($sql)) {
            nv_insert_logs(NV_LANG_DATA, $module_name, 'Delete possiton', 'ID: ' . $id, $admin_info['userid']);

            $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_listevents ORDER BY weight ASC';
            $result = $db->query($sql);
            $weight = 0;
            while ($row = $result->fetch()) {
                ++$weight;
                $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_listevents SET weight=' . $weight . ' WHERE id=' . $row['id'];
                $db->query($sql);
            }
            $nv_Cache->delMod($module_name);

            $content = 'OK_' . $id;
        }
    } else {
        $content = 'ERR_' . $id;
    }
    die($content);
}
$action = '';
$num_items = 0;
$id = $nv_Request->get_int('id', 'post,get', 0);
if( $nv_Request->isset_request('add', 'get' ) || $id > 0 ){

    if ($id) {
        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_listevents WHERE id=' . $id;
        $row = $db->query($sql)->fetch();

        if (empty($row)) {
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
        }

        $page_title = $lang_module['edit_event'];
        $action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&add=1&amp;id=' . $id;
    } else {
        $page_title = $lang_module['add_events'];
        $action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&add=1';
    }

    $error = '';

    if ($nv_Request->isset_request('submit', 'post')) {
        $row['title'] = $nv_Request->get_title('title', 'post', '', 1);
        $row['addressevent'] = $nv_Request->get_title('addressevent', 'post', '', 1);
        $row['contactname'] = $nv_Request->get_title('contactname', 'post', '', 1);
        $row['contactmobile'] = $nv_Request->get_title('contactmobile', 'post', '', 1);
        $row['description'] = $nv_Request->get_editor('description', '', NV_ALLOWED_HTML_TAGS);

        $row['provinceid'] = $nv_Request->get_int('provinceid', 'post',0);

        if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string('timeevent', 'post'), $m)) {
            $_hour = $nv_Request->get_int('timeevent_hour', 'post',0);
            $_min = $nv_Request->get_int('timeevent_minute', 'post',0);
            $row['timeevent'] = mktime($_hour, $_min, 59, $m[2], $m[1], $m[3]);
        } else {
            $row['timeevent'] = 0;
        }

        if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string('timeclose', 'post'), $m)) {
            $_hour = $nv_Request->get_int('timeclose_hour', 'post',0);
            $_min = $nv_Request->get_int('timeclose_minute', 'post',0);
            $row['timeclose'] = mktime($_hour, $_min, 59, $m[2], $m[1], $m[3]);
        } else {
            $row['timeclose'] = 0;
        }

        if (empty($row['title'])) {
            $error = $lang_module['empty_title_event'];
        }elseif (empty($row['contactname'])) {
            $error = $lang_module['empty_contactname'];
        }elseif (empty($row['contactmobile'])) {
            $error = $lang_module['empty_contactmobile'];
        }elseif (empty($row['addressevent'])) {
            $error = $lang_module['empty_addressevent'];
        }elseif ($row['timeevent'] == 0) {
            $error = $lang_module['empty_timeevent'];
        } elseif ($row['provinceid'] == 0) {
            $error = $lang_module['empty_provinceid'];
        } else {
            if ($id) {
                $_sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_listevents SET 
                 title=:title, description=:description, contactname=:contactname, contactmobile=:contactmobile, addressevent=:addressevent, timeevent=:timeevent, provinceid=:provinceid, timeclose=:timeclose
                 WHERE id =' . $id;

            } else {

                $weight = $db->query("SELECT MAX(weight) FROM " . NV_PREFIXLANG . "_" . $module_data . '_listevents')->fetchColumn();
                $weight = intval($weight) + 1;

                $_sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_listevents
				(title, description, contactname, contactmobile, addressevent, timeevent, provinceid, timeclose, weight, status, addtime) VALUES
				(:title, :description, :contactname, :contactmobile, :addressevent, :timeevent, :provinceid, :timeclose, ' . $weight . ', 1, ' . NV_CURRENTTIME . ')';
            }

            try {
                $sth = $db->prepare($_sql);
                $sth->bindParam(':title', $row['title'], PDO::PARAM_STR);
                $sth->bindParam(':description', $row['description'], PDO::PARAM_STR, strlen( $row['description'] ));
                $sth->bindParam(':contactname', $row['contactname'], PDO::PARAM_STR);
                $sth->bindParam(':contactmobile', $row['contactmobile'], PDO::PARAM_STR);
                $sth->bindParam(':addressevent', $row['addressevent'], PDO::PARAM_STR);
                $sth->bindParam(':timeevent', $row['timeevent'], PDO::PARAM_INT);
                $sth->bindParam(':provinceid', $row['provinceid'], PDO::PARAM_INT);
                $sth->bindParam(':timeclose', $row['timeclose'], PDO::PARAM_INT);

                $sth->execute();

                if ($sth->rowCount()) {
                    $nv_Cache->delMod($module_name);
                    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
                } else {
                    $error = $lang_module['errorsave'];
                }
            } catch (PDOException $e) {
                $error = $e->getMessage();
            }
        }
    }

}else{

    $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

    $per_page = 30;
    $page = $nv_Request->get_int( 'page', 'post,get', 1 );
    $provinceid = $nv_Request->get_int( 'provinceid', 'post,get', 0 );
    $db->sqlreset()->select( 'COUNT(*)' )->from( '' . NV_PREFIXLANG . '_' . $module_data . '_listevents' );
    $sql_where = '';
    if( $provinceid > 0 )
    {
        $sql_where .= ( ! empty( $sql_where ) ) ? ' AND provinceid=' . $provinceid : 'provinceid=' . $provinceid;
        $base_url .= '&provinceid=' . $provinceid;
    }
    $db->where( $sql_where );

    $sth = $db->prepare( $db->sql() );

    $sth->execute();
    $num_items = $sth->fetchColumn();
    if ($num_items < 1) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&add=1');
    }
}
if ($row['timeevent'] > 0) {
    $row['timeeventf'] = date('d/m/Y', $row['timeevent']);
}
if ($row['timeclose'] > 0) {
    $row['timeclosef'] = date('d/m/Y', $row['timeclose']);
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('FORM_ACTION', $action);
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'MODULE_FILE', $module_file );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign('DATA', $row);
$xtpl->assign('add_events', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&add=1');
if( $num_items> 0 ){

    $sql = 'SELECT * FROM ' . NV_TABLE_AFFILIATE_LANG . '_province WHERE status=1 ORDER BY weight ASC ';
    $array_province = $nv_Cache->db($sql, 'id', $module_name);
    foreach( $array_province as $data )
    {
        $data['selected'] = ( $data['id'] == $provinceid ) ? ' selected="selected"' : '';
        $xtpl->assign( 'OPTION', $data );
        $xtpl->parse( 'main.data.province_select' );
    }


    $array_status = array(
        $lang_module['inactive'],
        $lang_module['active']
    );
    $db->select( '*' )->order( 'timeevent DESC' )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );
    $sth = $db->prepare( $db->sql() );

    $sth->execute();

    $xtpl->assign( 'NV_GENERATE_PAGE', nv_generate_page( $base_url, $num_items, $per_page, $page ) );
    while( $row = $sth->fetch() )
    {
        $row['url_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $row['id'];
        $row['url_sms'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=list-sms&amp;eventid=' . $row['id'];
        $row['checkss'] = md5($row['id'] . NV_CHECK_SESSION);

        foreach ($array_status as $key => $val) {
            $xtpl->assign('STATUS', array(
                'key' => $key,
                'val' => $val,
                'selected' => ($key == $row['status']) ? ' selected="selected"' : ''
            ));

            $xtpl->parse('main.data.row.status');
        }
        $row['provinceid'] = isset( $array_province[$row['provinceid']] )? $array_province[$row['provinceid']]['title'] : 'N/A';
        $row['timeevent'] = date('d/m/Y H:i', $row['timeevent'] );
        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.data.row');
    }
    $xtpl->parse('main.data');
}else{
    if ($error) {
        $xtpl->assign('ERROR', $error);
        $xtpl->parse('main.add.error');
    }

    $hour = !empty($row['timeevent']) ? date('H', $row['timeevent']) : 0;
    for ($i = 0; $i <= 23; $i++) {
        $sl = $i == $hour ? 'selected="selected"' : '';
        $xtpl->assign('HOUR', array(
            'index' => $i,
            'selected' => $sl
        ));
        $xtpl->parse('main.add.timeevent_hour');
    }

    $min = !empty($row['timeevent']) ? date('i', $row['timeevent']) : 0;
    for ($i = 0; $i <= 59; $i+=30) {
        $sl = $i == $min ? 'selected="selected"' : '';
        $xtpl->assign('MINUTE', array(
            'index' => $i,
            'selected' => $sl
        ));
        $xtpl->parse('main.add.timeevent_minute');
    }
    $hour = !empty($row['timeclose']) ? date('H', $row['timeclose']) : 0;
    for ($i = 0; $i <= 23; $i++) {
        $sl = $i == $hour ? 'selected="selected"' : '';
        $xtpl->assign('HOUR', array(
            'index' => $i,
            'selected' => $sl
        ));
        $xtpl->parse('main.add.timeclose_hour');
    }

    $min = !empty($row['timeclose']) ? date('i', $row['timeclose']) : 0;
    for ($i = 0; $i <= 59; $i++) {
        $sl = $i == $min ? 'selected="selected"' : '';
        $xtpl->assign('MINUTE', array(
            'index' => $i,
            'selected' => $sl
        ));
        $xtpl->parse('main.add.timeclose_minute');
    }

    $sql = 'SELECT * FROM ' . NV_TABLE_AFFILIATE_LANG . '_province WHERE status=1 ORDER BY weight ASC ';
    $array_province = $nv_Cache->db($sql, 'id', $module_name);
    foreach ( $array_province as $province ){
        $province['sl'] = ($province['id'] == $row['provinceid'])? ' selected=selected' : '';
        $xtpl->assign('PROVINCE', $province);
        $xtpl->parse('main.add.province');
    }

    if (defined('NV_EDITOR')) require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
    $row['description'] = htmlspecialchars(nv_editor_br2nl($row['description']));
    if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
        $description = nv_aleditor('description', '100%', '300px', $row['description']);
    } else {
        $description = '<textarea style="width:100%;height:300px" name="description">' . $row['description'] . '</textarea>';
    }
    $xtpl->assign('description', $description);
    $xtpl->parse('main.add');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
