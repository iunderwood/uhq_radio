<?php

/*
UHQ-Radio :: XOOPS Live Radio Broadcast Module
Copyright (C) 2008-2015 :: Ian A. Underwood :: xoops@underwood-hq.org

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

/*	xml_status.php

Return an XML output, providing status information for a specific channel using full details.
Return an XML output, following the schema for IceCast statistics, providing listener count only for all channels.

*/

include '../../mainfile.php';

require_once XOOPS_ROOT_PATH . '/class/template.php';
if (!isset($xoopsTpl)) {
	$xoopsTpl = new XoopsTpl();
}

$xoopsTpl->xoops_setCaching(0);

// Disable all debugging for this file.

$xoopsLogger->activated = false;

include_once XOOPS_ROOT_PATH.'/modules/uhq_radio/include/functions.php';
include_once XOOPS_ROOT_PATH.'/modules/uhq_radio/include/sanity.php';
require_once (XOOPS_ROOT_PATH.'/modules/uhq_radio/include/modoptions.php');
require_once (XOOPS_ROOT_PATH.'/modules/uhq_radio/include/sambc.php');


// Function for output, because we can break.

function xml_stats() {
	global $xoopsDB;
	global $xoopsTpl;

	$sane = uhqradio_dosanity();

	// Verify we have a channel ID
	if (isset ($sane['chid'])) {

		$data = uhqradio_data_status($sane['chid']);
		$data['baseurl'] = uhqradio_opt_albumurl();

		$xoopsTpl->assign('data',$data);

		// Process Counter Mountpoints
	} elseif (isset ($_REQUEST['icecast']) )  {
		$data['icecast'] = 1;

		$i = 0;
		$i2 = 0;

		if ( uhqradio_statspw($_SERVER['PHP_AUTH_PW']) == true ) {
			// Load channels
			$query = "SELECT * FROM ".$xoopsDB->prefix('uhqradio_channels');
			$result = $xoopsDB->queryF($query);
			if ($result == false) {
				$xoopsTpl->assign('error',$xoopsDB->error());
				return false;
			}

			// Return each station and its listener population.
			while ($row = $xoopsDB->fetchArray($result) ) {
				$data['channel'][$i]['chid'] = $row['chid'];
				$data['channel'][$i]['population'] = uhqradio_listeners($row['chid']);
				$i++;
			}
			$i2 = $i2 + $data['channel'][$i]['population'];
		}
		$data['sources'] = $i;

		$xoopsTpl->assign('data',$data);
	} else {
		$xoopsTpl->assign('error',_MD_UHQRADIO_XMLSTATUS_CREQ);
	}
}

// Set header

header ('Content-Type: text/xml');

xml_stats();

$xoopsTpl->display('db:uhqradio_xml_status.xml');