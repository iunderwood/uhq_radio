<?php

/**
 * @param XoopsModule $module
 * @param null        $oldversion
 * @return bool
 */
function xoops_module_update_uhq_radio(XoopsModule $module, $oldversion = null)
{
    global $xoopsDB;

    if ($oldversion < 4) {
        // Set anonymous access to the module

        $sql = 'INSERT IGNORE INTO ' . $xoopsDB->prefix('group_permission') . " VALUES (NULL, '" . XOOPS_GROUP_ANONYMOUS . '\', \'' . $module->getVar('mid') . '\', 1, \'module_read\')';
        if ($xoopsDB->queryF($sql)) {
            echo _MI_UHQRADIO_INSTALL_ANON_OK;
        } else {
            echo _MI_UHQRADIO_INSTALL_ANON_NOK;
        }
        $oldversion = 4;
    }

    if ($oldversion < 5) {
        $query  = 'CREATE TABLE ' . $xoopsDB->prefix('uhqradio_handoffs') . ' (
                reqtime        DATETIME,
                requser        INT            UNSIGNED NOT NULL,
                reqip        CHAR(50)    NOT NULL,
                reqstat        INT            UNSIGNED NOT NULL,
                PRIMARY KEY (reqtime)
            ) ENGINE=MyISAM;';
        $result = $xoopsDB->queryF($query);
        if (!$result) {
            $module->setErrors('Error adding DB table uhqradio_handoffs');

            return false;
        } else {
            $oldversion = 5;
        }
    }

    if ($oldversion < 6) {

        // Set Main Menu to visibility of 1

        $sql = 'UPDATE ' . $xoopsDB->prefix('modules') . " SET weight = 1 WHERE dirname = 'uhq_radio'";
        if ($xoopsDB->queryF($sql)) {
            echo _MI_UHQRADIO_INSTALL_WEIGHT_OK;
        } else {
            echo _MI_UHQRADIO_INSTALL_WEIGHT_NOK;
        }

        // Grant registered users access to the module

        $sql = 'INSERT IGNORE INTO ' . $xoopsDB->prefix('group_permission') . " VALUES (NULL, '" . XOOPS_GROUP_USERS . '\', \'' . $module->getVar('mid') . '\', 1, \'module_read\')';
        if ($xoopsDB->queryF($sql)) {
            echo _MI_UHQRADIO_INSTALL_USERS_OK;
        } else {
            echo _MI_UHQRADIO_INSTALL_USERS_NOK;
        }

        // Add our airstaff table!

        $query  = 'CREATE TABLE ' . $xoopsDB->prefix('uhqradio_airstaff') . ' (
                djid        CHAR(5)            NOT NULL,
                userkey        MEDIUMINT(8)    UNSIGNED NOT NULL,
                urlpic        CHAR(120)        NOT NULL,
                urlsite        CHAR(120)        NOT NULL,
                flag_req    INT                UNSIGNED NOT NULL,
                blurb        VARCHAR(1024),
                play_mu        VARCHAR(120),
                play_ga        VARCHAR(120),
                PRIMARY KEY (djid)
            ) ENGINE=MyISAM;';
        $result = $xoopsDB->queryF($query);
        if (false === $result) {
            $module->setErrors('Error adding DB table uhqradio_airstaff');

            return false;
        } else {
            echo _MI_UHQRADIO_UPDATE_AIRSTAFF;
        }

        $oldversion = 6;
    }

    if ($oldversion < 7) {

        // Add our mountpoint table!

        $query  = 'CREATE TABLE ' . $xoopsDB->prefix('uhqradio_mountpoints') . ' (
                mpid        INT            UNSIGNED NOT NULL AUTO_INCREMENT,
                server        CHAR(50)    NOT NULL,
                port        INT            UNSIGNED NOT NULL,
                type        CHAR(1)        NOT NULL,
                mount        CHAR(20),
                auth_un        CHAR(20)    NOT NULL,
                auth_pw        CHAR(20)    NOT NULL,
                codec        CHAR(1)        NOT NULL,
                bitrate        INT            UNSIGNED NOT NULL,
                fallback    CHAR(20),
                listeners    INT            UNSIGNED NOT NULL,
                variance    INT            NOT NULL,
                flag_text    INT,
                flag_count    INT,
                PRIMARY KEY (mpid)
            ) ENGINE=MyISAM;';
        $result = $xoopsDB->queryF($query);
        if (false === $result) {
            $module->setErrors('Error adding DB table uhqradio_mountpoints');

            return false;
        } else {
            echo _MI_UHQRADIO_UPDATE_MOUNTPOINTS;
        }

        // Add our channel table!

        $query  = 'CREATE TABLE ' . $xoopsDB->prefix('uhqradio_channels') . ' (
                chid        INT            UNSIGNED NOT NULL AUTO_INCREMENT,
                chan_name    CHAR(80),
                chan_tag    VARCHAR(80),
                chan_web    VARCHAR(80),
                chan_descr    VARCHAR(512),
                text_mpid    INT            UNSIGNED NOT NULL,
                flag_djid    INT            UNSIGNED NOT NULL,
                flag_d_sol    INT            UNSIGNED NOT NULL,
                flag_d_eol    INT            UNSIGNED NOT NULL,
                delim_dj_s    CHAR(5),
                delim_dj_e    CHAR(5),
                flag_show    INT            UNSIGNED NOT NULL,
                flag_s_sol    INT            UNSIGNED NOT NULL,
                flag_s_eol    INT            UNSIGNED NOT NULL,
                delim_sh_s    CHAR(5),
                delim_sh_e    CHAR(5),
                PRIMARY KEY (chid)
            ) ENGINE=MyISAM;';
        $result = $xoopsDB->queryF($query);
        if (false === $result) {
            $module->setErrors('Error adding DB table uhqradio_channels');

            return false;
        } else {
            echo _MI_UHQRADIO_UPDATE_CHANNELS;
        }

        // Add our counter map!

        $query  = 'CREATE TABLE ' . $xoopsDB->prefix('uhqradio_countmap') . ' (
                chid        INT            UNSIGNED NOT NULL,
                mpid        INT            UNSIGNED NOT NULL,
                PRIMARY KEY (chid,mpid)
            ) ENGINE=MyISAM;';
        $result = $xoopsDB->queryF($query);
        if (false === $result) {
            $module->setErrors('Error adding DB table uhqradio_countmap');

            return false;
        } else {
            echo _MI_UHQRADIO_UPDATE_COUNTMAP;
        }

        // Reset all status blocks to default.

        $query = 'UPDATE ' . $xoopsDB->prefix('newblocks');
        $query .= " SET options = '0|0|http://localhost/tunein.html|_blank|200|300|0|0|0'";
        $query .= " WHERE dirname = 'uhq_radio' AND show_func = 'b_uhqradio_status_show'";

        $oldversion = 7;
    }

    if ($oldversion < 9) {

        // Add fields to DB

        $query  = 'ALTER TABLE ' . $xoopsDB->prefix('uhqradio_airstaff');
        $query  .= ' ADD rdb_port INT UNSIGNED NOT NULL AFTER play_ga';
        $result = $xoopsDB->queryF($query);
        if (!$result) {
            $module->setErrors('Error adding rdb_port field.');

            return false;
        }

        $query  = 'ALTER TABLE ' . $xoopsDB->prefix('uhqradio_airstaff');
        $query  .= ' ADD rdb_user CHAR(20) AFTER rdb_port';
        $result = $xoopsDB->queryF($query);
        if (!$result) {
            $module->setErrors('Error adding rdb_user field.');

            return false;
        }

        $query  = 'ALTER TABLE ' . $xoopsDB->prefix('uhqradio_airstaff');
        $query  .= ' ADD rdb_pass CHAR(20) AFTER rdb_user';
        $result = $xoopsDB->queryF($query);
        if (!$result) {
            $module->setErrors('Error adding rdb_pass field.');

            return false;
        }

        $query  = 'ALTER TABLE ' . $xoopsDB->prefix('uhqradio_airstaff');
        $query  .= ' ADD rdb_name CHAR(20) AFTER rdb_pass';
        $result = $xoopsDB->queryF($query);
        if (!$result) {
            $module->setErrors('Error adding rdb_name field.');

            return false;
        }

        $query  = 'ALTER TABLE ' . $xoopsDB->prefix('uhqradio_airstaff');
        $query  .= ' ADD sam_port INT UNSIGNED NOT NULL AFTER rdb_name';
        $result = $xoopsDB->queryF($query);
        if (!$result) {
            $module->setErrors('Error adding sam_port field.');

            return false;
        }

        $oldversion = 9;
    }

    if ($oldversion < 10) {

        // Add Listener History tables

        $query = 'CREATE TABLE ' . $xoopsDB->prefix('uhqradio_lhistory') . ' (
            mpid        INT UNSIGNED NOT NULL,
            stamp        DATETIME,
            stamp_a     DATETIME,
            status        CHAR(1),
            pop            INT UNSIGNED NOT NULL,
            PRIMARY KEY (mpid,stamp)
            ) ENGINE=MyISAM;';

        $result = $xoopsDB->queryF($query);
        if (false === $result) {
            $module->setErrors('Error adding DB table uhqradio_lhistory');

            return false;
        } else {
            echo _MI_UHQRADIO_UPDATE_LHISTORY;
        }

        // Add Song History tables

        $query = 'CREATE TABLE ' . $xoopsDB->prefix('uhqradio_shistory') . ' (
            chid        INT UNSIGNED NOT NULL,
            stamp        DATETIME,
            djid        CHAR(5) NOT NULL,
            showname    VARCHAR(128),
            artist        VARCHAR(255),
            track        VARCHAR(255),
            songtype    CHAR(1),
            album        VARCHAR(255),
            albumyear    VARCHAR(4),
            label        VARCHAR(100),
            picture        VARCHAR(255),
            requestID    INT,
            viewers        INT NOT NULL,
            PRIMARY KEY (chid,stamp)
            ) ENGINE=MyISAM;';

        $result = $xoopsDB->queryF($query);
        if (false === $result) {
            $module->setErrors('Error adding DB table uhqradio_shistory');

            return false;
        } else {
            echo _MI_UHQRADIO_UPDATE_SHISTORY;
        }

        // Add spot for LoudCity updates.

        $query  = 'ALTER TABLE ' . $xoopsDB->prefix('uhqradio_channels');
        $query  .= ' ADD lc_guid VARCHAR(40) AFTER delim_sh_e';
        $result = $xoopsDB->queryF($query);
        if (!$result) {
            $module->setErrors('Error adding lc_guid field.');

            return false;
        }

        $oldversion = 10;
    }

    if ($oldversion < 12) {
        // Remove unused template file.
        unlink(XOOPS_ROOT_PATH . '/modules/uhq_radio/templates/uhqradio_ecu.tpl');

        $oldversion = 12;
    }

    if ($oldversion < 13) {
        // Remove depreciated PHP files, if we can.
        unlink(XOOPS_ROOT_PATH . '/modules/uhq_radio/playlist.php');
        unlink(XOOPS_ROOT_PATH . '/modules/uhq_radio/djprofile.php');

        // Add requester name to the song history table.

        $query  = 'ALTER TABLE ' . $xoopsDB->prefix('uhqradio_shistory');
        $query  .= ' ADD requestor VARCHAR(32) AFTER requestID';
        $result = $xoopsDB->queryF($query);
        if (!$result) {
            $module->setErrors('Error adding requestor field.');

            return false;
        }

        $oldversion = 13;
    }

    if ($oldversion < 14) {
        $query  = 'ALTER TABLE ' . $xoopsDB->prefix('uhqradio_channels');
        $query  .= ' ADD tunein_pid CHAR(10) AFTER lc_guid';
        $result = $xoopsDB->queryF($query);
        if (!$result) {
            $module->setErrors('Error adding tunein_pid field.');

            return false;
        }

        $query  = 'ALTER TABLE ' . $xoopsDB->prefix('uhqradio_channels');
        $query  .= ' ADD tunein_pkey CHAR(15) AFTER tunein_pid';
        $result = $xoopsDB->queryF($query);
        if (!$result) {
            $module->setErrors('Error adding tunein_pkey field.');

            return false;
        }

        $query  = 'ALTER TABLE ' . $xoopsDB->prefix('uhqradio_channels');
        $query  .= ' ADD tunein_sid CHAR(10) AFTER tunein_pkey';
        $result = $xoopsDB->queryF($query);
        if (!$result) {
            $module->setErrors('Error adding tunein_sid field.');

            return false;
        }

        $oldversion = 14;
    }

    // Return true if we get this far.

    return true;
}
