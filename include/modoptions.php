<?php

/*

This file contains functions which return the state of any module-wide options.

*/

// This function returns true if a submitted password matches the configured update PW.

/**
 * @param null $password
 * @return bool
 */
function uhqradio_updatepw($password = null)
{

    // Load module options

    $moduleHandler     = xoops_getHandler('module');
    $xoopsModule       = $moduleHandler->getByDirname('uhq_radio');
    $configHandler     = xoops_getHandler('config');
    $xoopsModuleConfig = $configHandler->getConfigsByCat(0, $xoopsModule->getVar('mid'));

    // Check password

    if ($xoopsModuleConfig['cache_updatepw'] == $password) {
        return true;
    } else {
        return false;
    }
}

// This function returns true if a submitted password matches the configured update PW.

/**
 * @param null $password
 * @return bool
 */
function uhqradio_statspw($password = null)
{

    // Load module options

    $moduleHandler     = xoops_getHandler('module');
    $xoopsModule       = $moduleHandler->getByDirname('uhq_radio');
    $configHandler     = xoops_getHandler('config');
    $xoopsModuleConfig = $configHandler->getConfigsByCat(0, $xoopsModule->getVar('mid'));

    // Check password

    if ($xoopsModuleConfig['statspw'] == $password) {
        return true;
    } else {
        return false;
    }
}

// This function returns true if SAM Broadcaster integration is enabled.

/**
 * @return bool
 */
function uhqradio_samint()
{
    //    global $configHandler, $xoopsModule;
    // Load module options
    //    $moduleHandler        = xoops_getHandler('module');
    //    $xoopsModule       = $moduleHandler->getByDirname('uhq_radio');
    //    $configHandler    = xoops_getHandler('config');
    //    $xoopsModuleConfig = $configHandler->getConfigsByCat(0, $xoopsModule->getVar('mid'));

    $moduleHandler = xoops_getHandler('module');
    $module        = $moduleHandler->getByDirname('uhq_radio');
    if ($module) {
        $configHandler     = xoops_getHandler('config');
        $xoopsModuleConfig = $configHandler->getConfigsByCat(0, $module->getVar('mid'));

        // Return true if SAM integration is enabled in the configuration.
        if ($xoopsModuleConfig['sambc'] == 1) {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * @return bool
 */
function uhqradio_opt_samint()
{
    return uhqradio_samint();
}

// This function returns true if we're going to save listener history.

/**
 * @return bool
 */
function uhqradio_opt_savelh()
{

    // Load module options
    $moduleHandler     = xoops_getHandler('module');
    $xoopsModule       = $moduleHandler->getByDirname('uhq_radio');
    $configHandler     = xoops_getHandler('config');
    $xoopsModuleConfig = $configHandler->getConfigsByCat(0, $xoopsModule->getVar('mid'));

    // Return true if SAM integration is enabled in the configuration.
    if ($xoopsModuleConfig['savelh'] == 1) {
        return true;
    } else {
        return false;
    }
}

// This function returns true if we're going to save song history.

/**
 * @return bool
 */
function uhqradio_opt_savesh()
{

    // Load module options
    $moduleHandler     = xoops_getHandler('module');
    $xoopsModule       = $moduleHandler->getByDirname('uhq_radio');
    $configHandler     = xoops_getHandler('config');
    $xoopsModuleConfig = $configHandler->getConfigsByCat(0, $xoopsModule->getVar('mid'));

    // Return true if SAM integration is enabled in the configuration.
    if ($xoopsModuleConfig['savesh'] == 1) {
        return true;
    } else {
        return false;
    }
}

// This function returns the Facebook API Key, if defined.  Otherwise, returns false.

/**
 * @return bool
 */
function uhqradio_opt_fbapikey()
{

    // Load module options
    $moduleHandler     = xoops_getHandler('module');
    $xoopsModule       = $moduleHandler->getByDirname('uhq_radio');
    $configHandler     = xoops_getHandler('config');
    $xoopsModuleConfig = $configHandler->getConfigsByCat(0, $xoopsModule->getVar('mid'));

    // Return true if SAM integration is enabled in the configuration.
    if ($xoopsModuleConfig['fbapikey'] != '') {
        return $xoopsModuleConfig['fbapikey'];
    } else {
        return false;
    }
}

// This function returns the Facebook Secret Key, if defined.  Otherwise, returns false.

/**
 * @return bool
 */
function uhqradio_opt_fbsecret()
{

    // Load module options
    $moduleHandler     = xoops_getHandler('module');
    $xoopsModule       = $moduleHandler->getByDirname('uhq_radio');
    $configHandler     = xoops_getHandler('config');
    $xoopsModuleConfig = $configHandler->getConfigsByCat(0, $xoopsModule->getVar('mid'));

    // Return true if SAM integration is enabled in the configuration.
    if ($xoopsModuleConfig['fbsecret'] != '') {
        return $xoopsModuleConfig['fbsecret'];
    } else {
        return false;
    }
}

// This function returns the category for limiting playlist scope.  Returns false if blank.

/**
 * @return bool
 */
function uhqradio_opt_plcat()
{

    // Load module options
    $moduleHandler     = xoops_getHandler('module');
    $xoopsModule       = $moduleHandler->getByDirname('uhq_radio');
    $configHandler     = xoops_getHandler('config');
    $xoopsModuleConfig = $configHandler->getConfigsByCat(0, $xoopsModule->getVar('mid'));

    if ($xoopsModuleConfig['pl_category'] != '') {
        return $xoopsModuleConfig['pl_category'];
    } else {
        return false;
    }
}

// This function returns the base URL for album art.  Returns false if blank

/**
 * @return bool
 */
function uhqradio_opt_albumurl()
{

    // Load module options
    $moduleHandler     = xoops_getHandler('module');
    $xoopsModule       = $moduleHandler->getByDirname('uhq_radio');
    $configHandler     = xoops_getHandler('config');
    $xoopsModuleConfig = $configHandler->getConfigsByCat(0, $xoopsModule->getVar('mid'));

    if ($xoopsModuleConfig['album_url'] != '') {
        return $xoopsModuleConfig['album_url'];
    } else {
        return false;
    }
}

// Return song history length

/**
 * @return bool
 */
function uhqradio_opt_songhistlen()
{

    // Load module options
    $moduleHandler     = xoops_getHandler('module');
    $xoopsModule       = $moduleHandler->getByDirname('uhq_radio');
    $configHandler     = xoops_getHandler('config');
    $xoopsModuleConfig = $configHandler->getConfigsByCat(0, $xoopsModule->getVar('mid'));

    if ($xoopsModuleConfig['songhist_len'] != '') {
        return $xoopsModuleConfig['songhist_len'];
    } else {
        return false;
    }
}

// Return request history length

/**
 * @return bool
 */
function uhqradio_opt_reqlistlen()
{

    // Load module options
    $moduleHandler     = xoops_getHandler('module');
    $xoopsModule       = $moduleHandler->getByDirname('uhq_radio');
    $configHandler     = xoops_getHandler('config');
    $xoopsModuleConfig = $configHandler->getConfigsByCat(0, $xoopsModule->getVar('mid'));

    if ($xoopsModuleConfig['reqlist_len'] != '') {
        return $xoopsModuleConfig['reqlist_len'];
    } else {
        return false;
    }
}

// Return group array for request permission.

/**
 * @return mixed
 */
function uhqradio_opt_reqgroups()
{

    // Load module options
    $moduleHandler     = xoops_getHandler('module');
    $xoopsModule       = $moduleHandler->getByDirname('uhq_radio');
    $configHandler     = xoops_getHandler('config');
    $xoopsModuleConfig = $configHandler->getConfigsByCat(0, $xoopsModule->getVar('mid'));

    return $xoopsModuleConfig['request_array'];
}
