<?php

/**
 * @Project NUKEVIET 4.x
 * @Author EDUSGROUP.JSC (thangbv@edus.vn)
 * @Copyright (C) 2014 EDUSGROUP.JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 24 Dec 2014 08:50:19 GMT
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$q = $nv_Request->get_title('term', 'get', '', 1);
if (empty($q)) {
    return;
}

$db_slave->sqlreset()
    ->select('id, title, mobile')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_teacher')
    ->where('title LIKE :title OR infotext LIKE :infotext OR mobile LIKE :mobile OR email LIKE :email')
    ->order('weight ASC')
    ->limit(50);

$sth = $db_slave->prepare($db_slave->sql());
$sth->bindValue(':title', '%' . $q . '%', PDO::PARAM_STR);
$sth->bindValue(':infotext', '%' . $q . '%', PDO::PARAM_STR);
$sth->bindValue(':email', '%' . $q . '%', PDO::PARAM_STR);
$sth->bindValue(':mobile', '%' . $q . '%', PDO::PARAM_STR);
$sth->execute();

$array_data = array();
while (list($id, $title, $mobile) = $sth->fetch(3)) {
    $array_data[] = array( 'key' => $id, 'value' => $title . ' - ' . $mobile );
}


header('Cache-Control: no-cache, must-revalidate');
header('Content-type: application/json');

ob_start('ob_gzhandler');
echo json_encode($array_data);
exit();
