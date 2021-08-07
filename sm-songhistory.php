<?php
include __DIR__ . '/../../mainfile.php';

$GLOBALS['xoopsOption']['template_main'] = 'uhqradio_sm_songhistory.tpl';

include XOOPS_ROOT_PATH . '/header.php';

// Modular Pieces

require_once XOOPS_ROOT_PATH . '/modules/uhqradio/include/functions.php';
require_once XOOPS_ROOT_PATH . '/modules/uhqradio/include/modoptions.php';
require_once XOOPS_ROOT_PATH . '/modules/uhqradio/include/rawdata.php';

// Generic Page: Last 10 songs.

// Need a way to work the multichannel aspects at some point.
$chid = 1;

if (uhqradio_opt_savesh()) {
    $data['baseurl'] = uhqradio_opt_albumurl();
    $xoopsTpl->assign('data', $data);

    $shistory = uhqradio_data_shistory($chid, uhqradio_opt_songhistlen());
    if (false !== $shistory) {
        $xoopsTpl->assign('shistory', $shistory);
    }
}

include XOOPS_ROOT_PATH . '/footer.php';
