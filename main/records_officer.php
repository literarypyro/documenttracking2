<?php
$db=retrieveRecordsDb();
$rs=topActiveDocuments($db);
?>
<?php
$nm=$rs->num_rows;

if(isset($_GET['AMd'])){
	if($nm>$_GET['AMd']){
		$nm=$_GET['AMd'];
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
<h3 class=""><a data-toggle="collapse" data-parent="#accordion" href="#collapseActiveDocs">Most Active Documents <?php echo "(".$nm.")"; ?></a></h3></th>

</td>

</tr>

</thead>
</table>
<div id="collapseActiveDocs" class="panel-collapse collapse">
<div class='panel-body'>


<table style='font-size:13px;' class='table table-striped table-bordered' width=100%>
<thead>
<tr>
<th>Office Code</th>
<th>Document Date</th>
<th>Subject</th>
<th>Reference Number</th>
<th>Status</th>
<th>Download</th>
</tr>
</thead>
<tbody>
<?php
	for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
?>
<tr>
	<td><?php echo $row['sending_office']; ?></td>
	<td><?php echo $row['document_date']; ?></td>
	<td><?php echo $row['subject']; ?></td>
	<td><?php echo calculateReferenceNumber($db,$row,adjustControlNumber($row['ref_id'])); ?></td>
	<td><a href="document_history.php?view=<?php echo $row['ref_id']; ?>" ><?php echo $row['status']; ?></a></td>
	<td><a href="download.php?refId=<?php echo $row['ref_id']; ?>" target='window'>[Link]</a></td>
</tr>
<?php
	}
?>
</tbody>
</table>
<div id="viewlink" align=right><a  class="btn btn-default" href='full_list.php?pp=4&RDm=0'>View More...</a></div>
</div>
</div>
</div>
</div>

<br>
<?php
}
else {
//	echo "There are no available documents that have been sent or received.<br>";

}
?>
<?php
$sql="select * from document order by receive_date desc";
$rs=$db->query($sql);
$nm=$rs->num_rows;
if(isset($_GET['RDm'])){
	if($nm>$_GET['RDm']){
		$nm=$_GET['RDm'];
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
<h3 class=""><a data-toggle="collapse" data-parent="#accordion" href="#collapseRecent">Most Recent Documents <?php echo "(".$nm.")"; ?></a></h3></th>

</td>

</tr>

</thead>
</table>
<div id="collapseRecent" class="panel-collapse collapse">
<div class='panel-body'>
<table style='font-size:13px;' class='table table-striped table-bordered' width=100%>
<thead>

<tr>
<th>Office Code</th>
<th>Document Date</th>
<th>Subject</th>
<th>Reference Number</th>
<th>Status</th>
<th>Download</th>
</tr>
</thead>
<tbody>
<?php 
for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
?>
	<tr>
	<td><?php echo $row['sending_office']; ?></td>
	<td><?php echo $row['document_date']; ?></td>
	<td><?php echo $row['subject']; ?></td>
	<td><?php echo calculateReferenceNumber($db,$row,adjustControlNumber($row['ref_id'])); ?></td>
	<td><a href="document_history.php?view=<?php echo $row['ref_id']; ?>" ><?php echo $row['status']; ?></a></td>
	<td><a href="download.php?refId=<?php echo $row['ref_id']; ?>" target='window'>[Link]</a></td>
	</tr>

<?php
}
?>
</tbody>
</table>
	<div id="viewlink" align=right><a  class="btn btn-default"  href='full_list.php?pp=4&AMd=0'>View More...</a></div>
</div>
</div>
</div>
</div>
<?php
}
else 
{
//	echo "There are no existing documents issued or received.";

}




?>
<form valign=top action='receiveDocument.php?pp=4&RDm=<?php echo $_GET['RDm']; ?>&AMd=<?php echo $_GET['AMd']; ?>#RECORDS' method='post'>
<?php
	echo "<table>";
	echo "<tr>";
	echo "<th>Select Division</th>";
	echo "<td>";
	echo "<select class='form-control' name='divisionActive'>";
	$db=retrieveRecordsDb();
	$sql="select * from department";
	
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		echo "<option value='".$row['department_code']."'>".$row['department_name']."</option>";
	}

	echo "</select>";
	echo "</td>";
	echo "<td><input class='btn btn-success' type=submit value='Get Most Active' /></td>";
	

	echo "</tr>";
	echo "</table>";
?>
</form>
<?php
if(isset($_POST['divisionActive'])){

	$department=getDepartment($db,$_POST['divisionActive']);
	$db=retrieveRecordsDb();
	$rs=topActiveDocumentsDivision($db,$_POST['divisionActive']);
	$nm=$rs->num_rows;

	if($nm>0){
?>

	<table style='font-size:13px;' class='table table-striped table-bordered'  width=100%>
	<thead>
	<tr>
	<th colspan=8><h3>Active Documents for <?php echo $department; ?></h3></th>
	</tr>
	<tr>
	<th>Office Code</th>
	<th>Document Date</th>
	<th>Subject</th>
	<th>Reference Number</th>
	<th>Status</th>
	<th>Download</th>
	</tr>
	</thead>
	<tbody>
<?php
		for($i=0;$i<$nm;$i++){	
		$row=$rs->fetch_assoc();
		$referenceIndex=calculateReferenceNumber($db,$row,adjustControlNumber($row['ref_id']));
?>
		<tr>
			<td><?php echo $row['sending_office']; ?></td>
			<td><?php echo $row['document_date']; ?></td>
			<td><?php echo $row['subject']; ?></td>
			<td><?php echo $referenceIndex; ?></td>
			<td><a href="document_history.php?view=<?php echo $row['ref_id']; ?>"><?php echo $row['status']; ?></a></td>
			<td><a href="download.php?refId=<?php echo $row['ref_id']; ?>" target='window'>[Link]</a></td>
		</tr>
<?php
		}
?>
	</tbody>
	</table>
	
<?php	
	}
	else {
	//	echo "There are no active documents for the ".$department;
	}
}
?>