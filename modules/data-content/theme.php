<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 19 Jun 2014 09:53:21 GMT
 */

if ( ! defined( 'NV_IS_MOD_SLIDE' ) ) die( 'Stop!!!' );


/**
 * nv_data_content_main()
 *
 * @param mixed $global_array_cat
 * @return
 */
function nv_data_content_main($global_array_cat)
{
    global $lang_module, $module_info;

    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);

    if (!empty($global_array_cat)) {
       foreach ( $global_array_cat as $cat){
           if( $cat['parentid'] == 0 ){
               $xtpl->assign('CAT', $cat);
               $xtpl->parse('main.cat.loop');
           }
       }
        $xtpl->parse('main.cat');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * viewcat_page()
 *
 * @param mixed $array_catpage
 * @param mixed $generate_page
 * @return
 */
function viewcat_page( $array_catpage, $generate_page, $cattype, $stt)
{
    global $lang_module, $module_info, $module_file, $module_name, $global_array_cat, $catid, $is_mobile;

    $tpl_show = 'viewcat.tpl';
    if( $is_mobile ){
        $tpl_show = 'viewcat_mobile.tpl';
    }
    $xtpl = new XTemplate($tpl_show, NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('TEMPLATE', $module_info['template']);
    $xtpl->assign('MODULE_FILE', $module_file);
    $xtpl->assign('send_data', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=content&add=1' );
    if( $global_array_cat[$catid]['numsubcat'] > 0 ){
        foreach ( $global_array_cat as $data_cat ){
            if( $data_cat['parentid'] == $catid ){
                $xtpl->assign('CAT', $data_cat);
                $xtpl->parse('main.cat.loop');
            }
        }
        $xtpl->parse('main.cat');
    }
    if (!empty($array_catpage)) {
        
        foreach ( $array_catpage as $catpage){
            $catpage['stt'] = $stt++;
            $catpage['bodytext'] = nv_clean60( strip_tags( $catpage['bodytext'] ), 200);
            if( !empty( $catpage['link'] ) ) {

                preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $catpage['link'], $matches);
                if(!empty( $matches )){
                    $xtpl->assign('VIDEOID', $matches[0]);
                    $xtpl->parse('main.' . $cattype . '.loop.iframe');
                }
            }
            $catpage['link_detail'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=detail/' . strtolower( change_alias( $catpage['title'] ) ) . '-' . $catpage['id'] . $global_config['rewrite_exturl'];
            $xtpl->assign('CAT', $catpage);
            if( empty( $catpage['link'] ) ){
                $xtpl->parse('main.' . $cattype . '.loop.nolink');
            }else{
                $xtpl->parse('main.' . $cattype . '.loop.havelink');
            }
            $xtpl->parse('main.' . $cattype . '.loop');
        }
        if( !empty( $generate_page )){
            $xtpl->assign('PAGE', $generate_page);
            $xtpl->parse('main.' . $cattype . '.generate_page');
        }
        $xtpl->parse('main.'. $cattype);
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

function detail_page( $data_content ){
  global $lang_module, $module_info, $module_file, $module_name, $global_array_cat, $catid;

    $xtpl = new XTemplate('detail.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('TEMPLATE', $module_info['template']);
    $xtpl->assign('MODULE_FILE', $module_file);
    $xtpl->assign('send_data', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=content&add=1' );
    $xtpl->assign('NEWSCHECKSS', $data_content['newscheckss']);
    if (!empty($data_content)) {
        $cattype = $global_array_cat[$data_content['catid']]['cattype'];
        $xtpl->assign('CAT', $data_content);
        if (!empty($data_content['link'])) {
            preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $data_content['link'], $matches);
            if (!empty($matches)) {
                $xtpl->assign('VIDEOID', $matches[0]);
                $xtpl->parse('main.' . $cattype . '.iframe');
            }
        }
        if (empty($catpage['link'])) {
            $xtpl->parse('main.' . $cattype . '.nolink');
        } else {
            $xtpl->parse('main.' . $cattype . '.havelink');
        }
        $xtpl->parse('main.' . $cattype);

        $xtpl->assign('LANGSTAR', $data_content['langstar']);
        $xtpl->assign('STRINGRATING', $data_content['stringrating']);
        $xtpl->assign('NUMBERRATING', $data_content['numberrating']);

    }

    $xtpl->parse('main');
    return $xtpl->text('main');  
}
