<?php
include "../../../mainfile.php";
include XOOPS_ROOT_PATH . "/include/cp_header.php";

include XOOPS_ROOT_PATH . "/modules/uhq_radio/admin/functions.inc.php";
include XOOPS_ROOT_PATH . "/modules/uhq_radio/admin/header.php";

include XOOPS_ROOT_PATH . "/modules/uhq_radio/include/functions.php";


require_once XOOPS_ROOT_PATH . '/class/template.php';
if (!isset($xoopsTpl)) {
	$xoopsTpl = new XoopsTpl();
}
$xoopsTpl->xoops_setCaching(0);

xoops_cp_header();
adminMenu(0);

// Template Page

$data['summary'][1]['count'] = uhqradio_summarycount("A");
$data['summary'][2]['count'] = uhqradio_summarycount("M");
$data['summary'][3]['count'] = uhqradio_summarycount("C"); 

$data['modules'][1]['module'] = _AM_UHQRADIO_MODULE_ICEAUTH;
$data['modules'][1]['installed'] = uhqradio_iceauthcheck();

$data['test'][1]['test'] = _AM_UHQRADIO_TEST_CURL;
$data['test'][1]['result'] = function_exists('curl_init');

$xoopsTpl->assign('data',$data);
$xoopsTpl->display("db:admin/uhqradio_admin_index.html");

xoops_cp_footer();

?>