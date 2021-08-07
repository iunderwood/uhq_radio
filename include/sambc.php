<?php

/* sambc.php

This is an include file which contains all functions required to directly interact w/ SAM Broadcaster.

*/

use XoopsModules\Uhqradio\{
    Helper,
    Utility
};
/** @var Admin $adminObject */
/** @var Helper $helper */

// Connect to the DB given a remote DJ, given ID and IP.  Returns MySQL Resource.

/**
 * @param $djid
 * @param $host
 * @return bool
 */
function uhqradio_sam_opendb($djid, $host)
{
    $djinfo = uhqradio_dj_info($djid);

    if (false === $djinfo) {
        // Return false if we haven't found any DJ info
        return false;
    }

    $samdb = mysqli_pconnect($host . ':' . $djinfo['rdb_port'], $djinfo['rdb_user'], $djinfo['rdb_pass']);

    if (false === $samdb) {
        // Return false if we can't connect.
        return false;
    }

    mysqli_select_db($djinfo['rdb_name'], $samdb);

    return $samdb;
}

// Close a connection to a given resource.

/**
 * @param $samdb
 */
function uhqradio_sam_closedb($samdb)
{
    $GLOBALS['xoopsDB']->close($samdb);
}

// Query and return upcoming track list

/**
 * @param $samdb
 * @param $limit
 * @param $ids
 * @return array|bool
 */
function uhqradio_sam_upcoming($samdb, $limit, $ids)
{
    $data = [];

    $query = 'SELECT songlist.*, queuelist.requestID as requestid ';
    $query .= 'FROM songlist, queuelist ';
    $query .= 'WHERE queuelist.songid = songlist.id AND (';
    $query .= "songlist.songtype='S'";
    if ($ids) {
        $query .= " OR songlist.songtype='I'";
    }
    $query .= ") AND songlist.artist <> '' ";
    $query .= 'ORDER BY queuelist.sortID ASC';

    if ($limit) {
        $query .= ' LIMIT ' . $limit;
    }

    $result = mysqli_query($samdb, $query);
    if (false === $result) {
        echo 'bad query' . $GLOBALS['xoopsDB']->error();

        // Return if query fails.
        return false;
    }

    $i = 0;

    while ($row = mysqli_fetch_assoc($result)) {
        $data[$i] = $row;

        // UTF-8 Encode going into an array
        $data[$i]['artist'] = utf8_encode($row['artist']);
        $data[$i]['title']  = utf8_encode($row['title']);
        $data[$i]['album']  = utf8_encode($row['album']);

        $i++;
    }
    if ($i = 0) {
        return false;
    }

    return $data;
}

/**
 * @param $samdb
 * @return array|bool|null
 */
function uhqradio_sam_nowplaying($samdb)
{
    $data = [];

    // The last song in the history is "now playing";

    $query = 'SELECT * FROM historylist ORDER BY id DESC LIMIT 1';

    $result = mysqli_query($samdb, $query);
    if (false === $result) {
        echo 'bad query: ' . $GLOBALS['xoopsDB']->error();

        return false;
    }
    if ($row = mysqli_fetch_assoc($result)) {
        if ($row['requestID']) {
            // Load request info, and populate if the query is good and we have a result.
            $req_query  = "SELECT * FROM requestlist WHERE ID = '" . $row['requestID'] . '\'';
            $req_result = mysqli_query($samdb, $req_query);
            if ($req_result) {
                if ($req_row = mysqli_fetch_assoc($req_result)) {
                    $row['requestor'] = $req_row['name'];
                }
            }
        }

        return $row;
    }

    return false;
}

// Build where query searching artist, title, and album for each word.

/**
 * @param     $input
 * @param int $and
 * @return string
 */
function uhqradio_sam_where($input, $and = 0)
{
    $words  = [];
    $output = '';

    // Get all the search terms out of the search string

    $temp = explode(' ', $input);
    reset($temp);
    //    while (list($key, $val) = each($temp)) {
    foreach ($temp as $key => $val) {
        $val = trim($val);
        if (!empty($val)) {
            $words[] = $val;
        }
    }

    // Build out the query which suits the terms given.

    $where2 = '';
    reset($words);
    //    while (list($key, $val) = each($words)) {
    foreach ($words as $key => $val) {
        if (!empty($where2)) {
            $where2 .= ' OR ';
        }
        $val    = "%$val%";
        $where2 .= " (songlist.title like '$val') OR (songlist.artist like '$val') OR (songlist.album like '$val') ";
    }
    if (!empty($where2)) {
        $output .= " AND ($where2) ";
    }

    return $output;
}

// Build a where based on a starting letter A=artist,L=album,T=title

/**
 * @param        $letter
 * @param string $type
 * @return null|string
 */
function uhqradio_sam_where_letter($letter, $type = 'T')
{
    $output = '';

    switch ($type) {
        case 'A':
            $field = 'songlist.artist';
            break;
        case 'L':
            $field = 'songlist.album';
            break;
        case 'T':
            $field = 'songlist.title';
            break;
        default:
            return null;
    }

    if ('0' == $letter) {
        $output = ' AND ' . $field . " >= '0' AND " . $field . " <= '9ZZZZZZZZZZ' ";
    } else {
        $output = ' AND ' . $field . " >= '" . $letter . '\' AND ' . $field . " <= '" . $letter . "ZZZZZZZZZZ' ";
    }

    // Build out the query which suits the terms given.

    return $output;
}

// Return the total number of items in the playlist.

/**
 * @param      $samdb
 * @param null $where
 * @return bool
 */
function uhqradio_sam_countpl($samdb, $where = null)
{
    $data = [];

    $category = uhqradio_opt_plcat();

    // Returns the total number of requestable items.
    $query = 'SELECT COUNT(*) FROM songlist';

    if ($category) {
        $query .= ', categorylist, category';
    }

    $query .= " WHERE songlist.songtype = 'S'";

    if ($category) {
        $query .= ' AND songlist.ID = categorylist.songID AND';
        $query .= ' categorylist.categoryID = category.ID AND';
        $query .= " category.name = '" . $category . '\'';
    }

    if ($where) {
        $query .= $where;
    }

    $result = mysqli_query($samdb, $query);
    if (false === $result) {
        echo 'bad query: ' . $GLOBALS['xoopsDB']->error($samdb) . '<br>' . $query;

        return false;
    } else {
        list($count) = $GLOBALS['xoopsDB']->fetchRow($result);
    }

    return $count;
}

// Return the total number of albums in the playlist.

/**
 * @param      $samdb
 * @param null $where
 * @return bool
 */
function uhqradio_sam_countalbum($samdb, $where = null)
{
    $data = [];

    $category = uhqradio_opt_plcat();

    // Returns the total number of requestable items.
    $query = 'SELECT COUNT(DISTINCT album) FROM songlist';

    if ($category) {
        $query .= ', categorylist, category';
    }

    $query .= " WHERE songlist.songtype = 'S'";

    if ($category) {
        $query .= ' AND songlist.ID = categorylist.songID AND';
        $query .= ' categorylist.categoryID = category.ID AND';
        $query .= " category.name = '" . $category . '\'';
    }

    if ($where) {
        $query .= $where;
    }

    $result = mysqli_query($samdb, $query);
    if (false === $result) {
        echo 'bad query: ' . $GLOBALS['xoopsDB']->error($samdb) . '<br>' . $query;

        return false;
    } else {
        list($count) = $GLOBALS['xoopsDB']->fetchRow($result);
    }

    return $count;
}

// Return the total number of artists in the playlist.

/**
 * @param      $samdb
 * @param null $where
 * @return bool
 */
function uhqradio_sam_countartist($samdb, $where = null)
{
    $data = [];

    $category = uhqradio_opt_plcat();

    // Returns the total number of requestable items.
    $query = 'SELECT COUNT(DISTINCT artist) FROM songlist';

    if ($category) {
        $query .= ', categorylist, category';
    }

    $query .= " WHERE songlist.songtype = 'S'";

    if ($category) {
        $query .= ' AND songlist.ID = categorylist.songID AND';
        $query .= ' categorylist.categoryID = category.ID AND';
        $query .= " category.name = '" . $category . '\'';
    }

    if ($where) {
        $query .= $where;
    }

    $result = mysqli_query($samdb, $query);
    if (false === $result) {
        echo 'bad query: ' . $GLOBALS['xoopsDB']->error($samdb) . '<br>' . $query;

        return false;
    } else {
        list($count) = $GLOBALS['xoopsDB']->fetchRow($result);
    }

    return $count;
}

// Return a list of albums w/ their picture files.

/**
 * @param      $samdb
 * @param null $where
 * @return array|bool
 */
function uhqradio_sam_displayalbums($samdb, $where = null)
{
    $data = [];

    $category = uhqradio_opt_plcat();

    // Return a subset of the playlist

    $query = 'SELECT DISTINCT(album) AS album, songlist.picture AS cover FROM songlist';
    if ($category) {
        $query .= ', categorylist, category';
    }

    $query .= " WHERE songlist.songtype = 'S'";

    if ($category) {
        $query .= ' AND songlist.ID = categorylist.songID AND';
        $query .= ' categorylist.categoryID = category.ID AND';
        $query .= " category.name = '" . $category . '\'';
    }

    if ($where) {
        $query .= $where;
    }

    $query .= ' ORDER BY album ASC';

    $result = mysqli_query($samdb, $query);
    if (false === $result) {
        echo 'bad query' . $GLOBALS['xoopsDB']->error() . '<br>' . $query;

        // Return if query fails.
        return false;
    }

    $i = 0;

    while ($row = mysqli_fetch_assoc($result)) {
        $data[$i] = $row;
        // UTF-8 Encode going into an array
        $data[$i]['album']    = utf8_encode($row['album']);
        $data[$i]['linkcode'] = urlencode($row['album']);
        $data[$i]['seq']      = $i + 1;
        $i++;
    }

    if ($i = 0) {
        return false;
    }

    return $data;
}

// List of artists given a where.

/**
 * @param      $samdb
 * @param null $where
 * @return array|bool
 */
function uhqradio_sam_displayartists($samdb, $where = null)
{
    $data = [];

    $category = uhqradio_opt_plcat();

    // Return a subset of the playlist

    $query = 'SELECT DISTINCT(artist) AS artist FROM songlist';
    if ($category) {
        $query .= ', categorylist, category';
    }

    $query .= " WHERE songlist.songtype = 'S'";

    if ($category) {
        $query .= ' AND songlist.ID = categorylist.songID AND';
        $query .= ' categorylist.categoryID = category.ID AND';
        $query .= " category.name = '" . $category . '\'';
    }

    if ($where) {
        $query .= $where;
    }

    $query .= ' ORDER BY artist ASC';

    $result = mysqli_query($samdb, $query);
    if (false === $result) {
        echo 'bad query' . $GLOBALS['xoopsDB']->error() . '<br>' . $query;

        // Return if query fails.
        return false;
    }

    $i = 0;

    while ($row = mysqli_fetch_assoc($result)) {
        $data[$i] = $row;
        // UTF-8 Encode going into an array
        $data[$i]['artist']   = utf8_encode($row['artist']);
        $data[$i]['linkcode'] = urlencode($row['artist']);
        $data[$i]['seq']      = $i + 1;
        $i++;
    }

    if ($i = 0) {
        return false;
    }

    return $data;
}

// List years and counts of OSTs in that year.

/**
 * @param $samdb
 * @return array|bool
 */
function uhqradio_sam_yearlist($samdb)
{
    $data = [];

    $category = uhqradio_opt_plcat();

    $query = 'SELECT COUNT(DISTINCT(album)) AS count, picture, album, albumyear FROM songlist';
    if ($category) {
        $query .= ', categorylist, category';
    }

    $query .= " WHERE songlist.songtype = 'S'";

    if ($category) {
        $query .= ' AND songlist.ID = categorylist.songID AND';
        $query .= ' categorylist.categoryID = category.ID AND';
        $query .= " category.name = '" . $category . '\'';
    }

    $query .= ' GROUP BY albumyear DESC';

    $result = mysqli_query($samdb, $query);
    if (false === $result) {
        echo 'bad query' . $GLOBALS['xoopsDB']->error() . '<br>' . $query;

        // Return if query fails.
        return false;
    }

    $i = 0;

    while ($row = mysqli_fetch_assoc($result)) {
        $data[$i] = $row;
        $i++;
    }

    if ($i = 0) {
        return false;
    }

    return $data;
}

// Return the most recent x albums added

/**
 * @param     $samdb
 * @param int $limit
 * @return array|bool
 */
function uhqradio_sam_lastxalbums($samdb, $limit = 10)
{
    $data = [];

    $category = uhqradio_opt_plcat();

    // Return a subset of the playlist

    $query = 'SELECT DISTINCT(album) AS album, songlist.picture AS cover, date(date_added) AS date_add FROM songlist';
    if ($category) {
        $query .= ', categorylist, category';
    }

    $query .= " WHERE songlist.songtype = 'S'";

    if ($category) {
        $query .= ' AND songlist.ID = categorylist.songID AND';
        $query .= ' categorylist.categoryID = category.ID AND';
        $query .= " category.name = '" . $category . '\'';
    }

    $query .= ' ORDER BY date_added DESC LIMIT ' . $limit;

    $result = mysqli_query($samdb, $query);
    if (false === $result) {
        echo 'bad query' . $GLOBALS['xoopsDB']->error() . '<br>' . $query;

        // Return if query fails.
        return false;
    }

    $i = 0;

    while ($row = mysqli_fetch_assoc($result)) {
        $data[$i] = $row;
        // UTF-8 Encode going into an array
        $data[$i]['album']    = utf8_encode($row['album']);
        $data[$i]['linkcode'] = urlencode($row['album']);
        $data[$i]['seq']      = $i + 1;
        $i++;
    }

    if ($i = 0) {
        return false;
    }

    return $data;
}

/**
 * @param $samdb
 * @param $album
 * @return array|bool
 */
function uhqradio_sam_getalbum($samdb, $album)
{
    $data = [];

    $category = uhqradio_opt_plcat();

    // Return a subset of the playlist

    $query = 'SELECT songlist.* FROM songlist';
    if ($category) {
        $query .= ', categorylist, category';
    }

    $query .= " WHERE songlist.songtype = 'S'";

    if ($category) {
        $query .= ' AND songlist.ID = categorylist.songID AND';
        $query .= ' categorylist.categoryID = category.ID AND';
        $query .= " category.name = '" . $category . '\'';
    }

    $query .= " AND songlist.album = '" . $album . '\' ';

    $query .= ' ORDER BY filename ASC ';

    $result = mysqli_query($samdb, $query);
    if (false === $result) {
        echo 'bad query' . $GLOBALS['xoopsDB']->error() . '<br>' . $query;

        // Return if query fails.
        return false;
    }

    $i = 0;

    while ($row = mysqli_fetch_assoc($result)) {
        if (0 == $i) {
            $data['album']['picture'] = $row['picture'];
            $data['album']['year']    = $row['albumyear'];
            $data['album']['label']   = $row['label'];
            $data['album']['genre']   = $row['genre'];
            $data['album']['added']   = $row['date_added'];
            $data['album']['name']    = utf8_encode($row['album']);
            $artist                   = $row['artist'];

            $ac = 1;
        } else {
            if ($artist != $row['artist']) {
                $ac++;
            }
        }
        $data['track'][$i] = $row;

        // UTF-8 Encode into array;
        $data['track'][$i]['artist'] = utf8_encode($row['artist']);
        $data['track'][$i]['title']  = utf8_encode($row['title']);
        $data['track'][$i]['album']  = utf8_encode($row['album']);

        $data['track'][$i]['mmss'] = uhqradio_mmss($data['track'][$i]['duration']);
        $data['track'][$i]['seq']  = $i + 1;
        $i++;
    }

    if ($i = 0) {
        return false;
    } else {
        if ($ac > 1) {
            $data['album']['artist'] = 'Various Artists';
        } else {
            $data['album']['artist'] = utf8_encode($artist);
        }
    }

    return $data;
}

// Display all tracks by an artist.

/**
 * @param $samdb
 * @param $artist
 * @return array|bool
 */
function uhqradio_sam_getartist($samdb, $artist)
{
    $data = [];

    $category = uhqradio_opt_plcat();

    // Return a subset of the playlist

    $query = 'SELECT songlist.* FROM songlist';
    if ($category) {
        $query .= ', categorylist, category';
    }

    $query .= " WHERE songlist.songtype = 'S'";

    if ($category) {
        $query .= ' AND songlist.ID = categorylist.songID AND';
        $query .= ' categorylist.categoryID = category.ID AND';
        $query .= " category.name = '" . $category . '\'';
    }

    $query .= " AND songlist.artist = '" . $artist . '\' ';

    $query .= ' ORDER BY albumyear DESC, album ASC, filename ASC ';

    $result = mysqli_query($samdb, $query);
    if (false === $result) {
        echo 'bad query' . $GLOBALS['xoopsDB']->error() . '<br>' . $query;

        // Return if query fails.
        return false;
    }

    $albumseq   = 0;
    $album      = null;
    $trackcount = 0;

    while ($row = mysqli_fetch_assoc($result)) {
        if (!$albumseq) {
            $data['artist'] = utf8_encode($row['artist']);
        }

        // Increment album array and fill in album info if we've got a change in the album.
        if ($album != $row['album']) {
            $albumseq++;
            $data['album'][$albumseq]['picture'] = $row['picture'];
            $data['album'][$albumseq]['year']    = $row['albumyear'];
            $data['album'][$albumseq]['label']   = $row['label'];
            $data['album'][$albumseq]['genre']   = $row['genre'];
            $data['album'][$albumseq]['added']   = $row['date_added'];
            $data['album'][$albumseq]['name']    = $row['album'];
            $album                               = $row['album'];
            $trackseq                            = 0;
        } else {
            // Otherwise, increment track sequence.
            $trackseq++;
        }

        // Assign track information.
        $data['album'][$albumseq]['track'][$trackseq] = $row;
        // UTF-8 Encode into array;
        $data['album'][$albumseq]['track'][$trackseq]['artist'] = utf8_encode($row['artist']);
        $data['album'][$albumseq]['track'][$trackseq]['title']  = utf8_encode($row['title']);
        $data['album'][$albumseq]['track'][$trackseq]['album']  = utf8_encode($row['album']);

        $data['album'][$albumseq]['track'][$trackseq]['mmss'] = uhqradio_mmss($row['duration']);
        $data['album'][$albumseq]['track'][$trackseq]['seq']  = $trackcount + 1;

        $trackcount++;
    }
    $data['trackcount'] = $trackcount;

    if ($albumseq = 0) {
        return false;
    }

    return $data;
}

// Return playlist data.

/**
 * @param      $samdb
 * @param null $where
 * @param int  $start
 * @param int  $limit
 * @return array|bool
 */
function uhqradio_sam_displaypl($samdb, $where = null, $start = 0, $limit = 15)
{
    $data = [];

    $category = uhqradio_opt_plcat();

    // Return a subset of the playlist

    $query = 'SELECT songlist.* FROM songlist';
    if ($category) {
        $query .= ', categorylist, category';
    }

    $query .= " WHERE songlist.songtype = 'S'";

    if ($category) {
        $query .= ' AND songlist.ID = categorylist.songID AND';
        $query .= ' categorylist.categoryID = category.ID AND';
        $query .= " category.name = '" . $category . '\'';
    }

    if ($where) {
        $query .= $where;
    }

    $query .= ' ORDER BY artist ASC, title ASC ';
    $query .= ' LIMIT ' . $limit . ' OFFSET ' . $start;

    $result = mysqli_query($samdb, $query);
    if (false === $result) {
        echo 'bad query' . $GLOBALS['xoopsDB']->error() . '<br>' . $query;

        // Return if query fails.
        return false;
    }

    $i = 0;

    while ($row = mysqli_fetch_assoc($result)) {
        $data[$i] = $row;
        // UTF-8 Encode going into an array
        $data[$i]['artist'] = utf8_encode($row['artist']);
        $data[$i]['title']  = utf8_encode($row['title']);
        $data[$i]['album']  = utf8_encode($row['album']);
        $data[$i]['seq']    = $start + $i + 1;
        $data[$i]['mmss']   = uhqradio_mmss($data[$i]['duration']);
        $i++;
    }

    if ($i = 0) {
        return false;
    }

    return $data;
}

// Get all the fields that match a given Song ID.

/**
 * @param $samdb
 * @param $songid
 * @return array|bool|null
 */
function uhqradio_sam_songinfo($samdb, $songid)
{
    $query = "SELECT * FROM songlist WHERE ID = '" . $songid . '\'';

    $result = mysqli_query($samdb, $query);
    if (false === $result) {
        echo 'Bad Query: ' . $GLOBALS['xoopsDB']->error() . '<br>' . $query . '<br>';

        return false;
    }

    if ($row = mysqli_fetch_assoc($result)) {
        $row['mmss'] = uhqradio_mmss($row['duration']);

        return $row;
    } else {
        return false;
    }
}

// Test Connectivity for a DJ ID w/ an IP address

/**
 * @param $djid
 * @param $host
 * @return array
 */
function uhqradio_sam_djtest($djid, $host)
{
    $data                      = [];
    $data['test'][1]['descr']  = _MD_UHQRADIO_POP_DJT_TEST1;
    $data['test'][1]['status'] = 0;
    $data['test'][1]['error']  = '';
    $data['test'][2]['descr']  = _MD_UHQRADIO_POP_DJT_TEST2;
    $data['test'][2]['status'] = 0;
    $data['test'][2]['error']  = '';
    $data['test'][3]['descr']  = _MD_UHQRADIO_POP_DJT_TEST3;
    $data['test'][3]['status'] = 0;
    $data['test'][3]['error']  = '';

    $djinfo = uhqradio_dj_info($djid);

    // If we don't have a DJ ID, there's no point in testing further.

    if (false === $djinfo) {
        $data['error'] = 'DJ ID Not Found';

        return $data;
    }

    // Test #1 & 2 - DB Connect, and DB Select

    $samdb = mysqli_pconnect($host . ':' . $djinfo['rdb_port'], $djinfo['rdb_user'], $djinfo['rdb_pass']);

    if (false === $samdb) {
        // Set error message if we can't get a resource.
        $data['test'][1]['status'] = -1;
        $data['test'][1]['error']  = $GLOBALS['xoopsDB']->errno() . ': ' . $GLOBALS['xoopsDB']->error();
    } else {
        // Only test selection if we can open up a DB resource
        $data['test'][1]['status'] = 1;
        if (false === mysqli_select_db($djinfo['rdb_name'], $samdb)) {
            // Set error message if we can't select the database
            $data['test'][2]['status'] = -1;
            $data['test'][2]['error']  = $GLOBALS['xoopsDB']->errno($samdb) . ': ' . $GLOBALS['xoopsDB']->error($samdb);
        } else {
            $data['test'][2]['status'] = 1;
        }
    }

    // Test #3 - Verify SAM access

    if (false === uhqradio_externalurl('http://' . $host . ':' . $djinfo['sam_port'] . '/event/test')) {
        $data['test'][3]['status'] = -1;
        $data['test'][3]['error']  = _MD_UHQRADIO_POP_DJT_SAMFAIL . $djinfo['sam_port'];
    } else {
        $data['test'][3]['status'] = 1;
    }

    // Close the DB connection, since we're done with it.

    $GLOBALS['xoopsDB']->close($samdb);

    return $data;
}

/**
 * @param $samdb
 * @param $reqid
 * @param $username
 * @return bool
 */
function uhqradio_sam_reqname($samdb, $reqid, $username)
{

    // It's only worth setting this if we've actually got a username.
    if ($username) {
        $query = "UPDATE requestlist SET name = '" . $username . '\' ';
        $query .= "WHERE ID='" . $reqid . '\'';

        $result = mysqli_query($samdb, $query);

        // We won't check for an error here, because update behavior may not be allowed on the remote SAM.
    }

    return true;
}
