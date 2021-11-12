<?php

/**
 * @Project NUKEVIET 4.x
 * @Author EDUSGROUP.JSC (thangbv@edus.vn)
 * @Copyright (C) 2014 EDUSGROUP.JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 24 Dec 2014 08:50:19 GMT
 */


if(! defined('NV_IS_FILE_ADMIN'))
{
    die('Stop!!!');
}

$link_view = '';
$rowcontent['id'] = $nv_Request->get_int('id', 'get,post', 0);
$rowcontent['area'] = $nv_Request->get_int('area', 'get,post', 0);
if($rowcontent['id'] > 0)
{

    $mod_function = $db_slave->query('SELECT * FROM ' . NV_MODFUNCS_TABLE . ' where  func_id=' . $rowcontent['area'])->fetch();
    if($mod_function['func_name'] == 'bai-giang')
    {
        $rowcontent = $db_slave->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_baihoc where id=' . $rowcontent['id'])->fetch();
        if(! empty($rowcontent['id']))
        {
            $link_view = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=bai-giang/' . $rowcontent['alias'] . '-' . $rowcontent['id'] . $global_config['rewrite_exturl'], true);
        }
    }
    if($mod_function['func_name'] == 'giao-vien')
    {
        $rowcontent = $db_slave->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_teacher where id=' . $rowcontent['id'])->fetch();
        if(! empty($rowcontent['id']))
        {
            $link_view = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=giao-vien/' . $rowcontent['alias'] . $global_config['end_exturl'], true);
        }
    }
}
if(!empty( $link_view ))
{
    Header('Location: ' . $link_view);
    die();
}
else
{
    nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['admin_no_allow_func'], 404);
}
