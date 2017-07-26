<?php
require_once __DIR__ . '/../../../include/cp_header.php';
//require_once $GLOBALS['xoops']->path('www/class/xoopsformloader.php');

//require_once __DIR__ . '/../class/utility.php';
//require_once __DIR__ . '/../include/common.php';

$moduleDirName = basename(dirname(__DIR__));

include XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/admin/functions.inc.php';
include XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/admin/admin_header.php';

include XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/functions.php';

require_once XOOPS_ROOT_PATH . '/class/template.php';
if (!isset($xoopsTpl)) {
    $xoopsTpl = new XoopsTpl();
}
$xoopsTpl->xoops_setCaching(0);

xoops_cp_header();
//adminMenu(0);

// Template Page

$data['summary'][1]['count'] = uhqradio_summarycount('A');
$data['summary'][2]['count'] = uhqradio_summarycount('M');
$data['summary'][3]['count'] = uhqradio_summarycount('C');

$data['modules'][1]['module']    = _AM_UHQRADIO_MODULE_ICEAUTH;
$data['modules'][1]['installed'] = uhqradio_iceauthcheck();

$data['test'][1]['test']   = _AM_UHQRADIO_TEST_CURL;
$data['test'][1]['result'] = function_exists('curl_init');

$xoopsTpl->assign('data', $data);
$xoopsTpl->display('db:admin/' . $moduleDirName . '_admin_index.tpl');

xoops_cp_footer();
