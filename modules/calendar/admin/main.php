<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 18 Nov 2014 04:27:19 GMT
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );



$action = $nv_Request->get_title( 'action', 'get', '' );

if( $nv_Request->isset_request( 'delete_id', 'get' ) and $nv_Request->isset_request( 'delete_checkss', 'get' ) )
{
    $id = $nv_Request->get_int( 'delete_id', 'get' );
    $delete_checkss = $nv_Request->get_string( 'delete_checkss', 'get' );
    if( $id > 0 and $delete_checkss == md5( $id . NV_CACHE_PREFIX . $client_info['session_id'] ) )
    {
        $db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id = ' . $db->quote( $id ) );
        Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
        die();
    }
}

if( $nv_Request->isset_request( 'checktime', 'post' ) ) {

    $row['timeevent'] = 0;
    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string('timeevent', 'post'), $m)) {
        $row['timeevent'] = mktime(0, 0, 59, $m[2], $m[1], $m[3]);
    }
    $_hour = $nv_Request->get_int('timeevent_hour', 'post', 0);
    $_min = $nv_Request->get_int('timeevent_minute', 'post', 0);

    $_hour = sprintf("%02d", $_hour);;
    $_min = sprintf("%02d", $_min);;
    $hour_minute_begin = $_hour . $_min;

    $_hour = $nv_Request->get_int('timeclose_hour', 'post', 0);
    $_min = $nv_Request->get_int('timeclose_minute', 'post', 0);
    $_hour = sprintf("%02d", $_hour);;
    $_min = sprintf("%02d", $_min);;
    $hour_minute_end = $_hour . $_min;
    $row['calendarid'] = $nv_Request->get_int('calendarid', 'post', 0);
    $row['catid'] = $nv_Request->get_int('catid', 'post', 0);
    $row['groupid'] = $nv_Request->get_int('groupid', 'post', 0);
    $row['day_week'] = date('N', $row['timeevent']);//thu trong tuan 1 = monday, 7 sunday

    $sql_check = ' WHERE id!=' . $row['calendarid'] . ' AND ( ' . $hour_minute_begin . ' <  hour_minute_end) AND ( ' . $hour_minute_end . ' > hour_minute_begin) AND timefix=1 AND day_week=' . $row['day_week'] . ' AND catid=' . $row['catid'] . ' AND groupid=' . $row['groupid'];
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . $sql_check;

    $result = $db->query($sql);
    $data_using = $result->fetch();
    if( !empty( $data_using )){
        echo sprintf( $lang_module['error_exits_calendar_fix'], $data_using['title'], date('H:i', $data_using['timeevent_begin']), date('H:i', $data_using['timeevent_end']), $data_using['addressevent'], date('d/m/Y', $row['timeevent']) );
    }
    exit('');
}


$row = array();
$error = array();
$row['id'] = $nv_Request->get_int( 'id', 'post,get', 0 );


if ($nv_Request->isset_request('submit', 'post')) {
    $row['title'] = $nv_Request->get_title('title', 'post', '', 1);
    $row['moderator'] = $nv_Request->get_title('moderator', 'post', '', 1);
    $row['participants'] = $nv_Request->get_title('participants', 'post', '', 1);
    $row['alias'] = strtolower( change_alias( $row['title'] ));
    $row['addressevent'] = $nv_Request->get_title('addressevent', 'post', '', 1);
    $row['description'] = $nv_Request->get_editor('description', '', NV_ALLOWED_HTML_TAGS);

    $row['catid'] = $nv_Request->get_int('catid', 'post',0);
    $row['groupid'] = $nv_Request->get_int('groupid', 'post',0);
    $row['provinceid'] = $nv_Request->get_int('provinceid', 'post',0);
    $row['timefix'] = $nv_Request->get_int('timefix', 'post',0);
    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string('timeevent', 'post'), $m)) {
        $_hour = $nv_Request->get_int('timeevent_hour', 'post',0);
        $_min = $nv_Request->get_int('timeevent_minute', 'post',0);
        $row['timeevent'] = mktime($_hour, $_min, 59, $m[2], $m[1], $m[3]);
    } else {
        $row['timeevent'] = 0;
    }

    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string('timeevent', 'post'), $m)) {
        $_hour = $nv_Request->get_int('timeclose_hour', 'post',0);
        $_min = $nv_Request->get_int('timeclose_minute', 'post',0);
        $row['timeclose'] = mktime($_hour, $_min, 59, $m[2], $m[1], $m[3]);
    } else {
        $row['timeclose'] = 0;
    }
    $row['day_week'] = date('N', $row['timeevent'] );//thu trong tuan 1 = monday, 7 sunday
    $check_exits = nv_calendar_check_exits( $row['id'], $row['timeevent'], $row['timeclose'], $row['catid'], $row['groupid'] );

    if (empty($row['title'])) {
        $error = $lang_module['empty_title_event'];
    }elseif (empty($row['moderator'])) {
        $error = $lang_module['empty_moderator'];
    }
    elseif (empty($row['addressevent'])) {
        $error = $lang_module['empty_addressevent'];
    }elseif ($row['timeevent'] == 0) {
        $error = $lang_module['empty_timeevent'];
    } elseif ($row['catid'] == 0) {
        $error = $lang_module['empty_catid'];
    } elseif ($row['groupid'] == 0) {
        $error = $lang_module['empty_groupid'];
    } elseif ($row['provinceid'] == 0) {
        $error = $lang_module['empty_provinceid'];
    }  elseif (!empty( $check_exits )) {
        $error = $check_exits;
    } else {
        $row['hour_minute_begin'] = date('Hi', $row['timeevent'] );
        $row['hour_minute_end'] = date('Hi', $row['timeclose'] );

        $check_timefix = nv_calendar_check_timefix_exits( $row['id'], $row['timeevent'], $row['timeclose'], $row['catid'], $row['groupid'] );
        $table_insert_update = NV_PREFIXLANG . '_' . $module_data;
        if( $check_timefix == 1 ) {
            $table_insert_update = NV_PREFIXLANG . '_' . $module_data . '_other';
        }

        if ($row['id']) {
            $_sql = 'UPDATE ' . $table_insert_update . ' SET 
                 catid=' . $row['catid'] . ',groupid=' . $row['groupid'] . ', title=:title, description=:description, 
                 moderator=:moderator, participants=:participants, addressevent=:addressevent, timeevent_begin=:timeevent_begin, 
                 timeevent_end=:timeevent_end, hour_minute_begin=:hour_minute_begin, hour_minute_end=:hour_minute_end, provinceid=:provinceid, day_week=:day_week, timefix=' . $row['timefix'] . ' WHERE id =' . $row['id'];
            $sth = $db->prepare($_sql);
        } else {

            $_sql = 'INSERT INTO ' . $table_insert_update . '
                (catid, groupid, title, alias, description, moderator, participants, addressevent, timeevent_begin, timeevent_end, hour_minute_begin, hour_minute_end, provinceid, day_week, status, timefix, addtime) VALUES
                (' . $row['catid'] . ', ' . $row['groupid'] . ', :title, :alias, :description, :moderator, :participants, :addressevent, :timeevent_begin, :timeevent_end, :hour_minute_begin, :hour_minute_end, :provinceid, :day_week, 1, ' . $row['timefix'] . ', ' . NV_CURRENTTIME . ')';
            $sth = $db->prepare($_sql);
            $sth->bindParam(':alias', $row['alias'], PDO::PARAM_STR);
        }
        try {
            $sth->bindParam(':title', $row['title'], PDO::PARAM_STR);
            $sth->bindParam(':description', $row['description'], PDO::PARAM_STR, strlen($row['description']));
            $sth->bindParam(':moderator', $row['moderator'], PDO::PARAM_STR);
            $sth->bindParam(':participants', $row['participants'], PDO::PARAM_STR);
            $sth->bindParam(':addressevent', $row['addressevent'], PDO::PARAM_STR);
            $sth->bindParam(':timeevent_begin', $row['timeevent'], PDO::PARAM_INT);
            $sth->bindParam(':timeevent_end', $row['timeclose'], PDO::PARAM_INT);
            $sth->bindParam(':hour_minute_begin', $row['hour_minute_begin'], PDO::PARAM_INT);
            $sth->bindParam(':hour_minute_end', $row['hour_minute_end'], PDO::PARAM_INT);
            $sth->bindParam(':provinceid', $row['provinceid'], PDO::PARAM_INT);
            $sth->bindParam(':day_week', $row['day_week'], PDO::PARAM_INT);

            $sth->execute();

            if ($sth->rowCount()) {
                $nv_Cache->delMod($module_name);
                if( $check_timefix == 0 ) {
                    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
                }else{
                    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=other' );
                }
            } else {
                $error = $lang_module['errorsave'];
            }
        } catch (PDOException $e) {
            $error = $e->getMessage();
        }
    }
}

elseif( $row['id'] > 0 )
{
    $row = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data .' WHERE id=' . $row['id'] )->fetch();
    if( empty( $row ) )
    {
        Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
        die();
    }
    $row['timeclose'] = $row['timeevent_end'];
    $row['timeevent'] = $row['timeevent_begin'];
    $action = 'edit';
}
else
{
    $row['status'] = 0;
    $row['sex'] = 1;
    $row['provinceid'] = $row['groupid'] = $row['timefix'] = $row['catid'] = 0;
    $row['timeevent'] = NV_CURRENTTIME;
    $row['description'] = '';
}


if ($row['timeevent'] > 0) {
    $row['timeevent_str'] = date('d/m/Y', $row['timeevent']);
}

$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'MODULE_FILE', $module_file );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'action', $action );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'DATA', $row );
$xtpl->assign( 'addcontact', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&action=add' );
$xtpl->assign('timefix', $row['timefix'] ? ' checked="checked"' : '');

if( $row['id'] > 0 or $action == 'add' or $action == 'edit' )
{

    if( ! empty( $error ) )
    {
        $xtpl->assign( 'ERROR', $error );
        $xtpl->parse( 'main.add_row.error' );
    }
    $hour = !empty($row['timeevent']) ? date('H', $row['timeevent']) : 0;
    for ($i = 0; $i <= 23; $i++) {
        $sl = $i == $hour ? 'selected="selected"' : '';
        $xtpl->assign('HOUR', array(
            'index' => $i,
            'selected' => $sl
        ));
        $xtpl->parse('main.add_row.timeevent_hour');
    }

    $min = !empty($row['timeevent']) ? date('i', $row['timeevent']) : 0;
    for ($i = 0; $i <= 59; $i+=30) {
        $sl = $i == $min ? 'selected="selected"' : '';
        $xtpl->assign('MINUTE', array(
            'index' => $i,
            'selected' => $sl
        ));
        $xtpl->parse('main.add_row.timeevent_minute');
    }
    $hour = !empty($row['timeclose']) ? date('H', $row['timeclose']) : 0;
    for ($i = 0; $i <= 23; $i++) {
        $sl = $i == $hour ? 'selected="selected"' : '';
        $xtpl->assign('HOUR', array(
            'index' => $i,
            'selected' => $sl
        ));
        $xtpl->parse('main.add_row.timeclose_hour');
    }

    $min = !empty($row['timeclose']) ? date('i', $row['timeclose']) : 0;
    for ($i = 0; $i <= 59; $i+=30) {
        $sl = $i == $min ? 'selected="selected"' : '';
        $xtpl->assign('MINUTE', array(
            'index' => $i,
            'selected' => $sl
        ));
        $xtpl->parse('main.add_row.timeclose_minute');
    }

    $sql = 'SELECT * FROM ' . NV_TABLE_AFFILIATE_LANG . '_province WHERE status=1 ORDER BY weight ASC ';
    $array_province = $nv_Cache->db($sql, 'id', $module_name);
    foreach ( $array_province as $province ){
        $province['sl'] = ($province['id'] == $row['provinceid'])? ' selected=selected' : '';
        $xtpl->assign('PROVINCE', $province);
        $xtpl->parse('main.add_row.province');
    }

    foreach( $array_cat as $cat  )
    {
        $cat['sl'] = ( $cat['id'] == $row['catid'] ) ? ' selected="selected"' : '';
        $xtpl->assign( 'CAT', $cat );
        $xtpl->parse( 'main.add_row.catid' );
    }
    foreach( $array_group as $group )
    {
        $group['sl'] = ( $group['id'] == $row['groupid'] ) ? ' selected="selected"' : '';
        $xtpl->assign( 'GROUP', $group );
        $xtpl->parse( 'main.add_row.groupid' );
    }

    if (defined('NV_EDITOR')) require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
    $row['description'] = htmlspecialchars(nv_editor_br2nl($row['description']));
    if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
        $description = nv_aleditor('description', '100%', '300px', $row['description']);
    } else {
        $description = '<textarea style="width:100%;height:300px" name="description">' . $row['description'] . '</textarea>';
    }
    $xtpl->assign('description', $description);

    if( $row['id'] == 0 )
    {
        $xtpl->parse( 'main.add_row.auto_get_alias' );
    }
    $xtpl->parse( 'main.add_row' );
}
else
{
    $q = $nv_Request->get_title( 'q', 'post,get' );
    $provinceid = $nv_Request->get_int( 'provinceid', 'post,get' );
    $groupid = $nv_Request->get_int( 'groupid', 'post,get' );
    $catid = $nv_Request->get_int( 'catid', 'post,get' );
    $status = $nv_Request->get_int( 'status', 'post,get', -1 );
    $xtpl->assign( 'Q', $q );
    if( $provinceid > 0 )
    {
        $xtpl->assign( 'provinceid', $provinceid );
        $xtpl->assign( 'districtid', $districtid );
        $xtpl->parse( 'main.view.loaddistrict' );
    }
    foreach( $array_status as $key => $title )
    {
        $selected = ( $key == $status ) ? ' selected="selected"' : '';
        $xtpl->assign( 'OPTION', array(
            'key' => $key,
            'title' => $title,
            'selected' => $selected ) );
        $xtpl->parse( 'main.view.status_select' );
    }

    $per_page = 30;
    $page = $nv_Request->get_int( 'page', 'post,get', 1 );
    $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

    $db->sqlreset()->select( 'COUNT(*)' )->from( NV_PREFIXLANG . '_' . $module_data );

    $sql_where = '';
    if( ! empty( $q ) )
    {
        $sql_where = '(title LIKE :title OR addressevent LIKE :addressevent)';
        $base_url .= '&q=' . $q;
    }
    if( $provinceid > 0 )
    {
        $sql_where .= ( ! empty( $sql_where ) ) ? ' AND provinceid=' . $provinceid : 'provinceid=' . $provinceid;
        $base_url .= '&provinceid=' . $provinceid;
    }
    if( $groupid > 0 )
    {
        $sql_where .= ( ! empty( $sql_where ) ) ? ' AND groupid=' . $groupid : 'groupid=' . $groupid;
        $base_url .= '&groupid=' . $groupid;
    }
    if( $catid > 0 )
    {
        $sql_where .= ( ! empty( $sql_where ) ) ? ' AND catid=' . $catid : 'catid=' . $catid;
        $base_url .= '&catid=' . $catid;
    }
    if( $status >= 0 )
    {
        $sql_where .= ( ! empty( $sql_where ) ) ? ' AND status=' . $status : 'status=' . $status;
        $base_url .= '&status=' . $status;
    }

    $db->where( $sql_where );

    $sth = $db->prepare( $db->sql() );

    if( ! empty( $q ) )
    {
        $sth->bindValue( ':title', '%' . $q . '%' );
        $sth->bindValue( ':addressevent', '%' . $q . '%' );
    }
    $sth->execute();
    $num_items = $sth->fetchColumn();

    $db->select( '*' )->order( 'id DESC' )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );

    $sth = $db->prepare( $db->sql() );

    if( ! empty( $q ) )
    {
        $sth->bindValue( ':title', '%' . $q . '%' );
        $sth->bindValue( ':addressevent', '%' . $q . '%' );
    }
    $sth->execute();

    $page_title = $lang_module['calendar'];

    foreach( $array_cat as $data )
    {
        $data['selected'] = ( $data['id'] == $catid ) ? ' selected="selected"' : '';
        $xtpl->assign( 'OPTION', $data );
        $xtpl->parse( 'main.view.cat_search' );
    }

    $xtpl->assign( 'NV_GENERATE_PAGE', nv_generate_page( $base_url, $num_items, $per_page, $page ) );
    while( $view = $sth->fetch() )
    {
        $view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $view['id'];
        $view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_id=' . $view['id'] . '&amp;delete_checkss=' . md5( $view['id'] . NV_CACHE_PREFIX . $client_info['session_id'] );
        $view['addtime'] = date( 'd/m/Y H:i', $view['addtime'] );
        $view['timeevent_begin'] = date( 'H:i', $view['timeevent_begin'] );
        $view['timeevent'] = date( 'd/m/Y', $view['timeevent_end'] );
        $view['timeevent_end'] = date( 'H:i', $view['timeevent_end'] );
        $view['status'] = $lang_module['status_' . $view['status']];
        if( $view['timefix'] == 1 ){
            $week = $view['day_week'] + 1;
            $view['timefix'] = $lang_module['timefix_' . $view['timefix']] . ' T' . $week;
        }else{
            $view['timefix'] = $lang_module['timefix_' . $view['timefix']];
        }

        $view['cat_title'] = isset( $array_cat[$view['catid']] ) ? $array_cat[$view['catid']]['title'] : 'N/A';
        $view['group_title'] = isset( $array_group[$view['groupid']] ) ? $array_group[$view['groupid']]['title'] : 'N/A';
        $xtpl->assign( 'VIEW', $view );
        $xtpl->parse( 'main.view.loop' );
    }
    $xtpl->parse( 'main.view' );
}
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
