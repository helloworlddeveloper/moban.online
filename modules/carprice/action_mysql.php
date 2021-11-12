<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 20:59
 */

if (! defined('NV_IS_FILE_MODULES')) {
    die('Stop!!!');
}

$sql_drop_module = array();

$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_producer;";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_temcar;";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_typecar;";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_location_fee;";

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_producer (
 id smallint(4) unsigned NOT NULL AUTO_INCREMENT,
 title varchar(250) NOT NULL,
 note text NOT NULL,
 PRIMARY KEY (id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_temcar (
 id int(10) unsigned NOT NULL AUTO_INCREMENT,
 producerid smallint(4) unsigned NOT NULL,
 typecarid smallint(4) unsigned NOT NULL,
 numseats smallint(4) unsigned NOT NULL COMMENT 'Số chỗ ngồi',
 price_listing float unsigned NOT NULL COMMENT 'Giá niêm yết',
 price_negotiate float unsigned NOT NULL COMMENT 'Giá đàm phán',
 title varchar(250) NOT NULL,
 image varchar(250) NOT NULL,
 note text NOT NULL,
 PRIMARY KEY (id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_typecar (
 id smallint(4) unsigned NOT NULL AUTO_INCREMENT,
 title varchar(250) NOT NULL,
 note text NOT NULL,
 PRIMARY KEY (id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_location_fee (
 locationid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
 typecarid smallint(4) unsigned NOT NULL,
 registration_fee float unsigned NOT NULL COMMENT 'Phí trước bạ',
 license_plate_fee float unsigned NOT NULL COMMENT 'Phí đăng ký biển',
 PRIMARY KEY (locationid,typecarid)
) ENGINE=MyISAM";

$array_data = array(
    'road_use' => '1560000',
    'civil_insurance_4' => '437000',
    'civil_insurance_5' => '437000',
    'civil_insurance_6' => '794000',
    'civil_insurance_7' => '794000',
    'registration' => '340000'
);

foreach ($array_data as $config_name => $config_value) {
    $sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', " . $db->quote($module_name) . ", " . $db->quote($config_name) . ", " . $db->quote($config_value) . ")";
}