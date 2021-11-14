<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$act = $nv_Request->get_title('act', 'get', 'referuser');
$q = $nv_Request->get_title( 'term', 'get', '', 1 );
if( empty( $q ) ) return;
if( $act == 'referuser' ){

    $db->sqlreset()->select( 'userid, username,first_name, last_name, birthday, email' )->from( NV_USERS_GLOBALTABLE )->where( 'active=1 AND (username LIKE :username OR concat(last_name," ",first_name) LIKE :fullname OR email LIKE :email)' )->limit( 50 );

    $sth = $db->prepare( $db->sql() );
    $sth->bindValue( ':fullname', '%' . $q . '%', PDO::PARAM_STR );
    $sth->bindValue( ':username', '%' . $q . '%', PDO::PARAM_STR );
    $sth->bindValue( ':email', '%' . $q . '%', PDO::PARAM_STR );
    $sth->execute();

    $array_data = array();
    while( list( $userid, $username, $first_name, $last_name, $birthday, $email ) = $sth->fetch( 3 ) )
    {
        $fullname = nv_show_name_user( $first_name, $last_name, $username );
        $array_data[] = array(
            'key' => $userid,
            'value' => $username . ' - ' . $fullname . ' - ' . $email,
            'fullname' => $fullname,
            'birthday' => ( $birthday > 0 )? date('d/m/Y', $birthday) : '',
            'username' => $username,
            'email' => $email,
        );
    }

}else{
    $db->sqlreset()->select( 'customer_id,fullname, phone, address, email' )->from( NV_PREFIXLANG . '_' . $module_data . '_customer' )->where( 'fullname LIKE :fullname OR phone LIKE :phone OR email LIKE :email' )->limit( 50 );

    $sth = $db->prepare( $db->sql() );
    $sth->bindValue( ':fullname', '%' . $q . '%', PDO::PARAM_STR );
    $sth->bindValue( ':phone', '%' . $q . '%', PDO::PARAM_STR );
    $sth->bindValue( ':email', '%' . $q . '%', PDO::PARAM_STR );
    $sth->execute();

    $array_data = array();
    while( list( $customer_id, $fullname, $phone, $address, $email ) = $sth->fetch( 3 ) )
    {
        $array_data[] = array(
            'key' => $customer_id,
            'value' => $fullname . ' - ' . $phone . ' - ' . $email,
            'fullname' => $fullname,
            'phone' => $phone,
            'address' => $address,
            'email' => $email,
        );
    }
}


header( 'Cache-Control: no-cache, must-revalidate' );
header( 'Content-type: application/json' );

ob_start( 'ob_gzhandler' );
echo json_encode( $array_data );
exit();
