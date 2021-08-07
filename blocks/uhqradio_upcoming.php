<?php

require_once XOOPS_ROOT_PATH . '/modules/uhqradio/include/modoptions.php';
require_once XOOPS_ROOT_PATH . '/modules/uhqradio/include/functions.php';
require_once XOOPS_ROOT_PATH . '/modules/uhqradio/include/sambc.php';

/**
 * @param $options
 * @return array|bool
 */
function b_uhqradio_upcoming_show($options)
{
    global $xoopsDB;
    global $samDB;

    $block = [];

    // Return a blank block if SAM Integration isn't enabled.
    if (false === uhqradio_samint()) {
        return false;
    }

    // Prepare remote DB connection

    $info = uhqradio_dj_onair($options[0]);

    if (false === $info) {
        if ($options[1]) {
            $block['error'] = _MB_UHQRADIO_ERROR . $xoopsDB->error();

            return $block;
        } else {
            // Blank block if we have no DJ Info.
            return false;
        }
    }

    if (0 == $info['djip']) {
        if ($options[1]) {
            $block['error'] = 'No Source IP Found';

            return $block;
        } else {
            // Blank block if we have no IP.
            return false;
        }
    }

    // Open Database

    $samdb = uhqradio_sam_opendb($info['djid'], $info['djip']);
    if (false === $samdb) {
        if ($options[1]) {
            $block['error'] = 'Unable to contact DB ' . $info['djid'] . ' at ' . $info['djip'];

            return $block;
        } else {
            // Return if we can't initialize the DB, for whatever reason.
            return false;
        }
    }

    // Get upcoming tracks

    $block['upcoming'] = uhqradio_sam_upcoming($samdb, $options[2], $options[3]);

    if (false === $block['upcoming']) {
        if ($options[1]) {
            $block['error'] = 'No Upcoming Data';

            return $block;
        } else {
            // Return if we can't get any data upcoming.
            return false;
        }
    }

    // Close Database

    uhqradio_sam_closedb($samdb);

    // Add Options
    $block['options'] = $options;

    return $block;
}

/**
 * @param $options
 * @return string
 */
function b_uhqradio_upcoming_edit($options)
{
    global $xoopsDB;

    // List stations
    $form = _MB_UHQRADIO_UPCOMING_OPT_CHANNEL;

    $query  = 'SELECT * FROM ' . $xoopsDB->prefix('uhqradio_channels');
    $result = $xoopsDB->queryF($query);
    if (false === $result) {
        $form = _MB_UHQRADIO_ERROR . $xoopsDB->error();
    } else {
        $form .= "<select size=1 name='options[0]'>";
        while ($row = $xoopsDB->fetchArray($result)) {
            $form .= "<option value='" . $row['chid'] . '\'>' . $row['chan_name'] . '</option>';
        }
        $form .= '</select>';
    }
    $form .= '<br>';

    // Show Errors?
    $form .= _MB_UHQRADIO_UPCOMING_OPT_ERR;
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

    // Set "coming up" limitations

    $form .= _MB_UHQRADIO_UPCOMING_OPT_LIMIT;
    $form .= "<input type='text' name='options[2]' value='" . $options[2] . '\'>';
    $form .= '<br>';

    // Show Station IDs?

    $form .= _MB_UHQRADIO_UPCOMING_OPT_IDS;
    $form .= "<input type='radio' name='options[3]' value= '1' ";
    if ('1' == $options[3]) {
        $form .= 'checked';
    }
    $form .= '>';
    $form .= _MB_UHQRADIO_YES;
    $form .= "<input type='radio' name='options[3]' value= '0' ";
    if ('0' == $options[3]) {
        $form .= 'checked';
    }
    $form .= '>';
    $form .= _MB_UHQRADIO_NO;
    $form .= '<br>';

    // List Header, Separator, and Trailer

    $form .= _MB_UHQRADIO_UPCOMING_OPT_LHDR;
    $form .= "<input type='text' name='options[4]' value='" . $options[4] . '\'>';
    $form .= '<br>';

    $form .= _MB_UHQRADIO_UPCOMING_OPT_LSEP;
    $form .= "<input type='text' name='options[5]' value='" . $options[5] . '\'>';
    $form .= '<br>';

    $form .= _MB_UHQRADIO_UPCOMING_OPT_LTRL;
    $form .= "<input type='text' name='options[6]' value='" . $options[6] . '\'>';
    $form .= '<br>';

    return $form;
}
