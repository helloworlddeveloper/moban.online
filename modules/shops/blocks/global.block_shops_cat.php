<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sat, 10 Dec 2011 06:46:54 GMT
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (!nv_function_exists('nv_block_shops_cat')) {
    /**
     * nv_block_config_news_cat()
     *
     * @param mixed $module
     * @param mixed $data_block
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_shops_cat($module, $data_block, $lang_block)
    {
        global $nv_Cache, $db_config, $site_mods;

        $tooltip_position = array(
            'top' => $lang_block['tooltip_position_top'],
            'bottom' => $lang_block['tooltip_position_bottom'],
            'left' => $lang_block['tooltip_position_left'],
            'right' => $lang_block['tooltip_position_right']
        );

        $html = '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_block['catid'] . ':</label>';

        $sql = 'SELECT catid, ' . NV_LANG_DATA . '_title AS title FROM ' . $db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_catalogs ORDER BY sort ASC';
        $list = $nv_Cache->db($sql, '', $module);
        if (!is_array($data_block['catid'])) {
            $data_block['catid'] = array($data_block['catid']);
        }

        $html .= '<div class="col-sm-18">';
        foreach ($list as $l) {
            $xtitle_i = '';

            if ($l['lev'] > 0) {
                for ($i = 1; $i <= $l['lev']; ++$i) {
                    $xtitle_i .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                }
            }
            $html .= $xtitle_i . '<label><input type="checkbox" name="config_catid[]" value="' . $l['catid'] . '" ' . ((in_array($l['catid'], $data_block['catid'])) ? ' checked="checked"' : '') . '</input>' . $l['title'] . '</label><br />';
        }
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_block['title_length'] . ':</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_title_length" size="5" value="' . $data_block['title_length'] . '"/></div>';
        $html .= '</div>';
        return $html;
    }

    /**
     * nv_block_config_news_cat_submit()
     *
     * @param mixed $module
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_shops_cat_submit($module, $lang_block)
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['catid'] = $nv_Request->get_array('config_catid', 'post', array());
        $return['config']['title_length'] = $nv_Request->get_int('config_title_length', 'post', 20);
        return $return;
    }

    /**
     * nv_block_news_cat()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_block_shops_cat($block_config, $module_array_cat)
    {
        global $module_array_cat, $site_mods, $global_config;
        $module = $block_config['module'];
        $mod_file = $site_mods[$module]['module_file'];
        if (empty($block_config['catid'])) {
            return '';
        }

        if (!empty($module_array_cat)) {
            if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $mod_file . '/block_show_cat.tpl')) {
                $block_theme = $global_config['module_theme'];
            } else {
                $block_theme = 'default';
            }

            $xtpl = new XTemplate('block_show_cat.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $mod_file );
            $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
            $xtpl->assign('TEMPLATE', $block_theme);

            foreach ($module_array_cat as $l ) {

                if ( in_array( $l['catid'], $block_config['catid'] ) && !empty( $l['image'] )) {
                    $l['title_clean'] = nv_clean60($l['title'], $block_config['title_length']);
                    $file_info = pathinfo( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $l['image']);

                    $l['image_hover'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . str_replace($file_info['filename'] . '.' . $file_info['extension'], $file_info['filename'] . '-hover.' . $file_info['extension'], $l['image'] );
                    $l['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $l['image'];
                    $xtpl->assign('DATA', $l);
                    $xtpl->parse('main.loop');
                }
            }
            $xtpl->parse('main');
            return $xtpl->text('main');
        }
    }
}
if (defined('NV_SYSTEM')) {
    global $nv_Cache, $site_mods, $module_name, $global_array_cat, $module_array_cat, $db_config;
    $module = $block_config['module'];

    if (isset($site_mods[$module])) {
        if ($module == $module_name) {
            $module_array_cat = $global_array_cat;
            unset($module_array_cat[0]);
        } else {
            $mod_data = $site_mods[$module]['module_data'];
            $sql = 'SELECT catid, parentid, lev, ' . NV_LANG_DATA . '_title AS title, ' . NV_LANG_DATA . '_alias AS alias, image, viewcat, numsubcat, subcatid, numlinks, ' . NV_LANG_DATA . '_description AS description, inhome, ' . NV_LANG_DATA . '_keywords AS keywords, groups_view, typeprice FROM ' . $db_config['prefix'] . '_' . $mod_data . '_catalogs ORDER BY sort ASC';
            $list = $nv_Cache->db($sql, 'catid', $module);
            foreach ($list as $row) {
                $module_array_cat[$row['catid']] = array(
                    'catid' => $row['catid'],
                    'parentid' => $row['parentid'],
                    'title' => $row['title'],
                    'alias' => $row['alias'],
                    'image' => $row['image'],
                    'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $row['alias'],
                    'viewcat' => $row['viewcat'],
                    'numsubcat' => $row['numsubcat'],
                    'subcatid' => $row['subcatid'],
                    'numlinks' => $row['numlinks'],
                    'description' => $row['description'],
                    'inhome' => $row['inhome'],
                    'keywords' => $row['keywords'],
                    'groups_view' => $row['groups_view'],
                    'lev' => $row['lev'],
                    'typeprice' => $row['typeprice']
                );
            }
            unset($list, $row);
        }
        $content = nv_block_shops_cat($block_config, $module_array_cat);
    }
}