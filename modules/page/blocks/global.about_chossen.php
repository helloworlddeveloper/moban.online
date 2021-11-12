<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/25/2010 18:6
 */

if (! defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (! nv_function_exists('nv_page_chossen')) {

    /**
     * nv_block_config_page_list()
     *
     * @param mixed $module
     * @param mixed $data_block
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_about_chossen($module, $data_block, $lang_block)
    {
        global $nv_Cache, $site_mods;
        $html = '';
        $html .= '<div class="form-group">';
        $html .= '	<label class="control-label col-sm-6">' . $lang_block['title_length'] . ':</label>';
        $html .= '	<div class="col-sm-9"><input type="text" class="form-control" name="config_title_length" value="' . $data_block['title_length'] . '"/></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_block['postid'] . ':</label>';
        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . ' WHERE status=1 ORDER BY weight ASC';
        $list = $nv_Cache->db($sql, '', $module);
        $html .= '<div class="col-sm-9">';
        $html .= '<div style="max-height: 200px; overflow: auto">';
        $html .= '<select name="config_postid" class="form-control">';

        foreach ($list as $data) {
            $html .= '<option value="' . $data['id'] . '" ' . ($data_block['postid'] == $data['id'] ? 'selected="selected"' : '') . '>' . $data['title'] . '</option>';
        }

        $html .= '</select>';
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    /**
     * nv_block_config_page_list_submit()
     *
     * @param mixed $module
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_about_chossen_submit($module, $lang_block)
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['title_length'] = $nv_Request->get_int('config_title_length', 'post', 24);
        $return['config']['postid'] = $nv_Request->get_int('config_postid', 'post', 5);
        return $return;
    }
    /**
     * nv_message_page()
     *
     * @return
     */
    function nv_page_chossen($block_config)
    {
        global $global_config, $site_mods, $db_slave, $module_upload;
        $module = $block_config['module'];

        if (! isset($site_mods[$module])) {
            return '';
        }


        $sql = 'SELECT id,title,alias,image,description,bodytext FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . ' WHERE id=' . $block_config['postid'];

        if (($query = $db_slave->query($sql)) !== false) {
            if (($row = $query->fetch()) !== false) {
                $row['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $row['alias'] . $global_config['rewrite_exturl'];
                $row['bodytext'] = strip_tags($row['bodytext']);
                if( $block_config['title_length'] > 0 ){
                    $row['bodytext'] = nv_clean60($row['bodytext'], $block_config['title_length']);
                }

                if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/page/block.chossen.tpl')) {
                    $block_theme = $global_config['module_theme'];
                } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/page/block.chossen.tpl')) {
                    $block_theme = $global_config['site_theme'];
                } else {
                    $block_theme = 'default';
                }

                $xtpl = new XTemplate('block.chossen.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/page');
                if (!empty($row['image']) and !nv_is_url($row['image'])) {
                    $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module . '/' . $row['image'];
                    $xtpl->assign('IMAGE', $row['image'] );
                    $xtpl->parse('main.image');
                }
                $xtpl->assign('DATA', $row);

                $xtpl->parse('main');
                return $xtpl->text('main');
            }
        }
        return '';
    }
}

$content = nv_page_chossen($block_config);