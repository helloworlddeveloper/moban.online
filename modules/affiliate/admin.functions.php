<?php

/**
 * @Project NUKEVIET 4.x
 * @Author mynukeviet (contact@mynukeviet.com)
 * @Copyright (C) 2017 mynukeviet. All rights reserved
 * @Createdate Tue, 07 Nov 2017 07:57:51 GMT
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) die('Stop!!!');
/*
$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_users';
$array_users = $nv_Cache->db($sql, 'id', $module_name);
$array_change_mobile = array();

foreach ( $array_users as $user ){
    $mobile_new = '';
    if (preg_match('/^(0120)[0-9]{7}$/', $user['mobile'], $result )){
        $mobile_new = substr_replace( $result[0], '070', 0, 4 );
    }elseif (preg_match('/^(0121)[0-9]{7}$/', $user['mobile'], $result )){
        $mobile_new = substr_replace( $result[0], '079', 0, 4 );
    }elseif (preg_match('/^(0122)[0-9]{7}$/', $user['mobile'], $result )){
        $mobile_new = substr_replace( $result[0], '077', 0, 4 );
    }elseif (preg_match('/^(0123)[0-9]{7}$/', $user['mobile'], $result )){
        $mobile_new = substr_replace( $result[0], '083', 0, 4 );
    }elseif (preg_match('/^(0124)[0-9]{7}$/', $user['mobile'], $result )){
        $mobile_new = substr_replace( $result[0], '084', 0, 4 );
    }elseif (preg_match('/^(0125)[0-9]{7}$/', $user['mobile'], $result )){

        $mobile_new = substr_replace( $result[0], '085', 0, 4 );

    }elseif (preg_match('/^(0126)[0-9]{7}$/', $user['mobile'], $result )){
        $mobile_new = substr_replace( $result[0], '076', 0, 4 );
    }elseif (preg_match('/^(0127)[0-9]{7}$/', $user['mobile'], $result )){
        $mobile_new = substr_replace( $result[0], '081', 0, 4 );
    }elseif (preg_match('/^(0128)[0-9]{7}$/', $user['mobile'], $result )){
        $mobile_new = substr_replace( $result[0], '078', 0, 4 );
    }elseif (preg_match('/^(0162)[0-9]{7}$/', $user['mobile'], $result )){
        $mobile_new = substr_replace( $result[0], '032', 0, 4 );
    }elseif (preg_match('/^(0163)[0-9]{7}$/', $user['mobile'], $result )){
        $mobile_new = substr_replace( $result[0], '033', 0, 4 );
    }elseif (preg_match('/^(0164)[0-9]{7}$/', $user['mobile'], $result )){
        $mobile_new = substr_replace( $result[0], '034', 0, 4 );
    }elseif (preg_match('/^(0165)[0-9]{7}$/', $user['mobile'], $result )){
        $mobile_new = substr_replace( $result[0], '035', 0, 4 );
    }elseif (preg_match('/^(0166)[0-9]{7}$/', $user['mobile'], $result )){
        $mobile_new = substr_replace( $result[0], '036', 0, 4 );
    }elseif (preg_match('/^(0167)[0-9]{7}$/', $user['mobile'], $result )){
        $mobile_new = substr_replace( $result[0], '037', 0, 4 );
    }elseif (preg_match('/^(0168)[0-9]{7}$/', $user['mobile'], $result )){
        $mobile_new = substr_replace( $result[0], '038', 0, 4 );
    }elseif (preg_match('/^(0169)[0-9]{7}$/', $user['mobile'], $result )){
        $mobile_new = substr_replace( $result[0], '039', 0, 4 );
    }elseif (preg_match('/^(0186)[0-9]{7}$/', $user['mobile'], $result )){
        $mobile_new = substr_replace( $result[0], '056', 0, 4 );
    }elseif (preg_match('/^(0188)[0-9]{7}$/', $user['mobile'], $result )){
        $mobile_new = substr_replace( $result[0], '058', 0, 4 );
    }elseif (preg_match('/^(0199)[0-9]{7}$/', $user['mobile'], $result )){
        $mobile_new = substr_replace( $result[0], '059', 0, 4 );
    }
    if( !empty( $mobile_new )){
        $db->query( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_users SET mobile= ' . $db->quote( $mobile_new ) . ' WHERE userid=' . $user['userid'] );
        $array_change_mobile[$user['userid']] = array('old' => $user['mobile'], 'new' => $mobile_new);
    }
}

file_put_contents(NV_ROOTDIR . '/backup-mobile11.txt', serialize( $array_change_mobile ));
$nv_Cache->delMod($module_name);
*/
define('NV_IS_FILE_ADMIN', true);

if(defined('NV_IS_SPADMIN'))
{
    $allow_func = array(
        'main',
        'users',
        'transaction',
        'export',
        'userajax',
        'usersp',
        'product',
        'productajax',
        'content-view',
        'config',
        'agency',
        'agencycontent',
        'possiton',
        'jobs',
        'province',
        'district',
        'chart',
        'scanuser',
        'scanuser-content'
    );
}
else{
    $allow_func = array(
        'main',
        'users',
        'transaction',
        'export',
        'userajax'
    );
}

define('NV_IS_NUM_CHANGE', 8); //chi so quy doi bai viet new sang data_content


$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_possiton WHERE status=1 ORDER BY weight ASC ';
$array_possiton = $nv_Cache->db($sql, 'id', $module_name);

require NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

/**
 * nv_show_cat_list()
 *
 * @param integer $parentid
 * @return
 */
function nv_show_users_list($parentid = 0)
{
    global $db, $lang_module, $lang_global, $module_name, $module_data, $db_config, $admin_info, $global_config, $module_file, $op;

    $xtpl = new XTemplate('users_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    $sql_where = '';
    if(!defined('NV_IS_SPADMIN')) {
        $sql_where . ' AND userid=' . $admin_info['userid'];
    }
    $sql = 'SELECT t1.userid, t1.parentid, t1.weight, t1.numsubcat, t1.datatext, t1.code, t1.benefit, t1.salary_day, t1.status, t1.permission, t1.mobile, t2.username, t2.first_name, t2.last_name, t2.birthday, t2.email FROM ' . $db_config['prefix'] . '_' . $module_data . '_users AS t1 INNER JOIN ' . NV_USERS_GLOBALTABLE . ' AS t2 ON t1.userid=t2.userid WHERE t1.parentid = ' . $parentid . $sql_where . ' ORDER BY t1.weight ASC';
    $result = $db->query( $sql );
    while ($row = $result->fetch()) {

        $checkss = md5($row['userid'] . NV_CHECK_SESSION);
        $row['fullname'] = nv_show_name_user( $row['first_name'] , $row['last_name'] , $row['username']  );
        $row['admin_edit'] = "<a title=\"" . $lang_global['edit'] . "\" href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=users&amp;userid=" . $row['userid'] . "&amp;parentid=" . $parentid . "#edit\" class=\"btn btn-info btn-xs\" data-toggle=\"tooltip\"><em class=\"fa fa-edit\"></em><span class=\"visible-xs-inline-block\">&nbsp;" . $lang_global['edit'] . "</span></a>\n";
        //khong cho xoa vi anh huong den thong ke
        $row['admin_delete'] = "<a title=\"" . $lang_global['delete'] . "\" href=\"javascript:void(0);\" onclick=\"nv_module_del(" . $row['userid'] . ", '" . $op . "', '" . $checkss . "')\" class=\"btn btn-danger btn-xs\" data-toggle=\"tooltip\"><em class=\"fa fa-trash-o\"></em><span class=\"visible-xs-inline-block\">&nbsp;" . $lang_global['delete'] . "</span></a>";
        $row['link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=users&amp;parentid=' . $row['userid'];
        $row['datatext'] = unserialize( $row['datatext'] );
        $row['birthday'] = ( $row['birthday'] > 0 )? date('d/m/Y', $row['birthday']) : '';
        $row['salary_day'] = number_format( $row['salary_day'], 0, '.', ',');
        $row['benefit'] = number_format( $row['benefit'], 0, '.', ',');
        $row['checked'] = ( $row['status'] == 1 )? ' checked=checked' : '';
        $row['permissionchecked'] = ( $row['permission'] == 1 )? ' checked=checked' : '';
        $xtpl->assign('ROW', $row );

        if ($row['numsubcat']) {
            $xtpl->assign('NUMSUBCAT', $row['numsubcat']);
            $xtpl->parse('main.data.loop.numsubcat');
        }
        $xtpl->parse('main.data.loop');
    }

        $xtpl->parse('main.data');


    $xtpl->parse('main');
    $contents = $xtpl->text('main');

    return $contents;
}


/**
 * nv_show_cat_list()
 *
 * @param integer $parentid
 * @return
 */
function nv_show_product_list($mod_name = '', $per_page, $page)
{
    global $db, $lang_module, $lang_global, $module_name, $module_data, $db_config, $db_slave, $global_config, $module_file, $op;

    $xtpl = new XTemplate('product.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('mod_name', $mod_name);
    $db_slave->sqlreset()
        ->select('COUNT(*)')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_product')
        ->where('module_name = ' . $db->quote( $mod_name ));
    $_sql = $db_slave->sql();
    $num_items = $db_slave->query($_sql)->fetchColumn();

    if( $num_items > 0 ){

        $db_slave->select('*')
            ->order('id DESC')
            ->limit($per_page)
            ->offset(($page - 1) * $per_page);
        $result = $db_slave->query($db_slave->sql());

        while ( $row = $result->fetch()) {
            $row['link'] = nv_url_rewrite( $row['link'], true );
            $row['url_title'] = 'javascript:void(0);';
            $row['active'] = $row['status'] ? 'checked="checked"' : '';
            $row['site_title'] = $row['title'];
            $xtpl->assign('ROW', $row );
            $xtpl->parse('main.table.loop');
        }

        $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&module=' . $mod_name;
        $generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);
        if (!empty($generate_page)) {
            $xtpl->assign('GENERATE_PAGE', $generate_page);
            $xtpl->parse('main.table.generate_page');
        }
        $xtpl->parse('main.table');
        $contents = $xtpl->text('main.table');
    }else{
        $contents = '';
    }

    return $contents;
}

/**
 * GetCatidInParent()
 *
 * @param mixed $catid
 * @return
 */
function GetCatidInParent($catid)
{
    global $global_array_cat;
    $array_cat = array();
    $array_cat[] = $catid;
    $subcatid = explode(',', $global_array_cat[$catid]['subcatid']);
    if (! empty($subcatid)) {
        foreach ($subcatid as $id) {
            if ($id > 0) {
                if ($global_array_cat[$id]['numsubcat'] == 0) {
                    $array_cat[] = $id;
                } else {
                    $array_cat_temp = GetCatidInParent($id);
                    foreach ($array_cat_temp as $catid_i) {
                        $array_cat[] = $catid_i;
                    }
                }
            }
        }
    }
    return array_unique($array_cat);
}



function fix_DisWeight($pro)
{
    global $db, $module_data;

    $sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_district WHERE idprovince=" . $pro . " ORDER BY weight ASC";
    $result = $db->query($sql);
    $weight = 0;
    while($row = $result->fetch())
    {
        $weight++;
        $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_district SET weight=" . $weight . " WHERE id=" . $row['id'] . " AND idprovince=" . $pro;
        $db->query($query);
    }
}
