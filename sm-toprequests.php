<?php
include __DIR__ . '/../../mainfile.php';

$GLOBALS['xoopsOption']['template_main'] = 'uhqradio_sm_toprequests.html';

include XOOPS_ROOT_PATH . '/header.php';

// Modular Pieces

require_once XOOPS_ROOT_PATH . '/modules/uhq_radio/include/functions.php';
require_once XOOPS_ROOT_PATH . '/modules/uhq_radio/include/modoptions.php';
require_once XOOPS_ROOT_PATH . '/modules/uhq_radio/include/rawdata.php';

// Need a way to work the multichannel aspects at some point.
$chid = 1;

if (uhqradio_opt_savesh()) {
    $data['baseurl'] = uhqradio_opt_albumurl();
    $xoopsTpl->assign('data', $data);

    $chartall = uhqradio_data_requestchart($chid, uhqradio_opt_reqlistlen());
    if ($chartall !== false) {
        $xoopsTpl->assign('chartall', $chartall);
    }

    $lastmonth = uhqradio_data_requestchart($chid, uhqradio_opt_reqlistlen(), 'LM');
    if ($lastmonth !== false) {
        $xoopsTpl->assign('lastmonth', $lastmonth);
    }
}

include XOOPS_ROOT_PATH . '/footer.php';
