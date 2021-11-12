<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Mr.Thang (kid.apt@gmail.com)
 * @Copyright (C) 2017 Mr.Thang. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10-01-2017 20:08
 */

if( ! defined( 'NV_IS_MESSAGE_ADMIN' ) )
    die( 'Stop!!!' );

$page_title = $lang_module['add_notification'];
$array = array();
$error = "";

$groups_list = nv_groups_list();
$array['id'] = $nv_Request->get_int( 'id', 'get', 0 );

if( $nv_Request->isset_request( 'submit', 'post' ) )
{
    $array['id'] = $nv_Request->get_int( 'id', 'post', 0 );
    $array['message'] = $nv_Request->get_title( 'message', 'post', '' );
    $array['description'] = $nv_Request->get_title( 'description', 'post', '' );
    $array['url'] = $nv_Request->get_title( 'url', 'post', '' );
    $array['icon'] = $nv_Request->get_title( 'icon', 'post', '' );
    $array['author'] = $nv_Request->get_title( 'author', 'post', '' );
    
    $publ_date = $nv_Request->get_title('addtime', 'post', '');

    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $publ_date, $m)) {
        $phour = $nv_Request->get_int('phour', 'post', 0);
        $pmin = $nv_Request->get_int('pmin', 'post', 0);
        $array['addtime'] = mktime($phour, $pmin, 0, $m[2], $m[1], $m[3]);
    } else {
        $array['addtime'] = NV_CURRENTTIME;
    }
    
    $array['showview'] = $nv_Request->get_int( 'showview', 'post', 0 );
    $array['status'] = $nv_Request->get_int( 'status', 'post', 0 );
    $_groups_view = $nv_Request->get_array( 'allowed_view', 'post', array() );
    
    $array['allowed_view'] = ! empty( $_groups_view ) ? implode( ',', nv_groups_post_message( array_intersect( $_groups_view, array_keys( $array_agency ) ) ) ) : '';
    if( empty( $array['message'] ) )
    {
        $error = $lang_module['notification_error_message'];
    }
    else
    {
        if( $array['id'] == 0 )
        {
            $_sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . ' (message, description, url, icon, author, adminid_send, addtime, groupsend, status) 
            VALUES (:message, :description, :url, :icon, :author, :adminid_send, :addtime, :groupsend, :status)';
            $data_insert = array();
            $data_insert['message'] = $array['message'];
            $data_insert['description'] = $array['description'];
            $data_insert['url'] = $array['url'];
            $data_insert['icon'] = $array['icon'];
            $data_insert['author'] = $array['author'];
            $data_insert['adminid_send'] = $admin_info['userid'];
            $data_insert['addtime'] = $array['addtime'];
            $data_insert['groupsend'] = $array['allowed_view'];
            $data_insert['status'] = $array['status'];

            $new_id = $db->insert_id($_sql, 'id', $data_insert);

            if( $new_id > 0 )
            {
                nvUpdatemsQueue( $new_id, 1, 1 );
                nv_insert_logs( NV_LANG_DATA, $module_name, 'addnew_notification', $lang_module['addnew_notification'] . ': ' . $array['message'], $admin_info['userid'] );
                Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name );
                exit();
            }
            else
            {
                $error = $lang_module['notification_error_save'];
            }
        }
        else
        {
            $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . " SET 
				message=" . $db->quote( $array['message'] ) . ", 
				description=" . $db->quote( $array['description'] ) . ",
				url=" . $db->quote( $array['url'] ) . ", 
				icon=" . $db->quote( $array['icon'] ) . ", 				 
				author=" . $db->quote( $array['author'] ) . ",
                addtime=" . $array['addtime'] . ", 
                groupsend=" . $db->quote( $array['allowed_view'] ) . ",
                status=" . intval( $array['status'] ) . " WHERE id=" . $array['id'];
            if( ! $db->query( $sql ) )
            {
                $error = $lang_module['notification_error_save'];
            }
            else
            {
                nvUpdatemsQueue( $array['id'], 1, 0 );
                nv_insert_logs( NV_LANG_DATA, $module_name, 'update_notification', $lang_module['update_notification'] . $array['id'] . '-' . $array['message'], $admin_info['userid'] );
                Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name );
                exit();
            }
        }

    }
}
else
{
    if( $array['id'] > 0 )
    {
        $query = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id=" . $array['id'];
        $result = $db->query( $query );
        $numrows = $result->rowCount();
        if( $numrows != 1 )
        {
            Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name );
            exit();
        }

        $array = $result->fetch();
    }
    else
    {
        $array['showview'] = $array['status'] = 1;
        $array['allowed_view'] = '6';
        $array['addtime'] = NV_CURRENTTIME;
        $array['author'] = '[SYSTEM]';
    }

}

$xtpl = new XTemplate( $op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'FORM_CATION', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;add=1" );
$xtpl->assign( 'UPLOADS_DIR', NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name );

if( ! empty( $error ) )
{
    $xtpl->assign( 'ERROR', $error );
    $xtpl->parse( 'main.error' );
}
$tdate = date('H|i', $array['addtime']);
$array['addtime'] = date('d/m/Y', $array['addtime']);
$xtpl->assign( 'DATA', $array );

list($phour, $pmin) = explode('|', $tdate);

$select = '';
for ($i = 0; $i <= 23; ++$i) {
    $select .= "<option value=\"" . $i . "\"" . (($i == $phour) ? ' selected="selected"' : '') . ">" . str_pad($i, 2, "0", STR_PAD_LEFT) . "</option>\n";
}
$xtpl->assign('phour', $select);
$select = '';
for ($i = 0; $i < 60; ++$i) {
    $select .= "<option value=\"" . $i . "\"" . (($i == $pmin) ? ' selected="selected"' : '') . ">" . str_pad($i, 2, "0", STR_PAD_LEFT) . "</option>\n";
}
$xtpl->assign('pmin', $select);

$allowed_view = explode( ',', $array['groupsend'] );
foreach( $array_agency as $_group_id => $data )
{
    $xtpl->assign( 'ALLOWED_VIEW', array(
        'value' => $_group_id,
        'checked' => in_array( $_group_id, $allowed_view ) ? ' checked="checked"' : '',
        'title' => $data['title'] ) );
    $xtpl->parse( 'main.allowed_view' );
}

$array_notification_status = array( 0 => $lang_module['notification_status_0'], 1 => $lang_module['notification_status_1'] );
foreach( $array_notification_status as $key => $title )
{
    $xtpl->assign( 'STATUS', array( //
        'key' => $key, //
        'title' => $title, //
        'selected' => ( $array['status'] == $key ) ? " selected=\"selected\"" : "" ) );
    $xtpl->parse( 'main.status' );
}

if (!empty($array_personal_messenger)) {
    foreach ($array_personal_messenger as $index => $value) {
        $xtpl->assign('PERSONAL', array(
            'index' => $index,
            'value' => $value
        ));
        $xtpl->parse('main.personal');
    }
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
exit();

?>