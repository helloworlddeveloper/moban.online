<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Mr.Thang <contact@vinades.vn>
 * @Copyright (C) 2010 - 2014 Mr.Thang. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 08 Apr 2012 00:00:00 GMT
 */

if (!defined('NV_IS_MOD_WORKFORCE')) die('Stop!!!');

if (!defined('NV_IS_USER') ) {
    $redirect = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;
    Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=login&nv_redirect=' . nv_redirect_encrypt($redirect));
    die();
}

if (!nv_user_in_groups($array_config['group_add_workforce'])) {
    Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=main');
    die();
}

if( $nv_Request->isset_request( 'user', 'get' ) )
{
    $q = $nv_Request->get_title('term', 'get', '', 1);
    if (empty($q)) {
        return;
    }

    $db_slave->sqlreset()
        ->select('id, last_name, first_name, phone, email')
        ->from(NV_PREFIXLANG . '_' . $module_data )
        ->where('concat(last_name," ",first_name) LIKE :full_name OR email LIKE :email OR phone LIKE :phone')
        ->order('last_name ASC')
        ->limit(50);

    $sth = $db_slave->prepare($db_slave->sql());
    $sth->bindValue(':full_name', '%' . $q . '%', PDO::PARAM_STR);
    $sth->bindValue(':email', '%' . $q . '%', PDO::PARAM_STR);
    $sth->bindValue(':phone', '%' . $q . '%', PDO::PARAM_STR);
    $sth->execute();

    $array_data = array();
    while (list($id, $last_name, $first_name, $phone, $email) = $sth->fetch(3)) {
        $array_data[] = array( 'value' => nv_show_name_user( $first_name, $last_name ) . ': ' . $phone, 'key' => $id );
    }

    nv_jsonOutput($array_data);
}
elseif( $nv_Request->isset_request( 'submit', 'post' ) )
{
    $array_reponsive = array();
    $array_reponsive['status'] = '';
    $data['hour'] = $nv_Request->get_int( 'hour', 'post', 0 );
    $data['minute'] = $nv_Request->get_int( 'minute', 'post', 0 );
    $data['day'] = $nv_Request->get_int( 'day', 'post', 0 );
    $data['month'] = $nv_Request->get_int( 'month', 'post', 0 );
    $data['year'] = $nv_Request->get_int( 'year', 'post', 0 );
    $data['thanhphankiemke'] = $nv_Request->get_array( 'thanhphankiemke', 'post', array() );
    $data['chucvu'] = $nv_Request->get_array( 'chucvu', 'post', array() );
    $data['solieukiemkethucte'] = $nv_Request->get_array( 'solieukiemkethucte', 'post', array() );
    $data['hong'] = $nv_Request->get_array( 'hong', 'post', array() );
    $data['thua'] = $nv_Request->get_array( 'thua', 'post', array() );
    $data['thieu'] = $nv_Request->get_array( 'thieu', 'post', array() );
    $data['ghichu'] = $nv_Request->get_array( 'ghichu', 'post', array() );
    $data['price_conlai'] = $nv_Request->get_array( 'price_conlai', 'post', array());

    if( $data['hour'] > 23 ){
        $array_reponsive['mess'] = $lang_module['error_time_hour'];
        $array_reponsive['status'] = 'error';
    }elseif( $data['minute'] > 59 ){
        $array_reponsive['mess'] = $lang_module['error_time_minute'];
        $array_reponsive['status'] = 'error';
    }elseif( $data['day'] > 31 ){
        $array_reponsive['mess'] = $lang_module['error_time_day'];
        $array_reponsive['status'] = 'error';
    }
    elseif( $data['month'] > 12 ){
        $array_reponsive['mess'] = $lang_module['error_time_month'];
        $array_reponsive['status'] = 'error';
    }elseif (empty( $data['thanhphankiemke'] )){
        $array_reponsive['mess'] = $lang_module['error_thanhphankiemke'];
        $array_reponsive['status'] = 'error';
    }elseif (empty( $data['solieukiemkethucte'] )){
        $array_reponsive['mess'] = $lang_module['error_solieukiemkethucte'];
        $array_reponsive['status'] = 'error';
    }else{
        $data['time_inventory'] = mktime($data['hour'], $data['minute'], 0, $data['month'], $data['day'], $data['year']);
        $data['departmentid'] = $nv_Request->get_int( 'departmentid', 'post', 0 );

        //ghi vao bang thong tin chung
        $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_inventory (
                departmentid, time_inventory, addtime
            ) VALUES (
                 ' . intval( $data['departmentid'] ) . ',
                 ' . intval( $data['time_inventory'] ) . ',
                 ' . NV_CURRENTTIME . '
            )';

        $data['id'] = $db->insert_id( $sql, 'id' );
        if( $data['id'] > 0 )
        {
            //ghi bang thanh phan ban kiem ke
            foreach ( $data['thanhphankiemke'] as $key => $thanhphankiemke ) {
                $postion_name = $data['chucvu'][$key];
                $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_inventory_users (
                    iid, userid, postion_name, checked, time_checked
                ) VALUES (
                    ' . intval($data['id']) . ',
                    ' . intval($thanhphankiemke) . ',
                    ' . $db->quote($postion_name) . ', 0,0
                )';
            }
            if( $db->query( $sql ) )
            {
                foreach ( $data['solieukiemkethucte'] as $key => $soluongthucte ){

                    $price_conlai = str_replace('.', '', $data['price_conlai'][$key] );
                    $amount_broken = intval( $data['hong'][$key] );
                    $amount_redundant = intval( $data['thua'][$key] );
                    $amount_missing = intval( $data['thieu'][$key] );
                    $ghichu = $data['ghichu'][$key];
                    //ghi bang chi tiet kiem ke
                    $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_inventory_detail (
                    iid, pid, price, amount, amount_broken, amount_redundant, amount_missing, note
                    ) VALUES (
                        ' . intval( $data['id'] ) . ',
                        ' . intval( $key ) . ',
                        ' . floatval( $price_conlai ) . ',
                        ' . intval( $soluongthucte ) . ',
                        ' . intval( $amount_broken ) . ',
                        ' . intval( $amount_redundant ) . ',
                        ' . intval( $amount_missing ) . ',
                        ' . $db->quote( $ghichu ) . '
                    )';
                    $db->query( $sql );
                }
            }
            $array_reponsive['mess'] = $lang_module['save_kiemke_ok'];
            $array_reponsive['status'] = 'sucsess';
        }else{
            $array_reponsive['mess'] = $lang_module['error_insert_data'];
            $array_reponsive['status'] = 'error';
        }
    }
    nv_jsonOutput( $array_reponsive );
}
else{
    $data = array();
    $data['hour'] = date('h', NV_CURRENTTIME);
    $data['minute'] = date('i', NV_CURRENTTIME);
    $data['day'] = date('d', NV_CURRENTTIME);
    $data['month'] = date('m', NV_CURRENTTIME);
    $data['year'] = date('Y', NV_CURRENTTIME);
}
$page_title = $lang_module['product_list'];
$departmentid = $nv_Request->get_int( 'departmentid', 'get', 0 );


$from = NV_PREFIXLANG . '_' . $module_data . '_product';

$where = array();
$array_department_allow = array();
if( !defined('NV_IS_ADMIN')){
    foreach ($array_department as $department ){
        if( $department['userid'] == $user_info['userid'] ){
            $array_department_allow[] = $department['id'];
        }
    }
    if( !empty( $array_department_allow) && in_array( $departmentid, $array_department_allow)){
        $where[] = 'departmentid =' . $departmentid;
    }
    else{
        $where[] = 'departmentid=0';
    }
}
else{
    if( $departmentid > 0 )
    {
        $where[] = " departmentid = " .$departmentid;
    }
}

$page = $nv_Request->get_int( 'page', 'get', 1 );

$db_slave->sqlreset()->select( 'COUNT(*)' )->from( $from )->where( implode(' AND ', $where) );

$_sql = $db_slave->sql();

$xtpl = new XTemplate( $op . '.tpl',  NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign( 'addproduct', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=addproduct' );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_SITEURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'departmentid', $departmentid );
$xtpl->assign( 'DATA', $data );
$array_department[0] =array('id' => 0, 'title' => $lang_module['all_company']);

asort( $array_department );
foreach ( $array_department as $department ){
    if( ( defined('NV_IS_ADMIN')) or in_array($department['id'], $array_department_allow )){
        $department['sl'] = ( $department['id'] == $departmentid )? ' selected=selected' : '';
        $xtpl->assign( 'DEPARTMENT', $department );
        $xtpl->parse( 'main.department' );
    }
}

$db_slave->select( '*' )->order( 'addtime DESC' );
$stt = 1;
$result = $db_slave->query( $db_slave->sql() );
$array_data = array();
while( $row = $result->fetch() )
{
    $row['tt'] = $stt++;
    $row['unit'] = $array_units[$row['unitid']]['title'];
    $timekhauhao = NV_CURRENTTIME - $row['time_in'];
    $row['time_in'] = date( 'm/Y', $row['time_in'] );
    $row['department'] = $array_department[$row['departmentid']]['title'];
    $giatrikhauhaongay = $row['price'] / $row['time_depreciation']/30;
    $timekhauhao = ceil( $timekhauhao/86400 );//quy doi ra ngay
    $row['price_conlai'] = $row['price'] - ($giatrikhauhaongay * $timekhauhao);
    $row['status'] = $lang_module['status_' . $row['status']];
    $row['price_conlai'] = number_format( $row['price_conlai'], 0, ',', '.');
    $row['price'] = number_format( $row['price'], 0, ',', '.');
    $row['amount_check'] = $row['amount'];
    $row['amount'] = number_format( $row['amount'], 0, ',', '.');
    $row['link_edit'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=addproduct&id=' . $row['id'];
    $row['link_delete'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_id=' . $row['id'] . '&amp;delete_checkss=' . md5( $row['id'] . NV_CACHE_PREFIX . $client_info['session_id'] );
    $array_data[$row['producttypeid']][] = $row;
}

foreach( $array_data as $producttypeid => $data_product )
{
    foreach( $data_product as $data )
    {
        $xtpl->assign( 'ROW', $data );
        $xtpl->parse( 'main.producttype.loop' );
    }
    $xtpl->assign( 'PRODUCTTYPE', $array_producttype[$producttypeid] );
    $xtpl->parse( 'main.producttype' );
}


$xtpl->parse( 'main' );
$contents = nv_theme_workforce_control( $array_control );
$contents .= $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';

//https://www.youtube.com/watch?v=6CYar9oeR_c