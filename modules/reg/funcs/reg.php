<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 24 Dec 2014 06:56:00 GMT
 */

if( ! defined( 'NV_IS_MOD_REG' ) )
    die( 'Stop!!!' );

$session_reg = $nv_Request->get_int($module_data . '_reg_ok', 'session', 0);
if( $nv_Request->isset_request( 'reg', 'post' ) && $session_reg != 1 )
{
    //lay cookie lan truy cap cuoi cung cua user vao cac trang dai ly
    $row['mobilerefer'] = $_COOKIE['daily_client_access'];
    $row['userid'] = 0;
    $row['siterefer'] = 0;
    if( $row['mobilerefer'] != '' ){
        $sql = 'SELECT id, useridm email FROM ' . $db_config['prefix'] . "_regsite WHERE mobile=" . $row['mobilerefer'];
        list( $row['siterefer'], $row['userid'], $row['email_daily'] ) = $db->query($sql)->fetch(3);
    }else{
        $row['mobilerefer'] = '';
    }

    $row['fullname'] = $nv_Request->get_title( 'fullname', 'post', '' );
    $row['email'] = $nv_Request->get_title( 'email', 'post', '' );
    $row['phone'] = $nv_Request->get_title( 'phone', 'post', '' );
    $row['address'] = $nv_Request->get_title( 'address', 'post', 'M' );

    $sql = 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_reg WHERE reg_phone=' . $db->quote( $row['phone'] ) ;
    $exits_data = $db->query($sql)->fetchColumn();
    if( $exits_data > 0 ){
        $error = $lang_module['error_exit_mobile'];
    }
    elseif( empty( $row['fullname'] ) )
    {
        $error = $lang_module['error_required_reg_full_name'];
    }
    elseif( empty( $row['phone'] ) )
    {
        $error = $lang_module['error_required_reg_phone'];
    }
    elseif( ! empty( $row['email'] ) and ( $error_email = nv_check_valid_email( $row['email'] ) ) != '' )
    {
        $error = $error_email;
    }
    if( empty( $error ) )
    {
        try
        {

            $row['add_time'] = NV_CURRENTTIME;
            $row['process_time'] = 0;
            $row['status'] = 0;

            $stmt = $db->prepare( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_reg (userid, reg_full_name, reg_email, reg_phone, reg_address, mobilerefer, siterefer, note, add_time, process_time, from_ip, status) VALUES (:userid, :reg_full_name, :reg_email, :reg_phone, :reg_address, :mobilerefer, :siterefer, :note, :add_time, :process_time, :from_ip, :status)' );

            $stmt->bindParam( ':userid', $row['userid'], PDO::PARAM_INT );
            $stmt->bindParam( ':add_time', $row['add_time'], PDO::PARAM_INT );
            $stmt->bindParam( ':process_time', $row['process_time'], PDO::PARAM_INT );
            $stmt->bindParam( ':status', $row['status'], PDO::PARAM_INT );
            $stmt->bindParam( ':reg_full_name', $row['fullname'], PDO::PARAM_STR );
            $stmt->bindParam( ':reg_email', $row['email'], PDO::PARAM_STR );
            $stmt->bindParam( ':reg_phone', $row['phone'], PDO::PARAM_STR );
            $stmt->bindParam( ':reg_address', $row['address'], PDO::PARAM_STR );
            $stmt->bindParam( ':mobilerefer', $row['mobilerefer'], PDO::PARAM_STR );
            $stmt->bindParam( ':siterefer', $row['siterefer'], PDO::PARAM_INT );
            $stmt->bindParam( ':note', $row['note'], PDO::PARAM_STR, strlen( $row['note'] ) );
            $stmt->bindParam( ':from_ip', $client_info['ip'], PDO::PARAM_INT );
            $exc = $stmt->execute();
            if( $exc )
            {
                require_once NV_ROOTDIR . '/modules/mkt/data-user.php';
                $customerid = check_data_info( $row['fullname'], $row['phone'] );
                if( $customerid == 0 ){
                    $customerid = save_data_user($row['userid'], 0, $row['fullname'], $row['address'], $row['email'], $row['phone'], '', 1 );
                }
                if( $customerid > 0 ){
                    if( empty( $row['mobilerefer'] )){
                        $note = sprintf( $lang_module['reg_note'], 'cty' );
                    }
                    else{
                        $note = sprintf( $lang_module['reg_note'], $row['mobilerefer'] );
                    }
                    save_eventcontent( $customerid, '', '', $note );
                }
                $nv_Request->set_Session($module_data . '_reg_ok', '1');
                //gui tin nhan
                if( $module_config[$module_name]['sms_on'] == 1 ){
                    $apikey = $module_config[$module_name]['apikey'];
                    $secretkey = $module_config[$module_name]['secretkey'];
                    $sms_type = $module_config[$module_name]['sms_type'];

                    $content = 'Don DK NPP: ' . $row['fullname'] . ' - ' . $row['phone'] . ' - ' . $row['email'] . ' - ' . $row['address'] . ' tai ' . NV_SERVER_NAME;
                    $url = '';
                    if( $sms_type == 2 ){
                        $url = '&Brandname=' . $module_config[$module_name]['brandname'];
                    }
                    //gui cho nguoi gioi thieu
                    if( !empty( $row['mobilerefer'] )){
                        $content = urlencode($content);
                        $data = 'http://rest.esms.vn/MainService.svc/json/SendMultipleMessage_V4_get?Phone=' . $row['mobilerefer'] . '&ApiKey=' . $apikey . '&SecretKey=' . $secretkey . '&Content=' . $content . '&SmsType=' . $sms_type . $url;
                        $curl = curl_init($data);
                        curl_setopt($curl, CURLOPT_FAILONERROR, true);
                        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        $result = curl_exec($curl);

                        $obj = json_decode($result, true);
                        if ($obj['CodeResult'] == 100) {
                            //gui thanh cong
                        } else {
                            //gui loi
                        }
                    }


                    if( !empty( $module_config[$module_name]['list_phone'] ) && empty( $row['mobilerefer'] )){
                        $list_phone = explode(',', $module_config[$module_name]['list_phone'] );
                        //gui cho tong cong ty
                        $content = 'Don DK NPP: ' . $row['fullname'] . ' - ' . $row['phone'] . ' - ' . $row['email'] . ' - ' . $row['address'] . ' tai ' . NV_SERVER_NAME;

                        $content = urlencode($content);
                        foreach( $list_phone as $mobile ){
                            $data = 'http://rest.esms.vn/MainService.svc/json/SendMultipleMessage_V4_get?Phone=' . $mobile . '&ApiKey=' . $apikey . '&SecretKey=' . $secretkey . '&Content=' . $content . '&SmsType=' . $sms_type . $url;
                            $curl = curl_init($data);
                            curl_setopt($curl, CURLOPT_FAILONERROR, true);
                            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                            $result = curl_exec($curl);

                            $obj = json_decode($result, true);
                            if ($obj['CodeResult'] == 100) {
                                //gui thanh cong
                            } else {
                                //gui loi
                            }
                        }
                    }
                }
				
				$xtpl_mail = new XTemplate( 'template_sendmail.tpl', NV_ROOTDIR . '/modules/' . $module_file );
				$xtpl_mail->assign( 'NV_MY_DOMAIN', NV_MY_DOMAIN );
				$xtpl_mail->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
				$xtpl_mail->assign( 'MODULE_FILE', $module_file );
				$xtpl_mail->assign( 'LANG', $lang_module );
				$xtpl_mail->assign( 'ROW', $row );
				if( !empty( $row['mobilerefer'] )){
					$xtpl_mail->parse( 'main.mobilerefer' );
				}
				$xtpl_mail->parse( 'main' );
				$message = $xtpl_mail->text( 'main' );
				
				if( !empty( $row['email_daily'] )){
					 @nv_sendmail( $global_config['site_email'], $row['email_daily'], 'Có đơn đăng ký đại lý mới với tên: ' . $row['fullname'] . ' tại ' . NV_MY_DOMAIN, $message );  
				}
                if( !empty( $module_config[$module_name]['email_notify'] )){
                    $email_notify = explode(',', $module_config[$module_name]['email_notify'] );
                    @nv_sendmail( $global_config['site_email'], $email_notify, 'Có đơn đăng ký đại lý mới với tên: ' . $row['fullname'] . ' tại ' . NV_MY_DOMAIN, $message );  
                }
                
                die( 'OK' );
            }
        }
        catch ( PDOException $e )
        {
            trigger_error( $e->getMessage() );
            die( $e->getMessage() ); //Remove this line after checks finished
        }
    }else{
        exit($error);
    }
}else{
    exit($lang_module['register_exits']);
}
