<?php
include __DIR__ . '/../../../mainfile.php';
include XOOPS_ROOT_PATH . '/include/cp_header.php';

include __DIR__ . '/functions.inc.php';
include __DIR__ . '/../include/functions.php';
include __DIR__ . '/../include/forms.php';
include __DIR__ . '/../include/sanity.php';

require_once XOOPS_ROOT_PATH . '/class/template.php';
if (!isset($xoopsTpl)) {
    $xoopsTpl = new XoopsTpl();
}
$xoopsTpl->xoops_setCaching(0);

include __DIR__ . '/header.php';

// Grab operator if we have it

if (isset($_REQUEST['op'])) {
    $op = $_REQUEST['op'];
} else {
    $op = 'none';
}

xoops_cp_header();

//adminMenu(3);

// Do sanity checks here

$sane_REQUEST = uhqradio_dosanity();

switch ($op) {
    case 'insert':
        if (isset($_REQUEST['verify'])) {
            $query  = uhqradio_channelform_insertquery($sane_REQUEST);
            $result = $xoopsDB->queryF($query);

            if ($result === false) {
                redirect_header('channels.php', 30, _AM_UHQRADIO_SQLERR . $query . "\r\n" . $xoopsDB->error());
                break;
            } else {
                redirect_header('channels.php', 5, _AM_UHQRADIO_ADDED . $sane_REQUEST['chan_name']);
                break;
            }
        } else {
            uhqradio_channelform(_AM_UHQRADIO_ADDCHANNEL, 'channels.php');
        }
        break;
    case 'addch':
        // Verify we have minimum parameters
        if (isset($_REQUEST['chid'])) {

            // Load Records
            $query = 'SELECT * FROM ' . $xoopsDB->prefix('uhqradio_channels');
            $query .= " WHERE chid = '" . $sane_REQUEST['chid'] . '\'';

            $result = $xoopsDB->queryF($query);
            if ($result === false) {
                redirect_header('channels.php', 30, _AM_UHQRADIO_SQLERR . $query . "\r\n" . $xoopsDB->error());
                break;
            }

            if (isset($_REQUEST['verify']) && ($sane_REQUEST['chid'] > 0) && ($sane_REQUEST['mpid'] > 0)) {
                // Make Changes
                $query  = uhqradio_mapform_insertquery($sane_REQUEST);
                $result = $xoopsDB->queryF($query);
                if ($result === false) {
                    redirect_header('channels.php', 30, _AM_UHQRADIO_SQLERR . $query . "\r\n" . $xoopsDB->error());
                    break;
                } else {
                    redirect_header('channels.php', 5, _AM_UHQRADIO_ADDED . $_REQUEST['chid'] . ':' . $sane_REQUEST['chan_name'] . ' map.');
                    break;
                }
            } else {
                $row = $xoopsDB->fetchArray($result);
                uhqradio_mapform(_AM_UHQRADIO_ADDMAP . ' :: ' . $row['chan_name'], 'channels.php', $row, 'addch');
            }
        } else {
            redirect_header('channels.php', 10, _AM_UHQRADIO_PARAMERR);
        }
        break;
    case 'delch':
        if (isset($_REQUEST['chid']) && isset($_REQUEST['mpid'])) {
            // Clear mapping
            $query = 'DELETE FROM ' . $xoopsDB->prefix('uhqradio_countmap');
            $query .= " WHERE chid = '" . $sane_REQUEST['chid'] . '\' AND mpid = \'' . $sane_REQUEST['mpid'] . '\'';

            $result = $xoopsDB->queryF($query);

            if ($result === false) {
                redirect_header('channels.php', 30, _AM_UHQRADIO_SQLERR . $query . "\r\n" . $xoopsDB->error());
                break;
            } else {
                redirect_header('channels.php', 5, _AM_UHQRADIO_UNMAP_OK);
            }
        } else {
            redirect_header('channels.php', 10, _AM_UHQRADIO_PARAMERR);
        }
        break;
    case 'edit':
        // Verify we have minimum parameters
        if (isset($_REQUEST['chid'])) {

            // Load Records
            $query = 'SELECT * FROM ' . $xoopsDB->prefix('uhqradio_channels');
            $query .= " WHERE chid = '" . $sane_REQUEST['chid'] . '\'';

            $result = $xoopsDB->queryF($query);
            if ($result === false) {
                redirect_header('channels.php', 30, _AM_UHQRADIO_SQLERR . $query . "\r\n" . $xoopsDB->error());
                break;
            }

            if (isset($_REQUEST['verify']) && ($sane_REQUEST['chid'] > 0)) {
                // Make Changes
                $query  = uhqradio_channelform_updatequery($sane_REQUEST);
                $result = $xoopsDB->queryF($query);
                if ($result === false) {
                    redirect_header('channels.php', 30, _AM_UHQRADIO_SQLERR . $query . "\r\n" . $xoopsDB->error());
                    break;
                } else {
                    redirect_header('channels.php', 5, _AM_UHQRADIO_EDITED . $_REQUEST['chid'] . ':' . $sane_REQUEST['chan_name']);
                    break;
                }
            } else {
                $row = $xoopsDB->fetchArray($result);
                uhqradio_channelform(_AM_UHQRADIO_EDITCHANNEL . ' :: ' . $row['chan_name'], 'channels.php', $row, 'edit');
            }
        } else {
            redirect_header('channels.php', 10, _AM_UHQRADIO_PARAMERR);
        }
        break;
    case 'delete':
        // Verify we have minimum parameters
        if (isset($_REQUEST['chid'])) {

            // Load Record
            $query = 'SELECT * FROM ' . $xoopsDB->prefix('uhqradio_channels');
            $query .= " WHERE chid = '" . $sane_REQUEST['chid'] . '\'';

            $result = $xoopsDB->queryF($query);
            if ($result === false) {
                redirect_header('channels.php', 30, _AM_UHQRADIO_SQLERR . $query . "\r\n" . $xoopsDB->error());
                break;
            }
            if (isset($_REQUEST['verify'])) {

                // Delete Info
                $query  = 'DELETE FROM ' . $xoopsDB->prefix('uhqradio_channels');
                $query  .= " WHERE chid = '" . $sane_REQUEST['chid'] . '\'';
                $result = $xoopsDB->queryF($query);
                if ($result === false) {
                    redirect_header('channels.php', 30, _AM_UHQRADIO_SQLERR . $query . "\r\n" . $xoopsDB->error());
                    break;
                } else {
                    $textresult = _AM_UHQRADIO_DELETED . $sane_REQUEST['chan_name'];

                    // Clear channel from counter map if this mountpoint is being used for count info

                    $query  = 'DELETE FROM ' . $xoopsDB->prefix('uhqradio_countmap');
                    $query  .= " WHERE chid = '" . $sane_REQUEST['chid'] . '\'';
                    $result = $xoopsDB->queryF($query);
                    if ($result === false) {
                        $textresult .= _AM_UHQRADIO_CH_COUNTCLEAR_NOK . ' (' . $xoopsDB->error() . ')';
                    } else {
                        $textresult .= _AM_UHQRADIO_CH_COUNTCLEAR_OK;
                    }

                    redirect_header('channels.php', 5, $textresult);
                    break;
                }
                // Use this space to delete any historical data in other tables.
            } else {
                // Display form
                $row = $xoopsDB->fetchArray($result);
                uhqradio_channelform_del($row);
            }
        } else {
            redirect_header('channels.php', 10, _AM_UHQRADIO_PARAMERR);
        }
        break;
    case 'none':
    default:
        if (uhqradio_channelsum()) {
            // List Channels

            $query  = 'SELECT * FROM ' . $xoopsDB->prefix('uhqradio_channels') . ' ORDER BY chan_name';
            $result = $xoopsDB->queryF($query);
            if ($result === false) {
                $xoospTpl->assign('error', $xoopsDB->error());
            } else {
                $data = array();
                $i    = 1;
                while ($row = $xoopsDB->fetchArray($result)) {

                    // Locate and load the mountpoint data for each channel.

                    $query   = 'SELECT mp.* FROM ' . $xoopsDB->prefix('uhqradio_countmap') . ' cm, ';
                    $query   .= $xoopsDB->prefix('uhqradio_mountpoints') . ' mp ';
                    $query   .= "WHERE cm.mpid = mp.mpid AND cm.chid = '" . $row['chid'] . '\'';
                    $result2 = $xoopsDB->queryF($query);

                    if ($result2 === false) {
                        echo "d'oh!";
                    } else {
                        $mapdata = array();
                        $j       = 1;
                        while ($crow = $xoopsDB->fetchArray($result2)) {
                            $mapdata[$j] = $crow;
                            $j++;
                        }
                    }

                    // Assign channel data
                    $data[$i]        = $row;
                    $data[$i]['map'] = $mapdata;
                    $i++;
                }
                $xoopsTpl->assign('data', $data);
            }
        }
        $xoopsTpl->display('db:admin/uhqradio_admin_channels.tpl');
        break;
}

xoops_cp_footer();
