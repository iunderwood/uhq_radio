<?php

require_once XOOPS_ROOT_PATH . '/modules/uhq_radio/include/rawdata.php';
require_once XOOPS_ROOT_PATH . '/modules/uhq_radio/include/modoptions.php';

// Return minute:second display based on a duration in miliseconds.

/**
 * @param $duration
 * @return string
 */
function uhqradio_mmss($duration)
{
    $ss = round($duration / 1000);
    $mm = (int)($ss / 60);
    $ss = ($ss % 60);
    if ($ss < 10) {
        $ss = "0$ss";
    }
    $mmss = "$mm:$ss";

    return $mmss;
}

// Set an XML path given a server type.

/**
 * @param      $type
 * @param null $password
 * @return bool|string
 */
function uhqradio_xmlpath($type, $password = null)
{
    switch ($type) {
        case 'I':
            $path = '/admin/stats.xml';
            break;
        case 'S':
            $path = '/admin.cgi?mode=viewxml';
            break;
        case 'P':
            $path = '/xml/stats.xml?password=' . $password;
            break;
        default:
            $path = false;
    }

    return $path;
}

// Set authentication for a server type.

/**
 * @param      $type
 * @param null $username
 * @param      $password
 * @return bool|string
 */
function uhqradio_svrauth($type, $username = null, $password)
{
    switch ($type) {
        case 'I':
            $auth = base64_encode($username . ':' . $password);
            break;
        case 'S':
            $auth = base64_encode('admin:' . $password);
            break;
        default:
            $auth = false;
    }

    return $auth;
}

// Grab an XML file given an IP, port, path, and authentication info.

/**
 * @param      $ipfqdn
 * @param      $port
 * @param      $xmlpath
 * @param      $auth
 * @param      $xmldata
 * @param null $override
 * @return bool
 */
function uhqradio_fetchxml($ipfqdn, $port, $xmlpath, $auth, &$xmldata, $override = null)
{
    $cachefile = XOOPS_ROOT_PATH . '/modules/uhq_radio/cache/xml_' . $ipfqdn . '_' . $port . '.xml';

    // Load Module Config

    $moduleHandler     = xoops_getHandler('module');
    $xoopsModule       = $moduleHandler->getByDirname('uhq_radio');
    $configHandler     = xoops_getHandler('config');
    $xoopsModuleConfig = $configHandler->getConfigsByCat(0, $xoopsModule->getVar('mid'));

    // Check cache.  Get XML if the file cache is too old and external caching is off ... or if we override.
    // Otherwise, read from the file.

    if (file_exists($cachefile)) {
        if ((((time() - filemtime($cachefile)) > $xoopsModuleConfig['cache_time'])
             && ($xoopsModuleConfig['cache_external'] == 0))
            || ($override == 1)) {
            // Don't read cache data, move to update XML.
        } else {
            // Read cache file.
            $cp      = fopen($cachefile, 'r');
            $xmldata = fread($cp, filesize($cachefile));
            fclose($cp);

            return false;
        }
    }

    // Get our own XML.

    $fp = fsockopen($ipfqdn, $port, $errno, $errstr, 1);

    if (!$fp) {
        return $errno;
    }

    $httpreq = 'GET ' . $xmlpath . " HTTP/1.0\r\n";
    $httpreq .= "User-Agent: Mozilla/3.0 (Compatible; UHQ-Radio)\r\n";
    if ($auth != null) {
        $httpreq .= 'Authorization: Basic ' . $auth . "\r\n";
    }
    $httpreq .= "\r\n";

    fwrite($fp, $httpreq);

    $xmldata = null;
    while (!feof($fp)) {
        $xmldata .= fgets($fp, 512);
    }
    fclose($fp);

    // Don't write any null junk to a file.

    if ($xmldata) {
        $cp = fopen($cachefile, 'w');
        if (!$cp) {
            // If we can't write, then just exit.
            return false;
        }
        fwrite($cp, $xmldata);
        fclose($cp);
    }

    return false;
}

// Isolate XML between specified tags.

/**
 * @param $input
 * @param $tagA
 * @param $tagB
 * @return string
 */
function uhqradio_isolatexml($input, $tagA, $tagB)
{
    $line   = substr($input, strpos($input, $tagA) + strlen($tagA));
    $output = substr($line, 0, strpos($line, $tagB));

    return trim($output);
}

// Take a given title, and make and split out artist and song.

/**
 * @param $title
 * @param $artist
 * @param $song
 * @return bool
 */
function uhqradio_titlesplit($title, &$artist, &$song)
{
    $endpos = strpos($title, ' - ');
    $artist = substr($title, 0, $endpos);
    $song   = substr($title, $endpos + 3);

    return true;
}

// Make an external URL call.  Return true if we get a good response.

/**
 * @param $eventurl
 * @return bool
 */
function uhqradio_externalurl($eventurl)
{
    $ext = curl_init();

    curl_setopt($ext, CURLOPT_URL, $eventurl);
    curl_setopt($ext, CURLOPT_USERAGENT, 'Mozilla/3.0 (compatible; XOOPS) UHQ-Radio');
    curl_setopt($ext, CURLOPT_RETURNTRANSFER, true);

    $resp = curl_exec($ext);
    $info = curl_getinfo($ext);

    curl_close($ext);

    if ($resp === false) {
        return false;
    } else {
        return true;
    }
}

// Return the username given a certain UID

/**
 * @param $uid
 * @return mixed
 */
function uhqradio_username($uid)
{
    $memberHandler = xoops_getHandler('member');
    $user          = $memberHandler->getUser($uid);

    if (is_object($user)) {
        return $user->getVar('uname');
    } else {
        return $uid;
    }
}

// This function pulls out specific mountpoint information for Icecast, otherwise makes no changes.

/**
 * @param      $xmldata
 * @param      $type
 * @param null $mountpoint
 * @param null $fallback
 * @return bool|string
 */
function uhqradio_scrubxml($xmldata, $type, $mountpoint = null, $fallback = null)
{

    // Extract specific mountpoint for Icecast, supporting fallback.

    if ($type === 'I') {
        if (strpos($xmldata, $mountpoint)) {
            $cleanxml = uhqradio_isolatexml($xmldata, $mountpoint . '">', '</source>');
            // If we have a server description, we've probably got a good source.
            if (strpos($cleanxml, '<server_description>')) {
                return $cleanxml;
            }
        }
        if ($fallback != null) {
            if (strpos($xmldata, $fallback)) {
                $cleanxml = uhqradio_isolatexml($xmldata, $fallback . '">', '</source>');
            }
        } else {
            return false;
        }
    } else {
        $cleanxml = $xmldata;
    }

    return $cleanxml;
}

// Extract show & DJ information based upon a set of flags and delimiters.

/**
 * @param     $xmldata
 * @param     $type
 * @param     $flag_sdel
 * @param     $sdel
 * @param     $flag_edel
 * @param     $edel
 * @param int $field
 * @return bool|string
 */
function uhqradio_getinfos($xmldata, $type, $flag_sdel, $sdel, $flag_edel, $edel, $field = 1)
{

    // Get our field.

    switch ($type) {
        case 'I':
            $output = uhqradio_isolatexml($xmldata, '<server_description>', '</server_description>');
            break;
        case 'S':
            $output = uhqradio_isolatexml($xmldata, '<SERVERTITLE>', '</SERVERTITLE>');
            break;
        default:
            return false;
    }

    // Process Start Delimiter
    if ($flag_sdel == 1) {
        $spos   = strpos($output, $sdel);
        $output = substr($output, $spos + strlen($sdel));
    }

    // Process End Delimiter
    if ($flag_edel == 1) {
        $epos   = strpos($output, $edel);
        $output = substr($output, 0, $epos);
    }

    // Trim whitespace
    $output = trim($output);

    return $output;
}

// This function returns the artist of a song if present.

/**
 * @param $xmldata
 * @param $type
 * @return null|string
 */
function uhqradio_getartist($xmldata, $type)
{
    switch ($type) {
        case 'I':
            if (strpos($xmldata, '<artist>') !== false) {
                // If the <artist> tag exists, this is easy.
                return uhqradio_isolatexml($xmldata, '<artist>', '</artist>');
            } else {
                // If the <artist> tag is missing, make sure we have our delimiter
                if (strpos(uhqradio_isolatexml($xmldata, '<title>', '</title>'), ' - ')) {
                    uhqradio_titlesplit(uhqradio_isolatexml($xmldata, '<title>', '</title>'), $artist, $title);

                    return utf8_encode($artist);
                }

                return null;
            }
            break;
        case 'S':
            if (strpos(uhqradio_isolatexml($xmldata, '<SONGTITLE>', '</SONGTITLE'), ' - ')) {
                uhqradio_titlesplit(uhqradio_isolatexml($xmldata, '<SONGTITLE>', '</SONGTITLE>'), $artist, $title);

                return utf8_encode($artist);
            }

            return null;
            break;
        default:
            return null;
    }
}

// This function returns the title of a song, after checking for artist data.

/**
 * @param $xmldata
 * @param $type
 * @return null|string
 */
function uhqradio_gettitle($xmldata, $type)
{
    switch ($type) {
        case 'I':
            if (strpos($xmldata, '<artist>') !== false) {
                // If the <artist> tag exists, take the title as it is.
                return uhqradio_isolatexml($xmldata, '<title>', '</title>');
            } else {
                // If the <artist> tag is missing, exclude the artist if there's a delimiter.
                if (strpos(uhqradio_isolatexml($xmldata, '<title>', '</title>'), ' - ')) {
                    uhqradio_titlesplit(uhqradio_isolatexml($xmldata, '<title>', '</title>'), $artist, $title);

                    return utf8_encode($title);
                }

                // Return a clear title
                return uhqradio_isolatexml($xmldata, '<title>', '</title>');
            }
            break;
        case 'S':
            // Check for delimiter
            if (strpos(uhqradio_isolatexml($xmldata, '<SONGTITLE>', '</SONGTITLE'), ' - ')) {
                uhqradio_titlesplit(uhqradio_isolatexml($xmldata, '<SONGTITLE>', '</SONGTITLE>'), $artist, $title);

                return utf8_encode($title);
            }

            // Otherwise, return the title.
            return uhqradio_isolatexml($xmldata, '<SONGTITLE>', '</SONGTITLE');
            break;
        default:
            return null;
    }
}

/**
 * @param $xmldata
 * @param $type
 * @return int|string
 */
function uhqradio_getlisteners($xmldata, $type)
{
    switch ($type) {
        case 'I':
            return uhqradio_isolatexml($xmldata, '<listeners>', '</listeners>');
        case 'S':
            return uhqradio_isolatexml($xmldata, '<CURRENTLISTENERS>', '</CURRENTLISTENERS>');
        case 'P':
            return uhqradio_isolatexml($xmldata, '<viewers>', '</viewers>');
        default:
            return 0;
    }
}

/**
 * @param $xmldata
 * @param $type
 * @return bool|string
 */
function uhqradio_getsource($xmldata, $type)
{
    switch ($type) {
        case 'I':
            return uhqradio_isolatexml($xmldata, '<source_ip>', '</source_ip>');
        default:
            return false;
    }
}

// This function returns the number of listeners of a given channel.

/**
 * @param $chid
 * @return bool|int
 */
function uhqradio_listeners($chid)
{
    global $xoopsDB;

    // Load mountpoints from the counter map

    $query  = 'SELECT * FROM ' . $xoopsDB->prefix('uhqradio_countmap') . " WHERE chid = '" . $chid . '\'';
    $result = $xoopsDB->queryF($query);

    if ($result === false) {
        return false;
    }

    $population = 0;

    // Load counters from each mountpoint

    while ($row = $xoopsDB->fetchArray($result)) {
        $query     = 'SELECT * FROM ' . $xoopsDB->prefix('uhqradio_mountpoints') . " WHERE mpid = '" . $row['mpid'] . '\'';
        $subresult = $xoopsDB->queryF($query);

        if ($subresult === false) {
            continue;
        }

        // If true, get data.
        $mountinfo = $xoopsDB->fetchArray($subresult);

        // Set and retrieve XML information
        $path = uhqradio_xmlpath($mountinfo['type'], $mountinfo['auth_pw']);
        $auth = uhqradio_svrauth($mountinfo['type'], $mountinfo['auth_un'], $mountinfo['auth_pw']);

        // Get mountpoint XML
        $xmldata = null;
        $errno   = uhqradio_fetchxml($mountinfo['server'], $mountinfo['port'], $path, $auth, $xmldata);
        if ($errno) {
            continue;
        }

        // Scrub XML.
        $cleanxml = uhqradio_scrubxml($xmldata, $mountinfo['type'], $mountinfo['mount']);
        if ($cleanxml === false) {
            continue;
        }

        // Get listeners, adjust for variance
        $count = uhqradio_getlisteners($cleanxml, $mountinfo['type']) - $mountinfo['variance'];

        // No negative numbers, please.
        if ($count > 0) {
            $population = $population + $count;
        }
    }

    return $population;
}

/**
 * @param $chid
 * @return bool
 */
function uhqradio_channel_info($chid)
{
    global $xoopsDB;

    $query  = 'SELECT * FROM ' . $xoopsDB->prefix('uhqradio_channels') . " WHERE chid = '" . $chid . '\'';
    $result = $xoopsDB->queryF($query);
    if ($result === false) {
        // Fail if we cannot connect
        return false;
    } else {
        // Load all variables into the block.
        $channel = $xoopsDB->fetchArray($result);

        return $channel;
    }
}

// This function returns the connected DJ ID and connected DJ's IP.
/**
 * @param $chid
 * @return array|bool
 */
function uhqradio_dj_onair($chid)
{
    global $xoopsDB;

    $onair = array();

    // Load Channel

    $channel = uhqradio_channel_info($chid);
    if ($channel === false) {
        // Return if we can't load channel info.
        return false;
    }

    if (($channel['flag_djid'] == 1) && ($channel['text_mpid'] > 0)) {
        // Load Text Mountpoint

        $query  = 'SELECT * FROM ' . $xoopsDB->prefix('uhqradio_mountpoints') . " WHERE mpid = '" . $channel['text_mpid'] . '\'';
        $result = $xoopsDB->queryF($query);
        if ($result === false) {
            return false;
        } else {
            // Load mountpoint information into the block.
            $mountinfo = $xoopsDB->fetchArray($result);
        }

        // Load text mountpoint XML

        $path = uhqradio_xmlpath($mountinfo['type']);
        $auth = uhqradio_svrauth($mountinfo['type'], $mountinfo['auth_un'], $mountinfo['auth_pw']);

        $xmldata = null;
        $errno   = uhqradio_fetchxml($mountinfo['server'], $mountinfo['port'], $path, $auth, $xmldata);

        if ($errno) {
            return false;
        }

        // Scrub XML.

        $cleanxml = uhqradio_scrubxml($xmldata, $mountinfo['type'], $mountinfo['mount'], $mountinfo['fallback']);

        if ($cleanxml === false) {
            return false;
        }

        // Extract DJ ID
        $onair['djid'] = uhqradio_getinfos($cleanxml, $mountinfo['type'], $channel['flag_d_sol'], $channel['delim_dj_s'], $channel['flag_d_eol'], $channel['delim_dj_e']);

        // Extract Source IP
        $onair['djip'] = uhqradio_getsource($cleanxml, $mountinfo['type']);
    }

    return $onair;
}

// This function returns the row w/ info for a DJ id, otherwise return false.

/**
 * @param $djid
 * @return bool
 */
function uhqradio_dj_info($djid)
{
    global $xoopsDB;

    $query = 'SELECT * FROM ' . $xoopsDB->prefix('uhqradio_airstaff');
    $query .= " WHERE djid = '" . $djid . '\'';

    $result = $xoopsDB->queryF($query);

    if ($result === false) {
        return false;
    }
    $row = $xoopsDB->fetchArray($result);

    return $row;
}

// This function updates listener history for all channels.

/**
 * @return bool
 */
function uhqradio_updatelh()
{
    global $xoopsDB;

    // Get timestamp
    $query  = 'SELECT now()';
    $result = $xoopsDB->queryF($query);
    if ($result === false) {
        // If we can't get the time, quit.
        return false;
    }
    list($timestamp) = $xoopsDB->fetchRow($result);

    // Locate mountpoints, but only use ones set for reliable count.
    $query  = 'SELECT * FROM ' . $xoopsDB->prefix('uhqradio_mountpoints') . " WHERE flag_count = '1' ORDER BY mpid";
    $result = $xoopsDB->queryF($query);
    if ($result === false) {
        // If we can't load up the mount points, then quit.
        return false;
    }

    // Process MP List.
    while ($mountinfo = $xoopsDB->fetchArray($result)) {
        $status = 'O';
        $count  = 0;

        // Set and retrieve XML information
        $path = uhqradio_xmlpath($mountinfo['type'], $mountinfo['auth_pw']);
        $auth = uhqradio_svrauth($mountinfo['type'], $mountinfo['auth_un'], $mountinfo['auth_pw']);

        // Get mountpoint XML
        $xmldata = null;
        $errno   = uhqradio_fetchxml($mountinfo['server'], $mountinfo['port'], $path, $auth, $xmldata);
        if ($errno) {
            // If we can't get the XML, then our status is bad.
            $status = 'F';
        }

        // Scrub the XML as required
        if ($status === 'O') {
            $cleanxml = uhqradio_scrubxml($xmldata, $mountinfo['type'], $mountinfo['mount']);
            if ($cleanxml === false) {
                $status = 'X';
            }
        }

        // Get listeners, adjust for variance.
        if ($status = 'O') {
            $count = uhqradio_getlisteners($cleanxml, $mountinfo['type']) - $mountinfo['variance'];
            echo $count;
        }

        // Build our query.
        $lh_query = 'INSERT INTO ' . $xoopsDB->prefix('uhqradio_lhistory');
        $lh_query .= " SET mpid = '" . $mountinfo['mpid'] . '\', ';
        $lh_query .= " stamp = date_format('" . $timestamp . '\',\'%Y-%m-%d %H:%i:00\'), ';
        $lh_query .= " stamp_a = '" . $timestamp . '\', ';
        $lh_query .= " status = '" . $status . '\', ';
        $lh_query .= " pop = '" . $count . '\'';

        // Save Listener History to DB
        $lh_result = $xoopsDB->queryF($lh_query);

        // If we logged, this is where we'd stick the failure notice if we had one.
    }
}

// This function updates song history for a specific channel.

/**
 * @param $chid
 * @return bool
 */
function uhqradio_updatesh($chid)
{
    global $xoopsDB;

    $myts = MyTextSanitizer::getInstance();

    // Get timestamp
    $query  = 'SELECT now()';
    $result = $xoopsDB->queryF($query);
    if ($result === false) {
        // If we can't get the time, quit.
        return false;
    }
    list($timestamp) = $xoopsDB->fetchRow($result);

    $data = uhqradio_data_status($chid);

    if ($data['error']) {
        return false;
    }

    // Load last history entry

    $sh_lquery = 'SELECT * FROM ' . $xoopsDB->prefix('uhqradio_shistory');
    $sh_lquery .= 'ORDER BY stamp DESC LIMIT 1';

    $result = $xoopsDB->queryF($query);

    if ($result !== false) {
        $lastsong = $xoopsDB->fetchRow($query);
    }

    // If we've got the same song title and artist, go no further.

    if ((strcasecmp($lastsong['artist'], $data['onair']['artist']) == 0)
        && (strcasecmp($lastsong['track'], $data['onair']['title']) == 0)) {
        echo " Match found.  Not updating ... \r\n";

        return false;
    }

    // Get SAM information for additionals if available

    $samdata === false;
    if (uhqradio_samint()) {
        require_once XOOPS_ROOT_PATH . '/modules/uhq_radio/include/sambc.php';
        $info = uhqradio_dj_onair($chid);
        if ($info !== false) {
            if ($info['djip'] != 0) {
                $samdb = uhqradio_sam_opendb($info['djid'], $info['djip']);
                if ($samdb) {
                    $samdata = uhqradio_sam_nowplaying($samdb);
                    uhqradio_sam_closedb($samdb);
                }
            }
        }
    }

    // Set our history query

    $sh_query = 'INSERT INTO ' . $xoopsDB->prefix('uhqradio_shistory');
    $sh_query .= " SET chid = '" . $chid . '\', ';
    $sh_query .= "stamp = '" . $timestamp . '\', ';
    $sh_query .= 'djid = "' . $myts->addSlashes($data['onair']['djid']) . '", ';
    $sh_query .= 'showname ="' . $myts->addSlashes($data['onair']['showname']) . '", ';
    $sh_query .= 'viewers = "' . $data['onair']['listeners'] . '", ';

    if ($samdata) {
        $sh_query .= 'songtype = "' . $samdata['songtype'] . '", ';
        $sh_query .= 'artist = "' . $myts->addSlashes(utf8_encode($samdata['artist'])) . '", ';
        $sh_query .= 'track = "' . $myts->addSlashes(utf8_encode($samdata['title'])) . '", ';
        $sh_query .= 'album = "' . $myts->addSlashes(utf8_encode($samdata['album'])) . '", ';
        $sh_query .= 'albumyear = "' . (int)$samdata['albumyear'] . '", ';
        $sh_query .= 'label = "' . $myts->addSlashes($samdata['label']) . '", ';
        $sh_query .= 'picture = "' . $myts->addSlashes($samdata['picture']) . '", ';
        $sh_query .= 'requestid = "' . (int)$samdata['requestID'] . '"';
        if ($samdata['requestID']) {
            $sh_query .= ", requestor = '" . $myts->addSlashes(utf8_encode($samdata['requestor'])) . '\'';
        }
    } else {
        $sh_query .= 'artist = "' . $myts->addSlashes(utf8_encode($data['onair']['artist'])) . '", ';
        $sh_query .= 'track = "' . $myts->addSlashes(utf8_encode($data['onair']['title'])) . '", ';
        // Song type is S if we can't determine.
        $sh_query .= "songtype = 'S'";
    }

    $sh_result = $xoopsDB->queryF($sh_query);

    if ($sh_result === false) {
        echo 'History Update Failed: ' . $xoopsDB->error() . "\r\n";
    } else {
        echo 'Recorded: ' . $timestamp . ' :: ' . $data['onair']['artist'] . ' - ' . $data['onair']['title'] . "\r\n";
    }

    // Update LoudCity Here
    if (strlen($data['channel']['lc_guid']) > 30) {
        $lc_url = 'http://logger.loudcity.net/tracks/submit?';
        $lc_url .= 'stationguid=' . $data['channel']['lc_guid'];
        $lc_url .= '&artist=' . rawurlencode($data['onair']['artist']);
        $lc_url .= '&title=' . rawurlencode($data['onair']['title']);
        $lc_url .= '&viewers=' . $data['onair']['listeners'];
        if ($samdata) {
            if ($samdata['songtype'] === 'S') {
                $lc_url .= '&album=';
                if ($samdata['album']) {
                    $lc_url .= rawurlencode($samdata['album']);
                }
                $lc_url .= '&albumyear=';
                if ($samdata['albumyear']) {
                    $lc_url .= rawurlencode($samdata['albumyear']);
                }
                $lc_url .= '&label=';
                if ($samdata['label']) {
                    $lc_url .= rawurlencode($samdata['label']);
                }
                echo "LC Hit: $lc_url\r\n";
            } else {
                // Clear the URL if this isn't a "song" so it won't get processed as one.
                $lc_url = null;
            }
        } else {
            // LC needs all the fields, even if they don't have data.
            $lc_url .= '&album=&albumyear=&label=';

            // Uncomment to debug the URL.
            // echo "LC Hit: $lc_url\r\n";
        }
    }

    if ($lc_url) {
        $fp = fsockopen('logger.loudcity.net', '80', $errno, $errstr, 1);

        if (!$fp) {
            echo "Error: $errno: $errstr";
        } else {
            $httpreq = 'GET ' . $lc_url . " HTTP/1.0\r\n";
            $httpreq .= "Host: logger.loudcity.net\r\n";
            $httpreq .= "User-Agent: Mozilla/3.0 (Compatible; UHQ-Radio)\r\n";
            $httpreq .= "\r\n";

            fwrite($fp, $httpreq);

            $response = null;
            while (!feof($fp)) {
                $response .= fgets($fp, 512);
            }
            echo "LoudCity Response: $response";
        }

        fclose($fp);
    }

    // Update TuneIn.com here

    if ($data['channel']['tunein_pid'] && $data['channel']['tunein_pkey'] && $data['channel']['tunein_sid']) {
        $tunein_url = 'http://air.radiotime.com/Playing.ashx?';
        $tunein_url .= 'partnerId=' . $data['channel']['tunein_pid'];
        $tunein_url .= '&partnerKey=' . $data['channel']['tunein_pkey'];
        $tunein_url .= '&id=' . $data['channel']['tunein_sid'];
        $tunein_url .= '&artist=' . rawurlencode($data['onair']['artist']);
        $tunein_url .= '&title=' . rawurlencode($data['onair']['title']);

        if ($samdata) {
            if (($samdata['songtype'] === 'S') && $samdata['album']) {
                $tunein_url .= '&album=' . rawurlencode($samdata['album']);
            }
            if ($samdata['songtype'] === 'A') {
                $tunein_url .= '&commercial=true';
            }
        }
    }

    if ($tunein_url) {
        $fp = fsockopen('air.radiotime.com', '80', $errno, $errstr, 1);

        if (!$fp) {
            echo "Error: $errno: $errstr";
        } else {
            $httpreq = 'GET ' . $tunein_url . " HTTP/1.0\r\n";
            $httpreq .= "Host: air.radiotime.com\r\n";
            $httpreq .= "User-Agent: UHQ-Radio/0.13 (XOOPS/2.5)\r\n";
            $httpreq .= "\r\n";

            fwrite($fp, $httpreq);

            $response = null;
            while (!feof($fp)) {
                $response .= fgets($fp, 512);
            }
            echo "TuneIn Response: $response";
        }

        fclose($fp);
    }
}

// Generate an array of links for 0-9, A to Z

/**
 * @param $urlcomponent
 * @return array
 */
function uhqradio_aznum_array($urlcomponent)
{
    $az = array();

    $az[0]['letter'] = '0-9';
    $az[0]['link']   = $urlcomponent . '=0';
    $i               = 1;
    foreach (range('A', 'Z') as $letter) {
        $az[$i]['letter'] = $letter;
        $az[$i]['link']   = $urlcomponent . '=' . $letter;
        $i++;
    }

    return $az;
}

// Check if the logged-in user can place requests.
// Set result globally as this will be used a LOT when making rendering decisions.

/**
 * @param int $chid
 * @return bool
 */
function uhqradio_reqallowed($chid = 1)
{
    global $xoopsUser;
    global $uhqradio_request;

    // If SAM integration is disabled, requests are not allowed.

    if (uhqradio_samint() === false) {
        $uhqradio_request = false;

        return false;
    }
    $block['samint'] = 1;

    // Get DJ On Air.  If we can't get this info, then requests are not allowed.

    $djonair = uhqradio_dj_onair($chid);

    if ($djonair === false) {
        $uhqradio_request = false;

        return false;
    }

    // Load DJ's info.  If we can't get this info, then requests are not allowed.

    $djinfo = uhqradio_dj_info($djonair['djid']);

    if ($djinfo === false) {
        $uhqradio_request = false;

        return false;
    }

    // If the DJ is okay, set a flag and continue.

    if ($djinfo['flag_req']) {
        $djok = true;
    } else {
        $uhqradio_request = false;

        return false;
    }

    // Verify current user has permission

    $grp = uhqradio_opt_reqgroups();

    if (is_object($xoopsUser)) {
        if (count(array_intersect($xoopsUser->getGroups(), $grp)) > 0) {
            $userok = true;
        } else {
            $userok = false;
        }
    } else {
        // Check and see if anonymous holds allowed group access;
        if (in_array(XOOPS_GROUP_ANONYMOUS, $grp)) {
            $userok = true;
        } else {
            $userok = false;
        }
    }

    if ($djok && $userok) {
        $uhqradio_request = true;
    }

    return true;
}

uhqradio_reqallowed();

/**
 * @return bool|mixed
 */
function uhqradio_iceauthcheck()
{
    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $module_object = $moduleHandler->getByDirname('uhq_iceauth');

    if (is_object($module_object)) {
        $isok = $module_object->getVar('isactive');
    } else {
        $isok = false;
    }

    return $isok;
}
