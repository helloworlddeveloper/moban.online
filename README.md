# moban.online

$_sql[] = "ALTER TABLE " . $dataname . "." . $db_config['prefix'] . "_" . $mod_data . "_users ADD status tinyint(4) unsigned NOT NULL DEFAULT '0' AFTER edit_time;";
