<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2017 14:43
 */

if( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );


$q = $nv_Request->get_title( 'term', 'get', '', 1 );
if( empty( $q ) ) return;

$db->sqlreset()->select( 't1.userid, t1.username, t1.first_name, t1.last_name, t1.birthday, t1.email, t2.datatext' )->from( NV_USERS_GLOBALTABLE . ' AS t1' )->join('INNER JOIN ' . $db_config['prefix'] . '_affiliate_users AS t2 ON t1.userid=t2.userid')->where( 't1.username LIKE :username OR concat(t1.last_name," ",t1.first_name) LIKE :fullname OR t1.email LIKE :email' )->limit( 50 );

$sth = $db->prepare( $db->sql() );
$sth->bindValue( ':fullname', '%' . $q . '%', PDO::PARAM_STR );
$sth->bindValue( ':username', '%' . $q . '%', PDO::PARAM_STR );
$sth->bindValue( ':email', '%' . $q . '%', PDO::PARAM_STR );
$sth->execute();

$array_data = array();
while( list( $userid, $username, $first_name, $last_name, $birthday, $email, $datatext ) = $sth->fetch( 3 ) )
{
    $datatext = unserialize( $datatext );
    $fullname = nv_show_name_user( $first_name, $last_name, $username );
    $array_data[] = array(
        'key' => $userid,
        'value' => $username . ' - ' . $fullname . ' - ' . $email . ' - ' . $datatext['mobile'],
        'fullname' => $fullname,
        'mobile' => $datatext['mobile'],
        'birthday' => ( $birthday > 0 )? date('d/m/Y', $birthday) : '',
        'username' => $username,
        'email' => $email,
    );
}

header( 'Cache-Control: no-cache, must-revalidate' );
header( 'Content-type: application/json' );

ob_start( 'ob_gzhandler' );
echo json_encode( $array_data );
exit();
