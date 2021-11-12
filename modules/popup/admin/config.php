<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Mr.Thang (kid.apt@gmail.com)
 * @Copyright (C) 2014 Mr.Thang. All rights reserved
 * @Createdate 21 Mar 2016 03:44:56 GMT
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) )
    die( 'Stop!!!' );

$page_title = $lang_module['main'];

$xtpl = new XTemplate( $op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'ACTION', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );

if( $nv_Request->isset_request( 'save', 'post' ) )
{
    $popup['active'] = $nv_Request->get_bool( 'active', 'post', 0 );
    $popup['timer_open'] = $nv_Request->get_int( 'timer_open', 'post', '' );
    $popup['timer_close'] = $nv_Request->get_int( 'timer_close', 'post', '' );
    
    $sth = $db->prepare( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . " SET config_value = :config_value WHERE config_name = :config_name" );

    foreach( $popup as $config_name => $config_value )
    {
        $sth->bindParam( ':config_name', $config_name, PDO::PARAM_STR );
        $sth->bindParam( ':config_value', $config_value, PDO::PARAM_STR );
        $sth->execute();
    }

    $nv_Cache->delMod($module_name);
    Header( "Location: " . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
    die();
}

// Get value
$sql = "SELECT config_name, config_value FROM " . NV_PREFIXLANG . "_" . $module_data;
$list = $nv_Cache->db( $sql, '', $module_name );

$row = array();
foreach( $list as $values )
{
    $row[$values['config_name']] = $values['config_value'];
}

$xtpl->assign( 'ACTIVE', $row['active'] ? 'checked="checked"' : '' );
$xtpl->assign( 'DATA', $row );

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>