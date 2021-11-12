<?php

if( $module_config[$module_name]['scan_user'] == 1 ){

    $db_slave->sqlreset()
        ->select('*')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_scanuser_config')
        ->where('status= 1')
        ->order('hourscan ASC');

    $result = $db_slave->query($db_slave->sql());
    $sql_scan = 0;
    $timedelete = 0;
    $data_content = array();
    while ( $row = $result->fetch()) {
        $data_content[] = $row;
        if( $sql_scan == 0 ){
            $sql_scan = $row['hourscan'];
        }
        if( $row['action'] == 1 ){
            $timedelete = $row['hourscan'];
        }
    }

    $timedelete = $timedelete * 3600;//thoi gian xoa user

    rsort($data_content);//sap xep lay gia tri lon nhat thuc thi trc
    $scan_time_alert = NV_CURRENTTIME - ($sql_scan * 3600);
    $inactive_or_delete = $module_config[$module_name]['inactive_or_delete'];

    $result = $db->query('SELECT t1.userid, t1.parentid, t1.mobile, t2.first_name, t2.last_name, t2.email, t1.add_time, t1.benefit FROM ' . $db_config['prefix'] . '_' . $module_data . '_users t1, 
    ' . NV_USERS_GLOBALTABLE . ' t2 WHERE t1.userid = t2.userid AND t1.haveorder=0 AND t1.shareholder=0 AND t1.status =1 AND t1.add_time<=' . $scan_time_alert );
    while ($row = $result->fetch()) {
        $content = '';
        $row['timedelete'] = date('H:i d/m/Y', $row['add_time'] + $timedelete );
        $row['fullname'] = nv_show_name_user( $row['first_name'], $row['last_name'] );
        $check_user = 0;
        foreach ( $data_content as $scan_user ){
             $time_checked = NV_CURRENTTIME - ($scan_user['hourscan'] * 3600);
            if(  $row['add_time'] <= $time_checked && $check_user == 0){
                $check_user = 1;
                //kiem tra xem da gui sms lan nao chua
                if( $row['benefit'] < $scan_user['hourscan'] ){
                    if( $scan_user['action'] == 1 ){
                        //xoa tai khoan
                        if( $inactive_or_delete == 1 ){
                            //xoa tai khoan
                            $result_sms = nv_affiliate_delete_user( $row['userid'], $row['parentid'] );
                        }
                        else{
                            //ngung kich hoat
                            $result_sms = nv_affiliate_inactive_user( $row['userid'] );
                        }
                        if( $result_sms ){
                            $content = nv_build_content_customer( $scan_user['content'], $row);
                        }
                    }else{
                        //gui sms canh bao
                        $content = nv_build_content_customer( $scan_user['content'], $row);
                    }
                    //$content = 'TAP DOAN CASH13 THONG BAO: Tai khoan cua ban chua len don hang tren he thong. Ke tu ngay 15/12/2018 tat cac cac tai khoan khong len don tren he thong trong vong 30 ngay ke tu thoi diem dang ky se bi xoa. Tran trong TB!';
                    if( !empty( $content ) && !empty( $row['mobile'] )){
                        //cap nhat co de lan sau khong gui sms
                        $db->query('UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_users SET benefit=' . $scan_user['hourscan'] . ' WHERE userid=' . $row['userid']);
                        //file_put_contents( NV_ROOTDIR . '/scan_user.txt', $row['mobile']  . "\t" . $content . "\n", FILE_APPEND);
                        call_funtion_send_sms($content, $row['mobile']);
                    }
                }
            }
        }
    }
    echo json_encode( array(1));
}else{
    file_put_contents( NV_ROOTDIR . '/scan_user-none.txt', date('d/m/Y H:i', NV_CURRENTTIME) . '\n', 1);
    echo json_encode( array(0));
}
exit('');
