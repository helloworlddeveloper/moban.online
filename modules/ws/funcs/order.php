<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 19 Jun 2014 09:53:21 GMT
 */

if ($userid  > 0)
{

    $array_repomsive = array('status' => 0);
    $data_order['order_name'] = nv_substr($nv_Request->get_title('fullname', 'post', '', 1), 0, 200);
    $data_order['order_email'] = nv_substr($nv_Request->get_title('email', 'post', '', 1), 0, 250);
    $data_order['order_phone'] = nv_substr($nv_Request->get_title('mobile', 'post', '', 1), 0, 20);
    $data_order['order_address'] = nv_substr($nv_Request->get_title('address', 'post', '', 1), 0, 255);
    $data_order['order_note'] = nv_substr($nv_Request->get_title('note', 'post', '', 1), 0, 2000);
    $data_order['customer_id'] = $nv_Request->get_int('customerid', 'post', 0);
    $data_order['chossentype'] = $nv_Request->get_int('chossentype', 'post', 0);
    $data_order['ordertype'] = $nv_Request->get_int('ordertype', 'post', 0);
    $data_order['order_shipcod'] = $nv_Request->get_int('shipCOD', 'post' );
    $data_order['orderid'] = $nv_Request->get_int('orderid', 'post', 0);//id don hang dat truoc
    $data_order['depotid'] = $nv_Request->get_int('depotid', 'post', 0);//id kho hang
    $data_order['price_payment'] = $nv_Request->get_int('payment', 'post', 0);//id kho hang
    $data_order['products'] = $nv_Request->get_title('products', 'post', '');
    $data_order['amounts'] = $nv_Request->get_title('amounts', 'post', '');

    if( empty( $data_order['products'] )){
        $array_repomsive['message'] = $lang_module['error_no_product_booking'];
    }elseif( empty( $data_order['amounts'] )){
        $array_repomsive['message'] = $lang_module['error_no_amounts_booking'];
    }
    $order_info = array();

    //neu sua tu don hang dat truoc thi lay customer_id tu don hang dat truoc
    if( $data_order['orderid'] > 0 ){
        $sql = 'SELECT * FROM ' . NV_IS_LANG_TABLE_SM . '_orders WHERE order_id=' . $data_order['orderid'];
        $data_order_info = $db->query($sql)->fetch();
        $data_order['customer_id'] = $data_order_info['customer_id'];
    }
    if( $data_order['customer_id'] == 0 && ( empty( $data_order['order_name'] ) or empty( $data_order['order_phone'] ) ) )
    {
        $array_repomsive['message'] = $lang_module['error_customer_info'];
    }
    //lay thong tin khach hang NPP
    if( $data_order['customer_id'] > 0){
        $sql = 'SELECT * FROM ' . NV_IS_TABLE_AFFILIATE . '_users WHERE userid=' . $data_order['customer_id'];
        $data_user_info = $db->query($sql)->fetch();
    }
    $saleoff = $discount = $check_warehouse = 0;


    //kiem tra doi tuong dat hang de giam gia theo %
    if( $data_order['chossentype'] != 3 ){
        if( $data_order['customer_id'] > 0){
            $sql = 'SELECT * FROM ' . NV_IS_TABLE_AFFILIATE . '_users WHERE userid=' . $data_order['customer_id'];
            $data_user_info = $db->query($sql)->fetch();
            if( $data_user_info['agencyid'] > 0 ){
                $agency_of_you = $array_agency[$data_user_info['agencyid']];
            }else{
                $agency_of_you = array();
            }
        } elseif( $data_order['customer_id'] > 0){
            $sql = 'SELECT * FROM ' . NV_IS_TABLE_AFFILIATE . '_users WHERE userid=' . $data_order['customer_id'];
            $data_user_info = $db->query($sql)->fetch();
            if( $data_user_info['agencyid'] > 0 ){
                $agency_of_you = $array_agency[$data_user_info['agencyid']];
            }else{
                $agency_of_you = array();
            }
        }
    }

    $data_order['saleoff'] = 0;
    //giam gia cho theo % phan saleoff
    if( $data_order['saleoff'] > 0 && isset( $array_saleoff[$data_order['saleoff']] ) ){
        $saleoff = $array_saleoff[$data_order['saleoff']]['percent'];
    }

    $total_price = 0;
    $productid_array = explode(',', $data_order['products'] );
    $amounts_array = explode(',', $data_order['amounts'] );
    foreach( $productid_array as $key => $pro_id )
    {
        $amounts = $amounts_array[$key];
        if( intval( $pro_id ) > 0 && $amounts > 0 ){
            $sql = "SELECT * FROM " . NV_IS_LANG_TABLE_SM . "_product WHERE id = " . $pro_id . " AND status =1";
            $result = $db->query( $sql );
            while( $data_row = $result->fetch( ) )
            {
                $data_row['cartnumber'] = $amounts;
                if( $data_order['chossentype'] == 3){

                    $data_row['price_total'] = $data_row['price_retail'] * $data_row['cartnumber'];
                    $total_price = $total_price + $data_row['price_total'];
                }else{
                    $data_row['price_total'] = nv_get_price_for_agency( $data_row['price_retail'], $data_row['id'], $data_row['cartnumber'], true );
                    $data_row['price_retail'] = nv_get_price_for_agency( $data_row['price_retail'], $data_row['id'], $data_row['cartnumber'] );
                    $total_price = $total_price + $data_row['price_total'];
                }
                $total_num_product = checkNumTotalWarehouseLogs( $data_order['depotid'], $pro_id );

                $data_content[] = $data_row;

                //kiem tra trong kho nhanh tren co du hang de nhap khong
                if( $total_num_product < $data_row['cartnumber'] && $data_order['ordertype'] == 1 ){
                    $array_repomsive['message'] = sprintf( $lang_module['number_in_warehouse_logs_error'], $data_row['title'], $total_num_product );
                }
            }
        }
    }


    if( $data_order['customer_id'] == 0 && !empty( $data_order['order_phone'] ) && !empty( $data_order['order_name'] ) && empty( $array_repomsive['message'] ) ){
        //tao tai khoan cho khach le
        $data_order['customer_id'] = createCustomer( $data_order['order_name'], $data_order['order_email'], $data_order['order_phone'], $data_order['order_address'] );
    }

    $data_order['price_total_discount'] = 0;
    if( $agency_of_you['price_for_discount'] > 0 and $agency_of_you['price_discount'] >0 && $data_order['ordertype'] == 1){
        $data_order['price_total_discount'] = floor($total_price / $agency_of_you['price_for_discount']) * $agency_of_you['price_discount'];
    }
    $total_check_price_require = $total_price;//so tien de check don hang toi thieu
    if( $data_order['price_total_discount'] > 0 ){
        $total_price = $total_price - $data_order['price_total_discount'];
    }
    $data_order['order_total'] = $total_price;

    $price_require = $agency_of_you['price_require'];// gia nhap bat buoc trong 1 lan nhap

    $percent_book_one = $module_config[$module_name]['percent_book_one']; //ti le % gia tri don hang moi lan nhap
    if( $total_check_price_require < $price_require ){
        $array_repomsive['message'] = sprintf( $lang_module['error_percent_book_first'], $agency_of_you['title'], number_format( $agency_of_you['price_require'], 0, '.', ','));
    }

    $price_payment = 0;
    if( $data_order['ordertype'] == 2 ){
        $price_payment = ($data_order['order_total'] / 100) * NV_DEFINE_DEPOSITS;
        if( $data_order['price_payment'] < $price_payment ){
            $array_repomsive['message'] = sprintf( $lang_module['error_price_payment_order_plane'], number_format( $price_payment, 0, '.', ','), NV_DEFINE_DEPOSITS . '%' );
        }
    }elseif( $data_order['orderid'] > 0 ){
        //lay thong tin don hagn dat trc
        $sql = 'SELECT * FROM ' . NV_IS_LANG_TABLE_SM . '_orders WHERE ordertype=2 AND user_id =' . $user_info['userid'] . ' AND order_id=' . $data_order['orderid'];
        $result = $db->query($sql);
        $data_order_old = $result->fetch();
        if( isset( $data_order_old['price_payment'] ) && $data_order['order_total'] < $data_order_old['price_payment'] ){
            $array_repomsive['message'] = $lang_module['error_price_total_for_plane'];
        }
    }


    if (empty($array_repomsive['message'])) {

        //don hang tao moi tu dau
        if( $data_order['orderid']  == 0 ){

            //lay ma don hang theo cau truc ma ng quan ly + ma don hang
            if( $data_order['chossentype'] == 3 or $data_order['customer_id'] != $user_info['userid'] ){
                $sql = "SELECT precode FROM " . NV_IS_TABLE_AFFILIATE . "_users WHERE userid=" . $data_order['customer_id'];
            }else{
                $sql = "SELECT parentid FROM " . NV_IS_TABLE_AFFILIATE . "_users WHERE userid=" . $data_order['customer_id'];
                $result = $db->query($sql);
                list( $parentid ) = $result->fetch(3);
                $sql = "SELECT precode FROM " . NV_IS_TABLE_AFFILIATE . "_users WHERE userid=" . $parentid;
            }

            $result = $db->query($sql);
            list( $precode ) = $result->fetch(3);
            if( $precode == ''){
                $precode = 'S%01s';
            }else{
                $precode = str_replace('%', '-S%', $precode) ;
            }


            $result = $db->query("SHOW TABLE STATUS WHERE Name='" . NV_IS_LANG_TABLE_SM . "_orders'");
            $item = $result->fetch();
            $result->closeCursor();

            $order_code = vsprintf($precode, $item['auto_increment']);

            $status = 0;
            if( $data_order['ordertype'] == 2 ){
                $status = 4;
            }
            //$data_order['price_payment'] = 0;

            try{
                $sql = "INSERT INTO " . NV_IS_LANG_TABLE_SM . "_orders (
                customer_id, order_code, order_name, order_email, order_phone, 
                order_address, order_note, user_id, admin_id, order_total, order_time, edit_time, postip, 
                saleoff, feeship, price_payment, shipcode, showadmin, chossentype, ordertype, orderid_refer, amount_refunded, depotid, status
                ) VALUES (
                    " . $data_order['customer_id'] . ", :order_code, :order_name, :order_email, :order_phone, :order_address, :order_note,
                    " . $userid . ", 0," . doubleval($data_order['order_total']) . ",
                    " . NV_CURRENTTIME . ", 0, :ip, " . $data_order['price_total_discount'] . ", 0, " . $data_order['price_payment'] . ", " . $data_order['order_shipcod'] . ", 0, " . $data_order['chossentype'] . ", " . $data_order['ordertype'] . ", 0, " . doubleval($data_order['order_total']) . ", " . intval( $data_order['depotid'] ) . ", " . $status . "
                )";
                $data_insert = array( );
                $data_insert['order_code'] = $order_code;
                $data_insert['order_name'] = $data_order['order_name'];
                $data_insert['order_email'] = $data_order['order_email'];
                $data_insert['order_phone'] = $data_order['order_phone'];
                $data_insert['order_address'] = $data_order['order_address'];
                $data_insert['order_note'] = $data_order['order_note'];
                $data_insert['ip'] = $client_info['ip'];

                $order_id = $db->insert_id($sql, 'order_id', $data_insert);
                if ($order_id > 0) {

                    if( $data_order['chossentype'] != 3 && $data_order['ordertype'] == 1) {
                        //khoi tao kho hang theo tung khach hang
                        $sql = 'SELECT COUNT(*) FROM ' . NV_IS_LANG_TABLE_SM . '_warehouse WHERE customerid=' . $data_order['customer_id'];
                        $check_exits = $db->query( $sql )->fetchColumn();
                        if( $check_exits == 0 ){
                            $title = $lang_module['warehouse_off'] . $user_data_affiliate['code'];
                            $note = $lang_module['warehouse_off_create_date'] . NV_CURRENTTIME;
                            //khoi tao kho hang
                            $sql = 'INSERT INTO ' . NV_IS_LANG_TABLE_SM . '_warehouse( customerid, title, note, addtime, price_discount_in, price_discount_out )
                             VALUES ( ' . $data_order['customer_id'] . ', ' . $db->quote( $title ) . ', ' . $db->quote( $note ) . ', ' . NV_CURRENTTIME . ',0 ,0 )';
                            $db->query($sql);
                        }

                    }
                    //ghi lich su giao dich voi don dat trc va status =4
                    if( $data_order['ordertype'] == 2 && $status == 4 ){
                        $payment_id = 0;
                        $payment_data = '';
                        $payment = '';

                        $transaction_id = $db->insert_id("INSERT INTO " . NV_IS_LANG_TABLE_SM . "_transaction (transaction_id, transaction_time, transaction_status, order_id, userid, payment, payment_id, payment_time, payment_amount, payment_data) VALUES (NULL, " . NV_CURRENTTIME . ", '" . $status . "', '" . $order_id . "', '" . $userid . "', '" . $payment . "', '" . $payment_id . "', " . NV_CURRENTTIME . ", '" . $data_order['price_payment'] . "', '" . $payment_data . "')");
                        if ($transaction_id == 0) {
                            //ghi log neu k ghi dc giao dich
                            nv_insert_logs(NV_LANG_DATA, $module_name, 'Log payment order', "Order code: " . $data_content['order_code'], $userid);
                        }
                    }
                    //Them chi tiet don hang
                    foreach ( $data_content as $key => $order_product ) {
                        $sql = 'INSERT INTO ' . NV_IS_LANG_TABLE_SM . '_orders_id( order_id, proid, num, type_return, numreturn, price, num_out ) 
                        VALUES ( :order_id, :proid, :num, 0, 0, :price, 0 )';
                        $data_insert = array();
                        $data_insert['order_id'] = $order_id;
                        $data_insert['proid'] = $order_product['id'];
                        $data_insert['num'] = $order_product['cartnumber'];
                        $data_insert['price'] = $order_product['price_retail'];
                        $order_i = $db->insert_id($sql, 'id', $data_insert);

                        $array_products[$key] = array(
                            'productid' => $order_product['id'],
                            'productcode' => $order_product['code'],
                            'productname' => $order_product['title'],
                            'productprice' => number_format( $order_product['price_retail'], 0,'.', ','),
                            'productimage' => !empty( $order_product['image'] )? NV_MY_DOMAIN . NV_BASE_SITEURL . NV_ASSETS_DIR . "/sm/" . $order_product['image'] : '',
                            'quantity' => $order_product['cartnumber'],
                        );

                        //nhap kho neu la don dat hang
                        if( $data_order['ordertype'] == 1){
                            //Khach le mua thi ghi vào bang cham soc khach hang
                            if( $data_order['chossentype'] == 3 ){
                                $day_received = ($data_order['order_shipcod'] == 1) ? NV_DEFINE_DAY_RECEIVED : 0;
                                $product_name = $array_product[$order_product['id']]['title'];
                                nvInsertSmsQueue( $order_id, $order_product['id'], $product_name, NV_CURRENTTIME, $data_order['order_name'], $data_order['order_email'], $data_order['order_phone'], $data_order['order_address'], $day_received );
                            }
                            //nhap kho hang voi he thong DL, CTV
                            $quantity = $order_product['cartnumber'];
                            $quantity_gift = $order_product['number_gift'];
                            $price = $order_product['price_retail'] * $order_product['cartnumber'];
                            $price = $price - ( $price * $saleoff )/100;
                            nhapkhohanghoa( $data_order['customer_id'], $data_order['depotid'], $order_product['id'], $quantity, $quantity_gift, $price, '+', $data_order['chossentype'], $order_id );
                        }
                    }
                    $array_repomsive['status'] = 1;
                }
            } catch (PDOException $e) {
                $array_repomsive['message'] = $e->getMessage();
            }

        }
        else{
            //kiem tra lai lan nua tinh xac thuc cua don hang dat truoc
            $sql = 'SELECT * FROM ' . NV_IS_LANG_TABLE_SM . '_orders WHERE ordertype=2 AND user_id =' . $userid . ' AND order_id=' . $data_order['orderid'];

            $result = $db->query($sql);
            $data_order_old = $result->fetch();
            if( !empty( $data_order_old ) && $data_order_old['ordertype'] == 2 ){
                try {
                    $_sql = 'UPDATE ' . NV_IS_LANG_TABLE_SM . '_orders
                     SET order_name = :order_name, order_email = :order_email, order_phone=:order_phone, 
                     order_address = :order_address, order_note = :order_note, order_total =:order_total, edit_time=' . NV_CURRENTTIME . ', postip=:postip,
                     saleoff=:saleoff, shipcode=:shipcode, ordertype=1 WHERE order_id =' . $data_order_old['order_id'];

                    $sth = $db->prepare($_sql);
                    $sth->bindParam(':order_name', $data_order['order_name'], PDO::PARAM_STR);
                    $sth->bindParam(':order_email', $data_order['order_email'], PDO::PARAM_STR);
                    $sth->bindParam(':order_phone', $data_order['order_phone'], PDO::PARAM_STR);
                    $sth->bindParam(':order_address', $data_order['order_address'], PDO::PARAM_STR);
                    $sth->bindParam(':order_note', $data_order['order_note'], PDO::PARAM_STR);
                    $sth->bindParam(':order_total', $data_order['order_total'], PDO::PARAM_INT);
                    $sth->bindParam(':postip', $client_info['ip'], PDO::PARAM_STR);
                    $sth->bindParam(':saleoff', $data_order['price_total_discount'], PDO::PARAM_INT);
                    $sth->bindParam(':shipcode', $data_order['order_shipcod'], PDO::PARAM_INT);
                    $sth->execute();

                    if ($sth->rowCount()) {

                        //xoa cac ban ghi trong phan chi tiet don hang cu
                        $db->query("DELETE FROM " . NV_IS_LANG_TABLE_SM . "_orders_id WHERE order_id=" . $data_order_old['order_id']);

                        if( $data_order['chossentype'] != 3 && $data_order['ordertype'] == 1) {

                            //khach hang le
                            if( $data_order['chossentype'] == 3){
                                $sql = "SELECT refer_userid FROM " . NV_IS_LANG_TABLE_SM . "_customer WHERE customer_id=" . $data_order_old['customer_id'];
                            }else{
                                $sql = "SELECT parentid FROM " . NV_IS_TABLE_AFFILIATE . "_users WHERE userid=" . $data_order_old['customer_id'];
                            }
                            $result = $db->query($sql);
                            list( $user_id ) = $result->fetch(3);

                            $kho_tru_chiet_khau = $user_id;
                            //neu $possitonid > 0 thi gan $user_id = 0 (kho tong)
                            list( $possitonid ) = $db->query("SELECT possitonid FROM " . NV_IS_TABLE_AFFILIATE . "_users WHERE userid=" . $user_id)->fetch(3);;
                            if( $possitonid > 0 ){
                                $kho_tru_chiet_khau = 0;
                            }

                            //khoi tao kho hang theo tung khach hang
                            $sql = 'SELECT COUNT(*) FROM ' . NV_IS_LANG_TABLE_SM . '_warehouse WHERE customerid=' . $data_order_old['customer_id'];
                            $check_exits = $db->query( $sql )->fetchColumn();
                            if( $check_exits == 0 ){
                                $title = $lang_module['warehouse_off'] . $user_data_affiliate['code'];
                                $note = $lang_module['warehouse_off_create_date'] . NV_CURRENTTIME;

                                $sql = 'INSERT INTO ' . NV_IS_LANG_TABLE_SM . '_warehouse( customerid, title, note, addtime, price_discount_in, price_discount_out )
                                     VALUES ( ' . $data_order_old['customer_id'] . ', ' . $db->quote( $title ) . ', ' . $db->quote( $note ) . ', ' . NV_CURRENTTIME . ',0 ,0 )';
                                $db->query($sql);
                            }
                        }
                        //Them chi tiet don hang
                        foreach ( $data_content as $order_product ) {
                            $sql = 'INSERT INTO ' . NV_IS_LANG_TABLE_SM . '_orders_id( order_id, proid, num, type_return, numreturn, price ) 
                            VALUES ( :order_id, :proid, :num, 0, 0, :price )';
                            $data_insert = array();
                            $data_insert['order_id'] = $data_order_old['order_id'];
                            $data_insert['proid'] = $order_product['id'];
                            $data_insert['num'] = $order_product['cartnumber'];
                            $data_insert['price'] = $order_product['price_retail'];
                            $order_i = $db->insert_id($sql, 'id', $data_insert);

                            $array_products[$key] = array(
                                'productid' => $order_product['id'],
                                'productcode' => $order_product['code'],
                                'productname' => $order_product['title'],
                                'productprice' => number_format( $order_product['price_retail'], 0,'.', ','),
                                'productimage' => !empty( $order_product['image'] )? NV_MY_DOMAIN . NV_BASE_SITEURL . NV_ASSETS_DIR . "/sm/" . $order_product['image'] : '',
                                'quantity' => $order_product['cartnumber'],
                            );

                            //nhap kho neu la don dat hang
                            if( $data_order['ordertype'] == 1){
                                //nhap kho hang voi he thong DL, CTV
                                $quantity = $order_product['cartnumber'];
                                $quantity_gift = $order_product['number_gift'];
                                $price = $order_product['price_retail'] * $order_product['cartnumber'];
                                $price = $price - ( $price * $saleoff )/100;
                                nhapkhohanghoa( $data_order_old['customer_id'], $order_product['id'], $quantity, $quantity_gift, $price, '+', $data_order['chossentype'], $data_order_old['order_id'] );
                            }
                        }

                        $array_repomsive['status'] = 1;
                    }
                } catch (PDOException $e) {
                    $array_repomsive['message'] = $e->getMessage();
                }
            }
        }
    }
    if( $array_repomsive['status'] == 1 ){

        //return message
        $array_return['ordercode'] = $order_code;
        $array_return['orderdate'] = date('d/m/Y H:i', NV_CURRENTTIME );
        $array_return['customer'] = $data_order['order_name'];
        $array_return['customerid'] = $data_order['customer_id'];
        $array_return['userid'] = $userid;
        $array_return['email'] = $data_order['order_email'];
        $array_return['mobile'] = $data_order['order_phone'];
        $array_return['address'] = $data_order['order_address'];
        $array_return['ordertotal'] = number_format( $total_check_price_require, 0,'.', ',');
        $array_return['bonus'] = number_format( $data_order['price_total_discount'], 0,'.', ',');
        $array_return['amount'] = number_format( $data_order['order_total'], 0,'.', ',');
        $array_return['payment'] = number_format( $data_order['price_payment'], 0,'.', ',');
        $array_return['debt'] = number_format( $data_order['order_total'] - $data_order['price_payment'], 0,'.', ',');
        $array_return['status'] = 1;
        $array_return['products'] = $array_products;

        echo json_encode($array_return);
    }else{
        echo json_encode( $array_repomsive );
        die;
    }
}
else
{
    echo json_encode(array());
}