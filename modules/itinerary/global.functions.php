<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 0:51
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

function nv_list_location( $selected_data = 0 )
{
    global $module_data, $nv_Cache, $module_name;

    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_location ORDER BY weight ASC";
    $result = $nv_Cache->db( $sql, 'id', $module_name );

    $array = array();
    if ( ! empty ( $result ) )
    {
        foreach ( $result as $row )
        {
            $array[$row['id']] = array(
                "id" => $row['id'],
                "title" => $row['title'],
                "note" => $row['note'],
                "selected" => ( $selected_data == $row['id'] ) ? " selected=\"selected\"" : ""
            );
        }
    }

    return $array;
}