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
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="ThemeBucket">
  <link rel="shortcut icon" href="#" type="image/png">

  <title>Document Tracking System - Search in Archive</title>

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

        <!--notification menu start -->
		
		<?php 
		require("notification menu.php");
		?>
		
        <!--notification menu end -->

        </div>

        <!-- header section end-->

		
        <!-- page heading start-->
        <div class="page-heading">
            <h3>
                End of the Month Report <small>date pickers, datetime pickers, time pickers and color pickers</small>
            </h3>
            <ul class="breadcrumb">
                <li>
                    <a href="#">Records Officer</a>
                </li>
                <li class="active"> Monthly Report </li>
            </ul>
        </div>

        <div class="wrapper">
        <div class="row">
        <div class="col-sm-12">
        <section class="panel">
        <header class="panel-heading">
            Documents Sent This Month
            <span class="tools pull-right">
				<a class="fa fa-search"  data-toggle="modal" href="#myModal2"></a>

                <a href="javascript:;" class="fa fa-chevron-down"></a>
                <a href="javascript:;" class="fa fa-times"></a>
             </span>
        </header>
        <div class="panel-body">
        <div class="adv-table">
		
		<table id='dynamic-table2' name='dynamic-table2' class='table table-striped table-hover' width=100% >
		<thead>
		<?php
		
		$db=connectDb();
		

		
		if(isset($_POST['sentDocumentFilter'])){
			if($_POST['sentDocumentFilter']=="CLOSED"){
				$sentClause=" status like ('%CLOSED%%')";
				$from_date=date("Y-m-d H:i:s",strtotime($_POST['from_date']." 00:00:00"));
				$to_date=date("Y-m-d H:i:s",strtotime($_POST['to_date']." 00:00:00"));
				$dateClause=" and receive_date between '".$from_date."' and '".$to_date."'";
			
				$_SESSION['sentClause']=$sentClause;
				$_SESSION['dateClause']=$dateClause;
			
			}
			else if($_POST['sentDocumentFilter']=="SENT"){
				$sentClause=" status in ('SENT')";
				$from_date=date("Y-m-d H:i:s",strtotime($_POST['from_date']." 00:00:00"));
				$to_date=date("Y-m-d H:i:s",strtotime($_POST['to_date']." 00:00:00"));
				$dateClause=" and receive_date between '".$from_date."' and '".$to_date."'";
				$_SESSION['sentClause']=$sentClause;
				$_SESSION['dateClause']=$dateClause;

			}
			else if($_POST['sentDocumentFilter']=="FORWARD"){
				$sentClause=" status in ('FORWARDED')";
				$from_date=date("Y-m-d H:i:s",strtotime($_POST['from_date']." 00:00:00"));
				$to_date=date("Y-m-d H:i:s",strtotime($_POST['to_date']." 00:00:00"));
				$dateClause=" and receive_date between '".$from_date."' and '".$to_date."'";
				$_SESSION['sentClause']=$sentClause;
				$_SESSION['dateClause']=$dateClause;

			}
			else {
				$sentClause=" (status in ('SENT','FORWARDED') or status like '%CLOSED%%') ";
				$dateClause=" and receive_date like '".date("Y-m")."%%'";
		
			}		
		
		}
		else {
			$sentClause=" (status in ('SENT','FORWARDED') or status like '%CLOSED%%') ";
			$dateClause=" and receive_date like '".date("Y-m")."%%'";
		
		
		}
		
		if(isset($_SESSION['sentClause'])){
			$sentClause=$_SESSION['sentClause'];
			$dateClause=$_SESSION['dateClause'];
		}

		
	
		$sql="select * from document where ".$sentClause."  ".$dateClause." order by receive_date desc";

		$rs=$db->query($sql);
		$nm=$rs->num_rows;

		
		
		?>
		
		
		
		<tr>
			<th>Originating Office</th>
			<th>Subject</th>
			<th>Reference Number</th>
			<th>Document Type</th>
			<th>Document Date</th>
			<th>Last Status</th>	
		</tr>	
		</thead>
		
		
		<tbody>
		<?php

			for($i=0;$i<$nm;$i++){
			$row=$rs->fetch_assoc();
		?>	

		<tr>
			<td><?php echo getOriginatingOffice($db, $row['originating_office']); ?></td>
			<td><?php echo $row['subject']; ?></td>
			<td><?php echo calculateReferenceNumber($db,$row,adjustControlNumber($row['ref_id'])); ?></td>
			<td><?php echo $row['document_type']; ?></td>
			<td><?php echo date("Y-m-d", strtotime($row['document_date'])); ?></td>
			<td><?php echo $row['status']; ?></td>

		</tr>		
		<?php
		}
		?>
		
		</tbody>
		
		
		
		
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
                                        <form action="monthly_report.php" method='post' class="form-horizontal ">

                                        <div class="modal-body">
												<div class='form-group'>
                                                    <label class="control-label col-md-4">Type of Document</label>
													<div class='col-md-6'>
													<select class='form-control' name='sentDocumentFilter'>
													<option value='ALL'>All Documents Sent</option>
													<option value='CLOSED'>Sent and Closed Documents</option>
													<option value='FORWARD'>Sent and Forwarded as Outgoing</option>
													<option value='SENT'>Sent without Action</option>
													</select>													
													
													</div>
												</div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Date Range</label>
                                                    <div class="col-md-6">
                                                        <div class="input-group input-large custom-date-range" data-date="13/07/2013" data-date-format="mm/dd/yyyy">
                                                            <input type="text" class="form-control dpd1" name="from_date">
                                                            <span class="input-group-addon">To</span>
                                                            <input type="text" class="form-control dpd2" name="to_date">
                                                        </div>
                                                        <span class="help-block">Select date range</span>
                                                    </div>
                                                </div>

                                        </div>

                                        <div class="modal-footer">
                                            <button class="btn btn-primary" type="submit">Search</button>
                                        </div>
										</form>
                                    </div>
                                </div>
                            </div>
		



        </section>
 
       <section class="panel">
        <header class="panel-heading">
            Documents Still Awaiting Reply
            <span class="tools pull-right">
				<a class="fa fa-search"  data-toggle="modal" href="#awaitModal"></a>

                <a href="javascript:;" class="fa fa-chevron-down"></a>
                <a href="javascript:;" class="fa fa-times"></a>
             </span>
        </header>
        <div class="panel-body">
        <div class="adv-table">
		<?php
		
		if(isset($_POST['awaitDocsFilter'])){

			if($_POST['awaitDocsFilter']=="AR"){
				$awaitClause=" status in ('AWAITING REPLY')";
			}
			else if($_POST['awaitDocsFilter']=="GM"){
				$awaitClause=" status in ('FOR: CLARIFICATION','FOR: GM APPROVAL')";

			}
			else if($_POST['awaitDocsFilter']=="NR"){
				$awaitClause=" status in ('FOR: ROUTING')";

			}

			else if($_POST['awaitDocsFilter']=="IN"){
				$awaitClause=" status in ('INCOMPLETE')";
			}
			else {
				$awaitClause=" status in ('INCOMPLETE','FOR: ROUTING','AWAITING REPLY','FOR: CLARIFICATION','FOR: GM APPROVAL') ";
			}		
			
			$from_date=date("Y-m-d H:i:s",strtotime($_POST['from_date']." 00:00:00"));
			$to_date=date("Y-m-d H:i:s",strtotime($_POST['to_date']." 00:00:00"));
			$dateClause=" and receive_date between '".$from_date."' and '".$to_date."'";
			$_SESSION['awaitClause']=$awaitClause;
			$_SESSION['dateClause']=$dateClause;
			
		}
		else {
			$awaitClause=" status in ('INCOMPLETE','FOR: ROUTING','AWAITING REPLY','FOR: CLARIFICATION','FOR: GM APPROVAL') ";

			$dateClause=" and receive_date like '".date("Y")."-".date("m")."%%'";
			$month=date("m");
			$year=date("Y");


			
		}
		
		if(isset($_SESSION['awaitClause'])){
			$awaitClause=$_SESSION['awaitClause'];
			$dateClause=$_SESSION['dateClause'];
		}
		
		
		
		?>
		
		<table id='dynamic-table' name='dynamic-table' class='table table-striped table-bordered table-hover' width=100% >
		<thead>

		<tr>
			<th>Originating Office</th>
			<th>Subject</th>
			<th>Reference Number</th>
			<th>Document Type</th>
			<th>Document Date</th>
			<th>Last Status</th>	
		</tr>		
		</thead>
		<tbody>
		<?php
		$sql="select * from document where ".$awaitClause."  ".$dateClause." order by receive_date desc";
		$rs=$db->query($sql);
		$nm=$rs->num_rows;
//		echo $sql;


		?>	
		<?php

			for($i=0;$i<$nm;$i++){
			$row=$rs->fetch_assoc();
		?>			
		<tr>
			<td><?php echo getOriginatingOffice($db, $row['originating_office']); ?></td>
			<td><?php echo $row['subject']; ?></td>
			<td><?php echo calculateReferenceNumber($db,$row,adjustControlNumber($row['ref_id'])); ?></td>
			<td><?php echo $row['document_type']; ?></td>
			<td><?php echo date("Y-m-d", strtotime($row['document_date'])); ?></td>
			<td><?php echo $row['status']; ?></td>

		</tr>
		<?php
			}
		?>			
		
		
		</tbody>
		</table>
		</div>
		</div>
		
							<div class="modal fade" id="awaitModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title">Filter Search</h4>
                                        </div>
                                        <form action="monthly_report.php" method='post' class="form-horizontal ">

                                        <div class="modal-body">
												<div class='form-group'>
                                                    <label class="control-label col-md-4">Type of Document</label>
													<div class='col-md-6'>
													<select class='form-control' name='awaitDocsFilter'>
														<option value='ALL'>All Documents Awaiting Reply</option>
														<option value='NR'>Documents Not Routed</option>
														<option value='IN'>Documents with No Upload</option>
														<option value='AR'>Documments Awaiting Reply</option>
														<option value='GM'>Documments Awaiting GM Approval</option>
													</select>													
													
													</div>
												</div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Date Range</label>
                                                    <div class="col-md-6">
                                                        <div class="input-group input-large custom-date-range" data-date="13/07/2013" data-date-format="mm/dd/yyyy">
                                                            <input type="text" class="form-control dpd1" name="from_date">
                                                            <span class="input-group-addon">To</span>
                                                            <input type="text" class="form-control dpd2" name="to_date">
                                                        </div>
                                                        <span class="help-block">Select date range</span>
                                                    </div>
                                                </div>

                                        </div>

                                        <div class="modal-footer">
                                            <button class="btn btn-primary" type="submit">Search</button>
                                        </div>
										</form>

                                    </div>
                                </div>
                            </div>
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		</section>
       <section class="panel">
        <header class="panel-heading">
            Office Orders Issued This Month
            <span class="tools pull-right">
				<a class="fa fa-search"  data-toggle="modal" href="#orderModal"></a>

                <a href="javascript:;" class="fa fa-chevron-down"></a>
                <a href="javascript:;" class="fa fa-times"></a>
             </span>
        </header>
        <div class="panel-body">
        <div class="adv-table">
		
		<table id='dynamic-table3' name='dynamic-table3' class='table table-striped table-hover' width=100% >
		<thead>

		
		<tr>
			<th>Originating Office</th>
			<th>Subject</th>
			<th>Reference Number</th>
			<th>Document Type</th>
			<th>Document Date</th>
			<th>Last Status</th>	
		</tr>		
		</thead>
		<tbody>
		<?php
		
		if(isset($_POST['ordersDocsFilter'])){
			if($_POST['ordersDocsFilter']=="NS"){
				$orderClause=" and status in ('ISSUED')";
			}
			else if($_POST['ordersDocsFilter']=="IS"){
				$orderClause=" and status in ('ISSUED AND SENT')";

			}
			else {
				$orderClause=" and status in ('ISSUED','ISSUED AND SENT') ";
		
			}	

			$from_date=date("Y-m-d H:i:s",strtotime($_POST['from_date']." 00:00:00"));
			$to_date=date("Y-m-d H:i:s",strtotime($_POST['to_date']." 00:00:00"));
			$dateClause=" and receive_date between '".$from_date."' and '".$to_date."'";
			$_SESSION['orderClause']=$orderClause;
			$_SESSION['dateClause']=$dateClause;
			
		}		
		else {
			$orderClause=" and status in ('ISSUED AND SENT','ISSUED') ";
		
			$dateClause=" and receive_date like '%".date("Y")."-".date("m")."%%'";
			$month=date("m");
			$year=date("Y");		
		
		}

		if(isset($_SESSION['orderClause'])){
			$orderClause=$_SESSION['orderClause'];
			$dateClause=$_SESSION['dateClause'];
		}

		
		$sql="select * from document where document_type='ORD' ".$orderClause." ".$dateClause;
		$rs=$db->query($sql);
		
		$nm=$rs->num_rows;
		if($ordersNM==0){
			$nm=$ordersNM;
		}		
		
		
		?>
		
		
		<?php

		for($i=0;$i<$nm;$i++){
			$row=$rs->fetch_assoc();
		?>
		<tr>
			<td><?php echo $row['subject']; ?></td>
			<td>Office Order</td>
			<td><?php echo date("Y-m-d h:ia",strtotime($row['document_date'])); ?></td>
			<td><?php echo getOriginatingOffice($db,$row['originating_office']); ?></td>
			<td><?php echo $row['status']; ?></td>
		</tr>
		<?php
		}
		?>	
		
		
		</tbody>
		</table>
		</div>
		</div>


							<div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title">Filter Search</h4>
                                        </div>
                                        <form action="monthly_report.php" method='post' class="form-horizontal ">

                                        <div class="modal-body">
												<div class='form-group'>
                                                    <label class="control-label col-md-4">Type of Document</label>
													<div class='col-md-6'>
													<select class='form-control' name='ordersDocsFilter'>
														<option value='ALL'>All Office Orders</option>
														<option value='NS'>Office Orders Not Sent</option>
														<option value='IS'>Office Orders Issued and Sent</option>
													</select>													
													
													</div>
												</div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Date Range</label>
                                                    <div class="col-md-6">
                                                        <div class="input-group input-large custom-date-range" data-date="13/07/2013" data-date-format="mm/dd/yyyy">
                                                            <input type="text" class="form-control dpd1" name="from_date">
                                                            <span class="input-group-addon">To</span>
                                                            <input type="text" class="form-control dpd2" name="to_date">
                                                        </div>
                                                        <span class="help-block">Select date range</span>
                                                    </div>
                                                </div>

                                        </div>

                                        <div class="modal-footer">
                                            <button class="btn btn-primary" type="submit">Search</button>
                                        </div>
										</form>

                                    </div>
                                </div>
                            </div>












		</section>

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

<script type="text/javascript" src="js/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="js/bootstrap-daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="js/bootstrap-daterangepicker/daterangepicker.js"></script>
<script type="text/javascript" src="js/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<script type="text/javascript" src="js/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>

<!--pickers initialization-->
<script src="js/pickers-init.js"></script>


<!--common scripts for all pages-->
<script src="js/scripts.js"></script>
<style type='text/css'>
#exception:hover {
	background-color:white;

}

</style>

</body>
</html>
