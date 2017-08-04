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

$moduleDirName = basename(__DIR__);

// ------------------- Informations ------------------- //
$modversion = array(
    'version'             => 0.20,
    'module_status'       => 'Beta 1',
    'release_date'        => '2015/06/17', //yyyy/mm/dd
    'name'                => _MI_UHQRADIO_NAME,
    'description'         => _MI_UHQRADIO_DESC,
    'official'            => 1, //1 indicates supported by XOOPS Dev Team, 0 means 3rd party supported
    'author'              => 'Ian A. Underwood',
    'author_mail'         => 'author-email',
    'author_website_url'  => 'https://xoops.org',
    'author_website_name' => 'XOOPS',
    'credits'             => 'Underwood Headquarters, XOOPS Development Team',
    'license'             => 'GPL 2.0 or later',
    'license_url'         => 'www.gnu.org/licenses/gpl-2.0.html/',
    'help'                => 'page=help',
    'release_info'        => 'Changelog',
    'release_file'        => XOOPS_URL . '/modules/{$moduleDirName}/docs/changelog file',
    //
    'manual'              => 'link to manual file',
    'manual_file'         => XOOPS_URL . '/modules/{$moduleDirName}/docs/install.txt',
    'min_php'             => '5.5',
    'min_xoops'           => '2.5.9',
    'min_admin'           => '1.1',
    'min_db'              => array('mysql' => '5.5'),
    // images
    'image'               => 'assets/images/logoModule.png',
    'iconsmall'           => 'assets/images/iconsmall.png',
    'iconbig'             => 'assets/images/iconbig.png',
    'dirname'             => "{$moduleDirName}",
    //Frameworks
    //    'dirmoduleadmin'      => 'Frameworks/moduleclasses/moduleadmin',
    //    'sysicons16'          => 'Frameworks/moduleclasses/icons/16',
    //    'sysicons32'          => 'Frameworks/moduleclasses/icons/32',
    // Local path icons
    'modicons16'          => 'assets/images',//'assets/images/icons/16',
    'modicons32'          => 'assets/images',//'assets/images/icons/32',
    //    'release'             => '2015-04-04',
    'demo_site_url'       => 'https://xoops.org',
    'demo_site_name'      => 'XOOPS Demo Site',
    'support_url'         => 'https://xoops.org/modules/newbb',
    'support_name'        => 'Support Forum',
    'module_website_url'  => 'www.xoops.org',
    'module_website_name' => 'XOOPS Project',

    // paypal
    //    'paypal' => array(
    //        'business'      => 'XXX@email.com',
    //        'item_name'     => 'Donation : ' . _AM_MODULE_DESC,
    //        'amount'        => 0,
    //        'currency_code' => 'USD'),

    // Admin system menu
    'system_menu'         => 1,
    // Admin menu
    'hasAdmin'            => 1,
    'adminindex'          => 'admin/index.php',
    'adminmenu'           => 'admin/menu.php',
    // Main menu
    'hasMain'             => 1,
    //Search & Comments
    //    'hasSearch'           => 1,
    //    'search'              => array(
    //        'file'   => 'include/search.inc.php',
    //        'func'   => 'XXXX_search'),
    //    'hasComments'         => 1,
    //    'comments'              => array(
    //        'pageName'   => 'index.php',
    //        'itemName'   => 'id'),

    // Install/Update
    'onInstall'           => 'include/oninstall.php',
    'onUpdate'            => 'include/onupdate.php'//  'onUninstall'         => 'include/onuninstall.php'

);

// ------------------- Mysql ------------------- //
$modversion['sqlfile']['mysql'] = 'sql/' . $moduleDirName . '.sql';
//$modversion['sqlfile']['mysql'] = 'sql/'.$moduleDirName.'_dev.sql';

// Tables created by sql file (without prefix!)
//$modversion['tables'] = array(
//    $moduleDirName . '_handoffs',
//    $moduleDirName . '_airstaff',
//    $moduleDirName . '_mountpoints',
//    $moduleDirName . '_channels',
//    $moduleDirName . '_countmap',
//    $moduleDirName . '_lhistory',
//    $moduleDirName . '_shistory');

$modversion['tables'] = array(
    'uhqradio_handoffs',
    'uhqradio_airstaff',
    'uhqradio_mountpoints',
    'uhqradio_channels',
    'uhqradio_countmap',
    'uhqradio_lhistory',
    'uhqradio_shistory'
);

// ------------------- Templates ------------------- //

// Modular Definitions

//$modversion['name']        = _MI_UHQRADIO_NAME;
//$modversion['version']     = 0.20;
//$modversion['description'] = _MI_UHQRADIO_DESC;
//$modversion['author']      = 'Ian A. Underwood';
//$modversion['credits']     = 'Underwood Headquarters';
//$modversion['help']        = 'uhq_radio.html';
//$modversion['license']     = 'CC-GNU GPL';
//$modversion['official']    = 0;
//$modversion['image']       = 'images/uhq_radio_slogo.png';
//$modversion['dirname']     = 'uhq_radio';

// Module-Wide Configuration Items

$modversion['hasConfig'] = 1;

$modversion['config'][] = array(
    'name'        => 'cache_time',
    'title'       => '_MI_UHQRADIO_OPT_CACHE_TIME',
    'description' => '_MI_UHQRADIO_OPT_CACHE_TIME_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 10
);

$modversion['config'][] = array(
    'name'        => 'cache_external',
    'title'       => '_MI_UHQRADIO_OPT_CACHE_EXT',
    'description' => '_MI_UHQRADIO_OPT_CACHE_EXT_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0
);

$modversion['config'][] = array(
    'name'        => 'cache_updatepw',
    'title'       => '_MI_UHQRADIO_OPT_CACHE_UPDATEPW',
    'description' => '_MI_UHQRADIO_OPT_CACHE_UPDATEPW_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'changeme'
);

$modversion['config'][] = array(
    'name'        => 'sambc',
    'title'       => '_MI_UHQRADIO_OPT_SAMBC',
    'description' => '_MI_UHQRADIO_OPT_SAMBC_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0
);

$modversion['config'][] = array(
    'name'        => 'statspw',
    'title'       => '_MI_UHQRADIO_OPT_STATSPW',
    'description' => '_MI_UHQRADIO_OPT_STATSPW_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'changeme'
);

$modversion['config'][] = array(
    'name'        => 'savelh',
    'title'       => '_MI_UHQRADIO_OPT_SAVELH',
    'description' => '_MI_UHQRADIO_OPT_SAVELH_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0
);

$modversion['config'][] = array(
    'name'        => 'savesh',
    'title'       => '_MI_UHQRADIO_OPT_SAVESH',
    'description' => '_MI_UHQRADIO_OPT_SAVESH_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0
);

$modversion['config'][] = array(
    'name'        => 'fbapikey',
    'title'       => '_MI_UHQRADIO_OPT_FBAPIKEY',
    'description' => '_MI_UHQRADIO_OPT_FBAPIKEY_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => ''
);

$modversion['config'][] = array(
    'name'        => 'fbsecret',
    'title'       => '_MI_UHQRADIO_OPT_FBSECRET',
    'description' => '_MI_UHQRADIO_OPT_FBSECRET_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => ''
);

$modversion['config'][] = array(
    'name'        => 'pl_category',
    'title'       => '_MI_UHQRADIO_OPT_PLCAT',
    'description' => '_MI_UHQRADIO_OPT_PLCAT_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => ''
);

$modversion['config'][] = array(
    'name'        => 'album_url',
    'title'       => '_MI_UHQRADIO_OPT_ALBUMURL',
    'description' => '_MI_UHQRADIO_OPT_ALBUMURL_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => ''
);

$modversion['config'][] = array(
    'name'        => 'songhist_len',
    'title'       => '_MI_UHQRADIO_OPT_SHISTLEN',
    'description' => '_MI_UHQRADIO_OPT_SHISTLEN_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 10
);

$modversion['config'][] = array(
    'name'        => 'reqlist_len',
    'title'       => '_MI_UHQRADIO_OPT_REQLEN',
    'description' => '_MI_UHQRADIO_OPT_REQLEN_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 10
);

$modversion['config'][] = array(
    'name'        => 'request_array',
    'title'       => '_MI_UHQRADIO_OPT_REQARRAY',
    'description' => '_MI_UHQRADIO_OPT_REQARRAY_DESC',
    'formtype'    => 'group_multi',
    'valuetype'   => 'array',
    'default'     => array(XOOPS_GROUP_ADMIN)
);

// Menu Items

require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/modoptions.php';

$modversion['sub'][1]['name'] = _MI_UHQRADIO_MENU_DJP;
$modversion['sub'][1]['url']  = 'sm-djprofile.php';

if (uhqradio_samint()) {
    $modversion['sub'][2]['name'] = _MI_UHQRADIO_MENU_TRACKLIST;
    $modversion['sub'][2]['url']  = 'sm-tracklist.php';
    $modversion['sub'][3]['name'] = _MI_UHQRADIO_MENU_SONGHIST;
    $modversion['sub'][3]['url']  = 'sm-songhistory.php';
    $modversion['sub'][4]['name'] = _MI_UHQRADIO_MENU_TOPREQ;
    $modversion['sub'][4]['url']  = 'sm-toprequests.php';
}

// ------------------- Templates ------------------- //

//$modversion['templates'] = array(
//    array('file' => $moduleDirName . '_djprofile.tpl', 'description' => _MI_UHQRADIO_TEMPLATE_DJPROFILE),
//    array('file' => 'admin/' . $moduleDirName . '_admin_airstaff.tpl', 'description' => _MI_UHQRADIO_TEMPLATE_ADM_AIRSTAFF),
//    array('file' => 'admin/' . $moduleDirName . '_admin_mountpoints.tpl', 'description' => _MI_UHQRADIO_TEMPLATE_ADM_MOUNTPOINTS),
//    array('file' => 'admin/' . $moduleDirName . '_admin_channels.tpl', 'description' => _MI_UHQRADIO_TEMPLATE_ADM_CHANNELS),
//    array('file' => $moduleDirName . '_xml_status.xml', 'description' => _MI_UHQRADIO_TEMPLATE_XML_STATUS),
//    array('file' => $moduleDirName . '_index.tpl', 'description' => _MI_UHQRADIO_TEMPLATE_INDEX),
//    array('file' => $moduleDirName . '_playlist.tpl', 'description' => _MI_UHQRADIO_TEMPLATE_PLAYLIST),
//    array('file' => 'pop/' . $moduleDirName . '_pop_songinfo.tpl', 'description' => _MI_UHQRADIO_TEMPLATE_POP_SONGINFO),
//    array('file' => 'pop/' . $moduleDirName . '_pop_djtest.tpl', 'description' => _MI_UHQRADIO_TEMPLATE_POP_DJTEST),
//    array('file' => 'pop/' . $moduleDirName . '_pop_request.tpl', 'description' => _MI_UHQRADIO_TEMPLATE_POP_REQUEST),
//    array('file' => $moduleDirName . '_request.tpl', 'description' => _MI_UHQRADIO_TEMPLATE_REQUEST),// NRE Working Template
//    array('file' => $moduleDirName . '_sm_songhistory.tpl', 'description' => _MI_UHQRADIO_TEMPLATE_SM_SONGHISTORY),
//    array('file' => $moduleDirName . '_sm_toprequests.tpl', 'description' => _MI_UHQRADIO_TEMPLATE_SM_TOPREQUESTS),
//    array('file' => 'admin/' . $moduleDirName . '_admin_index.tpl', 'description' => _MI_UHQRADIO_TEMPLATE_ADM_INDEX));

$modversion['templates'] = array(
    array('file' => 'uhqradio_djprofile.tpl', 'description' => _MI_UHQRADIO_TEMPLATE_DJPROFILE),
    array('file' => 'admin/' . 'uhqradio_admin_airstaff.tpl', 'description' => _MI_UHQRADIO_TEMPLATE_ADM_AIRSTAFF),
    array(
        'file'        => 'admin/' . 'uhqradio_admin_mountpoints.tpl',
        'description' => _MI_UHQRADIO_TEMPLATE_ADM_MOUNTPOINTS
    ),
    array('file' => 'admin/' . 'uhqradio_admin_channels.tpl', 'description' => _MI_UHQRADIO_TEMPLATE_ADM_CHANNELS),
    array('file' => 'uhqradio_xml_status.xml', 'description' => _MI_UHQRADIO_TEMPLATE_XML_STATUS),
    array('file' => 'uhqradio_index.tpl', 'description' => _MI_UHQRADIO_TEMPLATE_INDEX),
    array('file' => 'uhqradio_playlist.tpl', 'description' => _MI_UHQRADIO_TEMPLATE_PLAYLIST),
    array('file' => 'pop/' . 'uhqradio_pop_songinfo.tpl', 'description' => _MI_UHQRADIO_TEMPLATE_POP_SONGINFO),
    array('file' => 'pop/' . 'uhqradio_pop_djtest.tpl', 'description' => _MI_UHQRADIO_TEMPLATE_POP_DJTEST),
    array('file' => 'pop/' . 'uhqradio_pop_request.tpl', 'description' => _MI_UHQRADIO_TEMPLATE_POP_REQUEST),
    array('file' => 'uhqradio_request.tpl', 'description' => _MI_UHQRADIO_TEMPLATE_REQUEST),// NRE Working Template
    array('file' => 'uhqradio_sm_songhistory.tpl', 'description' => _MI_UHQRADIO_TEMPLATE_SM_SONGHISTORY),
    array('file' => 'uhqradio_sm_toprequests.tpl', 'description' => _MI_UHQRADIO_TEMPLATE_SM_TOPREQUESTS),
    array('file' => 'admin/' . 'uhqradio_admin_index.tpl', 'description' => _MI_UHQRADIO_TEMPLATE_ADM_INDEX)
);

// ------------------- Blocks ------------------- //

// Radio Status Block
// Option 0: Configured Channel ID        // Option 5: Pop-up Height
// Option 1: Use tune-in link?            // Option 6: Show Listeners?
// Option 2: Tune-in URL                // Option 7: Show Errors?
// Option 3: Tune-in Target                // Option 8: Use link graphic?
// Option 4: Pop-up Width                // Option 9: Standard=0 :: AJAX=1
// Option 10: Show album art?

$modversion['blocks'][] = array(
    'file'        => 'uhqradio_blocks.php',
    'name'        => _MI_UHQRADIO_BLOCK_STATUS_NAME,
    'description' => _MI_UHQRADIO_BLOCK_STATUS_DESC,
    'show_func'   => 'b_uhqradio_status_show',
    'edit_func'   => 'b_uhqradio_status_edit',
    'template'    => 'uhqradio_status.tpl',
    //    'can_clone'     => true,
    'options'     => '0|0|http://localhost/tunein.html|_blank|200|300|0|0|0|1|0'
);

// Autoplayer Start/Stop block

$modversion['blocks'][] = array(
    'file'        => 'uhqradio_blocks.php',
    'name'        => _MI_UHQRADIO_BLOCK_CONTROL_NAME,
    'description' => _MI_UHQRADIO_BLOCK_CONTROL_DESC,
    'show_func'   => 'b_uhqradio_control_show',
    'edit_func'   => 'b_uhqradio_control_edit',
    'template'    => 'uhqradio_control.tpl',
    //  'can_clone'   => true,
    'options'     => 'http://autoplayer.url/start|http://autoplayer.url/stop|http://autoplayer.url/skip|stopnow|restart'
);

// Handoff Block
// Option 0: Port to automate handoff requests to
// Option 1: Allow unverified handoffs?  1 = yes, 0 = no.
// Option 2: Event to call to verify handoff is ready.
// Option 3: Event to call to actually send things off.

$modversion['blocks'][] = array(
    'file'        => 'uhqradio_blocks.php',
    'name'        => _MI_UHQRADIO_BLOCK_HANDOFF_NAME,
    'description' => _MI_UHQRADIO_BLOCK_HANDOFF_DESC,
    'show_func'   => 'b_uhqradio_handoff_show',
    'edit_func'   => 'b_uhqradio_handoff_edit',
    'template'    => 'uhqradio_handoff.tpl',
    //    'can_clone'     => true,
    'options'     => '1221|0|/event/test|/event/start'
);

// DJ Panel Block - No Options

$modversion['blocks'][] = array(
    'file'        => 'uhqradio_blocks.php',
    'name'        => _MI_UHQRADIO_BLOCK_DJPANEL_NAME,
    'description' => _MI_UHQRADIO_BLOCK_DJPANEL_DESC,
    'show_func'   => 'b_uhqradio_djpanel_show',
    'edit_func'   => 'b_uhqradio_djpanel_edit',
    'template'    => 'uhqradio_djpanel.tpl',
    //    'can_clone'     => true,
    'options'     => ''
);

// DJ Listing - Options are:
// Option 0: Number of columns to display withß
// Option 1: Relative Font Size in CSS speak.  "medium" is normal sized.

$modversion['blocks'][] = array(
    'file'        => 'uhqradio_blocks.php',
    'name'        => _MI_UHQRADIO_BLOCK_DJLIST_NAME,
    'description' => _MI_UHQRADIO_BLOCK_DJLIST_DESC,
    'show_func'   => 'b_uhqradio_djlist_show',
    'edit_func'   => 'b_uhqradio_djlist_edit',
    'template'    => 'uhqradio_djlist.tpl',
    //    'can_clone'     => true,
    'options'     => '1|medium'
);

// On-Air - Options are:
// Option 0: Channel ID
// Option 1: Show errors?

$modversion['blocks'][] = array(
    'file'        => 'uhqradio_onair.php',
    'name'        => _MI_UHQRADIO_BLOCK_ONAIR_NAME,
    'description' => _MI_UHQRADIO_BLOCK_ONAIR_DESC,
    'show_func'   => 'b_uhqradio_onair_show',
    'edit_func'   => 'b_uhqradio_onair_edit',
    'template'    => 'uhqradio_onair.tpl',
    //    'can_clone'     => true,
    'options'     => '0|0'
);

// Upcoming Tracks - Options are:
// Option 0: Channel ID
// Option 1: Show errors?
// Option 2: Upcoming limit
// Option 3: Show Station IDs?
// Option 4: List Header
// Option 5: List Item Separator
// Option 6: List Trailer

$modversion['blocks'][] = array(
    'file'        => 'uhqradio_upcoming.php',
    'name'        => _MI_UHQRADIO_BLOCK_UPCOMING_NAME,
    'description' => _MI_UHQRADIO_BLOCK_UPCOMING_DESC,
    'show_func'   => 'b_xnewsletter_subscrinfo',
    'edit_func'   => 'b_uhqradio_upcoming_edit',
    'template'    => 'uhqradio_upcoming.tpl',
    //    'can_clone'     => true,
    'options'     => '0|0|2|0||[ | :: | ]'
);

// Listener History - Options are:
// Option 0: Channel ID
// Option 1: Show errors?
// Option 2: Number of intervals,
// Option 3: Output type: List, Graph

$modversion['blocks'][] = array(
    'file'        => 'uhqradio_lhistory.php',
    'name'        => _MI_UHQRADIO_BLOCK_LHIST_NAME,
    'description' => _MI_UHQRADIO_BLOCK_LHIST_DESC,
    'show_func'   => 'b_uhqradio_lhistory_show',
    'edit_func'   => 'b_uhqradio_lhistory_edit',
    'template'    => 'uhqradio_lhistory.tpl',
    //    'can_clone'     => true,
    'options'     => '0|0|15|L|1'
);

// Song History - Options are:
// Option 0: Channel ID
// Option 1: Show errors?
// Option 2: History Length
// Option 3: Extended Form?

$modversion['blocks'][] = array(
    'file'        => 'uhqradio_shistory.php',
    'name'        => _MI_UHQRADIO_BLOCK_SHIST_NAME,
    'description' => _MI_UHQRADIO_BLOCK_SHIST_DESC,
    'show_func'   => 'b_uhqradio_shistory_show',
    'edit_func'   => 'b_uhqradio_shistory_edit',
    'template'    => 'uhqradio_shistory.tpl',
    //    'can_clone'     => true,
    'options'     => '0|0|10'
);
