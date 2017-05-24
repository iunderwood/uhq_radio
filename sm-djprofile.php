<?php

include '../../mainfile.php';
include XOOPS_ROOT_PATH."/class/xoopsformloader.php";

include 'include/sanity.php';
include 'include/functions.php';
include 'include/forms.php';
require_once (XOOPS_ROOT_PATH.'/modules/uhq_radio/include/rawdata.php');


$sane_REQUEST = uhqradio_dosanity();

$myts = MyTextsanitizer::getInstance();

if ( isset($_REQUEST['op']) ) {
	$op = $_REQUEST['op'];
} else {
	$op = "none";
}

// Do the Header

$xoopsOption['template_main'] = "uhqradio_djprofile.html";

include XOOPS_ROOT_PATH."/header.php";

switch ($op) {
	case "edit":
		
		// Verify mandatory option is set.
		if ( isset ($sane_REQUEST['djid']) ) {
			
			// Try and load data
			$query = "SELECT * FROM ".$xoopsDB->prefix("uhqradio_airstaff");
			$query .= " WHERE djid='".$sane_REQUEST['djid']."'";
			
			$result = $xoopsDB->queryF($query);
			
			if ($result == false) {
				$xoopsTpl->assign('error',$xoopsDB->error() );
				break;
			}
			$row = $xoopsDB->fetchArray($result);
			
			// Verify the record is not empty
			if ($row['djid']) {
				
				// Verify the user is the same one being edited
				if ($xoopsUser->getVar('uid') == $row['userkey']) {
					
					// If the "verify" flag is set, commit changes to the DB.
					if ( isset ($_REQUEST['verify']) ) {

						$result = $xoopsDB->queryF( uhqradio_airform_updatequery($sane_REQUEST) );
						
						if ($result == false) {
							$xoopsTpl->assign('error',$xoopsDB->error() );
							break;
						}

					// If the "verify" flag is unset, show the form.	
					} else {
						uhqradio_airform(_MD_UHQRADIO_DJPROFILE_EYI,'sm-djprofile.php',$row,'edit');
						break;
					}
				} else {
					$xoopsTpl->assign('error',_MD_UHQRADIO_DJPROFILE_NYP);
					break;
				}
			} else {
				$xoopsTpl->assign('error',_MD_UHQRADIO_DJPROFILE_NPF);
				break;
			}
			
		} else {
			$xoopsTpl->assign('error',_MD_UHQRADIO_DJPROFILE_NPS);
			break;		
		}
	default:
		if ( isset ($sane_REQUEST['djid']) ) {
			// Try and load data
			$query = "SELECT * FROM ".$xoopsDB->prefix("uhqradio_airstaff");
			$query .= " WHERE djid='".$sane_REQUEST['djid']."'";
			
			$result = $xoopsDB->queryF($query);
			
			if ($result == false) {
				$xoopsTpl->assign('error',$xoopsDB->error());
				break;
			}
			$row = $xoopsDB->fetchArray($result);
			
			// If there is a record, show it.  Otherwise error out.
			if ($row['djid']) {
				$data = $row;
				$data['djname'] = uhqradio_username($row['userkey']);
				
				// Sanitize for display.
				$data['play_mu'] = $myts->displayTarea($row['play_mu'],1,0,0);
				$data['blurb'] = $myts->displayTarea($row['blurb'],1);
			
				// Provide option to edit if the profile and current user are the same.
				if ($xoopsUser) {
					if ($xoopsUser->getVar('uid') == $row['userkey']) {
						$data['isuser'] = 1;
					}
				}
				$xoopsTpl->assign('data',$data);
			} else {
				$xoopsTpl->assign('error',_MD_UHQRADIO_DJPROFILE_NPF);
			}
			
		} else {
			// If there's nothing specified, print out a list.
			$data = uhqradio_data_djlist();
			$xoopsTpl->assign('data',$data);
			
			// $xoopsTpl->assign('error',_MD_UHQRADIO_DJPROFILE_NPS);
		}
		break;
}

// Do the Footer

include XOOPS_ROOT_PATH."/footer.php";

?>