<?php

use Xmf\Module\Admin;
use XoopsModules\Uhqradio\{
    Helper,
    Utility
};
/** @var Admin $adminObject */
/** @var Helper $helper */

require_once __DIR__ . '/admin_header.php';
include XOOPS_ROOT_PATH . '/include/cp_header.php';
include __DIR__ . '/header.php';

xoops_cp_header();
$adminObject->displayNavigation(basename(__FILE__));

echo _AM_UHQRADIO_NOTHINGTOSET;

xoops_cp_footer();
