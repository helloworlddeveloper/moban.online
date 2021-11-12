<?php

/**
 * @Project NUKEVIET 4.x
 * @Author TDFOSS.,LTD (anvh.ceo@gmail.com)
 * @Copyright (C) 2018 TDFOSS.,LTD. All rights reserved
 * @Createdate Fri, 12 Jan 2018 02:38:03 GMT
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$module_version = array(
	'name' => 'Inventory',
	'modfuncs' => 'main,producer,producttype,department,unit,product,addproduct,inventory,inventory-list,inventory-detail,tag-product,content-tag',
	'change_alias' => 'main,list,content',
	'submenu' => 'main,producer,producttype,department,unit,product,inventory,inventory-list,tag-product',
	'is_sysmod' => 0,
	'virtual' => 1,
	'version' => '1.0.01',
	'date' => 'Fri, 12 Jan 2018 02:38:03 GMT',
	'author' => 'TDFOSS.,LTD (anvh.ceo@gmail.com)',
	'uploads_dir' => array($module_name),
	'note' => ''
);