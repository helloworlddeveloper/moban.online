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
    $sql = "SELECT id,title FROM " . NV_IS_LANG_TABLE_AFFILIATE . "_province WHERE status = 1 ORDER BY weight ASC";
    $result = $db->query( $sql );
    $array = array();
    $i = 0;
    while( $row = $result->fetch() )
	{
        $array[$i] = array();
        $array[$i]["provinceid"] = $row['id'];
        $array[$i]["province"] = $row['title'];

		$i++;
	}
    
    echo json_encode($array);
}
else
{
    echo json_encode(array()); 
}
