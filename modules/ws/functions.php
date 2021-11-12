<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 19 Jun 2014 09:53:21 GMT
 */

if (!defined('NV_SYSTEM')) die('Stop!!!');

global $userid;
$userid = $nv_Request->get_int('userid', 'post', 0);

//kiem tra key truoc khi cho phep truy cap chuc nang
$key = $nv_Request->get_title('key', 'post', '');
$md5 = md5($userid . $global_config['sitekey']);
//echo json_encode( array( $md5 ));
/*if ($key != $md5) {
    echo json_encode(array('Stop!'));
    exit();
}*/

//header('Content-Type: application/json');
define('NV_IS_MOD_WS', true);
define('NV_IS_TABLE_AFFILIATE', $db_config['prefix'] . '_affiliate');
define('NV_IS_LANG_TABLE_AFFILIATE', NV_PREFIXLANG . '_affiliate');
define('NV_IS_LANG_TABLE_SM', NV_PREFIXLANG . '_sm');
define('NV_MEASURE_ID_ACCEPT', 1);
define('NV_EVENT_ID_REGISTER', 1);

$sql = "SELECT * FROM " . NV_IS_TABLE_AFFILIATE . "_agency WHERE status=1 ORDER BY weight";
$array_agency = $nv_Cache->db($sql, 'id', 'affiliate');
$sql = "SELECT * FROM " . NV_IS_TABLE_AFFILIATE . "_possiton WHERE status=1 ORDER BY weight";
$array_possiton = $nv_Cache->db($sql, 'id', 'affiliate');

$sql = "SELECT * FROM " . NV_IS_LANG_TABLE_AFFILIATE . "_province WHERE status=1 ORDER BY weight";
$array_province = $nv_Cache->db($sql, 'id', 'affiliate');

$user_data_affiliate = array();
if ($userid > 0) {
    $sql = 'SELECT t1.code, t1.possitonid, t1.agencyid, t1.salary_day, t1.benefit, t1.subcatid, t1.permission, t1.datatext, t2.userid, t2.username, t2.first_name, t2.last_name, t2.birthday, t2.email FROM ' . NV_IS_TABLE_AFFILIATE . '_users AS t1 INNER JOIN ' . NV_USERS_GLOBALTABLE . ' AS t2 ON t1.userid=t2.userid WHERE t2.userid=' . $userid . ' ORDER BY t1.sort ASC';
    $user_data_affiliate = $db->query($sql)->fetch();
}


$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_mkt_listevents WHERE status=1 AND timeclose>' . NV_CURRENTTIME . ' ORDER BY weight LIMIT 16';
$array_listevents = $nv_Cache->db($sql, 'id', $module_name );

$array_discount = array();
$_sql = 'SELECT * FROM ' . NV_IS_LANG_TABLE_SM . '_discounts ORDER BY productid ASC, end_quantity DESC';
$array_tmp = $nv_Cache->db($_sql, 'id', 'sm');
foreach ($array_tmp as $tmp) {
    $array_discount[$tmp['productid']][] = $tmp;
}

function get_sub_nodes_users($subcatid)
{
    global $db, $array_possiton, $array_agency;

    $sql = 'SELECT t1.userid, t1.code, t1.possitonid, t1.agencyid, t1.numsubcat, t1.subcatid, t2.username, concat(t2.last_name, t2.first_name) fullname, t2.email, t1.mobile 
    FROM ' . NV_IS_TABLE_AFFILIATE . '_users t1, ' . NV_USERS_GLOBALTABLE . ' t2 WHERE t1.userid = t2.userid AND t1.userid IN(' . $subcatid . ')';

    $array_reponsive = array();
    $res = $db->query($sql);
    $i = 0;
    while ($tmp = $res->fetch()) {
        if ($tmp['possitonid'] > 0) {
            $type = isset($array_possiton[$tmp['possitonid']]) ? $array_possiton[$tmp['possitonid']]['title'] : 'N/A';
        } elseif ($tmp['agencyid'] > 0) {
            $type = isset($array_agency[$tmp['agencyid']]) ? $array_agency[$tmp['agencyid']]['title'] : 'N/A';
        }
        $array_reponsive[$i] = array();
        $array_reponsive[$i]["id"] = $tmp['userid'];
        $array_reponsive[$i]["type"] = $type;
        $array_reponsive[$i]["infor"] = $type . '-' . $tmp['code'] . '-' . $tmp['fullname'] . '-[' . $tmp['numsubcat'] . ']';
        $array_reponsive[$i]["downline"] = array();

        if (!empty($tmp['subcatid'])) {
            $array_reponsive[$i]["downline"] = get_sub_nodes_users($tmp['subcatid']);
        }
        $i++;
    }
    return $array_reponsive;
}


/**
 * nv_get_price()
 *
 * @param mixed $price
 * @param mixed $percent_sale
 * @param mixed $number
 * @param mixed $per_pro
 * @return
 */

function nv_get_price_for_agency($price_retail, $productid = 0, $number = 1, $per_pro = false)
{
    global $db, $array_discount;

    if ($productid > 0) {
        $discount = 0;
        //chiet khau theo sl sp
        if (isset($array_discount[$productid]) && !empty($array_discount[$productid])) {
            foreach ($array_discount[$productid] as $_d) {
                if ($_d['begin_quantity'] <= $number and $_d['end_quantity'] >= $number) {
                    $discount = $_d['percent'];
                    break;
                }
            }
        }
        $price_agency = ($price_retail - $price_retail * $discount / 100) / 1000;
        if ($per_pro) {
            $price_agency = $price_agency * 1000 * $number;
        } else {
            $price_agency = $price_agency * 1000;
        }
        $return = $price_agency;// Giá nhap cho agency
        return $return;
    }


}


//kiem tra so luong hang trong kho cua NPP phia tren, Nhap vao IDcustomer cua nguoi nhap hang
function checkNumTotalWarehouseLogs($depotid, $productid)
{
    global $db, $userid;

    list ($quantity_out, $quantity_in, $quantity_gift_in, $quantity_gift_out) = $db->query('SELECT quantity_out, quantity_in, quantity_gift_in, quantity_gift_out FROM ' . NV_IS_LANG_TABLE_SM . '_warehouse_logs WHERE customerid =' . $userid . ' AND productid=' . $productid . ' AND depotid=' . $depotid)->fetch(3);
    return ($quantity_in + $quantity_gift_in) - ($quantity_out + $quantity_gift_out);

    return 0;
}


//Tao tai khoan khach hang le mua san pham
function createCustomer($customer_name, $customer_mail, $customer_phone, $customer_address)
{

    global $db, $userid;

    $sql = 'SELECT customer_id FROM ' . NV_IS_LANG_TABLE_SM . '_customer WHERE refer_userid=' . $userid . ' AND fullname=' . $db->quote($customer_name) . ' AND phone=' . $db->quote($customer_phone);
    list($customer_id) = $db->query($sql)->fetch(3);//neu = 0 thi se tao tai khoan
    $customer_id = intval($customer_id);
    if ($customer_id == 0) {

        $result = $db->query("SHOW TABLE STATUS WHERE Name='" . NV_IS_LANG_TABLE_SM . "_customer'");
        $item = $result->fetch();
        $result->closeCursor();
        $precode = 'KH%02s';
        $customer_code = vsprintf($precode, $item['auto_increment']);
        $sql = 'INSERT INTO ' . NV_IS_LANG_TABLE_SM . '_customer (refer_userid, code, fullname, address, phone, email, description, add_time, edit_time, custype, status) VALUES (:refer_userid, :code, :fullname, :address, :phone, :email, :description, :add_time, :edit_time, :custype, :status)';

        $data_insert = array();
        $data_insert['refer_userid'] = $userid;
        $data_insert['code'] = $customer_code;
        $data_insert['fullname'] = $customer_name;
        $data_insert['address'] = $customer_address;
        $data_insert['phone'] = $customer_phone;
        $data_insert['email'] = $customer_mail;
        $data_insert['description'] = '';
        $data_insert['add_time'] = NV_CURRENTTIME;
        $data_insert['edit_time'] = NV_CURRENTTIME;
        $data_insert['custype'] = 0;
        $data_insert['status'] = 1;

        $customer_id = $db->insert_id($sql, 'customer_id', $data_insert);
    }
    return $customer_id;

}


//tao noi dung cham soc khach hang
function nvInsertSmsQueue($order_id, $proid, $product_name, $booktime, $full_name, $email, $phone, $address, $day_received)
{
    global $db, $module_data;

    //lay kich ban cham soc dc kich hoat
    $data = $db->query('SELECT * FROM ' . NV_IS_LANG_TABLE_SM . '_scenario_header WHERE status=1 AND proid =' . $proid)->fetch();
    if (!empty($data)) {

        $customer['phone'] = $phone;
        $customer['fullname'] = $full_name;
        $customer['email'] = $email;
        $customer['address'] = $address;
        $customer['gender'] = 2;

        $sql = 'SELECT * FROM ' . NV_IS_LANG_TABLE_SM . '_scenario_detail WHERE status=1 AND scenarioid =' . $data['id'];
        $result = $db->query($sql);
        while ($row = $result->fetch()) {

            $receiver = '';
            if ($row['sendtype'] == 1 || $row['sendtype'] == 3) {
                $receiver = $phone;
            } elseif ($row['sendtype'] == 2) {
                $receiver = $email;
            }
            //co nguoi nhan thi moi tao noi dung cham soc
            if (!empty($receiver)) {
                $title = nv_build_content_customer($row['sendtype'], $row['title'], $customer);
                $content = nv_build_content_customer($row['sendtype'], $row['content'], $customer);

                //neu mua hang nhan ngay thi chinh lai thoi gian gui sms ngay sau thoi diem mua hang 1h
                $timesend = 0;
                if ($day_received == 0 && $row['daysend'] == 0) {
                    $timesend = $booktime + 3600;
                } else {
                    $timesend = $booktime + (($day_received + $row['daysend']) * 86400);
                }

                $sql = 'INSERT INTO ' . NV_IS_LANG_TABLE_SM . '_message_queue( order_id, proid, sid, sid_detail, title, receiver, content, timesend, sendtype, active ) 
                VALUES (  ' . intval($order_id) . ', ' . intval($proid) . ', ' . intval($data['id']) . ', ' . intval($row['id']) . ', ' . $db->quote($title) . ', ' . $db->quote($receiver) . ', ' . $db->quote($content) . ', ' . $timesend . ', ' . intval($row['sendtype']) . ', 1)';
                $db->query($sql);
            }
        }
    }
}


function nv_build_content_customer($sendtype, $content, $customer)
{
    global $global_config, $lang_module, $user_data_affiliate;

    //khong phai gui mail thi loai bo cac the html
    if ($sendtype != 2) {
        $content = nv_unhtmlspecialchars($content);
    }
    $shop_mobile = !empty($user_data_affiliate['mobile']) ? $user_data_affiliate['mobile'] : $global_config['site_phone'];
    // Thay the bien noi dung
    $array_replace = array(
        '[FULLNAME]' => !empty($customer['fullname']) ? $customer['fullname'] : $lang_module['customers'],
        '[MOBILE]' => $customer['phone'],
        '[SHOP_MOBILE]' => $shop_mobile,
        '[EMAIL]' => $customer['email'],
        '[ADDRESS]' => $customer['address'],
        '[ALIAS]' => $lang_module['alias_' . $customer['gender']],
        '[SITE_NAME]' => $global_config['site_name'],
        '[SITE_DOMAIN]' => NV_MY_DOMAIN
    );
    $html = '';
    foreach ($array_replace as $index => $value) {
        $html = str_replace($index, $value, $html);
        $content = str_replace($index, $value, $content);
    }
    return $content;
}


//nhap hang cho thanh vien
function nhapkhohanghoa($customerid, $depotid, $productid, $quantity, $quantity_gift, $price, $type, $typeorder = 3, $order_id = 0)
{

    global $db, $userid;

    $customerid = intval($customerid);
    if ($productid > 0 and ($quantity > 0 || $quantity_gift > 0)) {
        //khong phai khach le
        if ($typeorder != 3) {
            $sql = 'SELECT COUNT(*) FROM ' . NV_IS_LANG_TABLE_SM . '_warehouse_logs WHERE customerid=' . $customerid . ' AND productid=' . $productid . ' AND depotid=0';

            $check_exits = $db->query($sql)->fetchColumn();
            if ($check_exits == 0) {
                $sql = 'INSERT INTO ' . NV_IS_LANG_TABLE_SM . '_warehouse_logs( customerid, depotid, productid, quantity_in, quantity_gift_in, price_in, quantity_out, quantity_gift_out, price_out ) 
                VALUES ( ' . $customerid . ', 0, ' . $productid . ', ' . $quantity . ', ' . intval($quantity_gift) . ', 0, 0, 0, ' . $price . ')';

                $res = $db->query($sql);
            } else {
                $sql = 'UPDATE ' . NV_IS_LANG_TABLE_SM . '_warehouse_logs SET quantity_in = quantity_in ' . $type . ' ' . $quantity . ', quantity_gift_in = quantity_gift_in ' . $type . ' ' . intval($quantity_gift) . ', price_out= price_out ' . $type . ' ' . $price . ' WHERE customerid =' . $customerid . ' AND productid=' . $productid . ' AND depotid=0';

                $res = $db->query($sql);
            }
            save_warehouse_order_customer($quantity, $price, $customerid, 0, $productid, $type, $order_id);
        }
        if (($res or $typeorder == 3) && $customerid > 0) {

            $sql = 'SELECT COUNT(*) FROM ' . NV_IS_LANG_TABLE_SM . '_warehouse_logs WHERE customerid=' . $userid . ' AND productid=' . $productid;
            $check_exits = $db->query($sql)->fetchColumn();
            if ($check_exits > 0) {
                //tru so luong trong kho tong ben tren va cong tien cua khach hang nay
                $db->query('UPDATE ' . NV_IS_LANG_TABLE_SM . '_warehouse_logs SET quantity_out = quantity_out' . $type . ' ' . $quantity . ', quantity_gift_out = quantity_gift_out' . $type . ' ' . intval($quantity_gift) . ', price_in= price_in ' . $type . ' ' . $price . ' WHERE customerid =' . $userid . ' AND productid=' . $productid . ' AND depotid=' . $depotid);

                save_warehouse_order_customer($quantity, $price, $userid, $depotid, $productid, '-', $order_id);
            }

        }
    }
}

//Ham ghi lai thay doi ve kho hang sau khi tra hang
function save_warehouse_order_customer($quantity, $price, $customerid, $depotid, $productid, $type, $order_id)
{
    global $db;

    $sql = 'SELECT quantity_in, quantity_out FROM ' . NV_IS_LANG_TABLE_SM . '_warehouse_logs WHERE customerid=' . $customerid . ' AND productid=' . $productid . ' AND depotid=' . $depotid;

    $data_warehouse_logs = $db->query($sql)->fetch();

    //sau khi co hanh dong cong so luong vao kho
    $quantity_in = $quantity_out = $price_in = $price_out = $quantity_befor = $quantity_after = 0;
    if ($type == '+') {
        $quantity_befor = $data_warehouse_logs['quantity_in'] - $data_warehouse_logs['quantity_out'] - $quantity;
        $quantity_after = $data_warehouse_logs['quantity_in'] - $data_warehouse_logs['quantity_out'];
        $quantity_in = $quantity;
        $price_out = $price;
    } else {
        $quantity_befor = ($data_warehouse_logs['quantity_in'] - $data_warehouse_logs['quantity_out']) + $quantity;
        $quantity_after = $data_warehouse_logs['quantity_in'] - $data_warehouse_logs['quantity_out'];
        $quantity_out = $quantity;
        $price_in = $price;
    }

    $sql = 'INSERT INTO ' . NV_IS_LANG_TABLE_SM . '_warehouse_order( customerid, depotid, productid, orderid, quantity_befor, quantity_in, price_in, quantity_after, quantity_out, price_out, addtime ) 
                VALUES ( :customerid, :depotid, :productid, :orderid, :quantity_befor, :quantity_in, :price_in, :quantity_after, :quantity_out, :price_out, :addtime )';
    $data_insert = array();
    $data_insert['customerid'] = $customerid;
    $data_insert['depotid'] = $depotid;
    $data_insert['productid'] = $productid;
    $data_insert['orderid'] = $order_id;
    $data_insert['quantity_befor'] = $quantity_befor;
    $data_insert['quantity_in'] = $quantity_in;
    $data_insert['price_in'] = $price_in;
    $data_insert['quantity_after'] = $quantity_after;
    $data_insert['quantity_out'] = $quantity_out;
    $data_insert['price_out'] = $price_out;
    $data_insert['addtime'] = NV_CURRENTTIME;

    return $db->insert_id($sql, '', $data_insert);
}


function check_phone_avaible($string)
{
    $string = str_replace(array('-', '.', ' '), '', $string);

    if (!preg_match('/^(01[2689]|03|05|07|08|09)[0-9]{8}$/', $string)) {
        return 0;
    }
    return $string;

}


//Tao tai khoan khach hang mua san pham
function nvCreateAgency($array_data)
{

    global $db, $user_data_affiliate, $lang_module, $crypt, $global_config, $module_config, $userid, $array_agency;

    $active = 0;
    if ($user_data_affiliate['permission'] == 1 || $module_config['affiliate']['verify_user'] == 0) {
        $active = 1;
    }
    //kiem tra xem he thong da co tk nay chua
    $md5username = nv_md5safe($array_data['mobile']);
    $stmt = $db->prepare('SELECT userid FROM ' . NV_USERS_GLOBALTABLE . ' WHERE md5username= :md5username');
    $stmt->bindParam(':md5username', $md5username, PDO::PARAM_STR);
    $stmt->execute();
    $query_error_username = $stmt->fetchColumn();
    $array_mess_error = array();
    if ($query_error_username) {
        $array_mess_error = $lang_module['edit_error_username_exist'];
    }
    $array_data['email'] = (!empty($array_data['email'])) ? $array_data['email'] : $array_data['mobile'] . '@gmail.com';
    // Thực hiện câu truy vấn để kiểm tra email đã tồn tại chưa.
    $stmt = $db->prepare('SELECT userid FROM ' . NV_USERS_GLOBALTABLE . ' WHERE email= :email');
    $stmt->bindParam(':email', $array_data['email'], PDO::PARAM_STR);
    $stmt->execute();
    $query_error_email = $stmt->fetchColumn();
    if ($query_error_email) {
        $array_mess_error = $lang_module['edit_error_email_exist'];
    }
    // Thực hiện câu truy vấn để kiểm tra email đã tồn tại trong nv4_users_reg  chưa.
    $stmt = $db->prepare('SELECT userid FROM ' . NV_USERS_GLOBALTABLE . '_reg WHERE email= :email');
    $stmt->bindParam(':email', $array_data['email'], PDO::PARAM_STR);
    $stmt->execute();
    $query_error_email_reg = $stmt->fetchColumn();
    if ($query_error_email_reg) {
        $array_mess_error = $lang_module['edit_error_email_exist'];
    }
    $stmt = $db->prepare('SELECT userid FROM ' . NV_USERS_GLOBALTABLE . '_openid WHERE email= :email');
    $stmt->bindParam(':email', $array_data['email'], PDO::PARAM_STR);
    $stmt->execute();
    $query_error_email_openid = $stmt->fetchColumn();
    if ($query_error_email_openid) {
        $array_mess_error = $lang_module['edit_error_email_exist'];
    }
    if (!empty($array_mess_error)) {
        return array('customer_id' => 0, 'message' => $array_mess_error);
    }

    $_user['password'] = $array_data['mobile'];
    $_user['username'] = $array_data['mobile'];
    $_user['email'] = $array_data['email'];
    $customer_name = explode(' ', $array_data['fullname']);
    $total_str = count($customer_name);
    $_user['first_name'] = $customer_name[$total_str - 1];
    unset($customer_name[$total_str - 1]);
    $_user['last_name'] = implode(' ', $customer_name);
    try {
        //tao tai khoan thanh vien tai module users
        $sql = "INSERT INTO " . NV_USERS_GLOBALTABLE . " (
        group_id, username, md5username, password, email, first_name, last_name, gender, birthday, sig, regdate,
        question, answer, passlostkey, view_mail,
        remember, in_groups, active, checknum, last_login, last_ip, last_agent, last_openid, idsite)
    VALUES (
        0,
        :username,
        :md5_username,
        :password,
        :email,
        :first_name,
        :last_name,
        :gender,
        0,
        :sig,
        " . NV_CURRENTTIME . ",
        :question,
        :answer,
        '',
         1,
         1,
         '', " . $active . ", '', 0, '', '', '', " . $global_config['idsite'] . "
    )";

        $data_insert = array();
        $data_insert['username'] = $_user['username'];
        $data_insert['md5_username'] = $md5username;
        $data_insert['password'] = $crypt->hash_password($_user['password'], $global_config['hashprefix']);
        $data_insert['email'] = $_user['email'];
        $data_insert['first_name'] = $_user['first_name'];
        $data_insert['last_name'] = $_user['last_name'];
        $data_insert['gender'] = 'N';
        $data_insert['sig'] = '';
        $data_insert['question'] = $lang_module['question_info_phone'];
        $data_insert['answer'] = $_user['username'];
        $_user['peopleid'] = '';
        $customer_id = $db->insert_id($sql, 'userid', $data_insert);

        if ($customer_id > 0) {

            //tao tk he thong QL Affiliate
            $precode = $module_config['affiliate']['precode'];
            $array_data['code'] = vsprintf($precode, $customer_id);

            $array_data['precode'] = $array_data['code'] . '%01s';

            $weight = $db->query('SELECT max(weight) FROM ' . NV_IS_TABLE_AFFILIATE . '_users WHERE parentid=' . $userid)->fetchColumn();
            $weight = intval($weight) + 1;
            $subcatid = '';

            $stmt = $db->prepare("INSERT INTO " . NV_IS_TABLE_AFFILIATE . "_users (userid, parentid, precode, code, mobile, peopleid, salary_day, benefit, datatext, weight, sort, lev, possitonid, agencyid, istype, numsubcat, listparentid, subcatid, add_time, edit_time, status, provinceid, districtid, permission, haveorder, shareholder) VALUES
			(:userid, :parentid, :precode, :code, :mobile, :peopleid, :salary_day, :benefit, :datatext, :weight, '0', '0', :possitonid, :agencyid, 0, '0', '', :subcatid, " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", " . $active . ", :provinceid, :districtid, 0, 0, 0)");

            $stmt->bindValue(':salary_day', 0, PDO::PARAM_INT);
            $stmt->bindValue(':benefit', 0, PDO::PARAM_INT);
            $stmt->bindParam(':userid', $customer_id, PDO::PARAM_INT);
            $stmt->bindParam(':parentid', $userid, PDO::PARAM_INT);
            $stmt->bindParam(':precode', $array_data['precode'], PDO::PARAM_STR);
            $stmt->bindParam(':code', $array_data['code'], PDO::PARAM_STR);
            $stmt->bindParam(':mobile', $_user['username'], PDO::PARAM_STR);
            $stmt->bindParam(':peopleid', $array_data['peopleid'], PDO::PARAM_STR);
            $stmt->bindParam(':weight', $weight, PDO::PARAM_INT);
            $stmt->bindValue(':possitonid', 0, PDO::PARAM_INT);
            $stmt->bindParam(':agencyid', $array_data['agencyid'], PDO::PARAM_INT);
            $stmt->bindParam(':subcatid', $subcatid, PDO::PARAM_STR);
            $stmt->bindValue(':datatext', serialize($array_data), PDO::PARAM_STR);
            $stmt->bindParam(':provinceid', $array_data['provinceid'], PDO::PARAM_INT);
            $stmt->bindParam(':districtid', $array_data['districtid'], PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount()) {
                nv_fix_users_order($userid);
            }

            if ($active == 1) {
                $_url = NV_MY_DOMAIN . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=login', true);
                if ($module_config['affiliate']['sms_register'] == 1) {
                    $agency_title = $array_agency[$array_data['agencyid']]['title'];
                    $content = 'Chuc mung ban da tro thanh ' . $agency_title . ' CASH13. Hay dang nhap ' . $_url . ' voi tai khoan: ' . $array_data['mobile'] . ', mat khau: ' . $array_data['mobile'] . ' va thay doi mat khau nhe.';
                    call_funtion_send_sms($content, $array_data['mobile']);
                }

                // Gửi mail thông báo
                if (!empty($_user['email'])) {
                    $full_name = nv_show_name_user($_user['first_name'], $_user['last_name'], $_user['username']);
                    $subject = $lang_module['adduser_register'];

                    $message = sprintf($lang_module['adduser_register_info1'], $full_name, $global_config['site_name'], $_url, $_user['username'], $_user['password']);
                    @nv_sendmail($global_config['site_email'], $_user['email'], $subject, $message);
                }
            }
        }
    } catch (PDOException $e) {
        return array('message' => $e->getMessage());
    }
    return array('customer_id' => $customer_id, 'fullname' => $array_data['fullname'], 'agencyid' => $array_data['agencyid'], 'mobile' => $array_data['mobile'], 'provinceid' => $array_data['provinceid'], 'districtid' => $array_data['districtid'], 'username' => $_user['username'], 'password' => $_user['password']);
}


/**
 * nv_fix_users_order()
 *
 * @param integer $parentid
 * @param integer $order
 * @param integer $lev
 * @return
 */
function nv_fix_users_order($parentid = 0, $order = 0, $lev = 0)
{
    global $db;

    $sql = 'SELECT userid, parentid FROM ' . NV_IS_TABLE_AFFILIATE . '_users WHERE parentid=' . $parentid . ' ORDER BY weight ASC';

    $result = $db->query($sql);
    $array_cat_order = array();
    while ($row = $result->fetch()) {
        $array_cat_order[] = $row['userid'];
    }
    $result->closeCursor();
    $weight = 0;
    if ($parentid > 0) {
        ++$lev;
    } else {
        $lev = 0;
    }

    foreach ($array_cat_order as $catid_i) {
        ++$order;
        ++$weight;
        $sql = 'UPDATE ' . NV_IS_TABLE_AFFILIATE . '_users SET weight=' . $weight . ', sort=' . $order . ', lev=' . $lev . ' WHERE userid=' . intval($catid_i);
        $db->query($sql);

        if ($parentid > 0) {

            list($listparentid) = $db->query('SELECT listparentid FROM ' . NV_IS_TABLE_AFFILIATE . '_users WHERE userid=' . $parentid)->fetch(3);

            $sql = 'UPDATE ' . NV_IS_TABLE_AFFILIATE . '_users SET ';
            if (!empty($listparentid)) {
                $sql .= "listparentid='" . $listparentid . ',' . $parentid . "'";
            } else {
                $sql .= "listparentid='" . $parentid . "'";
            }
            $sql .= ' WHERE userid=' . intval($catid_i);
            $db->query($sql);
        }
        $order = nv_fix_users_order($catid_i, $order, $lev);
    }
    $numsubcat = $weight;
    if ($parentid > 0) {
        $sql = 'UPDATE ' . NV_IS_TABLE_AFFILIATE . '_users SET numsubcat=' . $numsubcat;
        if ($numsubcat == 0) {
            $sql .= ",subcatid=''";
        } else {
            $sql .= ",subcatid='" . implode(',', $array_cat_order) . "'";
        }
        $sql .= ' WHERE userid=' . intval($parentid);
        $db->query($sql);

    }
    return $order;
}

// Xác định cấu hình module user
$global_users_config = array();
$cacheFile = NV_LANG_DATA . '_users_config_' . NV_CACHE_PREFIX . '.cache';
$cacheTTL = 3600;
if (($cache = $nv_Cache->getItem('users', $cacheFile, $cacheTTL)) != false) {
    $global_users_config = unserialize($cache);
} else {
    $sql = "SELECT config, content FROM " . NV_USERS_GLOBALTABLE . "_config";
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        $global_users_config[$row['config']] = $row['content'];
    }
    $cache = serialize($global_users_config);
    $nv_Cache->setItem('users', $cacheFile, $cache, $cacheTTL);
}


/**
 * validUserLog()
 *
 * @param mixed $array_user
 * @param mixed $remember
 * @param mixed $opid
 * @return
 */
function validUserLog($array_user, $remember, $opid, $current_mode = 0)
{
    global $db, $global_config, $nv_Request, $lang_module, $global_users_config, $module_name, $client_info;

    $remember = intval($remember);
    $checknum = md5(nv_genpass(10));

    $user = array(
        'userid' => $array_user['userid'],
        'current_mode' => $current_mode,
        'checknum' => $checknum,
        'checkhash' => md5($array_user['userid'] . $checknum . $global_config['sitekey'] . $client_info['browser']['key']),
        'current_agent' => NV_USER_AGENT,
        'last_agent' => $array_user['last_agent'],
        'current_ip' => NV_CLIENT_IP,
        'last_ip' => $array_user['last_ip'],
        'current_login' => NV_CURRENTTIME,
        'last_login' => intval($array_user['last_login']),
        'last_openid' => $array_user['last_openid'],
        'current_openid' => $opid
    );

    $stmt = $db->prepare("UPDATE " . NV_USERS_GLOBALTABLE . " SET
		checknum = :checknum,
		last_login = " . NV_CURRENTTIME . ",
		last_ip = :last_ip,
		last_agent = :last_agent,
		last_openid = :opid,
		remember = " . $remember . "
		WHERE userid=" . $array_user['userid']);

    $stmt->bindValue(':checknum', $checknum, PDO::PARAM_STR);
    $stmt->bindValue(':last_ip', NV_CLIENT_IP, PDO::PARAM_STR);
    $stmt->bindValue(':last_agent', NV_USER_AGENT, PDO::PARAM_STR);
    $stmt->bindValue(':opid', $opid, PDO::PARAM_STR);
    $stmt->execute();
    $live_cookie_time = ($remember) ? NV_LIVE_COOKIE_TIME : 0;


    $nv_Request->set_Cookie('nvloginhash', serialize($user), $live_cookie_time);

    if (!empty($global_users_config['active_user_logs'])) {
        $log_message = $opid ? ($lang_module['userloginviaopt'] . ' ' . $opid) : $lang_module['st_login'];
        nv_insert_logs(NV_LANG_DATA, $module_name, '[' . $array_user['username'] . '] ' . $log_message, ' Client IP:' . NV_CLIENT_IP, 0);
    }
}


function call_funtion_send_sms($content, $mobile)
{

    if (file_exists(NV_ROOTDIR . '/modules/sm/function.sendsms.php')) {
        require NV_ROOTDIR . '/modules/sm/function.sendsms.php';
        sendsms($content, $mobile);
    }

}


function nv_ws_get_path_dir()
{
    global $db;

    $currentpath = 'affiliate/' . date('Ym');

    if (file_exists(NV_UPLOADS_REAL_DIR . '/' . $currentpath)) {
        $upload_real_dir_page = NV_UPLOADS_REAL_DIR . '/' . $currentpath;
    } else {
        $upload_real_dir_page = NV_UPLOADS_REAL_DIR . '/affiliate';
        $e = explode('/', $currentpath);
        if (!empty($e)) {
            $cp = '';
            foreach ($e as $p) {
                if (!empty($p) and !is_dir(NV_UPLOADS_REAL_DIR . '/' . $cp . $p)) {
                    $mk = nv_mkdir(NV_UPLOADS_REAL_DIR . '/' . $cp, $p);
                    if ($mk[0] > 0) {
                        $upload_real_dir_page = $mk[2];
                        try {
                            $db->query("INSERT INTO " . NV_UPLOAD_GLOBALTABLE . "_dir (dirname, time) VALUES ('" . NV_UPLOADS_DIR . "/" . $cp . $p . "', 0)");
                        } catch (PDOException $e) {
                            trigger_error($e->getMessage());
                        }
                    }
                } elseif (!empty($p)) {
                    $upload_real_dir_page = NV_UPLOADS_REAL_DIR . '/' . $cp . $p;
                }
                $cp .= $p . '/';
            }
        }
        $upload_real_dir_page = str_replace('\\', '/', $upload_real_dir_page);
    }

    $currentpath = str_replace(NV_ROOTDIR . '/', '', $upload_real_dir_page);
    return $currentpath;
}

function save_file_from_base64($data, $file_name)
{

    $file_allowed_ext[] = 'images';
    $pathSaveUrl = nv_ws_get_path_dir();
    $length_substr = strlen(NV_ROOTDIR . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/affiliate/');
    $binary = base64_decode($data);
    $file_info = getimagesizefromstring($binary);
    $mine = explode('/', $file_info['mime']);

    if ($mine[0] == 'image') {

        $file = fopen(NV_ROOTDIR . '/' . $pathSaveUrl . '/' . $file_name . '.' . $mine[1], 'w');
        $file_return = substr(NV_ROOTDIR . '/' . $pathSaveUrl . '/' . $file_name . '.' . $mine[1], $length_substr);
        fwrite($file, $binary);
        fclose($file);
        return $file_return;
    } else {
        return false;
    }
}

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_mkt_listevents WHERE status=1 AND timeclose>' . NV_CURRENTTIME . ' ORDER BY weight LIMIT 16';

$array_listevents = $nv_Cache->db($sql, 'id', 'mkt');

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_affiliate_province WHERE status=1 ORDER BY weight';
$array_province = $nv_Cache->db($sql, 'id', 'affiliate');
function save_eventcontent($customerid, $measureid = NV_MEASURE_ID_ACCEPT, $eventtype = NV_EVENT_ID_REGISTER, $note,$userid)
{
    global $db, $user_info;
    if ($customerid > 0 && !empty($note)) {
        try {
            $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_mkt_events (customerid, measureid, adminid, addtime, eventtype, content) VALUES ( :customerid, :measureid, :adminid, :addtime, :eventtype, :content)');

            $addtime = NV_CURRENTTIME;
            $stmt->bindParam(':addtime', $addtime, PDO::PARAM_INT);
            $stmt->bindParam(':customerid', $customerid, PDO::PARAM_STR);
            $stmt->bindParam(':measureid', $measureid, PDO::PARAM_STR);
            $stmt->bindParam(':adminid', intval($userid), PDO::PARAM_INT);
            $stmt->bindParam(':eventtype', $eventtype, PDO::PARAM_INT);
            $stmt->bindParam(':content', $note, PDO::PARAM_STR, strlen($note));

            $exc = $stmt->execute();
            if ($exc) {
                return 1;
            }

        } catch (PDOException $e) {
            die($e->getMessage());
        }
        return 0;
    }
    return 0;
}