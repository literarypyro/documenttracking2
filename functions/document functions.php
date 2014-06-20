<?php
function updateDocumentStatus($setDb,$status,$ref_id){
	$update2="update document set status='".$status."' where (ref_id*1)='".($ref_id*1)."'";
	
	$update=$setDb->query($update2);	
}

function createNewDocument($db,$class,$office,$details){
	//$insYear=date("Y");
	$sql="insert into document (classification_id,subject,document_date,document_type,originating_office,originating_name,status,receive_date,sending_office,security,chronId) values 
	('".$class."',\"".$details[0]."\",\"".$details[1]."\",\"".$details[2]."\",\"".$office."\",'".$details[3]."','FOR: UPLOAD','".$details[4]."','".$details[5]."','".$details[6]."','".$details[7]."')";
	$rs=$db->query($sql);
	$documentId=$db->insert_id;
	return $documentId;
}

function createNewClassification($db,$classify){
	$insertClass="insert into classification(classification_desc) values (\"".$classify."\")";
	$rs=$db->query($insertClass);
	$classId=$db->insert_id;
	return $classId;
}

function createNewOffice($db,$originating){
	$insertClass="insert into originating_office(department_name) values (\"".$originating."\")";
//	echo $insertClass;
	$rs=$db->query($originating);
	$originId=$db->insert_id;
	return $originId;
}
function adjustNControlNumber($docId){
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
function calculateReferenceNumber($db,$row,$chronId){
	$receiveTime=$row['receive_date'];
	$documentType=$row['document_type'];
	
	$refType="";
	if($documentType=="MEMO"){
		$refType=$row["sending_office"];
	}
	else {
		$refType=$documentType;
	}
	
	
	//For the time
	$year=date("Y",strtotime($receiveTime));
	$refMonth=date("m",strtotime($receiveTime));
	$refYear=($year*1)%1000;
	$chronTag=adjustNControlNumber($row['chronId']);
	
	//$refType
	//$chronId
	//$refYear
	//$refMonth
	$reference_number=$refMonth.".".$refYear.".".$chronTag.".".$refType;
	return $reference_number;
}

function getDocumentDetails($db,$ref_no){
	$sql="select * from document where ref_id='".$ref_no."'";
	$rs=$db->query($sql);
	$row=$rs->fetch_assoc();
	return $row;	
}

function sortDocument($db,$condition,$department){
	$sql="select * from document where status='".$condition."' and sending_office='".$department."'";
	$rs=$db->query($sql);
	return $rs;	
}


function receiveDocument($db,$details){
	$insertSQL="insert into document_receipt(reference_id,confirm_time,document_id,upload_link) values 
	(\"".$details[0]."\",\"".$details[1]."\",'".$details[2]."','".$details[3]."')";
	$rs=$db->query($insertSQL);

}

function addUpload($db,$details_b){
	$upload_d=date("Y-m-d H:i:s");

	$insertSQL="insert into uploads(ref_no,upload_link,upload_date) values 
	(\"".$details_b[0]."\",\"".$details_b[1]."\",'".$upload_d."')";
	$rs=$db->query($insertSQL);
}

function getDocumentId($db,$referenceNumber){
$sql="select * from document_receipt where reference_id='".$referenceNumber."'";
$rs=$db->query($sql);
$row=$rs->fetch_assoc();
$referenceId=$row['document_id'];
return $referenceId;

}

function prepareUpload($documentType,$office,$security,$filename,$originOffice){
	$target_path="";
	if($documentType=="IN"){
		$folder=$originOffice;
	}
	else {
		$folder=$office;
	}
	
	if(is_dir("uploads/".$security."/".$folder)){
		$target_path="uploads/".$security."/".$folder;
	}
	else {
		mkdir("uploads/".$security);
		mkdir("uploads/".$security."/".$folder);

		$target_path="uploads/".$security."/".$folder;
	}
	
	if($filename==""){
	$target_path="";
	}
	else {
	$target_path = $target_path."/".$filename; 
	}
	return $target_path;

	
}

function uploadDocument($source,$target){
	$notification="";
	if(move_uploaded_file($source,$target)){
		$notification="Transfer was successful.";
	
	}
	else {
		$notification="There was an error uploading the file. Rename the file, or please try again.";

	}
	return $notification;

}

function recordDocumentAccess($db,$reference_number,$username,$department){
	$actionDate=date("Y-m-d H:i:s");
	$sql="insert into document_access(date_time,username,reference_id,division) values ('".$actionDate."','".$username."','".$reference_number."','".$department."')";
	$rs=$db->query($sql);
}

function recordDownloadAccess($db,$reference_number,$username,$department){
	$actionDate=date("Y-m-d H:i:s");
	$sql="insert into download(download_time,username,reference_id,department_code) values ('".$actionDate."','".$username."','".$reference_number."','".$department."')";
	$rs=$db->query($sql);
}

function retrieveDocumentAccess($db,$reference_number){
	$sql="select * from document_access where reference_id='".$reference_number."' limit 10";
	$rs=$db->query($sql);
	return $rs;
}

function retrieveDownloadAccess($db,$reference_number){
	$sql="select * from download where reference_id='".$reference_number."' limit 10";
	$rs=$db->query($sql);
	return $rs;
	
}

function topActiveDocuments($db){
	$sql="select *,(select request_date from document_routing where reference_no=document.ref_id 
	order by request_date desc limit 1) as latest_date from document where (select request_date from document_routing where reference_no=document.ref_id 
	order by request_date desc limit 1) is not null order by latest_date desc";
	$rs=$db->query($sql);
	return $rs;
}

function topActiveDocumentsDivision($db,$division){
	$sql="select *,(select request_date from document_routing where reference_no=document.ref_id 
	order by request_date desc limit 1) as latest_date from document where (select request_date from document_routing where reference_no=document.ref_id 
	order by request_date desc limit 1) is not null and sending_office='".$division."' order by latest_date";
	$rs=$db->query($sql);
	return $rs;
}

function turnOfTheYear($db){
	
	$currentYr=date("Y")*1;
	
	$sql="select * from reference_increment order by ref_id desc limit 1";
	$rs=$db->query($sql);
	
	$row=$rs->fetch_assoc();
	$dataYr=$row['year']*1;

	if($dataYr<$currentYr){
		$sql="delete from reference_increment";
		
		$rs=$db->query($sql);
		$sql="alter table reference_increment auto_increment=1";
		
		$rs=$db->query($sql);
	}
}


function retrieveIncrement($db){
	$dYear=date("Y");


	$sql="insert into reference_increment(year) values ('".$dYear."')";
	$rs=$db->query($sql);
	
	$insId=$db->insert_id;
	return $insId;
}

?>