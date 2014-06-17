<?php
session_start();
?>
<?php
$date=date("Y-m-d");
?>
<?php
//$db=retrieveRecordsDb();


$sql="select * from routing_targets inner join document_routing on routing_targets.routing_id=document_routing.id where status in ('ISSUED AND SENT','CLOSED') and destination_office in ('".$_SESSION['department']."','ALL OFFICERS') and request_date like '".$date."%%'";

$rs=$db->query($sql);
$nm=$rs->num_rows;
if(isset($_GET['dFO'])){
	if($nm>$_GET['dFO']){
		$nm=$_GET['dFO'];
	}
}

if(isset($dFO)){
	if($nm>$dFO){
		$nm=$dFO;
	}
}

$db2=retrieveRecordsDb();


if($nm>0){

?>

<div class="panel-group" id="accordion">

<div class="panel panel-default">
<table class="table table-striped table-hover table-bordered"  width=100% >
<thead>
<tr>
<td>
<h3 class=""><a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Office Orders Issued Today <?php echo "(".$nm.")"; ?></a></h3></th>

</td>

</tr>

</thead>
</table>
<div id="collapseOne" class="panel-collapse collapse">
<div class='panel-body'>

<table style='font-size:13px;'  class="table table-striped table-hover table-bordered"  width=100% >
<thead>
<tr>
<th>Subject</th>
<th>Classification</th>
<th>Document Date</th>
<th>Originating Office</th>
<th>Information</th>
</tr>
</thead>

<tbody>
<?php
$routing_Option="<select class='form-control' name='reference_number'>";
for($i=0;$i<$nm;$i++){
$row=$rs->fetch_assoc();
$row2=getDocumentDetails($db,$row['reference_no']);
$reference=calculateReferenceNumber($db2,$row2,adjustControlNumber($row['reference_no']));	

?>
<tr>
<td><?php echo $row2['subject']." ".$reference; ?> <a style="color:red" href='download.php?refId=<?php echo $row2['ref_id']; ?>' target='window'>[Link]</a></td>
<td>Office Order</td>
<td><?php echo date("Y-m-d",strtotime($row2['document_date'])); ?></td>
<td><?php echo getOriginatingOffice($db,$row2['originating_office']); ?></td>
<td><a href='document_history.php?view=<?php echo $row2['ref_id']; ?>'>See Document History</a></td>
</tr>
<?php
	if($row['status']=="CLOSED"){
	}
	else {
	$routing_Option.="<option value='".$reference."'>".$row2['subject']."</option>";
	}
}
$routing_Option.="</select>";
?>
</tbody>
</table>
<div id="viewlink" align=right><a  class="btn btn-default" href='full_list.php?ts=1'>View More...</a></div>

<form action='submit.php' method=post>
<?php

	echo "<table>";
	echo "<tr>";
	echo "<th>Action to Document:</th>";
	echo "<td>";
	echo $routing_Option;

	echo "</td>";
	echo "<td>";
	echo "<select class='form-control' name='actionToTake'>";
	echo "<option value='REPLY'>Reply</option>";
	echo "<option value='FORWARD'>Forward as an Outgoing Document</option>";
	echo "<option value='CLOSE'>Close Document/Acknowledge Receipt</option>";
	echo "</select>";
	
	
	echo "</td>";
	
	
	echo "<td><input class='btn btn-success' type=submit value='Process' /></td>";
	

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
<table class="table table-striped table-hover table-bordered"  width=100% >
<thead>
<tr>
<td>
<h3 class=""><a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Office Orders Issued Today</a></h3></th>

</td>

</tr>

</thead>
</table>
<div id="collapseOne" class="panel-collapse collapse">
<div class='panel-body'>


<table  style='font-size:13px;' class="table table-striped table-hover table-bordered"  width=100% >
<thead>
<tr>
<th>Subject</th>
<th>Classification</th>
<th>Document Date</th>
<th>Originating Office</th>
<th>Information</th>
</tr>
</thead>
<tbody>
<tr><td colspan=5>
<?php
	echo "No Office Orders were issued today.";

}
?>
</td>
</tr>
</tbody>
</table>

</div>
</div>

</div>
</div>


