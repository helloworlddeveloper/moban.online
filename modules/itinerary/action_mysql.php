<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2018 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Fri, 12 Jan 2018 07:59:54 GMT
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$sql_drop_module = array();
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data;
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_location";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_commodity";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_cost";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_customer";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_vehicle";

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_location (
  id smallint(10) NOT NULL AUTO_INCREMENT,
  title varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên địa điểm',
  note text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Ghi chú',
  weight smallint(4) UNSIGNED NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . " (
  id int(11) NOT NULL AUTO_INCREMENT,
  title_itinerary varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên hành trình',
  time_start int(11) NOT NULL COMMENT 'Thời gian xuất phát ',
  time_end int(11) NOT NULL COMMENT 'Thời gian kết húc hành trình',
  localtion_start varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Địa điểm xuất phát',
  localtion_end varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Địa điểm kết thúc hành trình',
  vehicle varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Xe',
  PRIMARY KEY (id)
) ENGINE=MyISAM";


$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_commodity (
  id tinyint(11) NOT NULL AUTO_INCREMENT,
  commodity_name varchar(250) NOT NULL COMMENT 'Tên hàng hóa gửi',
  sender_name varchar(250) NOT NULL DEFAULT '' COMMENT 'Tên ng gửi',
  sender_mobile varchar(250) NOT NULL DEFAULT '' COMMENT 'SĐT ng gửi',
  receiver_name varchar(250) NOT NULL DEFAULT '' COMMENT 'Tên ng nhận',
  receiver_mobile varchar(250) NOT NULL DEFAULT '' COMMENT 'SĐT ng nhận',
  itinerary_id tinyint(11) NOT NULL COMMENT 'Mã hành trình',
  localtion_start varchar(250) NOT NULL DEFAULT '' COMMENT 'Địa điểm nhận hàng',
  localtion_end varchar(250) NOT NULL DEFAULT '' COMMENT 'Địa điểm trả hàng',
  qty tinyint(2) NOT NULL COMMENT 'Số lượng',
  price_ship double NOT NULL COMMENT 'Giá ship',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_cost (
  id tinyint(11) NOT NULL AUTO_INCREMENT,
  cost_name varchar(250) NOT NULL,
  note text COLLATE utf8mb4_unicode_ci NOT NULL,
  itinerary_id tinyint(11) NOT NULL COMMENT 'Mã hành trình',
  localtion_cost varchar(250) NOT NULL DEFAULT '' COMMENT 'Địa điểm chi tiền',
  price double NOT NULL COMMENT 'Số tiền chi',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_customer (
  id tinyint(11) NOT NULL AUTO_INCREMENT,
  fullname varchar(250) NOT NULL DEFAULT '',
  mobile varchar(250) NOT NULL DEFAULT '',
  itinerary_id tinyint(11) NOT NULL COMMENT 'Mã hành trình',
  localtion_customer_start varchar(250) NOT NULL DEFAULT '' COMMENT 'Địa điểm khách lên',
  localtion_customer_end varchar(250) NOT NULL DEFAULT '' 'Địa điểm khách xuống',
  qty_customer tinyint(2) NOT NULL COMMENT 'Số lượng đi cùng',
  price_ticket double NOT NULL COMMENT 'Giá vé',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_vehicle (
  id smallint(10) NOT NULL AUTO_INCREMENT,
  car_number_plate varchar(50) NOT NULL COMMENT 'Biển số xe',
  mobilephone varchar(50) NOT NULL COMMENT 'Hotline nhà xe',
  number_seats tinyint(10) NOT NULL COMMENT 'Số ghế',
  note text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Ghi chú',
  active tinyint(1) UNSIGNED NOT NULL COMMENT 'Kích hoạt',
  weight smallint(4) UNSIGNED NOT NULL,
  PRIMARY KEY (id)
)ENGINE=MyISAM";

