<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/25/2010 18:6
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (!nv_function_exists('nv_product_list_image')) {
    /**
     * nv_block_config_page_list()
     *
     * @param mixed $module
     * @param mixed $data_block
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_product_image($module, $data_block, $lang_block)
    {
        $html = '';
        $html .= '<div class="form-group">';
        $html .= '	<label class="control-label col-sm-6">' . $lang_block['title_length'] . ':</label>';
        $html .= '	<div class="col-sm-9"><input type="text" class="form-control" name="config_title_length" value="' . $data_block['title_length'] . '"/></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '	<label class="control-label col-sm-6">' . $lang_block['numrow'] . ':</label>';
        $html .= '	<div class="col-sm-9"><input type="text" name="config_numrow" class="form-control" value="' . $data_block['numrow'] . '"/></div>';
        $html .= '</div>';
        return $html;
    }

    /**
     * nv_block_config_product_image_submit()
     *
     * @param mixed $module
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_product_image_submit($module, $lang_block)
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['title_length'] = $nv_Request->get_int('config_title_length', 'post', 24);
        $return['config']['numrow'] = $nv_Request->get_int('config_numrow', 'post', 5);
        return $return;
    }

    /**
     * nv_page_list()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_product_list_image($block_config)
    {
        global $nv_Cache, $global_config, $site_mods, $db;
        $module = $block_config['module'];

        if (!isset($site_mods[$module])) {
            return '';
        }

        $db->sqlreset()->select('*')->from(NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'])->where('status = 1')->order('weight ASC')->limit($block_config['numrow']);

        $list = $nv_Cache->db($db->sql(), 'id', $module);

        if (!empty($list)) {
            if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/product/block.product_list_image.tpl')) {
                $block_theme = $global_config['module_theme'];
            } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/product/block.product_list_image.tpl')) {
                $block_theme = $global_config['site_theme'];
            } else {
                $block_theme = 'default';
            }

            $xtpl = new XTemplate('block.product_list_image.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/product');

            foreach ($list as $l) {

                $l['image'] = ( !empty( $l['image'] ))? NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module . '/' . $l['image'] : '';
                if( !empty( $l['image'] )){
                    $l['title_clean60'] = nv_clean60($l['title'], $block_config['title_length']);
                    $l['title_english_clean60'] = nv_clean60($l['title'], $block_config['title_english']);
                    $l['price'] = number_format( $l['price'], 0, ',', '.');
                    $xtpl->assign('ROW', $l);
                    $xtpl->parse('main.loop');
                }
            }

            $xtpl->parse('main');
            return $xtpl->text('main');
        } else {
            return '';
        }
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_product_list_image($block_config);
}
