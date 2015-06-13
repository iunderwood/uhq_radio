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

// Modular Definitions

$modversion['name'] = _MI_UHQRADIO_NAME;
$modversion['version'] = 0.20;
$modversion['description'] = _MI_UHQRADIO_DESC;
$modversion['author'] = "Ian A. Underwood";
$modversion['credits'] = "Underwood Headquarters";
$modversion['help'] = "uhq_radio.html";
$modversion['license'] = "CC-GNU GPL";
$modversion['official'] = 0;
$modversion['image'] = "images/uhq_radio_slogo.png";
$modversion['dirname'] = "uhq_radio";

// Install/Update Scripts

$modversion['onUpdate'] = "include/onupdate.php";
$modversion['onInstall'] = "include/oninstall.php";

// Database Information

$modversion['sqlfile']['mysql'] = "sql/uhq_radio.sql";

$modversion['tables'][1] = "uhqradio_handoffs";
$modversion['tables'][2] = "uhqradio_airstaff";
$modversion['tables'][3] = "uhqradio_mountpoints";
$modversion['tables'][4] = "uhqradio_channels";
$modversion['tables'][5] = "uhqradio_countmap";
$modversion['tables'][6] = "uhqradio_lhistory";
$modversion['tables'][7] = "uhqradio_shistory";

// Module-Wide Configuration Items

$modversion['hasConfig'] = 1;

$modversion['config'][] = array (
	'name'			=> 'cache_time',
	'title'			=> '_MI_UHQRADIO_OPT_CACHE_TIME',
	'description'	=> '_MI_UHQRADIO_OPT_CACHE_TIME_DESC',
	'formtype'		=> 'textbox',
	'valuetype'		=> 'int',
	'default'		=> 10
);

$modversion['config'][] = array (
	'name'			=>	'cache_external',
	'title'			=>	'_MI_UHQRADIO_OPT_CACHE_EXT',
	'description'	=>	'_MI_UHQRADIO_OPT_CACHE_EXT_DESC',
	'formtype'		=>	'yesno',
	'valuetype'		=>	'int',
	'default'		=>	0
);

$modversion['config'][] = array (
	'name'			=> 'cache_updatepw',
	'title'			=> '_MI_UHQRADIO_OPT_CACHE_UPDATEPW',
	'description'	=> '_MI_UHQRADIO_OPT_CACHE_UPDATEPW_DESC',
	'formtype'		=> 'textbox',
	'valuetype'		=> 'text',
	'default'		=> 'changeme'
);

$modversion['config'][] = array (
	'name'			=>	'sambc',
	'title'			=>	'_MI_UHQRADIO_OPT_SAMBC',
	'description'	=>	'_MI_UHQRADIO_OPT_SAMBC_DESC',
	'formtype'		=>	'yesno',
	'valuetype'		=>	'int',
	'default'		=>	0
);

$modversion['config'][] = array (
	'name'			=> 'statspw',
	'title'			=> '_MI_UHQRADIO_OPT_STATSPW',
	'description'	=> '_MI_UHQRADIO_OPT_STATSPW_DESC',
	'formtype'		=> 'textbox',
	'valuetype'		=> 'text',
	'default'		=> 'changeme'
);

$modversion['config'][] = array (
	'name'			=>	'savelh',
	'title'			=>	'_MI_UHQRADIO_OPT_SAVELH',
	'description'	=>	'_MI_UHQRADIO_OPT_SAVELH_DESC',
	'formtype'		=>	'yesno',
	'valuetype'		=>	'int',
	'default'		=>	0
);

$modversion['config'][] = array (
	'name'			=>	'savesh',
	'title'			=>	'_MI_UHQRADIO_OPT_SAVESH',
	'description'	=>	'_MI_UHQRADIO_OPT_SAVESH_DESC',
	'formtype'		=>	'yesno',
	'valuetype'		=>	'int',
	'default'		=>	0
);

$modversion['config'][] = array (
	'name'			=> 'fbapikey',
	'title'			=> '_MI_UHQRADIO_OPT_FBAPIKEY',
	'description'	=> '_MI_UHQRADIO_OPT_FBAPIKEY_DESC',
	'formtype'		=> 'textbox',
	'valuetype'		=> 'text',
	'default'		=> ''
);

$modversion['config'][] = array (
	'name'			=> 'fbsecret',
	'title'			=> '_MI_UHQRADIO_OPT_FBSECRET',
	'description'	=> '_MI_UHQRADIO_OPT_FBSECRET_DESC',
	'formtype'		=> 'textbox',
	'valuetype'		=> 'text',
	'default'		=> ''
);

$modversion['config'][] = array (
	'name'			=> 'pl_category',
	'title'			=> '_MI_UHQRADIO_OPT_PLCAT',
	'description'	=> '_MI_UHQRADIO_OPT_PLCAT_DESC',
	'formtype'		=> 'textbox',
	'valuetype'		=> 'text',
	'default'		=> ''
);

$modversion['config'][] = array (
	'name'			=> 'album_url',
	'title'			=> '_MI_UHQRADIO_OPT_ALBUMURL',
	'description'	=> '_MI_UHQRADIO_OPT_ALBUMURL_DESC',
	'formtype'		=> 'textbox',
	'valuetype'		=> 'text',
	'default'		=> ''
);

$modversion['config'][] = array (
	'name'			=> 'songhist_len',
	'title'			=> '_MI_UHQRADIO_OPT_SHISTLEN',
	'description'	=> '_MI_UHQRADIO_OPT_SHISTLEN_DESC',
	'formtype'		=> 'textbox',
	'valuetype'		=> 'int',
	'default'		=> 10
);

$modversion['config'][] = array (
	'name'			=> 'reqlist_len',
	'title'			=> '_MI_UHQRADIO_OPT_REQLEN',
	'description'	=> '_MI_UHQRADIO_OPT_REQLEN_DESC',
	'formtype'		=> 'textbox',
	'valuetype'		=> 'int',
	'default'		=> 10
);

$modversion['config'][] = array (
	'name'			=> 'request_array',
	'title'			=> '_MI_UHQRADIO_OPT_REQARRAY',
	'description'	=> '_MI_UHQRADIO_OPT_REQARRAY_DESC',
	'formtype'		=> 'group_multi',
	'valuetype'		=> 'array',
	'default'		=> array(XOOPS_GROUP_ADMIN)
);

// Administrative Items

$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

// Menu Items

$modversion['hasMain'] = 1;

require_once (XOOPS_ROOT_PATH.'/modules/uhq_radio/include/modoptions.php');

$modversion['sub'][1]['name']	= _MI_UHQRADIO_MENU_DJP;
$modversion['sub'][1]['url']	= "sm-djprofile.php";

if (uhqradio_samint()) {
	$modversion['sub'][2]['name']	= _MI_UHQRADIO_MENU_TRACKLIST;
	$modversion['sub'][2]['url']	= "sm-tracklist.php";
	$modversion['sub'][3]['name']	= _MI_UHQRADIO_MENU_SONGHIST;
	$modversion['sub'][3]['url']	= "sm-songhistory.php";
	$modversion['sub'][4]['name']	= _MI_UHQRADIO_MENU_TOPREQ;
	$modversion['sub'][4]['url']	= "sm-toprequests.php";
}



// Templates

$modversion['templates'][1]['file'] 		= "uhqradio_djprofile.html";
$modversion['templates'][1]['description']	= _MI_UHQRADIO_TEMPLATE_DJPROFILE;
$modversion['templates'][2]['file']			= "admin/uhqradio_admin_airstaff.html";
$modversion['templates'][2]['description']	= _MI_UHQRADIO_TEMPLATE_ADM_AIRSTAFF;
$modversion['templates'][3]['file']			= "admin/uhqradio_admin_mountpoints.html";
$modversion['templates'][3]['description']	= _MI_UHQRADIO_TEMPLATE_ADM_MOUNTPOINTS;
$modversion['templates'][4]['file']			= "admin/uhqradio_admin_channels.html";
$modversion['templates'][4]['description']	= _MI_UHQRADIO_TEMPLATE_ADM_CHANNELS;
$modversion['templates'][5]['file']			= "uhqradio_xml_status.xml";
$modversion['templates'][5]['description']	= _MI_UHQRADIO_TEMPLATE_XML_STATUS;
$modversion['templates'][6]['file']			= "uhqradio_index.html";
$modversion['templates'][6]['description']	= _MI_UHQRADIO_TEMPLATE_INDEX;
$modversion['templates'][7]['file']			= "uhqradio_playlist.html";
$modversion['templates'][7]['description']	= _MI_UHQRADIO_TEMPLATE_PLAYLIST;
$modversion['templates'][8]['file']			= "pop/uhqradio_pop_songinfo.html";
$modversion['templates'][8]['description']	= _MI_UHQRADIO_TEMPLATE_POP_SONGINFO;
$modversion['templates'][9]['file']			= "pop/uhqradio_pop_djtest.html";
$modversion['templates'][9]['description']	= _MI_UHQRADIO_TEMPLATE_POP_DJTEST;
$modversion['templates'][10]['file']		= "pop/uhqradio_pop_request.html";
$modversion['templates'][10]['description']	= _MI_UHQRADIO_TEMPLATE_POP_REQUEST;
$modversion['templates'][11]['file']		= "uhqradio_request.html";	// NRE Working Template
$modversion['templates'][11]['description'] = _MI_UHQRADIO_TEMPLATE_REQUEST;
$modversion['templates'][12]['file']		= "uhqradio_sm_songhistory.html";
$modversion['templates'][12]['description']	= _MI_UHQRADIO_TEMPLATE_SM_SONGHISTORY;
$modversion['templates'][13]['file']		= "uhqradio_sm_toprequests.html";
$modversion['templates'][13]['description']	= _MI_UHQRADIO_TEMPLATE_SM_TOPREQUESTS;
$modversion['templates'][14]['file']		= "admin/uhqradio_admin_index.html";
$modversion['templates'][14]['description'] = _MI_UHQRADIO_TEMPLATE_ADM_INDEX;

// Blocks

	// Radio Status Block
		// Option 0: Configured Channel ID		// Option 5: Pop-up Height
		// Option 1: Use tune-in link?			// Option 6: Show Listeners?
		// Option 2: Tune-in URL				// Option 7: Show Errors?
		// Option 3: Tune-in Target				// Option 8: Use link graphic?
		// Option 4: Pop-up Width				// Option 9: Standard=0 :: AJAX=1
		// Option 10: Show album art?

$modversion['blocks'][1]['file']		= "uhqradio_blocks.php";
$modversion['blocks'][1]['name']		= _MI_UHQRADIO_BLOCK_STATUS_NAME;
$modversion['blocks'][1]['description']	= _MI_UHQRADIO_BLOCK_STATUS_DESC;
$modversion['blocks'][1]['show_func']	= "b_uhqradio_status_show";
$modversion['blocks'][1]['edit_func']	= "b_uhqradio_status_edit";
$modversion['blocks'][1]['template']	= "uhqradio_status.html";
$modversion['blocks'][1]['options']		= "0|0|http://localhost/tunein.html|_blank|200|300|0|0|0|1|0";

	// Autoplayer Start/Stop block

$modversion['blocks'][2]['file']		= "uhqradio_blocks.php";
$modversion['blocks'][2]['name']		= _MI_UHQRADIO_BLOCK_CONTROL_NAME;
$modversion['blocks'][2]['description']	= _MI_UHQRADIO_BLOCK_CONTROL_DESC;
$modversion['blocks'][2]['show_func']	= "b_uhqradio_control_show";
$modversion['blocks'][2]['edit_func']	= "b_uhqradio_control_edit";
$modversion['blocks'][2]['template']	= "uhqradio_control.html";
$modversion['blocks'][2]['options']		= "http://autoplayer.url/start|http://autoplayer.url/stop|http://autoplayer.url/skip|stopnow|restart";

	// Handoff Block
		// Option 0: Port to automate handoff requests to
		// Option 1: Allow unverified handoffs?  1 = yes, 0 = no.
		// Option 2: Event to call to verify handoff is ready.
		// Option 3: Event to call to actually send things off.

$modversion['blocks'][3]['file']		= "uhqradio_blocks.php";
$modversion['blocks'][3]['name']		= _MI_UHQRADIO_BLOCK_HANDOFF_NAME;
$modversion['blocks'][3]['description']	= _MI_UHQRADIO_BLOCK_HANDOFF_DESC;
$modversion['blocks'][3]['show_func']	= "b_uhqradio_handoff_show";
$modversion['blocks'][3]['edit_func']	= "b_uhqradio_handoff_edit";
$modversion['blocks'][3]['template']	= "uhqradio_handoff.html";
$modversion['blocks'][3]['options']		= "1221|0|/event/test|/event/start";

	// DJ Panel Block - No Options

$modversion['blocks'][4]['file']		= "uhqradio_blocks.php";
$modversion['blocks'][4]['name']		= _MI_UHQRADIO_BLOCK_DJPANEL_NAME;
$modversion['blocks'][4]['description']	= _MI_UHQRADIO_BLOCK_DJPANEL_DESC;
$modversion['blocks'][4]['show_func']	= "b_uhqradio_djpanel_show";
$modversion['blocks'][4]['edit_func']	= "b_uhqradio_djpanel_edit";
$modversion['blocks'][4]['template']	= "uhqradio_djpanel.html";
$modversion['blocks'][4]['options']		= "";

	// DJ Listing - Options are:
		// Option 0: Number of columns to display withß
		// Option 1: Relative Font Size in CSS speak.  "medium" is normal sized.

$modversion['blocks'][5]['file']		= "uhqradio_blocks.php";
$modversion['blocks'][5]['name']		= _MI_UHQRADIO_BLOCK_DJLIST_NAME;
$modversion['blocks'][5]['description']	= _MI_UHQRADIO_BLOCK_DJLIST_DESC;
$modversion['blocks'][5]['show_func']	= "b_uhqradio_djlist_show";
$modversion['blocks'][5]['edit_func']	= "b_uhqradio_djlist_edit";
$modversion['blocks'][5]['template']	= "uhqradio_djlist.html";
$modversion['blocks'][5]['options']		= "1|medium";

	// On-Air - Options are:
		// Option 0: Channel ID
		// Option 1: Show errors?

$modversion['blocks'][6]['file']		= "uhqradio_onair.php";
$modversion['blocks'][6]['name']		= _MI_UHQRADIO_BLOCK_ONAIR_NAME;
$modversion['blocks'][6]['description']	= _MI_UHQRADIO_BLOCK_ONAIR_DESC;
$modversion['blocks'][6]['show_func']	= "b_uhqradio_onair_show";
$modversion['blocks'][6]['edit_func']	= "b_uhqradio_onair_edit";
$modversion['blocks'][6]['template']	= "uhqradio_onair.html";
$modversion['blocks'][6]['options']		= "0|0";

	// Upcoming Tracks - Options are:
		// Option 0: Channel ID
		// Option 1: Show errors?
		// Option 2: Upcoming limit
		// Option 3: Show Station IDs?
		// Option 4: List Header
		// Option 5: List Item Separator
		// Option 6: List Trailer

$modversion['blocks'][7]['file']		= "uhqradio_upcoming.php";
$modversion['blocks'][7]['name']		= _MI_UHQRADIO_BLOCK_UPCOMING_NAME;
$modversion['blocks'][7]['description']	= _MI_UHQRADIO_BLOCK_UPCOMING_DESC;
$modversion['blocks'][7]['show_func']	= "b_uhqradio_upcoming_show";
$modversion['blocks'][7]['edit_func']	= "b_uhqradio_upcoming_edit";
$modversion['blocks'][7]['template']	= "uhqradio_upcoming.html";
$modversion['blocks'][7]['options']		= "0|0|2|0||[ | :: | ]";

	// Listener History - Options are:
		// Option 0: Channel ID
		// Option 1: Show errors?
		// Option 2: Number of intervals,
		// Option 3: Output type: List, Graph

$modversion['blocks'][8]['file']		= "uhqradio_lhistory.php";
$modversion['blocks'][8]['name']		= _MI_UHQRADIO_BLOCK_LHIST_NAME;
$modversion['blocks'][8]['description']	= _MI_UHQRADIO_BLOCK_LHIST_DESC;
$modversion['blocks'][8]['show_func']	= "b_uhqradio_lhistory_show";
$modversion['blocks'][8]['edit_func']	= "b_uhqradio_lhistory_edit";
$modversion['blocks'][8]['template']	= "uhqradio_lhistory.html";
$modversion['blocks'][8]['options']		= "0|0|15|L|1";

	// Song History - Options are:
		// Option 0: Channel ID
		// Option 1: Show errors?
		// Option 2: History Length
		// Option 3: Extended Form?

$modversion['blocks'][9]['file']		= "uhqradio_shistory.php";
$modversion['blocks'][9]['name']		= _MI_UHQRADIO_BLOCK_SHIST_NAME;
$modversion['blocks'][9]['description']	= _MI_UHQRADIO_BLOCK_SHIST_DESC;
$modversion['blocks'][9]['show_func']	= "b_uhqradio_shistory_show";
$modversion['blocks'][9]['edit_func']	= "b_uhqradio_shistory_edit";
$modversion['blocks'][9]['template']	= "uhqradio_shistory.html";
$modversion['blocks'][9]['options']		= "0|0|10";