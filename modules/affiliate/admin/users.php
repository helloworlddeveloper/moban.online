<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

if( $nv_Request->isset_request( 'setactive', 'post' )){
    $userid = $nv_Request->get_int('userid', 'post', 0);
    
    $sql = 'SELECT status FROM ' . $db_config['prefix'] . '_' . $module_data . '_users WHERE userid = ' . $userid;
    $result = $db->query( $sql );
    list( $active ) = $result->fetch(3);
    
    if ($active == 0) {
        $active = 1;
    }  else {
        $active = 0;
    }

    $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_users SET status=' . $active . ' WHERE userid=' . $userid;

    $result = $db->query($sql);
    $nv_Cache->delMod($module_name);
    nv_htmlOutput('OK');
}
elseif( $nv_Request->isset_request( 'permission', 'post' )){
    $userid = $nv_Request->get_int('userid', 'post', 0);

    $sql = 'SELECT permission FROM ' . $db_config['prefix'] . '_' . $module_data . '_users WHERE userid = ' . $userid;
    $result = $db->query( $sql );
    list( $active ) = $result->fetch(3);

    if ($active == 0) {
        $active = 1;
    }  else {
        $active = 0;
    }

    $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_users SET permission=' . $active . ' WHERE userid=' . $userid;

    $result = $db->query($sql);
    $nv_Cache->delMod($module_name);
    nv_htmlOutput('OK');
}
else if( $nv_Request->isset_request('del', 'post', 0) ){
    $id = $nv_Request->get_int('id', 'post', 0);
    $checkss = $nv_Request->get_string('checkss', 'post', '');

    if (md5($id . NV_CHECK_SESSION) == $checkss) {
        $content = 'NO_' . $id;

        $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_users WHERE userid=' . $id;
        $data_users = $db->query($sql)->fetch();

        if (empty($data_users) || $data_users['numsubcat'] > 0 ) {
            die('NO_' . $id);
        }

        $sql = 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_users WHERE userid = ' . $id;
        if ($db->exec($sql)) {
            $db->query( 'DELETE FROM ' . $db_config['prefix'] . '_regsite WHERE userid = ' . $id );
            $db->query('DELETE FROM ' . NV_USERS_GLOBALTABLE . '  WHERE userid=' . $id );
            nv_insert_logs(NV_LANG_DATA, $module_name, 'Delete users', 'ID: ' . $id, $admin_info['userid']);
            if( $data_users['parentid'] > 0 ){
                //update lai thong tin tuyen tren
                $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_users WHERE userid=' . $data_users['parentid'];
                $data_users_parent = $db->query($sql)->fetch();
                $subcatid = explode(',', $data_users_parent['subcatid'] );

                $key = array_search($id, $subcatid);
                if (false !== $key) {
                    unset($subcatid[$key]);
                }
                $data_users_parent['subcatid'] = implode(',', $subcatid );

                $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_users SET numsubcat=numsubcat-1, subcatid=' . $db->quote( $data_users_parent['subcatid'] ) . ' WHERE userid=' . $data_users['parentid'];
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

$page_title = $lang_module['users'];

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$error = '';
$savecat = 0;
$array_data = array();
list($array_data['userid'], $array_data['parentid'], $array_data['possitonid'], $array_data['agencyid'], $array_data['provinceid'], $array_data['districtid'] ) = array(0,0,0,0,0,0,0);

$array_data['parentid'] = $nv_Request->get_int('parentid', 'get,post', 0);
$userid = $nv_Request->get_int('userid', 'get', 0);

if ($userid > 0 ) {

    $sql = 'SELECT t1.userid, t1.parentid, t1.weight, t1.numsubcat, t1.mobile, t1.datatext, t1.possitonid, t1.agencyid, t1.provinceid, t1.districtid, t2.username, t2.first_name, t2.last_name, t2.birthday, t2.email FROM ' . $db_config['prefix'] . '_' . $module_data . '_users AS t1 INNER JOIN ' . NV_USERS_GLOBALTABLE . ' AS t2 ON t1.userid=t2.userid WHERE t1.userid = ' . $userid;
    $result = $db->query( $sql );
    $array_data = $result->fetch();
    $array_data['datatext'] = unserialize( $array_data['datatext'] );
    $array_data['fullname'] = nv_show_name_user( $array_data['first_name'] , $array_data['last_name'] , $array_data['username']  );
    $array_data['birthday'] = ( $array_data['birthday'] > 0 )? date('d/m/Y', $array_data['birthday']) : '';
    $caption = $lang_module['edit_users'] . ' ' . $array_data['fullname'];
    $array_in_cat = GetCatidInParent($array_data['userid']);
} else {
    $caption = $lang_module['add_users'];
    if( $array_data['parentid'] > 0 ){
        $sql = 'SELECT username, first_name, last_name FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid = ' . $array_data['parentid'];
        $result = $db->query( $sql );
        $array_parent = $result->fetch();
        $array_parent['fullname'] = nv_show_name_user( $array_parent['first_name'] , $array_parent['last_name'] , $array_parent['username']  );
        $caption .= ' ' . $lang_module['for'] . $array_parent['fullname'];
    }

    $array_in_cat = array();
}

$savecat = $nv_Request->get_int('savecat', 'post', 0);
if (! empty($savecat)) {
    $array_data['userid'] = $nv_Request->get_int('userid', 'post', 0);
    $array_data['parentid']  = $nv_Request->get_int('parentid', 'post', 0);
    $array_data['mobile']  = $nv_Request->get_title('mobile', 'post', '', 1);
    $array_data['address']  = $nv_Request->get_title('address', 'post', '', 1);
    $array_data['cmnd']  = $nv_Request->get_title('cmnd', 'post', '', 1);
    $array_data['ngaycap']  = $nv_Request->get_title('ngaycap', 'post', '', 1);
    $array_data['noicap']  = $nv_Request->get_title('noicap', 'post', '', 1);
    $array_data['stknganhang']  = $nv_Request->get_title('stknganhang', 'post', '', 1);
    $array_data['tennganhang']  = $nv_Request->get_title('tennganhang', 'post', '', 1);
    $array_data['chinhanh']  = $nv_Request->get_title('chinhanh', 'post', '', 1);

    $array_data['salary_day'] = $nv_Request->get_float('salary_day', 'post', 0);
    $array_data['benefit'] = $nv_Request->get_float('benefit', 'post', 0);
    $array_data['possitonid'] = $nv_Request->get_int('possitonid', 'post', 0);
    $array_data['agencyid'] = $nv_Request->get_int('agencyid', 'post', 0);
    $array_data['provinceid'] = $nv_Request->get_int('provinceid', 'post', 0);
    $array_data['districtid'] = $nv_Request->get_int('districtid', 'post', 0);

    if( $array_data['possitonid'] == 0 && $array_data['agencyid'] == 0 ){
        $error = $lang_module['error_no_chossen_possiton_agency'];
    }elseif( $array_data['possitonid'] > 0 && $array_data['agencyid'] > 0 ){
        $error = $lang_module['agency_and_possition_error'];
    }


    list( $userid, $haveorder, $parentid ) = $db->query('SELECT userid, haveorder, parentid FROM ' . $db_config['prefix'] . '_' . $module_data . '_users WHERE userid=' . $array_data['userid'] )->fetch( 3 );
    $userid = intval( $userid );
    if( $userid > 0 && $haveorder == 1 && $array_data['parentid'] != $parentid ){
      //  $error = $lang_module['not_change_user_parent_error'];
        //$array_data['parentid'] = $parentid;
    }

    if( empty( $error )){
        try{
            if ( $array_data['userid'] > 0 && $userid == 0 ) {
                $precode = $affiliate_config['precode'];
                $row['code'] = vsprintf($precode, $array_data['userid']);
                $row['precode'] = $row['code'] . '%01s';

                $weight = $db->query('SELECT max(weight) FROM ' . $db_config['prefix'] . '_' . $module_data . '_users WHERE parentid=' . $array_data['parentid'])->fetchColumn();
                $weight = intval($weight) + 1;
                $listparentid = $subcatid = '';

                $stmt = $db->prepare("INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_users (userid, parentid, precode, code, mobile, salary_day, benefit, datatext, weight, sort, lev, possitonid, agencyid, numsubcat, subcatid, listparentid, add_time, edit_time, status, provinceid, districtid, permission, haveorder, shareholder) VALUES
			(:userid, :parentid, :precode, :code, :mobile, :salary_day, :benefit, :datatext, :weight, '0', '0', :possitonid, :agencyid, '0', :subcatid, :listparentid, " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 1, :provinceid, :districtid, 0, 0, 0)");

                $stmt->bindParam(':salary_day', $array_data['salary_day'], PDO::PARAM_INT);
                $stmt->bindParam(':benefit', $array_data['benefit'], PDO::PARAM_INT);
                $stmt->bindParam(':userid', $array_data['userid'], PDO::PARAM_INT);
                $stmt->bindParam(':parentid', $array_data['parentid'], PDO::PARAM_INT);
                $stmt->bindParam(':precode', $row['precode'], PDO::PARAM_STR);
                $stmt->bindParam(':code', $row['code'], PDO::PARAM_STR);
                $stmt->bindParam(':mobile', $array_data['mobile'], PDO::PARAM_STR);
                $stmt->bindParam(':weight', $weight, PDO::PARAM_INT);
                $stmt->bindParam(':possitonid', $array_data['possitonid'], PDO::PARAM_INT);
                $stmt->bindParam(':agencyid', $array_data['agencyid'], PDO::PARAM_INT);
                $stmt->bindParam(':subcatid', $subcatid, PDO::PARAM_STR);
                $stmt->bindParam(':listparentid', $listparentid, PDO::PARAM_STR);
                $stmt->bindParam(':datatext', serialize( $array_data ), PDO::PARAM_STR, strlen( serialize( $array_data ) ));
                $stmt->bindParam(':provinceid', $array_data['provinceid'], PDO::PARAM_INT);
                $stmt->bindParam(':districtid', $array_data['districtid'], PDO::PARAM_INT);

                $stmt->execute();

                if ($stmt->rowCount()) {
                    nv_fix_users_order();
                    $nv_Cache->delMod($module_name);
                    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['add_users'], $array_data['userid'], $admin_info['userid']);
                    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&parentid=' . $array_data['parentid']);
                } else {
                    $error = $lang_module['errorsave'];
                }
            } elseif ( $array_data['userid'] > 0 ) {
                $stmt = $db->prepare('UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_users SET parentid=' . $array_data['parentid'] . ', mobile=:mobile, salary_day=:salary_day, benefit=:benefit, datatext=:datatext, possitonid=:possitonid, agencyid=:agencyid, edit_time=' . NV_CURRENTTIME . ', provinceid=:provinceid, districtid=:districtid WHERE userid =' . $array_data['userid']);
                $stmt->bindParam(':datatext', serialize( $array_data ), PDO::PARAM_STR);
                $stmt->bindParam(':mobile', $array_data['mobile'], PDO::PARAM_STR);
                $stmt->bindParam(':salary_day', $array_data['salary_day'], PDO::PARAM_INT);
                $stmt->bindParam(':benefit', $array_data['benefit'], PDO::PARAM_INT);
                $stmt->bindParam(':possitonid', $array_data['possitonid'], PDO::PARAM_INT);
                $stmt->bindParam(':agencyid', $array_data['agencyid'], PDO::PARAM_INT);
                $stmt->bindParam(':provinceid', $array_data['provinceid'], PDO::PARAM_INT);
                $stmt->bindParam(':districtid', $array_data['districtid'], PDO::PARAM_INT);
                $stmt->execute();

                if ($stmt->rowCount()) {
                    nv_fix_users_order();
                    $nv_Cache->delMod($module_name);
                    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&parentid=' . $array_data['parentid']);
                } else {
                    $error = $lang_module['errorsave'];
                }
            } else {
                $error = $lang_module['error_username'];
            }
        } catch (PDOException $e) {
            $error = $e->getMessage();
        }
    }

}

$global_array_users = array();
$sql = 'SELECT t1.lev, t1.possitonid, t1.agencyid, t1.mobile, t2.userid, t2.username, t2.first_name, t2.last_name, t2.birthday, t2.email FROM ' . $db_config['prefix'] . '_' . $module_data . '_users AS t1 INNER JOIN ' . NV_USERS_GLOBALTABLE . ' AS t2 ON t1.userid=t2.userid ORDER BY t1.sort ASC';

$result = $db_slave->query($sql);
while ($row = $result->fetch()) {
    $row['fullname'] = nv_show_name_user( $row['first_name'], $row['last_name'], $row['username'] );
    $global_array_users[$row['userid']] = $row;
}

$array_users_list = array();
foreach ($global_array_users as $userid_i => $array_value) {
    $lev_i = $array_value['lev'];
    $xtitle_i = '';
    if ($lev_i > 0) {
        $xtitle_i .= '&nbsp;&nbsp;&nbsp;|';
        for ($i = 1; $i <= $lev_i; ++$i) {
            $xtitle_i .= '---';
        }
        $xtitle_i .= '>&nbsp;';
    }
    $xtitle_i .= $array_value['fullname'] . ' - ' . $array_value['email'];
    $array_users_list[$userid_i] = $xtitle_i;

}

$xtpl = new XTemplate('users.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

$xtpl->assign('caption', $caption);
$xtpl->assign('DATA', $array_data);
$xtpl->assign('CAT_LIST', nv_show_users_list($array_data['parentid']));

if( $array_data['userid'] > 0 ){
    $xtpl->parse('main.content.data_users');
    $xtpl->parse('main.content.data_cus_js');
}

if (!empty($array_users_list)) {
    foreach ($array_possiton as $possiton ) {
        $possiton['sl'] =  ($possiton['id'] == $array_data['possitonid']) ? ' selected="selected"' : '';
        $xtpl->assign('POSSITION', $possiton);
        $xtpl->parse('main.content.possiton');
    }
}
if (!empty($array_agency)) {
    foreach ($array_agency as $agency ) {
        $agency['sl'] =  ($agency['id'] == $array_data['agencyid']) ? ' selected="selected"' : '';
        $agency['price_require'] = number_format( $agency['price_require'], 0, '.', ',');
        $xtpl->assign('AGENCY', $agency);
        $xtpl->parse('main.content.agency');
    }
}
foreach ($array_users_list as $userid_i => $title_i) {
    if ($userid_i != $array_data['userid'] ) {
        $xtpl->assign('CAT_SUB', array(
            'value' => $userid_i,
            'selected' => ($userid_i == $array_data['parentid']) ? ' selected="selected"' : '',
            'title' => $title_i));
        $xtpl->parse('main.content.catinfo');
    }
}


$array_province = $nv_Cache->db( 'SELECT * FROM ' . NV_PREFIXLANG . "_" . $module_data . '_province WHERE status=1 ORDER BY weight', 'id', 'location' );
foreach( $array_province as $province )
{
    $xtpl->assign( 'OPTION', array(
        'key' => $province['id'],
        'title' => $province['title'],
        'selected' => ( $province['id'] == $array_data['provinceid'] ) ? ' selected="selected"' : '' ) );
    $xtpl->parse( 'main.content.select_province' );
}
if( $array_data['provinceid'] > 0 ){
    $xtpl->parse('main.content.load_district');
}
if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

$xtpl->parse('main.content');


$xtpl->parse('main');
$contents .= $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
