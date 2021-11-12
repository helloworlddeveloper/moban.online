<?php

/**
 * @Project NUKEVIET 4.x - module Notice
 * @Author Mr. An (anvh.ceo@gmail.com)
 * @Copyright (C) 2013 Mr. An. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Fri, 01 Feb 2013 04:11:16 GMT
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$module_version = array( //
		"name" => "Notice", //
		"modfuncs" => "main,detail,search,perpage", //
		"submenu" => "main,detail,search", //
		"is_sysmod" => 0, //
		"virtual" => 1, //
		"version" => "4.0.0", //
		"date" => "Fri, 1 Feb 2013 04:11:16 GMT", //
		"author" => "Mr. An (anvh.ceo@gmail.com)", //
		"uploads_dir" => array($module_name), //
		"note" => "Display notice message" //
	);