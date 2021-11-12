<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Dec 3, 2010  11:11:28 AM 
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );



$allow_func = array( 
    'main', 'config','district','showlist','province'
);

function nv_Mien()
{
    global $db, $module_data;

    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_mien WHERE status=1 ORDER BY weight ASC";
   
    $result = $db->query( $sql );
    $list = array();
    while ( $row = $result->fetch() )
    {
        $list[$row['id']] = array( //
        	'id' => $row['id'],//        	
            'title' => $row['title'], //
            'alias' => $row['alias'], //
            'weight' => ( int )$row['weight'] //
        );
    }

    return $list;
}

function fix_mienWeight()
{
    global $db, $module_data;

    $sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_mien ORDER BY weight ASC";
    $result = $db->query( $sql );
    $weight = 0;
    while ( $row =  $result->fetch() )
    {
        $weight++;
        $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_mien SET weight=" . $weight . " WHERE id=" . $row['id'];
        $db->query( $query );
    }
}

function nv_Province()
{
    global $db, $module_data;

    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_province WHERE status=1 ORDER BY weight ASC";
    $result = $db->query( $sql );
    $list = array();
    while ( $row =  $result->fetch() )
    {
        $list[$row['id']] = array( //
        	'code' => $row['code'],
        	'idmien' => $row['idmien'],
            'title' => $row['title'], //
            'alias' => $row['alias'], //
            'weight' => ( int )$row['weight'] //
            );
    }

    return $list;
}

function fix_catWeight($mien)
{
    global $db, $module_data;

    $sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_province WHERE idmien= ".$mien." ORDER BY weight ASC";
    $result = $db->query( $sql );
    $weight = 0;
    while ( $row =  $result->fetch() )
    {
        $weight++;
        $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_province SET weight=" . $weight . " WHERE id=" . $row['id'];
        $db->query( $query );
    }
}

function nv_District()
{
    global $db, $module_data;

    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_district WHERE status=1 ORDER BY weight ASC";
    $result = $db->query( $sql );
    $list = array();
    while ( $row =  $result->fetch() )
    {
        $list[$row['id']] = array( //
        	'idprovince' => $row['idprovince'],//
            'title' => $row['title'], //
            'alias' => $row['alias'], //
            'weight' => ( int )$row['weight'] //
            );
    }

    return $list;
}

function fix_DisWeight($pro)
{
    global $db, $module_data;

    $sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_district WHERE idprovince=".$pro." ORDER BY weight ASC";
    $result = $db->query( $sql );
    $weight = 0;
    while ( $row =  $result->fetch() )
    {
        $weight++;
        $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_district SET weight=" . $weight . " WHERE id=" . $row['id']." AND idprovince=".$pro;
        $db->query( $query );
    }
}

function fix_wardWeight($dis)
{
    global $db, $module_data;

    $sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_ward WHERE iddistrict=".$dis." ORDER BY weight ASC";
    $result = $db->query( $sql );
    $weight = 0;
    while ( $row =  $result->fetch() )
    {
        $weight++;
        $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_ward SET weight=" . $weight . " WHERE id=" . $row['id']." AND iddistrict=".$dis;
        $db->query( $query );
    }
}

function fix_streetWeight($dis)
{
    global $db, $module_data;

    $sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_street WHERE iddistrict=".$dis." ORDER BY weight ASC";
    $result = $db->query( $sql );
    $weight = 0;
    while ( $row =  $result->fetch() )
    {
        $weight++;
        $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_street SET weight=" . $weight . " WHERE id=" . $row['id']." AND iddistrict=".$dis;
        $db->query( $query );
    }
}

function nv_location_add_from_temp ( $arr_listupdate, $loaidiadiem )
{
    global $db, $module_data;
    if( $loaidiadiem == 1 ){
       $table = "ward";
       $row = "xa";
       $row1 = "xakhac";
    }else{
         $table = "street";
         $row = "duong";
         $row1 = "duongkhac";
    }
    $listid_temp_location = "";
    foreach( $arr_listupdate as $arr_listupdate_i ){
        
        $module = $arr_listupdate_i['module'];
        $listid_temp_location .= $arr_listupdate_i['id'].",";
        $sql = "SELECT weight FROM " . NV_PREFIXLANG . "_" . $module_data . "_".$table." WHERE iddistrict= " . $arr_listupdate_i['iddistrict'] . " ORDER BY weight DESC";
        $result = $db->query( $sql );
        list( $weight ) =  $result->fetch();
        $weight++;
        $query = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_".$table." VALUES (NULL, " . $arr_listupdate_i['idprovince'] . ", " . $arr_listupdate_i['iddistrict'] . "," . $db->dbescape( $arr_listupdate_i['title'] ) . "," . $db->dbescape( $arr_listupdate_i['alias'] ) . "," . $weight . ",1)";
        $id = $db->sql_query_insert_id( $query );
        if( $id > 0){
            if( $arr_listupdate_i['listidrow'] != "" ){
                $listid = explode(",",$arr_listupdate_i['listidrow']);
                $listid = implode(",", $listid);
                
                $query = "UPDATE " . NV_PREFIXLANG . "_".$module."_row SET ".$row."=" . $id . ", ".$row1." ='' WHERE id IN (" . $listid.")";

                $db->query( $query );
            }if( $arr_listupdate_i['listidtemp'] != "" ){
                $listid = explode(",",$arr_listupdate_i['listidtemp']);
                $listid = implode(",", $listid);
                
                $query = "UPDATE " . NV_PREFIXLANG . "_".$module."_temp SET ".$row."=" . $id . ", ".$row1." ='' WHERE id IN (" . $listid.")";
                $db->query( $query );
            }
        }
    }
    $listid_temp_location = substr( $listid_temp_location, 0, strlen($listid_temp_location)-1);
    $db->query( "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_".$table."_temp WHERE id IN (" . $listid_temp_location . ")");
    die("OK");
}
function nv_location_del_from_temp ( $arr_listupdate, $loaidiadiem )
{
    global $db, $module_data;
    $table = ( $loaidiadiem == 1) ? "ward" : "street";
    foreach( $arr_listupdate as $arr_listupdate_i ){
        
        $module = $arr_listupdate_i['module'];
        
        $a = intval($db->query( "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_".$table."_temp WHERE id =" . $arr_listupdate_i['id'] . ""));
        if( $a > 0 ){
            if( $arr_listupdate_i['listidtemp'] != "" ){
                $listid = explode(",",$arr_listupdate_i['listidtemp']);
                $listid = implode(",", $listid);
                $db->query( "DELETE FROM " . NV_PREFIXLANG . "_".$module."_temp WHERE id IN (" . $listid . ")");
            }if( $arr_listupdate_i['listidrow'] != "" ){
                $listid = explode(",",$arr_listupdate_i['listidrow']);
                $listid = implode(",", $listid);
                $db->query( "DELETE FROM " . NV_PREFIXLANG . "_".$module."_row WHERE id IN (" . $listid . ")");
            }
        }
    }    
    die("OK");
}

function nv_update_diaoc_row ( $arr_listupdate, $loaidiadiem )
{
    global $db, $module_data;
    if( $loaidiadiem == 1 ){
       $table = "ward";
       $row = "xa";
       $row1 = "xakhac";
    }else{
         $table = "street";
         $row = "duong";
         $row1 = "duongkhac";
    }
   foreach( $arr_listupdate as $arr_listupdate_i ){
        
        $module = $arr_listupdate_i['module'];
        
        $listid_temp_location .= $arr_listupdate_i['id'].",";
        $id = $arr_listupdate_i['diadiem'];
        
        if( $arr_listupdate_i['listidrow'] != "" ){
            $listid = explode(",",$arr_listupdate_i['listidrow']);
            $listid = implode(",", $listid);
            $query = "UPDATE " . NV_PREFIXLANG . "_".$module."_row SET ".$row."=" . $id . ", ".$row1." ='' WHERE id IN (" . $listid.")";
            $db->query( $query );
        }if( $arr_listupdate_i['listidtemp'] != "" ){
            $listid = explode(",",$arr_listupdate_i['listidtemp']);
            $listid = implode(",", $listid);
            
            $query = "UPDATE " . NV_PREFIXLANG . "_".$module."_temp SET ".$row."=" . $id . ", ".$row1." ='' WHERE id IN (" . $listid.")";
            $db->query( $query );
        }
        
    }
    $listid_temp_location = substr( $listid_temp_location, 0, strlen($listid_temp_location)-1);
    $db->query( "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_".$table."_temp WHERE id IN (" . $listid_temp_location . ")");

    die("OK");
}


function nv_update_diaoc_row_null_location ( $arr_listupdate, $loaidiadiem )
{
    global $db, $module_data;
    if( $loaidiadiem == 1 ){
       $table = "ward";
       $row = "xa";
       $row1 = "xakhac";
    }else{
         $table = "street";
         $row = "duong";
         $row1 = "duongkhac";
    }
   foreach( $arr_listupdate as $arr_listupdate_i ){
        
        $module = $arr_listupdate_i['module'];
        
        $listid_temp_location .= $arr_listupdate_i['id'].",";
        $id = $arr_listupdate_i['diadiem'];
        
        if( $arr_listupdate_i['listidrow'] != "" ){
            $listid = explode(",",$arr_listupdate_i['listidrow']);
            $listid = implode(",", $listid);
            $query = "UPDATE " . NV_PREFIXLANG . "_".$module."_row SET ".$row."=0, ".$row1." ='' WHERE id IN (" . $listid.")";
            $db->query( $query );
        }if( $arr_listupdate_i['listidtemp'] != "" ){
            $listid = explode(",",$arr_listupdate_i['listidtemp']);
            $listid = implode(",", $listid);
            
            $query = "UPDATE " . NV_PREFIXLANG . "_".$module."_temp SET ".$row."=0, ".$row1." ='' WHERE id IN (" . $listid.")";
            $db->query( $query );
        }
        
    }
    $listid_temp_location = substr( $listid_temp_location, 0, strlen($listid_temp_location)-1);
    $db->query( "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_".$table."_temp WHERE id IN (" . $listid_temp_location . ")");

    die("OK");
}

// tao bang co so du lieu cho cac tinh
function nv_create_table_diaoc_rows ( $catid )
{
    global $db, $module_name, $module_data;
    $db->query( "SET SQL_QUOTE_SHOW_CREATE = 1" );
    $result = $db->query( "SHOW CREATE TABLE " . NV_PREFIXLANG . "_diaoc_row" );
    $show =  $result->fetch();
    $db->sql_freeresult( $result );
    $show = preg_replace( '/(KEY[^\(]+)(\([^\)]+\))[\s\r\n\t]+(USING BTREE)/i', '\\1\\3 \\2', $show[1] );
    $sql = preg_replace( '/(default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP|DEFAULT CHARSET=\w+|COLLATE=\w+|character set \w+|collate \w+|AUTO_INCREMENT=\w+)/i', ' \\1', $show );
    //bang 1
    $sql_des = str_replace( NV_PREFIXLANG . "_diaoc_row", NV_PREFIXLANG . "_diaoc_def_" . $catid, $sql );
    $db->query( $sql_des );
    $db->query( "TRUNCATE TABLE " . NV_PREFIXLANG . "_diaoc_def_" . $catid . "" );
    //bang 2
    $sql_vip = str_replace( NV_PREFIXLANG . "_diaoc_row", NV_PREFIXLANG . "_diaoc_vip_" . $catid, $sql );
    $db->query( $sql_vip );
    $db->query( "TRUNCATE TABLE " . NV_PREFIXLANG . "_diaoc_vip_" . $catid . "" );
}

define( 'NV_IS_FILE_ADMIN', true );

?>