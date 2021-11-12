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
    $array = array(
        "prices" => "https://cash13.vn//uploads/page/bang-gia-nhap-cho-dai-ly_1.jpg",
        "profits" => "https://cash13.vn//uploads/page/minh-hoa-loi-nhuan_1.jpg",
        "bonus" =>  "https://cash13.vn//uploads/page/luong-thuong-dai-ly.jpg"
    );

    echo json_encode($array);
}
else
{
    echo json_encode(array()); 
}