<?php

/*
UHQ-Radio :: XOOPS Module for IceCast Authentication
Copyright (C) 2008-2015 :: Ian A. Underwood :: xoops@underwood-hq.org

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

include __DIR__ . '/../../../mainfile.php';
include XOOPS_ROOT_PATH . '/include/cp_header.php';

require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

include __DIR__ . '/../include/functions.php';
include __DIR__ . '/../include/forms.php';
include __DIR__ . '/../include/sanity.php';
include __DIR__ . '/functions.inc.php';
include __DIR__ . '/header.php';

require_once XOOPS_ROOT_PATH . '/class/template.php';
if (!isset($xoopsTpl)) {
    $xoopsTpl = new XoopsTpl();
}
$xoopsTpl->xoops_setCaching(0);

// Grab operator if we have it

if (isset($_REQUEST['op'])) {
    $op = $_REQUEST['op'];
} else {
    $op = 'none';
}

xoops_cp_header();
//adminMenu(1);

// Do sanity checks here

$sane_REQUEST = uhqradio_dosanity();

switch ($op) {
    case 'insert':
        if (isset($_REQUEST['verify']) && ($sane_REQUEST['userkey'] > 0)) {
            $query  = uhqradio_airform_insertquery($sane_REQUEST);
            $result = $xoopsDB->queryF($query);

            if ($result === false) {
                redirect_header('airstaff.php', 30, _AM_UHQRADIO_SQLERR . $query . "\r\n" . $xoopsDB->error());
                break;
            } else {
                redirect_header('airstaff.php', 5, _AM_UHQRADIO_ADDED . $_REQUEST['djid']);
                break;
            }
        } else {
            uhqradio_airform(_AM_UHQRADIO_ADDAIRSTAFF, 'airstaff.php');
        }
        break;
    case 'edit':
        // Verify we have minimum parameters
        if ($_REQUEST['djid']) {

            // Load Records
            $query = 'SELECT * FROM ' . $xoopsDB->prefix('uhqradio_airstaff');
            $query .= " WHERE djid = '" . $sane_REQUEST['djid'] . '\'';

            $result = $xoopsDB->queryF($query);
            if ($result === false) {
                redirect_header('airstaff.php', 30, _AM_UHQRADIO_SQLERR . $query . "\r\n" . $xoopsDB->error());
                break;
            }

            if (isset($_REQUEST['verify']) && ($sane_REQUEST['userkey'] > 0)) {
                // Make Changes
                $query  = uhqradio_airform_updatequery($sane_REQUEST);
                $result = $xoopsDB->queryF($query);
                if ($result === false) {
                    redirect_header('airstaff.php', 30, _AM_UHQRADIO_SQLERR . $query . "\r\n" . $xoopsDB->error());
                    break;
                } else {
                    redirect_header('airstaff.php', 5, _AM_UHQRADIO_EDITED . $_REQUEST['djid']);
                    break;
                }
            } else {
                $row = $xoopsDB->fetchArray($result);
                uhqradio_airform(_AM_UHQRADIO_EDITAIRSTAFF . ' : ' . $row['djid'], 'airstaff.php', $row, 'edit');
            }
        } else {
            redirect_header('airstaff.php', 10, _AM_UHQRADIO_PARAMERR);
        }
        break;
    case 'delete':

        // Verify we have minimum parameters
        if ($_REQUEST['djid']) {

            // Load Record
            $query = 'SELECT * FROM ' . $xoopsDB->prefix('uhqradio_airstaff');
            $query .= " WHERE djid = '" . $sane_REQUEST['djid'] . '\'';

            $result = $xoopsDB->queryF($query);
            if ($result === false) {
                redirect_header('airstaff.php', 30, _AM_UHQRADIO_SQLERR . $query . "\r\n" . $xoopsDB->error());
                break;
            }
            if (isset($_REQUEST['verify'])) {

                // Delete Info
                $query  = 'DELETE FROM ' . $xoopsDB->prefix('uhqradio_airstaff');
                $query  .= " WHERE djid = '" . $sane_REQUEST['djid'] . '\'';
                $result = $xoopsDB->queryF($query);
                if ($result === false) {
                    redirect_header('airstaff.php', 30, _AM_UHQRADIO_SQLERR . $query . "\r\n" . $xoopsDB->error());
                    break;
                } else {
                    redirect_header('airstaff.php', 5, _AM_UHQRADIO_DELETED . $sane_REQUEST['djid']);
                    break;
                }
                // Use this space to delete any historical data in other tables.
            } else {
                // Display form
                $row = $xoopsDB->fetchArray($result);
                uhqradio_del_airform($row);
            }
        } else {
            redirect_header('airstaff.php', 10, _AM_UHQRADIO_PARAMERR);
        }
        break;
    case 'none':
    default:
        // Check for staff
        if (uhqradio_airstaffsum()) {
            // List DJs
            $query  = 'SELECT * FROM ' . $xoopsDB->prefix('uhqradio_airstaff') . ' ORDER BY djid';
            $result = $xoopsDB->queryF($query);
            if ($result === false) {
                $xoospTpl->assign('error', $xoopsDB->error());
            } else {
                $data = array();
                $i    = 1;
                while ($row = $xoopsDB->fetchArray($result)) {
                    $data[$i]           = $row;
                    $data[$i]['djname'] = uhqradio_username($row['userkey']);
                    $i++;
                }
                $xoopsTpl->assign('data', $data);
            }
        }
        $xoopsTpl->display('db:admin/uhqradio_admin_airstaff.tpl');
        break;
}

xoops_cp_footer();
