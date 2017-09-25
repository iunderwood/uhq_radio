<?php

/*
    This file takes care of the entirety in function for the facebook application.
    Moved to its own directory, because this will likely be considered an add-on.
*/

include __DIR__ . '/../../../../mainfile.php';

require_once XOOPS_ROOT_PATH . '/class/template.php';
require_once XOOPS_ROOT_PATH . '/modules/uhq_radio/addons/facebook/class/facebook/facebook.php';

require_once XOOPS_ROOT_PATH . '/modules/uhq_radio/include/functions.php';
require_once XOOPS_ROOT_PATH . '/modules/uhq_radio/include/modoptions.php';
require_once XOOPS_ROOT_PATH . '/modules/uhq_radio/include/rawdata.php';

// Load Add-on Language

if (file_exists(XOOPS_ROOT_PATH . '/modules/uhq_radio/addons/facebook/language/' . $xoopsConfig['language'] . '/fb.php')) {
    require_once XOOPS_ROOT_PATH . '/modules/uhq_radio/addons/facebook/language/' . $xoopsConfig['language'] . '/fb.php';
} else {
    require_once XOOPS_ROOT_PATH . '/modules/uhq_radio/addons/facebook/language/english/fb.php';
}

// Turn off logger

$xoopsLogger->activated = false;

// Initialize template

if (!isset($xoopsTpl)) {
    $xoopsTpl = new XoopsTpl();
}

// Init FB Page

$fbapikey = uhqradio_opt_fbapikey();
$fbsecret = uhqradio_opt_fbsecret();

if ((null != $fbapikey) && (null != $fbsecret)) {
    $facebook = new Facebook($fbapikey, $fbsecret);
} else {
    break;
}

// Channel ID for this application is somewhat important

if (isset($_REQUEST['chid'])) {
    $chid = (int)$_REQUEST['chid'];
}

// Load Current Infos

$status = uhqradio_data_status($chid);
if (false !== $status) {
    $xoopsTpl->assign('status', $status);
}

// Load Song History, only if we actually save that sort of thing.

if (uhqradio_opt_savesh()) {
    $shistory = uhqradio_data_shistory($chid);
    if (false !== $shistory) {
        $xoopsTpl->assign('shistory', $shistory);
    }
}

$xoopsTpl->display('file:' . XOOPS_ROOT_PATH . '/modules/uhq_radio/addons/facebook/fbindex.fbml');
