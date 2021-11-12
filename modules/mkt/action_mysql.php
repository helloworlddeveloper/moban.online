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
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_usersend;";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_statisticuser;";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_jobs;";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_relationship;";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_from;";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_listevents";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_usersevents";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_message";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_eventtype";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_events";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_measure";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config_mkt";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_smscontent";
$sql_create_module = $sql_drop_module;

//bang thong tin khach moi
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . " (
 id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
 adminid mediumint(8) unsigned NULL,
 provinceid mediumint(8) unsigned NOT NULL,
 districtid mediumint(8) unsigned NOT NULL,
 first_name varchar(150) NOT NULL,
 last_name varchar(150) CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL,
 full_name varchar(150) NOT NULL,
 birthday int(11) NOT NULL DEFAULT '0' COMMENT 'Ngày sinh',
 sex tinyint(1) unsigned NOT NULL DEFAULT '0',
 address varchar(250) NOT NULL COMMENT 'Địa chỉ',
 email varchar(100) NOT NULL COMMENT 'Email',
 mobile varchar(20) NOT NULL COMMENT 'SĐT di động',
 facebook varchar(100) NOT NULL COMMENT 'Facebook',
 from_by tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Dữ liệu từ kênh',
 gmap_lat float DEFAULT '0' COMMENT 'Vĩ độ',
 gmap_lng float DEFAULT '0' COMMENT 'Kinh độ',
 add_time int(11) NOT NULL DEFAULT '0',
 edit_time int(11) NOT NULL DEFAULT '0',
 mkt_time int(11) NULL DEFAULT '0',
 remkt_time int(11) NULL DEFAULT '0',
 status tinyint(1) unsigned NOT NULL DEFAULT '0',
 note varchar(250) NULL DEFAULT NULL,
 PRIMARY KEY (id),
 UNIQUE KEY full_name (full_name, mobile)
) ENGINE=MyISAM";

//bang giao user tu cty cho NPP
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_usersend (
  mktid int(10) unsigned NOT NULL,
  adminid int(10) unsigned NOT NULL,
  addtime int(10) unsigned NOT NULL DEFAULT '0',
  status tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1 : hiệu lực, 0 hết liệu lực',
  KEY status (status)
) ENGINE=MyISAM";

//bang luu tru dem so luong khach giao cho cac dau nhanh
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_statisticuser(
  adminid int(10) unsigned NOT NULL,
  total int(10) unsigned NOT NULL COMMENT 'SL da giao',
  KEY total (total)
) ENGINE=MyISAM";

//bang du lieu tu kenh nao
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_from (
 from_id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
 from_name VARCHAR(150) NOT NULL,
 weight smallint(4) NOT NULL DEFAULT '0' COMMENT 'STT',
 status tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1 : Hiển thị, 0 Ẩn',
 PRIMARY KEY (from_id)
) ENGINE=MyISAM";


//bang cac su kien cua cty
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_listevents (
  id int(11) NOT NULL AUTO_INCREMENT,
  title varchar(250) NOT NULL,
  description text NOT NULL,
  contactname varchar(100) NOT NULL COMMENT 'Người liên hệ',
  contactmobile varchar(100) NOT NULL COMMENT 'SĐT liên hệ',
  addressevent varchar(250) NOT NULL COMMENT 'dia chi dien ra su kien',
  timeevent int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TG dien ra su kien',
  provinceid int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Khu vuc dien ra su kien',
  timeclose int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'TG khoa dk su kien',
  num_register int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Số khách mời đăng ký',
  num_agree int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Số khách mời xác nhận tham gia',
  num_join int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Số khách mời đến tham dự',
  weight smallint(4) NOT NULL DEFAULT '0',
  status tinyint(4) NOT NULL,
  addtime int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY  (id)
) ENGINE=MyISAM";

//bang cac thanh vien tham gia su kien cua cty
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_usersevents (
  eventid int(10) unsigned NOT NULL,
  customerid int(10) unsigned NOT NULL,
  full_name varchar(250) NOT NULL,
  sex tinyint(1) unsigned NOT NULL DEFAULT '0',
  address varchar(250) NOT NULL COMMENT 'Địa chỉ',
  email varchar(100) NOT NULL COMMENT 'Email',
  mobile varchar(20) NOT NULL COMMENT 'SĐT di động',
  addtime int(10) unsigned NOT NULL DEFAULT '0',
  status tinyint(4) NOT NULL,
  PRIMARY KEY customerid (eventid, customerid)
) ENGINE=MyISAM";

//Message gui cho khach hang dang ky su kien
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_message (
 id int(10) unsigned NOT NULL auto_increment,
 eventid int(10) unsigned NOT NULL,
 title varchar(250) NOT NULL,
 content TEXT NOT NULL,
 timesend int(10) unsigned NOT NULL default '0',
 active tinyint(1) NOT NULL COMMENT '1: kích hoạt, 0 không',
 PRIMARY KEY (id),
 KEY timesend (timesend),
 KEY active (active)
) ENGINE=MyISAM";

//bang du các sự kiện
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_eventtype (
 eventtype_id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
 eventtype_name VARCHAR(150) NOT NULL,
 color VARCHAR(15) NOT NULL DEFAULT '',
 weight smallint(4) NOT NULL DEFAULT '0' COMMENT 'STT',
 status tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1 : Hiển thị, 0 Ẩn',
 PRIMARY KEY (eventtype_id)
) ENGINE=MyISAM";

//bang ghi thong tin các hành dộng của học sinh
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_events (
 eventid int(10) unsigned NOT NULL AUTO_INCREMENT,
 customerid mediumint(8) unsigned NOT NULL,
 measureid smallint(5) unsigned NOT NULL,
 adminid mediumint(8) unsigned NOT NULL,
 addtime int(11) NOT NULL DEFAULT '0',
 eventtype tinyint(1) unsigned NOT NULL DEFAULT '0',
 content text COMMENT 'Nội dung',
 PRIMARY KEY (eventid)
) ENGINE=MyISAM";

//bang thang do tiem nang khi MKT
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_measure (
 measure_id smallint(5) unsigned NOT NULL AUTO_INCREMENT,
 measure_name VARCHAR(150) NOT NULL,
 weight smallint(4) NOT NULL DEFAULT '0' COMMENT 'STT',
 status tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1 : Hiển thị, 0 Ẩn',
 PRIMARY KEY (measure_id)
) ENGINE=MyISAM";


$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config_mkt (
 group_id smallint(5) NOT NULL,
 addhistory tinyint(4) NOT NULL,
 PRIMARY KEY (group_id)
) ENGINE=MyISAM";

//SMS cho su kien
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_smscontent (
 id int(10) unsigned NOT NULL auto_increment,
 eventid int(10) unsigned NOT NULL,
 title varchar(250) NOT NULL COMMENT 'Chỉ dành cho App',
 content TEXT NOT NULL,
 hoursend tinyint(1) unsigned NOT NULL default '0' COMMENT 'Tin dc gui trc bao nhieu gio',
 addtime int(10) unsigned NOT NULL default '0',
 sendusers tinyint(1) NOT NULL COMMENT 'Gửi cho NPP trong hệ thống, 1 = yes',
 status tinyint(1) NOT NULL,
 PRIMARY KEY (id),
 KEY eventid (eventid)
) ENGINE=MyISAM";

$data = array();
$data['sms_on'] = 1;
$data['sms_type'] = 2;
$data['apikey'] = '81CF49D2388126412DB2E7CE63CA46';
$data['secretkey'] = 'EE310DB80631F52381480F085CAF8D';
$data['brandname'] = 'CASH13';
$data['per_page'] = '30';
$data['permissions_users'] = '';
foreach ($data as $config_name => $config_value) {
    $sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', " . $db->quote($module_name) . ", " . $db->quote($config_name) . ", " . $db->quote($config_value) . ")";
}