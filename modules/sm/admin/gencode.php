<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */

if( !defined( 'NV_IS_FILE_ADMIN' ) )
{
    die( 'Stop!!!' );
}
$page_title = sprintf($lang_module['gencode_title'], nv_date('d/m/Y', NV_CURRENTTIME));

$module_data = 'sm';
$data_order = array( 'chossentype' => 0, 'ordertype' => 0, 'customer_id' => 0, 'order_shipcod' => 0 );

if( $nv_Request->isset_request('resetcart', 'get') )
{
    unset($_SESSION[$module_data . '_cart']);

    $contents_msg = 'OK_update';
    include NV_ROOTDIR . '/includes/header.php';
    echo $contents_msg;
    include NV_ROOTDIR . '/includes/footer.php';
    exit;
}

if( $nv_Request->isset_request('setprice', 'get') )
{
    $id = $nv_Request->get_int('id', 'post,get', 0);
    $price = $nv_Request->get_title('price', 'post,get', '');
    $contents_msg = "";

    if ($id > 0) {
        $_SESSION[$module_data . '_cart'][$id]['gift_point'] = $price;
    }
    $contents_msg = 'OK_update';
    include NV_ROOTDIR . '/includes/header.php';
    echo $contents_msg;
    include NV_ROOTDIR . '/includes/footer.php';
    exit;
}

if( $nv_Request->isset_request('setcart', 'get') )
{
    /*
    if (! isset($_SESSION[$module_data . '_cart'])) {
        $_SESSION[$module_data . '_cart'] = array();
    }
    $_SESSION[$module_data . '_cart']['updated'] = 1;
    */
    $id = $nv_Request->get_int('id', 'post,get', 0);
    $num = $nv_Request->get_title('num', 'post,get', '');
    $contents_msg = "";

    if ($id > 0) {
        $_SESSION[$module_data . '_cart'][$id]['num'] = $num;
    }
    $contents_msg = 'OK_update';
    include NV_ROOTDIR . '/includes/header.php';
    echo $contents_msg;
    include NV_ROOTDIR . '/includes/footer.php';
    exit;
}

if( $nv_Request->isset_request('remove', 'get') )
{
    $id = $nv_Request->get_int('id', 'post,get', 1);

    if( isset( $_SESSION[$module_data . '_cart'][$id] )){
        unset( $_SESSION[$module_data . '_cart'][$id] );
    }

    $contents_msg = 'OK_update';
    include NV_ROOTDIR . '/includes/header.php';
    echo $contents_msg;
    include NV_ROOTDIR . '/includes/footer.php';
    exit;
}

if( $nv_Request->isset_request('loadcart', 'get') )
{
    //$_SESSION[$module_data . '_cart'] = array();
    $producttype = $nv_Request->get_int( 'producttype', 'get', 0 );
    if ($producttype == 0)//không chọn
    {
        unset($_SESSION[$module_data . '_cart']);
        echo "";
        exit;
    }

    $array_product = array();
    if ($producttype == 3) {
        $array_product[1]['id'] = 1;
        $array_product[1]['proid'] = 0;
        $array_product[1]['catid'] = 0;
        $array_product[1]['title'] = 'Tạo mã dùng chung cho cả sản phẩm hoặc nhóm sản phẩm';
        $array_product[1]['codetype'] = 'Tạo mã dùng chung';

        if( !isset( $_SESSION[$module_data . '_cart'][1] )){
            $_SESSION[$module_data . '_cart'][1]['proid'] = $array_product[1]['proid'];
            $_SESSION[$module_data . '_cart'][1]['catid'] = $array_product[1]['catid'];
            $_SESSION[$module_data . '_cart'][1]['codetype'] = $array_product[1]['codetype'];
            $_SESSION[$module_data . '_cart'][1]['num'] = '';
            $_SESSION[$module_data . '_cart'][1]['gift_point'] = '';
        }

    } else {
        if ($producttype == 1) {//tạo mã theo sản phẩm
            $sql = "SELECT id, id as proid, 0 as catid, title, 'Tạo mã theo sản phẩm' as codetype FROM " . NV_PREFIXLANG . "_" . $module_data . "_product WHERE status = 1 ORDER BY weight";
        } elseif ($producttype == 2) {//tạo mã theo nhóm sản phẩm
            $sql = "SELECT id, 0 as proid, id as catid, title, 'Tạo mã theo nhóm sản phẩm' as codetype FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat WHERE status = 1 ORDER BY weight";
        }

        $result = $db->query( $sql );
        while( $row = $result->fetch( ) )
        {
            if( !isset( $_SESSION[$module_data . '_cart'][$row['id']] )){
                $_SESSION[$module_data . '_cart'][$row['id']]['proid'] = $row['proid'];
                $_SESSION[$module_data . '_cart'][$row['id']]['catid'] = $row['catid'];
                $_SESSION[$module_data . '_cart'][$row['id']]['codetype'] = $row['codetype'];
                $_SESSION[$module_data . '_cart'][$row['id']]['num'] = '';
                $_SESSION[$module_data . '_cart'][$row['id']]['gift_point'] = '';
            }
            $array_product[$row['id']] = $row;
        }
    }
    //print_r($array_product);echo '<br/>';
    $data_content = array();
    foreach( $_SESSION[$module_data . '_cart'] as $pro_id => $pro_info )
    {
        if( $pro_id > 0 && isset($array_product[$pro_id])){
            $row = $array_product[$pro_id];

            $row['cartnumber'] = $_SESSION[$module_data . '_cart'][$row['id']]['num'];
            $row['gift_point'] = $_SESSION[$module_data . '_cart'][$row['id']]['gift_point'];
            $data_content[$pro_id] = $row;
        }
        else if( intval( $pro_id ) > 0 && !isset($array_product[$pro_id])){
            unset( $_SESSION[$module_data . '_cart'][$pro_id]);
        }
    }

    $xtpl = new XTemplate('gencode.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);

    //hien thi du lieu
    $stt = 1;
    foreach ($data_content as $data_row) {
        $data_row['stt'] = $stt++;
        $xtpl->assign('DATA', $data_row);
        $xtpl->parse('product.rows');
    }
    $xtpl->parse('product' );
    $contents = $xtpl->text('product' );

    //$contents = cart_product_load_gencode_order($data_content);
    include NV_ROOTDIR . '/includes/header.php';
    echo $contents;
    include NV_ROOTDIR . '/includes/footer.php';
    exit;
}

$exportxls = false;
$sql_export = '';
$order_id = 0;
if( $nv_Request->isset_request('submit', 'post') ) {

    $data_order['customer_id'] = $admin_info['userid'] ;// $nv_Request->get_int('customer_id', 'post', 0);
    $data_order['producttype'] = $nv_Request->get_int( 'producttype', 'post', 1 );

    $precode = 'KM%01s';

    $result = $db->query("SHOW TABLE STATUS WHERE Name='" . NV_PREFIXLANG . "_" . $module_data . "_orders'");
    $item = $result->fetch();
    $result->closeCursor();

    $order_code = vsprintf($precode, $item['auto_increment']);

    $sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_orders (
        customer_id, order_code, order_name, user_id, order_time, postip, status, producttype
    ) VALUES (
        " . $data_order['customer_id'] . ", :order_code, :order_name,
        " . $data_order['customer_id'] . ", 
        " . NV_CURRENTTIME . ", :ip, :status,
        " . $data_order['producttype'] . "
    )";

    //die($sql);
    $data_insert = array( );
    $data_insert['order_code'] = $order_code;
    $data_insert['order_name'] = $page_title;
    $data_insert['ip'] = $client_info['ip'];
    $data_insert['status'] = OD7_FINISHED;

    $order_id = $db->insert_id($sql, 'order_id', $data_insert);

    if ($order_id > 0) {
        $code_array = array();
        //Them chi tiet don hang
        foreach ( $_SESSION[$module_data . '_cart'] as $pro_id => $order_product ) {
            $pronums = explode(',', $order_product['num'] );
            $progifts = explode(',', $order_product['gift_point'] );

            if( !empty($pronums) && !empty($order_product['num'])){
                $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_orders_gencode( order_id, proid, catid, num, bonus ) 
                        VALUES ( :order_id, :proid, :catid, :num, :bonus)';
                $data_insert = array();
                $data_insert['order_id'] = $order_id;
                $data_insert['proid'] = $order_product['proid'];
                $data_insert['catid'] = $order_product['catid'];
                $data_insert['num'] = $order_product['num'];
                $data_insert['bonus'] = $order_product['gift_point'];
                $order_detail_id = $db->insert_id($sql, 'id', $data_insert);

                if ($order_detail_id > 0) {
                    //sinh mã
                    for ($i = 0; $i < count($pronums); $i++) {
                        try {
                            $barcodes = nvGenBarCodeArr($pronums[$i], $code_array);
                            $bonus_point = 0;
                            $bonus_gift = "";
                            if (!empty($progifts[$i])) {
                                if (is_numeric($progifts[$i])) {
                                    $bonus_point = $progifts[$i];
                                } else {
                                    $bonus_gift = $progifts[$i];
                                }
                            }

                            foreach ($barcodes as $barcode) {
                                $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_barcode( proid, barcode, bonus_point, bonus_gift, status, created_date, catid, ordercodeid ) 
                            VALUES ( :proid, :barcode, :bonus_point, :bonus_gift, 0, ' . NV_CURRENTTIME . ', :catid, :ordercodeid)';
                                $data_insert = array();
                                $data_insert['ordercodeid'] = $order_id;
                                $data_insert['barcode'] = $barcode;
                                $data_insert['bonus_point'] = $bonus_point;
                                $data_insert['bonus_gift'] = $bonus_gift;
                                $data_insert['proid'] = $order_product['proid'];
                                $data_insert['catid'] = $order_product['catid'];

                                $db->insert_id($sql, 'id', $data_insert);
                            }
                            $exportxls = true;

                        } catch (Exception $e) {
                            echo $e->getMessage();
                        }
                    }
                }
                //print_r($data_order);die();
            }
        }
        //Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name);
        //exit();
    }
}

$xtpl = new XTemplate('gencode.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );

if( !empty( $error )){
    $xtpl->assign('ERROR', implode('<br />', $error));
    $xtpl->parse('main.error');
}
//print_r($data_order); echo '<br/>';
//print_r($array_select_gencodetype);echo '<br/>';
//print_r($_SESSION[$module_data . '_cart'] );echo '<br/>';
//echo 'a:'.$exportxls;
foreach ( $array_select_gencodetype as $key => $producttype ){
    $sl = ( $data_order['producttype'] == $key )? ' selected=selected' : '';
    $xtpl->assign('PRODUCTTYPE', array('key' => $key, 'value' => $producttype, 'sl' => $sl));
    $xtpl->parse('main.producttype');
}

if (!empty($array_bonus_barcode)) {
    foreach ($array_bonus_barcode as $index => $value) {
        $xtpl->assign('PERSONAL', array(
            'index' => $index,
            'value' => $value
        ));
        $xtpl->parse('main.personal');
    }
}

if (!$exportxls) {
    $xtpl->parse('main.gencode');
} elseif ($order_id > 0) {
    $sql_export = 'select * from ' . NV_PREFIXLANG . '_' . $module_data . '_barcode where ordercodeid = ' . $order_id;
    $xtpl->assign( 'sql_export', base64_encode( $sql_export));
    $xtpl->parse('main.exportxls');
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
