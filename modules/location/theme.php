<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate Wed, 07 Sep 2011 15:07:06 GMT
 */

if ( ! defined( 'NV_IS_MOD_LOCATION' ) ) die( 'Stop!!!' );

/**
 * nv_theme_location_main()
 * 
 * @param mixed $array_data
 * @return
 */
function nv_theme_location_main ( $array_data )
{
    global $global_config, $module_name, $module_data, $module_file, $lang_module, $module_config, $module_info, $op, $array_workplace;
    
    
    $xtpl = new XTemplate( $op . ".tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
    $xtpl->assign( 'LANG', $lang_module );
    foreach ( $array_data as $array_data_i ){
         $xtpl->assign( 'mien', $array_data_i['title'] );
         $i = 0;
         foreach( $array_data_i['country'] as $temp ){
            $xtpl->assign( 'ROW', $temp );
             if($i % 3 == 0 )
            $xtpl->parse( 'main.clear' );
            $xtpl->parse( 'main.cat.loop' );
            $i++;
         }
         $xtpl->parse( 'main.cat' );
    }
    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

?>