<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2017 14:43
 */

if( ! defined( 'NV_IS_MOD_SM' ) ) die( 'Stop!!!' );


$chossentype = $nv_Request->get_int( 'chossentype', 'get', 2 );
$q = $nv_Request->get_title( 'term', 'get', '', 1 );
if( empty( $q ) ) return;

//khach le
if( $chossentype == 3 ){
    $db->sqlreset()->select( '*' )->from( NV_PREFIXLANG . '_' . $module_data . '_customer')->where( 'refer_userid=' . $user_info['userid'] . ' AND (code LIKE :code OR fullname LIKE :fullname OR phone LIKE :phone OR email LIKE :email)' )->limit( 30 );

    $sth = $db->prepare( $db->sql() );
    $sth->bindValue( ':fullname', '%' . $q . '%', PDO::PARAM_STR );
    $sth->bindValue( ':code', '%' . $q . '%', PDO::PARAM_STR );
    $sth->bindValue( ':phone', '%' . $q . '%', PDO::PARAM_STR );
    $sth->bindValue( ':email', '%' . $q . '%', PDO::PARAM_STR );
    $sth->execute();

    $array_data = array();
    while( $data_customer = $sth->fetch(  ) )
    {
        $array_data[] = array(
            'key' => $data_customer['customer_id'],
            'value' => $data_customer['code'] . ' - ' . $data_customer['fullname'] . ' - ' . $data_customer['email'] . ' - ' . $data_customer['phone'],
            'fullname' => $data_customer['fullname'],
            'phone' => $data_customer['phone'],
            'email' => $data_customer['email'],
            'address' => $data_customer['address'],
        );
    }
}else{

    //chi lay cac dai ly trong he thong minh quan ly
    $sql = 'SELECT numsubcat, subcatid FROM ' . NV_TABLE_AFFILIATE . '_users WHERE userid=' . $user_info['userid'];
    list( $numsubcat, $subcatid ) = $db->query($sql)->fetch(3);
    $listuserid = array();
    if( $numsubcat > 0 ){
        $listuserid = nvGetUseridInParent($user_info['userid'], $subcatid, false, false);
        $sql_where = ' AND t1.userid IN ( ' . implode(',', $listuserid ) . ')';
    }

    //he thong ctv, dl
    $db->sqlreset()->select('t1.userid, t2.code, t2.agencyid, t1.username, t1.first_name, t1.last_name, t1.email, t2.datatext' )->from( NV_TABLE_AFFILIATE . '_users AS t2')->join( 'INNER JOIN ' . NV_USERS_GLOBALTABLE . ' AS t1 ON t1.userid=t2.userid')->where( 't1.userid != ' . $user_info['userid'] . $sql_where . ' AND ( t2.code LIKE :code OR concat(t1.last_name," ",t1.first_name) LIKE :fullname OR t2.mobile LIKE :mobile OR t1.email LIKE :email)' )->limit( 30 );

    $sth = $db->prepare( $db->sql() );
    $sth->bindValue( ':fullname', '%' . $q . '%', PDO::PARAM_STR );
    $sth->bindValue( ':code', '%' . $q . '%', PDO::PARAM_STR );
    $sth->bindValue( ':mobile', '%' . $q . '%', PDO::PARAM_STR );
    $sth->bindValue( ':email', '%' . $q . '%', PDO::PARAM_STR );
    $sth->execute();

    $array_data = array();
    while( list( $userid, $code, $agencyid, $username, $first_name, $last_name, $email, $datatext ) = $sth->fetch( 3 ) )
    {
        $percent_sale_agency = '';
        $fullname = nv_show_name_user( $first_name, $last_name, $username );
        if( $array_agency[$agencyid]['percent_sale'] > 0){
            $percent_sale_agency = sprintf( $lang_module['percent_sale_agency_sub'], $array_agency[$agencyid]['title'] . ' ' . $fullname, $array_agency[$agencyid]['percent_sale'] . '%' );
            if( $array_agency[$agencyid]['number_sale'] > 0 and $array_agency[$agencyid]['number_gift'] >0 ){
                $percent_sale_agency = $percent_sale_agency . sprintf( $lang_module['gift_product_agency'], $array_agency[$agencyid]['number_sale'], $array_agency[$agencyid]['number_gift'] );
            }
        }
        $datatext = unserialize( $datatext );

        $array_data[] = array(
            'key' => $userid,
            'value' => $code . ' - ' . $fullname,
            'fullname' => $fullname,
            'phone' => $datatext['mobile'],
            'address' => $datatext['address'],
            'username' => $username,
            'info_agency' => $percent_sale_agency,
            'email' => $email,
        );
    }
}


header( 'Cache-Control: no-cache, must-revalidate' );
header( 'Content-type: application/json' );

ob_start( 'ob_gzhandler' );
echo json_encode( $array_data );
exit();
