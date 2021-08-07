<?php

/*

This file is meant to be included, and provides functions which provide raw data only.

The idea here is to separate as much data gathering as possible from specific functions
to enhance code reuse, AJAX capability, and provide goodies for Facebook application
support.

*/

use XoopsModules\Uhqradio\{
    Helper,
    Utility
};
/** @var Admin $adminObject */
/** @var Helper $helper */

/**
 * @param $chid
 * @return array
 */
function uhqradio_data_status($chid)
{
    global $xoopsDB;

    $data = [];

    // Locate Channel Info
    $query  = 'SELECT * FROM ' . $xoopsDB->prefix('uhqradio_channels') . " WHERE chid = '" . $chid . '\'';
    $result = $xoopsDB->queryF($query);
    if (false === $result) {
        $data['error'] = $xoopsDB->error();

        return $data;
    }

    // Load Channel Info
    $channel = $xoopsDB->fetchArray($result);
    if (null === $channel['chid']) {
        $data['error'] = _MD_UHQRADIO_XMLSTATUS_CND;

        return $data;
    }

    $data['channel'] = $channel;

    // Process Text Mountpoint if used

    if ($channel['text_mpid'] > 0) {

        // Locate Text Mountpoint
        $query  = 'SELECT * FROM ' . $xoopsDB->prefix('uhqradio_mountpoints') . " WHERE mpid = '" . $channel['text_mpid'] . '\'';
        $result = $xoopsDB->queryF($query);
        if (false === $result) {
            $data['error'] = _MD_UHQRADIO_XMLSTATUS_MLERR . $xoopsDB->error();

            return $data;
        }

        // Load Text Mountpoint info
        $mountinfo = $xoopsDB->fetchArray($result);

        if (false === $mountinfo) {
            $data['error'] = _MD_UHQRADIO_XMLSTATUS_TMNF;

            return $data;
        }

        // Set and retrieve XML information
        $path = uhqradio_xmlpath($mountinfo['type']);
        $auth = uhqradio_svrauth($mountinfo['type'], $mountinfo['auth_un'], $mountinfo['auth_pw']);

        // Get mountpoint XML
        $xmldata = null;
        $errno   = uhqradio_fetchxml($mountinfo['server'], $mountinfo['port'], $path, $auth, $xmldata);
        if ($errno) {
            $data['error'] = _MD_UHQRADIO_XMLSTATUS_NOTEXT;

            return $data;
        }

        // Scrub XML.
        $cleanxml = uhqradio_scrubxml($xmldata, $mountinfo['type'], $mountinfo['mount'], $mountinfo['fallback']);
        if (false === $cleanxml) {
            $data['error'] = _MD_UHQRADIO_XMLSTATUS_MNF;

            return $data;
        }

        // Process Status if we have Shoutcast
        if ('S' == $mountinfo['type']) {
            if (1 != uhqradio_isolatexml($cleanxml, '<STREAMSTATUS>', '</STREAMSTATUS>')) {
                $data['error'] = _MD_UHQRADIO_XMLSTATUS_SCOFF;

                return $data;
            }
        }

        // Extract DJ ID, if used.  Expand DJ Name.
        if (1 == $channel['flag_djid']) {
            $data['onair']['djid']   = uhqradio_getinfos($cleanxml, $mountinfo['type'], $channel['flag_d_sol'], $channel['delim_dj_s'], $channel['flag_d_eol'], $channel['delim_dj_e']);
            $djinfo                  = uhqradio_dj_info($data['onair']['djid']);
            $data['onair']['djname'] = uhqradio_username($djinfo['userkey']);
        }

        // Extract Show name, if used.
        if (1 == $channel['flag_show']) {
            $data['onair']['showname'] = uhqradio_getinfos($cleanxml, $mountinfo['type'], $channel['flag_s_sol'], $channel['delim_sh_s'], $channel['flag_s_eol'], $channel['delim_sh_e']);
        }

        // Extract Artist

        $data['onair']['artist']  = uhqradio_getartist($cleanxml, $mountinfo['type']);
        $data['onair']['xartist'] = htmlspecialchars($data['onair']['artist']);

        // Extract Title

        $data['onair']['title']  = uhqradio_gettitle($cleanxml, $mountinfo['type']);
        $data['onair']['xtitle'] = htmlspecialchars($data['onair']['title']);

        // Extract Listener Count

        $data['onair']['listeners'] = uhqradio_listeners($channel['chid']);

        // Set status to 1 if we've gotten this far!

        $data['channel']['status'] = 1;

        // If we're using SAM information and history, load the last row in the history table.

        if (uhqradio_opt_samint() && uhqradio_opt_savesh()) {
            // Load last history entry

            $sh_lquery = 'SELECT * FROM ' . $xoopsDB->prefix('uhqradio_shistory');
            $sh_lquery .= ' ORDER BY stamp DESC LIMIT 1';

            $sh_result = $xoopsDB->queryF($sh_lquery);

            if (false !== $sh_result) {
                $lastsong = $xoopsDB->fetchArray($sh_result);
            }

            // Add to $data if either the artist or track matches.  Sometimes encoding will throw this off.

            if ((0 == strcasecmp($lastsong['artist'], $data['onair']['artist']))
                || (0 == strcasecmp($lastsong['track'], $data['onair']['title']))) {
                $data['saminfo']           = $lastsong;
                $data['saminfo']['xalbum'] = htmlspecialchars($data['saminfo']['album']);
            }
        }

        return $data;
    }
}

// Return raw data for the DJ listing

/**
 * @return array|bool
 */
function uhqradio_data_djlist()
{
    global $xoopsDB;

    $data = [];

    // Load the DJ List
    $query = 'SELECT * FROM ' . $xoopsDB->prefix('uhqradio_airstaff');

    $result = $xoopsDB->queryF($query);

    if (false === $result) {
        return false;
    }

    $i = 0;
    while ($row = $xoopsDB->fetchArray($result)) {
        if ($row['djid']) {
            $data['djs'][$i]['djid'] = $row['djid'];
            $data['djs'][$i]['name'] = uhqradio_username($row['userkey']);
            $i++;
        }
    }
    $data['djcount'] = $i;

    return $data;
}

// Return raw data history for a mountpoint, channel, or mountpoints associated w/ a channel, for the last $minutes.

/**
 * @param      $id
 * @param      $type
 * @param int  $minutes
 * @param bool $summary
 * @return array|bool
 */
function uhqradio_data_lhistory($id, $type, $minutes = 15, $summary = false)
{
    global $xoopsDB;

    // We need to start out w/ a blank query.
    $query = '';

    if ($summary) {
        // Summary stats are like the regular stats, but used as a subquery.
        $query .= 'SELECT max(listeners) AS high, min(listeners) AS low, avg(listeners) AS average FROM (';
    }

    // Interval query needs to have zero-seconds in order to make sure we get the right interval we're looking for.
    $intquery = " AND lh.stamp > adddate(date_format(now(),'%Y-%m-%d %H:%i:00'), interval -" . $minutes . ' minute)';

    switch ($type) {
        case 'C':    // Specific Channel
            $query .= 'SELECT lh.stamp as stamp, sum(lh.pop) as listeners ';
            $query .= ' FROM ' . $xoopsDB->prefix('uhqradio_lhistory') . ' lh,';
            $query .= ' ' . $xoopsDB->prefix('uhqradio_countmap') . ' cm';
            $query .= " WHERE cm.chid = '" . $id . '\' AND lh.mpid = cm.mpid';
            $query .= $intquery;
            $query .= ' GROUP BY lh.stamp';
            break;
        case 'M':    // Specific Mountpoint
            $query = 'SELECT lh.stamp as stamp, lh.pop as listeners ';
            $query .= 'FROM ' . $xoopsDB->prefix('uhqradio_lhistory') . ' lh';
            $query .= "WHERE lh.mpid = '" . $id . '\'';
            $query .= $intquery;
            break;
        case 'A': // All mountpoints for a given channel
            $query = 'SELECT lh.stamp as stamp, lh.pop as listeners, lh.mpid as mpid';
            $query .= ' FROM ' . $xoopsDB->prefix('uhqradio_lhistory') . ' lh,';
            $query .= ' ' . $xoopsDB->prefix('uhqradio_countmap') . ' cm';
            $query .= " WHERE cm.chid = '" . $id . '\' AND lh.mpid = cm.mpid';
            $query .= $intquery;
            $query .= ' ORDER BY lh.stamp';
            break;
    }

    if ($summary) {
        // Round out the summary subquery if we're using it.
        $query .= ') as summarytable';
    }

    $result = $xoopsDB->queryF($query);
    if (false === $result) {
        // Return false if we can't load data.
        echo "can't load " . $query . '<br>' . $xoopsDB->error();

        return false;
    }
    $data = [];
    $i    = 0;

    if ($summary) {
        $data = $xoopsDB->fetchArray($result);
    } else {
        // Load the array into data.
        while ($data['history'][$i] = $xoopsDB->fetchArray($result)) {
            $i++;
        }
        // Pass the size of the acquired data, along w/ the raw data.
        $data['size'] = $i;
    }

    return $data;
}

/**
 * @param        $chid
 * @param int    $limit
 * @param string $type
 * @return bool
 */
function uhqradio_data_shistory($chid, $limit = 10, $type = 'S')
{
    global $xoopsDB;

    $query = 'SELECT * FROM ' . $xoopsDB->prefix('uhqradio_shistory') . " WHERE chid = '" . $chid . '\'';

    if ('A' != $type) {
        $query .= " AND songtype = '" . $type . '\'';
    }

    $query .= ' ORDER BY stamp DESC LIMIT ' . $limit;

    $result = $xoopsDB->queryF($query);
    if (false === $result) {
        // Return false if we can't load data.
        echo "can't load " . $query . '<br>' . $xoopsDB->error();

        return false;
    }

    $i = 0;
    while ($row = $xoopsDB->fetchArray($result)) {
        $data['history'][$i] = $row;
        $i++;
    }
    if ($i > 0) {
        $data['count'] = $i;

        return $data;
    }

    return false;
}

// Return the list of top requests.  Must have song history.

/**
 * @param      $chid
 * @param int  $limit
 * @param null $type
 * @return bool
 */
function uhqradio_data_requestchart($chid, $limit = 10, $type = null)
{
    global $xoopsDB;

    $query = 'SELECT artist, track, album, count(*) AS reqs FROM ' . $xoopsDB->prefix('uhqradio_shistory');
    $query .= " WHERE chid = '" . $chid . '\' AND requestID > 0 ';

    switch ($type) {
        case 'LM':    // Request list for last month
            $query .= ' AND MONTH(stamp) = MONTH( DATE_SUB(CURDATE(), INTERVAL 1 MONTH) )';
            break;
        default:
            break;
    }

    $query .= ' GROUP BY artist, track, album ORDER BY reqs DESC LIMIT ' . $limit;

    $result = $xoopsDB->queryF($query);
    if (false === $result) {
        // Return false if we can't load data.
        echo "can't load " . $query . '<br>' . $xoopsDB->error();

        return false;
    }

    $i = 0;
    while ($row = $xoopsDB->fetchArray($result)) {
        $data['request'][$i] = $row;
        $i++;
    }
    if ($i > 0) {
        $data['count'] = $i;

        return $data;
    }

    return false;
}
