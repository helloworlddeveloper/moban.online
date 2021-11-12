<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Mr.An (anvh.ceo@gmail.com)
 * @Copyright (C) 2017 Mr.Thang. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10-01-2017 20:08
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$module_version = array( //
    "name" => "Notification", // Tieu de module
    "modfuncs" => "main,sendtofb", //
    "is_sysmod" => 0, //
    "virtual" => 0, //
    "version" => "4.1.10", //
    "date" => "Tue, 10 Jan 2017 11:40:15 GMT", //
    "author" => "Mr.An (anvh.ceo@gmail.com)", //
    "note" => "", //
    "uploads_dir" => array( 
        $module_name, //
    ) 
);

?>