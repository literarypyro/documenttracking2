<?php
session_start();
?>
<?php
	require("db_page.php");
	require("functions/routing process.php");
	require("functions/general functions.php");
	require("functions/document functions.php");
	require("title.php");
	$_SESSION['page']="forward report.php";
?>
	<LINK href="css/program design 2.css" rel="stylesheet" type="text/css">
	<body>
	<div align=right><a href='receiveDocument.php'>Go Back to Main Page</a></div>
	
	
<?php	
?>
	<!--Heading Table-->
	<?php 
	$headingTable="
	<table align=center width=100%>
	<tr><th colspan=2>(FORWARDED COPY)</th>
	</tr>
	</table>";	
	$db=retrieveRecordsDb();

	$forwardId=$_GET['forId'];
	$sql="select * from forward_copy where id='".$_GET['forId']."'";
	
	$rs=$db->query($sql);
	$forRow=$rs->fetch_assoc();
	
	$ref_no=getDocumentId($db,$forRow['reference_number']);
	$row=getDocumentDetails($db,$ref_no);
	$dStatus=$row['status'];
	$referenceLabel=$forRow['reference_number'];
	
	
	//Document Details for Below
	$originating_office=getOriginatingOffice($db,$row['originating_office']);
	$document_type=$row['document_type'];
	$receive_date=date("F d, Y, h:iA",strtotime($row['receive_date']));
	$document_date=date("F d, Y, h:iA",strtotime($row['document_date']));
	$subject=$row['subject'];

	$routing_id=$ref_no;
	$headingTable.="	
	<table  width=100%>
	<tr width=100%>
		<td width=50%>&nbsp;</td>
		<td width=50% align=right>Reference Number: ".$referenceLabel."</td>
	</tr>
	</table>";
	echo $headingTable;
	?>
	<!--Heading Table-->
	<!--Document Details Table-->
	<?php
	$documentDetailsTable="
	<table id='csstable' border=1 width=100%>
	<tr>
		<th colspan=2 >ORIGINATING OFFICE</th>
		<th colspan=2>".$originating_office."</th>
	</tr>
	<tr>
		<td colspan=2 align=center valign=center rowspan=2><b>Subject: ".$subject."</b></td>
		<td colspan=2><b>Date of Document: </b>".$document_date."</td>
	</tr>
	<tr>
		<td colspan=2>
			<b>Date/Time";  
		if($document_type=="OUT"){
		$documentDetailsTable.="
			Sent for Approval: "; 
		}
		else {
		$documentDetailsTable.="
			Received:  ";
		}	
		$documentDetailsTable.="
			</b>".$receive_date."
		</td>

	</tr>
	</table>";
	echo $documentDetailsTable;

	?>
	<!-- Document Details Table -->

	<!-- Link -->
	<br>
	<b>Download:</b><br>
	<a href='download.php?refId=<?php echo $routing_id; ?>' target='window'>Routing Data (For Records Purposes)</a><br>
	<a href='download.php?refId=<?php echo $routing_id; ?>' target='window'>Document</a><br>

	<br>
	<!-- Link -->
	
	<!--Forwarding Remarks-->
	<table id='csstable' border=1 width=100%>
	<tr>
		<th colspan=2 >REMARKS:</th>
	</tr>
	<tr>
		<td><?php echo $forRow['remarks']; ?></td>
		
	</tr>
	</table>
	<!--Forwarding Remarks-->



