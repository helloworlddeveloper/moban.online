<?php

/**
 * @Project NUKEVIET 4.x
 * @Author EDUSGROUP.JSC (thangbv@edus.vn)
 * @Copyright (C) 2014 EDUSGROUP.JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 14 Mar 2017 10:56:01 GMT
 */

if(!defined('NV_IS_FILE_ADMIN'))
{
	die('Stop!!!');
}

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $mod_data . '_class WHERE status=1 ORDER BY weight ASC';
$result = $db->query($sql);
while($row = $result->fetch())
{
	$array_item[$row['id']] = array(
		'parentid' => 0,
		'key' => $row['id'],
		'title' => $row['title'],
		'alias' => $row['alias']);
}