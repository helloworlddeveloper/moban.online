<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 0:51
 */

if (! defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$sql = "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_agency WHERE status=1 ORDER BY weight";
$array_agency = $nv_Cache->db($sql, 'id', $module_name);
$sql = "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_possiton WHERE status=1 ORDER BY weight";
$array_possiton = $nv_Cache->db($sql, 'id', $module_name);

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_province WHERE status=1 ORDER BY weight";
$array_province = $nv_Cache->db($sql, 'id', $module_name);


$array_personal_sms = array(
    '[FULLNAME]' => $lang_module['content_note_fullname'],
    '[FIRSTNAME]' => $lang_module['content_note_lastname'],
    '[MOBILE]' => $lang_module['content_note_phone'],
    '[EMAIL]' => $lang_module['content_note_email'],
    '[TIME_DELETE]' => $lang_module['content_note_time_delete'],
    '[SITE_NAME]' => sprintf($lang_module['content_note_site_name'], $global_config['site_name']),
    '[SITE_DOMAIN]' => sprintf($lang_module['content_note_site_domain'], NV_MY_DOMAIN)
);
// Cau hinh mac dinh
$affiliate_config = $module_config[$module_name];
$per_page = $affiliate_config['per_page'];

$array_search_status = array();

$array_search_status[0] = array(
    'key' => 0,
    'value' => $lang_module['search_status_0']
);
$array_search_status[1] = array(
    'key' => 1,
    'value' => $lang_module['search_status_1']
);
$array_search_status[2] = array(
    'key' => 2,
    'value' => $lang_module['search_status_2']
);
$array_search_status[3] = array(
    'key' => 3,
    'value' => $lang_module['search_status_3']
);

$array_istype = array(
    '0' => $lang_module['istype_0'],
    '1' => $lang_module['istype_1'],
);

/**
 * nv_affiliate_number_format()
 *
 * @param mixed $number
 * @param integer $decimals
 * @return
 *
 */
function nv_affiliate_number_format($number, $decimals = 0)
{
    $str = number_format($number, $decimals, '.', ',');

    return $str;
}

function convert_number_to_string( $number )
{

    $hyphen = ' ';
    $conjunction = '  ';
    $separator = ' ';
    $negative = 'negative ';
    $decimal = ' point ';
    $dictionary = array(
        0 => 'Không',
        1 => 'Một',
        2 => 'Hai',
        3 => 'Ba',
        4 => 'Bốn',
        5 => 'Năm',
        6 => 'Sáu',
        7 => 'Bảy',
        8 => 'Tám',
        9 => 'Chín',
        10 => 'Mười',
        11 => 'Mười một',
        12 => 'Mười hai',
        13 => 'Mười ba',
        14 => 'Mười bốn',
        15 => 'Mười lăm',
        16 => 'Mười sáu',
        17 => 'Mười bảy',
        18 => 'Mười tám',
        19 => 'Mười chín',
        20 => 'Hai mươi',
        30 => 'Ba mươi',
        40 => 'Bốn mươi',
        50 => 'Năm mươi',
        60 => 'Sáu mươi',
        70 => 'Bảy mươi',
        80 => 'Tám mươi',
        90 => 'Chín mươi',
        100 => 'trăm',
        1000 => 'ngàn',
        1000000 => 'triệu',
        1000000000 => 'tỉ',
        1000000000000 => 'nghìn tỉ',
        1000000000000000 => 'ngàn triệu tỉ',
        1000000000000000000 => 'tỉ tỉ' );

    if( ! is_numeric( $number ) )
    {
        return false;
    }

    if( ( $number >= 0 && ( int )$number < 0 ) || ( int )$number < 0 - PHP_INT_MAX )
    {
        // overflow
        trigger_error( 'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, E_USER_WARNING );
        return false;
    }

    if( $number < 0 )
    {
        return $negative . convert_number_to_string( abs( $number ) );
    }

    $string = $fraction = null;

    if( strpos( $number, '.' ) !== false )
    {
        list( $number, $fraction ) = explode( '.', $number );
    }

    switch( true )
    {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens = ( ( int )( $number / 10 ) ) * 10;
            $units = $number % 10;
            $string = $dictionary[$tens];
            if( $units )
            {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if( $remainder )
            {
                $string .= $conjunction . convert_number_to_string( $remainder );
            }
            break;
        default:
            $baseUnit = pow( 1000, floor( log( $number, 1000 ) ) );
            $numBaseUnits = ( int )( $number / $baseUnit );
            $remainder = $number % $baseUnit;
            $string = convert_number_to_string( $numBaseUnits ) . ' ' . $dictionary[$baseUnit];
            if( $remainder )
            {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_string( $remainder );
            }
            break;
    }

    if( null !== $fraction && is_numeric( $fraction ) )
    {
        $string .= $decimal;
        $words = array();
        foreach( str_split( ( string )$fraction ) as $number )
        {
            $words[] = $dictionary[$number];
        }
        $string .= implode( ' ', $words );
    }

    return $string . ' đồng';
}



/**
 * nv_fix_users_order()
 *
 * @param integer $parentid
 * @param integer $order
 * @param integer $lev
 * @return
 */
function nv_fix_users_order($parentid = 0, $order = 0, $lev = 0)
{
    global $db, $db_config, $module_data;

    $sql = 'SELECT userid, parentid FROM ' . $db_config['prefix'] . '_' . $module_data . '_users WHERE parentid=' . $parentid . ' ORDER BY weight ASC';

    $result = $db->query($sql);
    $array_cat_order = array();
    while ($row = $result->fetch()) {
        $array_cat_order[] = $row['userid'];
    }
    $result->closeCursor();
    $weight = 0;
    if ($parentid > 0) {
        ++$lev;
    } else {
        $lev = 0;
    }

    foreach ($array_cat_order as $catid_i) {
        ++$order;
        ++$weight;
        $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_users SET weight=' . $weight . ', sort=' . $order . ', lev=' . $lev . ' WHERE userid=' . intval($catid_i);
        $db->query($sql);

        if( $parentid > 0){

            list( $listparentid ) = $db->query('SELECT listparentid FROM ' . $db_config['prefix'] . '_' . $module_data . '_users WHERE userid=' . $parentid )->fetch(3);

            $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_users SET ';
            if (!empty( $listparentid ) ) {
                $sql .= "listparentid='" . $listparentid  . ',' . $parentid . "'";
            } else {
                $sql .= "listparentid='" . $parentid . "'";
            }
            $sql .= ' WHERE userid=' . intval($catid_i);
            $db->query($sql);
        }
        $order = nv_fix_users_order($catid_i, $order, $lev);
    }
    $numsubcat = $weight;
    if ($parentid > 0) {
        $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_users SET numsubcat=' . $numsubcat;
        if ($numsubcat == 0) {
            $sql .= ",subcatid=''";
        } else {
            $sql .= ",subcatid='" . implode(',', $array_cat_order) . "'";
        }
        $sql .= ' WHERE userid=' . intval($parentid);
        $db->query($sql);

    }
    return $order;
}


/**
 * nv_fix_users_order()
 *
 * @param integer $parentid
 * @param integer $order
 * @param integer $lev
 * @return
 */
function nv_fix_users_parent_order($parentid = 0, $order = 0, $lev = 0)
{
    global $db, $db_config, $module_data;

    $sql = 'SELECT userid, parentid, listparentid, provinceid FROM ' . $db_config['prefix'] . '_' . $module_data . '_users WHERE parentid=' . $parentid . ' ORDER BY weight ASC';

    $result = $db->query($sql);
    $array_cat_order = array();
    $array_cat_listparentid = array();
    while ($row = $result->fetch()) {
        $array_cat_order[] = $row['userid'];
        $array_cat_listparentid[$row['userid']] = $row['listparentid'];
    }
    $result->closeCursor();
    $weight = 0;
    if ($parentid > 0) {
        ++$lev;
    } else {
        $lev = 0;
    }

    foreach ($array_cat_order as $catid_i) {
        ++$order;
        ++$weight;
        $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_users SET weight=' . $weight . ', sort=' . $order . ', lev=' . $lev . ' WHERE userid=' . intval($catid_i);
        $db->query($sql);

        if( $parentid > 0){
            $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_users SET ';
            if (!empty( $array_cat_listparentid[$catid_i] ) ) {
                if( $array_cat_listparentid[$catid_i] != $parentid ){
                    $sql .= "listparentid='" . $array_cat_listparentid[$catid_i]  . ',' . $parentid . "'";
                }else{
                    $sql .= "listparentid='" . $parentid . "'";
                }
            } else {
                $sql .= "listparentid='" . $parentid . "'";
            }
            $sql .= ' WHERE userid=' . intval($catid_i);
            $db->query($sql);
        }

        $order = nv_fix_users_order($catid_i, $order, $lev);
    }
    $numsubcat = $weight;
    if ($parentid > 0) {
        $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_users SET numsubcat=' . $numsubcat;
        if ($numsubcat == 0) {
            $sql .= ",subcatid=''";
        } else {
            $sql .= ",subcatid='" . implode(',', $array_cat_order) . "'";
        }
        $sql .= ' WHERE userid=' . intval($parentid);
        $db->query($sql);

    }
    return $order;
}
/**
 * nv_get_price()
 *
 * @param mixed $price
 * @param mixed $percent_sale
 * @param mixed $number
 * @param mixed $per_pro
 * @return
 */
function nv_get_price_agency($price, $percent_sale = 0, $number = 1, $per_pro = false )
{
    $price_agency = floor( ($price - $price * $percent_sale / 100)/1000 );
    if( $per_pro ){
        $price_agency = $price_agency * 1000 * $number;
    }else{
        $price_agency = $price_agency * 1000;
    }
    $return = $price_agency;// Giá nhap cho agency
    return $return;
}


function nv_Province()
{
    global $db, $module_data;

    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_province WHERE status=1 ORDER BY weight ASC";
    $result = $db->query( $sql );
    $list = array();
    while( $row = $result->fetch() )
    {
        $list[$row['id']] = array( //
            'id' => $row['id'], //
            'title' => $row['title'], //
            'weight' => ( int )$row['weight'] //
        );
    }

    return $list;
}


function nv_District( $provinceid = 0 )
{
    global $db, $module_data;

    if( $provinceid == 0 ){
        $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_district WHERE status=1 ORDER BY weight ASC";
    }else{
        $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_district WHERE status=1 AND idprovince= " . $provinceid . " ORDER BY weight ASC";
    }

    $result = $db->query( $sql );
    $list = array();
    while( $row = $result->fetch() )
    {
        $list[$row['id']] = array( //
            'id' => $row['id'], //
            'idprovince' => $row['idprovince'], //
            'title' => $row['title'], //
            'weight' => ( int )$row['weight'] //
        );
    }
    return $list;
}

function check_phone_avaible( $string ){
    $string = str_replace(array('-', '.', ' '), '', $string);
    return $string;//tam thoi k check sdt
    if (!preg_match('/^(01[2689]|03|05|07|08|09)[0-9]{8}$/', $string)){
        return 0;
    }
    return $string;

}