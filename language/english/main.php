<?php

// Error Conditions

define("_MD_UHQRADIO_ERROR_LOGIN", "You must be logged in to perform this function.");
define("_MD_UHQRADIO_ERROR_NOAUTH", "Unable to authenticate this request.");
define("_MD_UHQRADIO_ERROR_NOPERM", "You do not have permission to execute this command.");
define("_MD_UHQRADIO_ERROR_BLOCKOPT", "Unable to load block options.");
define("_MD_UHQRADIO_ERROR_UNSUPPORTED", "Unsupported Operation: ");
define("_MD_UHQRADIO_ERROR_NOOP", "No Operation Specified");
define("_MD_UHQRADIO_ERROR_DIRECT", "You cannot call this page directly.");
define("_MD_UHQRADIO_ERROR_SQL","SQL Error: ");

// Command Responses

define("_MD_UHQRADIO_CMD_START_OK", "Start Command Accepted");
define("_MD_UHQRADIO_CMD_START_NOK", "Start Command Failed");
define("_MD_UHQRADIO_CMD_START_NOCONF", "Start Command Not Configured");

define("_MD_UHQRADIO_CMD_STOP_OK", "Stop Command Accepted");
define("_MD_UHQRADIO_CMD_STOP_NOK", "Stop Command Failed");
define("_MD_UHQRADIO_CMD_STOP_NOCONF", "Stop Command Not Configured");

define("_MD_UHQRADIO_CMD_STOPNOW_OK", "Stop NOW Command Accepted");
define("_MD_UHQRADIO_CMD_STOPNOW_NOK", "Stop NOW Command Failed");
define("_MD_UHQRADIO_CMD_STOPNOW_NOCONF", "Stop NOW Command Not Configured");

define("_MD_UHQRADIO_CMD_SKIP_OK", "Skip Command Accepted");
define("_MD_UHQRADIO_CMD_SKIP_NOK", "Skip Command Failed");
define("_MD_UHQRADIO_CMD_SKIP_NOCONF", "Skip Command Not Configured");

define("_MD_UHQRADIO_CMD_REW_OK", "Rewind Command Accepted");
define("_MD_UHQRADIO_CMD_REW_NOK", "Rewind Command Failed");
define("_MD_UHQRADIO_CMD_REW_NOCONF", "Rewind Command Not Configured");

// Handoff Responses

define("_MD_UHQRADIO_HANDOFF_OK", "Handoff Successful");
define("_MD_UHQRADIO_HANDOFF_NOK", "Handoff Unsuccessful");

define("_MD_UHQRADIO_HANDOFF_VERIFY_OK", "Handoff Request Accepted.");
define("_MD_UHQRADIO_HANDOFF_VERIFY_MANUAL", "Handoff Capability Failed.  You must manually take the stream.");
define("_MD_UHQRADIO_HANDOFF_VERIFY_NOK", "Handoff Request Failed.  Unable to verify your player is ready.");

// Index Page

define("_MD_UHQRADIO_INDEX_HEADER","Radio Information");
define("_MD_UHQRADIO_INDEX_B_ALBUM","Browse by Album");
define("_MD_UHQRADIO_INDEX_B_ARTIST","Browse by Artist");
define("_MD_UHQRADIO_INDEX_ALBUM","Album");
define("_MD_UHQRADIO_INDEX_ALBUMS","Albums");
define("_MD_UHQRADIO_INDEX_ARTIST","Artist");
define("_MD_UHQRADIO_INDEX_ARTISTS","Artists");
define("_MD_UHQRADIO_INDEX_INDB","in the database.");
define("_MD_UHQRADIO_INDEX_ITEM","item");
define("_MD_UHQRADIO_INDEX_ITEMS","items");
define("_MD_UHQRADIO_INDEX_STARTWITH","starting with");
define("_MD_UHQRADIO_INDEX_TRACKSBY","tracks by");
define("_MD_UHQRADIO_INDEX_NUMBERS","numbers");
define("_MD_UHQRADIO_INDEX_TITLE","Title");
define("_MD_UHQRADIO_INDEX_DURATION","Duration");
define("_MD_UHQRADIO_INDEX_LINK","Link");
define("_MD_UHQRADIO_INDEX_INFO","Info");

define("_MD_UHQRADIO_INDEX_LAST","Last");
define("_MD_UHQRADIO_INDEX_ADDS","Additions");

define("_MD_UHQRADIO_INDEX_SA_GENRE","Genre");
define("_MD_UHQRADIO_INDEX_SA_LYEAR","Label/Year");
define("_MD_UHQRADIO_INDEX_SA_YEAR","Year");
define("_MD_UHQRADIO_INDEX_SA_DATE","Date Added");

// Submenu: DJ Profile

define("_MD_UHQRADIO_DJPROFILE_ERR","Error");
define("_MD_UHQRADIO_DJPROFILE_NPF","Profile not found!");
define("_MD_UHQRADIO_DJPROFILE_NPS","Profile not specified!");
define("_MD_UHQRADIO_DJPROFILE_NYP","You can't edit someone else's profile!");
define("_MD_UHQRADIO_DJPROFILE_HDR","DJ Profile");
define("_MD_UHQRADIO_DJPROFILE_REQ_OK","Accepting requests!");
define("_MD_UHQRADIO_DJPROFILE_REQ_NOK","Not taking requests.");
define("_MD_UHQRADIO_DJPROFILE_EYI","Edit Your Info");
define("_MD_UHQRADIO_DJPROFILE_WEB","Website");
define("_MD_UHQRADIO_DJPROFILE_PLAYGA","Games Played");
define("_MD_UHQRADIO_DJPROFILE_PLAYMU","Music Played");
define("_MD_UHQRADIO_DJPROFILE_BLURB","The Story");
define("_MD_UHQRADIO_DJPROFILE_NOLIST","No DJs Listed");


// Submenu: Track Search

define("_MD_UHQRADIO_PLAYLIST_NA","Radio integration is not enabled.");
define("_MD_UHQRADIO_PLAYLIST_NOTRACKS","No tracks currently requestable.");
define("_MD_UHQRADIO_PLAYLIST_ONETRACK","One track available.");
define("_MD_UHQRADIO_PLAYLIST_MORETRACKS"," tracks available.");
define("_MD_UHQRADIO_PLAYLIST_SHOW","Showing");
define("_MD_UHQRADIO_PLAYLIST_THRU"," through ");
define("_MD_UHQRADIO_PLAYLIST_PREV","Prev");
define("_MD_UHQRADIO_PLAYLIST_NEXT","Next");
define("_MD_UHQRADIO_PLAYLIST_LINK_INFO","Info");
define("_MD_UHQRADIO_PLAYLIST_LINK_REQ","Req.");
define("_MD_UHQRADIO_PLAYLIST_CLEARSEARCH","Clear Search");

// Submenu: Song HIstory

define("_MD_UHQRADIO_SHIST_HDR1","Last ");
define("_MD_UHQRADIO_SHIST_HDR2"," tracks played:");
define("_MD_UHQRADIO_SHIST_NA","Song history not available.");
define("_MD_UHQRADIO_SHIST_ARTIST","Artist");
define("_MD_UHQRADIO_SHIST_TITLE","Title");
define("_MD_UHQRADIO_SHIST_ALBUM","Album");
define("_MD_UHQRADIO_SHIST_YEAR","Year");
define("_MD_UHQRADIO_SHIST_REQ","Requested");
define("_MD_UHQRADIO_SHIST_BY","by");

// Submenu: Top Requests

define("_MD_UHQRADIO_TOPREQ_TOP","Top");
define("_MD_UHQRADIO_TOPREQ_LM","Requests Last Month");
define("_MD_UHQRADIO_TOPREQ_AT","Requests of All Time");
define("_MD_UHQRADIO_TOPREQ_NOLM","No requests recorded last month.");
define("_MD_UHQRADIO_TOPREQ_NOAT","No requests recorded.");

define("_MD_UHQRADIO_TOPREQ_THREQ","Reqs");
define("_MD_UHQRADIO_TOPREQ_THSONG","Song");
define("_MD_UHQRADIO_TOPREQ_THALBUM","Album");

// XML Status Page

define("_MD_UHQRADIO_XMLSTATUS_BADPW","Correct password required");
define("_MD_UHQRADIO_XMLSTATUS_CND","Channel not defined");
define("_MD_UHQRADIO_XMLSTATUS_CREQ","Channel ID Required");
define("_MD_UHQRADIO_XMLSTATUS_MLERR","Mount Load Error: ");
define("_MD_UHQRADIO_XMLSTATUS_MNF","Mount Not Found");
define("_MD_UHQRADIO_XMLSTATUS_NOTEXT","Unable to load text info");
define("_MD_UHQRADIO_XMLSTATUS_SCOFF","Shoutcast Offline");
define("_MD_UHQRADIO_XMLSTATUS_TMNF","Text Mount Not Found");

// ECU Page

define("_MD_UHQRADIO_ECU_IP","Insufficient parameters.");
define("_MD_UHQRADIO_ECU_BADPW","Bad update password.");
define("_MD_UHQRADIO_ECU_BADTYPE","Bad update type: ");
define("_MD_UHQRADIO_ECU_XMLP","Update Pass: ");
define("_MD_UHQRADIO_ECU_XMLF","Update Fail: ");

// DJ Test Popup

define("_MD_UHQRADIO_POP_DJT_TITLE","DJ Test Results");
define("_MD_UHQRADIO_POP_DJT_GOOD","Good!");
define("_MD_UHQRADIO_POP_DJT_SKIP","Skipped!");
define("_MD_UHQRADIO_POP_DJT_ERR","Error:");
define("_MD_UHQRADIO_POP_DJT_SAMFAIL","Unable to reach port ");
define("_MD_UHQRADIO_POP_DJT_TEST1","MySQL Connect");
define("_MD_UHQRADIO_POP_DJT_TEST2","MySQL Select");
define("_MD_UHQRADIO_POP_DJT_TEST3","SAM Connect");

?>