<?php
session_start();
?>

	<?php
	/** Document History Page
	*
	* This page offers the details of a document.  The specific details,
	* the Routing Actions, who accessed it in the past few days, the soft copy,
	* and others
	*
	* @author Patrick Simon Silva
	* @version 2.0
	* @package document_history
	*/	
	?>
	<body>
	<?php	
//	require("header.php");
	require("db_page.php");
	require("title.php");
?>
	<script language="javascript">
	function openPrint(url){
		window.open(url);
	}
	</script>
	<!--
	<LINK href="css/program design 2.css" rel="stylesheet" type="text/css">
	-->
	<link href="dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="justified-nav.css" rel="stylesheet">	
	<style type="text/css">
	thead th{
		text-align:center;
	}
	
	
	</style>
		
<title>Document History</title>
<?php
require("functions/general functions.php");
require("functions/document functions.php");
require("functions/routing process.php");
require("functions/user functions.php");

if((isset($_POST['view']))||(isset($_GET['view']))){
	$docId=$_POST['view'].$_GET['view'];

	//Record the accessing of this page
	$db=retrieveRecordsDb(); // retrieved from db_page
	
	recordDocumentAccess($db,$docId,$_SESSION['username'],$_SESSION['department']); // retrieved from functions/document functions

	//recordDownloadAccess($db,$reference_number,$username,$department)
	$_SESSION['page']="document_history.php";
	
	$row=getDocumentDetails($db,$docId);
	$reference_number=calculateReferenceNumber($db,$row,adjustControlNumber($docId)); // retrieved from functions/document functions
	$_SESSION['reference_number']=$reference_number;
	$stats=$row['status'];
?>
<div align="right" width=100%><a href="receiveDocument.php">Go Back To Main Page</a></div>
<div align=right><input type=button class='btn btn-info' value='Prepare Print Out' onclick='openPrint("print_outline.php")' /></div>
<hr>
<table  width=100%>
<thead>
<tr>
<th colspan=11><h2>DOCUMENT HISTORY</h2></th>
</tr>
</thead>
</table>
</hr>
<br>
<!--Document Details Table-->
<?php
$documentDetails="
<table id='cssTable' class='table table-striped table-bordered' width=100%>
<tr>
<th colspan=2>ORIGINATING OFFICE</th>
<th colspan=2>".getOriginatingOffice($db,$row['originating_office'])."</th>
</tr>
<tr>
<td colspan=2 rowspan=2><b>Subject: </b>".$row['subject']."</td>
<td colspan=2 >
	<b>Date of Document: </b> 
	".date("F d, Y",strtotime($row['document_date']))." 
</td>
</tr>
<tr>
<td colspan=2 >
	<b>Date/Time Received: </b>
	".date("F d, Y, h:iA",strtotime($row['receive_date']))."
</td>
</tr>
</table>";
echo $documentDetails;
?>
<!-- Document Details Table-->
<br>
<!--Routing History/Actions Table-->
<?php
$actionTitle="
<table id='cssTable' class='table table-striped table-bordered table-hover' border=1 width=100%>
<thead>
<tr>";
$actionHeading="<th colspan=8><h3>ROUTING HISTORY</h3></th>";
$actionHeading2="<th colspan=8><h3>ACTION/S TAKEN</h3></th>";
$actionTable="
</tr>
<tr>
<th>DATE/TIME</th>
	<th align=center><b>FROM</b><br>Position and Name of<br> Official/Signature</th>
	<th align=center><b>TO</b><br>Position and Name of<br> Official/Signature</th>
	<th>REQUEST/ACTION</th>
	<th>ADDITIONAL REMARKS</th>
	<th>Status</th>
	<th>Received</th>
	<th>Code</th>
</tr></thead><tbody>";

	$db=retrieveRecordsDb(); //	retrieved from db_page
	$rs=getRoutingActions($db,$docId);	//	retrieved from functions/routing process
	$nm=$rs->num_rows;
	for($a=0;$a<$nm;$a++){
		$row=$rs->fetch_assoc();
		
		//For From Officer
		if($row['from_name']=="OTHER"){
			$officer_name=$row['alter_from'];
			$officer_position=$row['alter_position'];
			$officer_signature="";
		}
		else {
			$row2=getOfficer($db,$row['from_name']);
			$officer_name=$row2['name'];
			$officer_position=$row2['position'];
			$officer_signature=$row2['signature'];
		}
		//For Targets
		$rs2=getRoutingTargets($db,$row['id']);  //	retrieved from functions/routing process
		$nm2=$rs2->num_rows;
		
		for($i=0;$i<$nm2;$i++){
		
			$row2=$rs2->fetch_assoc();
			//$to_caption.=", ".$row2['destination_office'];		

			//For From Officer
			if($row2['destination_office']=="ALL"){
				$officer_name2="ALL DIVISIONS";
				$officer_position2="";
			}
			else {
				if($row2['to_name']=="OTHER"){
					$officer_name2=$row2['alter_to'];	
					$officer_position2=$row2['alter_position'];
					$officer_signature2="";
				}
				else {
					$row3=getOfficer($db,$row2['to_name']);
					$officer_name2=$row3['name'];
					$officer_position2=$row3['position'];
					$officer_signature2=$row3['signature'];
					
				}	
			}
			$action=getAction($db,$row2['action_id']); // retrieved from functions/routing process
			$actionStatus=$row2['status'];
		
		
		if($row['input_time']==""){
			$inp_time="";
		
		}
		else {
			$inp_time="<img src='info.gif' title='Input time: ".date("m/d/Y H:i",strtotime($row['input_time']))."' />";
		
		}
		
		
//			<img src='info.gif' title='Input time: ".date("m/d/Y H:i",strtotime($row['input_time']."' />
		$actionTable.="
		<tr>
		<td valign=center>".date("F d, Y, h:iA",strtotime($row['request_date']))." $inp_time <br></td>
		<td align=center>";
		if($officer_signature==""){
		}
		else {
			$actionTable.="<img src='signature/".$officer_signature.".png' /><br>";
		
		}
		
		
		$actionTable.=strtoupper($officer_name)."<br>".$officer_position."</td>
		<td align=center>";
		
		if($officer_signature2==""){
		}
		else {
			$actionTable.="<img src='signature/".$officer_signature2.".png' /><br>";
		
		}

		$actionTable.=strtoupper($officer_name2)."<br>".$officer_position2."</td>
		<td align=center>".$action."<br></td>
		<td align=center>".$row2["remarks"]."<br></td>			
		<td align=center>".$actionStatus."<br></td>	
		<td align=center>".strtoupper(isReceived($db,$row2['id'],""))."</td>";
	
	if($row['from_office']==$_SESSION['department']){
		$actionTable.="<td align=center><img src='barcodegen/test_1D2.php?text=".$row2['keycode']."' style='width:60%;height:60%;' /></td>";


	}
	else {
	
		$actionTable.="<td align=center><img src='barcodegen/test_1D.php?text=".$row2['keycode']."'  style='width:60%;height:60%;' /></td>";
	
	
	}
	
	
	
$actionTable.="</tr>
	";
			if($i==(($nm2*1)-1)){
				$lastDestination=$row['from_office'];
			}

		}
	
	}
$actionTable.="</tbody>
</table>
";	
echo $actionTitle.$actionHeading.$actionTable;
?>

<!--Routing History/Actions Table-->
<br>

<table width=100%>
<tr>
<td valign=top align=center>
<!--Download History Table-->
<table id='cssTable' class='table table-striped table-bordered table-hover'  border=1 style='width:80%;'>
<thead>
<tr>
<th colspan=5><h3>DOWNLOAD HISTORY</h3></th>
</tr>
<tr>
	<th>Date/Time</th>
	<th>User Name</th>
	<th>Division</th>
</tr>
</thead>
<tbody>
<?php
	$recordRS=retrieveDownloadAccess($db,$docId); // retrieved from functions/document functions
	$nm=$recordRS->num_rows;
	for($i=0;$i<$nm;$i++){
		$row=$recordRS->fetch_assoc();
?>
		<tr>
		<td><?php echo date("Y-m-d H:i:s",strtotime($row['download_time'])); ?></td>
		<td><?php echo $row["username"]; ?></td>
		<td><?php echo getDepartment($db,$row["department_code"]); ?></td>
		</tr>
	
<?php
	}
?>
</tbody>
</table>
<!--Download History Table-->
</td>
<td valign=top align=center>

<!--User Access Table-->
<table id='cssTable' class='table table-striped table-bordered table-hover'   style='width:80%' >
<thead>
<tr>
<th colspan=5><h3>USERS WHO ACCESSED RECORD</h3></th>
</tr>
<tr>
	<th>Date/Time</th>
	<th>User Name</th>
	<th>Division</th>
</tr>
</thead>
<tbody>
<?php
	$recordRS=retrieveDocumentAccess($db,$docId); // retrieved from functions/document functions
	$nm=$recordRS->num_rows;
	for($i=0;$i<$nm;$i++){
		$row=$recordRS->fetch_assoc();
		$db2=retrieveUsersDb(); // retrieved from db_page
?>
		<tr>
		<td><?php echo date("Y-m-d H:i:s",strtotime($row["date_time"])); ?></td>
		<td><?php echo retrieveName($db2,$row["username"]); // retrieved from functions/general functions ?></td>
		<td><?php echo $row["division"]; ?></td>
		</tr>
<?php
	}
?>
</tbody>
</table>
</td>
</tr>
</table>

<!--User Access Table-->
<?php
}
$_SESSION['printOut']=$documentDetails."<br>".$actionTitle.$actionHeading2.$actionTable;
?>