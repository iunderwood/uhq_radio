<?php

require_once XOOPS_ROOT_PATH . '/modules/uhq_radio/include/functions.php';

/**
 * @param $options
 * @return mixed
 */
function b_uhqradio_status2_show($options)
{

    // All we return to the block is the channel number to request info for.

    $block['chid'] = $options[0];

    return $block;
}

/**
 * @param $options
 * @return string
 */
function b_uhqradio_status2_edit($options)
{
    global $xoopsDB;

    // List stations
    $form = _MB_UHQRADIO_STATUS2_OPTA;

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

    return $form;
}
