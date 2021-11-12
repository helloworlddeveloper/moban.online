<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate Thu, 14 Apr 2011 12:01:30 GMT
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$sql_drop_module = array();
$sql_drop_module[] = "DROP TABLE IF EXISTS ".$db_config['prefix']."_".$lang."_".$module_data."_mien";
$sql_drop_module[] = "DROP TABLE IF EXISTS ".$db_config['prefix']."_".$lang."_".$module_data."_province";
$sql_drop_module[] = "DROP TABLE IF EXISTS ".$db_config['prefix']."_".$lang."_".$module_data."_district";

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE ".$db_config['prefix']."_".$lang."_".$module_data."_mien (
id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
title varchar(250) NOT NULL DEFAULT '',
alias varchar(250) NOT NULL DEFAULT '',
weight smallint(4) unsigned NOT NULL DEFAULT '0',
status tinyint(1) unsigned NOT NULL DEFAULT '0',
PRIMARY KEY (id),
UNIQUE KEY alias (alias)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE ".$db_config['prefix']."_".$lang."_".$module_data."_province (
id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
code varchar(250) NOT NULL DEFAULT '',
idmien mediumint(8) unsigned NOT NULL DEFAULT '0',
title varchar(250) NOT NULL DEFAULT '',
alias varchar(250) NOT NULL DEFAULT '',
weight smallint(4) unsigned NOT NULL DEFAULT '0',
status tinyint(1) unsigned NOT NULL DEFAULT '0',
PRIMARY KEY (id),
UNIQUE KEY alias (alias),
UNIQUE KEY code (code)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE ".$db_config['prefix']."_".$lang."_".$module_data."_district (
id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
idprovince mediumint(8) unsigned NOT NULL DEFAULT '0',
title varchar(240) NOT NULL DEFAULT '',
alias varchar(240) NOT NULL DEFAULT '',
weight smallint(4) unsigned NOT NULL DEFAULT '0',
status tinyint(1) unsigned NOT NULL DEFAULT '0',
PRIMARY KEY (id),
UNIQUE KEY alias_idprovince (alias,idprovince)
) ENGINE=MyISAM";


$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_mien VALUES (1, 'Miền Bắc', 'Mien-Bac',1, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_mien VALUES (2, 'Miền Trung', 'Mien-Trung',3, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_mien VALUES (3, 'Miền Nam', 'Mien-Nam',2, 1)";
//tinh thanh
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (1, 'HN', 1, 'Hà Nội', 'Ha-Noi', 4, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (10, 'HCM', 3, 'TP.HCM', 'TPHCM', 1, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (20, 'HP', 1, 'Hải Phòng', 'Hai-Phong', 2, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (21, 'BG', 1, 'Bắc Giang', 'Bac-Giang', 29, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (22, 'BK', 1, 'Bắc Kạn', 'Bac-Kan', 30, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (23, 'BN', 1, 'Bắc Ninh', 'Bac-Ninh', 5, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (24, 'CB', 1, 'Cao Bằng', 'Cao-Bang', 6, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (25, 'DB', 1, 'Điện Biên', 'Dien-Bien', 7, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (26, 'HAG', 1, 'Hà Giang', 'Ha-Giang', 8, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (27, 'HNA', 1, 'Hà Nam', 'Ha-Nam', 9, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (28, 'HD', 1, 'Hải Dương', 'Hai-Duong', 10, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (29, 'HB', 1, 'Hòa Bình', 'Hoa-Binh', 11, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (30, 'HY', 1, 'Hưng Yên', 'Hung-Yen', 12, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (31, 'LC', 1, 'Lai Châu', 'Lai-Chau', 13, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (32, 'LS', 1, 'Lạng Sơn', 'Lang-Son', 14, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (34, 'LCI', 1, 'Lào Cai', 'Lao-Cai', 15, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (35, 'ND', 1, 'Nam Định', 'Nam-Dinh', 16, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (36, 'NB', 1, 'Ninh Bình', 'Ninh-Binh', 17, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (37, 'PT', 1, 'Phú Thọ', 'Phu-Tho', 18, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (38, 'QN', 1, 'Quảng Ninh', 'Quang-Ninh', 19, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (39, 'SL', 1, 'Sơn La', 'Son-La', 20, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (40, 'TB', 1, 'Thái Bình', 'Thai-Binh', 21, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (41, 'THN', 1, 'Thái Nguyên', 'Thai-Nguyen', 22, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (42, 'TH', 1, 'Thanh Hóa', 'Thanh-Hoa', 23, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (43, 'TQ', 1, 'Tuyên Quang', 'Tuyen-Quang', 24, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (44, 'VP', 1, 'Vĩnh Phúc', 'Vinh-Phuc', 25, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (45, 'YB', 1, 'Yên Bái', 'Yen-Bai', 26, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (46, 'DNG', 2, 'Đà Nẵng', 'Da-Nang', 3, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (47, 'BDH', 2, 'Bình Định', 'Binh-Dinh', 7, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (48, 'BP', 2, 'Bình Phước', 'Binh-Phuoc', 3, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (49, 'BT', 2, 'Bình Thuận', 'Binh-Thuan', 4, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (50, 'DLK', 2, 'Đắk Lắk', 'Dak-Lak', 5, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (51, 'DNO', 2, 'Đắk Nông', 'Dak-Nong', 6, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (52, 'GL', 2, 'Gia Lai', 'Gia-Lai', 7, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (53, 'HTH', 2, 'Hà Tĩnh', 'Ha-Tinh', 8, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (54, 'KH', 2, 'Khánh Hòa', 'Khanh-Hoa', 9, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (55, 'KT', 2, 'Kon Tum', 'Kon-Tum', 10, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (56, 'LD', 2, 'Lâm Đồng', 'Lam-Dong', 11, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (57, 'NA', 2, 'Nghệ An', 'Nghe-An', 12, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (58, 'NT', 2, 'Ninh Thuận', 'Ninh-Thuan', 13, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (59, 'PY', 2, 'Phú Yên', 'Phu-Yen', 14, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (60, 'QB', 2, 'Quảng Bình', 'Quang-Binh', 15, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (61, 'QNM', 2, 'Quảng Nam', 'Quang-Nam', 16, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (62, 'QNI', 2, 'Quảng Ngãi', 'Quang-Ngai', 17, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (63, 'QT', 2, 'Quảng Trị', 'Quang-Tri', 18, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (64, 'TTH', 2, 'Thừa Thiên Huế', 'Thua-Thien-Hue', 19, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (65, 'CT', 3, 'Cần Thơ', 'Can-Tho', 4, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (66, 'AG', 3, 'An Giang', 'An-Giang', 1, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (67, 'BR-VT', 3, 'BR - Vũng Tàu', 'BR-Vung-Tau', 1, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (68, 'BL', 3, 'Bạc Liêu', 'Bac-Lieu', 5, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (69, 'BTR', 3, 'Bến Tre', 'Ben-Tre', 6, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (70, 'BD', 3, 'Bình Dương', 'Binh-Duong', 3, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (71, 'CM', 3, 'Cà Mau', 'Ca-Mau', 8, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (72, 'DN', 3, 'Đồng Nai', 'Dong-Nai', 9, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (73, 'DT', 3, 'Đồng Tháp', 'Dong-Thap', 10, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (74, 'HG', 3, 'Hậu Giang', 'Hau-Giang', 11, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (75, 'KG', 3, 'Kiên Giang', 'Kien-Giang', 12, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (76, 'LA', 3, 'Long An', 'Long-An', 13, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (77, 'ST', 3, 'Sóc Trăng', 'Soc-Trang', 14, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (78, 'TN', 3, 'Tây Ninh', 'Tay-Ninh', 15, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (79, 'TG', 3, 'Tiền Giang', 'Tien-Giang', 16, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (80, 'TV', 3, 'Trà Vinh', 'Tra-Vinh', 17, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_province VALUES (81, 'VL', 3, 'Vĩnh Long', 'Vinh-Long', 18, 1)";

//quan huyen
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (3, 1, 'Thanh Xuân', 'Q-Thanh-Xuan', 1, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (4, 1, 'Hoàn Kiếm', 'Q-Hoan-Kiem', 2, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (13, 10, 'Quận 1', 'Q-1', 1, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (14, 10, 'Quận 2', 'Quan-2', 2, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (15, 10, 'Quận 3', 'Quan-3', 3, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (18, 10, 'Q. Bình Thạnh', 'Q-Binh-Thanh', 4, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (82, 1, 'Q. Ba Đình', 'Q-Ba-Dinh', 3, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (83, 1, 'Q. Cầu Giấy', 'Q-Cau-Giay', 4, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (84, 1, 'Q. Đống Đa', 'Q-Dong-Da', 5, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (85, 1, 'Q. Hà Đông', 'Q-Ha-Dong', 6, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (86, 1, 'Q. Hai Bà Trưng', 'Q-Hai-Ba-Trung', 7, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (87, 1, 'Q. Hoàng Mai', 'Q-Hoang-Mai', 8, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (88, 1, 'Q. Long Biên', 'Q-Long-Bien', 9, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (89, 1, 'Q. Tây Hồ', 'Q-Tay-Ho', 10, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (90, 1, 'TX Sơn Tây', 'TX-Son-Tay', 11, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (91, 1, 'H. Ba Vì', 'H-Ba-Vi', 12, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (92, 1, 'H. Chương Mỹ', 'H-Chuong-My', 13, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (93, 1, 'H. Đan Phượng', 'H-Dan-Phuong', 14, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (94, 1, 'H. Đông Anh', 'H-Dong-Anh', 15, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (95, 1, 'H. Gia Lâm', 'H-Gia-Lam', 16, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (96, 1, 'H. Hoài Đức', 'H-Hoai-Duc', 17, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (97, 1, 'H. Mê Linh', 'H-Me-Linh', 18, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (98, 1, 'H. Mỹ Đức', 'H-My-Duc', 19, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (99, 1, 'H. Phú Xuyên', 'H-Phu-Xuyen', 20, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (100, 1, 'H. Phúc Thọ', 'H-Phuc-Tho', 21, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (101, 1, 'H. Quốc Oai', 'H-Quoc-Oai', 22, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (102, 1, 'H. Sóc Sơn', 'H-Soc-Son', 23, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (103, 1, 'H. Thạch Thất', 'H-Thach-That', 24, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (104, 1, 'H. Thanh Oai', 'H-Thanh-Oai', 25, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (105, 1, 'H. Thanh Trì', 'H-Thanh-Tri', 26, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (106, 1, 'H. Thường Tín', 'H-Thuong-Tin', 27, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (107, 1, 'H. Từ Liêm', 'H-Tu-Liem', 28, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (108, 1, 'H. Ứng Hòa', 'H-Ung-Hoa', 29, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (109, 20, 'H. An Dương', 'H-An-Duong', 1, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (110, 20, 'H. An Lão', 'H-An-Lao', 2, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (111, 20, 'H. Đảo Bạch Long Vĩ', 'H-Dao-Bach-Long-Vi', 3, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (112, 20, 'H. Cát Hải', 'H-Cat-Hai', 4, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (113, 20, 'H. Kiến Thụy', 'H-Kien-Thuy', 5, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (114, 20, 'H. Thuỷ Nguyên', 'H-Thuy-Nguyen', 6, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (115, 20, 'H. Tiên Lãng', 'H-Tien-Lang', 7, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (116, 20, 'H. Vĩnh Bảo', 'H-Vinh-Bao', 8, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (117, 20, 'Q. Hải An', 'Q-Hai-An', 9, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (118, 20, 'Q. Hồng Bàng', 'Q-Hong-Bang', 10, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (119, 20, 'Q. Kiến An', 'Q-Kien-An', 11, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (120, 20, 'Q. Lê Chân', 'Q-Le-Chan', 12, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (121, 20, 'Q. Ngô Quyền', 'Q-Ngo-Quyen', 13, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (122, 20, 'TX  Đồ Sơn', 'TX-Do-Son', 14, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (123, 10, 'Quận 4', 'Quan-4', 5, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (124, 10, 'Quận 5', 'Quan-5', 6, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (125, 10, 'Quận 6', 'Quan-6', 7, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (126, 10, 'Quận 7', 'Quan-7', 8, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (127, 10, 'Quận 8', 'Quan-8', 9, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (128, 10, 'Quận 9', 'Quan-9', 10, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (129, 10, 'Quận 10', 'Quan-10', 11, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (130, 10, 'Quận 11', 'Quan-11', 12, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (131, 10, 'Quận 12', 'Quan-12', 13, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (132, 10, 'Q.  Tân Bình', 'Q-Tan-Binh', 14, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (133, 10, 'Q. Phú Nhuận', 'Q-Phu-Nhuan', 15, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (134, 10, 'Q. Thủ Đức', 'Q-Thu-Duc', 16, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (135, 10, 'Q. Gò Vấp', 'Q-Go-Vap', 17, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (136, 10, 'Q. Bình Tân', 'Q-Binh-Tan', 18, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (137, 10, 'Q.  Tân Phú', 'Q-Tan-Phu', 19, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (138, 10, 'H. Nhà Bè', 'H-Nha-Be', 20, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (139, 10, 'H. Cần Giờ', 'H-Can-Gio', 21, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (140, 10, 'H. Hóc Môn', 'H-Hoc-Mon', 22, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (899, 67, 'TP. Vũng Tàu', 'TP-Vung-Tau', 1, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1197, 46, 'Hải Châu', 'Hai-Chau', 1, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1198, 46, 'Thanh Khê', 'Thanh-Khe', 2, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1199, 46, 'Sơn Trà', 'Son-Tra', 3, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1200, 46, 'Ngũ Hành Sơn', 'Ngu-Hanh-Son', 4, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1201, 46, 'Liên Chiểu', 'Lien-Chieu', 5, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1202, 46, 'Cẩm Lệ', 'Cam-Le', 6, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1203, 46, 'Hòa Vang', 'Hoa-Vang', 7, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1204, 46, 'Đảo Hoàng Sa', 'Dao-Hoang-Sa', 8, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1277, 21, 'TP. Bắc Giang.', 'TP-Bac-Giang', 1, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1278, 21, 'Yên Thế', 'Yen-The', 2, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1279, 21, 'Tân Yên', 'Tan-Yen', 3, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1280, 21, 'Lục Ngạn.', 'Luc-Ngan', 4, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1281, 21, 'Hiệp Hoà.', 'Hiep-Hoa', 5, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1282, 21, 'Lạng Giang', 'Lang-Giang', 6, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1283, 21, 'Sơn Động', 'Son-Dong', 7, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1284, 21, 'Lục Nam', 'Luc-Nam', 8, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1285, 21, 'Việt Yên', 'Viet-Yen', 9, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1286, 21, 'Yên Dũng', 'Yen-Dung', 10, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1452, 22, 'TX Bắc Kạn', 'TX-Bac-Kan', 1, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1453, 22, 'Ba Bể', 'Ba-Be', 2, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1454, 22, 'Bạch Thông', 'Bach-Thong', 3, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1455, 22, 'Chợ Đồn', 'Cho-Don', 4, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1456, 22, 'Chợ Mới', 'Cho-Moi', 5, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1457, 22, 'Na Rì', 'Na-Ri', 6, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1458, 22, 'Ngân Sơn', 'Ngan-Son', 7, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1459, 22, 'Pác Nặm', 'Pac-Nam', 8, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1567, 23, 'Tp. Bắc Ninh', 'Tp-Bac-Ninh', 1, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1568, 23, 'Từ Sơn', 'Tu-Son', 2, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1569, 23, 'Gia Bình', 'Gia-Binh', 3, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1570, 23, 'Lương Tài', 'Luong-Tai', 4, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1571, 23, 'Quế Võ', 'Que-Vo', 5, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1572, 23, 'Thuận Thành', 'Thuan-Thanh', 6, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1573, 23, 'Tiên Du', 'Tien-Du', 7, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1574, 23, 'Yên Phong', 'Yen-Phong', 8, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1686, 24, 'Bảo Lạc', 'Bao-Lac', 1, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1687, 24, 'Bảo Lâm', 'Bao-Lam', 2, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1688, 24, 'Hạ Lang', 'Ha-Lang', 3, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1689, 24, 'Hà Quảng', 'Ha-Quang', 4, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1690, 24, 'Hòa An', 'Hoa-An', 5, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1691, 24, 'Nguyên Bình', 'Nguyen-Binh', 6, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1692, 24, 'Phục Hòa', 'Phuc-Hoa', 7, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1693, 24, 'Quảng Uyên', 'Quang-Uyen', 8, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1694, 24, 'Thạch An', 'Thach-An', 9, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1695, 24, 'Thông Nông', 'Thong-Nong-1', 10, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1696, 24, 'Trà Lĩnh', 'Tra-Linh', 11, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1697, 24, 'Trùng Khánh', 'Trung-Khanh', 12, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1763, 67, 'Tx Bà Rịa', 'Tx-Ba-Ria', 2, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1764, 67, 'Long Điền', 'Long-Dien', 3, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1765, 67, 'Đất Đỏ', 'Dat-Do', 4, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1766, 67, 'Châu Đức', 'Chau-Duc', 5, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1767, 67, 'Tân Thành', 'Tan-Thanh', 6, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1768, 67, 'Côn Đảo', 'Con-Dao', 7, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (1769, 67, 'Xuyên Mộc', 'Xuyen-Moc', 8, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2132, 25, 'Tp. Điện Biên Phủ', 'Tp-Dien-Bien-Phu', 1, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2133, 25, 'Tx.  Mường Lay', 'Tx-Muong-Lay', 2, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2134, 25, 'Điện Biên', 'Dien-Bien', 3, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2135, 25, 'Điện Biên Đông', 'Dien-Bien-Dong', 4, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2136, 25, 'Mường Ảng', 'Muong-Ang', 5, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2137, 25, 'Mường Chà', 'Muong-Cha', 6, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2138, 25, 'Mường Nhé', 'Muong-Nhe', 7, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2139, 25, 'Tủa Chùa', 'Tua-Chua', 8, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2140, 25, 'Tuần Giáo', 'Tuan-Giao', 9, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2237, 26, 'Tp. Hà Giang', 'Tp-Ha-Giang', 1, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2238, 26, 'Bắc Mê', 'Bac-Me', 2, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2239, 26, 'Bắc Quang', 'Bac-Quang', 3, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2240, 26, 'Đồng Văn', 'Dong-Van', 4, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2241, 26, 'Hoàng Su Phì', 'Hoang-Su-Phi', 5, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2242, 26, 'Mèo Vạc', 'Meo-Vac', 6, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2243, 26, 'Quản Bạ', 'Quan-Ba', 7, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2244, 26, 'Quang Bình', 'Quang-Binh', 8, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2245, 26, 'Vị Xuyên', 'Vi-Xuyen', 9, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2246, 26, 'Xín Mần', 'Xin-Man', 10, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2247, 26, 'Yên Minh', 'Yen-Minh', 11, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2403, 27, 'Tp. Phủ Lý', 'Tp-Phu-Ly', 1, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2404, 27, 'Bình Lục', 'Binh-Luc', 2, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2405, 27, 'Duy Tiên', 'Duy-Tien', 3, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2406, 27, 'Kim Bảng', 'Kim-Bang', 4, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2407, 27, 'Lý Nhân', 'Ly-Nhan', 5, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2408, 27, 'Thanh Liêm', 'Thanh-Liem', 6, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2523, 28, 'Tp. Hải Dương', 'Tp-Hai-Duong', 1, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2524, 28, 'Chí Linh', 'Chi-Linh', 2, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2525, 28, 'Bình Giang', 'Binh-Giang', 3, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2526, 28, 'Cẩm Giàng', 'Cam-Giang', 4, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2527, 28, 'Gia Lộc', 'Gia-Loc', 5, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2528, 28, 'Kim Thành', 'Kim-Thanh', 6, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2529, 28, 'Kinh Môn', 'Kinh-Mon', 7, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2530, 28, 'Nam Sách', 'Nam-Sach', 8, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2531, 28, 'Ninh Giang', 'Ninh-Giang', 9, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2532, 28, 'Thanh Hà', 'Thanh-Ha', 10, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2533, 28, 'Thanh Miện', 'Thanh-Mien', 11, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2534, 28, 'Tứ Kỳ', 'Tu-Ky', 12, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2775, 29, 'Lương Sơn', 'Luong-Son', 1, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2776, 29, 'Cao Phong', 'Cao-Phong', 2, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2777, 29, 'Đà Bắc', 'Da-Bac', 3, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2778, 29, 'Kim Bôi', 'Kim-Boi', 4, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2779, 29, 'Kỳ Sơn', 'Ky-Son', 5, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2780, 29, 'Lạc Sơn', 'Lac-Son', 6, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2781, 29, 'Lạc Thủy', 'Lac-Thuy', 7, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2782, 29, 'Mai Châu', 'Mai-Chau', 8, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2783, 29, 'Tân Lạc', 'Tan-Lac', 9, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2784, 29, 'Yên Thủy', 'Yen-Thuy', 10, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2826, 30, 'Tp.  Hưng Yên', 'Tp-Hung-Yen', 1, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2827, 30, 'Ân Thi', 'An-Thi', 2, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2828, 30, 'Khoái Châu', 'Khoai-Chau', 3, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2829, 30, 'Kim Động', 'Kim-Dong', 4, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2830, 30, 'Mỹ Hào', 'My-Hao', 5, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2831, 30, 'Phù Cừ', 'Phu-Cu', 6, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2832, 30, 'Tiên Lữ', 'Tien-Lu', 7, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2833, 30, 'Văn Giang', 'Van-Giang', 8, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2834, 30, 'Văn Lâm', 'Van-Lam', 9, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2835, 30, 'Yên Mỹ', 'Yen-My', 10, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2836, 31, 'Lai Châu', 'Lai-Chau', 1, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2837, 31, 'Mường Tè', 'Muong-Te', 2, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2838, 31, 'Phong Thổ', 'Phong-Tho', 3, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2839, 31, 'Sìn Hồ', 'Sin-Ho', 4, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2840, 31, 'Tam Đường', 'Tam-Duong', 5, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2841, 31, 'Than Uyên', 'Than-Uyen', 6, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2842, 31, 'Tân Uyên', 'Tan-Uyen', 7, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2843, 32, 'Tràng Định', 'Trang-Dinh', 1, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2844, 32, 'Văn Lãng', 'Van-Lang', 2, 1)";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_district VALUES (2845, 32, 'Văn Quan', 'Van-Quan', 3, 1)";