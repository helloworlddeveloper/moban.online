<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Mr.Thang <contact@vinades.vn>
 * @Copyright (C) 2010 - 2014 Mr.Thang. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 08 Apr 2012 00:00:00 GMT
 */
$shop_items = "&nv=shops&op=content";
Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . $shop_items );
die();
