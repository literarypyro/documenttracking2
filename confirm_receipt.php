<?php
session_start();
?>
<?php
require("db_page.php");
?>
<?php
$db=retrieveRecordsDb();

if(isset($_POST['keycode'])){
	
	$sql="select * from routing_targets where id='".$_GET['target_id']."'";
	$rs=$db->query($sql);
		
	$row=$rs->fetch_assoc();
	$keycode=$row['keycode'];
		
	if($keycode==$_POST['keycode']){
		$update_time=date("Y-m-d H:i:s");
		
		$update="insert into routing_receipt(target_id,receive_time,division) values ";
		$update.="('".$_POST['target_id']."','".$update_time."','".$_SESSION['department']."')";
		$updateRS=$db->query($update);
		
//		$_SESSION['reference_number']==$_POST['doc_id'];
//		header("Location: routing report.php");
		echo "Document received";	
		echo "<script language='javascript'>window.opener.location=\"receiveDocument.php?pp=1a&iN=10&St=10\";</script>";
		

	}	
	else {
		echo "Invalid Code.  Document not Received.";
		
		
	}
}






$sql="select * from routing_targets where id='".$_GET['target_id']."' limit 1";
$rs=$db->query($sql);

$row=$rs->fetch_assoc();

$code_key=$row['keycode'];
?>
<form action='confirm_receipt.php?target_id=<?php echo $_GET['target_id']; ?>&doc_id=<?php echo $_GET['doc_id']; ?>' method='post'>
<table border=1px style='border-collapse:collapse;' width=40%>
<tr><th>Confirm Receipt of Document</th></tr>
<tr>
<td align=center>
<img src='barcodegen/test_1D.php?text=<?php echo $code_key; ?>' />

</td>
</tr>
<tr>
<td align=center>
	Enter Key Code: <input type='text' name='keycode' id='keycode' />

</td>
</tr>
<tr>
	<td align=center><input type='submit' value='Submit'><input type=hidden name='target_id' value='<?php echo $_GET['target_id']; ?>' /><input type=hidden name='doc_id' value='<?php echo $_GET['doc_id']; ?>' /></td>
</tr>
</table>
</form>



