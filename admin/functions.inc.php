<?php

// Display the airstaff summary

function uhqradio_airstaffsum ($djid = '') {
	global $xoopsDB;
	
	// Count Airstaff
	$query = "SELECT COUNT(*) FROM ".$xoopsDB->prefix('uhqradio_airstaff');
	
	// Add a DJ ID to search for if it's provided
	if ( $djid ) {
		$query .= "WHERE djid = '".$djid."'";
	}
	
	$result = $xoopsDB->queryF($query);
	if ($result == false) {
		echo _AM_UHQRADIO_SQLERR.$query;
		return false;
	} else {
		list ($count) = $xoopsDB->fetchRow($result);
	}
	
	if ($count == 0) {
		echo _AM_UHQRADIO_AIR_NONE;
	} else {
		echo $count;
		if ($count > 1) {
			echo _AM_UHQRADIO_AIR_PLU;
		} else {
			echo _AM_UHQRADIO_AIR_ONE;
		}				
	}	
	
	return $count;
}

// Mountpoint summary

function uhqradio_mountpointsum () {
	global $xoopsDB;
	
	// Count Airstaff
	$query = "SELECT COUNT(*) FROM ".$xoopsDB->prefix('uhqradio_mountpoints');
		
	$result = $xoopsDB->queryF($query);
	if ($result == false) {
		echo _AM_UHQRADIO_SQLERR.$query;
		return false;
	} else {
		list ($count) = $xoopsDB->fetchRow($result);
	}
	
	if ($count == 0) {
		echo _AM_UHQRADIO_MOUNT_NONE;
	} else {
		echo $count;
		if ($count > 1) {
			echo _AM_UHQRADIO_MOUNT_PLU;
		} else {
			echo _AM_UHQRADIO_MOUNT_ONE;
		}				
	}	
	
	return $count;
}

// Channel summary

function uhqradio_channelsum () {
	global $xoopsDB;
	
	// Count Airstaff
	$query = "SELECT COUNT(*) FROM ".$xoopsDB->prefix('uhqradio_channels');
		
	$result = $xoopsDB->queryF($query);
	if ($result == false) {
		echo _AM_UHQRADIO_SQLERR.$query;
		return false;
	} else {
		list ($count) = $xoopsDB->fetchRow($result);
	}
	
	if ($count == 0) {
		echo _AM_UHQRADIO_CHANNEL_NONE;
	} else {
		echo $count;
		if ($count > 1) {
			echo _AM_UHQRADIO_CHANNEL_PLU;
		} else {
			echo _AM_UHQRADIO_CHANNEL_ONE;
		}				
	}	
	
	return $count;
}

// Return the sumary count of a table.

function uhqradio_summarycount($sumtype) {
	global $xoopsDB;
	
	switch ($sumtype) {
		case "A":	// Airstaff
			$query = "SELECT COUNT(*) FROM ".$xoopsDB->prefix('uhqradio_airstaff');
			break;
		case "M":	// Mount Points
			$query = "SELECT COUNT(*) FROM ".$xoopsDB->prefix('uhqradio_mountpoints');
			break;
		case "C":	// Channels
			$query = "SELECT COUNT(*) FROM ".$xoopsDB->prefix('uhqradio_channels');
			break;
		default:
			return false;
	}
	
	$result = $xoopsDB->queryF($query);
	if ($result == false) {
		return false;
	} else {
		list ($count) = $xoopsDB->fetchRow($result);
		return $count;
	}
}

// Return the name given a server input type.

function uhqradio_fullservertype($input) {

	switch ($input) {
		case "I" : $output = _AM_UHQRADIO_STYPE_I; break;
		case "P" : $output = _AM_UHQRADIO_STYPE_P; break;
		case "S" : $output = _AM_UHQRADIO_STYPE_S; break;
		default: $output = $input; break;
	}
	
	return $output;
}

?>