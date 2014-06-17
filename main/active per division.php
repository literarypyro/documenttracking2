<?php
session_start();
?>
<?php
require("db_page.php");
require("../functions/general functions.php");
if(isset($_GET['activeMonitoring'])){
	$db=retrieveRecordsDb();
	$sql="select * from document where sending_office='".$_GET['activeMonitoring']."' order by receive_date";
	$rs=$db->query($sql);
}
?>
<table style='font-size:13px;'   border=1 width=100%>
<tr>
<th colspan=7><h3>Most Recent Documents for Division</h3></th>
</tr>
<tr>
<th>Office Code</th>
<th>Document Date</th>
<th>Subject</th>
<th>Reference Number</th>
<th>Status</th>
<th>Download</th>
</tr>
<?php
$nm=$rs->num_rows;
for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
?>
<tr>
	<td><?php echo $row['sending_office']; ?></td>
	<td><?php echo $row['document_date']; ?></td>
	<td><?php echo $row['subject']; ?></td>
	<td><?php echo adjustControlNumber($row['ref_id']); ?></td>
	<td><?php echo $row['status']; ?></td>
	<td><a style='color:red' href='download.php?refId=<?php echo $row['ref_id']; ?>'>Link</a></td>
</tr>
<?php
}
?>
</table>