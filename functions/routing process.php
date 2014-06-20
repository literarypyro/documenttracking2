<?php
//Routing Specific Functions


/**
* Document Routing has two parts: one where the source office and source officer is specified
* and the Routing instance is created, and the one with the target office and officer.  This is a 
* one-is-to-many relationship.  
*
*  AddNewRouting creates the first section, which specifies the document being routed.  Going 
* to a destination, however, requires the second section.
*/
function addNewRouting($db,$referenceNumber,$date,$office,$officerMark){
	ini_set("date.timezone","Asia/Kuala_Lumpur");
	
	$officer=$officerMark[0];
	$alter_officer=$officerMark[1];
	$alter_position=$officerMark[2];
	
	$sql="insert into document_routing(reference_no,from_name,request_date,from_office,alter_from,alter_position,input_time) values
	('".$referenceNumber."','".$officer."','".$date."','".$office."','".$alter_officer."','".$alter_position."','".date("Y-m-d H:i")."')";
	$rs=$db->query($sql);
		
	$routingId=$db->insert_id;
	return $routingId;
}

/** This is the second section which provides the routing targets for a particular routing action.
*  There are two options: one is to mark a routing action as being sent to all destinations, and another
* that specifies one or many destination, but not all
*/
function addRoutingStatus($setDb,$routing){
	$destination=$routing[0];
	$to_name=$routing[1];
	$action_id=$routing[2];
	$status=$routing[3];
	$routing_id=$routing[4];
	$remarks=$routing[5];
	$documentId=$routing[6];
	$alter_to=$routing[7];
	$alter_position=$routing[8];
	$code_key=$routing[9];
	
	$sql="insert into routing_targets
	(destination_office,to_name,action_id, status, routing_id, remarks,alter_to,alter_position,keycode)
	values
	(\"".$destination."\",\"".$to_name."\",\"".$action_id."\",\"".$status."\",\"".$routing_id."\",\"".$remarks."\",\"".$alter_to."\",\"".$alter_position."\",'".$code_key."')";
	$rs=$setDb->query($sql);
	$routingTargetId=$setDb->insert_id;
	
	$docRow=getDocumentDetails($setDb,$documentId);
	
	
	if(($docRow['security']=="unsecured")||($docRow['security']=="divSecured")){
		if($destination=="ALL OFFICERS"){
			if ($handle = opendir('data')) {
				while (false !== ($file = readdir($handle))) {
					if ($file != "." && $file != "..") {
							$msg=$action_id;
							$filename  = "data/".$file;
							// store new message in the file
							
							
							file_put_contents($filename,$msg);

	//				echo "$file\n";
					}
				}		
				closedir($handle);
			}
		}
		else {
			$msg=$action_id;
			$filename  = "data/".$destination.".txt";
			// store new message in the file
			file_put_contents($filename,$msg);
		
		}
	}
	else {
		if($docRow['security']=="GMsecured"){
			if($docRow['status']=="SENT"){
				$sql="select * from document_routing where reference_no='".$documentId."' order by request_date desc";
				$rs=$setDb->query($sql);
				$row=$rs->fetch_assoc();
				
				$sql2="select * from routing_targets where routing_id='".$row['id']."' and status in ('PENDING') order by id desc";
				$rs2=$setDb->query($sql2);
				$nm2=$rs2->num_rows;
				
				for($i=0;$i<$nm2;$i++){
//					echo $row2['destination_office']."<br>";
					$row2=$rs2->fetch_assoc();
					$msg=$action_id;
					$filename  = "data/".$row2['destination_office'].".txt";
					// store new message in the file
					file_put_contents($filename,$msg);
				}
			}		
			else if($docRow['status']=="FOR: GM APPROVAL"){
				$msg=$action_id;
				$filename  = "data/OGM.txt";
				// store new message in the file
				file_put_contents($filename,$msg);
				
			}
		
			else if($docRow['status']=="FOR: CLARIFICATION"){
				$msg=$action_id;
				$filename  = "data/".$destination.".txt";
				// store new message in the file
				file_put_contents($filename,$msg);
				
			}
		
			else {
				$msg=$action_id;
				$filename  = "data/".$destination.".txt";
				// store new message in the file
				file_put_contents($filename,$msg);
			
			}
		}
	}

	return $routingTargetId;
}

/** Any changes, whether an answered Routing Action or a closing of a Document,
*  changes the status of a Routing object.  This is the function that does the 
* updating; it is the first version, which generally changes a status
*/
function updateRoutingStatus($setDb,$department,$status,$routing){
	$new_status=$status[0];
	$old_status=$status[1];

	$sql="update
	routing_targets set 
	status='".$new_status."' where status='".$old_status."' and 
	destination_office in ('".$department."') and routing_id='".$routing."'";
	
	$rs=$setDb->query($sql);
	
	$routingTargetId=$setDb->insert_id;
	return $routingTargetId;
	
}
function updateRoutingStatus3($setDb,$department,$status,$routing){
	$new_status=$status[0];
	$old_status=$status[1];

	$sql="update
	routing_targets set 
	status='".$new_status."' where status='".$old_status."' and 
	destination_office in ('".$department."') and id='".$routing."'";
	
	$rs=$setDb->query($sql);
	
	$routingTargetId=$setDb->insert_id;
	return $routingTargetId;
	
}
/**
* The second version of the update of Routing Action status,
* this caters mainly to the Routing Actions in need of answer.
* The status becomes Answered after invoking this function.
*/
function updateRoutingStatus2($setDb,$status,$routing,$department){
	$sql="update
	routing_targets set 
	status='".$status."' where status='ANSWERED' and 
	destination_office in ('".$department."') and routing_id='".$routing."'";
	$rs=$setDb->query($sql);
}

/**
* Retrieves the Originating Officer
*/
function getOfficer($db,$officer_id){
	$sql="select * from originating_officer where id='".$officer_id."'";
	$rs=$db->query($sql);
	$row=$rs->fetch_assoc();
	return $row;
}

/**
* A Routing Action has options on its action.  This is the 
* function that identifies which action was used
*/
function getAction($db,$action_id){
	$sql="select * from document_actions where action_code='".$action_id."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
	if($nm>0){
	$row=$rs->fetch_assoc();
	$action=$row['action_description'];
	}
	else {
	$action=$action_id;	
	}
	
	return $action;
}

/** This function lists all the routing actions within a particular document
*/
function getRoutingActions($db,$ref_no){

	$sql="select * from document_routing where reference_no*1='".$ref_no."'";
	
	$rs=$db->query($sql);
	return $rs;
}
function getRoutingActions2($db,$ref_no){

	$sql="select * from document_routing where reference_no*1='".$ref_no."' order by request_date desc";
	
	$rs=$db->query($sql);
	return $rs;
}


/** In a particular Routing Action, this function lists all the destinations
*/
function getRoutingTargets($db,$routing_id){
	$sql="select * from routing_targets where routing_id='".$routing_id."'";
	$rs=$db->query($sql);
	return $rs;
}

function receiveRoutingMessages($db,$department){
	$sql="select * from document_routing inner join routing_targets on document_routing.id=routing_targets.routing_id where destination_office in ('".$department."','ALL OFFICERS') and status in ('PENDING','NEEDING CLARIFICATION') order by request_date desc";
	$rs=$db->query($sql);
	return $rs;
}

/** This action deletes the last routing action made */
function deleteLastAction($db,$reference_number,$department){
	$sql="select * from document_routing where reference_no='".$reference_number."' order by request_date desc limit 2";
	$rs=$db->query($sql);
	$row=$rs->fetch_assoc();
	$routingID=$row['id'];
	
	$sql2="delete from document_routing where trim(id)='".$routingID."'";
	$rs2=$db->query($sql2);
	deleteLastTargets($db,$row['id']);

	$row=$rs->fetch_assoc();
	updateRoutingStatus2($db,"PENDING",$row['id'],$department);	
	
}

/** This function clears all the destinations set within a routing action */
function deleteLastTargets($db,$routingId){
	$sql="delete from routing_targets where routing_id='".$routingId."'";
	$rs=$db->query($sql);
}	

/** A Routing Action has the option of uploading a related document to it.  This 
* uploads the document for specific action
*/
function linkUpload($db,$routingTargetId,$uploadLink){
	$upload_Date=date("Y-m-d H:i:s");
	$sql="insert into routing_uploads(targets_id,upload_link,upload_date) values (\"".$routingTargetId."\",\"".$uploadLink."\",'".$upload_Date."')";
	$rs=$db->query($sql);
	$id=$db->insert_id;
	return $id;
}

/** Select the uploaded document attached to a routing action */
function getRoutingUpload($db,$routing_target){
	$sql="select * from routing_uploads where targets_id='".$routing_target."'";
	$rs=$db->query($sql);
	$row=$rs->fetch_assoc();
	$link=$row['upload_link'];
	return $link;
}

function generateCodeKey(){
	 $characters = 'psilva143bcd25efgh78jkmno69qrtuwxyz0';
	 
	 $random_string_length=6;
	 $string = '';
	 for ($i = 0; $i < $random_string_length; $i++) {
		  $string .= $characters[rand(0, strlen($characters) - 1)];
	 }

	return $string;
}

function isReceived($db,$target_id,$division){
	$sql="select * from routing_receipt where target_id='".$target_id."' and division like '".$division."%%'";

	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
	if($nm>0){
		return "YES";
	}
	else {
		return "NO";
	}


}



?>