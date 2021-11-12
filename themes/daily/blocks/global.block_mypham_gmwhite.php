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

if (! nv_function_exists('nv_block_mypham_gmwhite')) {
    function nv_block_mypham_gmwhite_config( $module, $data_block, $lang_block)
    {
        global $lang_global;

        if (defined('NV_EDITOR')) {
            require NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
        }

        $htmlcontent = htmlspecialchars(nv_editor_br2nl($data_block['htmlcontent']));
        if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
            $html_editor = nv_aleditor('htmlcontent', '100%', '150px', $htmlcontent);
        } else {
            $html_editor = '<textarea style="width: 100%" name="htmlcontent" id="htmlcontent" cols="20" rows="8">' . $htmlcontent . '</textarea>';
        }

        $html = '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_global['browse_image'] . ':</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_image" value="' . $data_block['image'] . '"><span></span></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">'. $html_editor . '<span></span></div>';
        $html .= '</div>';


        return $html;
    }

    function nv_block_mypham_gmwhite_config_submit()
    {
        global $nv_Request;

        $return = array();
        $return['error'] = array();
        $return['config']['image'] = $nv_Request->get_title('config_image', 'post');

        $htmlcontent = $nv_Request->get_editor('htmlcontent', '', NV_ALLOWED_HTML_TAGS);
        $htmlcontent = strtr($htmlcontent, array(
            "\r\n" => '',
            "\r" => '',
            "\n" => ''
        ));
        $return['config']['htmlcontent'] = $htmlcontent;

        return $return;
    }

    /**
     * nv_menu_theme_default_footer()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_block_mypham_gmwhite($block_config)
    {
        global $global_config, $lang_global, $blockID;

        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/blocks/global.block_mypham_gmwhite.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/blocks/global.block_mypham_gmwhite.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }


        $xtpl = new XTemplate('global.block_mypham_gmwhite.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/blocks');
        $xtpl->assign('LANG', $lang_global);
        $xtpl->assign('BLOCKID', $blockID);
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
        $xtpl->assign('DATA', $block_config);

        $xtpl->parse('main');
        return $xtpl->text('main');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_mypham_gmwhite($block_config);
}
