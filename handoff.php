<?php



// Handle the "handoff" block.  This separate from the autoplayer contol.
//
// All responses will be redirects to the referring page.

include '../../mainfile.php';

include 'include/functions.php';

// Set a referrer.

if ($_SERVER['HTTP_REFERER']) {
	$refer = $_SERVER['HTTP_REFERER'];
} else {
	$refer = '/';
}

// Process Request, but only an operation is present.

if ($_REQUEST['op']) {

	// Check Permission First, except for actual handoff and verification.
	
	if ( ($_REQUEST['op'] != "go") && ($_REQUEST['op'] != "verify") ) {
		
		// Make sure someone is logged in.
		
		if ( !$xoopsUser ) {
			redirect_header($refer,5,_MD_UHQRADIO_ERROR_LOGIN);
			break;
		}

		// Load group permissions for the block we want.

		$query = "SELECT DISTINCT xp.gperm_groupid FROM ";
		$query .= $xoopsDB->prefix("newblocks")." xb, ";
		$query .= $xoopsDB->prefix("group_permission")." xp ";
		$query .= "WHERE xb.dirname = 'uhq_radio' AND xb.show_func = 'b_uhqradio_handoff_show' ";
		$query .= "AND xb.bid = xp.gperm_itemid ";
		$query .= "ORDER BY xb.bid, xp.gperm_itemid";
	
		$result = $xoopsDB->queryF($query);
		if ($result == false) {
			redirect_header($refer,1,_MD_UHQRADIO_ERROR_NOAUTH);
			break;
		}
		
		$blockgroups = array();
		while ($row=$xoopsDB->fetchRow($result) ) {
			$blockgroups[] = $row[0];
		}
		
		// Reject if the user does not have permission.
		
		if ( count(array_intersect($xoopsUser->getGroups(), $blockgroups)) == 0) {
			redirect_header($refer,10,_MD_UHQRADIO_ERROR_NOPERM);
			break;
		}
	}
	
	// Load options from Block
	
	$query = "SELECT options FROM ";
	$query .= $xoopsDB->prefix("newblocks");
	$query .= " WHERE dirname = 'uhq_radio' AND show_func = 'b_uhqradio_handoff_show'";
	
	$result = $xoopsDB->queryF($query);
	if ($result == false) {
		redirect_header($refer,1,_MD_UHQRADIO_ERROR_BLOCKOPT);
		break;
	}
	$row = $xoopsDB->fetchRow($result);
	$options = explode("|",$row[0]);
	
	// Load last handoff info from DB.
	
	$query = "SELECT * FROM ".$xoopsDB->prefix('uhqradio_handoffs');
	$query .= " WHERE reqstat > 0 ORDER BY reqtime LIMIT 1";
	
	$result = $xoopsDB->queryF($query);
	
	if ($result == false) {
		redirect_header($refer,1,"Cannot load last handoff from DB.");
		break;
	}
	$handoff = $xoopsDB->fetchArray($result);

	// Process Request	
	
	switch ( $_REQUEST['op'] )
	{		
		case "go" :
			if ( $handoff['reqtime'] ) {
				// Use last verified handoff
				
				$request = "http://".$handoff['reqip'].":".$options[0].$options[3];
			
				if ( uhqradio_externalurl($request) ) {
					// Remove successful handoff from DB.
					$query = "DELETE FROM ".$xoopsDB->prefix('uhqradio_handoffs')." WHERE ";
					$query .= "reqtime = '".$handoff['reqtime']."'";

					$result = $xoopsDB->queryF($query);
					echo "Handoff command accepted.";
				} else {
					echo "Automatic handoff failed.";
				}
			} else {
				// If there's no request, let's start the autoplayer.
	
				$query = "SELECT options FROM ";
				$query .= $xoopsDB->prefix("newblocks");
				$query .= " WHERE dirname = 'uhq_radio' AND show_func = 'b_uhqradio_control_show'";
	
				$result = $xoopsDB->queryF($query);
				if ($result == false) {
					redirect_header($refer,1,_MD_UHQRADIO_ERROR_BLOCKOPT);
					break;
				}
				$row = $xoopsDB->fetchRow($result);
				$controloptions = explode("|",$row[0]);	
				
				if ( uhqradio_externalurl($controloptions[0]) ) {
					echo "Autoplayer started!";
				} else {
					echo "Autoplayer handoff failed.";
				}			}
			return;
		case "request" :	// Attempt to contact remote DJ Player.
			$request = "http://".$_SERVER['REMOTE_ADDR'].":".$options[0].$options[2];

			// Build Query	
			$query = "INSERT INTO ".$xoopsDB->prefix('uhqradio_handoffs')." SET ";
			$query .= "reqtime = NOW(), ";
			$query .= "requser = '".$xoopsUser->uid()."', ";
			$query .= "reqip = '".$_SERVER['REMOTE_ADDR']."', ";
			
			if ( uhqradio_externalurl($request) ) {
				// Save request to DB
				$query .= "reqstat = '1'";
				$result = $xoopsDB->queryF($query);
				
				if ($result == false) {
					redirect_header($refer,5,"Verify OK, but not saved to DB.");
					break;
				}
				// Redirect
				redirect_header($refer,1,_MD_UHQRADIO_HANDOFF_VERIFY_OK);
				break;
			} else {
				if ( $options[1] ) {
					// Save request to DB
					$query .= "reqstat = '2'";
					$result = $xoopsDB->queryF($query);
					
					// Respond
					redirect_header($refer,10,_MD_UHQRADIO_HANDOFF_VERIFY_MANUAL);
					break;
				} else {
					// Save attempt to DB
					$query .= "reqstat = '0'";
			
					$result = $xoopsDB->queryF($query);
					
					redirect_header($refer,1,_MD_UHQRADIO_HANDOFF_VERIFY_NOK);
					break;
				}
			}
			return;
		case "surrender" :
			if ($xoopsUser->uid() == $handoff['requser']) {
				$query = "DELETE FROM ".$xoopsDB->prefix('uhqradio_handoffs')." WHERE ";
				$query .= "reqtime = '".$handoff['reqtime']."'";
				
				$result = $xoopsDB->queryF($query);
				
				redirect_header($refer,5,"Handoff surrendered.");
				break;
			} else {
				redirect_header($refer,5,"You cannot surrender someone's handoff for them.");
				break;
			}
			break; 
		case "verify" :
			// This comes from the remote start script indicating readiness.
			
			if ($handoff['reqip'] == $_SERVER['REMOTE_ADDR']) {
				$query = "UPDATE ".$xoopsDB->prefix('uhqradio_handoffs')." SET ";
				$query .= " reqstat = '2' ";
				$query .= " WHERE reqtime = '".$handoff['reqtime']."'";
				
				$result = $xoopsDB->queryF($query);
				
				if ($result == false) {
					echo "Your player is verified, but I couldn't save the status.";
				} else {
					echo "Your player is verified.  Enjoy your show!";
				}
			} else {
				echo "I cannot verify this request.";
			}
			
			return;
		default :
			redirect_header($refer, 1, _MD_UHQRADIO_ERROR_UNSPOORTED.$_REQUEST['op']);
			return;
	}
} else {

	// Handle if there is no operation
	
	if ($_SERVER['HTTP_REFERER']) {
		redirect_header($refer, 1, _MD_UHQRADIO_ERROR_NOOP);
	} else {
		redirect_header('/', 1, _MD_UHQRADIO_ERROR_DIRECT);		
	}
}

?>