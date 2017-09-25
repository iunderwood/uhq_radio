<?php

include __DIR__ . '/../../mainfile.php';

// Need to load up a hell of a lot of goodies here.

require_once XOOPS_ROOT_PATH . '/modules/uhq_radio/include/sanity.php';
require_once XOOPS_ROOT_PATH . '/modules/uhq_radio/include/functions.php';
require_once XOOPS_ROOT_PATH . '/modules/uhq_radio/include/sambc.php';

$sane_REQUEST = uhqradio_dosanity();

if (isset($_REQUEST['op'])) {
    $op = $_REQUEST['op'];
} else {
    $op = 'b_album';
}

if (isset($sane_REQUEST['start'])) {
    $start = $sane_REQUEST['start'];
} else {
    $start = null;
}

$data = [];

// Do the Header

$GLOBALS['xoopsOption']['template_main'] = 'uhqradio_index.html';

include XOOPS_ROOT_PATH . '/header.php';

/**
 * @return mixed
 */
function uhqradio_nre_open()
{
    global $xoopsDB;
    global $samdb;

    // Prepare remote DB connection

    $info = uhqradio_dj_onair(1);

    if (false === $info) {
        $block['error'] = _MB_UHQRADIO_ERROR . $xoopsDB->error();

        return $block;
    }

    if (0 == $info['djip']) {
        $block['error'] = 'No Source IP Found';

        return $block;
    }

    // Open Database

    $samdb = uhqradio_sam_opendb($info['djid'], $info['djip']);
    if (false === $samdb) {
        $block['error'] = 'Unable to contact DB ' . $info['djid'] . ' at ' . $info['djip'];

        return $block;
    }

    //  Assign our DJ info

    $block['djid'] = $info['djid'];

    return $block;
}

/**
 * @param $view
 * @param $start
 * @return mixed
 */
function uhqradio_nre_summary($view, $start)
{
    global $xoopsDB;
    global $samdb;

    // Items in the database.

    switch ($view) {
        case 'L':
            $block['totalcount'] = uhqradio_sam_countalbum($samdb);
            break;
        case 'A':
            $block['totalcount'] = uhqradio_sam_countartist($samdb);
            break;
    }

    // Handle starting letters & Generate List

    if (null != $start) {
        $block['start'] = $start;

        // Count up either the number of artists or albums.
        switch ($view) {
            case 'L':
                $where              = uhqradio_sam_where_letter($start, 'L');
                $block['itemcount'] = uhqradio_sam_countalbum($samdb, $where);
                $block['itemlist']  = uhqradio_sam_displayalbums($samdb, $where);
                break;
            case 'A':
                $where              = uhqradio_sam_where_letter($start, 'A');
                $block['itemcount'] = uhqradio_sam_countartist($samdb, $where);
                $block['itemlist']  = uhqradio_sam_displayartists($samdb, $where);
                break;
        }
    } else {
        $where = null;
    }

    return $block;
}

/**
 * @param $op
 * @return mixed
 */
function uhqradio_nre_footer($op)
{
    global $xoopsDB;
    global $samdb;

    // Close and return

    uhqradio_sam_closedb($samdb);

    switch ($op) {
        case 's_album':
            $block['az'] = uhqradio_aznum_array('op=b_album&start');
            break;
        case 's_artist':
            $block['az'] = uhqradio_aznum_array('op=b_artist&start');
            break;
        default:
            $block['az'] = uhqradio_aznum_array('op=' . $op . '&start');
            break;
    }
    $block['baseurl'] = uhqradio_opt_albumurl();

    return $block;
}

/**
 * @return array|mixed
 */
function uhqradio_index()
{
    global $op;
    global $start;
    global $samdb;

    // Verify that we've enable automation integration.

    if (false === uhqradio_samint()) {
        $data['samint'] = 0;

        return $data;
    }

    $data           = uhqradio_nre_open();
    $data['samint'] = 1;

    // THe meat of showing the playlist and showing the raw information will go here.
    $data['op'] = $op;

    switch ($op) {
        case 'b_artist':
            $data = array_merge($data, uhqradio_nre_summary('A', $start));
            break;
        case 's_artist':
            $data = array_merge($data, uhqradio_nre_summary('A', $start));
            $data = array_merge($data, uhqradio_sam_getartist($samdb, $sane_REQUEST['info']));
            break;
        case 'b_album':
            $data               = array_merge($data, uhqradio_nre_summary('L', $start));
            $data['albumlimit'] = 10;
            $data['newalbums']  = uhqradio_sam_lastxalbums($samdb, $data['albumlimit']);
            break;
        case 's_album':
            $data = array_merge($data, uhqradio_nre_summary('L', $start));
            $data = array_merge($data, uhqradio_sam_getalbum($samdb, $sane_REQUEST['info']));
            break;
        case 'b_year':
            $data             = array_merge($data, uhqradio_nre_summary('L', $start));
            $data['yearlist'] = uhqradio_sam_yearlist($samdb);
            break;
        default:
            break;
    }

    $data = array_merge($data, uhqradio_nre_footer($op));

    echo '<pre>';
    print_r($data);
    echo '</pre>';

    return $data;

    // Welcome to the New Request Engine.  Holy shit.
}

$xoopsTpl->assign('data', uhqradio_index());

include XOOPS_ROOT_PATH . '/footer.php';
