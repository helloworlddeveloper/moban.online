<?php
/**

 * @Project NUKEVIET 4.x

 * @Author VINADES.,JSC (contact@vinades.vn)

 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved

 * @License GNU/GPL version 2 or any later version

 * @Createdate Tue, 18 Nov 2014 10:21:15 GMT

 */

if( !defined( 'NV_IS_MOD_SM' ) )
{
    die( 'Stop!!!' );
}
if( $nv_Request->isset_request( 'cust_id', 'get' ) and $nv_Request->isset_request( 'checkss', 'get' ) )
{
    $customer_id = $nv_Request->get_int( 'cust_id', 'get' );
    $checkss = $nv_Request->get_string( 'checkss', 'get' );
    if( $customer_id > 0 and $checkss == md5( $customer_id . NV_CACHE_PREFIX . $client_info['session_id'] ) )
    {
        $sql = 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid = ' . $customer_id;

        $array_data = $db->query($sql)->fetch();
        //print_r($array_data);die();
        if ($array_data) {
            //tao tk he thong QL Affiliate
            $precode = !empty($module_config['affiliate']['precode']) ? $module_config['affiliate']['precode'] : 'MK%01s';
            $array_data['code'] = vsprintf($precode, $customer_id);

            $array_data['precode'] = $array_data['code'] . '%01s';

            $weight = $db->query('SELECT max(weight) FROM ' . $db_config['prefix'] . '_affiliate_users WHERE parentid=' . $user_info['userid'])->fetchColumn();
            $weight = intval($weight) + 1;
            $array_data['weight'] = intval($weight) + 1;
            $array_data['possitonid'] = 0;
            $array_data['agencyid'] = 1;
            $array_data['istype'] = 0;
            $array_data['subcatid'] = '';
            $array_data['jobid'] = 0;
            $active = 1;

            try {
                $stmt = $db->prepare("INSERT INTO " . $db_config['prefix'] . "_affiliate_users (userid, parentid, precode, code, mobile, peopleid, salary_day, benefit, datatext, weight, sort, lev, possitonid, agencyid, istype, numsubcat, subcatid, listparentid, add_time, edit_time, status, provinceid, districtid, wardid, address, permission, haveorder, shareholder , jobid) VALUES
                        (:userid, :parentid, :precode, :code, :mobile, :peopleid, :salary_day, :benefit, :datatext, :weight, '0', '0', :possitonid, :agencyid, :istype, '0', :subcatid, '', " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", " . $active . ", :provinceid, :districtid, :wardid, :address, 1,0,0,:jobid)");

                $stmt->bindValue(':salary_day', 0, PDO::PARAM_INT);
                $stmt->bindValue(':benefit', 0, PDO::PARAM_INT);
                $stmt->bindParam(':userid', $array_data['userid'], PDO::PARAM_INT);
                $stmt->bindParam(':parentid', $user_info['userid'], PDO::PARAM_INT);
                $stmt->bindParam(':precode', $array_data['precode'], PDO::PARAM_STR);
                $stmt->bindParam(':code', $array_data['code'], PDO::PARAM_STR);
                $stmt->bindParam(':mobile', $array_data['phone'], PDO::PARAM_STR);
                $stmt->bindParam(':peopleid', $array_data['phone'], PDO::PARAM_STR);
                $stmt->bindParam(':weight', $array_data['weight'], PDO::PARAM_INT);
                $stmt->bindValue(':possitonid', $array_data['possitonid'], PDO::PARAM_INT);
                $stmt->bindParam(':agencyid', $array_data['agencyid'], PDO::PARAM_INT);
                $stmt->bindParam(':istype', $array_data['istype'], PDO::PARAM_INT);
                $stmt->bindParam(':subcatid', $array_data['subcatid'], PDO::PARAM_STR);
                $stmt->bindValue(':datatext', serialize( $array_data ), PDO::PARAM_STR);
                $stmt->bindParam(':provinceid', $array_data['provinceid'], PDO::PARAM_INT);
                $stmt->bindParam(':districtid', $array_data['districtid'], PDO::PARAM_INT);
                $stmt->bindParam(':wardid', $array_data['wardid'], PDO::PARAM_INT);
                $stmt->bindParam(':address', $array_data['address'], PDO::PARAM_STR);
                $stmt->bindParam(':jobid', $array_data['jobid'], PDO::PARAM_INT);
                echo 'a';
                $stmt->execute();

                //if ($stmt->rowCount()) {
                //    nv_fix_users_order();
                //}

                /*
                if( $active == 1 ){
                    $_url = NV_MY_DOMAIN . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=login', true);
                    if( $module_config[$module_name]['sms_register'] == 1 ){
                        $agency_title = $array_agency[$array_data['agencyid']]['title'];
                        //$content = 'Chuc mung ban da tro thanh ' . $agency_title . ' Minh Khang. Hay dang nhap ' . $_url . ' voi tai khoan: ' . $array_data['mobile'] . ', mat khau: ' . $_user['password'] . ' va thay doi mat khau nhe. Luu y: Ban can len don hang trong vong 30 ngay de tranh bi xoa tai khoan!';
                        $content = 'Chuc mung ban da tham gia he thong NPP/Dai ly cua Minh Khang.';
                        call_funtion_send_sms($content, $array_data['mobile'] );
                    }

                    // Gửi mail thông báo
                    if( !empty($array_data['email'])){
                        $full_name = nv_show_name_user($array_data['first_name'], $array_data['last_name'], $array_data['username']);
                        $subject = $lang_module['adduser_register'];

                        $message = 'Chuc mung ban da tham gia he thong NPP/Dai ly cua Minh Khang.';
                        @nv_sendmail($global_config['site_email'], $array_data['email'], $subject, $message);
                    }
                }
*/
                //cập nhật trạng thái customer
                $db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_customer SET isagency = 1  WHERE refer_userid = ' . $customer_id );
            } catch (PDOException $ex) {
                echo 'faild';
                echo $ex->getMessage();
                die();
            }
        }
        Header( 'Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
        die();
    }
}

$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

$per_page = 20;
$page = $nv_Request->get_int( 'page', 'post,get', 1 );
$q = $nv_Request->get_title( 'q', 'post,get' );
$sql_where = '';
if( ! empty( $q ) )
{
    $sql_where = "(t1.code LIKE '%" . $q . "%' OR t1.fullname LIKE '%" . $q . "%' OR t1.address LIKE '%" . $q . "%' OR t1.phone LIKE '%" . $q . "%' OR t1.email LIKE '%" . $q . "%')";
    $base_url .= '&q=' . $q;
}

$sql = "SELECT count(*) 
        FROM nv4_vi_sm_customer t1
            INNER JOIN nv4_users t2 ON t1.refer_userid = t2.userid";
$sql .= " WHERE t2.presentcode = '" . $user_info['username'] . "' ". $sql_where;

$num_items = $db->query($sql)->fetchColumn();

$sql = "SELECT t1.*,t2.purchase_points
        FROM nv4_vi_sm_customer t1
            INNER JOIN nv4_users t2 ON t1.refer_userid = t2.userid";
$sql .= " WHERE t2.presentcode = '" . $user_info['username'] . "' ". $sql_where . " LIMIT " . (($page - 1) * $per_page) . ", " . $per_page;
//echo $sql; die();
$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'UPLOAD_CURRENT', NV_UPLOADS_DIR . '/shared' );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'Q', $q );
$xtpl->assign( 'TOTAL', $num_items );

$xtpl->assign( 'NV_GENERATE_PAGE', nv_generate_page( $base_url, $num_items, $per_page, $page ) );

$result = $db->query($sql);
$number = 0;
while( $view = $result->fetch() )
{
    $view['number'] = ++$number;
    $view['note'] = $view['isagency'] == 0 ? 'Khách lẻ' : 'Đã tham gia hệ thống NPP/ĐL Minh Khang';
    $view['change_agency'] = $view['isagency'] == 0 ? 'Duyệt ĐL' : '';
    $view['link_gift'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=customer_gifts&amp;id=' . $view['customer_id'];
    $view['link_edit'] = $view['isagency'] == 1 ? "" : NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=customer&amp;cust_id=' . $view['refer_userid'] . '&amp;checkss=' . md5( $view['refer_userid'] . NV_CACHE_PREFIX . $client_info['session_id'] );
    $view['edit_time'] = date( 'd/m/Y H:i', $view['edit_time'] );
    $view['status'] = $lang_module['active_' . $view['status']];
    $view['custype'] = $lang_module['custype_' . $view['custype']];
    $xtpl->assign( 'VIEW', $view );
    if ($view['isagency'] == 0)
        $xtpl->parse( 'main.loop.approve_agency' );
    $xtpl->parse( 'main.loop' );

}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );
$page_title = "Khách hàng của tôi";

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';