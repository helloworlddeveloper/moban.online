<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 19 Jun 2014 09:53:21 GMT
 * API tra ve sp va ton kho cua NPP
 */

$productid = $nv_Request->get_title('productid', 'post', '');
$quantity = $nv_Request->get_title('quantity', 'post', '');
if (!empty( $productid ))
{
    $productid_array = explode(',', $productid );
    $quantity_array = explode(',', $quantity );
    $array_product = array();
    $total_price_all = 0;
    foreach ( $productid_array as $key => $productid ){
        $sql = "SELECT price_retail FROM " . NV_IS_LANG_TABLE_SM . "_product WHERE id= " . $productid;
        list( $price_retail ) = $db->query( $sql )->fetch(3);
        $quantity = $quantity_array[$key];
        $price_discount = nv_get_price_for_agency( $price_retail, $productid, $quantity, $per_pro = false );
        $total_price_all += $price_discount * $quantity;
        $array_product[$key] = array(
            "userid" => $userid,
            "productid" => $productid,
            "quantity" => $quantity,
            "price" =>  $price_retail,
            "price_format" => number_format( $price_retail, 0, '.', ','),
            "price_discount" =>  $price_discount,
            "price_discount_format" => number_format( $price_discount, 0, '.', ','),
        );
    }

    $array = array('total_price' => $total_price_all, 'total_price_format' => number_format( $total_price_all, 0, '.', ','), 'listproduct' => $array_product );

    echo json_encode($array);
}
else
{
    echo json_encode(array());
}