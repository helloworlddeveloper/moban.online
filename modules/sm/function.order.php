<?php

define('NV_TABLE_AFFILIATE', $db_config['prefix'] . '_affiliate');

//ham tao don hang tu module shop
function book_order( $userid_refer, $order_name, $order_email, $order_phone, $order_address, $order_note )
{
    global $client_info, $db_config, $db, $module_data, $lang_module, $global_config, $module_info, $module_config, $mobilerefer;

    $userid_refer = intval( $userid_refer );
    //khoi tao tai khoan khach hang neu chua ton tai
    $customer_id = createCustomer($order_name, $order_email, $order_phone, $order_address, $userid_refer);

    $sql = "SELECT precode, possitonid, mobile FROM " . NV_TABLE_AFFILIATE . "_users WHERE userid=" . $userid_refer;
    $result = $db->query($sql);
    list($precode, $possitonid, $mobilerefer) = $result->fetch(3);
    if ($precode == '') {
        $precode = 'S%01s';
    } else {
        $precode = str_replace('%', '-S%', $precode);
    }

    $order_shipcod = 1;
    $depotid = 0;
    $status = -1;
    $showadmin = 0;//don hang hien trong admin hay khong
    if ($possitonid > 0) {
        $showadmin = 1;
    }
    $result = $db->query("SHOW TABLE STATUS WHERE Name='" . NV_PREFIXLANG . "_sm_orders'");
    $item = $result->fetch();
    $result->closeCursor();

    $order_code = vsprintf($precode, $item['auto_increment']);
    $order_total = 0;

    $sql = "INSERT INTO " . NV_PREFIXLANG . "_sm_orders (
                customer_id, order_code, order_name, order_email, order_phone, 
                order_address, order_note, user_id, admin_id, order_total, order_time, edit_time, postip, 
                saleoff, feeship, price_payment, shipcode, showadmin, chossentype, ordertype, orderid_refer, amount_refunded, depotid, status
            ) VALUES (
                " . $customer_id . ", :order_code, :order_name, :order_email, :order_phone, :order_address, :order_note,
                " . $userid_refer . ", 0," . doubleval($order_total) . ",
                " . NV_CURRENTTIME . ", 0, :ip, 0, 0, " . $order_total . ", " . $order_shipcod . ", " . $showadmin . ", 3, 1, 0, " . doubleval($order_total) . ", " . intval($depotid) . ", " . $status . "
            )";

    $data_insert = array();
    $data_insert['order_code'] = $order_code;
    $data_insert['order_name'] = $order_name;
    $data_insert['order_email'] = $order_email;
    $data_insert['order_phone'] = $order_phone;
    $data_insert['order_address'] = $order_address;
    $data_insert['order_note'] = $order_note;
    $data_insert['ip'] = $client_info['ip'];

    $order_id = $db->insert_id($sql, 'order_id', $data_insert);

    if ($order_id > 0) {

        $j = 0;
        $list_productid = $array_title = array();
        //Them chi tiet don hang
        foreach ($_SESSION['shops_cart'] as $pro_id => $info) {
            $j++;
            $proid = explode('_', $pro_id);
            $proid = $proid[0];

            $sql = "SELECT id, price_retail, title FROM " . NV_PREFIXLANG . "_sm_product WHERE productshopid=" . $proid;
            $result = $db->query($sql);
            list($productid, $price, $title) = $result->fetch(3);
            $list_productid[$proid] = array('num' => $info['num'], 'price' => $price );

            $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_sm_orders_id( order_id, proid, num, type_return, numreturn, price ) 
            VALUES ( :order_id, :proid, :num, 0, 0, :price )';
            $data_insert = array();
            $data_insert['order_id'] = $order_id;
            $data_insert['proid'] = $productid;
            $data_insert['num'] = $info['num'];
            $data_insert['price'] = $price;

            $db->insert_id($sql, 'id', $data_insert);
            $order_total += $info['num'] * $price;
            $array_title[] = $title . ' SL: ' . $info['num'];
        }

        //tao tai khoan tai module quan ly KH tap trung
        require_once NV_ROOTDIR . '/modules/mkt/data-user.php';
        $customerid = check_data_info( $order_name, $order_phone );
        if( $customerid == 0 ){
            $customerid = save_data_user($userid_refer, 0, $order_name, $order_address, $order_email, $order_phone, '', 2 );
        }
        if( $customerid > 0 ){
            if( $mobilerefer == ''){
                $note = 'Đặt hàng sản phẩm ' . implode(', ', $array_title );
            }else{
                $note = 'Đặt hàng sản phẩm ' . implode(', ', $array_title ) . ' từ NPP ' . $mobilerefer;
            }
            save_eventcontent( $customerid, '', '', $note );
        }

        //cap nhat gia tri tong don hang
        $db->query('UPDATE ' . NV_PREFIXLANG . '_sm_orders SET order_total=' . $order_total . ', price_payment=' . $order_total . ' WHERE order_id=' . $order_id);

        if( !empty( $order_email )){
            // Gui mail thong bao den khach hang
            $data_order['id'] = $order_id;
            $data_order['order_code'] = $order_code;
        }

        // Thong tin san pham dat hang
        if (!empty($list_productid)) {
            $templistid = implode(array_keys($list_productid));

            $sql = 'SELECT t1.id, t1.catid, t1.listcatid, t1.publtime, t1.' . NV_LANG_DATA . '_title, t1.' . NV_LANG_DATA . '_alias, t1.' . NV_LANG_DATA . '_hometext, t2.' . NV_LANG_DATA . '_title, t1.money_unit FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows AS t1 LEFT JOIN ' . $db_config['prefix'] . '_' . $module_data . '_units AS t2 ON t1.product_unit = t2.id WHERE t1.id IN (' . $templistid . ') AND t1.status =1';
            $result = $db->query($sql);

            while (list($id, $catid_i, $listcatid, $publtime, $title, $alias, $hometext, $unit, $money_unit) = $result->fetch(3)) {

                $data_pro[] = array(
                    'id' => $id,
                    'publtime' => $publtime,
                    'title' => $title,
                    'alias' => $alias,
                    'hometext' => $hometext,
                    'product_price' => $list_productid[$id]['price'],
                    'product_unit' => $unit,
                    'money_unit' => $money_unit,
                    'product_number' => $list_productid[$id]['num']
                );
            }
        }

            $lang_module['order_email_noreply'] = sprintf($lang_module['order_email_noreply'], $global_config['site_url'], $global_config['site_url']);
            $lang_module['order_email_thanks'] = sprintf($lang_module['order_email_thanks'], $global_config['site_url']);
            $lang_module['order_email_review'] = sprintf($lang_module['order_email_review'], $global_config['site_url'] );

            $content = '';
            $email_contents_table = call_user_func('email_new_order', $content, $data_order, $data_pro, true);
            $replace_data = array(
                'order_code' => $order_code,
                'order_name' => $order_name,
                'order_email' => $order_email,
                'order_phone' => $order_phone,
                'order_address' => $order_address,
                'order_note' => $data_order['order_note'],
                'order_total' => $data_order['order_total'],
                'unit_total' => $data_order['unit_total'],
                'dateup' => nv_date("d-m-Y", $data_order['order_time']),
                'moment' => nv_date("H:i", $data_order['order_time']),
                'review_url' => '<a href="' . $global_config['site_url'] . $data_order['review_url'] . '">' . $lang_module['content_here'] . '</a>',
                'table_product' => $email_contents_table,
                'site_url' => $global_config['site_url'],
                'site_name' => $global_config['site_name'],
            );

            $content_file = NV_ROOTDIR . '/' . NV_DATADIR . '/' . NV_LANG_DATA . '_' . $module_data . '_order_content.txt';
            if (file_exists($content_file)) {
                $content = file_get_contents($content_file);
                $content = nv_editor_br2nl($content);
            } else {
                $content = $lang_module['order_payment_email'];
            }

            foreach ($replace_data as $key => $value) {
                $content = str_replace('{' . $key . '}', $value, $content);
            }

            $email_contents = call_user_func('email_new_order', $content, $data_order, $data_pro);
            $email_title = empty($order_info) ? $lang_module['order_email_title'] : $lang_module['order_email_edit_title'];

            nv_sendmail(array(
                $global_config['site_name'],
                $global_config['site_email']
            ), $data_order['order_email'], sprintf($email_title, $module_info['custom_title'], $data_order['order_code']), $email_contents);

            //gui sms cho NPP
            if( $mobilerefer != '' ){
                $apikey = $module_config['sm']['apikey'];
                $secretkey = $module_config['sm']['secretkey'];
                $sms_type = $module_config['sm']['sms_type'];
                $url = '';
                if( $sms_type == 2 ){
                    $url = '&Brandname=' . $module_config['sm']['brandname'];
                }
                $content = 'Ban co don dat hang moi voi ma: ' . $order_code . ' tu KH ' . $order_name. ' SDT ' . $order_phone;
                $content = urlencode($content);

                $data = 'http://rest.esms.vn/MainService.svc/json/SendMultipleMessage_V4_get?Phone=' . $mobilerefer . '&ApiKey=' . $apikey . '&SecretKey=' . $secretkey . '&Content=' . $content . '&SmsType=' . $sms_type . $url;

                $curl = curl_init($data);
                curl_setopt($curl, CURLOPT_FAILONERROR, true);
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

                $result = curl_exec($curl);
                $obj = json_decode($result, true);

                if ($obj['CodeResult'] == '100') {
                    //gui thanh cong
                }else{
                    //ghi lai loi
                }
            }

        return $order_id;
    }
}

//Tao tai khoan khach hang mua san pham
function createCustomer( $customer_name, $customer_mail, $customer_phone, $customer_address, $userid_ref = 0 ){

    global $db;

    $userid_ref = intval( $userid_ref );
    $sql = 'SELECT customer_id FROM ' . NV_PREFIXLANG . '_sm_customer WHERE refer_userid=' . $userid_ref . ' AND fullname=' . $db->quote( $customer_name ) . ' AND phone=' . $db->quote( $customer_phone );

    list( $customer_id ) = $db->query( $sql )->fetch(3);//neu = 0 thi se tao tai khoan
    $customer_id  = intval( $customer_id );
    if( $customer_id == 0 ){

        $result = $db->query("SHOW TABLE STATUS WHERE Name='" . NV_PREFIXLANG . "_sm_customer'");
        $item = $result->fetch();
        $result->closeCursor();
        $precode = 'KH%02s';
        $customer_code = vsprintf($precode, $item['auto_increment']);
        $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_sm_customer (refer_userid, code, fullname, address, phone, email, description, add_time, edit_time, custype, status) VALUES (:refer_userid, :code, :fullname, :address, :phone, :email, :description, :add_time, :edit_time, :custype, :status)';

        $data_insert = array();
        $data_insert['refer_userid'] = $userid_ref;
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

        $customer_id = $db->insert_id( $sql, 'customer_id', $data_insert );
    }


    return $customer_id;

}