<?php

include '../../mainfile.php';
require_once (XOOPS_ROOT_PATH.'/modules/uhq_radio/include/sanity.php');
require_once (XOOPS_ROOT_PATH.'/modules/uhq_radio/include/functions.php');
require_once (XOOPS_ROOT_PATH.'/modules/uhq_radio/include/rawdata.php');
require_once (XOOPS_ROOT_PATH.'/modules/uhq_radio/include/modoptions.php');
require_once (XOOPS_ROOT_PATH.'/modules/uhq_radio/include/sambc.php');

// Load Templates & Smarty

require_once XOOPS_ROOT_PATH . '/class/template.php';
if (!isset($xoopsTpl)) {
	$xoopsTpl = new XoopsTpl();
}

include XOOPS_ROOT_PATH."/header.php";

$xoopsTpl->assign('data',$data);

$sanerequest = uhqradio_dosanity();

// Ensure minimum variables have been passed.

if ( (isset($sanerequest['djid'])) && (isset($sanerequest['host'])) ) {
	$data = uhqradio_sam_djtest($sanerequest['djid'],$sanerequest['host']);
} else {
	$data['error'] = "Insufficient Parameters";
}

// Render Template

$xoopsTpl->assign('data',$data);
$xoopsTpl->display("db:pop/uhqradio_pop_djtest.html");

?>