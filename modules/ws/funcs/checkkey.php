<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 19 Jun 2014 09:53:21 GMT
 */
if ( ! defined( 'NV_IS_MOD_WS' ) ) die( 'Stop!!!' );
header('Content-Type: application/json');

$key = $nv_Request->get_string('key', 'post');
if ($key != $global_config['sitekey'])
	 die( 'Stop!!!' );


     