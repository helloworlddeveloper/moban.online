<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_MOD_AFFILIATE' ) ) die( 'Stop!!!' );

if( ! defined( 'NV_IS_USER' ) )
{
    $redirect = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=maps', true );
    Header( 'Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=login&nv_redirect=' . nv_base64_encode( $redirect ) );
    die();
}

function nv_viewdirtree_genealogy( $data, $config_data )
{
    global $db_config, $module_data, $db, $module_info, $module_file, $array_agency, $array_possiton, $global_config, $module_name, $lang_module, $user_info, $array_province;

    $xtpl = new XTemplate( 'maps.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );

    $link_warehouse = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=sm&' . NV_OP_VARIABLE . '=warehouse_logs&userid=';
    $link_doanhso = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=sm&' . NV_OP_VARIABLE . '=doanhso&userid=';
    $link_affiliate = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=';

    $sql = 'SELECT t1.*, t2.code, t2.possitonid, t2.agencyid, t2.provinceid FROM ' . NV_USERS_GLOBALTABLE . ' AS t1 INNER JOIN ' . $db_config['prefix'] . '_' . $module_data . '_users AS t2 ON t1.userid=t2.userid WHERE t1.userid = ' . intval( $data['userid'] );
    $result = $db->query( $sql );
    $row = $result->fetch();
    if( ! empty( $data['data'] ) )
    {
        $content_i = '';
        foreach( $data['data'] as $data_i )
        {
            $content_i .= nv_viewdirtree_genealogy( $data_i, $config_data );
        }
        $xtpl->assign( "TREE_LOOP", $content_i );
        $xtpl->parse( 'tree.loop.tree' );
        $xtpl->parse( 'tree.loop' );
    }
    $row['lang_edit'] = $lang_module['edit'];
    if( isset( $row['possitonid'] ) && $row['possitonid'] == 0){
        $row['link_edit'] = $link_affiliate . 'register&userid=' . $row['userid'] . '&checkss=' . md5($row['userid'] . $global_config['sitekey'] . session_id());
        $row['link_warehouse'] = $link_warehouse . $row['userid'] . '&checkss=' . md5($row['userid'] . $global_config['sitekey'] . session_id());
    }else{
        if( $user_info['userid'] == $row['userid'] ){
            $row['link_edit'] = $link_affiliate . 'editinfo';
        }else{
            $row['link_edit'] = $row['lang_edit'] = '';
        }

        $row['link_warehouse'] = $link_doanhso . $row['userid'] . '&checkss=' . md5($row['userid'] . $global_config['sitekey'] . session_id());
    }

    $row['postion'] = ( $row['agencyid']> 0 && isset( $array_agency[$row['agencyid']] ) )? $array_agency[$row['agencyid']]['title'] : $array_possiton[$row['possitonid']]['title'];
    $xtpl->assign( "total_sub", count($data['data']) );
    $row['fullname'] = nv_show_name_user( $row['first_name'] , $row['last_name'] , $row['username']  );
    $row['province_name'] = isset( $array_province[$row['provinceid']] )? $array_province[$row['provinceid']]['title'] : '';

    $xtpl->assign( "TREE", $row );
    $xtpl->parse( 'tree' );
    $content_i = $xtpl->text( 'tree' );
    return $content_i;
}

function get_parent_nodes_shops( $data_parent_node, $level )
{
    global $db_config, $module_data, $db;
    $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_users status=1 AND WHERE userid IN( $subcatid )';
    $array_data = array();
    $res = $db->query( $sql );
    $data_parent_node[$level] = $res->fetch();

    if( $data_parent_node[$level]['parent_node'] != 0 && $level > 0 )
    {
        $parent_node = $data_parent_node[$level]['parent_node'];
        $data_parent_node[$level]['data'] = get_parent_nodes_shops( $data_parent_node, $level );
        $level--;
    }
    return $data_parent_node;
}

$page_title = $lang_module['maps'];
$array_search['user_code'] = $nv_Request->get_title('user_code', 'get', '');
$array_search['province'] = $nv_Request->get_int('province', 'get', 0);


$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'TEMPLATE', $module_info['template'] );
$xtpl->assign( 'module_file', $module_file );
$xtpl->assign( 'user_info', $user_info );
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('SEARCH', $array_search);

foreach ( $array_province as $province){
    $province['sl'] = ( $province['id'] == $array_search['province'] )? ' selected=selected' : '';
    $xtpl->assign( 'PROVINCE', $province );
    $xtpl->parse( 'search.province' );
}
$xtpl->parse( 'search' );
$contents = $xtpl->text( 'search' );
$array_nodes = array();

//$sql .= " AND (listparentid LIKE '%," . $array_search['province'] . "' OR listparentid LIKE '%" . $array_search['province'] . ",%' OR listparentid LIKE '%," . $array_search['province'] . ",%' OR listparentid =" . $array_search['province'] . ")";
$admin_id_explode = explode(',', $site_mods[$module_name]['admins']);
if( defined('NV_IS_SPADMIN') || defined('NV_IS_ADMIN') && in_array( $user_info['userid'], $admin_id_explode ) ){

    if( $array_search['province'] > 0 || !empty( $array_search['user_code'] ) ){
        $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_users WHERE status=1';
        if( $array_search['province'] > 0 ){
            $sql .= ' AND provinceid=' . $array_search['province'];
        }
        if( $array_search['user_code'] > 0 ){
            $sql .= ' AND code=' . $db->quote( $array_search['user_code'] );
        }
    }
    else{
        $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_users WHERE status=1 AND parentid=0';
    }
    $res = $db->query( $sql );
    while ($array_data = $res->fetch()){
        $data_tree[$array_data['userid']] = $array_data;
        $data_tree[$array_data['userid']]['data'] = array();
        $level = 1;
        if( $array_data['userid'] > 0 )
        {
            if( $array_data['numsubcat'] > 0){
               // $data_tree[$array_data['userid']]['data'] = get_sub_nodes_shops( $array_data['subcatid'] );
            }

            $config_data = $module_config[$module_name];
            $config_data['config_postion'] = unserialize( $config_data['config_postion'] );
            if( ! empty( $data_tree ) )
            {
                $content = '';
                foreach( $data_tree as $data )
                {
                    $content .= nv_viewdirtree_genealogy( $data, $config_data );
                    // die($content);
                }
                $xtpl->assign( 'DATATREE', $content );
                $xtpl->parse( 'main.foldertree' );
            }

            $xtpl->parse( 'main' );
            $contents .= $xtpl->text( 'main' );
        }

    }
}
else{
    if( $array_search['province'] > 0 || !empty( $array_search['user_code'] ) ){
        $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_users WHERE status=1';
        if( $array_search['province'] > 0 ){
            $sql .= ' AND provinceid=' . $array_search['province'];
        }
        if( $array_search['user_code'] > 0 ){
            $sql .= ' AND code=' . $db->quote( $array_search['user_code'] );
        }
    }
    else{
        $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_users WHERE status=1 AND userid=' . $user_info['userid'];
    }

    $res = $db->query( $sql );
    while( $array_data = $res->fetch() ){
		$listparentid = explode(',', $array_data['listparentid'] );
		if( in_array( $user_info['userid'], $listparentid ) ){
			$data_tree[$array_data['userid']] = $array_data;
			$data_tree[$array_data['userid']]['data'] = array();
			$level = 1;
		  
			if( $array_data['numsubcat'] > 0){
				//$data_tree[$array_data['userid']]['data'] = get_sub_nodes_shops( $array_data['subcatid'] );
			}

			$config_data = $module_config[$module_name];
			$config_data['config_postion'] = unserialize( $config_data['config_postion'] );
			if( ! empty( $data_tree ) )
			{
				$content = '';
				foreach( $data_tree as $data )
				{
					$content .= nv_viewdirtree_genealogy( $data, $config_data );
					// die($content);
				}
			}
		
		}
		
	}

    $xtpl->assign( 'DATATREE', $content );
			$xtpl->parse( 'main.foldertree' );
			$xtpl->parse( 'main' );
			$contents .= $xtpl->text( 'main' );
    
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
