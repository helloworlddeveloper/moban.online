<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Mr.Thang (kid.apt@gmail.com)
 * @Copyright (C) 2012 Mr.Thang. All rights reserved
 * @Createdate 3/25/2010 18:6
 */

if( ! defined( 'NV_SYSTEM' ) )
    die( 'Stop!!!' );

if( ! nv_function_exists( 'nv_popup' ) )
{
    /**
     * nv_popup()
     * 
     * @return
     */
    function nv_popup( $block_config )
    {
        global $db, $nv_Cache, $lang_module, $global_config, $site_mods, $my_head, $module_file, $module_name, $module_data, $op, $popup_contentid;
        $module = $block_config['module'];
        $mod_data = $site_mods[$module]['module_data'];
        
        // Get value
        $sql = "SELECT config_name, config_value FROM " . NV_PREFIXLANG . "_" . $mod_data;
        $list = $nv_Cache->db( $sql, '', $module );
        $array_config_data = array();
        
        foreach( $list as $values )
        {
            $array_config_data[$values['config_name']] = $values['config_value'];
        }
        $is_show_popup = false;
        if( file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/popup_connect.php' ) )
        {
           
            require_once NV_ROOTDIR . '/modules/' . $module_file . '/popup_connect.php';

            $array_content_popup = array();
            //kiem tra hien thi popup theo tung op, module, id
            $db->sqlreset()->select( 'COUNT(*)' )->from( NV_PREFIXLANG . '_' . $mod_data . '_bymodule WHERE status =1 AND module_name = ' . $db->quote( $module_name ) . ' AND op_name=' . $db->quote( $op ) );

            $sth = $db->prepare( $db->sql() );
            $sth->execute();
            $num_items = $sth->fetchColumn();
            if( $num_items > 0 )
            {
                $db->select( '*' )->order( 'edit_time DESC' );
                $sth = $db->prepare( $db->sql() );
                $sth->execute();
                while( $row = $sth->fetch() )
                {
                    if( $row['contentid'] > 0 )
                    {
                        $array_option = nv_popup_get_table( $module_data );
                        //hien popup
                        if( isset( $array_option[$row['table_show']] ) && $row['op_name'] == $op && $module_name == $row['module_name'] && $popup_contentid == $row['contentid'] && ! isset( $_COOKIE['popup_site_' . $module_data . '_' . $op . '_' . $popup_contentid] ) )
                        {
                            $is_show_popup = true;
                            $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module . '/' . $row['image'];
                            $array_content_popup = $row;
                            break;
                        }
                    }else{
                        if( $row['op_name'] == $op && $module_name == $row['module_name'] && $row['contentid'] == 0 && !isset( $_COOKIE['popup_site_' . $module_data . '_' . $op . '_' . 1 . $row['contentid']] ) )
                        {
                            $is_show_popup = true;
                            $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module . '/' . $row['image'];
                            $array_content_popup = $row;
                            break;
                        }
                    }
                } 
            }
        }
        if( $array_config_data['active'] && $is_show_popup )
        {

            if( $module != $module_name )
            {
                include NV_ROOTDIR . "/modules/" . $module . "/language/" . NV_LANG_INTERFACE . '.php';
            }
            if( file_exists( NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module . "/block.popup.tpl" ) )
            {
                $block_theme = $global_config['module_theme'];
            }
            elseif( file_exists( NV_ROOTDIR . "/themes/" . $global_config['site_theme'] . "/modules/" . $module . "/block.popup.tpl" ) )
            {
                $block_theme = $global_config['site_theme'];
            }
            else
            {
                $block_theme = "default";
            }

            $my_head .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . NV_BASE_SITEURL . "themes/" . $block_theme . "/css/popup.css\" />";

            $xtpl = new XTemplate( "block.popup.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $module );
            $xtpl->assign( 'module', $module );
            $xtpl->assign( 'module_data', $module_data );
            $xtpl->assign( 'BLANG', $lang_module );
            $xtpl->assign( 'op', $op );
            $xtpl->assign( 'popup_contentid', $popup_contentid );
            
            
            $db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $mod_data . '_bymodule SET numview = numview+1 WHERE id=' . $array_content_popup['id'] );
            
            if( $array_config_data['timer_close'] )
            {
                $array_config_data['timer_close'] = $array_config_data['timer_close'] * 1000;
                $xtpl->parse( 'main.timer_close' );
            }
            $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $mod_data . '_province WHERE status=1 ORDER BY weight ASC';
        	$result = $db->query( $sql );
        	$list = array();
        	while( $row = $result->fetch() )
        	{
        	   $xtpl->assign( 'PROVINCE', $row );
                $xtpl->parse( 'main.provinceid' );
        	}
            
            $array_config_data['timer_open'] = $array_config_data['timer_open'] * 1000;
            $array_content_popup['modulename'] = $module_name;
            $array_content_popup['idpost'] = 0;
            $xtpl->assign( 'DATA', $array_content_popup );
            $xtpl->assign( 'CONFIG', $array_config_data );

            $xtpl->parse( 'main.showtype' . $array_content_popup['showtype']);
            $xtpl->parse( 'main' );
            return $xtpl->text( 'main' );
        }
    }
}

$content = nv_popup( $block_config );

?>