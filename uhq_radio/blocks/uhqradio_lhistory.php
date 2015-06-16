<?php

/*
	This file contains any block information which is used to show any listener history.
*/

require_once (XOOPS_ROOT_PATH.'/modules/uhq_radio/include/functions.php');
require_once (XOOPS_ROOT_PATH.'/modules/uhq_radio/include/modoptions.php');
require_once (XOOPS_ROOT_PATH.'/modules/uhq_radio/include/rawdata.php');


function b_uhqradio_lhistory_show ($options) {
	
	// Empty the block if we're not saving the history.
	
	if (uhqradio_opt_savelh() != 1) {
		if ($options[1]) {
			$block['error'] = _MB_UHQRADIO_LHIST_ERR_DISABLED;
			return $block;
		} else {
			return false;
		}
	}
	
	// Load the data
	$block = uhqradio_data_lhistory($options[0],'C',$options[2]);
	
	// Trap an error if we couldn't load the data.
	
	if ($block == false) {
		if ($options[1]) {
			$block['error'] = _MB_UHQRADIO_LHIST_ERR_LOAD;
			return $block;
		} else {
			return false;
		}
	}
	
	$block['display'] = $options[3];
	
	if ($options[3] == 'G') {
		
	}
	
	// Load stats if we're using those..
	if ($options[4]) {
		$block['summary'] = uhqradio_data_lhistory($options[0],'C',$options[2],true);
	}
	
	return $block;
}

function b_uhqradio_lhistory_edit ($options) {
	global $xoopsDB;

	// List stations
	$form = _MB_UHQRADIO_LHIST_OPT_CHID;
	
	$query = "SELECT * FROM ".$xoopsDB->prefix('uhqradio_channels');
	$result = $xoopsDB->queryF($query);
	if ($result == false) {
		$form = _MB_UHQRADIO_ERROR.$xoopsDB->error();	
	} else {
		$form .= "<select size=1 name='options[0]'>";
		while ($row = $xoopsDB->fetchArray($result)) {
			$form .= "<option value='".$row['chid']."'>".$row['chan_name']."</option>";
		}
		$form .= "</select>";
	}
	$form .= "<br />";

	// Show Errors?
	$form .= _MB_UHQRADIO_LHIST_OPT_ERR;
	$form .= "<input type='radio' name='options[1]' value= '1' ";
	if ($options[1] == "1") $form .= "checked='checked'";
	$form .= "/>";
	$form .= _MB_UHQRADIO_YES;
	$form .= "<input type='radio' name='options[1]' value= '0' ";
	if ($options[1] == "0") $form .= "checked='checked'";
	$form .= "/>";
	$form .= _MB_UHQRADIO_NO;
	$form .= "<br />";
	
	// Specify Interval
	$form .= _MB_UHQRADIO_LHIST_OPT_INTERVAL;
	$form .= "<input type='text' name='options[2]' maxlength=3 value='".$options[2]."' />";
	$form .= "<br />";
	
	// Specify Format
	$form .= _MB_UHQRADIO_LHIST_OPT_FORMAT;
	$form .= "<input type='radio' name='options[3]' value= 'L' ";
	if ($options[3] == "L") $form .= "checked='checked'";
	$form .= "/>";
	$form .= _MB_UHQRADIO_LHIST_OPT_LIST;
	$form .= "<input type='radio' name='options[3]' value= 'G' ";
	if ($options[3] == "G") $form .= "checked='checked'";
	$form .= "/>";
	$form .= _MB_UHQRADIO_LHIST_OPT_BLANK;
	$form .= "<input type='radio' name='options[3]' value= 'B' ";
	if ($options[3] == "B") $form .= "checked='checked'";
	$form .= "/>";
	$form .= _MB_UHQRADIO_LHIST_OPT_LIST;
	$form .= "<br />";
	
	
	// Show Summary Stats?
	$form .= _MB_UHQRADIO_LHIST_OPT_SUM;
	$form .= "<input type='radio' name='options[4]' value= '1' ";
	if ($options[4] == "1") $form .= "checked='checked'";
	$form .= "/>";
	$form .= _MB_UHQRADIO_YES;
	$form .= "<input type='radio' name='options[4]' value= '0' ";
	if ($options[4] == "0") $form .= "checked='checked'";
	$form .= "/>";
	$form .= _MB_UHQRADIO_NO;
	$form .= "<br />";

	return $form;
}


?>