<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if (!defined('NV_MAINFILE')) die('Stop!!!');

if (!nv_function_exists('nv_global_bxslider_review_image_homtext')) {

    /**
     * nv_block_config_bxproduct_center_blocks()
     *
     * @param mixed $module
     * @param mixed $data_block
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_bxslider_review_slider_image_homtext($module, $data_block, $lang_block)
    {
        global $db_config, $site_mods, $nv_Cache;

        $html = '';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_block['blockid'] . ':</label>';
        $html .= '<div class="col-sm-18"><select name="config_catid" class="form-control">';
        $html .= '<option value="0"> -- </option>';
        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_cat ORDER BY weight ASC';
        $list = $nv_Cache->db($sql, 'id', $module);
        $html_input = '';
        foreach ($list as $l) {
            $html_input .= '<input type="hidden" id="config_blockid_' . $l['id'] . '" value="' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $site_mods[$module]['alias']['groups'] . '/' . $l['alias'] . '" />';
            $html .= '<option value="' . $l['id'] . '" ' . (($data_block['catid'] == $l['id']) ? ' selected="selected"' : '') . '>' . $l['title'] . '</option>';
        }
        $html .= '</select>';
        $html .= $html_input;
        $html .= '</div></div>';

        $html .= "<div class=\"form-group\">";
        $html .= "	<label class=\"control-label col-sm-6\">" . $lang_block['numrow'] . "</label>";
        $html .= "	<div class=\"col-sm-18\"><input class=\"form-control w100\" type=\"text\" name=\"config_numrow\" size=\"5\" value=\"" . $data_block['numrow'] . "\"/></div>";
        $html .= "</div>";

        return $html;
    }

    /**
     * nv_block_config_bxslider_center_blocks_submit()
     *
     * @param mixed $module
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_bxslider_review_slider_image_homtext_submit($module, $lang_block)
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['catid'] = $nv_Request->get_int('config_catid', 'post', 0);
        $return['config']['numrow'] = $nv_Request->get_int('config_numrow', 'post', 0);
        return $return;
    }

    /**
     * nv_global_bxslider_image_homtext()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_global_bxslider_review_image_homtext($block_config)
    {
        global $site_mods, $module_config, $global_config, $lang_module, $db, $blockID, $nv_Cache;

        $module = $block_config['module'];
        $mod_file = $site_mods[$module]['module_file'];

        if (file_exists(NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $mod_file . "/block.bxslider_hometext_image.tpl")) {
            $block_theme = $global_config['module_theme'];
        } else {
            $block_theme = 'default';
        }

        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_cat ORDER BY weight ASC';
        $list_cat = $nv_Cache->db($sql, 'id', $module);


        $xtpl = new XTemplate('block.bxslider_hometext_image.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $mod_file);
        $xtpl->assign('LANG', $lang_module);
        $xtpl->assign('THEME_TEM', NV_BASE_SITEURL . 'themes/' . $block_theme);
        $xtpl->assign('CAT', $list_cat[$block_config['catid']]);

        $db->sqlreset()
            ->select('*')
            ->from(NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] )
            ->where('catid= ' . $block_config['catid'] . ' AND status= 1')
            ->limit($block_config['numrow']);
        $list = $nv_Cache->db($db->sql(), '', $module);

        foreach ($list as $row) {
            $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module . '/' . $row['image'];
            $row['title_cut'] = nv_clean60($row['title'], 30);
            $row['bodytext_cut'] = nv_clean60($row['bodytext'], 320);
            $xtpl->assign('DATA', $row);
            $xtpl->parse('main.items');
        }

        $xtpl->parse('main');
        return $xtpl->text('main');
    }

}

if (defined('NV_SYSTEM')) {
    $content = nv_global_bxslider_review_image_homtext($block_config);
}
