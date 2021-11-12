<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Mr.Thang (kid.apt@gmail.com)
 * @Copyright (C) 2012 Mr.Thang. All rights reserved
 * @Createdate 2-10-2010 20:59
 */

if( ! defined( 'NV_MAINFILE' ) )
    die( 'Stop!!!' );

//Bang danh sach cac table trong CSDL se lay ra de hien popup dong thoi lay key va title trong bang
function nv_popup_get_table( $module_data )
{
    $array_table_key_name[NV_PREFIXLANG . '_' . $module_data . '_cat'] = array(
        'keycolumn' => 'catid',
        'keytitle' => 'title',
        );
    $array_table_key_name[NV_PREFIXLANG . '_' . $module_data . '_topics'] = array(
        'keycolumn' => 'topicid',
        'keytitle' => 'title',
        );
    $array_table_key_name[NV_PREFIXLANG . '_' . $module_data . '_tags'] = array(
        'keycolumn' => 'tid',
        'keytitle' => 'keywords',
        );
    $array_table_key_name[NV_PREFIXLANG . '_' . $module_data . '_rows'] = array(
        'keycolumn' => 'id',
        'keytitle' => 'title',
        );
    return $array_table_key_name;
}
global $op, $id, $topicid, $catid, $popup_contentid;

if( $op == 'detail'){
    $popup_contentid = $id;
}elseif( $op == 'viewcat'){
    $popup_contentid = $catid;
}elseif( $op == 'tag'){
    $popup_contentid = $tid;
}
elseif( $op == 'topic'){
    $popup_contentid = $topicid;
}
