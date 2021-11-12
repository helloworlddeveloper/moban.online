<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Mr.Thang (kid.apt@gmail.com)
 * @Copyright (C) 2017 Mr.Thang. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10-01-2017 20:08
 */

if ( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$sql_drop_module = array();
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data;
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . '_message_queue';
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . '_message_history';
$sql_create_module = $sql_drop_module;

//tin thong bao
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . " (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  message varchar(250) NOT NULL,
  description text NOT NULL,
  url varchar(250) NOT NULL,
  icon varchar(250) NOT NULL,
  author varchar(250) NOT NULL,
  adminid_send mediumint(8) unsigned NOT NULL DEFAULT '0',
  addtime int(10) unsigned NOT NULL DEFAULT '0',
  groupsend varchar(250) NOT NULL DEFAULT '' COMMENT 'Nhom nhan thong tin, 0 tat ca, 1 level 1, 2 level 2...',
  status tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
)ENGINE=MyISAM";


//Message Queue
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_message_queue (
 id int(10) unsigned NOT NULL auto_increment,
 mid mediumint(8) unsigned NOT NULL,
 userid mediumint(8) unsigned NOT NULL,
 url varchar(250) NOT NULL,
 icon varchar(250) NOT NULL,
 title varchar(250) NOT NULL,
 receiver varchar(250) NOT NULL COMMENT 'Thiet bi nhan message',
 content text NULL,
 timesend int(10) unsigned NOT NULL default '0',
 active tinyint(1) NOT NULL COMMENT '1: kích hoạt, 0 không',
 PRIMARY KEY (id),
 KEY timesend (timesend),
 KEY active (active)
) ENGINE=MyISAM";

//Message history
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_message_history (
 id int(10) unsigned NOT NULL auto_increment,
 mid mediumint(8) unsigned NOT NULL,
 userid mediumint(8) unsigned NOT NULL,
 url varchar(250) NOT NULL,
 icon varchar(250) NOT NULL,
 title varchar(250) NOT NULL,
 receiver varchar(250) NOT NULL COMMENT 'Thiet bi nhan message',
 content TEXT NOT NULL,
 timesend int(10) unsigned NOT NULL default '0',
 timesent int(10) unsigned NOT NULL default '0',
 smsid varchar(50) NOT NULL default '',
 status tinyint(1) NOT NULL,
 PRIMARY KEY (id),
 KEY timesend (timesend)
) ENGINE=MyISAM";

$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'timeview', '20')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'firebase_url', '')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'firebase_api_access_key', '')";