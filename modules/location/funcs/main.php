<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate Wed, 07 Sep 2011 15:07:06 GMT
 */

if ( ! defined( 'NV_IS_MOD_LOCATION' ) ) die( 'Stop!!!' );

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

$array_data = array();

$sql = "SELECT id, alias, title  FROM " . $db_config['prefix'] . "_". NV_LANG_DATA . "_" . $module_data . "_mien ORDER BY weight ASC";
$result = $db->query( $sql );
$link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=";
////////////////////////////////////////////////////////

while ( list( $id, $alias, $title ) =  $result->fetch() )
{
    $arr_mien = array("id" => $id, "title" => $title, "alias" => $alias);
    $sql_country = "SELECT id, title, alias  FROM " . $db_config['prefix'] . "_". NV_LANG_DATA . "_" . $module_data . "_province WHERE idmien = ".$id." ORDER BY weight ASC";
    
    $result_country = $db->query( $sql_country );
    $array_country = array();
    while ( list( $id_i, $title_i, $alias_i ) = $db->sql_fetchrow( $result_country ) )
    {
        $array_country[] = array("id" => $id_i, "title" => $title_i, "alias" => $alias_i);
    }
    $arr_mien['country'] = $array_country;
    $array_data[$id] = $arr_mien; 
}


$contents = nv_theme_location_main( $array_data );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>