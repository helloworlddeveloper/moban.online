<?php

/**
 * @Project NUKEVIET 4.x - module Notice
 * @Author Nguyễn Thái Hà (tlthaiha@gmail.com)
 * @Copyright (C) 2013 Nguyễn Thái Hà. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Fri, 01 Feb 2013 04:11:16 GMT
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) )
	die( 'Stop!!!' );
$sql_drop_module = array();
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_cat";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows";

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_cat (
id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
title varchar(255) NOT NULL,
status tinyint(1) unsigned NOT NULL DEFAULT '0',
PRIMARY KEY (id)
)ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows (
id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
catid mediumint(8) unsigned NOT NULL,
title mediumtext NOT NULL,
html mediumtext NOT NULL,
link mediumtext NOT NULL,
pubtime int(11) unsigned NOT NULL DEFAULT '0',
exptime int(11) unsigned NOT NULL DEFAULT '0',
userid mediumint(8) unsigned NOT NULL,
weight smallint(4) NOT NULL DEFAULT '0',
status tinyint(1) unsigned NOT NULL DEFAULT '0',
PRIMARY KEY (id)
)ENGINE=MyISAM";

$sql_create_module[] = "INSERT IGNORE INTO ". $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_cat (id,title,status) VALUES (1,". $db->quote('Thông báo chung') .",1);";