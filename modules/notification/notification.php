<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 0:51
 */

if( ! defined( 'NV_MAINFILE' ) )
    die( 'Stop!!!' );

define( 'NV_TABLE_NOTIFICATION', NV_PREFIXLANG . '_notification' );

function save_notification( $message, $url, $icon, $author )
{
    global $db;
    if( ! empty( $message ) )
    {
        try
        {
            $sql = "INSERT INTO " . NV_TABLE_NOTIFICATION . " VALUES (
				NULL, 
				" . $db->quote( $message ) . ", 
				" . $db->quote( $url ) . ", 
				" . $db->quote( $icon ) . ", 
                " . $db->quote( $author ) . ",
                0," . NV_CURRENTTIME . ", 0,6,1)";
            if( $db->query( $sql ) )
            {
                return 1;
            }

        }
        catch ( PDOException $e )
        {
            return $e->getMessage();
        }
        return 0;
    }
    return 0;
}
