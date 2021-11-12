<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['document_payment'];

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$content_docpay_file = NV_ROOTDIR . '/' . NV_DATADIR . '/' . NV_LANG_DATA . '_' . $module_data . '_users_content.txt';
$content_docpay = '';

if (file_exists($content_docpay_file)) {
    $content_docpay = file_get_contents($content_docpay_file);
}

if ($nv_Request->get_int('saveintro', 'post', 0) == 1) {
    $content_docpay = $nv_Request->get_string('content_docpay', 'post', '');
    if (defined('NV_EDITOR')) {
        $content_docpay = nv_nl2br($content_docpay, '');
    } else {
        $content_docpay = nv_nl2br(nv_htmlspecialchars(strip_tags($content_docpay)), '<br />');
    }
    file_put_contents($content_docpay_file, $content_docpay);
}

$content_docpay = htmlspecialchars(nv_editor_br2nl($content_docpay));
if (defined('NV_EDITOR') and function_exists('nv_aleditor')) {
    $content_docpay_edits = nv_aleditor('content_docpay', '100%', '300px', $content_docpay);
} else {
    $content_docpay_edits = "<textarea style=\"width: 100%\" name=\"content_docpay\" id=\"content_docpay\" cols=\"20\" rows=\"15\">" . $content_docpay . "</textarea>";
}

$xtpl = new XTemplate("content-view.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('content_docpay', $content_docpay_edits);

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
