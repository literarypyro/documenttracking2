<?php
session_start();
?>
<?php
ini_set("date.timezone","Asia/Kuala_Lumpur");
require("functions/document functions.php");
require("db.php");

if(isset($_POST['confirmation'])){
//	$db=retrieveRecordsDb();
//	$sql="insert into download(ref_id,department_code,download_time) values ('".$_POST['confirmation']."','".$_SESSION['department']."','".date("Y-m-d H:i:s")."')";
//	$rs=$db->query($sql);
	

}
if(isset($_GET['refId'])){
	$db=connectDb();
	recordDownloadAccess($db,$_GET['refId'],$_SESSION['username'],$_SESSION['department']);

	$sql="select * from document_receipt where document_id=".($_GET['refId']*1)."";
	$rs=$db->query($sql);
	$row=$rs->fetch_assoc();
	$link=$row['upload_link'];	
}
echo "Your download will begin shortly.";
echo "<br>";
echo "<script language='javascript'>window.open('".$link."');</script>";

echo "If the download does not begin, <br>please right click <a href='".$link."'>here</a> and choose Save As.";

echo "<br><br>";
echo "<form action='download.php' method=post>";
echo "<input type=hidden name='confirmation' value='".($_GET['refId']*1)."'>";
echo "<input type=submit value='Confirm Receipt of Document' />";
echo "</form>";

?>