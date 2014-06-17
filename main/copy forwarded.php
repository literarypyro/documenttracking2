<?php
session_start();
?>
<?php

$db=retrieveRecordsDb();
$sql="select * from forward_copy where document_type in ('MEMO','IN') and to_department='".$_SESSION['department']."' order by forward_date desc";

$rs=$db->query($sql);
$nm=$rs->num_rows;

if(isset($_GET['cInG'])){
	if($nm>$_GET['cInG']){
		$nm=$_GET['cInG'];
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
<h3 class=""><a data-toggle="collapse" data-parent="#accordion" href="#collapseCopiesReceived">Document Copies Received <?php echo "(".$nm.")"; ?></a></h3></th>

</td>

</tr>

</thead>
</table>
<div id="collapseCopiesReceived" class="panel-collapse collapse">
<div class='panel-body'>




<table style='font-size:13px;'  class='table table-striped table-hover table-bordered' width=100%>
<thead>
<tr>
<th>Document Date</th>
<th>Subject</th>
<th>Reference Number</th>
<th>Document Information</th>
</tr>
</thead>
<tbody>
<?php 
	for($i=0;$i<$nm;$i++){
	$row3=$rs->fetch_assoc();
	$documentRow=getDocumentDetails($db,getDocumentId($db,$row3['reference_number']));
?>
	<tr>
	<td><?php echo $documentRow['document_date']; ?></td>
	<td><a href='document_history.php?view=<?php echo getDocumentId($db,$row3['reference_number']); ?>'><?php echo $documentRow['subject']; ?></a></td>
	<td><?php echo $row3['reference_number']; ?></td>
	<td><a href='forward report.php?forId=<?php echo $row3['id']; ?>'>[Link]</a></td>
	</tr>
<?php
	}
?>
</tbody>
</table>
<div id="viewlink" align=right><a  class="btn btn-default" href='full_list.php?pp=5'>View More...</a></div>
</div>
</div>
</div>
</div>
<?php
}
else {
?>
<div class="panel-group" id="accordion">

<div class="panel panel-default">
<table class="table table-striped table-hover table-bordered"  width=100% >
<thead>
<tr>
<td>
<h3 class=""><a data-toggle="collapse" data-parent="#accordion" href="#collapseCopiesReceived">Document Copies Received</a></h3></th>

</td>

</tr>

</thead>
</table>
<div id="collapseCopiesReceived" class="panel-collapse collapse">
<div class='panel-body'>

<table style='font-size:13px;'  class='table table-striped table-hover table-bordered'  width=100%>
<thead>
<tr>
<th>Document Date</th>
<th>Subject</th>
<th>Reference Number</th>
<th>Document Information</th>
</tr>
</thead>
<tbody>
<tr>
<td colspan=4>No document copies found.</td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
</div>
<?php

}


?>
