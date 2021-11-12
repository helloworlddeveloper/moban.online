<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 19 Jun 2014 09:53:21 GMT
 */

if ($userid > 0 )
{
    $sql = 'SELECT id, catid, product_code, product_price, product_weight, weight_unit, homeimgfile, homeimgthumb, vi_title, vi_alias, vi_hometext, vi_bodytext ' .
           'FROM ' . $db_config['prefix'] . '_shops_rows';

    echo $sql;
    $result = $db->query( $sql );
    $array = array();
    $i = 0;
    while( $row = $result->fetch() )
	{
        $array[$i] = array();
        $array[$i]["productId"] = $row['id'];
        $array[$i]["categoryId"] = $row['catid'];
        $array[$i]["productCode"] = $row['product_code'];
        $array[$i]["productPrice"] = $row['product_price'];
        $array[$i]["productWeight"] = $row['product_weight'];
        $array[$i]["weightUnit"] = $row['weight_unit'];
        if (!empty($row['homeimgfile'])) {
            $array[$i]["homeimgfile"] = NV_MY_DOMAIN . NV_BASE_SITEURL . NV_ASSETS_DIR . "/shops/" . $row['homeimgfile'];
        } else {
            $array[$i]["homeimgfile"] = '';
        }
        $array[$i]["homeimgthumb"] = $row['homeimgthumb'];
        $array[$i]["productName"] = $row['vi_title'];
        $array[$i]["productAlias"] = $row['vi_alias'];
        $array[$i]["productInfor"] = strip_tags( $row['vi_hometext'] );

        if (preg_match_all("/\<img[^\>]*src=\"([^\"]*)\"[^\>]*\>/is", $row['vi_bodytext'], $match)) {
            foreach ($match[0] as $key => $_m) {
                $image_url = $image_url_tmp = $match[1][$key];
                $str_replace = 0;

                // Loại bỏ https trong đường dẫn ảnh
                $parsed = parse_url($image_url);
                if (isset($parsed['scheme']) && strtolower($parsed['scheme']) == 'https') {
                    $str_replace = 1;
                    $image_url = 'http://' . substr($image_url, 8);
                }

                if (!empty($image_url) and !isset($parsed['host']) and !preg_match('/^\/\//', $image_url)) {
                    $str_replace = 1;
                    $image_url = NV_MY_DOMAIN . $groups_info['url'] . $image_url;
                }

                if ($str_replace) {
                    $row['vi_bodytext'] = str_replace($image_url_tmp, $image_url, $row['vi_bodytext']);
                }

            }
            $row['vi_bodytext'] = preg_replace(
                array('/width="\d+"/i', '/height="\d+"/i'),
                array(sprintf('width="%d"', 350), sprintf('height="%d"', 350)),
                $row['vi_bodytext']);
        }

        $array[$i]["productDetail"] = $row['vi_bodytext'];

		$i++;
	}
    
    echo json_encode($array);
}
else
{
    echo json_encode(array()); 
}