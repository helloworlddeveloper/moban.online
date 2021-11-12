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

if (!nv_function_exists('nv_block_news_cat')) {
    /**
     * nv_block_config_news_cat()
     *
     * @param mixed $module
     * @param mixed $data_block
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_video_cat($module, $data_block, $lang_block)
    {
        global $nv_Cache, $site_mods;

        $html = '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_block['catid'] . ':</label>';

        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_topic ORDER BY weight ASC';
        $list = $nv_Cache->db($sql, '', $module);
        if (!is_array($data_block['topicid'])) {
            $data_block['topicid'] = array($data_block['topicid']);
        }

        $html .= '<div class="col-sm-18">';
        foreach ($list as $l) {
            if ($l['status'] == 1 or $l['status'] == 2) {
                $xtitle_i = '';

                if ($l['lev'] > 0) {
                    for ($i = 1; $i <= $l['lev']; ++$i) {
                        $xtitle_i .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                    }
                }
                $html .= $xtitle_i . '<label><input type="checkbox" name="config_topicid[]" value="' . $l['id'] . '" ' . ((in_array($l['id'], $data_block['topicid'])) ? ' checked="checked"' : '') . '</input>' . $l['title'] . '</label><br />';
            }
        }
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_block['title_length'] . ':</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_title_length" size="5" value="' . $data_block['title_length'] . '"/></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_block['numrow'] . ':</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_numrow" size="5" value="' . $data_block['numrow'] . '"/></div>';
        $html .= '</div>';
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
    function nv_block_config_video_cat_submit($module, $lang_block)
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['topicid'] = $nv_Request->get_array('config_topicid', 'post', array());
        $return['config']['numrow'] = $nv_Request->get_int('config_numrow', 'post', 0);
        $return['config']['title_length'] = $nv_Request->get_int('config_title_length', 'post', 20);

        return $return;
    }

    /**
     * nv_block_news_cat()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_block_video_cat($block_config)
    {
        global $my_head, $module_name, $nv_Cache, $site_mods, $global_config, $db;
        $module = $block_config['module'];

        if (empty($block_config['topicid'])) {
            return '';
        }

        $catid = implode(',', $block_config['topicid']);

        $db->sqlreset()
            ->select('* ')
            ->from(NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_clip')
            ->where('status= 1 AND tid IN(' . $catid . ')')
            ->order('addtime DESC')
            ->limit($block_config['numrow']);
        $list = $nv_Cache->db($db->sql(), '', $module);

        if (!empty($list)) {
            if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $site_mods[$module]['module_file'] . '/block_video_by_cat.tpl')) {
                $block_theme = $global_config['module_theme'];
            } else {
                $block_theme = 'default';
            }

            if ($module != $module_name) {
                $my_head .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . NV_BASE_SITEURL . "themes/" . $block_theme . "/css/" . $site_mods[$module]['module_file'] . ".css\" />\n";
            }

            $xtpl = new XTemplate('block_video_by_cat.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $site_mods[$module]['module_file']);
            $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
            $xtpl->assign('TEMPLATE', $block_theme);

            foreach ($list as $row) {
                if (!empty($row['img'])) {
                    $row['img'] = substr($row['img'], strlen(NV_UPLOADS_DIR));
                    if (file_exists(NV_ROOTDIR . '/' . NV_ASSETS_DIR . $row['img'])) {
                        $row['img'] = NV_BASE_SITEURL . NV_ASSETS_DIR . $row['img'];
                    } elseif (file_exists(NV_UPLOADS_REAL_DIR . $row['img'])) {
                        $row['img'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . $row['img'];
                    } else {
                        $row['img'] = '';
                    }
                }
                if (empty($row['img'])) {
                    $row['img'] = NV_BASE_SITEURL . "themes/" . $block_theme . "/images/" . $site_mods[$module]['module_file'] . "/video.png";
                }
                $row['href'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . "&amp;" . NV_OP_VARIABLE . "=" . $row['alias'] . $global_config['rewrite_exturl'];
                $row['sortTitle'] = nv_clean60($row['title'], $block_config['title_length']);
                $row['addtime'] = nv_date("d/m/Y", $row['addtime']);

                $xtpl->assign('ROW', $row);
                $xtpl->parse('main.loop');
            }

            $xtpl->parse('main');
            return $xtpl->text('main');
        }
    }
}
if (defined('NV_SYSTEM')) {
    global $nv_Cache, $site_mods, $module_name;
    $module = $block_config['module'];
    if (isset($site_mods[$module])) {
        $content = nv_block_video_cat($block_config);
    }
}