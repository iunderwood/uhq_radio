<?php

/*    ecu.php

    This script updates all XML caches based upon the value passed to $GET['update']:
        pop - Updates XML files used to derive listener counts.
        txt - Updates XML files used to derive text information.

    A module option password is required.  This is to prevent a DoS by constant XML updating.

    This script will eventually update both counter histories and song histories when they are implemented.

*/

$timestart = gettimeofday(true);

include __DIR__ . '/../../mainfile.php';

require_once XOOPS_ROOT_PATH . '/modules/uhq_radio/include/modoptions.php';
require_once XOOPS_ROOT_PATH . '/modules/uhq_radio/include/functions.php';
require_once XOOPS_ROOT_PATH . '/modules/uhq_radio/include/sanity.php';

// All logging functions should be turned off for this page.
$xoopsLogger->activated = false;

$data = [];

$sane_REQUEST = uhqradio_dosanity();

/**
 * @param $type
 * @return bool
 */
function update_cache($type)
{
    global $xoopsDB;
    global $sane_REQUEST;

    $error = null;

    // Check update PW first.

    if (false === uhqradio_updatepw($_REQUEST['updatepw'])) {
        $error = _MD_UHQRADIO_ECU_BADPW;

        return false;
    }

    // Gather the list of unique mountpoints based on the update type.

    $query = 'SELECT DISTINCT server,port,type,auth_un,auth_pw FROM ' . $xoopsDB->prefix('uhqradio_mountpoints');
    $query .= ' WHERE mpid IN (';

    switch ($_REQUEST['update']) {
        case 'pop':
            $query .= 'SELECT DISTINCT mpid FROM ' . $xoopsDB->prefix('uhqradio_countmap');
            break;
        case 'txt':
            $query .= 'SELECT DISTINCT text_mpid FROM ' . $xoopsDB->prefix('uhqradio_channels') . ' WHERE text_mpid > 0';
            break;
        default:
            $error = _MD_UHQRADIO_ECU_BADTYPE . $_REQUEST['update'];

            return false;
            break;
    }
    $query .= ')';

    // Get the list of mountpoints to grab!

    $result = $xoopsDB->queryF($query);

    if (false === $result) {
        $error = _MD_UHQRADIO_ERROR_SQL . $xoopsDB->error();

        return false;
    }

    // Refresh all caches of a specific type.

    $xmlfail = 0;
    $xmlpass = 0;

    while ($mountinfo = $xoopsDB->fetchArray($result)) {
        // Set XML path & authentication
        $path = uhqradio_xmlpath($mountinfo['type']);
        $auth = uhqradio_svrauth($mountinfo['type'], $mountinfo['auth_un'], $mountinfo['auth_pw']);

        // Get mountpoint XML
        $xmldata = null;
        $errno   = uhqradio_fetchxml($mountinfo['server'], $mountinfo['port'], $path, $auth, $xmldata, 1);
        if ($errno) {
            ++$xmlfail;
        } else {
            ++$xmlpass;
        }
    }

    // Now that the caches are up-to-date, process any history requirements.

    switch ($_REQUEST['update']) {
        case 'pop':
            // Update all mountpoints, if the option is set.
            if (uhqradio_opt_savelh()) {
                uhqradio_updatelh();
            }
            break;
        case 'txt':
            // Only update song history if a specific channel is passed to ECU, option is set, and requesting IP matches source IP
            if (isset($sane_REQUEST['chid']) && uhqradio_opt_savesh()) {
                $djinfo = uhqradio_dj_onair($sane_REQUEST['chid']);
                if ($djinfo['djip'] == $_SERVER['REMOTE_ADDR']) {
                    uhqradio_updatesh($sane_REQUEST['chid']);
                }
            }
            break;
    }
}

if (isset($_REQUEST['update']) && isset($_REQUEST['updatepw'])) {
    update_cache($_REQUEST['update']);
} else {
    $error = _MD_UHQRADIO_ECU_IP;
}

if ($error) {
    echo $error . "\r\n";
} else {
    echo "\r\n";
    if ($xmlpass) {
        echo _MD_UHQRADIO_ECU_XMLP . $xmlpass . "\r\n";
    }
    if ($xmlfail) {
        echo _MD_UHQRADIO_ECU_XMLF . $xmlfail;
    }
}
echo 'Time to complete: ';
printf('%0.2f seconds.', gettimeofday(true) - $timestart);
