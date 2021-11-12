<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/9/2010 23:25
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (!nv_function_exists('nv_get_link_affiliate')) {
    /**
     * nv_block_config_get_link_affiliate()
     *
     * @param mixed $module
     * @param mixed $data_block
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_get_link_affiliate($module, $data_block, $lang_block)
    {
        $data_block['showboxlink'] = ( $data_block['showboxlink'] == 1 )? ' checked=checked' : '';
        $data_block['showqr'] = ( $data_block['showqr'] == 1 )? ' checked=checked' : '';

        $html = '';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_block['showqr'] . ':</label>';
        $html .= '<div class="col-sm-18"><input type="checkbox" class="form-control" name="config_showqr"' . $data_block['showqr'] . ' value="1"/></div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_block['showboxlink'] . ':</label>';
        $html .= '<div class="col-sm-18"><input type="checkbox" class="form-control" name="config_showboxlink"' . $data_block['showboxlink'] . ' value="1"/></div>';
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    /**
     * nv_block_config_news_category_submit()
     *
     * @param mixed $module
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_get_link_affiliate_submit($module, $lang_block)
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['showqr'] = $nv_Request->get_int('config_showqr', 'post', 0);
        $return['config']['showboxlink'] = $nv_Request->get_int('config_showboxlink', 'post', 0);
        return $return;
    }

    /**
     * nv_news_category()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_get_link_affiliate($block_config, $userid)
    {
        global $lang_module, $global_config, $client_info;

        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/affiliate/block_get_link_affiliate.tpl')) {
            $block_theme = $global_config['module_theme'];
        } else {
            $block_theme = 'default';
        }

        $xtpl = new XTemplate('block_get_link_affiliate.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/affiliate');
        $xtpl->assign('LANG', $lang_module);
        $url_affiliate = $client_info['selfurl'] . '?ref=' . $userid;
        if( $block_config['showqr'] == 1 ){
            $xtpl->assign('QRCODE', NV_BASE_SITEURL . 'index.php?second=qr&u=' . $url_affiliate);
            $xtpl->parse('main.showqr');
        }
        if( $block_config['showboxlink'] == 1 ){
            $xtpl->assign('BOXURL', $url_affiliate);
            $xtpl->parse('main.showboxlink');
        }

            $xtpl->parse('main');
            return $xtpl->text('main');

    }
}

if (defined('NV_SYSTEM')) {
    global $db_config, $site_mods, $user_info;
    if( !defined('NV_IS_USER')){
        return '';
    }
    $module = $block_config['module'];
    if (isset($site_mods[$module])) {
        $module_array_cat = array();
        $userid = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_users WHERE userid=' . $user_info['userid'])->fetchColumn();
        if( $userid == 0){
            return '';
        }else{
            $content = nv_get_link_affiliate($block_config, $user_info['userid']);
        }
    }
}
