<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sat, 10 Dec 2011 06:46:54 GMT
 */

if (! defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (! nv_function_exists('nv_block_sm_bestnpp')) {
    /**
     * nv_block_config_news_groups()
     *
     * @param mixed $module
     * @param mixed $data_block
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_sm_bestnpp($module, $data_block, $lang_block)
    {

        $html = '';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_block['numrow'] . ':</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_numrow" size="5" value="' . $data_block['numrow'] . '"/></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">Thời gian:</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_date" value="' . $data_block['date'] . '"/></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">npp:</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_npp" value="' . $data_block['npp'] . '"/></div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * nv_block_config_news_groups_submit()
     *
     * @param mixed $module
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_sm_bestnpp_submit($module, $lang_block)
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['numrow'] = $nv_Request->get_int('config_numrow', 'post', 0);
        $return['config']['date'] = $nv_Request->get_int('config_date', 'post', '');
        $return['config']['npp'] = $nv_Request->get_int('config_npp', 'post', '');
        return $return;
    }

    /**
     * nv_block_news_groups()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_block_sm_bestnpp($block_config)
    {
        global $nv_Cache, $site_mods, $global_config, $db, $db_config;
        $module = $block_config['module'];

        $sql = "SELECT * FROM " . $db_config['prefix']  . "_affiliate_agency WHERE status=1 ORDER BY weight";
        $array_agency = $nv_Cache->db($sql, 'id', 'affiliate');


        $sql = "SELECT * FROM " . NV_PREFIXLANG . "_affiliate_province WHERE status=1 ORDER BY weight";
        $array_province = $nv_Cache->db($sql, 'id', 'affiliate');


        $db->sqlreset()
            ->select('t2.title as fullname, t2.email, t2.image_site, t2.domain, t3.agencyid, t3.provinceid, t3.code, t3.mobile')
            ->from($db_config['prefix'] . '_regsite t2, ' . $db_config['prefix'] . '_affiliate_users t3')
            ->where('t2.userid=t3.userid and t3.mobile in ("' .  $block_config['npp'] . '")')
            ->limit($block_config['numrow']);

        $result = $db->query( $db->sql() );
        $array_statistic = array();
        while ($row = $result ->fetch()){
            $array_statistic[] = $row;
        }

        if (! empty($array_statistic)) {
            if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/sm/block_statistic.tpl')) {
                $block_theme = $global_config['module_theme'];
            } else {
                $block_theme = 'default';
            }
            $xtpl = new XTemplate('block_bestnpp.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/sm');
            $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
            $xtpl->assign('TEMPLATE', $block_theme);
            $xtpl->assign('DATE', $block_config['date']);
            $i = 1;

            foreach ($array_statistic as $data ) {

                if ( !empty( $data['image_site'] ) ) {
                    $data['photo'] = $data['image_site'];
                } else {
                    $data['photo'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $site_mods[$module]['module_upload'] . '/top' . $i . '.png';
                }
                $data['mobile'] = substr_replace($data['mobile'],'xxx',strlen( $data['mobile'] ) - 3 );
                //$data['photo'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $site_mods[$module]['module_upload'] . '/top' . $i . '.png';
                //$data['total_price'] = number_format($data['total_price'], 0, '.', ',' );
                //$data['fullname'] = nv_show_name_user(  $data['first_name'], $data['last_name'] );

                $data['agency'] = $array_agency[$data['agencyid']]['title'];
                $data['province'] = $array_province[$data['provinceid']]['title'];
                $xtpl->assign('ROW', $data);

                if( $i ==1 ){
                    $xtpl->parse('main.showmain');
                }else{
                    $xtpl->parse('main.showsub.loop');
                }
                $i++;
            }
            if( $i > 1 ){
                $xtpl->parse('main.showsub');
            }
            $xtpl->parse('main');
            return $xtpl->text('main');
        }
    }
}
if (defined('NV_SYSTEM')) {
    global $site_mods, $module_name, $global_array_cat, $module_array_cat, $nv_Cache, $db;
    $module = $block_config['module'];
    if (isset($site_mods[$module])) {
        $content = nv_block_sm_bestnpp($block_config);
    }
}
