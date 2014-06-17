<?php
session_start();
?>
<?php
//$db=retrieveRecordsDb();
$sql="select * from document inner join document_routing on document.id=document_routing.reference_id where sending_office='".$_SESSION['department']."' and (status in ('FORWARDED','SENT','AWAITING REPLY','FOR: GM APPROVAL','FOR: CLARIFICATION') or status like '%CLOSED%%') and document_type='OUT' order by request_date desc";
$rs=$db->query($sql);
$nm=$rs->num_rows;

if(isset($_GET['St'])){
	if($nm>$_GET['St']){
		$nm=$_GET['St'];
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





<table style='font-size:13px;'  class='table table-striped table-hover table-bordered'  width=100%>
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
		
?>
		<tr>
			<td><?php echo $row['sending_office']; ?></td>
			<td><?php echo $row['document_date']; ?></td>
			<td><?php echo $row['subject']; ?></td>
			<td><?php echo $referenceNumber; ?></td>
			<td><?php echo $action; ?></td>
			<td><?php if($row2['request_date']==""){ echo ""; } else { echo date("Y-m-d H:i:s",strtotime($row2['request_date'])); } ?></td>
			<td><a href='document_history.php?view=<?php echo $row['ref_id']; ?>'><?php echo $row['status']; ?></a></td>
			<td><a href='download.php?refId=<?php echo $row['ref_id']; ?>'  target='window'>[Link]</a></td>
			
		</tr>
<?php	
	}
	
?>


</table>
</tbody>
<div id="viewlink" align=right><a  class="btn btn-default" href='full_list.php?pp=1&iN=0'>View More...</a></div>

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
<h3 class=""><a data-toggle="collapse" data-parent="#accordion" href="#collapseSent">Documents Recently Sent</a></h3></th>

</td>

</tr>

</thead>
</table>
<div id="collapseSent" class="panel-collapse collapse">
<div class='panel-body'>



<table style='font-size:13px;'  class='table table-striped table-hover table-bordered'  width=100%>
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
<tr>

<td colspan=8>You have not sent out any documents.
</td>
</tr>
</tbody>
</table>

</div>
</div>
</div>
</div>
<?php

//	echo "You have not sent out any documents.";

}
?>

