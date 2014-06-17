<?php
/** This retrieves the ID of a document from the given Reference Number stamp */
function retrieveReferenceId($referenceNumber){
	$reference_element=explode(".",$referenceNumber);
	$ref_no=$reference_element[2];
	return $ref_no;
}

/** The control number has a certain format with leading zeros.  This calculates how many
* leading zeros there are.
*/
function adjustControlNumber($docId){
	if($docId<10000){
		if(($docId<10000)&&($docId>=1000)){
			$ref_chron=$docId;
		}
		else if(($docId<1000)&&(($docId>=100))){
			$ref_chron="0".$docId;
		}
		else if(($docId<100)&&($docId>=10)){
			$ref_chron="00".$docId;
		}
		else {
			$ref_chron="000".$docId;
		}
	}
	else {
		$ref_chron=$docId;
	}	

	return $ref_chron;
}

/** This adjusts the date to military time, from 1 PM to 13 */
function adjustTime($shift,$hour){
	$newHour=0;
	if($shift=='PM'){
		if($hour<12){
			$newHour=$hour*1+12;
		}
		else {
			$newHour=12;
		}
	}
	else if($shift=="AM"){
		if($hour<12){
			$newHour=$hour;
		}
		else {
			$newHour=0;
		}
	}
	return $newHour;
}

/** Retrieves the Division from a given Division Code */
function getDepartment($db,$departmentCode){
	$sql="select * from department where department_code='".$departmentCode."'";
	$rs=$db->query($sql);
	$row=$rs->fetch_assoc();
	return $row['department_name'];
}

/** Retrieves the Division from a given Division Code */
function getOriginatingOffice($db,$officeCode){
	$sql="select * from originating_office where department_code='".$officeCode."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;

	$orig="";
	if($nm>0){
		$row=$rs->fetch_assoc();
		$orig=$row['department_name'];
	}
	else {
		$orig=$officeCode;
	}
	

	return $orig;
}

/** From the document code, the Document Type is calculated */
function getDocumentType($db,$type_symbol){
	switch ($type_symbol) {
    case "IN":
		$document_type="INCOMING";
        break;
    case "OUT":
		$document_type="OUTGOING";
			break;
    case "MEMO":
		$document_type="INTERNAL MEMO";
        break;
    case "ORD":
		$document_type="OFFICE ORDER";
        break;
	}	
	return $document_type;
}

/** Displays a given timestamp */
function displayDate($date){
	$label=date("Y-m-d h:i:s",strtotime($date));
	return $label;

}

?>