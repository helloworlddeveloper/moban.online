<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if (!defined('NV_MAINFILE')) die('Stop!!!');

if (!nv_function_exists('nv_global_bxslider_center')) {

    /**
     * nv_block_config_bxproduct_center_blocks()
     *
     * @param mixed $module
     * @param mixed $data_block
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_bxslider_center_blocks($module, $data_block, $lang_block)
    {
        global $db_config, $site_mods, $nv_Cache;

        $html = '';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_block['blockid'] . ':</label>';
        $html .= '<div class="col-sm-18"><select name="config_catid" class="form-control">';
        $html .= '<option value="0"> -- </option>';
        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_cat ORDER BY weight ASC';
        $list = $nv_Cache->db($sql, '', $module);
        $html_input = '';
        foreach ($list as $l) {
            $html_input .= '<input type="hidden" id="config_blockid_' . $l['id'] . '" value="' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $site_mods[$module]['alias']['groups'] . '/' . $l['alias'] . '" />';
            $html .= '<option value="' . $l['id'] . '" ' . (($data_block['catid'] == $l['id']) ? ' selected="selected"' : '') . '>' . $l['title'] . '</option>';
        }
        $html .= '</select>';
        $html .= $html_input;
        $html .= '<script type="text/javascript">';
        $html .= '	$("select[name=config_blockid]").change(function() {';
        $html .= '		$("input[name=title]").val($("select[name=config_blockid] option:selected").text());';
        $html .= '		$("input[name=link]").val($("#config_blockid_" + $("select[name=config_blockid]").val()).val());';
        $html .= '	});';
        $html .= '</script>';
        $html .= '</div></div>';

        $html .= "<div class=\"form-group\">";
        $html .= "	<label class=\"control-label col-sm-6\">" . $lang_block['numget'] . "</label>";
        $html .= "	<div class=\"col-sm-18\"><input class=\"form-control w100\" type=\"text\" name=\"config_numget\" size=\"5\" value=\"" . $data_block['numget'] . "\"/></div>";
        $html .= "</div>";

        $html .= "<div class=\"form-group\">";
        $html .= "	<label class=\"control-label col-sm-6\">" . $lang_block['numrow'] . "</label>";
        $html .= "	<div class=\"col-sm-18\"><input class=\"form-control w100\" type=\"text\" name=\"config_numrow\" size=\"5\" value=\"" . $data_block['numrow'] . "\"/></div>";
        $html .= "</div>";

        $html .= "<div class=\"form-group\">";
        $html .= "  <label class=\"control-label col-sm-6\">" . $lang_block['auto'] . "</label>";
        $auto = ($data_block['auto'] == 1) ? 'checked="checked"' : '';
        $html .= "  <div class=\"col-sm-18\"><input type=\"checkbox\" name=\"config_auto\" value=\"1\" " . $auto . " \></div>";
        $html .= "</div>";

        $html .= "<div class=\"form-group\">";
        $html .= "<label class=\"control-label col-sm-6\">" . $lang_block['mode'] . "</label>";
        $html .= "<td>";
        $sorting_array1 = array(
            'horizontal' => 'Ngang',
            'vertical' => 'Dọc'
        );
        $html .= '<select name="config_mode" class="form-control w100">';
        foreach ($sorting_array1 as $key1 => $value1) {
            $html .= '<option value="' . $key1 . '" ' . ($data_block['mode'] == $key1 ? 'selected="selected"' : '') . '>' . $value1 . '</option>';
        }
        $html .= '</select>';
        $html .= "</td";
        $html .= "	</div>";

        $html .= "<div class=\"form-group\">";
        $html .= "	<label class=\"control-label col-sm-6\">" . $lang_block['speed'] . "</label>";
        $html .= "	<div class=\"col-sm-18\"><input class=\"form-control w100\" type=\"text\" name=\"config_speed\" size=\"5\" value=\"" . $data_block['speed'] . "\"/></div>";
        $html .= "</div>";

        $html .= "<div class=\"form-group\">";
        $html .= "	<label class=\"control-label col-sm-6\">" . $lang_block['width'] . "</label>";
        $html .= "	<div class=\"col-sm-18\"><input class=\"form-control w100\" type=\"text\" name=\"config_width\" size=\"5\" value=\"" . $data_block['width'] . "\"/></div>";
        $html .= "</div>";

        $html .= "<div class=\"form-group\">";
        $html .= "	<label class=\"control-label col-sm-6\">" . $lang_block['margin'] . "</label>";
        $html .= "	<div class=\"col-sm-18\"><input class=\"form-control w100\" type=\"text\" name=\"config_margin\" size=\"5\" value=\"" . $data_block['margin'] . "\"/></div>";
        $html .= "</div>";

        $html .= "<div class=\"form-group\">";
        $html .= "	<label class=\"control-label col-sm-6\">" . $lang_block['move'] . "</label>";
        $html .= "	<div class=\"col-sm-18\"><input class=\"form-control w100\" type=\"text\" name=\"config_move\" size=\"5\" value=\"" . $data_block['move'] . "\"/></div>";
        $html .= "</div>";

        $html .= "<div class=\"form-group\">";
        $html .= "  <label class=\"control-label col-sm-6\">" . $lang_block['pager'] . "</label>";
        $pager = ($data_block['pager'] == 1) ? 'checked="checked"' : '';
        $html .= "  <div class=\"col-sm-18\"><input type=\"checkbox\" name=\"config_pager\" value=\"1\" " . $pager . " \></div>";
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
    function nv_block_config_bxslider_center_blocks_submit($module, $lang_block)
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['catid'] = $nv_Request->get_int('config_catid', 'post', 0);
        $return['config']['numrow'] = $nv_Request->get_int('config_numrow', 'post', 0);
        $return['config']['numget'] = $nv_Request->get_int('config_numget', 'post', 0);
        $return['config']['auto'] = $nv_Request->get_int('config_auto', 'post', 0);
        $return['config']['mode'] = $nv_Request->get_string('config_mode', 'post', 0);
        $return['config']['speed'] = $nv_Request->get_int('config_speed', 'post', 0);
        $return['config']['width'] = $nv_Request->get_int('config_width', 'post', 0);
        $return['config']['margin'] = $nv_Request->get_int('config_margin', 'post', 0);
        $return['config']['move'] = $nv_Request->get_int('config_move', 'post', 0);
        $return['config']['pager'] = $nv_Request->get_int('config_pager', 'post', 0);
        return $return;
    }

    /**
     * nv_global_bxslider_center()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_global_bxslider_center($block_config)
    {
        global $site_mods, $module_config, $global_config, $lang_module, $db, $blockID, $nv_Cache;

        $module = $block_config['module'];
        $mod_data = $site_mods[$module]['module_data'];
        $mod_file = $site_mods[$module]['module_file'];
        $num_view = $block_config['numrow'];
        $num_get = $block_config['numget'];
        $auto = $block_config['auto'] == 1 ? 'true' : 'false';
        $mode = $block_config['mode'];
        $speed = $block_config['speed'];
        $width = $block_config['width'];
        $margin = $block_config['margin'];
        $move = $block_config['move'];
        $pager = $block_config['pager'] == 1 ? 'false' : 'true';

        $i = 1;
        $j = 1;
        $page_i = '';
        if (file_exists(NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $mod_file . "/block.bxslider_center.tpl")) {
            $block_theme = $global_config['module_theme'];
        } else {
            $block_theme = 'default';
        }

        $xtpl = new XTemplate('block.bxslider_center.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $mod_file);
        $xtpl->assign('LANG', $lang_module);
        $xtpl->assign('THEME_TEM', NV_BASE_SITEURL . 'themes/' . $block_theme);
        $xtpl->assign('LIB_PATH', NV_BASE_SITEURL . 'themes/default/');
        $xtpl->assign('AUTO', $auto);
        $xtpl->assign('MODE', $mode);
        $xtpl->assign('SPEED', $speed);
        $xtpl->assign('WIDTH', $width);
        $xtpl->assign('MARGIN', $margin);
        $xtpl->assign('MOVE', $move);
        $xtpl->assign('NUMVIEW', $num_view);
        $xtpl->assign('PAGER', $pager);
        $xtpl->assign('BLOCKID', $blockID);


        $db->sqlreset()
            ->select('*')
            ->from(NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] )
            ->where('catid= ' . $block_config['catid'] . ' AND status= 1')
            ->limit($block_config['numrow']);
        $list = $nv_Cache->db($db->sql(), '', $module);

        foreach ($list as $row) {
            $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module . '/' . $row['image'];
            $row['title_cut'] = nv_clean60($row['title'], 30);
            $xtpl->assign('DATA', $row);
            $xtpl->parse('main.items');
        }
        if (!defined('BXLIB')) {
            define('BXLIB', true);
            $xtpl->parse('main.lib');
        }

        $xtpl->parse('main');
        return $xtpl->text('main');
    }

}

if (defined('NV_SYSTEM')) {
    $content = nv_global_bxslider_center($block_config);
}
