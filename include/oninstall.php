<?php

use XoopsModules\Uhqradio\{
    Helper,
    Utility
};
/** @var Admin $adminObject */
/** @var Helper $helper */

/**
 * @param XoopsModule $module
 * @return bool
 */
function xoops_module_install_uhq_radio(XoopsModule $module)
{
    global $xoopsDB;

    // Set anonymous access to the module

    $sql = 'INSERT IGNORE INTO ' . $xoopsDB->prefix('group_permission') . " VALUES (NULL, '" . XOOPS_GROUP_ANONYMOUS . '\', \'' . $module->getVar('mid') . '\', 1, \'module_read\')';
    if ($xoopsDB->queryF($sql)) {
        echo _MI_UHQRADIO_INSTALL_ANON_OK;
    } else {
        echo _MI_UHQRADIO_INSTALL_ANON_NOK;
    }

    // Set registered access to the module

    $sql = 'INSERT IGNORE INTO ' . $xoopsDB->prefix('group_permission') . " VALUES (NULL, '" . XOOPS_GROUP_USERS . '\', \'' . $module->getVar('mid') . '\', 1, \'module_read\')';
    if ($xoopsDB->queryF($sql)) {
        echo _MI_UHQRADIO_INSTALL_USERS_OK;
    } else {
        echo _MI_UHQRADIO_INSTALL_USERS_NOK;
    }

    // Set Main Menu to visibility of 1

    $sql = 'UPDATE ' . $xoopsDB->prefix('modules') . " SET weight = 1 WHERE dirname = 'uhq_radio'";
    if ($xoopsDB->queryF($sql)) {
        echo _MI_UHQRADIO_INSTALL_WEIGHT_OK;
    } else {
        echo _MI_UHQRADIO_INSTALL_WEIGHT_NOK;
    }

    return true;
}
