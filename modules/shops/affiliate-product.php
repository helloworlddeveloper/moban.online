<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 21-04-2011 11:17
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}
 
//danh cho tim kiem
if ( empty( $q_search )){
    $db->sqlreset()->select( 't1.id, t1.' . NV_LANG_DATA . '_title AS title, t1.' . NV_LANG_DATA . '_alias AS alias, t2.' . NV_LANG_DATA . '_alias AS cat_alias' )->from( $db_config['prefix'] . '_' . $mod_data . '_rows AS t1' )
        ->join( 'INNER JOIN ' . $db_config['prefix'] . '_' . $mod_data . '_catalogs AS t2 ON t1.listcatid=t2.catid')
        ->where( 't1.status=1' );

    $result = $db->query(  $db->sql() );
    $array_item = array();
    while ($row = $result->fetch()) {
        $array_item[$row['id']] = array(
            'id' => $row['id'],
            'title' => $row['title'],
            'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $mod_name . '&' . NV_OP_VARIABLE . '=' . $row['cat_alias'] . '/' . $row['alias'] . $global_config['rewrite_exturl'],
        );
    }
}else{
    //phan nap lai menu
    $db->sqlreset()->select( 't1.id, t1.' . NV_LANG_DATA . '_title AS title, t1.' . NV_LANG_DATA . '_alias AS alias, t2.' . NV_LANG_DATA . '_alias AS cat_alias' )->from( $db_config['prefix'] . '_' . $mod_data . '_rows AS t1' )
        ->join( 'INNER JOIN ' . $db_config['prefix'] . '_' . $mod_data . '_catalogs AS t2 ON t1.listcatid=t2.catid')
        ->where( 't1.' . NV_LANG_DATA . '_title LIKE :title' )->limit( 20 );

    $sth = $db->prepare( $db->sql() );
    $sth->bindValue( ':title', '%' . $q . '%', PDO::PARAM_STR );
    $sth->execute();
    $array_item = array();
    while( $row = $sth->fetch( ) )
    {
        $array_item[$row['id']] = array(
            'key' => $row['id'],
            'value' => $row['title'],
            'listcatid' => $row['listcatid'],
            'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $mod_name . '&' . NV_OP_VARIABLE . '=' . $row['cat_alias'] . '/' . $row['alias'] . $global_config['rewrite_exturl'],

        );
    }
}

