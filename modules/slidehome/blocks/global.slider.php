<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 19 Jun 2014 02:27:09 GMT
 */

if( ! defined( 'NV_MAINFILE' ) )
	die( 'Stop!!!' );
if( ! nv_function_exists( 'sliderhome_block_slider' ) )
{
    /**
     * nv_block_config_bxproduct_center_blocks()
     *
     * @param mixed $module
     * @param mixed $data_block
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_center_blocks($module, $data_block, $lang_block)
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
        $html .= "	<label class=\"control-label col-sm-6\">" . $lang_block['numrow'] . "</label>";
        $html .= "	<div class=\"col-sm-18\"><input class=\"form-control w100\" type=\"text\" name=\"config_numrow\" size=\"5\" value=\"" . $data_block['numrow'] . "\"/></div>";
        $html .= "</div>";

        $html .= "<div class=\"form-group\">";
        $html .= "  <label class=\"control-label col-sm-6\">" . $lang_block['showpage'] . "</label>";
        $pager = ($data_block['showpage'] == 1) ? 'checked="checked"' : '';
        $html .= "  <div class=\"col-sm-18\"><input type=\"checkbox\" name=\"config_showpage\" value=\"1\" " . $pager . " \></div>";
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
    function nv_block_config_center_blocks_submit($module, $lang_block)
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['catid'] = $nv_Request->get_int('config_catid', 'post', 0);
        $return['config']['numrow'] = $nv_Request->get_int('config_numrow', 'post', 0);
        $return['config']['showpage'] = $nv_Request->get_int('config_showpage', 'post', 0);
        return $return;
    }

    function sliderhome_block_slider( $block_config )
	{
		global $global_config, $db, $site_mods, $module_name, $module_info;

		$module = $block_config['module'];
		$list = array();
		if( isset( $site_mods[$module] ) )
		{
			$mod_file = $site_mods[$module]['module_file'];
			$mod_data = $site_mods[$module]['module_data'];
		}
		$db->sqlreset()->select( '*' )->from( '' . NV_PREFIXLANG . '_' . $mod_data )
            ->where('catid= ' . $block_config['catid'] . ' AND status= 1')
            ->limit($block_config['numrow']);
		$sth = $db->prepare( $db->sql() );
		$sth->execute();
		$list = $sth->fetchAll();

		if( file_exists( NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $mod_file . '/global.slider.tpl' ) )
		{
			$block_theme = $module_info['template'];
		}
		else
		{
			$block_theme = 'default';
		}
		$xtpl = new XTemplate( 'global.slider.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $mod_file );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
		$xtpl->assign( 'TEMPLATE', $block_theme );
		$i = 0;
		foreach( $list as $row )
		{
			$i++;

			$row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module . '/' . $row['image'];
			$xtpl->assign( 'num', $i );
			$xtpl->assign( 'ROW', $row );
			$xtpl->parse( 'main.slide' );
            $xtpl->parse( 'main.showpage.numpage' );
		}
		if( $block_config['showpage'] ){
            $xtpl->parse( 'main' );
        }
        $xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}
}
if( defined( 'NV_SYSTEM' ) )
{
	$content = sliderhome_block_slider( $block_config );
}

?>