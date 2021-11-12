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
        ->select('userid, last_name, first_name, email')
        ->from(NV_USERS_GLOBALTABLE )
        ->where('concat(last_name," ",first_name) LIKE :full_name OR email LIKE :email')
        ->order('last_name ASC')
        ->limit(50);

    $sth = $db_slave->prepare($db_slave->sql());
    $sth->bindValue(':full_name', '%' . $q . '%', PDO::PARAM_STR);
    $sth->bindValue(':email', '%' . $q . '%', PDO::PARAM_STR);
    $sth->execute();

    $array_data = array();
    while (list($id, $last_name, $first_name ) = $sth->fetch(3)) {
        $array_data[] = array( 'value' => nv_show_name_user( $first_name, $last_name ), 'key' => $id );
    }

    nv_jsonOutput($array_data);
}
elseif( $nv_Request->isset_request( 'submit', 'post' ) )
{
    $data['id'] = $nv_Request->get_int( 'id', 'post', 0 );
    $data['bienbanso'] = $nv_Request->get_title( 'bienbanso', 'post', '' );
    $data['day'] = $nv_Request->get_int( 'day', 'post', 0 );
    $data['month'] = $nv_Request->get_int( 'month', 'post', 0 );
    $data['year'] = $nv_Request->get_int( 'year', 'post', 0 );
    $data['tenkyhieu'] = $nv_Request->get_title( 'tenkyhieu', 'post', '' );
    $data['sokyhieu'] = $nv_Request->get_title( 'sohieu', 'post', '' );
    $data['nuocsanxuat'] = $nv_Request->get_title( 'nuocsanxuat', 'post', '' );
    $data['namsanxuat'] = $nv_Request->get_int( 'namsanxuat', 'post', 0 );
    $data['bophanquanly'] = $nv_Request->get_title( 'bophanquanly', 'post', '' );
    $data['namsudung'] = $nv_Request->get_int( 'namduavaosudung', 'post', 0 );
    $data['congxuat'] = $nv_Request->get_title( 'congxuat', 'post', '' );
    $data['ngaydinhchi'] = $nv_Request->get_int( 'ngaydinhchi', 'post', 0 );
    $data['thangdinhchi'] = $nv_Request->get_int( 'thangdinhchi', 'post', 0 );
    $data['namdinhchi'] = $nv_Request->get_int( 'namdinhchi', 'post', 0 );
    $data['lydodinhchi'] = $nv_Request->get_title( 'lydodinhchi', 'post', '' );
    $data['ghigiamtscd'] = $nv_Request->get_title( 'ghigiamtscd', 'post', '' );
    $data['ngayghigiam'] = $nv_Request->get_int( 'ngayghigiam', 'post', 0 );
    $data['thangghigiam'] = $nv_Request->get_int( 'thangghigiam', 'post', 0 );
    $data['namghigiam'] = $nv_Request->get_int( 'namghigiam', 'post', 0 );

    $sochungtu = $nv_Request->get_array( 'sochungtu', 'post', array() );
    $ngayghichungtu = $nv_Request->get_array( 'ngayghichungtu', 'post', array() );
    $nguyengia = $nv_Request->get_array( 'nguyengia', 'post', array() );
    $namsudung = $nv_Request->get_array( 'namsudung', 'post', array() );
    $giatrihaomon = $nv_Request->get_array( 'giatrihaomon', 'post', array() );
    $luyke = $nv_Request->get_array( 'luyke', 'post', array() );

    $data_detail = array();
    $item_key = 0;
    foreach ( $sochungtu as $key => $value ) {
        if( !empty( $value )) {

            $ngaynhap = $ngayghichungtu[$key];
            $price = $nguyengia[$key];
            $price = floatval(preg_replace('/[^0-9\,]/', '', $price));

            $namsudung_data = $namsudung[$key];
            $giatrihaomon_data = $giatrihaomon[$key];
            $giatrihaomon_data = floatval(preg_replace('/[^0-9\,]/', '', $giatrihaomon_data));

            $luyke_data = $luyke[$key];
            $luyke_data = floatval(preg_replace('/[^0-9\,]/', '', $luyke_data));

            if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $ngaynhap, $m)) {
                $ngaynhap = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
            } else {
                $ngaynhap = 0;
            }

            $data_detail[$item_key]['sochungtu'] = $value;
            $data_detail[$item_key]['ngaynhap'] = $ngaynhap;
            $data_detail[$item_key]['price'] = $price;
            $data_detail[$item_key]['namsudung'] = $namsudung_data;
            $data_detail[$item_key]['giatrihaomon'] = $giatrihaomon_data;
            $data_detail[$item_key]['luyke'] = $luyke_data;
            $item_key++;
        }
    }
    $tencongcu = $nv_Request->get_array( 'tencongcu', 'post', array() );
    $donvitinh = $nv_Request->get_array( 'donvitinh', 'post', array() );
    $soluong = $nv_Request->get_array( 'soluong', 'post', array() );
    $giatri = $nv_Request->get_array( 'giatri', 'post', array() );

    $item_key = 0;
    $data_dungcu = array();
    foreach ( $tencongcu as $key => $value) {
        if (!empty($value)) {
            $donvitinh_data = $donvitinh[$key];
            $soluong_data = $soluong[$key];
            $giatri_data = $giatri[$key];
            $giatri_data = floatval(preg_replace('/[^0-9\,]/', '', $giatri_data));

            $data_dungcu[$item_key]['tencongcu'] = $value;
            $data_dungcu[$item_key]['donvitinh'] = $donvitinh_data;
            $data_dungcu[$item_key]['soluong'] = $soluong_data;
            $data_dungcu[$item_key]['giatri'] = $giatri_data;
            $item_key++;
        }
    }
    if( $data['day'] > 31 ){
        $error[] = $lang_module['error_time_day'];
    }
    elseif( $data['month'] > 12 ){
        $error[] = $lang_module['error_time_month'];
    }
    elseif( empty( $data['tenkyhieu'] ) ){
        $error[] = $lang_module['error_tenkyhieu'];
    }
    else{
        $data['timeinput'] = mktime(0,0, 0, $data['month'], $data['day'], $data['year']);

        if( $data['thangdinhchi'] > 0 && $data['namdinhchi'] > 0 && $data['namdinhchi'] > 0 ){
            $data['ngaydinhchi'] = mktime(0,0, 0, $data['thangdinhchi'], $data['ngaydinhchi'], $data['namdinhchi']);
        }else{
            $data['ngaydinhchi'] = 0;
        }
        if( $data['thangghigiam'] > 0 && $data['ngayghigiam'] > 0 && $data['namghigiam'] > 0 ) {
            $data['ngayghigiamtscd'] = mktime(0, 0, 0, $data['thangghigiam'], $data['ngayghigiam'], $data['namghigiam']);
        }else{
            $data['ngayghigiamtscd'] = 0;
        }
        if( $data['id'] == 0 ){
            //ghi vao bang thong tin chung
            $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_tscd (
                bophanquanly, userid, timeinput, sohopdong, tenkyhieu, sokyhieu, nuocsanxuat, namsanxuat, namsudung, congsuat, ngaydinhchi, lydodinhchi, ghigiamtscd, ngayghigiamtscd, addtime
            ) VALUES (
                 ' . $db->quote( $data['bophanquanly'] ) . ',
                 ' . intval( $user_info['userid'] ) . ',
                 ' . intval( $data['timeinput'] ) . ',
                 ' . $db->quote( $data['bienbanso'] ) . ',
                 ' . $db->quote( $data['tenkyhieu'] ) . ',
                 ' . $db->quote( $data['sokyhieu'] ) . ',
                 ' . $db->quote( $data['nuocsanxuat'] ) . ',
                 ' . intval( $data['namsanxuat'] ) . ',
                 ' . intval( $data['namsudung'] ) . ',
                 ' . $db->quote( $data['congxuat'] ) . ',
                 ' . intval( $data['ngaydinhchi'] ) . ',
                 ' . $db->quote( $data['lydodinhchi'] ) . ',
                 ' . $db->quote( $data['ghigiamtscd'] ) . ',
                 ' . intval( $data['ngayghigiamtscd'] ) . ',
                 ' . NV_CURRENTTIME . '
            )';

            $data['id'] = $db->insert_id( $sql, 'id' );
            if( $data['id'] > 0 )
            {
                //ghi bang chi tiet chung tu
                foreach ( $data_detail as $detail ) {

                        $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_tscd_detail (
                        tscdid, sochungtu, ngaynhap, price, namsudung, giatrihaomon, luyke
                    ) VALUES (
                        ' . intval($data['id']) . ',
                        ' . $db->quote($detail['sochungtu']) . ',
                        ' . intval($detail['ngaynhap']) . ',
                        ' . floatval($detail['price']) . ',
                        ' . $db->quote($detail['namsudung']) . ',
                        ' . floatval($detail['giatrihaomon']) . ',
                        ' . floatval($detail['luyke']) . '
                    )';
                        $db->query( $sql );

                }
                //ghi bang dung cu
                foreach ( $data_dungcu as $dungcu ){

                    $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_tscd_dungcu (
                    tscdid, donvitinh, tencongcu, soluong, giatri
                    ) VALUES (
                        ' . intval( $data['id'] ) . ',
                        ' . $db->quote( $dungcu['donvitinh'] ) . ',
                        ' . $db->quote( $dungcu['tencongcu'] ) . ',
                        ' . intval( $dungcu['soluong'] ) . ',
                        ' . floatval( $dungcu['giatri'] ) . '
                    )';
                    $db->query( $sql );

                }
                Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=tag-product' );
                die();
        } else{
                $error[] = $lang_module['error_insert_data'];
            }
        } else{

            $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tscd SET 
            bophanquanly=' . $db->quote( $data['bophanquanly'] ) . ',
            userid=' . intval( $user_info['userid'] ) . ',
            timeinput=' . intval( $data['timeinput'] ) . ',
            sohopdong=' . $db->quote( $data['bienbanso'] ) . ',
            tenkyhieu=' . $db->quote( $data['tenkyhieu'] ) . ',
            sokyhieu=' . $db->quote( $data['sokyhieu'] ) . ',
            nuocsanxuat=' . $db->quote( $data['nuocsanxuat'] ) . ',
            namsanxuat=' . intval( $data['namsanxuat'] ) . ',
            namsudung=' . intval( $data['namsudung'] ) . ',
            congsuat=' . $db->quote( $data['congxuat'] ) . ',
            ngaydinhchi=' . intval( $data['ngaydinhchi'] ) . ',
            lydodinhchi=' . $db->quote( $data['lydodinhchi'] ) . ',
            ghigiamtscd=' . $db->quote( $data['ghigiamtscd'] ) . ',
            ngayghigiamtscd=' . intval( $data['ngayghigiamtscd'] ) . '
            WHERE id=' . $data['id'];

            $db->query( $sql );

            //xoa ban ghi cu de update
            $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tscd_detail  WHERE tscdid = ' . $db->quote($data['id']));
            $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tscd_dungcu  WHERE tscdid = ' . $db->quote($data['id']));

            //ghi bang chi tiet chung tu
            foreach ( $data_detail as $detail ) {

                $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_tscd_detail (
                        tscdid, sochungtu, ngaynhap, price, namsudung, giatrihaomon, luyke
                    ) VALUES (
                        ' . intval($data['id']) . ',
                        ' . $db->quote($detail['sochungtu']) . ',
                        ' . intval($detail['ngaynhap']) . ',
                        ' . floatval($detail['price']) . ',
                        ' . $db->quote($detail['namsudung']) . ',
                        ' . floatval($detail['giatrihaomon']) . ',
                        ' . floatval($detail['luyke']) . '
                    )';
                $db->query( $sql );

            }
            //ghi bang dung cu
            foreach ( $data_dungcu as $dungcu ){

                $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_tscd_dungcu (
                    tscdid, donvitinh, tencongcu, soluong, giatri
                    ) VALUES (
                        ' . intval( $data['id'] ) . ',
                        ' . $db->quote( $dungcu['donvitinh'] ) . ',
                        ' . $db->quote( $dungcu['tencongcu'] ) . ',
                        ' . intval( $dungcu['soluong'] ) . ',
                        ' . floatval( $dungcu['giatri'] ) . '
                    )';
                $db->query( $sql );

            }
            Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=tag-product' );
            die();
        }
    }
}
elseif( $nv_Request->isset_request( 'id', 'get') ){
    $id = $nv_Request->get_int( 'id', 'get', 0 );
    $db_slave->sqlreset()->select( '*' )->from( NV_PREFIXLANG . '_' . $module_data . '_tscd' )->where( 'id=' . $id );

    $data = $db->query( $db_slave->sql() )->fetch();
    if( empty($data )){
        Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=tag-product' );
        die();
    }
    $data['day'] = date('d', $data['timeinput']);
    $data['month'] = date('m', $data['timeinput']);
    $data['year'] = date('Y', $data['timeinput']);
    if($data['ngaydinhchi'] == 0 ){
        $data['ngaydinhchi'] = '';
    } else{
        $data['thangdinhchi'] = date('m', $data['ngaydinhchi'] );
        $data['namdinhchi'] = date('Y', $data['ngaydinhchi'] );
        $data['ngaydinhchi'] = date('d', $data['ngaydinhchi'] );
    }

    if($data['ngayghigiamtscd'] == 0 ){
        $data['ngayghigiamtscd'] = '';
    } else{
        $data['thangghigiamtscd'] = date('m', $data['ngayghigiamtscd'] );
        $data['namghigiamtscd'] = date('Y', $data['ngayghigiamtscd'] );
        $data['ngayghigiamtscd'] = date('d', $data['ngayghigiamtscd'] );
    }
    $db_slave->sqlreset()->select( '*' )->from( NV_PREFIXLANG . '_' . $module_data . '_tscd_detail' )->order('id ASC')->where( 'tscdid=' . $id );
    $data_detail = $db->query( $db_slave->sql() )->fetchAll();

    $db_slave->sqlreset()->select( '*' )->from( NV_PREFIXLANG . '_' . $module_data . '_tscd_dungcu' )->order('id ASC')->where( 'tscdid=' . $id );
    $data_dungcu = $db->query( $db_slave->sql() )->fetchAll();
}
else{
    $data = $data_detail = $data_dungcu = array();
    $data['day'] = date('d', NV_CURRENTTIME);
    $data['month'] = date('m', NV_CURRENTTIME);
    $data['year'] = date('Y', NV_CURRENTTIME);
}


$xtpl = new XTemplate( $op . '.tpl',  NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign( 'addproduct', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=addproduct' );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_SITEURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'DATA', $data );
if( isset( $error ) && !empty( $error )){
    $xtpl->assign( 'ERROR', implode('<br/>', $error) );
    $xtpl->parse( 'main.error' );
}

foreach( $data_detail as $detail )
{
    $detail['ngaynhap'] = date('d/m/Y', $detail['ngaynhap'] );
    $detail['price'] = number_format($detail['price'], 0,',', '.');
    $detail['giatrihaomon'] = number_format($detail['giatrihaomon'], 0,',', '.');
    $detail['luyke'] = number_format($detail['luyke'], 0,',', '.');
    $xtpl->assign( 'DETAIL', $detail );
    $xtpl->parse( 'main.loop' );
}
if( empty( $data_detail )){
    $xtpl->parse( 'main.loop' );
}
$stt = 1;
foreach( $data_dungcu as $dungcu  )
{
    $dungcu['stt'] = $stt++;
    $dungcu['giatri'] = number_format($dungcu['giatri'], 0,',', '.');
    $xtpl->assign( 'DUNGCU', $dungcu );
    $xtpl->parse( 'main.loopdungcu' );
}
$xtpl->assign( 'stt', $stt );
if( empty( $data_dungcu )){
    $xtpl->parse( 'main.loopdungcu' );
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
