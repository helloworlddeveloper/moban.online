<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 19 Jun 2014 09:53:21 GMT
 */

$array = array();
if ($userid  > 0 ) {
    $sql = "SELECT id,code,title,image,price_retail FROM " . NV_IS_LANG_TABLE_SM . "_product ORDER BY weight ASC";
    $result = $db->query($sql);
    $i = 0;
    while ($row = $result->fetch()) {
        $array[$i] = array();
        $array[$i]["productId"] = $row['id'];
        $array[$i]["productCode"] = $row['code'];
        $array[$i]["productName"] = $row['title'];
        $array[$i]["productPrice"] = $row['price_retail'];
        if (!empty($row['image'])) {
            $array[$i]["productImage"] = NV_MY_DOMAIN . NV_BASE_SITEURL . NV_ASSETS_DIR . "/sm/" . $row['image'];
        } else {
            $array[$i]["productImage"] = '';
        }

        $i++;
    }
}
echo json_encode($array);
