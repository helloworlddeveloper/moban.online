
<?php

/**
 * @Project NUKEVIET 4.x
 * @Author TDFOSS.,LTD (quanglh268@gmail.com)
 * @Copyright (C) 2018 TDFOSS.,LTD. All rights reserved
 * @Createdate Fri, 12 Jan 2018 02:38:03 GMT
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$row['id'] = $nv_Request->get_int('id', 'post,get', 0);
if ($nv_Request->isset_request('delete_id', 'get') and $nv_Request->isset_request('delete_checkss', 'get')) {
    $delcustomer = $nv_Request->get_int('delcustomer', 'get');
    $delcommodity = $nv_Request->get_int('delcommodity', 'get');
    $delcost = $nv_Request->get_int('delcost', 'get');

    $delete_checkss = $nv_Request->get_string('delete_checkss', 'get');
    if ($delcustomer > 0 and $delete_checkss == md5($delcustomer . NV_CACHE_PREFIX . $client_info['session_id'])) {
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_customer WHERE id = ' . $db->quote($delcustomer));
        $nv_Cache->delMod($module_name);
        Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&id=' . $row['id'] . '#tab_customer');
        die();
    }
    elseif ($delcommodity > 0 and $delete_checkss == md5($delcommodity . NV_CACHE_PREFIX . $client_info['session_id'])) {
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_commodity WHERE id = ' . $db->quote($delcommodity));
        $nv_Cache->delMod($module_name);
        Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&id=' . $row['id'] . '#tab_commodity');
        die();
    }
    elseif ($delcost > 0 and $delete_checkss == md5($delcost . NV_CACHE_PREFIX . $client_info['session_id'])) {
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cost WHERE id = ' . $db->quote($delcost));
        $nv_Cache->delMod($module_name);
        Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&id=' . $row['id'] . '#tab_cost');
        die();
    }
}

$row['customer'] = $nv_Request->get_int('customer', 'post,get', 0);
$row['commodity'] = $nv_Request->get_int('commodity', 'post,get', 0);
$row['cost'] = $nv_Request->get_int('cost', 'post,get', 0);

$id = $row['id'];
$current_url =  NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op .'&id='.$row['id'];
// var_dump($quang);
// die();

$iti_id = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $row['id'])->fetch();
if (!$iti_id) {
    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    die();
}

$iti_id['time_start'] = (!empty($row['time_start'])) ? '' : nv_date('H:i - d/m/Y', $iti_id['time_start']);
$iti_id['time_end'] = (!empty($row['time_end'])) ? '' : nv_date('H:i - d/m/Y', $iti_id['time_end']);

$array_vehicle_itinerary = array();
$_sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_vehicle WHERE id=' . $iti_id['vehicle'];
$_query = $db->query($_sql);
while ($_row = $_query->fetch()) {
    $array_vehicle_itinerary[$iti_id['id']] = $_row['car_number_plate'];
}

$array_location = array();
$_sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_location WHERE id=' . $iti_id['localtion_start'];
$_query = $db->query($_sql);
while ($_row = $_query->fetch()) {
    $array_location[$iti_id['id']] = $_row['title'];
}

$error = array();

if ( $nv_Request->isset_request( 'submit_customer', 'post' ) )
{
    $row['localtion_customer_start'] = $nv_Request->get_title( 'localtion_customer_start', 'post', '' );
    $row['localtion_customer_end'] = $nv_Request->get_title( 'localtion_customer_end', 'post', '' );
    $row['qty_customer'] = $nv_Request->get_int( 'qty_customer', 'post', 0 );
    $row['price_ticket'] = $nv_Request->get_title( 'price_ticket', 'post', '' );
    $row['id'] = $nv_Request->get_title( 'id', 'post', '' );
    $row['fullname'] = $nv_Request->get_title( 'fullname', 'post', '' );
    $row['mobile'] = $nv_Request->get_title( 'mobile', 'post', '' );
    if( empty( $error ) )
    {
        try
        {
            if( empty( $row['id'] ) )
            {

                $row['itinerary_id'] = $id;

                $stmt = $db->prepare( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_customer (fullname, mobile, itinerary_id, localtion_customer_start, localtion_customer_end, qty_customer, price_ticket) VALUES (:fullname, :mobile, :itinerary_id, :localtion_customer_start, :localtion_customer_end, :qty_customer, :price_ticket)' );

                $stmt->bindParam( ':itinerary_id', $row['itinerary_id'], PDO::PARAM_INT );

            }
            else
            {
                $stmt = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_customer SET fullname=:fullname, mobile=:mobile, localtion_customer_start = :localtion_customer_start, localtion_customer_end = :localtion_customer_end, qty_customer = :qty_customer, price_ticket = :price_ticket WHERE id=' . $row['id'] );
            }
            $stmt->bindParam( ':localtion_customer_start', $row['localtion_customer_start'], PDO::PARAM_INT );
            $stmt->bindParam( ':localtion_customer_end', $row['localtion_customer_end'], PDO::PARAM_INT );
            $stmt->bindParam( ':qty_customer', $row['qty_customer'], PDO::PARAM_INT );
            $stmt->bindParam( ':price_ticket', $row['price_ticket'], PDO::PARAM_INT );
            $stmt->bindParam( ':fullname', $row['fullname'], PDO::PARAM_STR );
            $stmt->bindParam( ':mobile', $row['mobile'], PDO::PARAM_STR );
            
            $exc = $stmt->execute();
            if( $exc )
            {
                $nv_Cache->delMod( $module_name );
                Header( 'Location: '.$current_url . '#tab_customer' );
                die();
            }
        }
        catch( PDOException $e )
        {
            trigger_error( $e->getMessage() );
            die( $e->getMessage() ); //Remove this line after checks finished
        }
    }
}
elseif ( $nv_Request->isset_request( 'submit_commodity', 'post' ) )
{
    $row['localtion_start'] = $nv_Request->get_title( 'localtion_start', 'post', '' );
    $row['localtion_end'] = $nv_Request->get_title( 'localtion_end', 'post', '' );
    $row['qty'] = $nv_Request->get_int( 'qty', 'post', 0 );
    $row['price_ship'] = $nv_Request->get_title( 'price_ship', 'post', '' );
    $row['commodity_name'] = $nv_Request->get_title( 'commodity_name', 'post', '' );
    $row['sender_name'] = $nv_Request->get_title( 'sender_name', 'post', '' );
    $row['sender_mobile'] = $nv_Request->get_title( 'sender_mobile', 'post', '' );
    $row['receiver_name'] = $nv_Request->get_title( 'receiver_name', 'post', '' );
    $row['receiver_mobile'] = $nv_Request->get_title( 'receiver_mobile', 'post', '' );
    $row['id'] = $nv_Request->get_int( 'id', 'post', '' );
    if( empty( $error ) )
    {
        try
        {
            if( empty( $row['id'] ) )
            {

                $row['itinerary_id'] = $id;

                $stmt = $db->prepare( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_commodity (commodity_name, sender_name, sender_mobile, receiver_name, receiver_mobile, itinerary_id, localtion_start, localtion_end, qty, price_ship) VALUES (:commodity_name, :sender_name, :sender_mobile, :receiver_name, :receiver_mobile, :itinerary_id, :localtion_start, :localtion_end, :qty, :price_ship)' );

                $stmt->bindParam( ':itinerary_id', $row['itinerary_id'], PDO::PARAM_INT );

            }
            else
            {
                $stmt = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_commodity SET commodity_name=:commodity_name, sender_name=:sender_name, sender_mobile=:sender_mobile, receiver_name=:receiver_name, receiver_mobile=:receiver_mobile, localtion_start = :localtion_start, localtion_end = :localtion_end, qty= :qty, price_ship = :price_ship WHERE id=' . $row['id'] );
            }
            $stmt->bindParam( ':commodity_name', $row['commodity_name'], PDO::PARAM_STR );
            $stmt->bindParam( ':sender_name', $row['sender_name'], PDO::PARAM_STR );
            $stmt->bindParam( ':sender_mobile', $row['sender_mobile'], PDO::PARAM_STR );
            $stmt->bindParam( ':receiver_name', $row['receiver_name'], PDO::PARAM_STR );
            $stmt->bindParam( ':receiver_mobile', $row['receiver_mobile'], PDO::PARAM_STR );
            $stmt->bindParam( ':localtion_start', $row['localtion_start'], PDO::PARAM_INT );
            $stmt->bindParam( ':localtion_end', $row['localtion_end'], PDO::PARAM_INT );
            $stmt->bindParam( ':qty', $row['qty'], PDO::PARAM_INT );
            $stmt->bindParam( ':price_ship', $row['price_ship'], PDO::PARAM_INT );

            $exc = $stmt->execute();
            if( $exc )
            {
                $nv_Cache->delMod( $module_name );
                Header( 'Location: '.$current_url . '#tab_commodity' );
                die();
            }
        }
        catch( PDOException $e )
        {
            trigger_error( $e->getMessage() );
            die( $e->getMessage() ); //Remove this line after checks finished
        }
    }
}
elseif ( $nv_Request->isset_request( 'submit_cost', 'post' ) )
{
    $row['costs_title'] = $nv_Request->get_title( 'costs_title', 'post', '' );
    $row['localtion_cost'] = $nv_Request->get_int( 'localtion_cost', 'post', 0 );
    $row['note'] = $nv_Request->get_title( 'note', 'post', '');
    $row['price'] = $nv_Request->get_title( 'price', 'post', '' );
    $row['id'] = $nv_Request->get_title( 'id', 'post', '' );
    if( empty( $error ) )
    {
        try
        {
            if( empty( $row['id'] ) )
            {
                $row['itinerary_id'] = $id;
                $stmt = $db->prepare( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_cost (cost_name, note, itinerary_id, localtion_cost, price) VALUES (:cost_name, :note, :itinerary_id, :localtion_cost, :price)' );
                $stmt->bindParam( ':itinerary_id', $row['itinerary_id'], PDO::PARAM_INT );

            }
            else
            {
                $stmt = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cost SET cost_name = :cost_name, note = :note, localtion_cost = :localtion_cost, price=:price WHERE id=' . $row['id'] );
            }
            $stmt->bindParam( ':cost_name', $row['costs_title'], PDO::PARAM_STR );
            $stmt->bindParam( ':note', $row['note'], PDO::PARAM_STR );
            $stmt->bindParam( ':localtion_cost', $row['localtion_cost'], PDO::PARAM_INT );
            $stmt->bindParam( ':price', $row['price'], PDO::PARAM_INT );

            $exc = $stmt->execute();
            if( $exc )
            {
                $nv_Cache->delMod( $module_name );
                Header( 'Location: '.$current_url . '#tab_cost' );
                die();
            }
        }
        catch( PDOException $e )
        {
            trigger_error( $e->getMessage() );
            die( $e->getMessage() ); //Remove this line after checks finished
        }
    }
}

$info_customer = $info_commodity = $info_cost = array();
$row['id'] = 0;
$row['localtion_customer_start'] = $row['localtion_customer_end'] = $row['localtion_start'] = $row['localtion_end'] = $row['localtion_cost'] = 0;
$row['qty_customer'] = 0;
$row['price_ticket'] = '';

if(!empty( $row['customer']) > 0 )
{
    $info_customer = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_customer WHERE id=' . $row['customer'] )->fetch();
    $row['localtion_customer_start'] = $info_customer['localtion_customer_start'];
    $row['localtion_customer_end'] = $info_customer['localtion_customer_end'];
    $row['fullname'] = $info_customer['fullname'];
    $row['mobile'] = $info_customer['mobile'];
    if( empty( $info_customer ) )
    {
        Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
        die();
    }
}
elseif(!empty( $row['commodity']) > 0 )
{
    $info_commodity = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_commodity WHERE id=' . $row['commodity'] )->fetch();
    $row['localtion_start'] = $info_commodity['localtion_start'];
    $row['localtion_end'] = $info_commodity['localtion_end'];
    $row['sender_name'] = $info_commodity['sender_name'];
    $row['sender_mobile'] = $info_commodity['sender_mobile'];
    $row['receiver_name'] = $info_commodity['receiver_name'];
    $row['receiver_mobile'] = $info_commodity['receiver_mobile'];
    $row['commodity_name'] = $info_commodity['commodity_name'];
    if( empty( $info_commodity ) )
    {
        Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
        die();
    }
}
elseif(!empty( $row['cost']) > 0 )
{
    $info_cost = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cost WHERE id=' . $row['cost'] )->fetch();
    $row['localtion_cost'] = $info_cost['localtion_cost'];
    if( empty( $info_cost ) )
    {
        Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
        die();
    }
}

$show_itinerary_id =  array();

$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('CUSTOMER', $info_customer);
$xtpl->assign('COMMODITY_EDIT', $info_commodity);
$xtpl->assign('COST_EDIT', $info_cost);

$xtpl->assign('VHC', $array_vehicle_itinerary[$iti_id['id']]);
$xtpl->assign('LCLST', $array_location[$iti_id['id']]);
$xtpl->assign('LCLEND', $array_location[$iti_id['id']]);
$xtpl->assign('ITI', $iti_id);


foreach( $show_itinerary_id as $value )
{
    $xtpl->assign('ITIVIEWID', $value);
    $xtpl->parse( 'main.view.loop' );
}

$stt = 1;
$total_price_ticket = $total_price_ships = $total_price_cost = 0;
$_query = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_customer WHERE itinerary_id=' . $id ) ;
while ($row = $_query->fetch()) {
    $row['stt'] = $stt++;
    $row['price_ticket_total'] = $row['price_ticket'] * $row['qty_customer'];
    $total_price_ticket = $total_price_ticket + $row['price_ticket_total'];
    $row['price_ticket'] = number_format( $row['price_ticket'], 0, '.', ',');
    $row['link_edit'] = $current_url . '&customer=' . $row['id'];
    $row['link_delete'] = $current_url . '&delete_id=1&delcustomer=' . $row['id'] . '&id=' . $id . '&delete_checkss=' . md5($row['id'] . NV_CACHE_PREFIX . $client_info['session_id']);
    $xtpl->assign('ITIVIEWID', $row);
    $xtpl->parse( 'main.customer.loop' );
}
$stt = 1;
$_query = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_commodity WHERE itinerary_id=' . $id ) ;
while ($row = $_query->fetch()) {

    $row['price_ship_total'] = $row['price_ship'] * $row['qty'];
    $total_price_ships = $total_price_ships + $row['price_ship_total'];
    $row['price_ship'] = number_format( $row['price_ship'], 0, '.', ',');
    $row['link_edit'] = $current_url . '&commodity=' . $row['id'];
    $row['link_delete'] = $current_url . '&delete_id=1&delcommodity=' . $row['id'] . '&id=' . $id . '&delete_checkss=' . md5($row['id'] . NV_CACHE_PREFIX . $client_info['session_id']);
    $row['stt'] = $stt++;
    $xtpl->assign('COMMODITY', $row);
    $xtpl->parse( 'main.commodity.loop' );
}
$stt = 1;
$_query = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cost WHERE itinerary_id=' . $id ) ;
while ($row = $_query->fetch()) {

    $total_price_cost = $total_price_cost + $row['price'];
    $row['price'] = number_format( $row['price'], 0, '.', ',');
    $row['stt'] = $stt++;
    $row['link_edit'] = $current_url . '&cost=' . $row['id'];
    $row['link_delete'] = $current_url . '&delete_id=1&delcost=' . $row['id'] . '&id=' . $id . '&delete_checkss=' . md5($row['id'] . NV_CACHE_PREFIX . $client_info['session_id']);
    $xtpl->assign('COST', $row);
    $xtpl->parse( 'main.cost.loop' );
}
$xtpl->parse( 'main.cost' );
$xtpl->parse( 'main.commodity' );
$xtpl->parse('main.customer');

$loinhuan = $total_price_ticket + $total_price_ships - $total_price_cost;
$xtpl->assign('customer_price', number_format( $total_price_ticket, 0, '.', ','));
$xtpl->assign('ship_price', number_format( $total_price_ships, 0, '.', ','));
$xtpl->assign('cost_price', number_format( $total_price_cost, 0, '.', ','));
$xtpl->assign('loinhuan', number_format( $loinhuan, 0, '.', ','));
$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['detail_of'] . ' ' . $iti_id['title_itinerary'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';