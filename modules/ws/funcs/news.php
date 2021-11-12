<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 19 Jun 2014 09:53:21 GMT
 */

if ($userid != '0')
{

    $per_page = 10;
    $page = $nv_Request->get_title('page', 'post', 1);
    $db_slave->sqlreset()
        ->select('COUNT(*)')
        ->from(NV_PREFIXLANG . '_news_rows t1, ' . NV_PREFIXLANG . '_news_block t2 , ' . NV_PREFIXLANG . '_news_detail t3 ')
        ->where('t1.id = t2.id AND t2.bid = 2 AND t1.status= 1');

    $num_items = $db_slave->query($db_slave->sql())
        ->fetchColumn();

    $db_slave->select('t1.id, t1.catid, t1.title, t1.homeimgfile, t1.homeimgthumb, t1.hometext, t1.publtime, t1.external_link, t1.hitstotal, t3.bodyhtml')
        ->order('t1.publtime DESC')
        ->limit($per_page)
        ->offset(($page - 1) * $per_page);

    if( $num_items / $per_page > $page ){
        $array = array( 'data' => array(), 'page' => $page + 1 );
    }else{
        $array = array( 'data' => array(), 'page' => '' );
    }

    $i = 0;
    $result = $db_slave->query($db_slave->sql());
    while ($row = $result->fetch()) {
        //$array['data'][$i] = array();
        $array['data'][$i]["id"] = $row['id'];
        $array['data'][$i]["catid"] = $row['catid'];
        $array['data'][$i]["title"] = $row['title'];

        if ($row['homeimgthumb'] == 1) {
            //image thumb
            $array['data'][$i]["homeimgfile"] = NV_MY_DOMAIN . NV_BASE_SITEURL . NV_FILES_DIR . '/news/' . $row['homeimgfile'];
        } elseif ($row['homeimgthumb'] == 2) {
            //image file
            $array['data'][$i]["homeimgfile"] = NV_MY_DOMAIN . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/news/' . $row['homeimgfile'];
        } elseif ($row['homeimgthumb'] == 3) {
            //image url
            $array['data'][$i]["homeimgfile"] = $row['homeimgfile'];
        }else {
            $array['data'][$i]["homeimgfile"] = '';
        }

        $array['data'][$i]["homeimgthumb"] = $row['homeimgthumb'];
        $array['data'][$i]["hometext"] = $row['hometext'];
        $array['data'][$i]["publtime"] = date('d/m/Y', $row['publtime'] );
        $array['data'][$i]["numview"] = $row['hitstotal'];
        $array['data'][$i]["detailcontent"] = $row['bodyhtml'];
        $array['data'][$i]["external_link"] = $row['external_link'];
        $i++;
    }

    echo json_encode($array);
}
else
{
    echo json_encode(array()); 
}