<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sat, 10 Dec 2011 06:46:54 GMT
 */

if(! defined('NV_MAINFILE'))
{
    die('Stop!!!');
}

if(! nv_function_exists('nv_block_khoahoc_groups'))
{
    function nv_block_config_khoahoc_groups($module, $data_block, $lang_block)
    {
        global $nv_Cache, $site_mods;

        $html_input = '';
        $html = '';
        $html .= '<tr>';
        $html .= '<td>' . $lang_block['blockid'] . '</td>';
        $html .= '<td><select name="config_blockid" class="form-control w200">';
        $html .= '<option value="0"> -- </option>';
        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_block_cat ORDER BY weight ASC';
        $list = $nv_Cache->db($sql, '', $module);
        foreach($list as $l)
        {
            $html_input .= '<input type="hidden" id="config_blockid_' . $l['bid'] . '" value="' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $site_mods[$module]['alias']['groups'] . '/' . $l['alias'] . '" />';
            $html .= '<option value="' . $l['bid'] . '" ' . (($data_block['blockid'] == $l['bid']) ? ' selected="selected"' : '') . '>' . $l['title'] . '</option>';
        }
        $html .= '</select>';
        $html .= $html_input;
        $html .= '<script type="text/javascript">';
        $html .= '	$("select[name=config_blockid]").change(function() {';
        $html .= '		$("input[name=title]").val($("select[name=config_blockid] option:selected").text());';
        $html .= '		$("input[name=link]").val($("#config_blockid_" + $("select[name=config_blockid]").val()).val());';
        $html .= '	});';
        $html .= '</script>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>' . $lang_block['title_length'] . '</td>';
        $html .= '<td><input type="text" class="form-control w200" name="config_title_length" size="5" value="' . $data_block['title_length'] . '"/></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>' . $lang_block['numrow'] . '</td>';
        $html .= '<td><input type="text" class="form-control w200" name="config_numrow" size="5" value="' . $data_block['numrow'] . '"/></td>';
        $html .= '</tr>';
        return $html;
    }

    function nv_block_config_khoahoc_groups_submit($module, $lang_block)
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['blockid'] = $nv_Request->get_int('config_blockid', 'post', 0);
        $return['config']['numrow'] = $nv_Request->get_int('config_numrow', 'post', 0);
        $return['config']['title_length'] = $nv_Request->get_int('config_title_length', 'post', 20);
        return $return;
    }

    function nv_block_khoahoc_groups($block_config, $msystem)
    {
        global $array_subject, $array_teacher, $array_class, $lang_module, $site_mods, $module_config, $global_config, $nv_Cache, $db, $module_name, $my_head;
        $module = $block_config['module'];
        $show_no_image = $module_config[$module]['show_no_image'];
        $blockwidth = $module_config[$module]['blockwidth'];

        $db->sqlreset()->select('t1.id, t1.classid, t1.subjectid, t1.title, t1.alias, t1.image,t1.hometext,t1.addtime,t1.numview, t1.numlike, t1.price, t1.teacherid, t1.numbuy, t1.listtag')->from(NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_khoahoc t1')->join('INNER JOIN ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_block t2 ON t1.id = t2.id')->where('t2.bid= ' . $block_config['blockid'] . ' AND t1.status= 1')->order('t2.weight ASC')->limit($block_config['numrow']);
        //die($db->sql());
        $list = $nv_Cache->db($db->sql(), 'id', $module);

        if(! empty($list))
        {
            if(file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $site_mods[$module]['module_file'] . '/block_groups.tpl'))
            {
                $block_theme = $global_config['module_theme'];
            }
            else
            {
                $block_theme = 'default';
            }
            if($module != $module_name)
            {
                $my_head .= '<link rel="stylesheet" href="' . NV_BASE_SITEURL . 'themes/' . $block_theme . '/css/' . $site_mods[$module]['module_file'] . '.css' . '" type="text/css" />';
                include NV_ROOTDIR . '/modules/' . $site_mods[$module]['module_file'] . '/language/' . NV_LANG_INTERFACE . '.php';
            }
            $xtpl = new XTemplate('block_groups.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $site_mods[$module]['module_file']);
            $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
            $xtpl->assign('TEMPLATE', $block_theme);
            $xtpl->assign('MSYSTEM', $msystem);

            foreach($list as $l)
            {
                $l['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $array_class[$l['classid']]['alias'] . '/' . $l['alias'] . '-' . $l['id'] . $global_config['rewrite_exturl'];
                if(! empty($l['image']))
                {
                    $l['thumb'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $l['image'];
                }
                elseif(! empty($show_no_image))
                {
                    $l['thumb'] = NV_BASE_SITEURL . $show_no_image;
                }
                else
                {
                    $l['thumb'] = '';
                }
                if($array_subject[$l['subjectid']]['icon'] != '')
                {
                    $xtpl->assign('subject_icon', NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $array_subject[$l['subjectid']]['icon']);
                    $xtpl->parse('main.loop.subject_icon');
                }
                $l['teacherid'] = explode(',', $l['teacherid']);
                foreach($l['teacherid'] as $teacherid)
                {
                    if(isset($array_teacher[$teacherid]))
                    {
                        $tmp = $array_teacher[$teacherid];
                        $tmp['teacher_link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=giao-vien/' . $tmp['alias'];
                        $xtpl->assign('TEACHER', $tmp);
                        $xtpl->parse('main.loop.teacher');
                        unset($tmp);
                    }
                }
                $l['subject_name'] = $array_subject[$l['subjectid']]['title'];
                $l['subject_link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $array_class[$l['classid']]['alias'] . '/' . $array_subject[$l['subjectid']]['alias'];
                $l['blockwidth'] = $blockwidth;
                $l['title_clean'] = nv_clean60($l['title'], $block_config['title_length']);
                if($l['price'] == 0)
                {
                    $l['price'] = $lang_module['free'];
                }
                else
                {
                    $l['price'] = number_format($l['price'], 0, ',', '.');
                }

                $l['addtime'] = date('d/m/Y', $l['addtime']);
                $xtpl->assign('ROW', $l);
                if(! empty($l['thumb']))
                {
                    $xtpl->parse('main.loop.img');
                }
                if( intval($l['price']) > 0 ){
                    if( !empty( $msystem['icon'] )){
                        $xtpl->parse('main.loop.money_icon');
                    }else{
                        $xtpl->parse('main.loop.money_text');
                    }
                }
                $xtpl->parse('main.loop');
            }
            $xtpl->parse('main');
            return $xtpl->text('main');
        }
    }
}
if(defined('NV_SYSTEM'))
{
    global $site_mods, $module_name, $global_array_cat, $array_subject, $array_teacher, $array_class, $array_tag, $nv_Cache, $db, $module_config, $db_config;
    $module = $block_config['module'];
    if(isset($site_mods[$module]))
    {
        if($module != $module_name)
        {

            // module taikhoan
            $taikhoan_array_money = array();
            $taikhoan_module_name = 'taikhoan';
            $msystem = array();
            if( isset($site_mods[$taikhoan_module_name])){

                $sql = 'SELECT id, mcountry, symbol_inter, symbol, icon FROM ' . $db_config['prefix'] . '_' . $taikhoan_module_name . '_msystem WHERE status = 1';
                $array_msystem = $nv_Cache->db($sql, 'id', $taikhoan_module_name);
                $msystem = $array_msystem[$module_config['taikhoan']['msystem_default']];
                $msystem['icon'] = !empty( $msystem['icon'] )? NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $taikhoan_module_name . '/' . $msystem['icon'] : '';

            }
            $array_subject = $nv_Cache->db('SELECT * FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_subject', 'id', $module);
            $array_teacher = $nv_Cache->db('SELECT * FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_teacher', 'id', $module);
            $array_class = $nv_Cache->db('SELECT * FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_class', 'id', $module);
            $array_tag = $nv_Cache->db('SELECT * FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_tag', 'tag_id', $module);
        }
        $content = nv_block_khoahoc_groups($block_config, $msystem);
    }
}
