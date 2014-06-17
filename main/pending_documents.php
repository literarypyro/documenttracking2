<?php
session_start();
?>
<?php

?>

<?php
$db=retrieveRecordsDb();

$rs=receiveRoutingMessages($db,$_SESSION['department']);
$nm=$rs->num_rows;

if(isset($_GET['iN'])){
	if($nm>$_GET['iN']){
		$nm=$_GET['iN'];
	}
}

if(isset($iN)){
	if($nm>$iN){
		$nm=$iN;
	}
}
if($nm>0){
?>

<div class="panel-group" id="accordion">

<div class="panel panel-default">
<table class="table table-striped table-hover table-bordered"  width=100% >
<thead>
<tr>
<td>
<h3 class=""><a data-toggle="collapse" data-parent="#accordion" href="#collapsePending">Documents Needing Reply <?php echo "(".$nm.")"; ?></a></h3></th>

</td>

</tr>

</thead>
</table>
<div id="collapsePending" class="panel-collapse collapse">
<div class='panel-body'>
<table style='font-size:13px;'  class="table table-striped table-hover  display table-bordered" width=100%>
<thead>
<tr>
<th>Office Code</th>
<th>Document Date</th>
<th>Subject</th>
<th>Reference Number</th>
<th>Action Taken</th>
<th>Action Date</th>
<th>Request Status</th>
<th>Download</th>
</tr>
</thead>
<tbody>
<?php

$routing_Option="<select  class='form-control'  name='reference_number' id='reference_number'>";
$routing_Option.="<option data-received='false'></option>";

for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
	$documentRow=getDocumentDetails($db,$row['reference_no']);
	$reference_number=calculateReferenceNumber($db,$documentRow,adjustControlNumber($row['reference_no']));
	if(($row['status']=="PENDING")||($documentRow["status"]=="SENT")||($documentRow["status"]=="AWAITING REPLY")||(($documentRow["status"]=="FOR: CLARIFICATION")&&($row['status']=="NEEDING CLARIFICATION"))){
?>
<tr>
<td><?php echo $documentRow['sending_office']; ?></td>
<td><?php echo date("Y-m-d",strtotime($documentRow['document_date'])); ?></td>
<td>


<a href='#' onclick='window.open("confirm_receipt.php?target_id=<?php echo $row['id']; ?>&doc_id=<?php echo $documentRow['ref_id']; ?>")'>

<?php echo $documentRow['subject']; ?> 

</a>


</td>
<td><?php echo $reference_number; ?></td>
<td><?php echo getAction($db,$row['action_id']); ?></td>
<td><?php echo date("Y-m-d H:i:s",strtotime($row['request_date'])); ?></td>
<td><a href='document_history.php?view=<?php echo $row['reference_no']; ?>'><?php echo $row['status']; ?></a></td>
<td><a href='download.php?refId=<?php echo $row['reference_no']; ?>'  target='window'>[Link]</a></td>
</tr>
<?php
	$docId=$row['reference_no'];
	$isReceived=isReceived($db,$row['id'],$_SESSION['department']);
	
	$routing_Option.="<option data-isreceived='".$isReceived."' value='".$reference_number."'>".$documentRow['subject']."</option>";
	}


}
$routing_Option.="</select>";


?>
</tbody>
</table>
<div id="viewlink" align=right><a class="btn btn-default" href='full_list.php?pp=1a&St=0'>View More...</a></div>
<form action='submit.php' method=post>
<?php

	echo "<table align=center>";
	echo "<tr>";
	echo "<th>Action to Document:</th>";
	echo "<td>";
	echo $routing_Option;

	echo "</td>";
	echo "<td>";
	echo "<select name='actionToTake' class='form-control'>";
	echo "<option value='REPLY'>Respond</option>";
	echo "<option value='FORWARD'>Forward as an Outgoing Document</option>";
	echo "<option value='CLOSE'>Close Document</option>";
	echo "</select>";
	
	
	echo "</td>";
	
	
	echo "<td><input disabled name='process_button' id='process_button' class='btn btn-success' type=submit value='NA' /></td>";
	

	echo "</tr>";
	echo "</table>";
?>
</form>
</div>
</div>
</div>

</div>

<br>

<?php

}
else {

	if(isset($_GET['iN'])){
	}
	else {
//	else if($a=="@"){

?>
<div class="panel-group" id="accordion">

<div class="panel panel-default">
<table class="table table-striped table-hover table-bordered"  width=100% >
<thead>
<tr>
<td>
<h3 class=""><a data-toggle="collapse" data-parent="#accordion" href="#collapsePending">Documents Needing Reply</a></h3></th>

</td>

</tr>

</thead>
</table>
<div id="collapsePending" class="panel-collapse collapse">
<div class='panel-body'>
<table  style='font-size:13px;'  class="table table-striped table-hover table-bordered"  width=100%>
<thead>
<tr>
<th>Office Code</th>
<th>Document Date</th>
<th>Subject</th>
<th>Reference Number</th>
<th>Action Taken</th>
<th>Action Date</th>
<th>Request Status</th>
<th>Download</th>
</tr>
</thead>
<tbody>
<tr><td colspan=8>
<?php
echo "You have no pending documents.";
?></td></tr>
</tbody>
</table>
</div>
</div>
</div>
</div>
<hr>
<?php
	}
}
?>
<?php
$db=retrieveRecordsDb();
$sql="select * from document where sending_office='".$_SESSION['department']."' and (status in ('FORWARDED','SENT','AWAITING REPLY','FOR: GM APPROVAL','FOR: CLARIFICATION') or status like '%CLOSED%%') and document_type in ('IN','MEMO') order by document_date desc";
$rs=$db->query($sql);
$nm=$rs->num_rows;

if(isset($_GET['St'])){
	if($nm>$_GET['St']){
		$nm=$_GET['St'];
	}
}
if(isset($St)){
	if($nm>$St){
		$nm=$St;
	}
}


if($nm>0){
?>

<div class="panel-group" id="accordion">

<div class="panel panel-default">
<table class="table table-striped table-hover table-bordered"  width=100% >
<thead>
<tr>
<td>
<h3 class=""><a data-toggle="collapse" data-parent="#accordion" href="#collapseSent">Documents Recently Sent <?php echo "(".$nm.")"; ?></a></h3></th>

</td>

</tr>

</thead>
</table>
<div id="collapseSent" class="panel-collapse collapse">
<div class='panel-body'>


<table style='font-size:13px;'  class="table table-striped table-hover table-bordered"  width=100%>
<thead>
<tr>
<th>Office Code</th>
<th>Document Date</th>
<th>Subject</th>
<th>Reference Number</th>
<th>Action Taken</th>
<th>Action Date</th>
<th>Status</th>
<th>Download</th>
</tr>
</thead>
<tbody>
<?php
$routing_Option="<select class='form-control' name='fwdReferenceNumber'>";

	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		$referenceNumber=calculateReferenceNumber($db,$row,adjustControlNumber($row['ref_id']));
		
		$sql2="select * from document_routing where reference_no='".$row['ref_id']."' order by request_date desc";
		$rs2=$db->query($sql2);
		
		$row2=$rs2->fetch_assoc();
		
		$sql3="select * from routing_targets where routing_id='".$row2["id"]."'";
		$rs3=$db->query($sql3);
		
		$row3=$rs3->fetch_assoc();
		$action=getAction($db,$row3['action_id']);
		
		$statusDoc=$row['status'];
		
		if($statusDoc=="FORWARDED"){
			$sqlStatus="select * from forward_copy where reference_number='".$reference_number."'";
			$rsStatus=$db->query($sqlStatus);
			$rowStatus=$rs->fetch_assoc();
			$statusDoc.=": ".$rowStatus['to_department'];
		
		
		}
		
?>
		<tr>
			<td><?php echo $row['sending_office']; ?></td>
			<td><?php echo $row['document_date']; ?></td>
			<td><?php echo $row['subject']; ?></td>
			<td><?php echo $referenceNumber; ?></td>
			<td><?php echo $action; ?></td>
			<td><?php if($row2['request_date']==""){ echo ""; } else { echo date("Y-m-d H:i:s",strtotime($row2['request_date'])); } ?></td>
			<td><a href='document_history.php?view=<?php echo $row['ref_id']; ?>'><?php echo $statusDoc; ?></a></td>
			<td><a href='download.php?refId=<?php echo $row['ref_id']; ?>'  target='window'>[Link]</a></td>
			
		</tr>
		
		<?php	
		$routing_Option.="<option value='".$referenceNumber."'>".$row['subject']."</option>";

	}
	$routing_Option.="</select>";

	?>

</tbody>
</table>
<div id="viewlink" align=right><a   class="btn btn-default" href='full_list.php?pp=1a&iN=0'>View More...</a></div>
<form action='submit.php' method=post>
<?php

	echo "<table align=center>";
	echo "<tr>";
	echo "<th>Action to Document:</th>";
	echo "<td>";
	echo $routing_Option;
	echo "</td>";
	echo "<td>";
	echo "<select name='actionToTake' class='form-control'>";
	echo "<option value='FORWARD'>Forward a Copy</option>";
	echo "</select>";
	echo "</td>";
	
	
	echo "<td><input  class='btn btn-success'  type=submit value='Process' /></td>";
	

	echo "</tr>";
	echo "</table>";
?>
	</form>
	
	</div>
	</div>
	</div>
	</div>
	
<?php
}
else {
//	echo "You have not sent out any documents.";

}
?>

