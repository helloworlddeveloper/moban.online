<?php


if( $nv_Request->isset_request( 'check', 'post' ) )
{
    $mobile = $nv_Request->get_title( 'mobile', 'post', '' );

    if( $flag_allow == 1 ){
        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE mobile=' . $db->quote( $mobile ) ;
    }else{
        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE adminid=' . $user_info['userid'] . ' AND mobile=' . $db->quote( $mobile ) ;
    }
    $data_content = $db->query($sql)->fetch();
    if( !empty( $data_content )){
        nv_jsonOutput( $data_content );
        exit;
    }
    exit('');
}
if( $nv_Request->isset_request( 'reg', 'post' ) )
{
    $row['fullname'] = $nv_Request->get_title( 'fullname', 'post', '' );
    $pos = strrpos( $row['fullname'], ' ' );
    if( $pos === false )
    {
        $row['first_name'] = '';
        $row['last_name'] = $row['fullname'];
    }
    else
    {
        $row['first_name'] = substr( $row['fullname'], 0, $pos + 1 );
        $row['last_name'] = substr( $row['fullname'], $pos );
    }

    $row['mobile'] = $nv_Request->get_title( 'phone', 'post', '' );
    $row['provinceid'] = $nv_Request->get_int( 'provinceid', 'post', 0 );
    $row['event'] = $nv_Request->get_int( 'event', 'post', 0 );
    $row['email'] = $nv_Request->get_title( 'email', 'post', '' );
    $row['address'] = $nv_Request->get_title( 'address', 'post', 'M' );
    $row['userid'] = $nv_Request->get_title( 'userid', 'post' );
    if( $row['userid'] == 0 ){
        $row['userid'] = $user_info['userid'];
    }

    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE mobile=' . $db->quote( $row['mobile'] ) ;
    $data_content = $db->query($sql)->fetch();
    if( !empty( $data_content )){
        if( $data_content['adminid'] != $user_info['userid'] ){
            $error = $lang_module['error_exits_data_by_other'];
        }else{
            $row['id'] = $data_content['id'];
        }
    }
    $check_phone = check_phone_avaible($row['mobile']);

    if( !defined( 'NV_IS_USER' ) )
    {
        $error = $lang_module['error_required_login'];
    }
    elseif( empty( $row['fullname'] ) )
    {
        $error = $lang_module['error_required_fullname'];
    }
    elseif( empty( $row['mobile'] ) )
    {
        $error = $lang_module['error_required_phone'];
    }
    elseif( $check_phone == 0 ){
        $error = $lang_module['error_mobile_wrong'];
    }
    elseif( ! empty( $row['email'] ) and ( $error_email = nv_check_valid_email( $row['email'] ) ) != '' )
    {
        $error = $error_email;
    }
    if( empty( $error ) )
    {
        try
        {

            $row['edit_time'] = $row['add_time'] = NV_CURRENTTIME;
            $row['from_by'] = $row['gmap_lat'] = $row['gmap_lng'] = $row['sex'] = $row['birthday'] = $row['districtid'] = $row['status'] = 0;
            $row['facebook'] = '';
            if( empty( $row['id'] ) )
            {
                $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . ' (adminid, provinceid, districtid, first_name, last_name, full_name, birthday, sex, address, email, mobile, facebook, from_by, gmap_lat, gmap_lng, add_time, edit_time, mkt_time, remkt_time, status) 
                VALUES (' . $row['userid'] . ', ' . $row['provinceid'] . ', 0, ' . $db->quote( $row['first_name'] ) . ', ' . $db->quote( $row['last_name'] ) . ', ' . $db->quote( $row['fullname'] ) . ', 0, 0, ' . $db->quote( $row['address'] ) . ', 
                ' . $db->quote( $row['email'] ) . ', ' . $db->quote( $row['mobile'] ) . ', ' . $db->quote( $row['facebook'] ) . ', 3, 0, 0, ' . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ', 0, 0)';

                $data_insert = array();
                $id = $db->insert_id($sql, 'id', $data_insert);

                if( $id > 0 ){
                    $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data .'_usersevents(eventid, customerid, full_name, sex, address, email, mobile, addtime, status) 
				    VALUES (' . intval( $row['event'] ) . ', ' . $id . ', ' . $db->quote( $row['fullname'] ) . ', 0, ' . $db->quote( $row['address'] ) . ', ' . $db->quote( $row['email'] ) . ', ' . $db->quote( $row['mobile'] ) . ', ' . NV_CURRENTTIME . ', 0)';
                    if( $db->query($sql) ){
                        //cap nhat so luong dang ky
                        $db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_listevents SET num_register=num_register+1 WHERE id=' . $row['event'] );

                        $note = sprintf( $lang_module['event_content'], $array_listevents[$row['event']]['title'], date('d/m/Y', $array_listevents[$row['event']]['timeevent'] ), $array_listevents[$row['event']]['addressevent']);
                        save_eventcontent( $id, NV_MEASURE_ID_ACCEPT, NV_EVENT_ID_REGISTER, $note );
                    }
                    die( 'OK' );
                }
            }
            else
            {
                $insert = 0;
                $stmt = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data .' SET provinceid=:provinceid, districtid=:districtid, full_name=:full_name, first_name=:first_name, last_name=:last_name, birthday=:birthday, sex=:sex, address=:address, email=:email, mobile=:mobile, gmap_lat=:gmap_lat, gmap_lng=:gmap_lng, from_by=:from_by, edit_time=:edit_time, status=:status WHERE id=' . $row['id'] );
                $stmt->bindParam( ':provinceid', $row['provinceid'], PDO::PARAM_INT );
                $stmt->bindParam( ':districtid', $row['districtid'], PDO::PARAM_INT );
                $stmt->bindParam( ':first_name', $row['first_name'], PDO::PARAM_STR );
                $stmt->bindParam( ':last_name', $row['last_name'], PDO::PARAM_STR );
                $stmt->bindParam( ':full_name', $row['fullname'], PDO::PARAM_STR );
                $stmt->bindParam( ':birthday', $row['birthday'], PDO::PARAM_INT );
                $stmt->bindParam( ':sex', $row['sex'], PDO::PARAM_INT );
                $stmt->bindParam( ':address', $row['address'], PDO::PARAM_STR );
                $stmt->bindParam( ':mobile', $row['mobile'], PDO::PARAM_STR );
                $stmt->bindParam( ':email', $row['email'], PDO::PARAM_STR );
                $stmt->bindParam( ':gmap_lat', $row['gmap_lat'], PDO::PARAM_INT );
                $stmt->bindParam( ':gmap_lng', $row['gmap_lng'], PDO::PARAM_INT );
                $stmt->bindParam( ':from_by', $row['from_by'], PDO::PARAM_INT );
                $stmt->bindParam( ':status', $row['status'], PDO::PARAM_INT );
                $stmt->bindParam( ':edit_time', $row['edit_time'], PDO::PARAM_INT );
                $exc = $stmt->execute();

                if( $exc )
                {
                    $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data .'_usersevents(eventid, customerid, full_name, sex, address, email, mobile, addtime, status) 
				    VALUES (' . intval( $row['event'] ) . ', ' . $row['id'] . ', ' . $db->quote( $row['fullname'] ) . ', 0, ' . $db->quote( $row['address'] ) . ', ' . $db->quote( $row['email'] ) . ', ' . $db->quote( $row['mobile'] ) . ', ' . NV_CURRENTTIME . ', 0)';
                    if( $db->query($sql) ){
                        //cap nhat so luong dang ky
                        $db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_listevents SET num_register=num_register+1 WHERE id=' . $row['event'] );

                        $note = sprintf( $lang_module['event_content'], $array_listevents[$row['event']]['title'], date('d/m/Y', $array_listevents[$row['event']]['timeevent'] ), $array_listevents[$row['event']]['addressevent']);
                        save_eventcontent( $row['id'], NV_MEASURE_ID_ACCEPT, NV_EVENT_ID_REGISTER, $note );
                    }
                    die( 'OK' );
                }
            }
        }
        catch ( PDOException $e )
        {
            trigger_error( $e->getMessage());
            die( 'Khách hàng ' . $row['last_name'] . ' đã được đăng ký vào sự kiện này rồi!'); //Remove this line after checks finished
        }
    }else{
        exit($error);
    }
}

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_listevents WHERE status=1 AND timeclose>' . NV_CURRENTTIME . ' ORDER BY weight LIMIT 16';

$array_listevents = $nv_Cache->db($sql, 'id', $module_name );

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_affiliate_province WHERE status=1 ORDER BY weight';
$array_province = $nv_Cache->db($sql, 'id', 'affiliate');

$eventid = $nv_Request->get_int('eventid', 'get', 0);
$contents = nv_theme_mkt_register( $array_listevents, $array_province, $eventid );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';