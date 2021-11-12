<?php
/**
 * Created by PhpStorm.
 * User: thong
 * Date: 20/11/2018
 * Time: 9:15 AM
 */

if ($userid > 0) {
    $array_data['userid'] = $nv_Request->get_int('userid', 'post', 0);
    $array_data['eventid'] = $nv_Request->get_int('eventid', 'post', 0);
    $array_data['mobile'] = $nv_Request->get_title('mobile', 'post', '', 1);
    $array_data['fullname'] = $nv_Request->get_title('fullname', 'post', '');
    $array_data['email'] = $nv_Request->get_title('email', 'post', '');
    $array_data['address'] = $nv_Request->get_title('address', 'post', '', 1);
    $array_data['provinceid'] = $nv_Request->get_int('provinceid', 'post', 0);

    $check_phone = check_phone_avaible($array_data['mobile']);
    $check_exits_peopleid = $db->query("SELECT COUNT(*) FROM " . $db_config['prefix'] . '_affiliate_users WHERE peopleid=' . $db->quote($array_data['peopleid']))->fetchColumn();

    if (empty($array_data['mobile'])) {
        $array_reponsive = array(
            'status' => 0,
            "message" => $lang_module['error_mobiles']
        );

    } elseif ($check_phone == 0) {
        $array_reponsive = array(
            'status' => 0,
            "message" => $lang_module['error_mobile']
        );

    } elseif (empty($array_data['fullname'])) {
        $array_reponsive = array(
            'status' => 0,
            "message" => $lang_module['error_fullnames']
        );
    } else {
        $array_data['fullname'] = $nv_Request->get_title('fullname', 'post', '');
        $pos = strrpos($array_data['fullname'], ' ');
        if ($pos === false) {
            $array_data['first_name'] = '';
            $array_data['last_name'] = $array_data['fullname'];
        } else {
            $array_data['first_name'] = substr($array_data['fullname'], 0, $pos + 1);
            $array_data['last_name'] = substr($array_data['fullname'], $pos);
        }
        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_mkt WHERE mobile=' . $db->quote($array_data['mobile']);
        $data_content = $db->query($sql)->fetch();
        if (!empty($data_content)) {
            $array_data['id'] = $data_content['id'];
        }
        try {
            $array_data['edit_time'] = $array_data['add_time'] = NV_CURRENTTIME;
            $array_data['from_by'] = $array_data['gmap_lat'] = $array_data['gmap_lng'] = $array_data['status'] = 0;

            if (empty($array_data['id'])) {
                $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_mkt (adminid, provinceid, first_name, last_name, full_name,address, email, mobile,  from_by, gmap_lat, gmap_lng, add_time, edit_time, mkt_time, remkt_time, status) 
                VALUES (' . $array_data['userid'] . ', ' . $array_data['provinceid'] . ', ' . $db->quote($array_data['first_name']) . ', ' . $db->quote($array_data['last_name']) . ', ' . $db->quote($array_data['fullname']) . ', ' . $db->quote($array_data['address']) . ',' . $db->quote($array_data['email']) . ', ' . $db->quote($array_data['mobile']) . ',0,0,0, ' . NV_CURRENTTIME . ', 0, 0, 0, 0)';
                $data_insert = array();
                $id = $db->insert_id($sql, 'id', $data_insert);

                if ($id > 0) {
                    $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_mkt_usersevents(eventid, customerid, adminid, full_name, address, email, mobile, addtime, status) 
				    VALUES (' . intval($array_data['eventid']) . ', ' . $id . ', ' . $array_data['userid'] . ', ' . $db->quote($array_data['fullname']) . ', ' . $db->quote($array_data['address']) . ', ' . $db->quote($array_data['email']) . ', ' . $db->quote($array_data['mobile']) . ', ' . NV_CURRENTTIME . ', 0)';

                    die($sql);
                    if ($db->query($sql)) {
                        //cap nhat so luong dang ky
                        $db->query('UPDATE ' . NV_PREFIXLANG . '_mkt_listevents SET num_register=num_register+1 WHERE id=' . $array_data['eventid']);
                        $note = sprintf($lang_module['event_content'], $array_listevents[$array_data['eventid']]['title'], date('d/m/Y', $array_listevents[$array_data['eventid']]['timeevent']), $array_listevents[$array_data['eventid']]['addressevent']);
                        save_eventcontent($id, NV_MEASURE_ID_ACCEPT, NV_EVENT_ID_REGISTER, $note, $array_data['userid']);

                        $array_reponsive = array(
                            'status' => 1,
                            "message" =>  $lang_module['success_insert']
                        );
                    } else {
                        die('Khách hàng ' . $array_data['last_name'] . ' insert thất bại!');
                    }
                } else {
                    $array_reponsive = array(
                        'status' => 0,
                        "message" => $lang_module['error_insert']
                    );
                }
            } else {
                $insert = 0;
                $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_mkt SET provinceid=:provinceid, first_name=:first_name, last_name=:last_name, full_name=:full_name, address=:address, mobile=:mobile,email=:email, gmap_lat=:gmap_lat, gmap_lng=:gmap_lng, from_by=:from_by, status=:status, edit_time=:edit_time WHERE id=' . $array_data['id']);
                $stmt->bindParam(':provinceid', $array_data['provinceid'], PDO::PARAM_INT);
                $stmt->bindParam(':first_name', $array_data['first_name'], PDO::PARAM_STR);
                $stmt->bindParam(':last_name', $array_data['last_name'], PDO::PARAM_STR);
                $stmt->bindParam(':full_name', $array_data['fullname'], PDO::PARAM_STR);
                $stmt->bindParam(':address', $array_data['address'], PDO::PARAM_STR);
                $stmt->bindParam(':mobile', $array_data['mobile'], PDO::PARAM_STR);
                $stmt->bindParam(':email', $array_data['email'], PDO::PARAM_STR);
                $stmt->bindParam(':gmap_lat', $array_data['gmap_lat'], PDO::PARAM_INT);
                $stmt->bindParam(':gmap_lng', $array_data['gmap_lng'], PDO::PARAM_INT);
                $stmt->bindParam(':from_by', $array_data['from_by'], PDO::PARAM_INT);
                $stmt->bindParam(':status', $array_data['status'], PDO::PARAM_INT);
                $stmt->bindParam(':edit_time', $array_data['edit_time'], PDO::PARAM_INT);
                $exc = $stmt->execute();

                if ($exc) {
                    $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_mkt_usersevents(eventid, customerid, adminid, full_name, address, email, mobile, addtime, status) 
				    VALUES (' . intval($array_data['eventid']) . ', ' . $id . ', ' . $array_data['userid'] . ', ' . $db->quote($array_data['fullname']) . ', ' . $db->quote($array_data['address']) . ', ' . $db->quote($array_data['email']) . ', ' . $db->quote($array_data['mobile']) . ', ' . NV_CURRENTTIME . ', 0)';
                    if ($db->query($sql)) {
                        //cap nhat so luong dang ky
                        $db->query('UPDATE ' . NV_PREFIXLANG . '_mkt_listevents SET num_register=num_register+1 WHERE id=' . $array_data['eventid']);
                        $note = sprintf($lang_module['event_content'], $array_listevents[$array_data['eventid']]['title'], date('d/m/Y', $array_listevents[$array_data['eventid']]['timeevent']), $array_listevents[$array_data['eventid']]['addressevent']);
                        save_eventcontent($id, NV_MEASURE_ID_ACCEPT, NV_EVENT_ID_REGISTER, $note, $array_data['userid']);
                    }
                }
            }

        } catch (PDOException $e) {
            trigger_error($e->getMessage());
            die('Khách hàng ' . $array_data['last_name'] . ' đã được đăng ký vào sự kiện này rồi!');
        }
    }

    echo json_encode($array_reponsive);
} else {
    echo json_encode(array());
}
