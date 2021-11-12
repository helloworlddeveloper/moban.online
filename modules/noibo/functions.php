<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */

if(! defined('NV_SYSTEM'))
{
    die('Stop!!!');
}

define('NV_IS_MOD_PAGE', true);
define('TABLE_SHARE', $db_config['prefix'] . '_shops');

function nv_get_base_url($urlinfo)
{
    global $client_info;
    if(empty($urlinfo))
        $urlinfo = $client_info['selfurl'];
    $pu = parse_url($urlinfo);
    return $pu["host"];
}
function nvGetProductInfo( $prodctid )
{
    global $db;
    $sql = 'SELECT id, listcatid, publtime, ' . NV_LANG_DATA . '_title, ' . NV_LANG_DATA . '_alias, ' . NV_LANG_DATA . '_hometext, homeimgalt, homeimgfile, homeimgthumb, product_code, product_number, product_price, money_unit, discount_id, showprice,' . NV_LANG_DATA . '_gift_content, gift_from, gift_to FROM ' . TABLE_SHARE . '_rows WHERE id=' . $prodctid;
    return $db->query( $sql)->fetch();
}