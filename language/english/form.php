<?php

// Airstaff Form

define('_FM_UHQRADIO_DJID', 'DJ Identifier: ');
define('_FM_UHQRADIO_USER', 'Website User: ');
define('_FM_UHQRADIO_URLPIC', 'URL - DJ Picture: ');
define('_FM_UHQRADIO_URLSITE', 'URL - DJ Website: ');
define('_FM_UHQRADIO_REQUESTS', 'Accept Requests');
define('_FM_UHQRADIO_REQ_OK', 'Requests ok!');
define('_FM_UHQRADIO_REQ_NOK', 'Turn off Requests');
define('_FM_UHQRADIO_DELETE', 'Delete');
define('_FM_UHQRADIO_DEL_AYS', 'Are You Sure?');
define('_FM_UHQRADIO_PLAYGAME', 'Likes to play (games)');
define('_FM_UHQRADIO_PLAYMUSIC', 'LIkes to play (music)');
define('_FM_UHQRADIO_BLURB', 'The Blurb<br>1024 char max');

define('_FM_UHQRADIO_PROFILE_PIC', 'Upload Profile Picture');
define('_FM_UHQRADIO_PROFILE_CURPIC', 'Current Picture');
define('_FM_UHQRADIO_PROFILE_EDITPIC', 'Change Profile Picture');

define('_FM_UHQRADIO_SAMDB_HDR',
       '<h3>SAM Broadcaster Integration</h3><p>This section contains the information needed to access your MySQL database and coordiate requests w/ SAM.</p><p>The default ports are 3306 for SQL and 1221 for SAM.  Please forward a unique port number to these ports through your firewall or gateway router.</p>');

define('_FM_UHQRADIO_SAMDB_HTTP', 'HTTP Port: ');
define('_FM_UHQRADIO_SAMDB_SQLP', 'SQL Port: ');
define('_FM_UHQRADIO_SAMDB_SQLDB', 'SQL Database: ');
define('_FM_UHQRADIO_SAMDB_SQLUN', 'SQL Username: ');
define('_FM_UHQRADIO_SAMDB_SQLPW', 'SQL Password: ');

// Mountpoint Form

define('_FM_UHQRADIO_SVRFQDN', 'Server IP/FQDN');
define('_FM_UHQRADIO_SVRPORT', 'Server Port');
define('_FM_UHQRADIO_SVRTYPE', 'Server Type');
define('_FM_UHQRADIO_SVRMOUNT', 'IceCast Mount');
define('_FM_UHQRADIO_SVRFALLBACK', 'IceCast Fallback');
define('_FM_UHQRADIO_SVRAUN', 'Statistics Username');
define('_FM_UHQRADIO_SVRAPW', 'Statistics Password');
define('_FM_UHQRADIO_SVRCODE', 'Codec');
define('_FM_UHQRADIO_SVRBR', 'Bitrate');
define('_FM_UHQRADIO_SVRMAX', 'Maximum Listeners');
define('_FM_UHQRADIO_SVRVAR', 'Listener Variance<br></b>Deduct from listener count.');
define('_FM_UHQRADIO_SVRTXT', 'Reliable Text?');
define('_FM_UHQRADIO_SVRCOUNT', 'Reliable Count?');

define('_FM_UHQRADIO_STYPE_I', 'IceCast');
define('_FM_UHQRADIO_STYPE_P', 'StreamerP2P');
define('_FM_UHQRADIO_STYPE_S', 'Shoutcast');

define('_FM_UHQRADIO_CODEC_A', 'AAC');
define('_FM_UHQRADIO_CODEC_M', 'MP3');
define('_FM_UHQRADIO_CODEC_O', 'Ogg Vorbis');

define('_FM_UHQRADIO_TEXT_OK', 'Suitable for text information.');
define('_FM_UHQRADIO_TEXT_NOK', 'Do not use for text.');

define('_FM_UHQRADIO_COUNT_OK', 'Suitable for listener count.');
define('_FM_UHQRADIO_COUNT_NOK', 'Do not use for counts.');

define('_FM_UHQRADIO_MOUNT_DELWARN', '<span style="color:red;">WARNING!</span><br><p>Deleting this mountpoint will remove it from all counter maps, playlists, and disassociate it from any channel text information.</p>');
define('_FM_UHQRADIO_FORM_DELHD', 'Delete Historic Data?');
define('_FM_UHQRADIO_FORM_RD', 'Remove Data');

// Channel Form

define('_FM_UHQRADIO_CHANNEL_NAME', 'Channel Name');
define('_FM_UHQRADIO_CHANNEL_TAG', 'Channel Tagline');
define('_FM_UHQRADIO_CHANNEL_WEB', 'Channel Info');
define('_FM_UHQRADIO_CHANNEL_DESC', 'Channel Description');

define('_FM_UHQRADIO_TEXT_INFO', 'Text Source Mountpoint');
define('_FM_UHQRADIO_TEXT_NONE', 'Source undefined/unused');
define('_FM_UHQRADIO_COUNT_INFO', 'Counter Source Mountpoint');

define('_FM_UHQRADIO_DJINFO_HDR', '<p>The following items pertain to extracing the DJ ID from your stream description.</p>');
define('_FM_UHQRADIO_SHOWINFO_HDR', '<p>The following items pertain to extracting the show name from your stream description.</p>');

define('_FM_UHQRADIO_DJINFO', 'Parse DJ Info');
define('_FM_UHQRADIO_SHOWINFO', 'Parse Show Info');

define('_FM_UHQRADIO_INFOSRC_0', 'Do not use.');
define('_FM_UHQRADIO_INFOSRC_1', 'Extract from description.');
define('_FM_UHQRADIO_INFOSRC_2', 'Extract from server title.');

define('_FM_UHQRADIO_START', 'SOL/Start Delimiter');
define('_FM_UHQRADIO_START_TXT', 'Start Delimiter Text');
define('_FM_UHQRADIO_END', 'EOL/End Delimiter');
define('_FM_UHQRADIO_END_TXT', 'End Delimiter Text');

define('_FM_UHQRADIO_SOL', 'Start-Of-Line');
define('_FM_UHQRADIO_EOL', 'End-Of-Line');
define('_FM_UHQRADIO_DELIM', 'Delimiter');

define('_FM_UHQRADIO_LICENSING', 'Licensing Information');
define('_FM_UHQRADIO_LOUDCITY_GUID', 'LoudCity GUID (blank if unused)');

define('_FM_UHQRADIO_REPORTING', 'Supplemental Reporting');
define('_FM_UHQRADIO_TUNEIN_PID', 'RadioTime Partner ID');
define('_FM_UHQRADIO_TUNEIN_PKEY', 'RadioTime Partner Key');
define('_FM_UHQRADIO_TUNEIN_SID', 'RadioTime Station ID');

define('_FM_UHQRADIO_CHANNEL_DELWARN', '<span style="color:red;">WARNING!</span><br><p>Deleting this channel will remove it from all counter maps and will delete its playlist.</p>');
