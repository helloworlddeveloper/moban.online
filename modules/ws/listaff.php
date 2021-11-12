<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 19 Jun 2014 09:53:21 GMT
 */
require_once NV_ROOTDIR . '/modules/' . $module_file . '/funcs/checkkey.php';

$userid = $nv_Request->get_int('userid', 'post');

if ($userid > 0 )
{
    $sql = 'SELECT t1.userid, t1.code, t1.possitonid, t1.agencyid, t1.numsubcat, t1.subcatid, t2.username, concat(t2.last_name, t2.first_name) fullname, t2.email, t1.mobile 
    FROM ' . NV_IS_TABLE_AFFILIATE . '_users t1, ' . NV_USERS_GLOBALTABLE . ' t2 WHERE t1.userid = t2.userid AND status=1 AND t1.userid=' . $userid;
    $res = $db->query( $sql );
    $array_data = $res->fetch();

    if( $array_data['possitonid'] > 0 ){
        $type = isset($array_possiton[$array_data['possitonid']])? $array_possiton[$array_data['possitonid']]['title'] : 'N/A';
    }elseif( $array_data['agencyid'] > 0 ){
        $type = isset($array_agency[$array_data['agencyid']])? $array_agency[$array_data['agencyid']]['title'] : 'N/A';
    }

    $array_reponsive[$array_data['userid']] = array(
        'userid' => $array_data['userid'],
        'type' => $type,
        'infor' => $type . '-' . $array_data['code'] . '-' . $array_data['fullname'] . '-[' . $array_data['numsubcat'] . ']',
        'downline' => array(),
    );

    if( $array_data['numsubcat'] > 0){
        $array_reponsive[$array_data['userid']]['downline'] = get_sub_nodes_users( $array_data['subcatid'] );
    }

    echo json_encode($array_reponsive);
}
else
{
    echo json_encode(array()); 
}