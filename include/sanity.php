<?php

// Sanitize variables we expect for this module in $_REQUEST, that are put into queries.

function uhqradio_dosanity() {
	
	$sanerequest = array();
	$myts =& MyTextsanitizer::getInstance();
	
	// Airstaff Variables
	
	if ( isset ($_REQUEST['djid']) )	{ $sanerequest['djid'] = $myts->addSlashes( strtoupper($_REQUEST['djid']) ); }
	if ( isset ($_REQUEST['host']) )	{ $sanerequest['host'] = $myts->addSlashes( $_REQUEST['host']); }
	if ( isset ($_REQUEST['userkey']) )	{ $sanerequest['userkey'] = intval ($_REQUEST['userkey']); }
	if ( isset ($_REQUEST['flag_req']) ){ $sanerequest['flag_req'] = intval ($_REQUEST['flag_req']); }
	if ( isset ($_REQUEST['urlpic']) )	{ $sanerequest['urlpic'] = $myts->addSlashes($_REQUEST['urlpic']); }
	if ( isset ($_REQUEST['urlsite']) )	{ $sanerequest['urlsite'] = $myts->addSlashes($_REQUEST['urlsite']); }
	if ( isset ($_REQUEST['play_ga']) ) { $sanerequest['play_ga'] = $myts->addSlashes($_REQUEST['play_ga']); }
	if ( isset ($_REQUEST['play_mu']) ) { $sanerequest['play_mu'] = $myts->addSlashes($_REQUEST['play_mu']); }
	if ( isset ($_REQUEST['blurb']) )	{ $sanerequest['blurb'] = $myts->addSlashes($_REQUEST['blurb']); }
	if ( isset ($_REQUEST['rdb_port']) )	{ 
		$sanerequest['rdb_port'] = intval ($_REQUEST['rdb_port']); 
		if ( ($sanerequest['rdb_port'] > 65535) || ($sanerequest['rdb_port'] < 0) ) $sanerequest['rdb_port'] = 3306;
	}
	if ( isset ($_REQUEST['rdb_name']) )	{ $sanerequest['rdb_name'] = $myts->addSlashes($_REQUEST['rdb_name']); }
	if ( isset ($_REQUEST['rdb_user']) )	{ $sanerequest['rdb_user'] = $myts->addSlashes($_REQUEST['rdb_user']); }
	if ( isset ($_REQUEST['rdb_pass']) )	{ $sanerequest['rdb_pass'] = $myts->addSlashes($_REQUEST['rdb_pass']); }
	if ( isset ($_REQUEST['sam_port']) )	{ 
		$sanerequest['sam_port'] = intval ($_REQUEST['sam_port']); 
		if ( ($sanerequest['sam_port'] > 65535) || ($sanerequest['sam_port'] < 0) ) $sanerequest['sam_port'] = 1221;
	}
	
	// Mountpoint Variables
	
	if ( isset ($_REQUEST['mpid']) )	{ $sanerequest['mpid'] = intval ($_REQUEST['mpid']); }
	if ( isset ($_REQUEST['server']) )	{ $sanerequest['server'] = $myts->addSlashes($_REQUEST['server']); }
	if ( isset ($_REQUEST['port']) )	{ 
		$sanerequest['port'] = intval ($_REQUEST['port']);
		if ( ($sanerequest['port'] > 65535) || ($sanerequest['port'] < 0) ) $sanerequest['port'] = 8000;
	}
	if ( isset ($_POST['listeners']) )	{
		$sanerequest['listeners'] = intval ($_POST['listeners']);
	}
	if ( isset ($_REQUEST['type']) )		{ $sanerequest['type'] = $myts->addSlashes($_REQUEST['type']); }
	if ( isset ($_REQUEST['mount']) )		{ $sanerequest['mount'] = $myts->addSlashes($_REQUEST['mount']); }
	if ( isset ($_REQUEST['fallback']) )	{ $sanerequest['fallback'] = $myts->addSlashes($_REQUEST['fallback']); }
	if ( isset ($_REQUEST['auth_un']) )		{ $sanerequest['auth_un'] = $myts->addSlashes($_REQUEST['auth_un']); }
	if ( isset ($_REQUEST['auth_pw']) )		{ $sanerequest['auth_pw'] = $myts->addSlashes($_REQUEST['auth_pw']); }
	if ( isset ($_REQUEST['codec']) )		{ $sanerequest['codec'] = $myts->addSlashes($_REQUEST['codec']); }
	if ( isset ($_REQUEST['bitrate']) )		{ $sanerequest['bitrate'] = intval ($_REQUEST['bitrate']); }
	if ( isset ($_REQUEST['variance']) )	{ $sanerequest['variance'] = intval ($_REQUEST['variance']); }
	if ( isset ($_REQUEST['flag_text']) )	{ 
		$sanerequest['flag_text'] = intval ($_REQUEST['flag_text']);
		if ( ($sanerequest['flag_text'] > 1) || ($sanerequest['flag_text'] < 0) )
			$sanerequest['flag_text'] = 0;
	}
	if ( isset ($_REQUEST['flag_count']) )	{ $sanerequest['flag_count'] = intval ($_REQUEST['flag_count']); }
	
	// Channel Variables
	
	if ( isset ($_REQUEST['chid']) )	{ $sanerequest['chid'] = intval ($_REQUEST['chid']); }

	
	if ( isset ($_REQUEST['chan_name']) )	{ $sanerequest['chan_name'] = $myts->addSlashes($_REQUEST['chan_name']); }
	if ( isset ($_REQUEST['chan_tag']) )	{ $sanerequest['chan_tag'] = $myts->addSlashes($_REQUEST['chan_tag']); }
	if ( isset ($_REQUEST['chan_web']) )	{ $sanerequest['chan_web'] = $myts->addSlashes($_REQUEST['chan_web']); }
	if ( isset ($_REQUEST['chan_descr']) )	{ $sanerequest['chan_descr'] = $myts->addSlashes($_REQUEST['chan_descr']); }
	
	if ( isset ($_REQUEST['text_mpid']) )	{ $sanerequest['text_mpid'] = intval ($_REQUEST['text_mpid']); }
	if ( isset ($_REQUEST['flag_djid']) )	{ $sanerequest['flag_djid'] = intval ($_REQUEST['flag_djid']); }
	if ( isset ($_REQUEST['flag_d_sol']) )	{ $sanerequest['flag_d_sol'] = intval ($_REQUEST['flag_d_sol']); }
	if ( isset ($_REQUEST['flag_d_eol']) )	{ $sanerequest['flag_d_eol'] = intval ($_REQUEST['flag_d_eol']); }
	
	if ( isset ($_REQUEST['delim_dj_s']) )	{ $sanerequest['delim_dj_s'] = $myts->addSlashes($_REQUEST['delim_dj_s']); }
	if ( isset ($_REQUEST['delim_dj_e']) )	{ $sanerequest['delim_dj_e'] = $myts->addSlashes($_REQUEST['delim_dj_e']); }
	
	if ( isset ($_REQUEST['flag_show']) )	{ $sanerequest['flag_show'] = intval ($_REQUEST['flag_show']); }
	if ( isset ($_REQUEST['flag_s_sol']) )	{ $sanerequest['flag_s_sol'] = intval ($_REQUEST['flag_s_sol']); }
	if ( isset ($_REQUEST['flag_s_eol']) )	{ $sanerequest['flag_s_eol'] = intval ($_REQUEST['flag_s_eol']); }
	
	if ( isset ($_REQUEST['delim_sh_s']) )	{ $sanerequest['delim_sh_s'] = $myts->addSlashes($_REQUEST['delim_sh_s']); }
	if ( isset ($_REQUEST['delim_sh_e']) )	{ $sanerequest['delim_sh_e'] = $myts->addSlashes($_REQUEST['delim_sh_e']); }
	
	if ( isset ($_REQUEST['lc_guid']) )	{ $sanerequest['lc_guid'] = $myts->addSlashes($_REQUEST['lc_guid']); }
	
	if ( isset ($_REQUEST['tunein_pid']) )	{ $sanerequest['tunein_pid'] = $myts->addSlashes($_REQUEST['tunein_pid']); }
	if ( isset ($_REQUEST['tunein_pkey']) )	{ $sanerequest['tunein_pkey'] = $myts->addSlashes($_REQUEST['tunein_pkey']); }
	if ( isset ($_REQUEST['tunein_sid']) )	{ $sanerequest['tunein_sid'] = $myts->addSlashes($_REQUEST['tunein_sid']); }
	
	
	// Playlist Variables
	if ( isset ($_REQUEST['pl_start']) )	{ 
		$sanerequest['pl_start'] = intval ($_REQUEST['pl_start']);
		if ($sanerequest['pl_start'] < 0) $sanerequest['pl_start'] = 0;
	 }
	if ( isset ($_REQUEST['pl_limit']) )	{ 
		$sanerequest['pl_limit'] = intval ($_REQUEST['pl_limit']);
		if ($sanerequest['pl_limit'] < 5) $sanerequest['pl_start'] = 5;
		if ($sanerequest['pl_limit'] > 50) $sanerequest['pl_limit'] = 50; 
	}
	if ( isset ($_REQUEST['pl_search']) )	{ $sanerequest['pl_search'] = $myts->addSlashes($_REQUEST['pl_search']); }
	
	if ( isset ($_REQUEST['songid']) )		{
		$sanerequest['songid'] = intval ($_REQUEST['songid']);
		if ($sanerequest['songid'] < 0) $sanerequest['songid'] = 0;
	}
	
	if ( isset ($_REQUEST['start']) )	{
		if (($_REQUEST['start'] == '0') || ( ($_REQUEST['start'][0] >= 'A') && ($_REQUEST['start'][0] <= 'Z') ))
			$sanerequest['start'] = $_REQUEST['start'][0];
	}
		
	if (isset ($_REQUEST['info']) )	{ $sanerequest['info'] = $myts->addSlashes($_REQUEST['info']); }
		
//	Check templates
//	if ( isset ($_REQUEST['']) )	{ $sanerequest[''] = $myts->addSlashes($_REQUEST['']); }
//	if ( isset ($_REQUEST['']) )	{ $sanerequest[''] = intval ($_REQUEST['']); }
		
	return $sanerequest;
}

?>