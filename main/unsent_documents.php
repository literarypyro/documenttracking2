<?php
//$db=retrieveRecordsDb();
$rs=sortDocument($db,"FOR: ROUTING",$_SESSION['department']);
$nm=$rs->num_rows;
if(isset($_GET['iR'])){
	if($nm>$_GET['iR']){
		$nm=$_GET['iR'];
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
<h3 class=""><a data-toggle="collapse" data-parent="#accordion" href="#collapseNotRouted">Incoming Documents not Routed <?php echo "(".$nm.")"; ?></a></h3></th>

</td>

</tr>

</thead>
</table>
<div id="collapseNotRouted" class="panel-collapse collapse">
<div class='panel-body'>


<table style='font-size:13px;'  class='table table-striped table-hover table-bordered' width=100%>
<thead>
<tr>
<th>Office Code</th>
<th>Document Date</th>

<th>Subject</th>
<th>Status</th>
</tr>
</thead>
<tbody>
<?php


$routing_Option="<select class='form-control' name='reference_number'>";


for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
	$document_type=getDocumentType($db,$row['document_type']);
	$docId=$row['ref_id'];
	$reference_number=calculateReferenceNumber($db,$row,adjustControlNumber($docId));	
?>
<tr>
<td><?php echo $row['sending_office']; ?></td>
<td><?php echo date("Y-m-d",strtotime($row['document_date'])); ?></td>
<td><a href='submit.php?reference_number=<?php echo $reference_number; ?>'><?php echo $row['subject']; ?></a></td>
<td>Incomplete</td>
</tr>
<?php


	$routing_Option.="<option value='".$reference_number."'>".$row['subject']."</option>";



}
$routing_Option.="</select>";
?>
</tbody>
</table>
<div id="viewlink" align=right><a  class="btn btn-default" href='full_list.php?pp=2&fL=0'>View More...</a></div>
<!--
<form valign=top action='submit.php' method='post'>
-->
<?php
/*	echo "<table>";
	echo "<tr>";
	echo "<th>Action to Document:</th>";
	echo "<td>";
	echo $routing_Option;

	echo "</td>";
	echo "<td><input class='btn btn-success' type=submit value='Process' /></td>";
	

	echo "</tr>";
	echo "</table>";
	*/
?>
<!--</form>
-->

</div>
</div>
</div>
</div>
<br>
<?php
}

else {
	if(isset($_GET['iR'])){
	}
	else {
	?>
<div class="panel-group" id="accordion">

<div class="panel panel-default">
<table class="table table-striped table-hover table-bordered"  width=100% >
<thead>
<tr>
<td>
<h3 class=""><a data-toggle="collapse" data-parent="#accordion" href="#collapseNotRouted">Incoming Documents not Routed <?php echo "(".$nm.")"; ?></a></h3></th>

</td>

</tr>

</thead>
</table>
<div id="collapseNotRouted" class="panel-collapse collapse">
<div class='panel-body'>
<table style='font-size:13px;'  class='table table-striped table-hover table-bordered' width=100%>
<thead>
<tr>
<th colspan=7><h2>Incoming Documents not Routed</h2></th>
</tr>
<tr>
<th>Office Code</th>
<th>Document Date</th>

<th>Subject</th>
<th>Status</th>
</tr>
</thead>
<tbody>
<tr><td colspan=4>
<?php
	echo "All Documents have been properly sent/routed. <br>";
?>
</td>
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
$rs=sortDocument($db,"FOR: UPLOAD",$_SESSION['department']);
$nm=$rs->num_rows;

if(isset($_GET['fL'])){
	if($nm>$_GET['fL']){
		$nm=$_GET['fL'];
	}
}

if($nm=="On the Bayou"){



?>

<div class="panel-group" id="accordion">

<div class="panel panel-default">
<table class="table table-striped table-hover table-bordered"  width=100% >
<thead>
<tr>
<td>
<h3 class=""><a data-toggle="collapse" data-parent="#accordion" href="#collapseForUpload">Documents for Upload <?php echo "(".$nm.")"; ?></a></h3></th>

</td>

</tr>

</thead>
</table>
<div id="collapseForUpload" class="panel-collapse collapse">
<div class='panel-body'>


	<table style='font-size:13px;'  class='table table-striped table-hover table-bordered' width=100%>
	<thead>
	<tr>
	<th>Office Code</th>
	<th>Document Date</th>
	<th>Subject</th>
	<th>Document Type</th>
	</tr>
	</thead>
	<tbody>
	<?php


	$routing_Option="<select class='form-control'  name='updateFile'>";


	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		$document_type=getDocumentType($db,$row['document_type']);
		$docId=$row['ref_id'];
		
	
	
	?>
		<tr>
		<td><?php echo $row['sending_office']; ?></td>
		<td><?php echo date("Y-m-d",strtotime($row['document_date'])); ?></td>
		<td><a href='submit.php?updateFile='<?php echo $docId; ?>'><?php echo $row['subject']; ?></a></td>
		<!--<td>For Upload</td>-->
		<td><?php echo $document_type; ?></td>
		</tr>
	<?php

			$routing_Option.="<option value='".$docId."'>".$row['subject']."</option>";
	}
	$routing_Option.="</select>";
	?>

	</tbody>
	</table>
	<table width=100%>
	<tr>
	<td id="viewlink" align=right><a  class="btn btn-default" href='full_list.php?pp=2&iR=0'>View More...</a></td>
	</tr>
	</table>
	<form action='uploadDocument.php' method=post>
	<?php
		echo "<table>";
		echo "<tr>";
		echo "<th>Upload Document:</th>";
		echo "<td>";
		echo $routing_Option;

		echo "</td>";
		echo "<td><input class='btn btn-success' type=submit value='Update' /></td>";
		

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
//	echo "All Documents have been uploaded. <br>";
}
?>