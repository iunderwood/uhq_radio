<?php

/*
    This file contains any block information which is used to show any listener history.
*/

require_once XOOPS_ROOT_PATH . '/modules/uhq_radio/include/functions.php';
require_once XOOPS_ROOT_PATH . '/modules/uhq_radio/include/modoptions.php';
require_once XOOPS_ROOT_PATH . '/modules/uhq_radio/include/rawdata.php';

/**
 * @param $options
 * @return bool
 */
function b_uhqradio_shistory_show($options)
{

    // Empty the block if we're not saving the history.

    if (1 != uhqradio_opt_savesh()) {
        if ($options[1]) {
            $block['error'] = _MB_UHQRADIO_SHIST_ERR_DISABLED;

            return $block;
        } else {
            return false;
        }
    }

    // Load the data
    $block = uhqradio_data_shistory($options[0], $options[2]);

    // Trap an error if we couldn't load the data.

    if (false === $block) {
        if ($options[1]) {
            $block['error'] = _MB_UHQRADIO_SHIST_ERR_LOAD;

            return $block;
        } else {
            return false;
        }
    }

    $block['size']     = $options[2];
    $block['extended'] = $options[3];

    return $block;
}

/**
 * @param $options
 * @return string
 */
function b_uhqradio_shistory_edit($options)
{
    global $xoopsDB;

    // List stations
    $form = _MB_UHQRADIO_SHIST_OPT_CHID;

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
    $form .= _MB_UHQRADIO_SHIST_OPT_ERR;
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

    // Specify History Length
    $form .= _MB_UHQRADIO_SHIST_OPT_LENGTH;
    $form .= "<input type='text' name='options[2]' maxlength=3 value='" . $options[2] . '\'>';
    $form .= '<br>';

    // Extended form?
    $form .= _MB_UHQRADIO_SHIST_OPT_EXTENDED;
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

    return $form;
}
