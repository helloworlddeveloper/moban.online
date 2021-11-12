<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 19 Jun 2014 09:53:21 GMT
 */

if ($userid  > 0 )
{
    $sql = 'SELECT t1.id, t1.title as event, t1.description, t1.contactname, t1.contactmobile, t1.addressevent, t1.timeevent, t2.title as province ' .
           'FROM ' . NV_PREFIXLANG . '_mkt_listevents t1 , ' . NV_PREFIXLANG . '_popup_province t2 ' .
           'WHERE t1.provinceid = t2.id and t1.status = 1 AND timeevent>=' . NV_CURRENTTIME . ' ORDER BY t1.timeevent ASC';

    echo $sql;
    $result = $db->query( $sql );
    $array = array();
    $i = 0;
    while( $row = $result->fetch() )
	{
        $array[$i] = array();
        $array[$i]["eventId"] = $row['id'];
        $array[$i]["eventTitle"] = $row['event'];
        $array[$i]["eventdescription"] = strip_tags( $row['description'] );
        $array[$i]["eventAddress"] = $row['addressevent'];
        $array[$i]["eventContact"] = $row['contactname'] . ' - ' . $row['contactmobile'];
        $array[$i]["eventTime"] = date('d/m/Y H:i', $row['timeevent']);
        $array[$i]["eventProvince"] = $row['province'];

		$i++;
	}
    
    echo json_encode($array);
}
else
{
    echo json_encode(array()); 
}