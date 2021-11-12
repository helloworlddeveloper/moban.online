<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 20:59
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$sql_drop_module = array();

$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data;
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data  . '_other';
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data  . '_cat';
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data  . '_group';
$sql_create_module = $sql_drop_module;

//bang thong tin lich
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . " (
  id int(11) NOT NULL AUTO_INCREMENT,
  catid mediumint(8) unsigned NOT NULL,
  groupid mediumint(8) unsigned NOT NULL,
  title varchar(250) NOT NULL,
  alias varchar(250) NOT NULL,
  description text NOT NULL,
  moderator varchar(250) NOT NULL COMMENT 'Người chủ trì',
  participants varchar(250) NOT NULL COMMENT 'Thành phần tham gia',
  addressevent varchar(250) NOT NULL COMMENT 'dia chi dien ra su kien',
  timeevent_begin int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TG bat dau su kien',
  timeevent_end int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TG ket thuc su kien',
  hour_minute_begin smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Gio phut dau su kien',
  hour_minute_end smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Gio phut ket thuc su kien',
  provinceid int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Khu vuc dien ra su kien',
  day_week tinyint(4) NOT NULL COMMENT 'Ngày trong tuần',
  status tinyint(4) NOT NULL,
  timefix tinyint(4) NOT NULL,
  addtime int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY  (id)
) ENGINE=MyISAM";

//bang thong tin lich khac
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_other (
  id int(11) NOT NULL AUTO_INCREMENT,
  catid mediumint(8) unsigned NOT NULL,
  groupid mediumint(8) unsigned NOT NULL,
  title varchar(250) NOT NULL,
  alias varchar(250) NOT NULL,
  description text NOT NULL,
  moderator varchar(250) NOT NULL COMMENT 'Người chủ trì',
  participants varchar(250) NOT NULL COMMENT 'Thành phần tham gia',
  addressevent varchar(250) NOT NULL COMMENT 'dia chi dien ra su kien',
  timeevent_begin int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TG bat dau su kien',
  timeevent_end int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TG ket thuc su kien',
  hour_minute_begin smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Gio phut dau su kien',
  hour_minute_end smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Gio phut ket thuc su kien',
  provinceid int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Khu vuc dien ra su kien',
  day_week tinyint(4) NOT NULL COMMENT 'Ngày trong tuần',
  status tinyint(4) NOT NULL,
  timefix tinyint(4) NOT NULL,
  addtime int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY  (id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_cat (
 id smallint(4) unsigned NOT NULL AUTO_INCREMENT,
 title varchar(250) NOT NULL,
 alias varchar(250) NOT NULL,
 note text NOT NULL,
 PRIMARY KEY (id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_group (
 id smallint(4) unsigned NOT NULL AUTO_INCREMENT,
 title varchar(250) NOT NULL,
 alias varchar(250) NOT NULL,
 note text NOT NULL,
 PRIMARY KEY (id)
) ENGINE=MyISAM";