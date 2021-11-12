<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 20:59
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

global $op;

$sql_drop_module = array();

$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_reg;";
$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_reg (
 reg_id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
 userid mediumint(8) unsigned NOT NULL,
 reg_full_name varchar(250) NULL COMMENT 'Họ và tên',
 reg_email varchar(250) NULL COMMENT 'Địa chỉ email',
 reg_phone varchar(250) NULL COMMENT 'Điện thoại',
 reg_address varchar(250) NULL COMMENT 'Địa chỉ',
 mobilerefer varchar(50) NULL COMMENT 'ĐT ng gioi thieu',
 siterefer mediumint(8) unsigned NOT NULL COMMENT 'domain gioi thieu',
 note text NULL COMMENT 'Ghi chu',
 add_time int(11) NOT NULL DEFAULT '0' COMMENT 'Thời gian đăng ký',
 process_time int(11) NOT NULL DEFAULT '0' COMMENT 'Thời gian xử lý',
 from_ip varchar(15) NULL COMMENT 'ip dk',
 status tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0: chưa xử lý, 1: đã gọi và xác nhận, 2: chưa liên lạc được',
 PRIMARY KEY (reg_id)
) ENGINE=MyISAM";

$data = array();
$data['sms_on'] = 0;
$data['sms_type'] = 2;
$data['apikey'] = '81CF49D2388126412DB2E7CE63CA46';
$data['secretkey'] = 'EE310DB80631F52381480F085CAF8D';
$data['email_notify'] = 'kid.apt@gmail.com';
$data['list_phone'] = '0913668600';
$data['brandname'] = 'CASH13';
foreach ($data as $config_name => $config_value) {
    $sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', " . $db->quote($module_name) . ", " . $db->quote($config_name) . ", " . $db->quote($config_value) . ")";
}