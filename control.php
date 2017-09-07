<?php

/*
UHQ-Radio :: XOOPS Module for Internet Radio Station Functions
Copyright (C) 2008-2011 :: Ian A. Underwood :: xoops@underwood-hq.org

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

// Request handler for the control block for the autoplayer.
//
// All responses will be redirects to the last page.

include __DIR__ . '/../../mainfile.php';

include __DIR__ . '/include/functions.php';

// Set a referrer.

if ($_SERVER['HTTP_REFERER']) {
    $refer = $_SERVER['HTTP_REFERER'];
} else {
    $refer = '/';
}

// Process Request, but only an operation is present.

if ($_REQUEST['op']) {

    // Make sure someone is logged in.

    if (!$xoopsUser) {
        redirect_header($refer, 5, _MD_UHQRADIO_ERROR_LOGIN);
        break;
    }

    // Load group permissions for the block we want.

    $query = 'SELECT DISTINCT xp.gperm_groupid FROM ';
    $query .= $xoopsDB->prefix('newblocks') . ' xb, ';
    $query .= $xoopsDB->prefix('group_permission') . ' xp ';
    $query .= "WHERE xb.dirname = 'uhq_radio' AND xb.show_func = 'b_uhqradio_control_show' ";
    $query .= 'AND xb.bid = xp.gperm_itemid ';
    $query .= 'ORDER BY xb.bid, xp.gperm_itemid';

    $result = $xoopsDB->queryF($query);
    if ($result === false) {
        redirect_header($refer, 1, _MD_UHQRADIO_ERROR_NOAUTH);
        break;
    }

    $blockgroups = [];
    while ($row = $xoopsDB->fetchRow($result)) {
        $blockgroups[] = $row[0];
    }

    // Reject if the user does not have permission.

    if (count(array_intersect($xoopsUser->getGroups(), $blockgroups)) == 0) {
        redirect_header($refer, 10, _MD_UHQRADIO_ERROR_NOPERM);
        break;
    }

    // Load options from Block

    $query = 'SELECT options FROM ';
    $query .= $xoopsDB->prefix('newblocks');
    $query .= " WHERE dirname = 'uhq_radio' AND show_func = 'b_uhqradio_control_show'";

    $result = $xoopsDB->queryF($query);
    if ($result === false) {
        redirect_header($refer, 1, _MD_UHQRADIO_ERROR_BLOCKOPT);
        break;
    }
    $row     = $xoopsDB->fetchRow($result);
    $options = explode('|', $row[0]);

    // Process Request

    switch ($_REQUEST['op']) {
        case 'start':
            if ($options[0]) {
                if (uhqradio_externalurl($options[0])) {
                    redirect_header($refer, 5, _MD_UHQRADIO_CMD_START_OK);
                } else {
                    redirect_header($refer, 5, _MD_UHQRADIO_CMD_START_NOK);
                }
            } else {
                redirect_header($refer, 5, _MD_UHQRADIO_CMD_START_NOCONF);
            }

            return;
        case 'stop':
            if ($options[1]) {
                if (uhqradio_externalurl($options[1])) {
                    redirect_header($refer, 5, _MD_UHQRADIO_CMD_STOP_OK);
                } else {
                    redirect_header($refer, 5, _MD_UHQRADIO_CMD_STOP_NOK);
                }
            } else {
                redirect_header($refer, 5, _MD_UHQRADIO_CMD_STOP_NOCONF);
            }

            return;
        case 'skip':
            if ($options[2]) {
                if (uhqradio_externalurl($options[2])) {
                    redirect_header($refer, 5, _MD_UHQRADIO_CMD_SKIP_OK);
                } else {
                    redirect_header($refer, 5, _MD_UHQRADIO_CMD_SKIP_NOK);
                }
            } else {
                redirect_header($refer, 5, _MD_UHQRADIO_CMD_SKIP_NOCONF);
            }

            return;
        case 'stopnow':
            if ($options[3]) {
                if (uhqradio_externalurl($options[3])) {
                    redirect_header($refer, 5, _MD_UHQRADIO_CMD_STOPNOW_OK);
                } else {
                    redirect_header($refer, 5, _MD_UHQRADIO_CMD_STOPNOW_NOK);
                }
            } else {
                redirect_header($refer, 5, _MD_UHQRADIO_CMD_STOPNOW_NOCONF);
            }

            return;
        case 'rewind':
            if ($options[4]) {
                if (uhqradio_externalurl($options[4])) {
                    redirect_header($refer, 5, _MD_UHQRADIO_CMD_REW_OK);
                } else {
                    redirect_header($refer, 5, _MD_UHQRADIO_CMD_REW_NOK);
                }
            } else {
                redirect_header($refer, 5, _MD_UHQRADIO_CMD_REW_NOCONF);
            }

            return;
        default:
            redirect_header($refer, 1, _MD_UHQRADIO_ERROR_UNSPOORTED . $_REQUEST['op']);

            return;
    }
} else {

    // Handle if there is no operation

    if ($_SERVER['HTTP_REFERER']) {
        redirect_header($refer, 1, _MD_UHQRADIO_ERROR_NOOP);
    } else {
        redirect_header('/', 1, _MD_UHQRADIO_ERROR_DIRECT);
    }
}
