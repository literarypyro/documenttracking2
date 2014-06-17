<?php
session_start();
?>
<?php
	$db=retrieveRecordsDb();
	$sql="select * from document where status='FOR: GM APPROVAL'";
	
	$rs=$db->query($sql);
	$nm=$rs->num_rows;

	if(isset($_GET['dNL'])){
		if($nm>$_GET['dNL']){
			$nm=$_GET['dNL'];
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
<h2 class=""><a data-toggle="collapse" data-parent="#accordion" href="#collapseForApproval">Documents Needing Approval <?php echo "(".$nm.")"; ?></a></h2></th>

</td>

</tr>

</thead>
</table>
<div id="collapseForApproval" class="panel-collapse collapse">
<div class='panel-body'>

<table class='table table-striped' width=100%>
<thead>
<tr>
<th>Document Date</th>
<th>Subject</th>
<th>Reference Number</th>
<th>Latest Request/Action</th>
<th>Originating Office</th>
<th>Destination Office</th>
<th>Status</th>
<th>Download</th>
</tr>
</thead>
<tbody>
<?php
$routing_Option="<select name='reference_number'>";
for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
	$referenceNumber=calculateReferenceNumber($db,$row,adjustControlNumber($row['ref_id']));
	
	$sql2="select * from document_routing where reference_no='".$row['ref_id']."' order by request_date desc";
	$rs2=$db->query($sql2);
	$row2=$rs2->fetch_assoc();
	
	$sql3="select * from routing_targets where routing_id='".$row2['id']."'";
	$rs3=$db->query($sql3);
	$row3=$rs3->fetch_assoc();
	$action=getAction($db,$row3['action_id']);
	$routing_Option.="<option value='".$referenceNumber."'>".$row['subject']."</option>";

?>
	<tr>
		<td><?php echo $row['document_date']; ?></td>
		<td><?php echo $row['subject']; ?></td>
		<td><?php echo $referenceNumber; ?></td>
		<td><?php echo $action; ?></td>
		<td><?php echo $row['sending_office']; ?></td>
		<td><?php echo $row3['destination_office']; ?></td>
		<td><a style="color:red" href="document_history.php?view=<?php echo $row['ref_id']; ?>"><?php echo $row['status']; ?></a></td>
		<td><a style="color:red" href="download.php?refId=<?php echo $row['ref_id']; ?>">Link</a> </td>
	</tr>

<?php
}	

$routing_Option.="</select>";
?>
</table>
</tbody>
<div id="viewlink" align=right><a class="btn btn-default"  href='full_list.php?pp=3'>View More...</a></div>
<form action='submit.php' method=post>
<?php

	echo "<table>";
	echo "<tr>";
	echo "<th>Action to Document:</th>";
	echo "<td>";
	echo $routing_Option;

	echo "</td>";
	echo "<td><input class='btn btn-success' type=submit value='Reply' /></td>";
	

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
?>
<div class="panel-group" id="accordion">

<div class="panel panel-default">
<table class="table table-striped table-hover"  width=100% >
<thead>
<tr>
<td>
<h2 class=""><a data-toggle="collapse" data-parent="#accordion" href="#collapseForApproval">Documents Needing Approval </a></h2></th>

</td>

</tr>

</thead>
</table>
<div id="collapseForApproval" class="panel-collapse collapse">
<div class='panel-body'>

<table  class='table table-striped' width=100%>
<thead>
<tr>
<th>Document Date</th>
<th>Subject</th>
<th>Reference Number</th>
<th>Latest Request/Action</th>
<th>Originating Office</th>
<th>Destination Office</th>
<th>Status</th>
<th>Download</th>
</tr>
</thead>
<tbody>
<tr><td colspan=8>
<?php

	echo "There are no present documents needing approval.";
	?>
</td></tr>
</tbody>
</table>
</div>
</div>
</div>
</div>
<?php
}

?>