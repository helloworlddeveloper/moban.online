<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 19 Jun 2014 09:53:21 GMT
 */

if ( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

define( 'NV_IS_MOD_REGSITE', true );


function addasite( $idsite, $array_data ){
    global $db;
    $file_config_db = $array_data['domain_name'] . '.php';
    //ghi vao file domain.php

    require NV_ROOTDIR . '/domain.php';
    $content_config = "";
    $content_config = "<?php" . "\n\n";
    $content_config .= NV_FILEHEAD . "\n\n";
    $content_config .= "if (!defined('NV_MAINFILE'))\n    die('Stop!!!');\n\n";
    $content_config .= "\$array_domain = array();\n\n";
    foreach ( $array_domain as $key => $val ){
        $content_config .= "\$array_domain['" . $key . "']='" . $val . "';\n";
    }
    $content_config .= "\$array_domain['" . $array_data['domain_name'] . "']='" . $idsite . "';\n";
    file_put_contents(NV_ROOTDIR . '/domain.php', trim($content_config), LOCK_EX);

}
