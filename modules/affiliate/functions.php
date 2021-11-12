<?php

/**
 * @Project NUKEVIET 4.x
 * @Author mynukeviet (contact@mynukeviet.com)
 * @Copyright (C) 2017 mynukeviet. All rights reserved
 * @Createdate Tue, 07 Nov 2017 07:57:51 GMT
 */

if (!defined('NV_SYSTEM')) die('Stop!!!');

define('NV_IS_MOD_AFFILIATE', true);


require NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

if (!defined('NV_IS_USER') and ( $op != 'scan-user' AND $op != 'deleteuser' ) ){
    $redirect = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;
    Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=login&nv_redirect=' . nv_redirect_encrypt($redirect));
    die();
}
if( $op != 'scan-user' AND $op != 'deleteuser'){

    $sql = 'SELECT t1.code, t1.possitonid, t1.agencyid, t1.salary_day, t1.benefit, t1.subcatid, t1.mobile, t1.datatext, t1.permission, t2.userid, t2.username, t2.first_name, t2.last_name, t2.birthday, t2.email FROM ' . $db_config['prefix'] . '_' . $module_data . '_users AS t1 INNER JOIN ' . NV_USERS_GLOBALTABLE . ' AS t2 ON t1.userid=t2.userid WHERE t2.userid=' . $user_info['userid'] . ' ORDER BY t1.sort ASC';

    $user_data_affiliate = $db->query($sql)->fetch();
//chua phai trong he thong thi khong vao dc chuc nang nay
    if( !isset( $user_data_affiliate ))
    {
        Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=users');
        die();
    }

    $user_data_affiliate['fullname'] = nv_show_name_user( $user_data_affiliate['first_name'], $user_data_affiliate['last_name'], $user_data_affiliate['username']);
    $user_data_affiliate['datatext'] = unserialize( $user_data_affiliate['datatext']);
    $user_data_affiliate['agencytitle'] = ( $user_data_affiliate['agencyid']> 0 )? $array_agency[$user_data_affiliate['agencyid']]['title'] : $array_possiton[$user_data_affiliate['possitonid']]['title'];


    $page = 1;
    $per_page = $affiliate_config['per_page'];
    if (preg_match('/^page\-([0-9]+)$/', (isset($array_op[1]) ? $array_op[1] : ''), $m)) {
        $page = ( int )$m[1];
    }

    $array_mod_title[] = array(
        'catid' => 0,
        'title' => $module_info['funcs'][$op]['func_site_title'],
        'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op
    );

//chi lay nhan vien cong ty
    $sql = 'SELECT t1.code, t1.possitonid, t1.agencyid, t1.salary_day, t1.benefit, t2.userid, t2.username, t2.first_name, t2.last_name, t2.birthday, t2.email FROM ' . $db_config['prefix'] . '_' . $module_data . '_users AS t1 INNER JOIN ' . NV_USERS_GLOBALTABLE . ' AS t2 ON t1.userid=t2.userid WHERE t1.status=1 AND t1.possitonid > 0 ORDER BY t1.sort ASC';
    $list_userdata = $nv_Cache->db($sql, 'userid', $module_name);


    $sql = 'SELECT t1.code, t2.userid, t2.username, t2.first_name, t2.last_name, t2.birthday, t2.email FROM ' . $db_config['prefix'] . '_' . $module_data . '_users AS t1 INNER JOIN ' . NV_USERS_GLOBALTABLE . ' AS t2 ON t1.userid=t2.userid ORDER BY t1.sort ASC ';
    $list_teacher_by_code = $nv_Cache->db($sql, 'code', $module_name);
}


function nv_read_data_from_excel($file_name)
{
    global $db, $module_data, $list_teacher_by_code, $module_config,
           $module_name;

    require_once NV_ROOTDIR . '/includes/plugin/PHPExcel.php';
    $objPHPExcel = PHPExcel_IOFactory::load($file_name);

    $objWorksheet = $objPHPExcel->getActiveSheet();

    $highestRow = $objWorksheet->getHighestRow();
    $highestColumn = $objWorksheet->getHighestColumn();
    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

    $user_field = array();
    $user_field['manhanvien'] = array('col' => 0, 'title' => 'manhanvien');
    $user_field['tennhanvien'] = array('col' => 1, 'title' => 'tennhanvien');
    $user_field['ngayquet'] = array('col' => 3, 'title' => 'ngayquet');
    $user_field['giovao1'] = array('col' => 4, 'title' => 'giovao1');
    $user_field['giora1'] = array('col' => 5, 'title' => 'giora1');
    $user_field['giovao2'] = array('col' => 6, 'title' => 'giovao2');
    $user_field['giora2'] = array('col' => 7, 'title' => 'giora2');
    $user_field['giovao3'] = array('col' => 8, 'title' => 'giovao3');
    $user_field['giora3'] = array('col' => 9, 'title' => 'giora3');

    $array_data_read = array();
    // read data
    for ($row = 3; $row <= $highestRow; ++$row) {
        foreach ($user_field as $field => $column) {
            $col = $column['col'];
            $cellValue = $objWorksheet->getCellByColumnAndRow($col, $row);
            //$cellValue = trim( $cellValue );
            if (!empty(trim($cellValue))) {
                $InvDate = $cellValue->getValue();
                if (PHPExcel_Shared_Date::isDateTime($cellValue)) {
                    $tmp = (array )PHPExcel_Shared_Date::ExcelToPHPObject($InvDate);
                    if( $field == 'ngayquet' ){
                        $tmp = explode(' ', $tmp['date']);
                        $array_data_read[$row][$field] = $tmp[0]; // date('d/m/Y', PHPExcel_Shared_Date::ExcelToPHP($InvDate));
                    }else{
                        $tmp = explode(' ', $tmp['date']);
                        $array_data_read[$row][$field] = str_replace(':00.000000', '', $tmp[1]); // date('d/m/Y', PHPExcel_Shared_Date::ExcelToPHP($InvDate));
                    }

                } else {
                    $array_data_read[$row][$field] = $cellValue->getCalculatedValue();
                }
            }
        }
    }
    //xu ly de gop du lieu

    $array_data_by_user = array();
    foreach ($array_data_read as $row) {

        if (preg_match('/^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})$/', $row['ngayquet'], $m)) {
            $tmp_check = array();
            $row['ngayquet_int'] = mktime(0, 0, 0, $m[2], $m[3], $m[1]);

            if( !empty( $row['giovao1'] )){
                $tmp_check['giovao'][0] = $row['giovao1'];
            }
            if( !empty( $row['giora1'] )){
                $tmp_check['giora'][0] = $row['giora1'];
            }
            if( !empty( $row['giovao2'] )){
                $tmp_check['giovao'][1] = $row['giovao2'];
            }
            if( !empty( $row['giora2'] )){
                $tmp_check['giora'][1] = $row['giora2'];
            }

            if( !empty( $row['giovao3'] )){
                $tmp_check['giovao'][2] = $row['giovao3'];
            }
            if( !empty( $row['giora3'] )){
                $tmp_check['giora'][1] = $row['giora3'];
            }
            $row['numcheck'] = count($tmp_check['giovao']);//luot check van tay
            $row['infocheck'] = $tmp_check;
            $row['giovao'] = current( $tmp_check['giovao'] );
            $row['giora'] = end( $tmp_check['giora'] );

            $row['giovao_int'] = intval( str_replace(':', '', $row['giovao']) );
            $row['giora_int'] = intval( str_replace(':', '', $row['giora']));
            if( $row['giovao_int'] > 0 ){
                $array_data_by_user[$row['manhanvien']][$row['ngayquet_int']] = $row;
            }
        }
    }

    // print_r($array_data_by_user);die;
    $config_data = $module_config[$module_name];
    $config_data['dimuon'] = explode(',', $config_data['dimuon']);
    $config_data['vesom'] = explode(',', $config_data['vesom']);
    $config_data['nuaca'] = explode('-', $config_data['nuaca']);
    $config_data['motca'] = explode('-', $config_data['motca']);

    $data_no_insert = array();
    foreach ($array_data_by_user as $manhanvien => $by_user) {
        try {
            if (isset($list_teacher_by_code[$manhanvien])) {
                foreach ($by_user as $datetime => $row) {

                    $row['teacherid'] = $list_teacher_by_code[$row['manhanvien']]['userid'];
                    $row['ngaycong'] = 0;
                    $row['dimuon'] = 0; //cau hinh thoi gian di muon trong tung khoang de kt co muon hay k
                    $row['vesom'] = 0; //cau hinh thoi gian ve som trong tung khoang de kt co ve som hay k
                    $row['vesomcophep'] = $row['dimuoncophep'] = $row['status'] = $row['admincheck'] =
                        0;
                    $row['note'] = '';

                    //kiem tra xem co di muon ve som khong
                    $giovao = str_replace(':', '', $row['giovao']);
                    foreach ($config_data['dimuon'] as $dimuon) {
                        $dimuon = explode('-', $dimuon);
                        if ($dimuon[0] <= $giovao && $giovao <= $dimuon[1]) {
                            $row['dimuon'] = 1;
                        }
                    }
                    $giora = str_replace(':', '', $row['giora']);
                    foreach ($config_data['vesom'] as $vesom) {
                        $vesom = explode('-', $vesom);
                        if ($vesom[0] <= $giora && $giora <= $vesom[1]) {
                            $row['vesom'] = 1;
                        }
                    }

                    //tinh ngay cong di lam
                    $_tmp_giovao = $giovao = explode(':', $row['giovao']);
                    $_tmp_giora = $giora = explode(':', $row['giora']);
                    $giovao = ($giovao[0] * 3600) + ($giovao[1] * 60);
                    $giora = ($giora[0] * 3600) + ($giora[1] * 60);
                    $gio_lam_thuc_te = ($giora - $giovao) / 3600;
                    //neu lam tu sang va ra sau 15h thi - thoi gian nghi giua ca
                    if ($_tmp_giovao[0] <= 10 && $_tmp_giora[0] >= 15) {
                        $gio_lam_thuc_te = $gio_lam_thuc_te - $config_data['nghigiuaca'];
                    }

                    //truong hop gio lam lon hon cau hinh 1 ca
                    if ($config_data['motca'][0] < $gio_lam_thuc_te && $gio_lam_thuc_te >= $config_data['motca'][1]) {
                        $row['ngaycong'] = 1;
                        $gio_lam_them = $gio_lam_thuc_te - $config_data['motca'][1];
                        if ($config_data['nuaca'][0] < $gio_lam_them && $gio_lam_them <= $config_data['nuaca'][1]) {
                            $row['ngaycong'] = 1.5;
                        }
                    }
                    //truong hop gio lam trong khoang cau hinh 1 ca
                    elseif ($config_data['motca'][0] < $gio_lam_thuc_te && $gio_lam_thuc_te <= $config_data['motca'][1]) {
                        $row['ngaycong'] = 1;
                    } elseif ($config_data['nuaca'][0] < $gio_lam_thuc_te && ($gio_lam_thuc_te >= $config_data['nuaca'][1] ||
                            $gio_lam_thuc_te <= $config_data['nuaca'][1])) {
                        $row['ngaycong'] = 0.5;
                    }
                    try {
                        $numrow =  $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_chamcong WHERE datetime=' . $datetime . ' AND teacherid=' . $row['teacherid'] )->fetchColumn();
                        if( $numrow == 0 ){
                            //$db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_chamcong WHERE datetime=' . $datetime . ' AND teacherid=' . $row['teacherid']);
                            $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data .
                                '_chamcong (teacherid, datetime, checkin, checkout, infocheck, numcheck, ngaycong, dimuon, dimuoncophep, vesom, vesomcophep, note, status, admincheck) 
                            VALUES (:teacherid, :datetime, :checkin, :checkout, :infocheck, :numcheck, :ngaycong, :dimuon, :dimuoncophep, :vesom, :vesomcophep, :note, :status, :admincheck)');
                            $stmt->bindParam(':teacherid', $row['teacherid'], PDO::PARAM_INT);
                            $stmt->bindParam(':datetime', $datetime, PDO::PARAM_INT);
                            $stmt->bindParam(':checkin', $row['giovao'], PDO::PARAM_STR);
                            $stmt->bindParam(':checkout', $row['giora'], PDO::PARAM_STR);
                            $stmt->bindParam(':infocheck', serialize($row['infocheck']), PDO::PARAM_STR);
                            $stmt->bindParam(':numcheck', $row['numcheck'], PDO::PARAM_INT);
                            $stmt->bindParam(':ngaycong', $row['ngaycong'], PDO::PARAM_INT);
                            $stmt->bindParam(':dimuon', $row['dimuon'], PDO::PARAM_INT);
                            $stmt->bindParam(':dimuoncophep', $row['dimuoncophep'], PDO::PARAM_INT);
                            $stmt->bindParam(':vesom', $row['vesom'], PDO::PARAM_INT);
                            $stmt->bindParam(':vesomcophep', $row['vesomcophep'], PDO::PARAM_INT);
                            $stmt->bindParam(':note', $row['note'], PDO::PARAM_STR);
                            $stmt->bindParam(':status', $row['status'], PDO::PARAM_INT);
                            $stmt->bindParam(':admincheck', $row['admincheck'], PDO::PARAM_INT);
                            $exc = $stmt->execute();
                        }else{
                            $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data .
                                '_chamcong SET checkin=:checkin, checkout=:checkout, infocheck=:infocheck, numcheck=:numcheck, ngaycong=:ngaycong, dimuon=:dimuon, dimuoncophep=:dimuoncophep, vesom=:vesom, vesomcophep=:vesomcophep, status=:status, admincheck=:admincheck WHERE datetime=' . $datetime . ' AND teacherid=' . $row['teacherid']);
                            $stmt->bindParam(':checkin', $row['giovao'], PDO::PARAM_STR);
                            $stmt->bindParam(':checkout', $row['giora'], PDO::PARAM_STR);
                            $stmt->bindParam(':infocheck', serialize($row['infocheck']), PDO::PARAM_STR);
                            $stmt->bindParam(':numcheck', $row['numcheck'], PDO::PARAM_INT);
                            $stmt->bindParam(':ngaycong', $row['ngaycong'], PDO::PARAM_INT);
                            $stmt->bindParam(':dimuon', $row['dimuon'], PDO::PARAM_INT);
                            $stmt->bindParam(':dimuoncophep', $row['dimuoncophep'], PDO::PARAM_INT);
                            $stmt->bindParam(':vesom', $row['vesom'], PDO::PARAM_INT);
                            $stmt->bindParam(':vesomcophep', $row['vesomcophep'], PDO::PARAM_INT);
                            $stmt->bindParam(':status', $row['status'], PDO::PARAM_INT);
                            $stmt->bindParam(':admincheck', $row['admincheck'], PDO::PARAM_INT);
                            $exc = $stmt->execute();
                        }
                    }
                    catch (PDOException $e) {
                        //die($e->getMessage());
                        trigger_error($e->getMessage());
                        $error[] = $e->getMessage(); //Remove this line after checks finished
                    }
                }
            } else {
                $data_no_insert[] = $row;
            }

        }
        catch (PDOException $e) {
            $data_no_insert[] = $row;
            $error = $e->getMessage();
        }
    }
    return $data_no_insert;
}


//Tao tai khoan khach hang mua san pham
function nvCreateAgency( $array_data ){

    global $db, $user_data_affiliate, $user_info, $lang_module, $crypt, $global_config, $module_config, $db_config, $module_name, $array_agency;

    $active = 0;
    if( $user_data_affiliate['permission'] == 1 || $module_config[$module_name]['verify_user'] == 0 ){
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
        $array_mess_error['order_phone'] = $lang_module['edit_error_username_exist'];
    }
    $array_data['email'] = ( !empty( $array_data['email'] ))? $array_data['email'] : $array_data['mobile'] .'@gmail.com';
    // Thực hiện câu truy vấn để kiểm tra email đã tồn tại chưa.
    $stmt = $db->prepare('SELECT userid FROM ' . NV_USERS_GLOBALTABLE . ' WHERE email= :email');
    $stmt->bindParam(':email', $array_data['email'], PDO::PARAM_STR);
    $stmt->execute();
    $query_error_email = $stmt->fetchColumn();
    if ($query_error_email) {
        $array_mess_error['order_email'] = $lang_module['edit_error_email_exist'];
    }
    // Thực hiện câu truy vấn để kiểm tra email đã tồn tại trong nv4_users_reg  chưa.
    $stmt = $db->prepare('SELECT userid FROM ' . NV_USERS_GLOBALTABLE . '_reg WHERE email= :email');
    $stmt->bindParam(':email', $array_data['email'], PDO::PARAM_STR);
    $stmt->execute();
    $query_error_email_reg = $stmt->fetchColumn();
    if ($query_error_email_reg) {
        $array_mess_error['order_email'] = $lang_module['edit_error_email_exist'];
    }
    $stmt = $db->prepare('SELECT userid FROM ' . NV_USERS_GLOBALTABLE . '_openid WHERE email= :email');
    $stmt->bindParam(':email', $array_data['email'], PDO::PARAM_STR);
    $stmt->execute();
    $query_error_email_openid = $stmt->fetchColumn();
    if ($query_error_email_openid) {
        $array_mess_error['order_email'] = $lang_module['edit_error_email_exist'];
    }

    if( !empty( $array_mess_error )){
        return array('customer_id' => 0, 'mess_error' => $array_mess_error);
    }

    $_user['password'] = nvRandomString();
    $_user['username'] = $array_data['mobile'];
    $_user['email'] = $array_data['email'];
    $customer_name = explode(' ', $array_data['fullname'] );
    $total_str = count( $customer_name );
    $_user['first_name'] = $customer_name[$total_str-1];
    unset( $customer_name[$total_str-1] );
    $_user['last_name'] = implode(' ', $customer_name );
    try{
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
        :birthday,
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
        $data_insert['gender'] = $array_data['gender'];
        $data_insert['birthday'] = $array_data['birthday'];
        $data_insert['sig'] = '';
        $data_insert['question'] = $lang_module['question_info_phone'];
        $data_insert['answer'] = $_user['username'];

        $customer_id = $db->insert_id($sql, 'userid', $data_insert);

        if ($customer_id > 0 ) {

            //tao tk he thong QL Affiliate
            $precode = $module_config['affiliate']['precode'];
            $array_data['code'] = vsprintf($precode, $customer_id);

            $array_data['precode'] = $array_data['code'] . '%01s';

            $weight = $db->query('SELECT max(weight) FROM ' . $db_config['prefix'] . '_affiliate_users WHERE parentid=' . $user_info['userid'])->fetchColumn();
            $weight = intval($weight) + 1;
            $subcatid = '';

            $stmt = $db->prepare("INSERT INTO " . $db_config['prefix'] . "_affiliate_users (userid, parentid, precode, code, mobile, peopleid, salary_day, benefit, datatext, weight, sort, lev, possitonid, agencyid, istype, numsubcat, subcatid, listparentid, add_time, edit_time, status, provinceid, districtid, permission, haveorder, shareholder , jobid) VALUES
			(:userid, :parentid, :precode, :code, :mobile, :peopleid, :salary_day, :benefit, :datatext, :weight, '0', '0', :possitonid, :agencyid, :istype, '0', :subcatid, '', " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", " . $active . ", :provinceid, :districtid, 1,0,0,:jobid)");

            $stmt->bindValue(':salary_day', 0, PDO::PARAM_INT);
            $stmt->bindValue(':benefit', 0, PDO::PARAM_INT);
            $stmt->bindParam(':userid', $customer_id, PDO::PARAM_INT);
            $stmt->bindParam(':parentid', $user_info['userid'], PDO::PARAM_INT);
            $stmt->bindParam(':precode', $array_data['precode'], PDO::PARAM_STR);
            $stmt->bindParam(':code', $array_data['code'], PDO::PARAM_STR);
            $stmt->bindParam(':mobile', $_user['username'], PDO::PARAM_STR);
            $stmt->bindParam(':peopleid', $array_data['peopleid'], PDO::PARAM_STR);
            $stmt->bindParam(':weight', $weight, PDO::PARAM_INT);
            $stmt->bindValue(':possitonid', 0, PDO::PARAM_INT);
            $stmt->bindParam(':agencyid', $array_data['agencyid'], PDO::PARAM_INT);
            $stmt->bindParam(':istype', $array_data['istype'], PDO::PARAM_INT);
            $stmt->bindParam(':subcatid', $subcatid, PDO::PARAM_STR);
            $stmt->bindValue(':datatext', serialize( $array_data ), PDO::PARAM_STR);
            $stmt->bindParam(':provinceid', $array_data['provinceid'], PDO::PARAM_INT);
            $stmt->bindParam(':districtid', $array_data['districtid'], PDO::PARAM_INT);
            $stmt->bindParam(':jobid', $array_data['jobid'], PDO::PARAM_INT);

            $stmt->execute();

            if ($stmt->rowCount()) {
                nv_fix_users_order();
            }

            if( $active == 1 ){
                $_url = NV_MY_DOMAIN . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=login', true);
                if( $module_config[$module_name]['sms_register'] == 1 ){
                    $agency_title = $array_agency[$array_data['agencyid']]['title'];
                    $content = 'Chuc mung ban da tro thanh ' . $agency_title . ' CASH13. Hay dang nhap ' . $_url . ' voi tai khoan: ' . $array_data['mobile'] . ', mat khau: ' . $_user['password'] . ' va thay doi mat khau nhe. Luu y: Ban can len don hang trong vong 30 ngay de tranh bi xoa tai khoan!';
                    call_funtion_send_sms($content, $array_data['mobile'] );
                }

                // Gửi mail thông báo
                if( !empty($_user['email'])){
                    $full_name = nv_show_name_user($_user['first_name'], $_user['last_name'], $_user['username']);
                    $subject = $lang_module['adduser_register'];

                    $message = sprintf($lang_module['adduser_register_info1'], $full_name, $global_config['site_name'], $_url, $_user['username'], $_user['password']);
                    @nv_sendmail($global_config['site_email'], $_user['email'], $subject, $message);
                }
            }
        }
    }
    catch (PDOException $e) {
        die($e->getMessage());
    }
    return array('customer_id' => $customer_id, 'username' => $_user['username'], 'fullname' => $array_data['fullname'], 'agencyid' => $array_data['agencyid'], 'mobile' => $array_data['mobile'], 'provinceid' => $array_data['provinceid'], 'districtid' => $array_data['districtid'], 'password' => $_user['password'], 'mess_error' => array());
}

function nvRandomString() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyz1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}


/**
 * nv_check_email_change()
 *
 * @param mixed $email
 * @return
 */
function nv_check_email_change($email, $edit_userid)
{
    global $db, $lang_module, $user_info, $global_users_config;

    $error = nv_check_valid_email($email);
    if ($error != '') {
        return preg_replace('/\&(l|r)dquo\;/', '', strip_tags($error));
    }

    if (!empty($global_users_config['deny_email']) and preg_match("/" . $global_users_config['deny_email'] . "/i", $email)) {
        return sprintf($lang_module['email_deny_name'], $email);
    }

    list($left, $right) = explode('@', $email);
    $left = preg_replace('/[\.]+/', '', $left);
    $pattern = str_split($left);
    $pattern = implode('.?', $pattern);
    $pattern = '^' . $pattern . '@' . $right . '$';

    $stmt = $db->prepare('SELECT userid FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid!=' . $edit_userid . ' AND email RLIKE :pattern');
    $stmt->bindParam(':pattern', $pattern, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->fetchColumn()) {
        return sprintf($lang_module['email_registered_name'], $email);
    }

    $stmt = $db->prepare('SELECT userid FROM ' . NV_USERS_GLOBALTABLE . '_reg WHERE email RLIKE :pattern');
    $stmt->bindParam(':pattern', $pattern, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->fetchColumn()) {
        return sprintf($lang_module['email_registered_name'], $email);
    }

    $stmt = $db->prepare('SELECT userid FROM ' . NV_USERS_GLOBALTABLE . '_openid WHERE userid!=' . $edit_userid . ' AND email RLIKE :pattern');
    $stmt->bindParam(':pattern', $pattern, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->fetchColumn()) {
        return sprintf($lang_module['email_registered_name'], $email);
    }

    return '';
}


/**
 * GetGroupidInParentGroup($parentid, $subcatid)
 *
 * @param mixed $parentid
 * @param integer $subcatid
 * @param integer $checksub co lay tat ca cac con ben trong khong
 * @return
 */
function nvGetUseridInParent($parentid, $subcatid, $checksub = false, $ispossiton = true)
{
    global $array_list_id, $db_config, $module_data, $db;

    $array_list_id[$parentid] = $parentid;
    if( !empty( $subcatid )){

        $subcatid = explode(',', $subcatid );

        if (!empty($subcatid)) {
            foreach ($subcatid as $id) {
                $data_sub = $db->query('SELECT numsubcat, subcatid, possitonid FROM ' . $db_config['prefix'] . '_' . $module_data . '_users WHERE userid=' . $id )->fetch();

                if( $ispossiton and $data_sub['possitonid'] > 0 ){
                    $array_list_id[$id] = $id;
                }elseif( !$ispossiton ){
                    $array_list_id[$id] = $id;
                }
                if( $checksub == true){
                    if ($id > 0 and !empty( $data_sub ) and (( $ispossiton == true and $data_sub['possitonid'] > 0 ) or !$ispossiton) ) {
                        if ($data_sub['numsubcat'] == 0) {
                            $array_list_id[$id] = $id;
                        }else{
                            $array_list_id = nvGetUseridInParent($id, $data_sub['subcatid'], $checksub, $ispossiton );
                        }
                    }
                }
            }
        }
    }
    return array_unique($array_list_id);
}


function get_sub_nodes_shops( $parentid )
{
    global $db_config, $module_data, $db, $user_data_affiliate;

    $sql = 'SELECT t1.*, t2.numsubcat, t2.code, t2.possitonid, t2.lev, t2.agencyid, t2.provinceid, t2.status, t2.pendingdelete FROM ' . NV_USERS_GLOBALTABLE . ' AS t1 INNER JOIN ' . $db_config['prefix'] . '_' . $module_data . '_users AS t2 ON t1.userid=t2.userid WHERE t2.ishidden=0 AND t2.parentid=' . $parentid . ' ORDER BY t2.sort';

    if( $user_data_affiliate['permission'] == 0 ){
        $sql .= ' AND t2.status=1';
    }
    $res = $db->query( $sql );
    while( $tmp = $res->fetch() )
    {
        $array_data[] = $tmp;
    }
    return $array_data;
}

function nv_viewdirtree_toexcel( $userid, $config_data )
{
    global $nv_Request, $db_config, $module_data, $db, $array_agency, $array_possiton, $array_province, $array_statistic;


    $sql = 'SELECT t1.*, t2.code, t2.numsubcat, t2.subcatid, t2.possitonid, t2.lev, t2.agencyid, t2.provinceid, t2.haveorder, t2.mobile FROM ' . NV_USERS_GLOBALTABLE . ' AS t1 INNER JOIN ' . $db_config['prefix'] . '_' . $module_data . '_users AS t2 ON t1.userid=t2.userid WHERE t2.ishidden=0 AND t2.status=1 AND t1.userid = ' . intval( $userid ) . ' ORDER BY sort';
    $result = $db->query( $sql );
    $row = $result->fetch();

    if( !empty( $row )){
        if( !isset( $array_statistic['type'][$row['agencyid']] )){
            $array_statistic['type'][$row['agencyid']] = 1;
        }else{
            $array_statistic['type'][$row['agencyid']]++;
        }
        if( !isset( $array_statistic['level'][$row['lev']] )){
            $array_statistic['level'][$row['lev']] = 1;
        }else{
            $array_statistic['level'][$row['lev']]++;
        }
        if( $row['possitonid'] > 0 ){
            $row['title_show'] = isset( $array_possiton[$row['possitonid']] )? $array_possiton[$row['possitonid']]['title'] : 'N/A';
        }else{
            $row['title_show'] = isset( $array_agency[$row['agencyid']] )? $array_agency[$row['agencyid']]['title'] : 'N/A';
        }

        $row['postion'] = ( $row['agencyid']> 0 && isset( $array_agency[$row['agencyid']] ) )? $array_agency[$row['agencyid']]['title'] : $array_possiton[$row['possitonid']]['title'];
        $row['fullname'] = nv_show_name_user( $row['first_name'] , $row['last_name'] , $row['username']  );
        $row['province_name'] = isset( $array_province[$row['provinceid']] )? $array_province[$row['provinceid']]['title'] : '';

        if( $row['lev'] % 2 == 0 )
        {
            $row['class_icon'] = 'fa fa-user-o';
        }else{
            $row['class_icon'] = 'fa fa-user';
        }

        $nv_Request->set_Session($module_data . '_data_statistic', serialize($array_statistic) );
        $tmp_array = array();
        if( $row['numsubcat'] > 0 )
        {
            $subcatid = explode(',', $row['subcatid']);
            foreach( $subcatid as $userid_i )
            {
                $tmp_array[$userid_i] = nv_viewdirtree_toexcel( $userid_i, $config_data );
            }
            $row['data'] = $tmp_array;
        }
        return $row;
    }
}

function call_funtion_send_sms($content, $mobile){

    if( file_exists( NV_ROOTDIR . '/modules/sm/function.sendsms.php' )){
        require_once NV_ROOTDIR . '/modules/sm/function.sendsms.php';
        sendsms($content, $mobile );
    }

}


function nv_build_content_customer( $content, $customer)
{
    global $global_config, $lang_module;

    //khong phai gui mail thi loai bo cac the html
    $content = nv_unhtmlspecialchars($content);
    // Thay the bien noi dung
    $array_replace = array(
        '[FULLNAME]' => !empty($customer['fullname']) ? $customer['fullname'] : $lang_module['customers'],
        '[FIRSTNAME]' => $customer['first_name'],
        '[MOBILE]' => $customer['phone'],
        '[EMAIL]' => $customer['email'],
        '[TIME_DELETE]' => $customer['timedelete'],
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

function nv_affiliate_inactive_user( $userid ){
    global $db, $db_config, $module_data;

    $db->query('UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_users SET status=0 WHERE userid=' . $userid );
    $db->query('UPDATE ' . NV_USERS_GLOBALTABLE . ' SET active=0 WHERE userid=' . $userid );
    $db->query('UPDATE ' . $db_config['prefix'] . '_regsite SET status=0 WHERE userid=' . $userid );

    return true;
}

function nv_affiliate_delete_user( $userid, $parentid ){
    global $db, $db_config, $module_name, $module_data;

    $sql = 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_users WHERE userid = ' . $userid;
    if ($db->exec($sql)) {
        nv_insert_logs(NV_LANG_DATA, $module_name, 'System delete users', 'ID: ' . $userid, 0);
        if( $parentid > 0 ){
            //update lai thong tin tuyen tren
            $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_users WHERE userid=' . $parentid;
            $data_users_parent = $db->query($sql)->fetch();
            $subcatid = explode(',', $data_users_parent['subcatid'] );

            $key = array_search($userid, $subcatid);
            if (false !== $key) {
                unset($subcatid[$key]);
            }
            $data_users_parent['subcatid'] = implode(',', $subcatid );

            $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_users SET numsubcat=numsubcat-1, subcatid=' . $db->quote( $data_users_parent['subcatid'] ) . ' WHERE userid=' . $parentid;
            $db->query($sql);

            //xoa tai khoan tai bang user
            $db->query('DELETE FROM ' . NV_USERS_GLOBALTABLE . '  WHERE userid=' . $userid );
            //xoa domain dang ky
            $db->query('DELETE FROM ' . $db_config['prefix'] . '_regsite WHERE userid=' . $userid );
        }
        return true;
    }
    return false;
}


function nv_affiliate_get_path_dir()
{
    global $module_upload, $db;

    $currentpath = $module_upload . '/' . date( 'Ym' );

    if( file_exists( NV_UPLOADS_REAL_DIR . '/' . $currentpath ) )
    {
        $upload_real_dir_page = NV_UPLOADS_REAL_DIR . '/' . $currentpath;
    }
    else
    {
        $upload_real_dir_page = NV_UPLOADS_REAL_DIR . '/' . $module_upload;
        $e = explode( '/', $currentpath );
        if( !empty( $e ) )
        {
            $cp = '';
            foreach( $e as $p )
            {
                if( !empty( $p ) and !is_dir( NV_UPLOADS_REAL_DIR . '/' . $cp . $p ) )
                {
                    $mk = nv_mkdir( NV_UPLOADS_REAL_DIR . '/' . $cp, $p );
                    if( $mk[0] > 0 )
                    {
                        $upload_real_dir_page = $mk[2];
                        try
                        {
                            $db->query( "INSERT INTO " . NV_UPLOAD_GLOBALTABLE . "_dir (dirname, time) VALUES ('" . NV_UPLOADS_DIR . "/" . $cp . $p . "', 0)" );
                        }
                        catch ( PDOException $e )
                        {
                            trigger_error( $e->getMessage() );
                        }
                    }
                }
                elseif( !empty( $p ) )
                {
                    $upload_real_dir_page = NV_UPLOADS_REAL_DIR . '/' . $cp . $p;
                }
                $cp .= $p . '/';
            }
        }
        $upload_real_dir_page = str_replace( '\\', '/', $upload_real_dir_page );
    }

    $currentpath = str_replace( NV_ROOTDIR . '/', '', $upload_real_dir_page );
    return $currentpath;
}