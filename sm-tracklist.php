<?php

/*
UHQ-Radio :: XOOPS Live Radio Broadcast Module
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

include __DIR__ . '/../../mainfile.php';
include __DIR__ . '/include/sanity.php';
include __DIR__ . '/include/functions.php';
require_once XOOPS_ROOT_PATH . '/modules/uhq_radio/include/rawdata.php';
require_once XOOPS_ROOT_PATH . '/modules/uhq_radio/include/modoptions.php';
require_once XOOPS_ROOT_PATH . '/modules/uhq_radio/include/sambc.php';

$sane_REQUEST = uhqradio_dosanity();

if (isset($_REQUEST['op'])) {
    $op = $_REQUEST['op'];
} else {
    $op = 'none';
}

// Do the Header

$GLOBALS['xoopsOption']['template_main'] = 'uhqradio_playlist.html';

include XOOPS_ROOT_PATH . '/header.php';

// THe meat of showing the playlist and showing the raw information will go here.

$data = [];

/**
 * @param int  $start
 * @param int  $limit
 * @param null $search
 * @return array
 */
function uhqradio_playlist_dopage($start = 0, $limit = 20, $search = null)
{
    global $xoopsDB;
    global $samdb;
    global $uhqradio_request;

    $block = [];

    // Return a blank block if SAM Integration isn't enabled.
    if (uhqradio_samint() === false) {
        $block['samint'] = 0;

        return $block;
    }
    $block['samint'] = 1;

    // Prepare remote DB connection

    $info = uhqradio_dj_onair(1);

    if ($info === false) {
        $block['error'] = _MB_UHQRADIO_ERROR . $xoopsDB->error();

        return $block;
    }

    if ($info['djip'] == 0) {
        $block['error'] = 'No Source IP Found';

        return $block;
    }

    // Open Database

    $samdb = uhqradio_sam_opendb($info['djid'], $info['djip']);
    if ($samdb === false) {
        $block['error'] = 'Unable to contact DB ' . $info['djid'] . ' at ' . $info['djip'];

        return $block;
    }

    // Build out search query.

    $where = uhqradio_sam_where($search, 0);

    // Get Song Count

    $block['trackcount'] = uhqradio_sam_countpl($samdb, $where);
    $block['tracklist']  = uhqradio_sam_displaypl($samdb, $where, $start, $limit);
    $block['start']      = $start;
    $block['limit']      = $limit;

    // Close Database

    uhqradio_sam_closedb($samdb);

    return $block;
}

// Initialize start if it's not in the URL.

$start = 0;
if (isset($sane_REQUEST['pl_start'])) {
    $start = $sane_REQUEST['pl_start'];
}

// Initialize limit

$limit = 15;

if (isset($sane_REQUEST['pl_limit'])) {
    // Take limit from command line and pass to a cookie.  Good for 7 days.
    $limit = $sane_REQUEST['pl_limit'];
    setcookie('pl_limit_cookie', $limit, time() + 86400 * 7);
} elseif (isset($_COOKIE['pl_limit_cookie'])) {
    // Check and assign limit from cookie.
    if ((int)$_COOKIE['pl_limit_cookie'] < 0) {
        $limit = 5;
        setcookie('pl_limit_cookie', $limit, time() + 86400 * 7);
    } elseif ((int)$_COOKIE['pl_limit_cookie'] > 50) {
        $limit = 50;
        setcookie('pl_limit_cookie', $limit, time() + 86400 * 7);
    } else {
        $limit = (int)$_COOKIE['pl_limit_cookie'];
    }
}

// Fill in our search if we have one.

if (isset($sane_REQUEST['pl_search'])) {
    $search = $sane_REQUEST['pl_search'];
} else {
    $search = null;
}

$data = uhqradio_playlist_dopage($start, $limit, $search);

$data['increments'][0] = 5;
$dara['increments'][1] = 10;
$data['increments'][2] = 15;
$data['increments'][3] = 25;
$data['increments'][4] = 50;

$data['showreq'] = $uhqradio_request;

if (isset($_REQUEST['pl_search'])) {
    $data['rawsearch'] = stripslashes($search);
    $data['urlsearch'] = urlencode($data['rawsearch']);
}

$xoopsTpl->assign('data', $data);

// Do the Footer

include XOOPS_ROOT_PATH . '/footer.php';
