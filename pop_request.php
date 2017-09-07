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
function uhqradio_request_dorequest($songid)
{
    global $xoopsDB;
    global $xoopsUser;
    global $samdb;
    global $uhqradio_request;

    $block   = [];
    $xmldata = null;

    // Return a blank block if SAM Integration isn't enabled.
    if (uhqradio_samint() === false) {
        $block['samint'] = 0;

        return $block;
    }
    $block['samint'] = 1;

    // Fail if requests are unavailable.
    if (!$uhqradio_request) {
        $block['code']    = '900';
        $block['message'] = 'Requests not available at this time.';

        return $block;
    }

    // Get DJ On-Air

    $info = uhqradio_dj_onair(1);

    if ($info === false) {
        $block['code']    = '901';
        $block['message'] = _MB_UHQRADIO_ERROR . $xoopsDB->error();

        return $block;
    }

    if ($info['djip'] == 0) {
        $data['code']   = '902';
        $block['error'] = 'No Source IP Found';

        return $block;
    }

    $djinfo = uhqradio_dj_info($info['djid']);

    // Place the request

    $xmlpath = '/req/?songID=' . $songid . '&host=' . urlencode($_SERVER['REMOTE_ADDR']);

    $error = uhqradio_fetchxml($info['djip'], $djinfo['sam_port'], $xmlpath, null, $xmldata, 1);

    if ($error) {
        $block['error'] = 'Error #' . $error . ' placing request.';

        return $block;
    }

    $block['code']    = uhqradio_isolatexml($xmldata, '<code>', '</code>');
    $block['message'] = uhqradio_isolatexml($xmldata, '<message>', '</message>');
    $block['reqID']   = uhqradio_isolatexml($xmldata, '<requestID>', '</requestID>');

    if ($block['reqID'] < 1) {
        // If the request is rejected, don't get any more info.
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

    // Attach a name to the request

    if ($xoopsUser) {
        $uid      = $xoopsUser->getVar('uid');
        $username = uhqradio_username($uid);
    } else {
        $username = null;
    }

    uhqradio_sam_reqname($samdb, (int)$block['reqID'], $username);

    // Close Database

    uhqradio_sam_closedb($samdb);

    return $block;
}

$data = uhqradio_request_dorequest($sanerequest['songid']);

$xoopsTpl->assign('data', $data);

// Render Template

$xoopsTpl->display('db:pop/uhqradio_pop_request.tpl');
