<?php
session_start();
?>
<title>Outgoing Documents</title>
<?php
require("functions/form functions.php");
//Documents Approved and Released
$db=retrieveRecordsDb();
$sql="select * from document where document_type='OUT' and status='SENT' order by (select request_date from document_routing where reference_no=document.ref_id order by request_date desc limit 1) desc";
$rs=$db->query($sql);
$nm=$rs->num_rows;

if(isset($_GET['dtg'])){
	if($nm>$_GET['dtg']){
		$nm=$_GET['dtg'];
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
<h3 class=""><a data-toggle="collapse" data-parent="#accordion" href="#collapseApprovedRelease">Documents Approved for Release <?php echo "(".$nm.")"; ?></a></h3></th>

</td>

</tr>

</thead>
</table>
<div id="collapseApprovedRelease" class="panel-collapse collapse">
<div class='panel-body'>

<table style='font-size:13px;'   class='table table-striped table-hover table-bordered' width=100%>
<thead>
<tr>
<th>Document Date</th>
<th>Subject</th>
<th>Reference Number</th>
<th>Download</th>
</tr>
</thead>
<tbody>
<?php
$routing_Option="<select class='form-control' name='forwardDocument'>";

for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
	$routing_Option.="<option>".calculateReferenceNumber($db,$row,adjustControlNumber($row['ref_id']))."</option>";
?>
	<tr>
	<td><?php echo $row['document_date']; ?></td>
	<td><?php echo $row['subject']; ?></td>
	<td><?php echo calculateReferenceNumber($db,$row,adjustControlNumber($row['ref_id'])); ?></td>
	<td><a  href="download.php?refId=<?php echo $row['ref_id']; ?>" target='window' >[Link]</a></td>
	</tr>
<?php
}
$routing_Option.="</select>";
?>	
</tbody>
</table>
<div id="viewlink" align=right><a  class="btn btn-default" href='full_list.php?ts=5&ftg=0'>View More...</a></div>
<form action='submit.php' method=post>
<?php

	echo "<table>";
	echo "<tr>";
	echo "<th>Send a Copy:</th>";
	echo "<td>";
	echo $routing_Option;

	echo "</td>";
	echo "<td>";
	$deptSQL="select * from department";
	$deptRs=$db->query($deptSQL);
	echo "<select class='form-control' name='forwardToDept'>";
	$deptNm=$deptRs->num_rows;
	for($i=0;$i<$deptNm;$i++){
		$deptRow=$deptRs->fetch_assoc();
		echo "<option value='".$deptRow['department_code']."'>".$deptRow['department_name']."</option>";
	
	}
	
	echo "</select>";

	
	echo "</td>";
	
	
	echo "<td><input class='btn btn-success' type=submit value='Send' /></td>";
	

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
	if(isset($_GET['dtg'])){
	}
	else {

?>


<div class="panel-group" id="accordion">

<div class="panel panel-default">
<table class="table table-striped table-hover table-bordered"  width=100% >
<thead>
<tr>
<td>
<h3 class=""><a data-toggle="collapse" data-parent="#accordion" href="#collapseApprovedRelease">Documents Approved for Release</a></h3></th>

</td>

</tr>

</thead>
</table>
<div id="collapseApprovedRelease" class="panel-collapse collapse">
<div class='panel-body'>


<table style='font-size:13px;'   class='table table-striped table-hover table-bordered' width=100%>
<thead>
<tr>
<th>Document Date</th>
<th>Subject</th>
<th>Reference Number</th>
<th>Download</th>
</tr>
</thead>
<tbody>
<tr>
<td colspan=8>No documents available</td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
</div>

<?php
	}
}
?>
<?php
$db=retrieveRecordsDb();
$sql="select * from forward_copy where document_type='OUT' order by forward_date desc";

$rs=$db->query($sql);
$nm=$rs->num_rows;
//Forwarded Outgoing Copy
if(isset($_GET['ftg'])){
	if($nm>$_GET['ftg']){
		$nm=$_GET['ftg'];
	}
}
if($nm>0){
?>
<div class="panel-group" id="accordion">

<div class="panel panel-default">
<table class="table table-striped table-hover"  width=100% >
<thead>
<tr>
<td>
<h3 class=""><a data-toggle="collapse" data-parent="#accordion" href="#collapseOutgoing1">Forwarded Outgoing Copy <?php echo "(".$nm.")"; ?></a></h3></th>

</td>

</tr>

</thead>
</table>

<div id="collapseOutgoing1" class="panel-collapse collapse">
<div class='panel-body'>


<table style='font-size:13px;' class='table table-striped table-hover table-bordered' width=100%>
<thead>
<tr>
<th>Sending Office Code</th>
<th>Document Date</th>
<th>Subject</th>
<th>Reference Number</th>
<th>Download</th>
</tr>
</thead>
<tbody>
<?php 
	for($i=0;$i<$nm;$i++){
	$row3=$rs->fetch_assoc();
	$documentRow=getDocumentDetails($db,getDocumentId($db,$row3['reference_number']));
?>
<tr>
	<td><a  href='forward report.php?forId=<?php echo $row3['id']; ?>'><?php echo $row3['forwarding_office']; ?></a></td>
	<td><?php echo $documentRow['document_date']; ?></td>
	<td><a  href='document_history.php?view=<?php echo $documentRow['ref_id']; ?>'><?php echo $documentRow['subject']; ?></a></td>
	<td><?php echo $row3['reference_number']; ?></td>
	<td><a  href="download.php?refId=<?php echo $documentRow['ref_id']; ?>" target='window'>[Link]</a></td>
</tr>
<?php
}
?>
</tbody>
</table>
<div id="viewlink" align=right><a  class="btn btn-default" href='full_list.php?ts=5&dtg=0'>View More...</a></div>

</div>
</div>
</div>
</div>
<?php
}
?>
<?php
$db=retrieveRecordsDb();
$sql="select * from forward_copy where forwarding_office='REC' order by forward_date desc";

$rs=$db->query($sql);
$nm=$rs->num_rows;
//Forwarded Outgoing Copy
if(isset($_GET['ftg'])){
	if($nm>$_GET['ftg']){
		$nm=$_GET['ftg'];
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
<h3 class=""><a data-toggle="collapse" data-parent="#accordion" href="#collapseOutgoing2">Copies of Outgoing Documents Sent Out <?php echo "(".$nm.")"; ?></a></h3></th>

</td>

</tr>

</thead>
</table>

<div id="collapseOutgoing2" class="panel-collapse collapse">
<div class='panel-body'>
<table style='font-size:13px;' class='table table-striped table-hover table-bordered' width=100%>
<thead>
<tr>
<th>Office Forwarded</th>
<th>Document Date</th>
<th>Subject</th>
<th>Reference Number</th>
<th>Download</th>
</tr>
</thead>
<tbody>
<?php 
	for($i=0;$i<$nm;$i++){
	$row3=$rs->fetch_assoc();
	$documentRow=getDocumentDetails($db,getDocumentId($db,$row3['reference_number']));
?>
<tr>
	<td><a  href='forward report.php?forId=<?php echo $row3['id']; ?>'><?php echo $row3['to_department']; ?></a></td>
	<td><?php echo $documentRow['document_date']; ?></td>
	<td><a  href='document_history.php?view=<?php echo $documentRow['ref_id']; ?>'><?php echo $documentRow['subject']; ?></a></td>
	<td><?php echo $row3['reference_number']; ?></td>
	<td><a  href="download.php?refId=<?php echo $documentRow['ref_id']; ?>" target='window'>[Link]</a></td>
</tr>
<?php
}
?>
</tbody>
</table>
<div id="viewlink" align=right><a  class="btn btn-default" href='full_list.php?ts=5&dtg=0'>View More...</a></div>

</div>
</div>
</div>
</div>


<?php
}
?>