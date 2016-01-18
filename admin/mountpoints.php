<?php
include "../../../mainfile.php";
include XOOPS_ROOT_PATH."/include/cp_header.php";

include "functions.inc.php";
include "../include/functions.php";
include "../include/forms.php";
include "../include/sanity.php";

require_once XOOPS_ROOT_PATH . '/class/template.php';
if (!isset($xoopsTpl)) {
	$xoopsTpl = new XoopsTpl();
}
$xoopsTpl->xoops_setCaching(0);

include "header.php";

// Grab operator if we have it

if ( isset($_REQUEST['op']) ) {
	$op = $_REQUEST['op'];
} else {
	$op = "none";
}

xoops_cp_header();

adminMenu(2);

// Do sanity checks here

$sane_REQUEST = uhqradio_dosanity();

switch ($op) {
	case "insert" :
		if ( isset($_REQUEST['verify']) ) {
			$query = uhqradio_mountform_insertquery($sane_REQUEST);
			$result = $xoopsDB->queryF($query);
			
			if ($result == false ) {
				redirect_header("mountpoints.php",30,_AM_UHQRADIO_SQLERR.$query."\r\n".$xoopsDB->error() );
				break;
			} else {
				$svrinfo = $sane_REQUEST['server'].":".$sane_REQUEST['port'].$sane_REQUEST['mount'];
				redirect_header("mountpoints.php",5,_AM_UHQRADIO_ADDED.$svrinfo);
				break;
			}
		} else {
			uhqradio_mountform(_AM_UHQRADIO_ADDMOUNT,'mountpoints.php');
		}
		break;
	case "edit" :
		// Verify we have minimum parameters
		if ( $_REQUEST['mpid'] ) {
			
			// Load Records
			$query = "SELECT * FROM ".$xoopsDB->prefix('uhqradio_mountpoints');
			$query .= " WHERE mpid = '".$sane_REQUEST['mpid']."'";
			
			$result = $xoopsDB->queryF($query);
			if ($result == false) {
				redirect_header("mountpoints.php",30,_AM_UHQRADIO_SQLERR.$query."\r\n".$xoopsDB->error() );
				break;
			}

			if ( (isset ($_REQUEST['verify'])) && ($sane_REQUEST['mpid'] > 0) ) {
				// Make Changes
				$query = uhqradio_mountform_updatequery($sane_REQUEST);
				$result = $xoopsDB->queryF($query);
				if ($result == false ) {
					redirect_header("mountpoints.php",30,_AM_UHQRADIO_SQLERR.$query."\r\n".$xoopsDB->error() );
					break;
				} else {
					redirect_header("mountpoints.php",5,_AM_UHQRADIO_EDITED.$_REQUEST['mpid']) ;
					break;
				}
			} else {
				$row = $xoopsDB->fetchArray($result);
				$srvinfo = $row['type']."=".$row['server'].":".$row['port'].$row['mount'];
				uhqradio_mountform(_AM_UHQRADIO_EDITMOUNT.' :: '.$srvinfo,'mountpoints.php',$row,'edit');
			}
		} else {
			redirect_header("mountpoints.php",10,_AM_UHQRADIO_PARAMERR);
		}
		break;
		
	case "delete" :
	
		// Verify we have minimum parameters
		if ( isset($_REQUEST['mpid']) ) {
			
			// Load Record
			$query = "SELECT * FROM ".$xoopsDB->prefix('uhqradio_mountpoints');
			$query .= " WHERE mpid = '".$sane_REQUEST['mpid']."'";
			
			$result = $xoopsDB->queryF($query);
			if ($result == false) {
				redirect_header("mountpoints.php",30,_AM_UHQRADIO_SQLERR.$query."\r\n".$xoopsDB->error() );
				break;
			}
			if ( isset ($_REQUEST['verify']) ) {
				
				// Delete Info
				$query = "DELETE FROM ".$xoopsDB->prefix('uhqradio_mountpoints');
				$query .= " WHERE mpid = '".$sane_REQUEST['mpid']."'";
				$result = $xoopsDB->queryF($query);
				if ($result == false ) {
					redirect_header("mountpoints.php",30,_AM_UHQRADIO_SQLERR.$query."\r\n".$xoopsDB->error() );
					break;
				} else {
					$textresult = _AM_UHQRADIO_DELETED.$sane_REQUEST['mpid'];

					// Clear text_mpid if any channels are using this mountpoint for text info.
					
					$query = "UPDATE ".$xoopsDB->prefix('uhqradio_channels');
					$query .= " SET text_mpid = '0' WHERE text_mpid = '".$sane_REQUEST['mpid']."'";
					$result = $xoopsDB->queryF($query);
					if ($result == false) {
						$textresult .= _AM_UHQRADIO_MP_TEXTCLEAR_NOK." (".$xoopsDB->error().")";
					} else {
						$textresult .= _AM_UHQRADIO_MP_TEXTCLEAR_OK;
					}
					
					// Clear mountpoint from counter map if this mountpoint is being used for count info
					
					$query = "DELETE FROM ".$xoopsDB->prefix('uhqradio_countmap');
					$query .= " WHERE mpid = '".$sane_REQUEST['mpid']."'";
					$result = $xoopsDB->queryF($query);
					if ($result == false) {
						$textresult .= _AM_UHQRADIO_MP_COUNTCLEAR_NOK." (".$xoopsDB->error().")";
					} else {
						$textresult .= _AM_UHQRADIO_MP_COUNTCLEAR_OK;
					}
					
				}
				
				if (isset ($_REQUEST['delhd']) ) {
					
					// Delete Listener History
					
					$query = "DELETE FROM ".$xoopsDB->prefix('uhqradio_lhistory');
					$query .= " WHERE mpid = '".$sane_REQUEST['mpid']."'";
					$result = $xoopsDB->queryF($query);
					if ($result == false) {
						$textresult .= _AM_UHQRADIO_MP_LHCLEAR_NOK." (".$xoopsDB->error().")";
					} else {
						$textresult .= _AM_UHQRADIO_MP_LHCLEAR_OK;
					}
						
				}
				
				redirect_header("mountpoints.php",5,$textresult) ;
				break;
				
			} else {
				// Display form
				$row = $xoopsDB->fetchArray($result);
				uhqradio_mountform_del($row);
			}
		} else {
			redirect_header("mountpoints.php",10,_AM_UHQRADIO_PARAMERR);
		}	
		break;
	case "none":
	default:
		if ( uhqradio_mountpointsum() ) {
			// List Mount Points
			$query = "SELECT * FROM ".$xoopsDB->prefix('uhqradio_mountpoints')." ORDER BY server, port, mount";
			$result = $xoopsDB->queryF($query);
			if ($result == false) {
				$xoospTpl->assign('error',$xoopsDB->error() );
			} else {
				$data = array();
				$i=1;
				while ($row = $xoopsDB->fetchArray($result) ) {
					$data[$i] = $row;
					$i++;
				}
				$xoopsTpl->assign('data',$data);
			}
		}
		$xoopsTpl->display("db:admin/uhqradio_admin_mountpoints.html");
		break;	
}

xoops_cp_footer();

?>