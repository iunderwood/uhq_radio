<?php

// Adjust icon path depending on the XOOPS version we're using.

$versioninfo=array();
preg_match_all('/\d+/',XOOPS_VERSION,$versioninfo);
if ( ($versioninfo[0][0] >= 2) && ($versioninfo[0][1] >= 4) ) {
  $menuiconpath = "/";
} else {
  $menuiconpath = "../../../../uhq_radio/";
}

// Assign goodies for Admin Menu

$adminmenu[0]['title'] = _MI_UHQRADIO_ADMENU_0;
$adminmenu[0]['link'] = "admin/index.php";
$adminmenu[0]['icon'] = $menuiconpath."images/menu_index.png";

$adminmenu[1]['title'] = _MI_UHQRADIO_ADMENU_1;
$adminmenu[1]['link'] = "admin/airstaff.php";
$adminmenu[1]['icon'] = $menuiconpath."images/menu_airstaff.png";

$adminmenu[2]['title'] = _MI_UHQRADIO_ADMENU_2;
$adminmenu[2]['link'] = "admin/mountpoints.php";
$adminmenu[2]['icon'] = $menuiconpath."images/menu_mounts.png";

$adminmenu[3]['title'] = _MI_UHQRADIO_ADMENU_3;
$adminmenu[3]['link'] = "admin/channels.php";
$adminmenu[3]['icon'] = $menuiconpath."images/menu_channels.png";

$adminmenu[4]['title'] = _MI_UHQRADIO_ADMENU_4;
$adminmenu[4]['link'] = "admin/playlists.php";
$adminmenu[4]['icon'] = $menuiconpath."images/menu_playlists.png";

?>