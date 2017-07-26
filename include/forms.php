<?php

/*
  This file stores form functions, and supporing SQL inserts/updates based on the form information.  This way, things don't get overlooked so easily.
*/

require_once XOOPS_ROOT_PATH . '/modules/uhq_radio/include/modoptions.php';

// Load up the form language if it hasn't already been done.

if (file_exists(XOOPS_ROOT_PATH . '/modules/uhq_radio/language/' . $xoopsConfig['language'] . '/form.php')) {
    require_once XOOPS_ROOT_PATH . '/modules/uhq_radio/language/' . $xoopsConfig['language'] . '/form.php';
} else {
    require_once XOOPS_ROOT_PATH . '/modules/uhq_radio/language/english/form.php';
}

require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
require_once XOOPS_ROOT_PATH . '/class/uploader.php';

/*
    Airstaff Functions to handle add/edit/delete forms and queries.
*/

// Airstaff Add/Edit form

/**
 * @param      $title
 * @param      $target
 * @param null $formdata
 * @param null $op
 */
function uhqradio_airform($title, $target, $formdata = null, $op = null)
{

    // Draw Form
    $form = new XoopsThemeForm($title, 'airform', $target, 'post', true);

    if ($op === 'edit') {
        // DJ Identifiers can't be changed once created.
        $form->addElement(new XoopsFormHidden('djid', $formdata['djid']));
        $form->addElement(new XoopsFormHidden('op', $op));
    } else {
        $form->addElement(new XoopsFormText(_FM_UHQRADIO_DJID, 'djid', 5, 5, $formdata['djid']), true);
        $form->addElement(new XoopsFormHidden('op', 'insert'));
    }
    // Can't change users in a self-edit
    if ($target === 'airstaff.php') {
        $form->addElement(new XoopsFormSelectUser(_FM_UHQRADIO_USER, 'userkey', false, $formdata['userkey'], 1, false));
    }
    /*    Changing this to a file handler
        $form->addElement(new XoopsFormText(_FM_UHQRADIO_URLPIC,"urlpic",60,120,$formdata['urlpic']) );
    */
    if ($op === 'edit') {
        $form->addElement(new XoopsFormLabel(_FM_UHQRADIO_PROFILE_CURPIC, '<img src="' . $formdata['urlpic'] . '">'));
        $form->addElement(new XoopsFormFile(_FM_UHQRADIO_PROFILE_EDITPIC, 'djpic', 512000));
        $form->addElement(new XoopsFormHidden('urlpic', $formdata['urlpic']));
    } else {
        $form->addElement(new XoopsFormFile(_FM_UHQRADIO_PROFILE_PIC, 'djpic', 512000));
    }
    $form->setExtra('enctype="multipart/form-data"');

    $form->addElement(new XoopsFormText(_FM_UHQRADIO_URLSITE, 'urlsite', 60, 120, $formdata['urlsite']));

    $form->addElement(new XoopsFormTextArea(_FM_UHQRADIO_PLAYGAME, 'play_ga', $formdata['play_ga'], 2, 60));
    $form->addElement(new XoopsFormTextArea(_FM_UHQRADIO_PLAYMUSIC, 'play_mu', $formdata['play_mu'], 2, 60));
    $form->addElement(new XoopsFormDhtmlTextArea(_FM_UHQRADIO_BLURB, 'blurb', $formdata['blurb'], 10, 60));

    $form_c = new XoopsFormRadio(_FM_UHQRADIO_REQUESTS, 'flag_req', $formdata['flag_req']);
    $form_c->addOption(1, _FM_UHQRADIO_REQ_OK);
    $form_c->addOption(0, _FM_UHQRADIO_REQ_NOK);

    $form->addElement($form_c);

    // Enable SAM Broadcaster fields if enabled.
    if (uhqradio_samint()) {
        $form->insertBreak(_FM_UHQRADIO_SAMDB_HDR);
        $form->addElement(new XoopsFormText(_FM_UHQRADIO_SAMDB_SQLP, 'rdb_port', 5, 5, $formdata['rdb_port']));
        $form->addElement(new XoopsFormText(_FM_UHQRADIO_SAMDB_SQLDB, 'rdb_name', 20, 20, $formdata['rdb_name']));
        $form->addElement(new XoopsFormText(_FM_UHQRADIO_SAMDB_SQLUN, 'rdb_user', 20, 20, $formdata['rdb_user']));
        $form->addElement(new XoopsFormText(_FM_UHQRADIO_SAMDB_SQLPW, 'rdb_pass', 20, 20, $formdata['rdb_pass']));
        $form->addElement(new XoopsFormText(_FM_UHQRADIO_SAMDB_HTTP, 'sam_port', 5, 5, $formdata['sam_port']));
    }

    $form->addElement(new XoopsFormButton('', 'post', $title, 'submit'));

    $form->addElement(new XoopsFormHidden('verify', '1'));

    $form->display();
}

// Airstaff Delete Form

/**
 * @param $formdata
 */
function uhqradio_del_airform($formdata)
{
    $title = _FM_UHQRADIO_DELETE . ' ' . $formdata['djid'];

    // Draw Form
    $form = new XoopsThemeForm($title, 'del_airform', 'airstaff.php', 'post', true);

    $form->addElement(new XoopsFormHidden('op', 'delete'));
    $form->addElement(new XoopsFormHidden('verify', '1'));
    $form->addElement(new XoopsFormHidden('djid', $formdata['djid']));

    $form->addElement(new XoopsFormButton(_FM_UHQRADIO_DEL_AYS, 'post', $title, 'submit'));
    $form->display();
}

// Generate query for new entries.

/**
 * @param $sane_REQUEST
 * @return string
 */
function uhqradio_airform_insertquery($sane_REQUEST)
{
    global $xoopsDB;

    // If the upload is good, save the file and save link info.
    $allowed_mimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png');
    $uploader          = new XoopsMediaUploader(XOOPS_ROOT_PATH . '/modules/uhq_radio/images/profile', $allowed_mimetypes, 512000);
    if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
        $uploader->setPrefix('djpic-' . $sane_REQUEST['djid'] . '-');
        if ($uploader->upload()) {
            // Set Variable
            $urlpic = '/modules/uhq_radio/images/profile/' . $uploader->getSavedFileName();
        } else {
            $urlpic = null;
        }
    } else {
        $urlpic = null;
    }

    // Insert Data

    $query = 'INSERT INTO ' . $xoopsDB->prefix('uhqradio_airstaff');
    $query .= " SET djid = '" . $sane_REQUEST['djid'] . '\', ';
    $query .= "userkey = '" . $sane_REQUEST['userkey'] . '\', ';
    $query .= "urlpic = '" . $urlpic . '\', ';
    $query .= "urlsite = '" . $sane_REQUEST['urlsite'] . '\', ';
    $query .= "flag_req = '" . $sane_REQUEST['flag_req'] . '\', ';
    $query .= "play_ga = '" . $sane_REQUEST['play_ga'] . '\', ';
    $query .= "play_mu = '" . $sane_REQUEST['play_mu'] . '\', ';
    $query .= "blurb = '" . $sane_REQUEST['blurb'] . '\', ';
    $query .= "rdb_port = '" . $sane_REQUEST['rdb_port'] . '\', ';
    $query .= "rdb_user = '" . $sane_REQUEST['rdb_user'] . '\', ';
    $query .= "rdb_pass = '" . $sane_REQUEST['rdb_pass'] . '\', ';
    $query .= "rdb_name = '" . $sane_REQUEST['rdb_name'] . '\', ';
    $query .= "sam_port = '" . $sane_REQUEST['sam_port'] . '\'';

    return $query;
}

// Generate query for updates to form data.

/**
 * @param $sane_REQUEST
 * @return string
 */
function uhqradio_airform_updatequery($sane_REQUEST)
{
    global $xoopsDB;

    $urlpic = null;

    // If the upload is good, save the file and save link info.
    $allowed_mimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png');
    $uploader          = new XoopsMediaUploader(XOOPS_ROOT_PATH . '/modules/uhq_radio/images/profile', $allowed_mimetypes, 512000);
    if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
        $uploader->setPrefix('djpic-' . $sane_REQUEST['djid'] . '-');
        if ($uploader->upload()) {
            // Set Variable
            $urlpic = '/modules/uhq_radio/images/profile/' . $uploader->getSavedFileName();
            // Delete previous file, if it exists.
            if (file_exists(XOOPS_ROOT_PATH . $sane_REQUEST['urlpic']) && ($sane_REQUEST['urlpic'] != null)) {
                $err = unlink(XOOPS_ROOT_PATH . $sane_REQUEST['urlpic']);
                if ($err) {
                    echo "Can't delete " . XOOPS_ROOT_PATH > $sane_REQUEST['urlpic'] . ' (' . $err . ')';
                }
            }
        } else {
            $urlpic = $sane_REQUEST['urlpic'];
        }
    } else {
        $urlpic = $sane_REQUEST['urlpic'];
    }
    // Insert Data

    $query = 'UPDATE ' . $xoopsDB->prefix('uhqradio_airstaff');
    $query .= " SET urlpic = '" . $urlpic . '\', ';
    $query .= "urlsite = '" . $sane_REQUEST['urlsite'] . '\', ';
    $query .= "flag_req = '" . $sane_REQUEST['flag_req'] . '\', ';
    $query .= "play_ga = '" . $sane_REQUEST['play_ga'] . '\', ';
    $query .= "play_mu = '" . $sane_REQUEST['play_mu'] . '\', ';
    $query .= "blurb = '" . $sane_REQUEST['blurb'] . '\', ';
    $query .= "rdb_port = '" . $sane_REQUEST['rdb_port'] . '\', ';
    $query .= "rdb_user = '" . $sane_REQUEST['rdb_user'] . '\', ';
    $query .= "rdb_pass = '" . $sane_REQUEST['rdb_pass'] . '\', ';
    $query .= "rdb_name = '" . $sane_REQUEST['rdb_name'] . '\', ';
    $query .= "sam_port = '" . $sane_REQUEST['sam_port'] . '\'';
    $query .= "WHERE djid = '" . $sane_REQUEST['djid'] . '\'';

    return $query;
}

/*
    Mountpoint Functions to handle add/edit/delete forms and queries.
*/

// Add/Edit Mountpoint Form

/**
 * @param      $title
 * @param      $target
 * @param null $formdata
 * @param null $op
 */
function uhqradio_mountform($title, $target, $formdata = null, $op = null)
{
    $form = new XoopsThemeForm($title, 'mountform', $target, 'post', true);

    // Set some defaults if the form is blank.
    if ($formdata == null) {
        $formdata['type']       = 'I';
        $formdata['codec']      = 'O';
        $formdata['variance']   = 0;
        $formdata['flag_text']  = 1;
        $formdata['flag_count'] = 1;
    }

    if ($op === 'edit') {
        $form->addElement(new XoopsFormHidden('mpid', $formdata['mpid']));
        $form->addElement(new XoopsFormHidden('op', 'edit'));
    } else {
        $form->addElement(new XoopsFormHidden('op', 'insert'));
    }

    // Start the form!

    $form->addElement(new XoopsFormText(_FM_UHQRADIO_SVRFQDN, 'server', 60, 120, $formdata['server']));
    $form->addElement(new XoopsFormText(_FM_UHQRADIO_SVRPORT, 'port', 5, 5, $formdata['port']));

    // Server Type
    $form_type = new XoopsFormRadio(_FM_UHQRADIO_SVRTYPE, 'type', $formdata['type']);
    $form_type->addOption('I', _FM_UHQRADIO_STYPE_I);
    $form_type->addOption('P', _FM_UHQRADIO_STYPE_P);
    $form_type->addOption('S', _FM_UHQRADIO_STYPE_S);
    $form->addElement($form_type);

    // IceCast Options
    $form->addElement(new XoopsFormText(_FM_UHQRADIO_SVRMOUNT, 'mount', 20, 20, $formdata['mount']));
    $form->addElement(new XoopsFormText(_FM_UHQRADIO_SVRFALLBACK, 'fallback', 20, 20, $formdata['fallback']));

    // Authentication Options
    $form->addElement(new XoopsFormText(_FM_UHQRADIO_SVRAUN, 'auth_un', 20, 20, $formdata['auth_un'], true));
    $form->addElement(new XoopsFormText(_FM_UHQRADIO_SVRAPW, 'auth_pw', 20, 20, $formdata['auth_pw'], true));

    // Codec & Bitrate

    $form_codec = new XoopsFormRadio(_FM_UHQRADIO_SVRCODE, 'codec', $formdata['codec']);
    $form_codec->addOption('A', _FM_UHQRADIO_CODEC_A);
    $form_codec->addOption('M', _FM_UHQRADIO_CODEC_M);
    $form_codec->addOption('O', _FM_UHQRADIO_CODEC_O);
    $form->addElement($form_codec);

    $form->addElement(new XoopsFormText(_FM_UHQRADIO_SVRBR, 'bitrate', 3, 3, $formdata['bitrate']));
    $form->addElement(new XoopsFormText(_FM_UHQRADIO_SVRMAX, 'listeners', 4, 4, $formdata['listeners']));

    // Listener Variance

    $form->addElement(new XoopsFormText(_FM_UHQRADIO_SVRVAR, 'variance', 2, 2, $formdata['variance']));

    // Ok for Text

    $form_text = new XoopsFormRadio(_FM_UHQRADIO_SVRTXT, 'flag_text', $formdata['flag_text']);
    $form_text->addOption(0, _FM_UHQRADIO_TEXT_NOK);
    $form_text->addOption(1, _FM_UHQRADIO_TEXT_OK);
    $form->addElement($form_text);

    $form_count = new XoopsFormRadio(_FM_UHQRADIO_SVRCOUNT, 'flag_count', $formdata['flag_count']);
    $form_count->addOption(0, _FM_UHQRADIO_COUNT_NOK);
    $form_count->addOption(1, _FM_UHQRADIO_COUNT_OK);
    $form->addElement($form_count);

    // Submit & verify

    $form->addElement(new XoopsFormButton('', 'post', $title, 'submit'));

    $form->addElement(new XoopsFormHidden('verify', '1'));

    $form->display();
}

// Delete Mountpoint Form

/**
 * @param $formdata
 */
function uhqradio_mountform_del($formdata)
{
    $title = _FM_UHQRADIO_DELETE . ' #' . $formdata['mpid'] . ': ';
    $title .= $formdata['type'] . '=' . $formdata['server'] . ':' . $formdata['port'] . $formdata['mount'];

    echo _FM_UHQRADIO_MOUNT_DELWARN;

    // Draw Form
    $form = new XoopsThemeForm($title, 'del_mountform', 'mountpoints.php', 'post', true);

    $form_c = new XoopsFormCheckbox(_FM_UHQRADIO_FORM_DELHD, 'delhd', 'none');
    $form_c->addOption(1, _FM_UHQRADIO_FORM_RD);

    $form->addElement($form_c);

    $form->addElement(new XoopsFormHidden('op', 'delete'));
    $form->addElement(new XoopsFormHidden('verify', '1'));
    $form->addElement(new XoopsFormHidden('mpid', $formdata['mpid']));

    $form->addElement(new XoopsFormButton(_FM_UHQRADIO_DEL_AYS, 'post', $title, 'submit'));

    $form->display();
}

// Insert Mount Point Query

/**
 * @param $sane_REQUEST
 * @return string
 */
function uhqradio_mountform_insertquery($sane_REQUEST)
{
    global $xoopsDB;

    $query = 'INSERT INTO ' . $xoopsDB->prefix('uhqradio_mountpoints');
    $query .= " SET server = '" . $sane_REQUEST['server'] . '\', ';
    $query .= " port = '" . $sane_REQUEST['port'] . '\', ';
    $query .= " type = '" . $sane_REQUEST['type'] . '\', ';
    if ($sane_REQUEST['type'] === 'I') {
        $query .= " mount = '" . $sane_REQUEST['mount'] . '\', ';
        $query .= " fallback = '" . $sane_REQUEST['fallback'] . '\', ';
    } else {
        $query .= " mount = '', ";
        $query .= " fallback = '', ";
    }
    $query .= " auth_un = '" . $sane_REQUEST['auth_un'] . '\', ';
    $query .= " auth_pw = '" . $sane_REQUEST['auth_pw'] . '\', ';
    $query .= " codec = '" . $sane_REQUEST['codec'] . '\', ';
    $query .= " listeners = '" . $sane_REQUEST['listeners'] . '\', ';
    $query .= " bitrate = '" . $sane_REQUEST['bitrate'] . '\', ';
    $query .= " variance = '" . $sane_REQUEST['variance'] . '\', ';
    if ($sane_REQUEST['type'] === 'P') {
        $query .= " flag_text = '0', ";
    } else {
        $query .= " flag_text = '" . $sane_REQUEST['flag_text'] . '\', ';
    }
    $query .= " flag_count = '" . $sane_REQUEST['flag_count'] . '\' ';

    return $query;
}

// Update Mount Point Query

/**
 * @param $sane_REQUEST
 * @return string
 */
function uhqradio_mountform_updatequery($sane_REQUEST)
{
    global $xoopsDB;

    $query = 'UPDATE ' . $xoopsDB->prefix('uhqradio_mountpoints');
    $query .= " SET server = '" . $sane_REQUEST['server'] . '\', ';
    $query .= " port = '" . $sane_REQUEST['port'] . '\', ';
    $query .= " type = '" . $sane_REQUEST['type'] . '\', ';
    if ($sane_REQUEST['type'] === 'I') {
        $query .= " mount = '" . $sane_REQUEST['mount'] . '\', ';
        $query .= " fallback = '" . $sane_REQUEST['fallback'] . '\', ';
    } else {
        $query .= " mount = '', ";
        $query .= " fallback = '', ";
    }
    $query .= " auth_un = '" . $sane_REQUEST['auth_un'] . '\', ';
    $query .= " auth_pw = '" . $sane_REQUEST['auth_pw'] . '\', ';
    $query .= " codec = '" . $sane_REQUEST['codec'] . '\', ';
    $query .= " listeners = '" . $sane_REQUEST['listeners'] . '\', ';
    $query .= " bitrate = '" . $sane_REQUEST['bitrate'] . '\', ';
    $query .= " variance = '" . $sane_REQUEST['variance'] . '\', ';
    if ($sane_REQUEST['type'] === 'P') {
        $query .= " flag_text = '0', ";
    } else {
        $query .= " flag_text = '" . $sane_REQUEST['flag_text'] . '\', ';
    }
    $query .= " flag_count = '" . $sane_REQUEST['flag_count'] . '\' ';
    $query .= "WHERE mpid = '" . $sane_REQUEST['mpid'] . '\'';

    return $query;
}

/*
    Channel Functions to handle add/edit/delete forms and queries.
*/

// Add/Edit Channel Form

/**
 * @param      $title
 * @param      $target
 * @param null $formdata
 * @param null $op
 */
function uhqradio_channelform($title, $target, $formdata = null, $op = null)
{
    global $xoopsDB;

    $form = new XoopsThemeForm($title, 'channelform', $target, 'post', true);

    // Set some defaults if the form is blank.

    if ($formdata == null) {
        $formdata['text_mpid']  = 0;
        $formdata['flag_djid']  = 0;
        $formdata['flag_d_sol'] = 0;
        $formdata['flag_d_eol'] = 0;
        $formdata['flag_show']  = 0;
        $formdata['flag_s_sol'] = 0;
        $formdata['flag_s_eol'] = 0;
    }

    // Set the operation to pass on the form.

    if ($op === 'edit') {
        $form->addElement(new XoopsFormHidden('chid', $formdata['chid']));
        $form->addElement(new XoopsFormHidden('op', 'edit'));
    } else {
        $form->addElement(new XoopsFormHidden('op', 'insert'));
    }

    $form->addElement(new XoopsFormText(_FM_UHQRADIO_CHANNEL_NAME, 'chan_name', 60, 80, $formdata['chan_name']));
    $form->addElement(new XoopsFormText(_FM_UHQRADIO_CHANNEL_TAG, 'chan_tag', 60, 80, $formdata['chan_tag']));
    $form->addElement(new XoopsFormText(_FM_UHQRADIO_CHANNEL_WEB, 'chan_web', 60, 80, $formdata['chan_web']));
    $form->addElement(new XoopsFormDhtmlTextArea(_FM_UHQRADIO_CHANNEL_DESC, 'chan_descr', $formdata['chan_descr'], 8, 60));

    // Selection of Text Info for station

    $form_t = new XoopsFormSelect(_FM_UHQRADIO_TEXT_INFO, 'text_mpid', $formdata['text_mpid']);
    $form_t->addOption(0, _FM_UHQRADIO_TEXT_NONE);

    $query  = 'SELECT * FROM ' . $xoopsDB->prefix('uhqradio_mountpoints');
    $query  .= ' WHERE flag_text > 0';
    $result = $xoopsDB->queryF($query);
    if ($result === false) {
        // Do not add options.
    } else {
        // Load option from DB
        while ($row = $xoopsDB->fetchArray($result)) {
            $srvinfo = uhqradio_fullservertype($row['type']) . '=' . $row['server'] . ':' . $row['port'] . $row['mount'];
            $form_t->addOption($row['mpid'], $srvinfo);
        }
    }
    $form->addElement($form_t);

    // How to parse DJ info

    $form->insertBreak(_FM_UHQRADIO_DJINFO_HDR);

    $form_d = new XoopsFormRadio(_FM_UHQRADIO_DJINFO, 'flag_djid', $formdata['flag_djid']);
    $form_d->addOption(0, _FM_UHQRADIO_INFOSRC_0);
    $form_d->addOption(1, _FM_UHQRADIO_INFOSRC_1);
    $form->addElement($form_d);

    $form_ds = new XoopsFormRadio(_FM_UHQRADIO_START, 'flag_d_sol', $formdata['flag_d_sol']);
    $form_ds->addOption(0, _FM_UHQRADIO_SOL);
    $form_ds->addOption(1, _FM_UHQRADIO_DELIM);
    $form->addElement($form_ds);

    $form->addElement(new XoopsFormText(_FM_UHQRADIO_START_TXT, 'delim_dj_s', 5, 5, $formdata['delim_dj_s']));

    $form_de = new XoopsFormRadio(_FM_UHQRADIO_END, 'flag_d_eol', $formdata['flag_d_eol']);
    $form_de->addOption(0, _FM_UHQRADIO_EOL);
    $form_de->addOption(1, _FM_UHQRADIO_DELIM);
    $form->addElement($form_de);

    $form->addElement(new XoopsFormText(_FM_UHQRADIO_END_TXT, 'delim_dj_e', 5, 5, $formdata['delim_dj_e']));

    // How to parse Show info

    $form->insertBreak(_FM_UHQRADIO_SHOWINFO_HDR);

    $form_s = new XoopsFormRadio(_FM_UHQRADIO_SHOWINFO, 'flag_show', $formdata['flag_show']);
    $form_s->addOption(0, _FM_UHQRADIO_INFOSRC_0);
    $form_s->addOption(1, _FM_UHQRADIO_INFOSRC_1);
    $form->addElement($form_s);

    $form_ss = new XoopsFormRadio(_FM_UHQRADIO_START, 'flag_s_sol', $formdata['flag_s_sol']);
    $form_ss->addOption(0, _FM_UHQRADIO_SOL);
    $form_ss->addOption(1, _FM_UHQRADIO_DELIM);
    $form->addElement($form_ss);

    $form->addElement(new XoopsFormText(_FM_UHQRADIO_START_TXT, 'delim_sh_s', 5, 5, $formdata['delim_sh_s']));

    $form_se = new XoopsFormRadio(_FM_UHQRADIO_END, 'flag_s_eol', $formdata['flag_s_eol']);
    $form_se->addOption(0, _FM_UHQRADIO_EOL);
    $form_se->addOption(1, _FM_UHQRADIO_DELIM);
    $form->addElement($form_se);

    $form->addElement(new XoopsFormText(_FM_UHQRADIO_END_TXT, 'delim_sh_e', 5, 5, $formdata['delim_sh_e']));

    $form->insertBreak(_FM_UHQRADIO_LICENSING);

    $form->addElement(new XoopsFormText(_FM_UHQRADIO_LOUDCITY_GUID, 'lc_guid', 40, 40, $formdata['lc_guid']));

    $form->insertBreak(_FM_UHQRADIO_REPORTING);

    $form->addElement(new XoopsFormText(_FM_UHQRADIO_TUNEIN_PID, 'tunein_pid', 8, 8, $formdata['tunein_pid']));
    $form->addElement(new XoopsFormText(_FM_UHQRADIO_TUNEIN_PKEY, 'tunein_pkey', 12, 12, $formdata['tunein_pkey']));
    $form->addElement(new XoopsFormText(_FM_UHQRADIO_TUNEIN_SID, 'tunein_sid', 8, 8, $formdata['tunein_sid']));

    $form->addElement(new XoopsFormButton('', 'post', $title, 'submit'));

    $form->addElement(new XoopsFormHidden('verify', '1'));

    $form->display();
}

// Delete Channel Form

/**
 * @param $formdata
 */
function uhqradio_channelform_del($formdata)
{
    $title = _FM_UHQRADIO_DELETE . ' ' . $formdata['chan_name'] . ': ';

    echo _FM_UHQRADIO_CHANNEL_DELWARN;

    // Draw Form
    $form = new XoopsThemeForm($title, 'del_chanform', 'channels.php', 'post', true);

    $form->addElement(new XoopsFormHidden('op', 'delete'));
    $form->addElement(new XoopsFormHidden('verify', '1'));
    $form->addElement(new XoopsFormHidden('chid', $formdata['chid']));

    $form->addElement(new XoopsFormButton(_FM_UHQRADIO_DEL_AYS, 'post', $title, 'submit'));

    $form->display();
}

// Insert Channel Query

/**
 * @param $sane_REQUEST
 * @return string
 */
function uhqradio_channelform_insertquery($sane_REQUEST)
{
    global $xoopsDB;

    $query = 'INSERT INTO ' . $xoopsDB->prefix('uhqradio_channels');
    $query .= " SET chan_name = '" . $sane_REQUEST['chan_name'] . '\', ';
    $query .= " chan_tag = '" . $sane_REQUEST['chan_tag'] . '\', ';
    $query .= " chan_web = '" . $sane_REQUEST['chan_web'] . '\', ';
    $query .= " chan_descr = '" . $sane_REQUEST['chan_descr'] . '\', ';
    $query .= " text_mpid = '" . $sane_REQUEST['text_mpid'] . '\', ';
    $query .= " flag_djid = '" . $sane_REQUEST['flag_djid'] . '\', ';
    $query .= " flag_d_sol = '" . $sane_REQUEST['flag_d_sol'] . '\', ';
    $query .= " flag_d_eol = '" . $sane_REQUEST['flag_d_eol'] . '\', ';
    $query .= " delim_dj_s = '" . $sane_REQUEST['delim_dj_s'] . '\', ';
    $query .= " delim_dj_e = '" . $sane_REQUEST['delim_dj_e'] . '\', ';
    $query .= " flag_show = '" . $sane_REQUEST['flag_show'] . '\', ';
    $query .= " flag_s_sol = '" . $sane_REQUEST['flag_s_sol'] . '\', ';
    $query .= " flag_s_eol = '" . $sane_REQUEST['flag_s_eol'] . '\', ';
    $query .= " delim_sh_s = '" . $sane_REQUEST['delim_sh_s'] . '\', ';
    $query .= " delim_sh_e = '" . $sane_REQUEST['delim_sh_e'] . '\', ';
    $query .= " lc_guid = '" . $sane_REQUEST['lc_guid'] . '\', ';
    $query .= " tunein_pid = '" . $sane_REQUEST['tunein_pid'] . '\', ';
    $query .= " tunein_pkey = '" . $sane_REQUEST['tunein_pkey'] . '\', ';
    $query .= " tunein_sid = '" . $sane_REQUEST['tunein_sid'] . '\' ';

    return $query;
}

// Update Channel Query

/**
 * @param $sane_REQUEST
 * @return string
 */
function uhqradio_channelform_updatequery($sane_REQUEST)
{
    global $xoopsDB;

    $query = 'UPDATE ' . $xoopsDB->prefix('uhqradio_channels');

    $query .= " SET chan_name = '" . $sane_REQUEST['chan_name'] . '\', ';
    $query .= " chan_name = '" . $sane_REQUEST['chan_name'] . '\', ';
    $query .= " chan_tag = '" . $sane_REQUEST['chan_tag'] . '\', ';
    $query .= " chan_web = '" . $sane_REQUEST['chan_web'] . '\', ';
    $query .= " chan_descr = '" . $sane_REQUEST['chan_descr'] . '\', ';
    $query .= " text_mpid = '" . $sane_REQUEST['text_mpid'] . '\', ';
    $query .= " flag_djid = '" . $sane_REQUEST['flag_djid'] . '\', ';
    $query .= " flag_d_sol = '" . $sane_REQUEST['flag_d_sol'] . '\', ';
    $query .= " flag_d_eol = '" . $sane_REQUEST['flag_d_eol'] . '\', ';
    $query .= " delim_dj_s = '" . $sane_REQUEST['delim_dj_s'] . '\', ';
    $query .= " delim_dj_e = '" . $sane_REQUEST['delim_dj_e'] . '\', ';
    $query .= " flag_show = '" . $sane_REQUEST['flag_show'] . '\', ';
    $query .= " flag_s_sol = '" . $sane_REQUEST['flag_s_sol'] . '\', ';
    $query .= " flag_s_eol = '" . $sane_REQUEST['flag_s_eol'] . '\', ';
    $query .= " delim_sh_s = '" . $sane_REQUEST['delim_sh_s'] . '\', ';
    $query .= " delim_sh_e = '" . $sane_REQUEST['delim_sh_e'] . '\', ';
    $query .= " lc_guid = '" . $sane_REQUEST['lc_guid'] . '\', ';
    $query .= " tunein_pid = '" . $sane_REQUEST['tunein_pid'] . '\', ';
    $query .= " tunein_pkey = '" . $sane_REQUEST['tunein_pkey'] . '\', ';
    $query .= " tunein_sid = '" . $sane_REQUEST['tunein_sid'] . '\' ';

    $query .= " WHERE chid = '" . $sane_REQUEST['chid'] . '\'';

    return $query;
}

// Map a counter-capable mountpoint

/**
 * @param      $title
 * @param      $target
 * @param null $formdata
 * @param null $op
 */
function uhqradio_mapform($title, $target, $formdata = null, $op = null)
{
    global $xoopsDB;

    $form = new XoopsThemeForm($title, 'mapform', $target, 'post', true);

    // Selection of counter info

    $form_c = new XoopsFormSelect(_FM_UHQRADIO_COUNT_INFO, 'mpid');

    $query  = 'SELECT * FROM ' . $xoopsDB->prefix('uhqradio_mountpoints');
    $query  .= ' WHERE flag_count > 0';
    $result = $xoopsDB->queryF($query);
    if ($result === false) {
        // Do not add options.
    } else {
        // Load option from DB
        while ($row = $xoopsDB->fetchArray($result)) {
            $srvinfo = uhqradio_fullservertype($row['type']) . '=' . $row['server'] . ':' . $row['port'] . $row['mount'];
            $form_c->addOption($row['mpid'], $srvinfo);
        }
    }
    $form->addElement($form_c);

    $form->addElement(new XoopsFormButton('', 'post', $title, 'submit'));
    $form->addElement(new XoopsFormHidden('chid', $formdata['chid']));
    $form->addElement(new XoopsFormHidden('op', 'addch'));
    $form->addElement(new XoopsFormHidden('verify', '1'));

    $form->display();
}

/**
 * @param $sane_REQUEST
 * @return string
 */
function uhqradio_mapform_insertquery($sane_REQUEST)
{
    global $xoopsDB;

    $query = 'INSERT INTO ' . $xoopsDB->prefix('uhqradio_countmap');
    $query .= " SET chid = '" . $sane_REQUEST['chid'] . '\', ';
    $query .= " mpid = '" . $sane_REQUEST['mpid'] . '\' ';

    return $query;
}
