<?php
session_start();
?>
<?php
require("db.php");
require("functions/document functions.php");
require("functions/general functions.php");
require("functions/routing process.php");
require("functions/user functions.php");
require("functions/form functions.php");


?>
<?php
$db=connectDb();
if(isset($_POST['keycode'])){
	
	$sql="select * from routing_targets where id='".$_POST['target_id']."'";
	$rs=$db->query($sql);
		
	$row=$rs->fetch_assoc();
	$keycode=$row['keycode'];
		
	if($keycode==$_POST['keycode']){
		$update_time=date("Y-m-d H:i:s");
		
		$update="insert into routing_receipt(target_id,receive_time,division) values ";
		$update.="('".$_POST['target_id_confirm']."','".$update_time."','".$_SESSION['division_code']."')";
		$updateRS=$db->query($update);
		
//		$_SESSION['reference_number']==$_POST['doc_id'];
//		header("Location: routing report.php");
		

	}	
}

?>
<?php
if(isset($_POST['target_id'])){
	$target_id=$_POST['target_id'];
	$ref_id=$_POST['ref_id'];
	
	
	$target_sql="select * from routing_targets where id='".$target_id."' limit 1";
	$target_rs=$db->query($target_sql);
	
	$target_row=$target_rs->fetch_assoc();
	
	$officer=$target_row['to_name'];
	if($officer=="OTHER"){
		$from_name=$officer;
		$alter_from=$target_row['alter_to'];
		$alter_position=$target_row['alter_position'];
	}
	else {
		$from_name=$officer;
		$alter_from="";
		$alter_position="";
	}
	$from_division=$_SESSION['division_code'];
	$reply_date=date("Y-m-d H:i");
	
	$update="insert into document_routing(from_name,from_office,alter_from,alter_position,request_date,input_time,reference_no) ";
	$update.=" VALUES ";
	$update.="('".$from_name."','".$from_division."','".$alter_from."','".$alter_position."','".$reply_date."','".$reply_date."','".$ref_id."')";

	
	$updateRS=$db->query($update);
	
	$routing_id=$db->insert_id;
	
	$sql="select * from department order by department_code";
	$rs=$db->query($sql);
			
	$nm=$rs->num_rows;
	
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		if(isset($_POST['stn_'.$row['department_code']])){
			$remarks=$_POST['remarks_stn_'.$row['department_code']];
			$action_stn=$_POST['action_stn_'.$row['department_code']];

			$officer_name=$_POST['officer_list_'.$row['department_code']];
			$alter_officer=$_POST['alter_to_stn_'.$row['department_code']];		
			$keycode=generateCodeKey();
			
			
			$file_name=(basename($_FILES['file_stn_'.$row['department_code']]['name']));

			$update="insert into routing_targets(destination_office,to_name,action_id,status,remarks,routing_id,keycode,alter_to) ";	
			$update.=" VALUES ";
			$update.="('".$row['department_code']."','".$officer_name."','".$action_stn."','PENDING',\"".$remarks."\",'".$routing_id."','".$keycode."','".$alter_officer."')";
			
			$updateRS=$db->query($update);
			
			if($file_name==""){
			}
			else {
				$rt_id=$db->insert_id;
				$update2="insert into routing_uploads(targets_id,upload_link,upload_date) values ('".$rt_id."','".$file_name."','".date("Y-m-d H:i")."')";
				$updateRS2=$db->query($update2);
					
			}
			
			
			
		}
			
	}
	
	$update="update routing_targets set status='ANSWERED' where id='".$_POST['target_id']."'";
	$updateRS=$db->query($update);
}	
if(isset($_POST['target_id_cc'])){
	$db=new mysqli("localhost","root","","records");

	$sql="select * from department order by department_code";
	$rs2=$db->query($sql);
			
	$nm=$rs2->num_rows;

	for($i=0;$i<$nm;$i++){
		$row2=$rs2->fetch_assoc();
		if(isset($_POST['fwd_stn_'.$row2['department_code']])){
			$forwardType="IN";
			$forwardTo=$row2['department_code'];
			$forwardRemarks=$_POST['fwdRemarks'];
			$forwardTime=date("Y-m-d H:i:s");
			$forwardSender=$_SESSION['division_code'];

			$forwardNumber=calculateReferenceNumber($db,getDocumentDetails($db,$_POST['ref_id_cc']),adjustControlNumber($row['ref_id_cc']));
			$sql="insert into forward_copy(remarks,to_department,reference_number,forward_date,forwarding_office,document_type) values ";
			$sql.="(";
			$sql.="\"".$forwardRemarks."\",";
			$sql.="\"".$forwardTo."\",";
			$sql.="\"".$forwardNumber."\",";
			$sql.="'".$forwardTime."',";
			$sql.="\"".$forwardSender."\",";
			$sql.="\"".$forwardType."\"";
			$sql.=")";	
			
			$rs=$db->query($sql);	
			
		}
	}
	updateDocumentStatus($db,"FORWARDED",$_POST['ref_id_cc']);
}			

if(isset($_POST['target_id_ccoutgo'])){
	$db=connectDb();
	$forwardType="OUT";
	$forwardTo=$_POST['outgo_destination'];
	$forwardRemarks=$_POST['outgo_remarks'];
	$forwardTime=date("Y-m-d H:i:s");
	$forwardSender=$_SESSION['division_code'];
	$forwardNumber=$_POST['ref_id_ccoutgo'];
	$sql="insert into forward_copy(remarks,to_department,reference_number,forward_date,forwarding_office,document_type) values ";
	$sql.="(";
	$sql.="\"".$forwardRemarks."\",";
	$sql.="\"".$forwardTo."\",";
	$sql.="\"".$forwardNumber."\",";
	$sql.="'".$forwardTime."',";
	$sql.="\"".$forwardSender."\",";
	$sql.="\"".$forwardType."\"";
	$sql.=")";	
			
	$rs=$db->query($sql);	
			
	updateDocumentStatus($db,"FORWARDED",$_POST['ref_id_ccoutgo']);
}			


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="ThemeBucket">
  <link rel="shortcut icon" href="#" type="image/png">

  <title>Document Tracking System - New Document Alerts</title>

    <link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/bootstrap_banzhow.css" rel="stylesheet">
	
	<link href="css/bootstrap_adminex.css" rel="stylesheet">
	<!-- custom CSS -->
	
	
	<link href="css/style_adminex.css" rel="stylesheet">
	<!--
	<link href="css/style_1.css" rel="stylesheet">
	-->
	<!--pickers css-->
  <link rel="stylesheet" type="text/css" href="js/bootstrap-datepicker/css/datepicker-custom.css" />
  <link rel="stylesheet" type="text/css" href="js/bootstrap-timepicker/css/timepicker.css" />
  <link rel="stylesheet" type="text/css" href="js/bootstrap-colorpicker/css/colorpicker.css" />
  <link rel="stylesheet" type="text/css" href="js/bootstrap-daterangepicker/daterangepicker-bs3.css" />
  <link rel="stylesheet" type="text/css" href="js/bootstrap-datetimepicker/css/datetimepicker-custom.css" />
  <link href="css/banzhow_nav.css" rel="stylesheet">
<!--

  <link href="css/style-responsive.css" rel="stylesheet">
-->
  <link rel="stylesheet" href="js/data-tables/DT_bootstrap.css" />
  <link href="js/advanced-datatable/css/demo_page.css" rel="stylesheet" />
  <link href="js/advanced-datatable/css/demo_table.css" rel="stylesheet" />
  <!--file upload-->
  <link rel="stylesheet" type="text/css" href="css/bootstrap-fileupload.min.css" />

  <link href="js/iCheck/skins/flat/red.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/bootstrap-modal.css" />
<!--
 <script language='javascript' src="ajax.js"></script>
-->
<!--
 <link rel="stylesheet" type="text/css" href="css/bootstrap-modal-bs3.css" />
	-->
 <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="js/html5shiv.js"></script>
  <script src="js/respond.min.js"></script>
  <![endif]-->
  
  
</head>

<body>
	<div class='banzhow'>
	<div class="sticky-header" >
	<?php require("nav_menu.php"); ?>
	</div>
	</div>
	<div class='adminex'>	
<section>
    <!-- left side start-->
    <div class="left-side sticky-left-side">

        <!--logo and iconic logo start-->
		<div class="logo">
            <a href="index.html"><img src="images/logo.png" alt=""></a>
        </div>
        <div class="logo-icon text-center">
            <a href="index.html"><img src="images/logo_icon.png" alt=""></a>
        </div>
        <!--logo and iconic logo end-->



		<?php require("sidebar.php"); ?>	


    </div>
	
    <div class="main-content" >
        <div class="header-section">


        <!--
        <form class="searchform" action="index.html" method="post">
            <input type="text" class="form-control" name="keyword" placeholder="Search here..." />
        </form>
        -->

        <a class="toggle-btn"><i class="fa fa-bars"></i></a>
		
		<?php require("notification menu.php"); ?>
		
        <!--notification menu end -->

        </div>

        <!-- header section end-->

		
        <!-- page heading start-->
        <div class="page-heading">
			<h3>
                New Document Alerts <small>date pickers, datetime pickers, time pickers and color pickers</small>
            </h3>
            <ul class="breadcrumb">
                <li>
                    <a href="#">Newly Received</a>
                </li>
                <li class="active"> Document Alerts </li>
            </ul>
        </div>
        <?php
		
		$db=connectDb();
		$sql2="select * from document_actions";
			$rs2=$db->query($sql2);
			$nm2=$rs2->num_rows;
			for($k=0;$k<$nm2;$k++){
				$row2=$rs2->fetch_assoc();
				$action[$k]["id"]=$row2['action_code'];
				$action[$k]["name"]=$row2['action_description'];

			}
		?>	
        <div class="wrapper">
        <div class="row">
        <div class="col-sm-12">
        <section class="panel">
        <header class="panel-heading">
			Pending Documents <?php echo $message; ?>
            <span class="tools pull-right">
				<a class="fa fa-cogs"  data-toggle="modal" href="#myModal2"></a>

                <a href="javascript:;" class="fa fa-chevron-down"></a>
                <a href="javascript:;" class="fa fa-times"></a>
             </span>
        </header>
        <div class="panel-body">
        <div class="adv-table">
		
		
		<?php
		$db=connectDb();
		$rs=receiveRoutingMessages($db,$_SESSION['division_code']);
		$nm=$rs->num_rows;

		
		
		?>	
		<span id='test' name='test'></span>
        <table  class="display table table-bordered table-striped" id="pending_dt" name='pending_dt'>
			<thead>
			<tr>
			<th>Office Code</th>
			<th>Document Date</th>
			<th>Subject</th>
			<th>Reference Number</th>
			<th>Action Taken</th>
			<th>Action Date</th>
			<th>Request Status</th>
			<th>Action</th>
			</tr>
			</thead>
			<tbody>
			<?php
			for($i=0;$i<$nm;$i++){
			$row=$rs->fetch_assoc();
			$documentRow=getDocumentDetails($db,$row['reference_no']);
			$reference_number=calculateReferenceNumber($db,$documentRow,adjustControlNumber($row['reference_no']));
			if(($row['status']=="PENDING")||($documentRow["status"]=="SENT")||($documentRow["status"]=="AWAITING REPLY")||(($documentRow["status"]=="FOR: CLARIFICATION")&&($row['status']=="NEEDING CLARIFICATION"))){

				$docId=$row['reference_no'];
				$isReceived=isReceived($db,$row['id'],$_SESSION['department']);


			?>
			<tr>
			<td><?php echo $documentRow['sending_office']; ?></td>
			<td><?php echo date("Y-m-d",strtotime($documentRow['document_date'])); ?></td>
			<td>


			<a href='#' >

			<?php echo $documentRow['subject']; ?> 

			</a>
			<?php
			if($isReceived=="NO"){
			?>
			
			<a title='Receive the Document' data-toggle="modal" href="#receiptModal" onclick='getBarCode("<?php echo $row['id']; ?>","<?php echo $documentRow['ref_id']; ?>");' ><i class="fa fa-barcode"></i></a>
			<?php
			}
			
			?>
			<span class='pull-right'>
			<a title='Download the Document' href='download.php?refId=<?php echo $row['reference_no']; ?>' target='_blank' ><i class="fa fa-file-text"></i></a>
			
			
			</span>
			
			</td>
			<td><?php echo $reference_number; ?></td>
			<td><?php echo getAction($db,$row['action_id']); ?></td>
			<td><?php echo date("Y-m-d H:i:s",strtotime($row['request_date'])); ?></td>
			<td><a href='document_history.php?view=<?php echo $row['reference_no']; ?>'><?php echo $row['status']; ?></a></td>
			<td>
			
			
			    <div class="btn-group">
					<button class="btn btn-default dropdown-toggle" <?php if($isReceived=="NO"){ echo "disabled"; } ?> data-toggle="dropdown">Action <i class="fa fa-angle-down"></i>
					</button>
						<ul class="dropdown-menu pull-right">
						<li><a onclick='prepareReply("<?php echo $row['reference_no'];?>","<?php echo $row['id']; ?>")' href="#">Reply</a></li>
						<li><a onclick='prepareCC("<?php echo $row['reference_no'];?>","<?php echo $row['id']; ?>")'  href='#'>Send A Copy</a></li>
                        <li><a  onclick='prepareCCOut("<?php echo $row['reference_no'];?>","<?php echo $row['id']; ?>")' href='#'>Send as Outgoing</a></li>
                        <li><a href="#">Close Document</a></li>
                        </ul>
                </div>			
			
			
			
			
			
			
			
			</td>
			</tr>
			<?php
				}
			}
			?>


			</tbody>
			<!--
			<tfoot>
			<tr>
			<th>Office Code</th>
			<th>Document Date</th>
			<th>Subject</th>
			<th>Reference Number</th>
			<th>Action Taken</th>
			<th>Action Date</th>
			<th>Request Status</th>
			<th>Download</th>
			</tr>        
			</tfoot>
			-->
        </table>
        </div>
        </div>
		<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title">Filter Search</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form action="#" class="form-horizontal ">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4"> Date time picker</label>
                                                    <div class="col-md-6">
                                                        <input size="16" type="text" value="2012-06-15 14:45" readonly class="form_datetime form-control">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Default Datepicker</label>
                                                    <div class="col-md-6 col-xs-11">
                                                        <input class="form-control form-control-inline input-medium default-date-picker"  size="16" type="text" value="" />
                                                        <span class="help-block">Select date</span>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Start with years viewMode</label>
                                                    <div class="col-md-6 col-xs-11">

                                                        <div data-date-viewmode="years" data-date-format="dd-mm-yyyy" data-date="12-02-2012"  class="input-append date dpYears">
                                                            <input type="text" readonly="" value="12-02-2012" size="16" class="form-control">
                                                                      <span class="input-group-btn add-on">
                                                                        <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                                      </span>
                                                        </div>
                                                        <span class="help-block">Select date</span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Months Only</label>
                                                    <div class="col-md-6 col-xs-11">
                                                        <div data-date-minviewmode="months" data-date-viewmode="years" data-date-format="mm/yyyy" data-date="102/2012"  class="input-append date dpMonths">
                                                            <input type="text" readonly="" value="02/2012" size="16" class="form-control">
                                                                      <span class="input-group-btn add-on">
                                                                        <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                                      </span>
                                                        </div>


                                                        <span class="help-block">Select month only</span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Date Range</label>
                                                    <div class="col-md-6">
                                                        <div class="input-group input-large custom-date-range" data-date="13/07/2013" data-date-format="mm/dd/yyyy">
                                                            <input type="text" class="form-control dpd1" name="from">
                                                            <span class="input-group-addon">To</span>
                                                            <input type="text" class="form-control dpd2" name="to">
                                                        </div>
                                                        <span class="help-block">Select date range</span>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>


                                        <form class="form-horizontal  " action="#">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Default Timepicker</label>
                                                <div class="col-md-6">
                                                    <div class="input-group bootstrap-timepicker">
                                                        <input type="text" class="form-control timepicker-default">
                                                            <span class="input-group-btn">
                                                            <button class="btn btn-default" type="button"><i class="fa fa-clock-o"></i></button>
                                                            </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4">24hr Timepicker</label>
                                                <div class="col-md-6">
                                                    <div class="input-group bootstrap-timepicker">
                                                        <input type="text" class="form-control timepicker-24">
                                                            <span class="input-group-btn">
                                                            <button class="btn btn-default" type="button"><i class="fa fa-clock-o"></i></button>
                                                            </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>



                                        <div class="modal-footer">
                                            <button data-dismiss="modal" class="btn btn-primary" type="button">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
		



        </section>

        <section class="panel">
        <header class="panel-heading">
            Documents Sent
            <span class="tools pull-right">
				<a class="fa fa-cogs"  data-toggle="modal" href="#myModal2"></a>

                <a href="javascript:;" class="fa fa-chevron-down"></a>
                <a href="javascript:;" class="fa fa-times"></a>
             </span>
        </header>
        <div class="panel-body">
        <div class="adv-table">
		<?php
		$sql="select * from document inner join document_routing on document.id=document_routing.reference_id where sending_office='".$_SESSION['department']."' and (status in ('FORWARDED','SENT','AWAITING REPLY','FOR: GM APPROVAL','FOR: CLARIFICATION') or status like '%CLOSED%%') and document_type='OUT' order by request_date desc";
		$rs=$db->query($sql);
		$nm=$rs->num_rows;
		
		?>
		
		
        <table  class="display table table-bordered table-striped" id="sent_dt">
			<thead>
			<tr>
			<th>Office Code</th>
			<th>Document Date</th>
			<th>Subject</th>
			<th>Reference Number</th>
			<th>Action Taken</th>
			<th>Action Date</th>
			<th>Status</th>
			<th>Action</th>
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
						<td><?php echo $row['subject']; ?>
						<a href='download.php?refId=<?php echo $row['ref_id']; ?>' target='_blank' class='pull-right'><i class="fa fa-file-text"></i></a>
						</td>
						<td><?php echo $referenceNumber; ?></td>
						<td><?php echo $action; ?></td>
						<td><?php if($row2['request_date']==""){ echo ""; } else { echo date("Y-m-d H:i:s",strtotime($row2['request_date'])); } ?></td>
						<td><a href='document_history.php?view=<?php echo $row['ref_id']; ?>'><?php echo $row['status']; ?></a></td>
						<td>
							<div class="btn-group">
								<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action <i class="fa fa-angle-down"></i>
								</button>
									<ul class="dropdown-menu pull-right">
									<li><a data-toggle="modal" href="#ccModal">Send A Copy</a></li>
									<li><a href="#">Close Document</a></li>
									</ul>
							</div>								
						</td>
						
					</tr>
			<?php	
				}
				
			?>




			</tbody>
			<tfoot>
        <!--
		<tr>
            <th>Rendering engine</th>
            <th>Browser</th>
            <th>Platform(s)</th>
            <th class="hidden-phone">Engine version</th>
            <th class="hidden-phone">CSS grade</th>
        </tr>
		-->
        </tfoot>
        </table>
        </div>
        </div>
		<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title">Filter Search</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form action="#" class="form-horizontal ">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4"> Date time picker</label>
                                                    <div class="col-md-6">
                                                        <input size="16" type="text" value="2012-06-15 14:45" readonly class="form_datetime form-control">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Default Datepicker</label>
                                                    <div class="col-md-6 col-xs-11">
                                                        <input class="form-control form-control-inline input-medium default-date-picker"  size="16" type="text" value="" />
                                                        <span class="help-block">Select date</span>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Start with years viewMode</label>
                                                    <div class="col-md-6 col-xs-11">

                                                        <div data-date-viewmode="years" data-date-format="dd-mm-yyyy" data-date="12-02-2012"  class="input-append date dpYears">
                                                            <input type="text" readonly="" value="12-02-2012" size="16" class="form-control">
                                                                      <span class="input-group-btn add-on">
                                                                        <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                                      </span>
                                                        </div>
                                                        <span class="help-block">Select date</span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Months Only</label>
                                                    <div class="col-md-6 col-xs-11">
                                                        <div data-date-minviewmode="months" data-date-viewmode="years" data-date-format="mm/yyyy" data-date="102/2012"  class="input-append date dpMonths">
                                                            <input type="text" readonly="" value="02/2012" size="16" class="form-control">
                                                                      <span class="input-group-btn add-on">
                                                                        <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                                      </span>
                                                        </div>


                                                        <span class="help-block">Select month only</span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Date Range</label>
                                                    <div class="col-md-6">
                                                        <div class="input-group input-large custom-date-range" data-date="13/07/2013" data-date-format="mm/dd/yyyy">
                                                            <input type="text" class="form-control dpd1" name="from">
                                                            <span class="input-group-addon">To</span>
                                                            <input type="text" class="form-control dpd2" name="to">
                                                        </div>
                                                        <span class="help-block">Select date range</span>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>


                                        <form class="form-horizontal  " action="#">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Default Timepicker</label>
                                                <div class="col-md-6">
                                                    <div class="input-group bootstrap-timepicker">
                                                        <input type="text" class="form-control timepicker-default">
                                                            <span class="input-group-btn">
                                                            <button class="btn btn-default" type="button"><i class="fa fa-clock-o"></i></button>
                                                            </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4">24hr Timepicker</label>
                                                <div class="col-md-6">
                                                    <div class="input-group bootstrap-timepicker">
                                                        <input type="text" class="form-control timepicker-24">
                                                            <span class="input-group-btn">
                                                            <button class="btn btn-default" type="button"><i class="fa fa-clock-o"></i></button>
                                                            </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>



                                        <div class="modal-footer">
                                            <button data-dismiss="modal" class="btn btn-primary" type="button">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
		



        </section>

        <section class="panel">
        <header class="panel-heading">
            Office Orders Issued
            <span class="tools pull-right">
				<a class="fa fa-cogs"  data-toggle="modal" href="#myModal2"></a>

                <a href="javascript:;" class="fa fa-chevron-down"></a>
                <a href="javascript:;" class="fa fa-times"></a>
             </span>
        </header>
        <div class="panel-body">
        <div class="adv-table">
		
		<?php
		$date=date("Y-m-d");
		?>
		<?php
		//$db=retrieveRecordsDb();


		$sql="select * from routing_targets inner join document_routing on routing_targets.routing_id=document_routing.id where status in ('ISSUED AND SENT','CLOSED') and destination_office in ('".$_SESSION['department']."','ALL OFFICERS') and request_date like '".$date."%%'";

		$rs=$db->query($sql);
		$nm=$rs->num_rows;
				
		?>
        <table  class="display table table-bordered table-striped" id="office_order_dt">
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
			<tfoot>
        <!--
		<tr>
            <th>Rendering engine</th>
            <th>Browser</th>
            <th>Platform(s)</th>
            <th class="hidden-phone">Engine version</th>
            <th class="hidden-phone">CSS grade</th>
        </tr>
		-->
        </tfoot>
        </table>
        </div>
        </div>
							<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title">Filter Search</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form action="#" class="form-horizontal ">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4"> Date time picker</label>
                                                    <div class="col-md-6">
                                                        <input size="16" type="text" value="2012-06-15 14:45" readonly class="form_datetime form-control">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Default Datepicker</label>
                                                    <div class="col-md-6 col-xs-11">
                                                        <input class="form-control form-control-inline input-medium default-date-picker"  size="16" type="text" value="" />
                                                        <span class="help-block">Select date</span>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Start with years viewMode</label>
                                                    <div class="col-md-6 col-xs-11">

                                                        <div data-date-viewmode="years" data-date-format="dd-mm-yyyy" data-date="12-02-2012"  class="input-append date dpYears">
                                                            <input type="text" readonly="" value="12-02-2012" size="16" class="form-control">
                                                                      <span class="input-group-btn add-on">
                                                                        <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                                      </span>
                                                        </div>
                                                        <span class="help-block">Select date</span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Months Only</label>
                                                    <div class="col-md-6 col-xs-11">
                                                        <div data-date-minviewmode="months" data-date-viewmode="years" data-date-format="mm/yyyy" data-date="102/2012"  class="input-append date dpMonths">
                                                            <input type="text" readonly="" value="02/2012" size="16" class="form-control">
                                                                      <span class="input-group-btn add-on">
                                                                        <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                                      </span>
                                                        </div>


                                                        <span class="help-block">Select month only</span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Date Range</label>
                                                    <div class="col-md-6">
                                                        <div class="input-group input-large custom-date-range" data-date="13/07/2013" data-date-format="mm/dd/yyyy">
                                                            <input type="text" class="form-control dpd1" name="from">
                                                            <span class="input-group-addon">To</span>
                                                            <input type="text" class="form-control dpd2" name="to">
                                                        </div>
                                                        <span class="help-block">Select date range</span>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>


                                        <form class="form-horizontal  " action="#">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Default Timepicker</label>
                                                <div class="col-md-6">
                                                    <div class="input-group bootstrap-timepicker">
                                                        <input type="text" class="form-control timepicker-default">
                                                            <span class="input-group-btn">
                                                            <button class="btn btn-default" type="button"><i class="fa fa-clock-o"></i></button>
                                                            </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4">24hr Timepicker</label>
                                                <div class="col-md-6">
                                                    <div class="input-group bootstrap-timepicker">
                                                        <input type="text" class="form-control timepicker-24">
                                                            <span class="input-group-btn">
                                                            <button class="btn btn-default" type="button"><i class="fa fa-clock-o"></i></button>
                                                            </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>



                                        <div class="modal-footer">
                                            <button data-dismiss="modal" class="btn btn-primary" type="button">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
		



        </section>
        </div>
        </div>		



		<div class="modal fade" id="ccModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Send A Copy</h4>
                </div>
                    <form action="main_documents.php" method="post" class="form-horizontal ">

					<div class="modal-body">
						<div style='display:none;' class='form-group'>
						<input type='text' name='target_id_cc' id='target_id_cc' />
						<input type='text' name='ref_id_cc' id='ref_id_cc' />	
						
						</div>

						<div class="form-group">
                            <label class="control-label col-md-4 col-sm-2 control-label">Destinations</label>
                            <div class="col-md-6 col-sm-6">
								<table>
								<?php
									$db=connectDb();
															
									$sql="select * from department order by department_code";
									$rs=$db->query($sql);
									$nm=$rs->num_rows;
									for($i=0;$i<$nm;$i++){
										$row=$rs->fetch_assoc();
								?>			
									<tr>	
																	
									<td valign=top><input id='fwd_stn_<?php echo $row['department_code']; ?>' name='fwd_stn_<?php echo $row['department_code']; ?>' class='division_unit' type='checkbox'   /> <?php echo $row['department_name']; ?></td>
																
									</tr>	
																
								<?php
																
									}
								?>
								</table>
							</div>
							
						</div>
                        <div class="form-group">
                        <label class="control-label col-md-4 col-sm-2" >Remarks</label>
                        <div class="col-md-6">
                            <textarea class='form-control' name='fwdRemarks'></textarea>
                        </div>
                        </div>
						
                    </div>
                     <div class="modal-footer">
					   <button class="btn btn-primary" type="submit">Submit</button>
						<!--
						<button data-dismiss="modal" class="btn btn-primary" type="button">Close</button>
						-->
					 </div>
					
					</form>	
					
                </div>
            </div>
		</div>
			
		<div class="modal fade" id="cc_outModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Send An Outgoing Copy</h4>
                </div>
                    <form action="main_documents.php" method="post" class="form-horizontal ">

					<div class="modal-body">
						<div style='display:none;' class='form-group'>
						<input type='text' name='target_id_ccoutgo' id='target_id_ccoutgo' />
						<input type='text' name='ref_id_ccoutgo' id='ref_id_ccoutgo' />	
						
						</div>


						<div class="form-group">
                            <label class="control-label col-md-4 col-sm-4 control-label">Enter Destination</label>
                            <div class="col-md-6 col-sm-6">	
								<input type='text' name='out_destination' id='out_destination' class='form-control' />
							</div>
						</div>
                        <div class="form-group">
                        <label class="control-label col-md-4 col-sm-2" >Remarks</label>
                        <div class="col-md-6">
                            <textarea class='form-control' name="outgo_remarks" id='outgo_remarks'></textarea>
                        </div>
                        </div>
                    </div>
                    <div class="modal-footer">
					   <button class="btn btn-primary" type="submit">Submit</button>
						<!--
						<button data-dismiss="modal" class="btn btn-primary" type="button">Close</button>
						-->
					 </div>
					</form>	
					
                </div>
            </div>
		</div>

		<div class="modal fade" id="replyModal"  tabindex="-1" data-focus-on="input:first" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog " style='width:800px;'>
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Reply To Document</h4>
                </div>
                <form action="main_documents.php" method="post" class="form-horizontal ">

                <div class="modal-body">
					<div style='display:none;' class='form-group'>
					<input type='text' name='target_id' id='target_id' />
					<input type='text' name='ref_id' id='ref_id' />	
					
					</div>
                    <div class="form-group">
						<div class='col-md-1 col-sm-1'>
						
						</div>
						
						<div class='col-md-10 col-sm-10'>
							<table>
								<tr>
															
								<td valign=top>

								
									<input type='checkbox' name='stn_all' id='stn_all' onclick='selectAll(this)' /> 

									<label> ALL DIVISIONS</label>

									<span name='ico_stn_all' id='ico_stn_all' class='col-md-3 col-sm-3'  style='display:none;'>
									
									<a href='#actionModal_all'  data-toggle='modal' title='Action'><i class='fa fa-edit'></i></a>
									<a href='#remarksModal_all' data-toggle='modal' title='Additional Remarks'><i class='fa fa-comment'></i></a>
									<a href='#uploadModal_all' data-toggle='modal' title='Upload File (Optional)'><i class='fa fa-paperclip'></i></a>
									</span>

									
									<div class="modal fade" data-focus-on="input:first" id="remarksModal_all" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
										<div class="modal-dialog " >
											<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="modal-title">Enter Additional Remarks</h4>
											</div>
											<form action="#" method="post" class="form-horizontal ">
											
											<div class="modal-body">
												<div class="form-group">
													<label class="control-label col-md-4">Remarks</label>
													<div class="col-md-6">
														<textarea class='form-control' name='stn_'></textarea>
														
													</div>
												</div>
											</div>
											<div class="modal-footer">
											   <!--
											   <button data-dismiss="modal" class="btn btn-primary" type="submit">Close</button>
												-->
												<button class="btn btn-primary" type="button">Submit</button>
											</div>
											</form>		
											
										</div>	
									</div>				
								
								
								
									<div class="modal fade" data-focus-on="input:first" id="actionModal_all" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
										<div class="modal-dialog " >
											<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="modal-title">Enter Action</h4>
											</div>
											
											<div class="modal-body">
												<div class="form-group">
													<label class="control-label col-md-2">Action</label>
													<div class="col-md-6">
														<select name='action_stn' id='action_stn'>	
															<?php

															for($k=0;$k<count($action);$k++){
															?>
																<option value='<?php echo $action[$k]['id']; ?>' data-action='<?php echo $action[$k]['name']; ?>'><?php echo $action[$k]['name']; ?></option>
															<?php
															}
															?>
														</select>
													</div>
												</div>
											</div>
											<div class="modal-footer">
											   <!--
											   <button data-dismiss="modal" class="btn btn-primary" type="submit">Close</button>
												-->
											   <button class="btn btn-primary" type="button">Save</button>
											</div>
											
										</div>	
									</div>			
								
								
									<div class="modal fade" data-focus-on="input:first" id="uploadModal_all" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
										<div class="modal-dialog " >
											<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="modal-title">Upload a File</h4>
											</div>
											<form action="#" method="post" class="form-horizontal ">
											
											<div class="modal-body">
												<div class="form-group">
													<label class="control-label col-md-4">Upload</label>
													<div class="col-md-6">
														<div class="fileupload fileupload-new" data-provides="fileupload">
															<span class="btn btn-default btn-file">
															<span class="fileupload-new"><i class="fa fa-paper-clip"></i> Select file</span>
															<span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
															<input type="file" class="default" id='file_stn' name='file_stn' />
															<input type='hidden' name="MAX_FILE_SIZE" value="4000000" />
															</span>
															<span class="fileupload-preview" style="margin-left:5px;"></span>
															<a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none; margin-left:5px;"></a>
														</div>
														
													</div>
												</div>
											</div>
											<div class="modal-footer">
											   <!--
											   <button data-dismiss="modal" class="btn btn-primary" type="submit">Close</button>
												-->
											   <button class="btn btn-primary" type="button">Save</button>
											</div>
											</form>		
											
										</div>	
									</div>			
									
									
								</td>									
								</tr>

							<?php
							$db=connectDb();
															
							$sql="select * from department order by department_code";
							$rs=$db->query($sql);
							$nm=$rs->num_rows;
								for($i=0;$i<$nm;$i++){
								$row=$rs->fetch_assoc();

								?>			
								<tr>	
																	
									<td class='col-md-8 col-sm-8' valign=top>
									
									<!--		
									<div class="flat-red single-row">
                                        <div class="radio ">
									-->
									
									<span id='check_stn_<?php echo $row['department_code']; ?>' name='check_stn_<?php echo $row['department_code']; ?>' class='division_bar col-sm-10'>
											<input id='stn_<?php echo $row['department_code']; ?>' name='stn_<?php echo $row['department_code']; ?>' class='division_unit' type='checkbox'  onclick='updateDestList("stn_<?php echo $row['department_code']; ?>","<?php echo $row['department_name']; ?>")' type="checkbox">
											<label><?php echo $row['department_name']; ?></label>
									<!--
										</div>
									</div>
									-->
									
									<!--
									<a href='#' data-toggle='tooltip' data-original-title='Enter Remarks'>
									<i class='fa fa-edit'  data-toggle="tooltip" data-original-title='Enter remarks'></i>
									</a>
									-->
									</span>
									<span name='ico_stn_<?php echo $row['department_code']; ?>' id='ico_stn_<?php echo $row['department_code']; ?>' class='icon_bar col-md-3 col-sm-3'  style='display:none;'>
									<a href='#' onclick="loadOfficerName('<?php echo $row['department_code']; ?>','officerModal_<?php echo $row['department_code']; ?>')" title='Officer Name'><i class='fa fa-group'></i></a>
									
									<a href='#actionModal_<?php echo $row['department_code']; ?>'  data-toggle='modal' title='Action' ><i class='fa fa-edit' ></i></a>
									<a href='#remarksModal_<?php echo $row['department_code']; ?>' data-toggle='modal' title='Additional Remarks'><i class='fa fa-comment'></i></a>
									<a href='#uploadModal_<?php echo $row['department_code']; ?>' data-toggle='modal' title='Upload File (Optional)'><i class='fa fa-paperclip'></i></a>
									</span>



									<div class="modal fade" data-focus-on="input:first" id="remarksModal_<?php echo $row['department_code']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
										<div class="modal-dialog " >
											<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="modal-title">Enter Additional Remarks</h4>
											</div>
											
											<div class="modal-body">
												<div class="form-group">
													<label class="control-label col-md-4">Remarks</label>
													<div class="col-md-6">
														<textarea class='form-control' name='remarks_stn_<?php echo $row['department_code']; ?>'></textarea>
														
													</div>
												</div>
											</div>
											<div class="modal-footer">
											   <!--
											   <button data-dismiss="modal" class="btn btn-primary" type="submit">Close</button>
												-->
											   <button class="btn btn-primary" type="button">Submit</button>
											</div>
											
											</div>	
										</div>				
									</div>
								
								
									<div class="modal fade" data-focus-on="input:first" id="actionModal_<?php echo $row['department_code']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
										<div class="modal-dialog " >
											<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="modal-title">Enter Action</h4>
											</div>
											
											<div class="modal-body">
												<div class="form-group">
													<label class="control-label col-md-2">Action</label>
													<div class="col-md-6">
														<select name='action_stn_<?php echo $row['department_code']; ?>' id='action_stn_<?php echo $row['department_code']; ?>'  >	
															<?php

															for($k=0;$k<count($action);$k++){
															?>
																<option value='<?php echo $action[$k]['id']; ?>' data-action='<?php echo $action[$k]['name']; ?>'><?php echo $action[$k]['name']; ?></option>
															<?php
															}
															?>
														</select>
													</div>
												</div>
											</div>
											<div class="modal-footer">
											   <!--
											   <button data-dismiss="modal" class="btn btn-primary" type="submit">Close</button>
												-->
											   <button class="btn btn-primary" type="button">Save</button>
											</div>

											
										</div>	
									</div>			
								
								
									<div class="modal fade" data-focus-on="input:first" id="uploadModal_<?php echo $row['department_code']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
										<div class="modal-dialog " >
											<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="modal-title">Upload a File</h4>
											</div>
											
											<div class="modal-body">
												<div class="form-group">
													<label class="control-label col-md-4">Upload</label>
													<div class="col-md-6">
														<div class="fileupload fileupload-new" data-provides="fileupload">
															<span class="btn btn-default btn-file">
															<span class="fileupload-new"><i class="fa fa-paper-clip"></i> Select file</span>
															<span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
															<input type="file" class="default" id='file_stn_<?php echo $row['department_code']; ?>' name='file_stn_<?php echo $row['department_code']; ?>' />
															<input type='hidden' name="MAX_FILE_SIZE" value="4000000" />
															</span>
															<span class="fileupload-preview" style="margin-left:5px;"></span>
															<a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none; margin-left:5px;"></a>
														</div>
														
													</div>
												</div>
											</div>
											<div class="modal-footer">
											   <!--
											   <button data-dismiss="modal" class="btn btn-primary" type="submit">Close</button>
												-->
											   <button class="btn btn-primary" type="button">Save</button>
											</div>
											
										</div>	
									</div>			
								
								
									<div class="modal fade" data-focus-on="input:first" id="officerModal_<?php echo $row['department_code']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
										<div class="modal-dialog " >
											<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="modal-title">Enter Officer Name</h4>
											</div>
											
											<div class="modal-body">
												<div class="form-group">
													<label class="control-label col-md-4">Officer Name</label>
													<div class="col-md-6">
														<select name='officer_list_<?php echo $row['department_code']; ?>' id='officer_list_<?php echo $row['department_code']; ?>' onchange='checkAlter(this,"<?php echo $row['department_code']; ?>")'>
														
														</select>
														
													</div>
												</div>
												<div class='form-group' style='display:none;'>
													<input type='text' name='alter_to_stn_<?php echo $row['department_code']; ?>' id='alter_to_stn_<?php echo $row['department_code']; ?>' />
													<input type='text' name='alter_position_stn_<?php echo $row['department_code']; ?>' id='alter_position_stn_<?php echo $row['department_code']; ?>' />
												</div>
											</div>
											<div class="modal-footer">
											   <!--
											   <button data-dismiss="modal" class="btn btn-primary" type="submit">Close</button>
												-->
											   <button class="btn btn-primary" type="button">Submit</button>
											</div>
											
										</div>	
									</div>		

									
									</td>

								</tr>	
																
							<?php
							}
							?>
						</table>

						</div>
                    </div>
					
						
					</div>

						
                    <div class="modal-footer">
                       <!--
					   <button data-dismiss="modal" class="btn btn-primary" type="submit">Close</button>
						-->
					   <button class="btn btn-primary" type="submit">Submit</button>

					</div>
					</form>

				</div>
			</div>
		</div>	
		
			<div class="modal fade" id="receiptModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog " >
					<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Confirm Document</h4>
					</div>
					<form action="main_documents.php" method="post" class="form-horizontal ">
					
					<div class="modal-body">
                        <div class="form-group">
                            <label class="control-label col-md-4">Receipt Code</label>
                            <div class="col-md-6" id='barcode' name='barcode'>

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">Enter Code to Confirm</label>
                            <div class="col-md-6">
								<input type='text' name='keycode' id='keycode' />
                            </div>
                        </div>
					</div>
                    <div class="modal-footer">
                       <!--
					   <button data-dismiss="modal" class="btn btn-primary" type="submit">Close</button>
						-->
					   <button class="btn btn-primary" type="submit">Submit</button>
						<div id='code_values' name='code_values'>
						
						</div>
					</div>
					</form>		
					
				</div>	
			</div>			
		
		
	
		
</section>
</div>



<!-- Placed js at the end of the document so the pages load faster -->
<script src="js/jquery-1.10.2.min.js"></script>
<script src="js/jquery-ui-1.9.2.custom.min.js"></script>
<script src="js/jquery-migrate-1.2.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/modernizr.min.js"></script>
<script src="js/jquery.nicescroll.js"></script>

<!--dynamic table-->
<script type="text/javascript" language="javascript" src="js/advanced-datatable/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="js/data-tables/DT_bootstrap.js"></script>
<!--dynamic table initialization -->
<script src="js/dynamic_table_init.js"></script>

<!--common scripts for all pages-->
<script src="js/scripts.js"></script>


<!--file upload-->
<script type="text/javascript" src="js/bootstrap-fileupload.min.js"></script>
<!--icheck -->
<script src="js/iCheck/jquery.icheck.js"></script>
<script src="js/icheck-init.js"></script>





<script src="js/iCheck/jquery.icheck.js"></script>
<script src="js/icheck-init.js"></script>

<script src="js/bootstrap-modal.js"></script>
<script src="js/bootstrap-modalmanager.js"></script>


<script language='javascript'>
var destination_count=0;
function getXHTML(){
	var xmlHttp;	
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlHttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
	}

	return xmlHttp;


}

function makeajax(url,testFunction){


	var xmlHttp;
	var ajaxHTML;
	xmlHttp=getXHTML();
	xmlHttp.onreadystatechange=function()
	{
		if (xmlHttp.readyState==4 && xmlHttp.status==200)
		{ 	
			ajaxHTML=xmlHttp.responseText;
			//window[testFunction](ajaxHTML);
			generateList(ajaxHTML);
			//return ajaxHTML;
		}
	}  	
	
	xmlHttp.open("GET",url,true); 
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=utf-8");	
	xmlHttp.send();	

}


function updateDestList(division,division_name){

	if($('#'+division).prop('checked')==true){
		
//		$("#label_officer_"+division).show();
//		$("#label_action_"+division).show();

		var url="ajax_processing.php?originating_officer="+division.replace("stn_","");
		
		makeajax(url,"generateList");	


		$("#ico_"+division).show();

		
	//	var officer_name=$("#officer_"+division).find("option:selected").data('name');
	//	var action=$("#action_"+division).find("option:selected").data('action');
		
//		$('#destination_table').append("<tr id='row_"+division+"' name='row_"+division+"'><td>"+division_name+"</td><td name='tag_officer_"+division+"' id='tag_officer_"+division+"'>"+officer_name+"</td><td name='tag_action_"+division+"' id='tag_action_"+division+"'>"+action+"</td></tr>");	
	
		destination_count++;
		
		$('#check_'+division).prop('class','division_bar col-md-6');
		
		
	}
	else {
		$("#ico_"+division).hide();

//		$("#label_officer_"+division).hide();
//		$("#label_action_"+division).hide();

		destination_count--;
		$('#check_'+division).prop('class','division_bar col-md-10');
	
	
	}
//	updateDestinationCount();
}
function getBarCode(barkey,dockey){

	$('#code_values').html("<input type=hidden id='target_id_confirm' name='target_id_confirm' value='"+barkey+"'/><input type=hidden id='doc_id' name='doc_id'  value='"+dockey+"' />");
						//<input type=hidden id='target_id' name='target_id' />
						//<input type=hidden id='doc_id' name='doc_id'  />					   
																						
																						
																						
	$('#barcode').html("<img src='barcodegen/test_1D.php?text="+barkey+"' />");


}


function generateList(responseJSON){

	var response=JSON.parse(responseJSON);	
	var report_title="";
	
	report_title=response.report_title;
	var report_contents="";	

	//report_contents+="<select>";

	var division="";
	for(var n=0;n<response.record_count;n++){
		if(n==0){
			division=response.officer[n].division;
		}
		report_contents+="<option data-name='"+response.officer[n].name+"' data-division='"+response.officer[n].division+"' value='"+response.officer[n].id+"'>"+response.officer[n].name+"</option>";
	}
	

	//report_contents+="</select>";
	
	$('#officer_list_'+division).html(report_contents);
	
	
}

function loadOfficerName(division){
	var url="ajax_processing.php?originating_officer="+division;
	if($('#stn_all').prop('checked')==true){
		makeajax(url,"generateList");	
	}
	
	$('#officerModal_'+division).modal('show');

}

function selectAll(element){
	if($(element).prop('checked')==true){
		$('.division_bar').prop('class','division_bar col-md-6');

		$('.division_unit').prop('checked',true);

		$('.icon_bar').show();

		}
	else {
		$('.division_bar').prop('class','division_bar col-md-10');

		$('.division_unit').prop('checked',false);

		$('.icon_bar').hide();
	
	}
	
}

function prepareReply(ref_id,target_id){
	$('#target_id').val(target_id);
	$('#ref_id').val(ref_id);
	$('#replyModal').modal('show');

}

function prepareCC(ref_id,target_id){
	$('#target_id_cc').val(target_id);
	$('#ref_id_cc').val(ref_id);
	$('#ccModal').modal('show');

}

function prepareCCOut(ref_id,target_id){
	$('#target_id_ccoutgo').val(target_id);
	$('#ref_id_ccoutgo').val(ref_id);
	$('#cc_outModal').modal('show');

}










function checkAlter(element,dept){
	if($(element).val()=="OTHER"){
		$('#alter_to_stn_'+dept).val($(element).data('name'));
	
	}
}

</script>

</body>
</html>
