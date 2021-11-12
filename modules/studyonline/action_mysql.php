<?php

/**
 * @Project NUKEVIET 4.x
 * @Author EDUSGROUP.JSC (thangbv@edus.vn)
 * @Copyright (C) 2014 EDUSGROUP.JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 20:59
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

global $op;

$sql_drop_module = array();

$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_subject;";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_class;";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_teacher;";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_tag;";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_khoahoc;";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_baihoc;";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_voucher;";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_vouchercode;";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_buyhistory;";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_viewhistory;";//luot xem bai giang
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_block_cat;";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_block;";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_tags;";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_tags_id;";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_review;";//danh gia & cam nhan cua hoc sinh

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_subject (
 id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
 title VARCHAR(150) NOT NULL,
 alias varchar(150) NOT NULL COMMENT 'Alias',
 description mediumtext COMMENT 'Mô tả',
 color varchar(10) NOT NULL COMMENT 'Mã màu minh họa',
 icon varchar(150) NOT NULL COMMENT 'Icon minh họa',
 weight smallint(4) NOT NULL DEFAULT '0' COMMENT 'STT',
 status tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1 : Hiển thị, 0 Ẩn',
 PRIMARY KEY (id),
 UNIQUE KEY alias (alias)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_class (
 id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
 title VARCHAR(150) NOT NULL,
 alias varchar(150) NOT NULL COMMENT 'Alias',
 description mediumtext COMMENT 'Mô tả',
 icon varchar(150) NOT NULL COMMENT 'Icon minh họa',
 listsubject varchar(200) NOT NULL COMMENT 'Các môn học cho lớp',
 weight smallint(4) NOT NULL DEFAULT '0' COMMENT 'STT',
 status tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1 : Hiển thị, 0 Ẩn',
 PRIMARY KEY (id),
 UNIQUE KEY alias (alias)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_teacher (
 id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
 title varchar(150) NOT NULL COMMENT 'Tên giáo viên',
 alias varchar(150) NOT NULL COMMENT 'Alias',
 description text COMMENT 'Mô tả cho SEO',
 infotext text COMMENT 'Giới thiệu giáo viên',
 avatar varchar(150) NOT NULL COMMENT 'Hình ảnh giáo viên',
 subjectlist varchar(150) NOT NULL COMMENT 'Các môn giảng dạy',
 facebooklink varchar(250) NOT NULL COMMENT 'Link fb giáo viên',
 address varchar(250) DEFAULT '' COMMENT 'Địa chỉ liên hệ',
 mobile varchar(150) DEFAULT '' COMMENT 'SĐT liên hệ',
 email varchar(150) DEFAULT '' COMMENT 'Email liên hệ',
 weight smallint(4) NOT NULL DEFAULT '0' COMMENT 'STT',
 numview int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Lượt xem',
 numfollow int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Lượt follow fb cá nhân',
 status tinyint(1) unsigned NOT NULL DEFAULT '0',
 updatetime int(11) NOT NULL DEFAULT '0',
 PRIMARY KEY (id),
 UNIQUE KEY alias (alias)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_tag (
 tag_id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
 tag_name varchar(150) NOT NULL,
 tag_icon varchar(150) NOT NULL COMMENT 'Icon',
 PRIMARY KEY (tag_id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_khoahoc (
 id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
 title varchar(150) NOT NULL COMMENT 'Tên khóa học',
 image varchar(150) NOT NULL COMMENT 'Ảnh minh họa',
 titleseo varchar(150) NOT NULL COMMENT 'Tiêu đề seo',
 alias varchar(150) NOT NULL COMMENT 'Alias',
 description text COMMENT 'Mô tả cho SEO',
 hometext text COMMENT 'Mô tả khóa học',
 classid int(11) NOT NULL DEFAULT '0',
 subjectid int(11) NOT NULL DEFAULT '0',
 teacherid varchar(150) NOT NULL COMMENT 'Các giáo viên trong khóa học',
 numlession smallint(4) NOT NULL DEFAULT '0',
 requirewatch smallint(4) NOT NULL DEFAULT '0' COMMENT 'NPP truc tiep de dc xem bai giang',
 numviewtime smallint(4) NOT NULL DEFAULT '0' COMMENT 'Lần xem một bài',
 price float NOT NULL DEFAULT '0' COMMENT 'Giá khóa học, 0: miễn phí',
 timestudy int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian học',
 timeend int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian học',
 addtime int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian tạo',
 numview int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Lượt xem',
 numlike int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Lượt like fb',
 numviewtrial int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Lượt học thử',
 numbuy int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Lượt mua',
 isvip tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'hiển thị là kh vip',
 isfreetrial tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Có học thử miễn phí không',
 total_rating int(11) NOT NULL default '0',
 click_rating int(11) NOT NULL default '0',
 listtag varchar(150) NOT NULL COMMENT 'Thẻ tag',
 status tinyint(1) unsigned NOT NULL DEFAULT '0',
 PRIMARY KEY (id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_baihoc (
 id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
 khoahocid mediumint(8) unsigned NOT NULL,
 title varchar(150) NOT NULL COMMENT 'Tên bài học',
 image varchar(150) NOT NULL COMMENT 'Ảnh Icon minh họa',
 titleseo varchar(150) NOT NULL COMMENT 'Tiêu đề seo',
 alias varchar(150) NOT NULL COMMENT 'Alias',
 description text COMMENT 'Mô tả cho SEO',
 list_video text COMMENT 'DS bài giảng',
 fileaddtack varchar(150) NOT NULL COMMENT 'File tài liệu đi kèm',
 timeamount smallint(4) NOT NULL DEFAULT '0' COMMENT 'Thời lượng bài giảng',
 numviewtime smallint(4) NOT NULL DEFAULT '0' COMMENT 'Lần xem một bài',
 price float NOT NULL DEFAULT '0' COMMENT 'Giá bài học, 0: miễn phí',
 timephathanh int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian phát hành',
 numview int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Lượt xem',
 numlike int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Lượt like fb',
 numbuy int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Lượt mua',
 weight smallint(4) NOT NULL DEFAULT '0' COMMENT 'STT',
 status tinyint(1) unsigned NOT NULL DEFAULT '0',
 addtime int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian tạo',
 PRIMARY KEY (id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_voucher (
 id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
 title varchar(150) NOT NULL COMMENT 'Tên chương trình',
 allowfor varchar(150) NOT NULL COMMENT 'Áp dụng cho các khóa nào',
 totalvoucher int(11) NOT NULL COMMENT 'Số lượng mã sẽ tạo',
 timeallow_from int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian áp dụng từ',
 timeallow_to int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian áp dụng đến',
 status tinyint(1) unsigned NOT NULL DEFAULT '0',
 addtime int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian tạo',
 PRIMARY KEY (id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_vouchercode (
 id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
 idvoucher mediumint(8) unsigned NOT NULL,
 code varchar(10) NOT NULL COMMENT 'Mã voucher',
 timeuse int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian sd',
 status tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0 =chua su dung',
 userid mediumint(8) COMMENT 'TK sử dụng',
 buyhistoryid int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID tại bảng ls mua',
 UNIQUE KEY code (code),
 PRIMARY KEY (id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_buyhistory (
 id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
 userid mediumint(8) COMMENT 'TK mua',
 istype tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Mua cả khóa hay từng bài (1 =từng bài,2 = cả khóa)',
 idbuy int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID khóa, bài học',
 timebuy int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian mua',
 pricebefor float NOT NULL DEFAULT '0' COMMENT 'Giá chưa giảm trừ',
 priceafter float NOT NULL DEFAULT '0' COMMENT 'Giá đã giảm trừ',
 numview tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Lượt xem bài',
 PRIMARY KEY (id)
) ENGINE=MyISAM";


$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_viewhistory (
 userid mediumint(8) COMMENT 'TK mua',
 khoahocid int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID khóa học',
 baihocid int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID bài học',
 timeupdate int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian xem moi nhat',
 total_time_view int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Tổng thời gian xem =giay',
 numview tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Lượt đã xem bài',
 PRIMARY KEY (userid,baihocid),
 INDEX khoahocid (khoahocid)
) ENGINE=MyISAM";

//nhom khoa hoc theo tung chu de
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_block_cat (
	 bid smallint(5) unsigned NOT NULL AUTO_INCREMENT,
	 adddefault tinyint(4) NOT NULL DEFAULT '0',
	 numbers smallint(5) NOT NULL DEFAULT '10',
	 title varchar(250) NOT NULL DEFAULT '',
	 alias varchar(250) NOT NULL DEFAULT '',
	 image varchar(255) DEFAULT '',
	 description varchar(255) DEFAULT '',
	 weight smallint(5) NOT NULL DEFAULT '0',
	 keywords text,
	 add_time int(11) NOT NULL DEFAULT '0',
	 edit_time int(11) NOT NULL DEFAULT '0',
	 PRIMARY KEY (bid),
	 UNIQUE KEY title (title),
	 UNIQUE KEY alias (alias)
	) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_block (
	 bid smallint(5) unsigned NOT NULL,
	 id int(11) unsigned NOT NULL,
	 weight int(11) unsigned NOT NULL,
	 UNIQUE KEY bid (bid,id)
	) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_tags (
	 tid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	 numnews mediumint(8) NOT NULL DEFAULT '0',
	 alias varchar(250) NOT NULL DEFAULT '',
	 image varchar(255) DEFAULT '',
	 description text,
	 keywords varchar(255) DEFAULT '',
	 PRIMARY KEY (tid),
	 UNIQUE KEY alias (alias)
	) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_tags_id (
	 id int(11) NOT NULL,
	 tid mediumint(9) NOT NULL,
	 keyword varchar(65) NOT NULL,
	 UNIQUE KEY id_tid (id,tid),
	 KEY tid (tid)
	) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_review (
 id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
 userid mediumint(8) COMMENT 'TK review',
 khoahocid int(10) unsigned NOT NULL DEFAULT '0',
 content text COMMENT 'Noi dung',
 addtime int(11) NOT NULL DEFAULT '0',
 status tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1 : Hiển thị, 0 Ẩn',
 PRIMARY KEY (id),
 KEY userid (userid)
) ENGINE=MyISAM";

//config  
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'indexfile', 'viewcat_main_right')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'per_page', '20')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'st_links', '10')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'homewidth', '100')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'homeheight', '150')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'blockwidth', '70')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'blockheight', '75')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'imagefull', '460')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'showhometext', '1')";;
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'show_no_image', '')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'allowed_rating_point', '1')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'facebookappid', '')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'socialbutton', '1')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'alias_lower', '1')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'auto_tags', '1')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'streaming', '0')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'server_streaming', '')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'numview_guest', '3')";
// Comments
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'auto_postcomm', '1')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'allowed_comm', '-1')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'view_comm', '6')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'setcomm', '4')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'activecomm', '1')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'emailcomm', '0')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'adminscomm', '')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'sortcomm', '0')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'captcha', '1')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'perpagecomm', '5')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'timeoutcomm', '360')";
