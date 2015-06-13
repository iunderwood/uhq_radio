<?php

require_once (XOOPS_ROOT_PATH.'/modules/uhq_radio/include/functions.php');

function b_uhqradio_onair_show ($options) {
	global $xoopsDB;
	
	$block = array();
	
	// Load channel info.
	
	$query = "SELECT * FROM ".$xoopsDB->prefix('uhqradio_channels')." WHERE chid = '".$options[0]."'";
	$result = $xoopsDB->queryF($query);
	if ($result == false) {
		if ($options[1]) {
			$block['error'] = _MB_UHQRADIO_ERROR.$xoopsDB->error();
			return $block;
		} else {
			return false;
		}
	} else {
		// Load all variables into the block.
		$channel = $xoopsDB->fetchArray($result);
		$block['channel'] = $channel;
	}
	
	// Verify that we're using the DJ info ... and that there's a text mountpoint set.
	
	if ( ($channel['flag_djid'] == 1) && ($channel['text_mpid'] > 0) ) {
	
		// Find text mountpoint
	
		$query = "SELECT * FROM ".$xoopsDB->prefix('uhqradio_mountpoints')." WHERE mpid = '".$block['channel']['text_mpid']."'";
		$result = $xoopsDB->queryF($query);
		if ($result == false) {
			if ($options[1]) {
				$block['error'] = _MB_UHQRADIO_ERROR.$xoopsDB->error();
				return $block;
			} else {
				return false;
			}
		} else {
			// Load mountpoint information into the block.
			$mountinfo = $xoopsDB->fetchArray($result);
		}
	
		// Load text mountpoint XML
	
		$path = uhqradio_xmlpath($mountinfo['type']) ;
		$auth = uhqradio_svrauth($mountinfo['type'],$mountinfo['auth_un'],$mountinfo['auth_pw']);
	
		$xmldata = null;
		$errno = uhqradio_fetchxml($mountinfo['server'], $mountinfo['port'], $path, $auth, $xmldata);
	
		if ($errno) {
			if ($options[1]) {
				$block['error'] = _MB_UHQRADIO_ERROR_CONN.$errno;
				return $block;
			} else {
				return false;
			}
		}

		// Scrub XML.
	
		$cleanxml = uhqradio_scrubxml($xmldata,$mountinfo['type'],$mountinfo['mount'],$mountinfo['fallback']);
	
		if ($cleanxml == false) {
			if ($options[1]) {
				$block['error'] = _MB_UHQRADIO_ERROR_MNF;
				return $block;
			} else {
				return false;
			}
		}
	
		// Extract DJ ID
		
		$djid = uhqradio_getinfos($cleanxml,$mountinfo['type'],$channel['flag_d_sol'],
			$channel['delim_dj_s'],$channel['flag_d_eol'],$channel['delim_dj_e']);
		
		// Load show name if we're using it.
		
		if ($channel['flag_show'] == 1) {
			$showname = uhqradio_getinfos($cleanxml,$mountinfo['type'],$channel['flag_s_sol'],
				$channel['delim_sh_s'],$channel['flag_s_eol'],$channel['delim_sh_e']);
		}
		
		// Load DJ info from DB
		
		$query = "SELECT * FROM ".$xoopsDB->prefix('uhqradio_airstaff')." WHERE djid = '".$djid."'";
		$result = $xoopsDB->queryF($query);
		
		if ($result == false) {
			if ($options[1]) {
				$block['error'] = _MB_UHQRADIO_ERROR.$xoopsDB->error();
				return $block;
			} else {
				return false;
			}
		}
		
		$djinfo = $xoopsDB->fetchArray($result);
		
		if ($djinfo['userkey']) {
			$block['dj'] = $djinfo;
			$block['dj']['name'] = uhqradio_username($djinfo['userkey']);
			if (isset ($showname)) {
				$block['showname'] = $showname;
			}
		} else {
			// Treat this as an error.  Return a blank block if we couldn't find the DJ.
			if ($options[1]) {
				$block['error'] = _MB_UHQRADIO_ONAIR_DJID.$djid._MB_UHQRADIO_ONAIR_DJDBERR;
				return $block;
			} else {
				return false;
			}
		}
		return $block;
	} else {
		
		// Don't show the block if the channel doesn't use DJ info.
		
		return false;
	}
}

function b_uhqradio_onair_edit ($options) {
	global $xoopsDB;

	// List stations
	$form = _MB_UHQRADIO_ONAIR_OPTA;
	
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
	$form .= _MB_UHQRADIO_ONAIR_OPT_ERR;
	$form .= "<input type='radio' name='options[1]' value= '1' ";
	if ($options[1] == "1") $form .= "checked='checked'";
	$form .= "/>";
	$form .= _MB_UHQRADIO_YES;
	$form .= "<input type='radio' name='options[1]' value= '0' ";
	if ($options[1] == "0") $form .= "checked='checked'";
	$form .= "/>";
	$form .= _MB_UHQRADIO_NO;
	$form .= "<br />";
	
	return $form;
}

?>