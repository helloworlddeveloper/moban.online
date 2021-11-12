<?php

/**
 * @Project NUKEVIET 4.x
 * @Author EDUS.,JSC (thangbv@edus.vn)
 * @Copyright (C) 2014 EDUS.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 20:59
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$sql_drop_module = array();

$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data;
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_sharing";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config";

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . " (
 id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
 title varchar(255) NOT NULL,
 alias varchar(255) NOT NULL,
 uploadtime int(11) unsigned NOT NULL,
 updatetime int(11) unsigned NOT NULL DEFAULT '0',
 user_id mediumint(8) unsigned NOT NULL,
 user_name varchar(100) NOT NULL,
 link_file varchar(200) NOT NULL,
 fileupload text NOT NULL,
 filesize int(11) NOT NULL DEFAULT '0',
 status tinyint(1) unsigned NOT NULL DEFAULT '1',
 download_hits int(11) NOT NULL DEFAULT '0',
 PRIMARY KEY (id),
 KEY user_id (user_id)
)ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_sharing (
 fileid mediumint(8) unsigned NOT NULL,
 user_id mediumint(8) unsigned NOT NULL,
 downloaded tinyint(1) NOT NULL DEFAULT '0',
 KEY user_id (user_id),
 UNIQUE KEY key_name (user_id, fileid)
)ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config (
 config_name varchar(30) NOT NULL,
 config_value varchar(255) NOT NULL,
 UNIQUE KEY config_name (config_name)
)ENGINE=MyISAM";

$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config VALUES
('is_addfile', '1'),
('is_upload', '1'),
('groups_upload', '4'),
('maxfilesize', ''),
('upload_filetype', 'doc,xls,zip,rar,mp4,png,jpg,gif'),
('groups_addfile', '4'),
('is_zip', '0'),
('is_resume', '1'),
('max_speed', '0')";
