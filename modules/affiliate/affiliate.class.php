<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Mr.Thang (kid.apt@gmail.com)
 * @Copyright (C) 2016 Mr.Thang. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/12/2016 06:38:53 GMT
 */

/*
 * Ham lay ID NV phu trach khach hang DL, CTV
 */
function nv_get_customer_refer( $customerid ){
    global $db;
    $sql = 'SELECT parentid FROM ' . NV_TABLE_AFFILIATE . '_users WHERE userid=' . $customerid;
    list( $parentid_ref ) = $db->query($sql)->fetch(3);

    return intval( $parentid_ref );
}

/*
* $userid = tk thanh vien
* $money = so tien giao dich
* $content_tracstion = noi dung giao dich
* $mod_name = module phat sinh giao dich
* $product_id = id cua don hang, san pham phat sinh giao dich..
 * $isretail = true la ban le, false la ban si
* */
class moneyService
{
    private $userid, $money, $content_tracstion, $mod_name, $product_id;
    public function __construct($userid, $money, $content_tracstion, $mod_name, $product_id, $isretail = true)
    {
        $this->userid = $userid;
        $this->money = $money;
        $this->content_tracstion = $content_tracstion;
        $this->mod_name = $mod_name;
        $this->product_id = $product_id;
        $this->isretail = $isretail;
    }

    //kiem tra xem du tien khong
    function checkEnoughMoney()
    {
        global $db_config, $db;
        $sql = "SELECT * FROM " . $db_config['prefix'] . "_affiliate_money WHERE userid=" . $this->userid;
        $result = $db->query($sql);
        $data_money = $result->fetch();
        if ($data_money['money'] >= $this->money)
        {
            return true;
        }else{
            return false;
        }
    }
    private function getParentNode()
    {
        global $db_config, $db;
        $sql = "SELECT t1.userid, t1.parentid, t1.lev, t1.possitonid, t2.username FROM " . $db_config['prefix'] . "_affiliate_users AS t1 INNER JOIN " . NV_USERS_GLOBALTABLE . " AS t2 ON t1.userid=t2.userid WHERE t1.userid=" . $this->userid;
        $result = $db->query($sql);
        $data_userid = $result->fetch();
        return $data_userid;
    }
    /*
    * $actiontype = loai giao dich, 1 = cong tien, -1 tru tien
    * */
    function callActionMoney($actiontype)
    {
        global $db_config, $db, $module_config, $lang_module;

        //cong tien vao tai khoan
        if ($actiontype == 1)
        {
            if( $this->isretail){
                //lay phan tram hoa hong ban le
                $percent = $module_config['affiliate']['config_fercent_return'];
            }else{
                //lay phan tram hoa hong DL,CTV nhap hang
                $percent = $module_config['affiliate']['config_fercent_return_agency'];
            }


            $money_insert = ($percent * $this->money) / 100;
            $return = $this->actionMoney( $actiontype, $money_insert);
            $this->affiliate_save_statistic_customer( $this->userid, $this->money );//tinh doanh thu KPI cho nhan vien phu trach DL
            if( $return ){
                $data_ParentNode = $this->getParentNode();

                while( $data_ParentNode['parentid'] > 0 ){

                    if( $data_ParentNode['possitonid'] > 0){

                        $array_possition = $this->get_possition();
                        $array_possition = $array_possition[$data_ParentNode['possitonid']];
                        if( $array_possition['percent_responsibility'] > 0 ){
                            $this->userid = $data_ParentNode['userid'];
                            $money_insert = ($this->money * floatval( $array_possition['percent_responsibility'] )) / 100;

                            require_once NV_ROOTDIR . '/modules/affiliate/language/' . NV_LANG_INTERFACE . '.php';
                            $this->content_tracstion = sprintf( $lang_module['transaction_note'], $array_possition['percent_responsibility'] . '%', $data_ParentNode['username'], number_format( $this->money, 0, '.', ','));
                            $this->affiliate_save_statistic_customer( $this->userid, $this->money );//tinh doanh thu KPI cho nhan vien quan ly
                            $this->actionMoney( $actiontype, $money_insert);
                        }
                    }
                    $this->userid = $data_ParentNode['parentid'];//set lai ID cha
                    $data_ParentNode = $this->getParentNode();
                }
                if( $data_ParentNode['possitonid'] > 0){
                    $array_possition = $this->get_possition();
                    $array_possition = $array_possition[$data_ParentNode['possitonid']];

                    $this->userid = $data_ParentNode['userid'];
                    $money_insert = ($this->money * floatval( $array_possition['percent_responsibility'] )) / 100;
                    $this->affiliate_save_statistic_customer( $this->userid, $this->money );//tinh doanh thu KPI cho nhan vien quan ly
                    require_once NV_ROOTDIR . '/modules/affiliate/language/' . NV_LANG_INTERFACE . '.php';
                    $this->content_tracstion = sprintf( $lang_module['transaction_note'], $array_possition['percent_responsibility'] . '%', $data_ParentNode['username'], number_format( $this->money, 0, '.', ','));
                    $return = $this->actionMoney( $actiontype, $money_insert);
                   // print_r($array_possition);die;
                }

            }

        } else
        {

            //tru tien trong tai khoan
            $this->actionMoney( $actiontype, $this->money);

        }
        return false;
    }
    function sendmail_notification($subject, $message){

        global $db, $module_config, $global_config;
        //gui mail thong bao khi co don hang do minh gioi thieu dc thanh toan
        if( $module_config['affiliate']['mail_notification'] == 1){
            $sql = "SELECT email FROM " . NV_USERS_GLOBALTABLE . " WHERE userid=" . $this->userid;
            $result = $db->query($sql);
            $data_send_mail = $result->fetch();
            $from = array($global_config['site_name'], $global_config['site_email']);
            $to_mail = $data_send_mail['email'];
            nv_sendmail($from, $to_mail, $subject, $message);
            return true;
        }
        return false;
    }
    /*
    $actiontype = loai giao dich, 1 = cong tien, -1 tru tien
    * chi goi noi bo de cong tien cho cac tai khoan
    * */
    private function actionMoney( $actiontype, $money_action )
    {
        global $db_config, $db, $module_config;

        $sql = "SELECT * FROM " . $db_config['prefix'] . "_affiliate_money WHERE userid=" . $this->userid;
        $result = $db->query($sql);
        $exit_wallet = $result->rowCount();

        //cong tien vao tai khoan
        if ($actiontype == 1)
        {
            //neu da ton tai vi tien
            if ($exit_wallet == 1)
            {
                $sql = "UPDATE " . $db_config['prefix'] . "_affiliate_money SET money_in= money_in+" . doubleval($money_action) . ", money = money+" . doubleval($money_action) . " WHERE userid= " . $this->userid;
                $res = $db->query($sql);
            } else
            {
                $sql = "INSERT INTO " . $db_config['prefix'] . "_affiliate_money (userid, money_in, money_out, money, status) 
                    VALUES(" . $this->userid . "," . doubleval($money_action) . ",0," . doubleval($money_action) . ",1)";
                $res = $db->query($sql);
            }

        } else
        {
            if ($exit_wallet == 1)
            {
                $data_money = $result->fetch();
                //khong du tien
                if ($data_money['money'] < $this->money)
                {
                    return false;
                }
                //tru tien trong tai khoan
                $sql = "UPDATE " . $db_config['prefix'] . "_affiliate_money SET money_out= money_out+" . doubleval($money_action) . ", money = money-" . doubleval($money_action) . " WHERE userid= " . $this->userid;
                $res = $db->query($sql);
            } else
            {
                return false;
            }
        }
        if ($res)
        {
            $this->save_transaction($actiontype, $money_action);
            return true;
        }
        return false;
        
    }
    private function save_transaction($actiontype, $money_action)
    {
        global $db, $db_config;

        try{
            $db->query("INSERT INTO " . $db_config['prefix'] . "_affiliate_transaction VALUES (NULL," . NV_CURRENTTIME . "," . $actiontype . "," . doubleval($money_action) . "," . $this->userid . "," . intval($this->product_id) . "," . $db->quote($this->mod_name) . ",''," . $db->quote($this->content_tracstion) . ",1);");
        }
        catch( PDOException $Exception ) {
            die($Exception->getMessage( )  . '  ' .  (int)$Exception->getCode( ) );
        }
        return true;
    }
    private function get_possition(){
        global $db_config, $nv_Cache;
        $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_affiliate_possiton WHERE status=1 ORDER BY weight ASC ';
        return $nv_Cache->db($sql, 'id', 'affiliate');
    }
    //Ham cong doanh thu theo thang cho thanh vien
    private function affiliate_save_statistic_customer( $customerid, $total_price, $monthyear = '' ){

        global $db;
        $monthyear = ($monthyear == '')? date('mY', NV_CURRENTTIME ) : $monthyear;
        $monthyear = intval( $monthyear );
        if ( $customerid > 0 and $total_price > 0 ) {
            $sql = 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_sm_statistic WHERE customer_id=' . $customerid . ' AND monthyear=' .$monthyear;
            $check_exits = $db->query( $sql )->fetchColumn();
            if( $check_exits == 0 ){
                $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_sm_statistic( customer_id, monthyear, total_price ) 
            VALUES ( ' . $customerid . ', ' . $monthyear . ', ' . $total_price . ')';
                $db->query($sql);
            }
            else{
                $db->query('UPDATE ' . NV_PREFIXLANG . '_sm_statistic SET total_price = total_price+' . $total_price . ' WHERE customer_id =' . $customerid . ' AND monthyear=' . $monthyear );
            }

        }
    }
}
