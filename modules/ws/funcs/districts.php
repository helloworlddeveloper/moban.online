<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 19 Jun 2014 09:53:21 GMT
 */

$provinceid = $nv_Request->get_int('provinceid', 'post');
if ($provinceid > 0 )
{
    $sql = "SELECT id,title FROM " . NV_IS_LANG_TABLE_AFFILIATE . "_district WHERE status = 1 and idprovince = " . $provinceid . " ORDER BY weight ASC";
    $result = $db->query( $sql );
    $array = array();
    $i = 0;
    while( $row = $result->fetch() )
    {
        $array[$i] = array();
        $array[$i]["districid"] = $row['id'];
        $array[$i]["district"] = $row['title'];

        $i++;
    }
    
    echo json_encode($array);
}
else
{
    echo json_encode(array()); 
}