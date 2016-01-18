<?php

// Module Information

define("_MI_UHQRADIO_NAME", "UHQ_Radio");
define("_MI_UHQRADIO_DESC", "The radio module returns real-time information about what's playing on an Internet radio station.");

// Installer Conditions

define("_MI_UHQRADIO_INSTALL_ANON_OK","Anonymous access granted successfully.");
define("_MI_UHQRADIO_INSTALL_ANON_NOK","Unable to grant anonymous module access.");

define("_MI_UHQRADIO_INSTALL_USERS_OK","Registered user access granted successfully.");
define("_MI_UHQRADIO_INSTALL_USERS_NOK","Unable to grant registered user module access.");

define("_MI_UHQRADIO_INSTALL_WEIGHT_OK","Module weight set to 1 successfully.");
define("_MI_UHQRADIO_INSTALL_WEIGHT_NOK","Unable to set module weight.");

// Configuration Options

define("_MI_UHQRADIO_OPT_CACHE_TIME", "XML Cache Time");
define("_MI_UHQRADIO_OPT_CACHE_TIME_DESC", "Seconds to cache gathered XML data for.");

define("_MI_UHQRADIO_OPT_CACHE_EXT", "Use External Cache");
define("_MI_UHQRADIO_OPT_CACHE_EXT_DESC", "If yes, an external script will update XML caches.");

define("_MI_UHQRADIO_OPT_CACHE_UPDATEPW", "External Cache Update PW");
define("_MI_UHQRADIO_OPT_CACHE_UPDATEPW_DESC","Cache updates won't be accepted w/o the password set here.");

define("_MI_UHQRADIO_OPT_SAMBC", "Enable SAM Broadcaster");
define("_MI_UHQRADIO_OPT_SAMBC_DESC","If yes, display options and fields for integrating with SAM Broadcaster.");

define("_MI_UHQRADIO_OPT_STATSPW", "Statistics Password");
define("_MI_UHQRADIO_OPT_STATSPW_DESC", "Password for external site-wide stats count.");

define("_MI_UHQRADIO_OPT_SAVELH","Save Listener History");
define("_MI_UHQRADIO_OPT_SAVELH_DESC","If yes, save listener history for each mountpoint during an external cache update.");

define("_MI_UHQRADIO_OPT_SAVESH","Save Song History");
define("_MI_UHQRADIO_OPT_SAVESH_DESC","If yes, save song history for each channel during an external cache update");

define("_MI_UHQRADIO_OPT_FBAPIKEY","Facebook API Key");
define("_MI_UHQRADIO_OPT_FBAPIKEY_DESC","Your Facebook public API key");

define("_MI_UHQRADIO_OPT_FBSECRET","Facebook Secret Key");
define("_MI_UHQRADIO_OPT_FBSECRET_DESC","Your Facebook secret key");

define("_MI_UHQRADIO_OPT_PLCAT","Playlist Category");
define("_MI_UHQRADIO_OPT_PLCAT_DESC","SAMBC: Displays requestable songs from one category only.  Leave blank for all tracks.");

define("_MI_UHQRADIO_OPT_ALBUMURL","Album URL");
define("_MI_UHQRADIO_OPT_ALBUMURL_DESC","SAMBC: Base URL for all album art.  e.g. http://images.your.site (No trailing slash)");

define("_MI_UHQRADIO_OPT_SHISTLEN","Song History Length");
define("_MI_UHQRADIO_OPT_SHISTLEN_DESC","Number of previous songs show in the song history submenu.");

define("_MI_UHQRADIO_OPT_REQLEN","Request History Length");
define("_MI_UHQRADIO_OPT_REQLEN_DESC","SAMBC: Number of requests to show in the Top Requests submenu.");

define("_MI_UHQRADIO_OPT_REQARRAY","Request Permissions");
define("_MI_UHQRADIO_OPT_REQARRAY_DESC","SAMBC: Group membership required in order to place a request.");

// Menu Options

define("_MI_UHQRADIO_MENU_DJP","DJ Profiles");
define("_MI_UHQRADIO_MENU_TRACKLIST","Track Search");
define("_MI_UHQRADIO_MENU_SONGHIST","Song History");
define("_MI_UHQRADIO_MENU_TOPREQ","Top Requests");

define("_MI_UHQRADIO_ADMENU_0", "Index");
define("_MI_UHQRADIO_ADMENU_1", "Airstaff");
define("_MI_UHQRADIO_ADMENU_2", "Mountpoints");
define("_MI_UHQRADIO_ADMENU_3", "Channels");
define("_MI_UHQRADIO_ADMENU_4", "Playlists");

// Template Descriptions

define("_MI_UHQRADIO_TEMPLATE_DJPROFILE","The nuts and bolts of the DJ Profile Page.");
define("_MI_UHQRADIO_TEMPLATE_PLAYLIST","The nuts and bolts of the Playlist Page.");
define("_MI_UHQRADIO_TEMPLATE_REQUEST","The New Request Engine");
define("_MI_UHQRADIO_TEMPLATE_INDEX","The main radio page.");

define("_MI_UHQRADIO_TEMPLATE_ADM_AIRSTAFF","The administrative airstaff page.");
define("_MI_UHQRADIO_TEMPLATE_ADM_MOUNTPOINTS","The administrative mountpoint page.");
define("_MI_UHQRADIO_TEMPLATE_ADM_CHANNELS","The administrative channel page.");
define("_MI_UHQRADIO_TEMPLATE_ADM_INDEX","Administrative Index.");

define("_MI_UHQRADIO_TEMPLATE_POP_SONGINFO","The Song Information Popup");
define("_MI_UHQRADIO_TEMPLATE_POP_DJTEST","DJ Test Page");
define("_MI_UHQRADIO_TEMPLATE_POP_REQUEST","The Request Popup");

define("_MI_UHQRADIO_TEMPLATE_XML_STATUS","XML Template for Status Page");
define("_MI_UHQRADIO_TEMPLATE_ECU","The external cache update output.");

define("_MI_UHQRADIO_TEMPLATE_SM_SONGHISTORY","Submenu: Song History");
define("_MI_UHQRADIO_TEMPLATE_SM_TOPREQUESTS","Submenu: Top Requests");

// Updates

define("_MI_UHQRADIO_UPDATE_AIRSTAFF","Added uhqradio_airstaff to the database. ");
define("_MI_UHQRADIO_UPDATE_MOUNTPOINTS","Added uhqradio_mountpoints to the database. ");
define("_MI_UHQRADIO_UPDATE_CHANNELS","Added uhqradio_channels to the database. ");
define("_MI_UHQRADIO_UPDATE_COUNTMAP","Added uhqradio_countmap to the database. ");
define("_MI_UHQRADIO_UPDATE_LHISTORY","Added uhqradio_lhistory to the database.  ");
define("_MI_UHQRADIO_UPDATE_SHISTORY","Added uhqradio_shistory to the database.  ");

// Block Information

define("_MI_UHQRADIO_BLOCK_STATUS_NAME", "Radio Status");
define("_MI_UHQRADIO_BLOCK_STATUS_DESC", "This block displays the current online status and what is now playing.");

define("_MI_UHQRADIO_BLOCK_CONTROL_NAME", "Radio Control");
define("_MI_UHQRADIO_BLOCK_CONTROL_DESC", "This block helps control an autoplayer via a URL.");

define("_MI_UHQRADIO_BLOCK_HANDOFF_NAME", "Handoff Control");
define("_MI_UHQRADIO_BLOCK_HANDOFF_DESC", "This block controls automated handoffs.");

define("_MI_UHQRADIO_BLOCK_DJPANEL_NAME","DJ Panel");
define("_MI_UHQRADIO_BLOCK_DJPANEL_DESC","This block provides a small DJ info panel to a DJ.");

define("_MI_UHQRADIO_BLOCK_DJLIST_NAME", "DJ List");
define("_MI_UHQRADIO_BLOCK_DJLIST_DESC", "Lists all station DJs in a nice block.");

define("_MI_UHQRADIO_BLOCK_ONAIR_NAME","On-Air");
define("_MI_UHQRADIO_BLOCK_ONAIR_DESC","Show the DJ who is live on-air.");

define("_MI_UHQRADIO_BLOCK_STN_STATUS_NAME", "Station Radio Status");
define("_MI_UHQRADIO_BLOCK_STN_STATUS_DESC", "This block displays the current online status and what is now playing.");

define("_MI_UHQRADIO_BLOCK_UPCOMING_NAME","Coming Up...");
define("_MI_UHQRADIO_BLOCK_UPCOMING_DESC","A simple list of what's coming up.");

define("_MI_UHQRADIO_BLOCK_LHIST_NAME","Listener History");
define("_MI_UHQRADIO_BLOCK_LHIST_DESC","Either a list or a graph of listener history over a period of time.");

define("_MI_UHQRADIO_BLOCK_SHIST_NAME","Song History");
define("_MI_UHQRADIO_BLOCK_SHIST_DESC","The last x songs played on a given channel.");

?>