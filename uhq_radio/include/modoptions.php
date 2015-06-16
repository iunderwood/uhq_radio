<?php

/*

This file contains functions which return the state of any module-wide options.

*/

// This function returns true if a submitted password matches the configured update PW.

function uhqradio_updatepw($password=null) {
	
	// Load module options
	
	$modhandler			=& xoops_gethandler('module');
	$xoopsModule		=& $modhandler->getByDirname('uhq_radio');
	$config_handler		=& xoops_gethandler('config');
	$xoopsModuleConfig	=& $config_handler->getConfigsByCat(0,$xoopsModule->getVar('mid'));
	
	// Check password
	
	if ($xoopsModuleConfig['cache_updatepw'] == $password) {
		return true;
	} else {
		return false;
	}
}

// This function returns true if a submitted password matches the configured update PW.

function uhqradio_statspw($password=null) {
	
	// Load module options
	
	$modhandler			=& xoops_gethandler('module');
	$xoopsModule		=& $modhandler->getByDirname('uhq_radio');
	$config_handler		=& xoops_gethandler('config');
	$xoopsModuleConfig	=& $config_handler->getConfigsByCat(0,$xoopsModule->getVar('mid'));
	
	// Check password
	
	if ($xoopsModuleConfig['statspw'] == $password) {
		return true;
	} else {
		return false;
	}
}

// This function returns true if SAM Broadcaster integration is enabled.

function uhqradio_samint() {
	
	// Load module options
	$modhandler			=& xoops_gethandler('module');
	$xoopsModule		=& $modhandler->getByDirname('uhq_radio');
	$config_handler		=& xoops_gethandler('config');
	$xoopsModuleConfig	=& $config_handler->getConfigsByCat(0,$xoopsModule->getVar('mid'));
	
	// Return true if SAM integration is enabled in the configuration.
	if ($xoopsModuleConfig['sambc'] == 1) {
		return true;
	} else {
		return false;
	}
}

function uhqradio_opt_samint() {
	return uhqradio_samint();
}

// This function returns true if we're going to save listener history.

function uhqradio_opt_savelh() {
	
	// Load module options
	$modhandler			=& xoops_gethandler('module');
	$xoopsModule		=& $modhandler->getByDirname('uhq_radio');
	$config_handler		=& xoops_gethandler('config');
	$xoopsModuleConfig	=& $config_handler->getConfigsByCat(0,$xoopsModule->getVar('mid'));
	
	// Return true if SAM integration is enabled in the configuration.
	if ($xoopsModuleConfig['savelh'] == 1) {
		return true;
	} else {
		return false;
	}
	
}

// This function returns true if we're going to save song history.

function uhqradio_opt_savesh() {
	
	// Load module options
	$modhandler			=& xoops_gethandler('module');
	$xoopsModule		=& $modhandler->getByDirname('uhq_radio');
	$config_handler		=& xoops_gethandler('config');
	$xoopsModuleConfig	=& $config_handler->getConfigsByCat(0,$xoopsModule->getVar('mid'));
	
	// Return true if SAM integration is enabled in the configuration.
	if ($xoopsModuleConfig['savesh'] == 1) {
		return true;
	} else {
		return false;
	}
}

// This function returns the Facebook API Key, if defined.  Otherwise, returns false.

function uhqradio_opt_fbapikey() {

	// Load module options
	$modhandler			=& xoops_gethandler('module');
	$xoopsModule		=& $modhandler->getByDirname('uhq_radio');
	$config_handler		=& xoops_gethandler('config');
	$xoopsModuleConfig	=& $config_handler->getConfigsByCat(0,$xoopsModule->getVar('mid'));
	
	// Return true if SAM integration is enabled in the configuration.
	if ($xoopsModuleConfig['fbapikey'] != '') {
		return $xoopsModuleConfig['fbapikey'];
	} else {
		return false;
	}
}

// This function returns the Facebook Secret Key, if defined.  Otherwise, returns false.

function uhqradio_opt_fbsecret() {
	
	// Load module options
	$modhandler			=& xoops_gethandler('module');
	$xoopsModule		=& $modhandler->getByDirname('uhq_radio');
	$config_handler		=& xoops_gethandler('config');
	$xoopsModuleConfig	=& $config_handler->getConfigsByCat(0,$xoopsModule->getVar('mid'));
	
	// Return true if SAM integration is enabled in the configuration.
	if ($xoopsModuleConfig['fbsecret'] != '') {
		return $xoopsModuleConfig['fbsecret'];
	} else {
		return false;
	}
}

// This function returns the category for limiting playlist scope.  Returns false if blank.

function uhqradio_opt_plcat() {
	
	// Load module options
	$modhandler			=& xoops_gethandler('module');
	$xoopsModule		=& $modhandler->getByDirname('uhq_radio');
	$config_handler		=& xoops_gethandler('config');
	$xoopsModuleConfig	=& $config_handler->getConfigsByCat(0,$xoopsModule->getVar('mid'));
	
	if ($xoopsModuleConfig['pl_category'] != '') {
		return $xoopsModuleConfig['pl_category'];
	} else {
		return false;
	}
}

// This function returns the base URL for album art.  Returns false if blank

function uhqradio_opt_albumurl() {
	
	// Load module options
	$modhandler			=& xoops_gethandler('module');
	$xoopsModule		=& $modhandler->getByDirname('uhq_radio');
	$config_handler		=& xoops_gethandler('config');
	$xoopsModuleConfig	=& $config_handler->getConfigsByCat(0,$xoopsModule->getVar('mid'));
	
	if ($xoopsModuleConfig['album_url'] != '') {
		return $xoopsModuleConfig['album_url'];
	} else {
		return false;
	}
}

// Return song history length

function uhqradio_opt_songhistlen() {
	
	// Load module options
	$modhandler			=& xoops_gethandler('module');
	$xoopsModule		=& $modhandler->getByDirname('uhq_radio');
	$config_handler		=& xoops_gethandler('config');
	$xoopsModuleConfig	=& $config_handler->getConfigsByCat(0,$xoopsModule->getVar('mid'));
	
	if ($xoopsModuleConfig['songhist_len'] != '') {
		return $xoopsModuleConfig['songhist_len'];
	} else {
		return false;
	}
}

// Return request history length

function uhqradio_opt_reqlistlen() {
	
	// Load module options
	$modhandler			=& xoops_gethandler('module');
	$xoopsModule		=& $modhandler->getByDirname('uhq_radio');
	$config_handler		=& xoops_gethandler('config');
	$xoopsModuleConfig	=& $config_handler->getConfigsByCat(0,$xoopsModule->getVar('mid'));
	
	if ($xoopsModuleConfig['reqlist_len'] != '') {
		return $xoopsModuleConfig['reqlist_len'];
	} else {
		return false;
	}
}

// Return group array for request permission.

function uhqradio_opt_reqgroups() {
	
	// Load module options
	$modhandler			=& xoops_gethandler('module');
	$xoopsModule		=& $modhandler->getByDirname('uhq_radio');
	$config_handler		=& xoops_gethandler('config');
	$xoopsModuleConfig	=& $config_handler->getConfigsByCat(0,$xoopsModule->getVar('mid'));
	
	return $xoopsModuleConfig['request_array'];
}

?>