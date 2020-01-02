<?php

use Xmf\Module\Admin;
use Xmf\Module\Helper;

defined('XOOPS_ROOT_PATH') || exit('Restricted access.');

use Xoopsmodules\uhqradio;

require_once __DIR__ . '/../class/Helper.php';
//require_once __DIR__ . '/../include/common.php';
$helper = uhqradio\Helper::getInstance();

$pathIcon32 = \Xmf\Module\Admin::menuIconPath('');
$pathModIcon32 = $helper->getModule()->getInfo('modicons32');

$adminmenu[] = [
    'title' => _MI_UHQRADIO_ADMENU_0,
    'link'  => 'admin/index.php',
    'icon'  => $pathModIcon32 . '/menu_index.png'
];

$adminmenu[] = [
    'title' => _MI_UHQRADIO_ADMENU_1,
    'link'  => 'admin/airstaff.php',
    'icon'  => $pathModIcon32 . '/menu_airstaff.png'
];

$adminmenu[] = [
    'title' => _MI_UHQRADIO_ADMENU_2,
    'link'  => 'admin/mountpoints.php',
    'icon'  => $pathModIcon32 . '/menu_mounts.png'
];

$adminmenu[] = [
    'title' => _MI_UHQRADIO_ADMENU_3,
    'link'  => 'admin/channels.php',
    'icon'  => $pathModIcon32 . '/menu_channels.png'
];

$adminmenu[] = [
    'title' => _MI_UHQRADIO_ADMENU_4,
    'link'  => 'admin/playlists.php',
    'icon'  => $pathModIcon32 . '/menu_playlists.png'
];

$adminmenu[] = [
    'title' => _MI_UHQRADIO_ADMENU_ABOUT,
    'link'  => 'admin/about.php',
    'icon'  => $pathIcon32 . '/about.png'
];

// Adjust icon path depending on the XOOPS version we're using.

/*
$versioninfo = array();
preg_match_all('/\d+/', XOOPS_VERSION, $versioninfo);
if (($versioninfo[0][0] >= 2) && ($versioninfo[0][1] >= 4)) {
    $menuiconpath = "/";
} else {
    $menuiconpath = "../../../../uhq_radio/";
}

// Assign goodies for Admin Menu

$adminmenu[0]['title'] = _MI_UHQRADIO_ADMENU_0;
$adminmenu[0]['link']  = "admin/index.php";
$adminmenu[0]['icon']  = $menuiconpath . "images/menu_index.png";

$adminmenu[1]['title'] = _MI_UHQRADIO_ADMENU_1;
$adminmenu[1]['link']  = "admin/airstaff.php";
$adminmenu[1]['icon']  = $menuiconpath . "images/menu_airstaff.png";

$adminmenu[2]['title'] = _MI_UHQRADIO_ADMENU_2;
$adminmenu[2]['link']  = "admin/mountpoints.php";
$adminmenu[2]['icon']  = $menuiconpath . "images/menu_mounts.png";

$adminmenu[3]['title'] = _MI_UHQRADIO_ADMENU_3;
$adminmenu[3]['link']  = "admin/channels.php";
$adminmenu[3]['icon']  = $menuiconpath . "images/menu_channels.png";

$adminmenu[4]['title'] = _MI_UHQRADIO_ADMENU_4;
$adminmenu[4]['link']  = "admin/playlists.php";
$adminmenu[4]['icon']  = $menuiconpath . "images/menu_playlists.png";

*/
