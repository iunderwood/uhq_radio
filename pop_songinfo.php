<?php

include __DIR__ . '/../../mainfile.php';
require_once XOOPS_ROOT_PATH . '/modules/uhq_radio/include/sanity.php';
require_once XOOPS_ROOT_PATH . '/modules/uhq_radio/include/functions.php';
require_once XOOPS_ROOT_PATH . '/modules/uhq_radio/include/rawdata.php';
require_once XOOPS_ROOT_PATH . '/modules/uhq_radio/include/modoptions.php';
require_once XOOPS_ROOT_PATH . '/modules/uhq_radio/include/sambc.php';

// Load Templates & Smarty

require_once XOOPS_ROOT_PATH . '/class/template.php';
if (!isset($xoopsTpl)) {
    $xoopsTpl = new XoopsTpl();
}

$sanerequest = uhqradio_dosanity();

// Header

include XOOPS_ROOT_PATH . '/header.php';

// Song Information Data

$data = [];

/**
 * @param $songid
 * @return array
 */
function uhqradio_playlist_dosonginfo($songid)
{
    global $xoopsDB;
    global $samdb;

    $block = [];

    // Return a blank block if SAM Integration isn't enabled.
    if (uhqradio_samint() === false) {
        $block['samint'] = 0;

        return $block;
    }
    $block['samint'] = 1;

    // Find DJ

    $info = uhqradio_dj_onair(1);

    if ($info === false) {
        $block['error'] = _MB_UHQRADIO_ERROR . $xoopsDB->error();

        return $block;
    }

    if ($info['djip'] == 0) {
        $block['error'] = 'No Source IP Found';

        return $block;
    }

    // Open Remote Database

    $samdb = uhqradio_sam_opendb($info['djid'], $info['djip']);
    if ($samdb === false) {
        $block['error'] = 'Unable to contact DB ' . $info['djid'] . ' at ' . $info['djip'];

        return $block;
    }

    // Get Song Information

    $block['songinfo'] = uhqradio_sam_songinfo($samdb, $songid);
    $block['djid']     = $info['djid'];
    $block['baseurl']  = uhqradio_opt_albumurl();

    // Close Database

    uhqradio_sam_closedb($samdb);

    return $block;
}

// Initialize start/limit if they aren't passed in the URL.

$data            = uhqradio_playlist_dosonginfo($sanerequest['songid']);
$data['showreq'] = $uhqradio_request;

$xoopsTpl->assign('data', $data);

// Render Template

$xoopsTpl->display('db:pop/uhqradio_pop_songinfo.tpl');
