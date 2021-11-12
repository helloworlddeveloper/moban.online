<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 19 Jun 2014 09:53:21 GMT
 */


if ($userid > 0)
{
    $sql = 'SELECT t1.userid, t1.code, t1.possitonid, t1.agencyid, t1.numsubcat, t1.subcatid, t1.datatext, t2.username, concat(t2.last_name, t2.first_name) fullname, t2.email, t1.mobile 
    FROM ' . NV_IS_TABLE_AFFILIATE . '_users t1, ' . NV_USERS_GLOBALTABLE . ' t2 WHERE t1.userid = t2.userid AND status=1 AND t1.parentid=' . $userid;
    $res = $db->query( $sql );

    $result = $db->query( $sql );
    $array = array();
    $i = 0;
    while( $row = $result->fetch() )
    {
        $array[$i] = array();
        $row['datatext'] = unserialize( $row['datatext'] );
        $array[$i]["userid"] = $row['userid'];
        $array[$i]["orderid"] = 0;
        $array[$i]["code"] = $row['code'];
        $array[$i]["username"] = $row['username'];
        $array[$i]["fullname"] = $row['fullname'];
        $array[$i]["email"] = $row['email'];
        $array[$i]["mobile"] = $row['mobile'];
        $array[$i]["address"] = $row['datatext']['address'];

        $i++;
    }

    echo json_encode($array);
}
else
{
    echo json_encode(array());
}