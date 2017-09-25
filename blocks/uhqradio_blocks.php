<?php

require_once XOOPS_ROOT_PATH . '/modules/uhq_radio/include/modoptions.php';

require_once XOOPS_ROOT_PATH . '/modules/uhq_radio/include/functions.php';
require_once XOOPS_ROOT_PATH . '/modules/uhq_radio/include/rawdata.php';
require_once XOOPS_ROOT_PATH . '/modules/uhq_radio/include/sambc.php';

/**
 * @param $options
 * @return array
 */
function b_uhqradio_status_show($options)
{
    global $xoopsDB;
    $block = [];

    // Locate Channel Info
    $query  = 'SELECT * FROM ' . $xoopsDB->prefix('uhqradio_channels') . " WHERE chid = '" . $options[0] . '\'';
    $result = $xoopsDB->queryF($query);
    if (false === $result) {
        $block['status']    = _MB_UHQRADIO_OFFAIR;
        $block['statusimg'] = _MB_UHQRADIO_OFFAIR_IMG;
        $block['error']     = 1;
        if ($options[7]) {
            $block['errorcode'] = _MB_UHQRADIO_ERROR_SQL . $xoopsDB->error();
        }

        return $block;
    }

    // Load Channel Info
    $channel = $xoopsDB->fetchArray($result);
    if (null == $channel['chid']) {
        $block['status']    = _MB_UHQRADIO_OFFAIR;
        $block['statusimg'] = _MB_UHQRADIO_OFFAIR_IMG;
        $block['error']     = 1;
        if ($options[7]) {
            $block['errorcode'] = _MB_UHQRADIO_ERROR_CHID;
        }

        return $block;
    }

    if ($channel['text_mpid'] > 0) {

        // Prep for the on-air link if we use one.

        if (1 == $options[1]) {
            $block['linkurl'] = $options[2];
            $block['target']  = $options[3];

            if ('pop' == $block['target']) {
                $block['popw'] = $options[4];
                $block['poph'] = $options[5];
            }

            if (1 == $options[8]) {
                $block['graphic'] = 1;
            }
        }

        // AJAX is easy.  If enabled, toss out the AJAX flag, and the channel ID

        if (1 == $options[9]) {
            $block['ajax']          = 1;
            $block['chid']          = $options[0];
            $block['showlisteners'] = $options[6];
            $block['showurl']       = $options[1];
            $block['showerr']       = $options[7];
            $block['showalbumart']  = $options[10];

            return $block;
        }

        // Locate Text Mountpoint
        $query  = 'SELECT * FROM ' . $xoopsDB->prefix('uhqradio_mountpoints') . " WHERE mpid = '" . $channel['text_mpid'] . '\'';
        $result = $xoopsDB->queryF($query);
        if (false === $result) {
            $block['status']    = _MB_UHQRADIO_OFFAIR;
            $block['statusimg'] = _MB_UHQRADIO_OFFAIR_IMG;
            $block['error']     = 1;
            if ($options[7]) {
                $block['errorcode'] = _MB_UHQRADIO_ERROR_SQL . $xoopsDB->error();
            }

            return $block;
        }

        // Load Text Mountpoint info
        $mountinfo = $xoopsDB->fetchArray($result);

        if (false === $mountinfo) {
            $block['status']    = _MB_UHQRADIO_OFFAIR;
            $block['statusimg'] = _MB_UHQRADIO_OFFAIR_IMG;
            $block['error']     = 1;
            if ($options[7]) {
                $block['errorcode'] = _MB_UHQRADIO_ERROR_TXTNF;
            }

            return $block;
        }

        // Set and retrieve XML information
        $path = uhqradio_xmlpath($mountinfo['type']);
        $auth = uhqradio_svrauth($mountinfo['type'], $mountinfo['auth_un'], $mountinfo['auth_pw']);

        // Get mountpoint XML
        $xmldata = null;
        $errno   = uhqradio_fetchxml($mountinfo['server'], $mountinfo['port'], $path, $auth, $xmldata);

        if ($errno) {
            $block['status']    = _MB_UHQRADIO_OFFAIR;
            $block['statusimg'] = _MB_UHQRADIO_OFFAIR_IMG;
            $block['error']     = 1;
            if ($options[7]) {
                $block['errorcode'] = _MB_UHQRADIO_ERROR_CONN . $errno;
            }
        }

        // Scrub XML.
        $cleanxml = uhqradio_scrubxml($xmldata, $mountinfo['type'], $mountinfo['mount'], $mountinfo['fallback']);
        if (false === $cleanxml) {
            $block['status']    = _MB_UHQRADIO_OFFAIR;
            $block['statusimg'] = _MB_UHQRADIO_OFFAIR_IMG;
            $block['error']     = 1;
            if ($options[7]) {
                $block['errorcode'] = _MB_UHQRADIO_ERROR_MNF;
            }

            return $block;
        }

        // Process Status if we have Shoutcast
        if ('S' == $mountinfo['type']) {
            if (1 != uhqradio_isolatexml($cleanxml, '<STREAMSTATUS>', '</STREAMSTATUS>')) {
                $block['status']    = _MB_UHQRADIO_OFFAIR;
                $block['statusimg'] = _MB_UHQRADIO_OFFAIR_IMG;
                $block['error']     = 1;
                if ($options[7]) {
                    $block['errorcode'] = _MB_UHQRADIO_ERROR_SU;
                }

                return $block;
            }
        }

        // Extract Artist

        $block['artist'] = uhqradio_getartist($cleanxml, $mountinfo['type']);

        // Extract Title

        $block['title'] = uhqradio_gettitle($cleanxml, $mountinfo['type']);

        // If we get this far, we're good on our song info.  Process show names if we use them.

        if (1 == $channel['flag_show']) {
            $block['statusdetail'] = uhqradio_getinfos($cleanxml, $mountinfo['type'], $channel['flag_s_sol'], $channel['delim_sh_s'], $channel['flag_s_eol'], $channel['delim_sh_e']);
        }

        // Provide listener response if configured.

        if (1 == $options[6]) {
            $block['count'] = uhqradio_listeners($channel['chid']);
            if (0 == $block['count']) {
                $block['listeners'] = _MB_UHQRADIO_LISTENERS_NONE;
            } elseif (1 == $block['count']) {
                $block['listeners'] = _MB_UHQRADIO_LISTENERS_ONE;
            } elseif ($block['count'] > 1) {
                $block['listeners'] = _MB_UHQRADIO_LISTENERS_MANY . $block['count'];
            }
        }

        // If we have album art, show it.

        if (1 == $options[10]) {
        }

        // If we've gotten this far, we are on the air.

        $block['status']    = _MB_UHQRADIO_ONAIR;
        $block['statusimg'] = _MB_UHQRADIO_ONAIR_IMG;
    } else {
        $block['status']    = _MH_UHQRADIO_OFFAIR;
        $block['statusimg'] = _MB_UHQRADIO_OFFAIR_IMG;
        $block['error']     = 1;
        if ($options[7]) {
            $block['errorcode'] = _MB_UHQRADIO_ERROR_NOTXT;
        }
    }

    return $block;
}

/**
 * @param $options
 * @return string
 */
function b_uhqradio_status_edit($options)
{
    global $xoopsDB;

    // Station ID

    $form = _MB_UHQRADIO_STATUS_OPTA;

    $query  = 'SELECT * FROM ' . $xoopsDB->prefix('uhqradio_channels');
    $result = $xoopsDB->queryF($query);
    if (false === $result) {
        $form .= _MB_UHQRADIO_ERROR . $xoopsDB->error();
    } else {
        $form .= "<select size=1 name='options[0]'>";
        while ($row = $xoopsDB->fetchArray($result)) {
            $form .= "<option value='" . $row['chid'] . '\'>' . $row['chan_name'] . '</option>';
        }
        $form .= '</select>';
    }
    $form .= '<br>';

    // Use a tune-in link?
    $form .= _MB_UHQRADIO_STATUS_OPTO;
    $form .= "<input type='radio' name='options[1]' value= '1' ";
    if ('1' == $options[1]) {
        $form .= 'checked';
    }
    $form .= '>';
    $form .= _MB_UHQRADIO_YES;
    $form .= "<input type='radio' name='options[1]' value= '0' ";
    if ('0' == $options[1]) {
        $form .= 'checked';
    }
    $form .= '>';
    $form .= _MB_UHQRADIO_NO;
    $form .= '<br>';

    // Tune-In URL
    $form .= _MB_UHQRADIO_STATUS_OPTP;
    $form .= "<input type='text' name='options[2]' value='" . $options[2] . '\'>';
    $form .= '<br>';

    // Tune-In Target
    $form .= _MB_UHQRADIO_STATUS_OPTQ;
    $form .= "<select name='options[3]'>";
    $form .= "<option value='_top' ";
    if ('_top' == $options[3]) {
        $form .= 'selected';
    }
    $form .= '>' . _MB_UHQRADIO_TARGET_SELF . '</option>';
    $form .= "<option value='_blank' ";
    if ('_blank' == $options[3]) {
        $form .= 'selected';
    }
    $form .= '>' . _MB_UHQRADIO_TARGET_NEW . '</option>';
    $form .= "<option value='pop' ";
    if ('pop' == $options[3]) {
        $form .= 'selected';
    }
    $form .= '>' . _MB_UHQRADIO_TARGET_POPUP . '</option>';
    $form .= '</select>';
    $form .= '<br>';

    // Pop-Up Dimensions
    $form .= _MB_UHQRADIO_STATUS_OPTRS;
    $form .= _MB_UHQRADIO_WIDTH;
    $form .= "<input type='text' name='options[4]' maxlength=3 value='" . $options[4] . '\'>';
    $form .= _MB_UHQRADIO_HEIGHT;
    $form .= "<input type='text' name='options[5]' maxlength=3 value='" . $options[5] . '\'>';
    $form .= '<br>';

    $form .= _MB_UHQRADIO_STATUS_OPTIONHDR;

    // Show listeners?
    $form .= _MB_UHQRADIO_STATUS_OPTT;
    $form .= "<input type='radio' name='options[6]' value= '1' ";
    if ('1' == $options[6]) {
        $form .= 'checked';
    }
    $form .= '>';
    $form .= _MB_UHQRADIO_YES;
    $form .= "<input type='radio' name='options[6]' value= '0' ";
    if ('0' == $options[6]) {
        $form .= 'checked';
    }
    $form .= '>';
    $form .= _MB_UHQRADIO_NO;
    $form .= '<br>';

    // Show Errors?
    $form .= _MB_UHQRADIO_STATUS_OPTU;
    $form .= "<input type='radio' name='options[7]' value= '1' ";
    if ('1' == $options[7]) {
        $form .= 'checked';
    }
    $form .= '>';
    $form .= _MB_UHQRADIO_YES;
    $form .= "<input type='radio' name='options[7]' value= '0' ";
    if ('0' == $options[7]) {
        $form .= 'checked';
    }
    $form .= '>';
    $form .= _MB_UHQRADIO_NO;
    $form .= '<br>';

    // Use a link graphic?
    $form .= _MB_UHQRADIO_STATUS_OPTV;
    $form .= "<input type='radio' name='options[8]' value= '1' ";
    if ('1' == $options[8]) {
        $form .= 'checked';
    }
    $form .= '>';
    $form .= _MB_UHQRADIO_YES;
    $form .= "<input type='radio' name='options[8]' value= '0' ";
    if ('0' == $options[8]) {
        $form .= 'checked';
    }
    $form .= '>';
    $form .= _MB_UHQRADIO_NO;
    $form .= '<br>';

    // Standard block or AJAX?
    $form .= _MB_UHQRADIO_STATUS_OPT_AJAX;
    $form .= "<input type='radio' name='options[9]' value= '1' ";
    if ('1' == $options[9]) {
        $form .= 'checked';
    }
    $form .= '>';
    $form .= _MB_UHQRADIO_YES;
    $form .= "<input type='radio' name='options[9]' value= '0' ";
    if ('0' == $options[9]) {
        $form .= 'checked';
    }
    $form .= '>';
    $form .= _MB_UHQRADIO_NO;
    $form .= '<br>';

    // Show Album Art?
    $form .= _MB_UHQRADIO_STATUS_OPT_ALBUMART;
    $form .= "<input type='radio' name='options[10]' value= '1' ";
    if ('1' == $options[10]) {
        $form .= 'checked';
    }
    $form .= '>';
    $form .= _MB_UHQRADIO_YES;
    $form .= "<input type='radio' name='options[10]' value= '0' ";
    if ('0' == $options[10]) {
        $form .= 'checked';
    }
    $form .= '>';
    $form .= _MB_UHQRADIO_NO;
    $form .= '<br>';

    return $form;
}

// Block for autoplayer control.

/**
 * @param $options
 * @return array
 */
function b_uhqradio_control_show($options)
{
    $block = [];

    if ($options[0]) {
        $block['start'] = '1';
    }

    if ($options[1]) {
        $block['stop'] = '1';
    }

    if ($options[2]) {
        $block['skip'] = '1';
    }

    if ($options[3]) {
        $block['stopnow'] = '1';
    }

    if ($options[4]) {
        $block['rewind'] = '1';
    }

    return $block;
}

/**
 * @param $options
 * @return string
 */
function b_uhqradio_control_edit($options)
{
    // Autoplayer Controls

    // Start up autoplayer
    $form .= _MB_UHQRADIO_CONTROL_OPTA;
    $form .= "<input type='text' name='options[0]' value='" . $options[0] . '\'>';
    $form .= '<br>';

    // Stop and hand off.
    $form .= _MB_UHQRADIO_CONTROL_OPTB;
    $form .= "<input type='text' name='options[1]' value='" . $options[1] . '\'>';
    $form .= '<br>';

    // Skip current track
    $form .= _MB_UHQRADIO_CONTROL_OPTC;
    $form .= "<input type='text' name='options[2]' value='" . $options[2] . '\'>';
    $form .= '<br>';

    // Stop autoplayer now and handoff
    $form .= _MB_UHQRADIO_CONTROL_OPTD;
    $form .= "<input type='text' name='options[3]' value='" . $options[3] . '\'>';
    $form .= '<br>';

    // Rewind current track
    $form .= _MB_UHQRADIO_CONTROL_OPTE;
    $form .= "<input type='text' name='options[4]' value='" . $options[4] . '\'>';
    $form .= '<br>';

    return $form;
}

// Block for handoff controls

/**
 * @param $options
 * @return array
 */
function b_uhqradio_handoff_show($options)
{
    global $xoopsDB;
    global $xoopsUser;

    $block = [];

    // Check DB & Status for handoff.

    $query = 'SELECT * FROM ' . $xoopsDB->prefix('uhqradio_handoffs');
    $query .= ' WHERE reqstat > 0 ORDER BY reqtime LIMIT 1';

    $result = $xoopsDB->queryF($query);

    if (false === $result) {
        $block['error'] = 'Unable to retrieve handoff information.';

        return $block;
    }
    $handoff = $xoopsDB->fetchArray($result);

    // If we have a handoff, let's look!

    if ($handoff['reqtime']) {
        if ($xoopsUser->uid() == $handoff['requser']) {
            $block['ondeck']    = 'You are ';
            $block['surrender'] = 1;
        } else {
            $memberHandler   = xoops_getHandler('member');
            $user            = $memberHandler->getUser($handoff['requser']);
            $block['ondeck'] = $user->uname() . ' is ';
        }
        if (2 == $handoff['reqstat']) {
            $block['status'] = 'ready to go';
        }
    } else {
        $block['ap'] = '1';
    }

    return $block;
}

/**
 * @param $options
 * @return string
 */
function b_uhqradio_handoff_edit($options)
{
    // Remote handoff port

    $form = _MB_UHQRADIO_HANDOFF_OPTA;
    $form .= "<input type='text' name='options[0]' value='" . $options[0] . '\'>';
    $form .= '<br>';

    // Allow unverified handoffs?

    $form .= _MB_UHQRADIO_HANDOFF_OPTB;
    $form .= "<input type='radio' name='options[1]' value= '1' ";
    if ('1' == $options[1]) {
        $form .= 'checked';
    }
    $form .= '>';
    $form .= _MB_UHQRADIO_YES;
    $form .= "<input type='radio' name='options[1]' value= '0' ";
    if ('0' == $options[1]) {
        $form .= 'checked';
    }
    $form .= '>';
    $form .= _MB_UHQRADIO_NO;
    $form .= '<br>';

    $form .= _MB_UHQRADIO_HANDOFF_EVENTS;

    // Verification Call

    $form .= _MB_UHQRADIO_HANDOFF_OPTC;
    $form .= "<input type='text' name='options[2]' value='" . $options[2] . '\'>';
    $form .= '<br>';

    // Handoff Call

    $form .= _MB_UHQRADIO_HANDOFF_OPTD;
    $form .= "<input type='text' name='options[3]' value='" . $options[3] . '\'>';
    $form .= '<br>';

    return $form;
}

/**
 * @param $options
 * @return bool
 */
function b_uhqradio_djpanel_show($options)
{
    global $xoopsUser;
    global $xoopsDB;
    global $samDB;

    $showblock = 0;

    if ($xoopsUser) {
        $uid = $xoopsUser->getVar('uid');

        $query = 'SELECT * FROM ' . $xoopsDB->prefix('uhqradio_airstaff');
        $query .= " WHERE userkey = '" . $uid . '\'';

        $result = $xoopsDB->queryF($query);

        if (false === $result) {
            return false;
        }
        $row = $xoopsDB->fetchArray($result);

        if ($row['djid']) {
            $block['djid'] = $row['djid'];
            if ('1' == $row['flag_req']) {
                $block['reqstat'] = _MB_UHQRADIO_DJP_REQ_OK;
            } else {
                $block['reqstat'] = _MB_UHQRADIO_DJP_REQ_NOK;
            }
            if (uhqradio_samint()) {
                $block['samint']   = 1;
                $block['sam_port'] = $row['sam_port'];
                $block['rdb_port'] = $row['rdb_port'];
                $block['rdb_name'] = $row['rdb_name'];

                // SAM test needs to be some sort of dynamic AJAX-ish thing, instead of being tested every time the block is loaded.

                /*
                $samdb = @uhqradio_sam_opendb($row['djid'],$_SERVER['REMOTE_ADDR']);
                if ($samdb) {
                    $block['samok'] = 1;
                    uhqradio_sam_closedb($samdb);
                } else {*/

                $block['reqip'] = $_SERVER['REMOTE_ADDR'];
                /*
                }
                */
            }
            $showblock = 1;
        }
    }

    if (1 == $showblock) {
        return $block;
    } else {
        return false;
    }
}

/**
 * @param $options
 */
function b_uhqradio_djpanel_edit($options)
{
}

/**
 * @param $options
 * @return array|bool
 */
function b_uhqradio_djlist_show($options)
{
    global $xoopsDB;

    $block = [];

    $block = uhqradio_data_djlist();

    // Need the options
    $block['cols'] = $options[0];
    $block['size'] = $options[1];

    if (0 == $block['djcount']) {
        return false;
    }

    return $block;
}

/**
 * @param $options
 * @return string
 */
function b_uhqradio_djlist_edit($options)
{

    // Number of columns

    $form = _MB_UHQRADIO_DJLIST_COLS;
    $form .= "<input type='text' name='options[0]' value='" . $options[0] . '\'>';
    $form .= '<br>';

    // Font Size

    $form .= _MB_UHQRADIO_DJLIST_FONT;
    $form .= "<input type='text' name='options[1]' value='" . $options[1] . '\'>';
    $form .= '<br>';

    return $form;
}
