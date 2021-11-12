<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jan 17, 2011 11:34:27 AM
 */

if (! defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (! nv_function_exists('nv_block_mypham_gmwhite_hotrodaily')) {
    function nv_block_mypham_gmwhite_hotrodaily_config( $module, $data_block)
    {
        $html = '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">Image 1:</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_image1" value="' . $data_block['image1'] . '"><span></span></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">Title 1:</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_title1" value="' . $data_block['title1'] . '"><span></span></div>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">Image 2:</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_image2" value="' . $data_block['image2'] . '"><span></span></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">Title 2:</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_title2" value="' . $data_block['title2'] . '"><span></span></div>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">Image 3:</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_image3" value="' . $data_block['image3'] . '"><span></span></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">Title 3:</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_title3" value="' . $data_block['title3'] . '"><span></span></div>';
        $html .= '</div>';
        return $html;
    }

    function nv_block_mypham_gmwhite_hotrodaily_config_submit()
    {
        global $nv_Request;

        $return = array();
        $return['error'] = array();
        $return['config']['image1'] = $nv_Request->get_title('config_image1', 'post');
        $return['config']['title1'] = $nv_Request->get_title('config_title1', 'post');

        $return['config']['image2'] = $nv_Request->get_title('config_image2', 'post');
        $return['config']['title2'] = $nv_Request->get_title('config_title2', 'post');

        $return['config']['image3'] = $nv_Request->get_title('config_image3', 'post');
        $return['config']['title3'] = $nv_Request->get_title('config_title3', 'post');
        return $return;
    }

    /**
     * nv_menu_theme_default_footer()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_block_mypham_gmwhite_hotrodaily($block_config)
    {
        global $global_config, $lang_global, $blockID;

        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/blocks/global.block_gmwhite-hotrodaily.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/blocks/global.block_gmwhite-hotrodaily.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }


        $xtpl = new XTemplate('global.block_gmwhite-hotrodaily.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/blocks');
        $xtpl->assign('LANG', $lang_global);
        $xtpl->assign('BLOCKID', $blockID);
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
        $xtpl->assign('DATA', $block_config);

        $xtpl->parse('main');
        return $xtpl->text('main');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_mypham_gmwhite_hotrodaily($block_config);
}
