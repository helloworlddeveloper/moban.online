<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Mr.Thang (kid.apt@gmail.com)
 * @Copyright (C) 2014 Mr.Thang. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sat, 28 Dec 2013 12:56:09 GMT
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) )	die( 'Stop!!!' );

$sql_drop_module = array();
$query = $db->query( "select table_name from all_tables WHERE table_name = '" . strtoupper( $db_config['prefix'] . "_" . $lang . "_" . $module_data ) . "'" );
while( $row = $query->fetch() )
{
	$sql_drop_module[] = 'drop table ' . $row['table_name'] . ' cascade constraints PURGE';
}

$query = $db->query( "select sequence_name from user_sequences WHERE sequence_name = '" . strtoupper( "SNV_" . $lang . "_" . $module_data ) . "'" );
while( $row = $query->fetch() )
{
	$sql_drop_module[] = 'drop SEQUENCE ' . $row['sequence_name'];
}

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE " . $db_config["prefix"] . "_" . $lang . "_" . $module_data . " (
 config_name VARCHAR2(30 CHAR) DEFAULT '',
 config_value CLOB NOT NULL ENABLE,
 CONSTRAINT cnv_" . $lang . "_" . $module_data . "_config_name UNIQUE (config_name)
)";

$sql_create_module[] = 'create sequence SNV_' . strtoupper( $lang . '_' . $module_data ) . ' MINVALUE 10';

$sql_create_module[] = 'CREATE OR REPLACE TRIGGER TNV_' . strtoupper( $lang . '_' . $module_data ) . '
 BEFORE INSERT ON ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '
 FOR EACH ROW WHEN (new.id is null)
	BEGIN
	 SELECT SNV_' . strtoupper( $lang . '_' . $module_data ) . '.nextval INTO :new.id FROM DUAL;
	END TNV_' . strtoupper( $lang . '_' . $module_data ) . ';';

?>