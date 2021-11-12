<?php
define('NV_EVENT_ID_REGISTER', 1);
define('NV_MEASURE_ID_ACCEPT', 1);

function check_data_info( $fullname, $mobile )
{
    global $db, $admin_info;
    if( ! empty( $mobile ) && ! empty( $fullname ) )
    {
        $sql = 'SELECT id FROM ' . NV_PREFIXLANG . "_mkt WHERE mobile LIKE '%" . $mobile . "%' AND full_name LIKE '%" . $fullname . "%'";
        list( $customerid ) = $db->query( $sql )->fetch( 3 );
        //neu chua ton tai thi ghi du lieu
        if( $customerid > 0 )
        {
            return $customerid;
        }
    }
    return 0;
}

function save_data_user($userid, $provinceid, $fullname, $address, $email, $mobile, $facebook, $from_by ){
    global $db;

    $pos = strrpos( $fullname, ' ' );
    if( $pos === false )
    {
        $first_name = '';
        $last_name = $fullname;
    }
    else
    {
        $first_name = substr( $fullname, 0, $pos + 1 );
        $last_name = substr( $fullname, $pos );
    }
    $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_mkt (adminid, provinceid, districtid, first_name, last_name, full_name, birthday, sex, address, email, mobile, facebook, from_by, gmap_lat, gmap_lng, add_time, edit_time, mkt_time, remkt_time, status) 
                VALUES (' . $userid . ', ' . $provinceid . ', 0, ' . $db->quote( $first_name ) . ', ' . $db->quote( $last_name ) . ', ' . $db->quote( $fullname ) . ', 0, 0, ' . $db->quote( $address ) . ', 
                ' . $db->quote( $email ) . ', ' . $db->quote( $mobile ) . ', ' . $db->quote( $facebook ) . ', ' . intval( $from_by ) . ', 0, 0, ' . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ', 0, 0)';

    $data_insert = array();
    return $db->insert_id($sql, 'id', $data_insert);
}


function save_eventcontent( $customerid, $measureid = NV_MEASURE_ID_ACCEPT, $eventtype = NV_EVENT_ID_REGISTER, $note )
{
    global $db, $user_info;
    if( $customerid > 0 && ! empty( $note ) )
    {
        try
        {
            $stmt = $db->prepare( 'INSERT INTO ' . NV_PREFIXLANG . '_mkt_events (customerid, measureid, adminid, addtime, eventtype, content) VALUES ( :customerid, :measureid, :adminid, :addtime, :eventtype, :content)' );

            $addtime = NV_CURRENTTIME;
            $stmt->bindParam( ':addtime', $addtime, PDO::PARAM_INT );
            $stmt->bindParam( ':customerid', $customerid, PDO::PARAM_STR );
            $stmt->bindParam( ':measureid', $measureid, PDO::PARAM_STR );
            $stmt->bindParam( ':adminid', intval( $user_info['userid'] ), PDO::PARAM_INT );
            $stmt->bindParam( ':eventtype', $eventtype, PDO::PARAM_INT );
            $stmt->bindParam( ':content', $note, PDO::PARAM_STR, strlen( $note ) );

            $exc = $stmt->execute();
            if( $exc )
            {
                return 1;
            }

        }
        catch ( PDOException $e )
        {
            die($e->getMessage());
        }
        return 0;
    }
    return 0;
}