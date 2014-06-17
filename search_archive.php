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
                Search in Archive <small>date pickers, datetime pickers, time pickers and color pickers</small>
            </h3>
            <ul class="breadcrumb">
                <li>
                    <a href="#">New Action</a>
                </li>
                <li class="active"> Search in Archive </li>
            </ul>
        </div>

        <div class="wrapper">
        <div class="row">
        <div class="col-sm-12">
        <section class="panel">
        <header class="panel-heading">
            Document Archives
            <span class="tools pull-right">
				<a class="fa fa-search"  data-toggle="modal" href="#myModal2"></a>

                <a href="javascript:;" class="fa fa-chevron-down"></a>
                <a href="javascript:;" class="fa fa-times"></a>
             </span>
        </header>
        <div class="panel-body">
        <div class="adv-table">
		
		
		
        <table  class="display table table-bordered table-striped" id="dynamic-table">
			<thead>
				<tr>
				<th>Subject</th>
				<th>Originating Office</th>
				<th>Document Date</th>
				<th>Received Date</th>
				<th>Reference Number</th>
				</tr>			
			</thead>
			<?php
			$db=connectDb();

			
			if(isset($_POST['from_date'])){
				$from_date=date("Y-m-d H:i:s",strtotime($_POST['from_date']." 00:00:00"));
				
				$to_date=date("Y-m-d H:i:s",strtotime($_POST['to_date']." 23:23:59"));
				
				$dateClause=" where receive_date between '".$from_date."' and '".$to_date."'";	
				
			}
			else {
				$dateClause=" where receive_date receive_date like '".date("Y-m")."%%'";	
			
			}

			
			if(isset($_POST['subject'])){
				$subjectClause=" and subject like '%%".str_replace(' ','%',$_POST['subject'])."%%'";
			}
			
			if(isset($_POST['classification'])){
				$class=$_POST['classification'];
				$classificationClause=" and classification_id='".$class."'";
			
			}
			if(isset($_POST['reference'])){
				$referenceClause=" and (select reference_id from document_receipt where document_id=document.ref_id) like '%%".$_POST['reference']."%%'";
			}
			
			
			
			
			
			$searchSQL="select * from document ".$dateClause.$subjectClause.$classificationClause.$referenceClause." and ((sending_office='".$_SESSION['division_code']."' and security in ('divSecured','GMsecured')) or (security='unsecured'))"; 	
			$searchRS=$db->query($searchSQL);	

			$searchNM=$searchRS->num_rows;

			
			
			
			
			
			
			
			
			?>
			<tbody>
			<?php
			for($i=0;$i<$searchNM;$i++){
			$searchRow=$searchRS->fetch_assoc();
			?>
				<tr>
					<td><?php echo $searchRow['subject']; ?></td>
					<td><?php echo getOriginatingOffice($db,$searchRow['originating_office']); ?></td>
					<td><?php echo $searchRow['document_date']; ?></td>
					<td><?php echo $searchRow['receive_date']; ?></td>
					<td><?php echo calculateReferenceNumber($db,$searchRow,adjustControlNumber($searchRow['ref_id'])); ?></td>
				</tr>
			<?php
			}
			?>
			</tbody>
			<tfoot>
				<tr>
				<th>Subject</th>
				<th>Originating Office</th>
				<th>Document Date</th>
				<th>Received Date</th>
				<th>Reference Number</th>
				</tr>			
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
                                            <form action="search_archive.php" method=post class="form-horizontal ">

                                        <div class="modal-body">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Subject</label>
                                                    <div class="col-md-6">
														<input class='form-control'  type='text' name='subject' />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Classification</label>
                                                    <div class="col-md-6">
														<select class='form-control' name='classification' id='classification' onchange='checkAlternate(this,5,"classification_search")' >
														<option></option>
														<?php

														$db=connectDb();
														$sql="select * from classification inner join division_classification on classification.id=classification_id where division_id in ('ALL','".$_SESSION['division_code']."') order by classification_desc";
														$rs=$db->query($sql);

														$nm=$rs->num_rows;

														for($i=0;$i<$nm;$i++){
															$row=$rs->fetch_assoc();
												?>			
															<option value='<?php echo $row['id']; ?>'><?php echo $row['classification_desc']; ?></option>

												<?php
														}
														?>
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
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Reference Number</label>
                                                    <div class="col-md-6">
														<input class='form-control' type='text' name='reference' />
                                                    </div>
                                                </div>

                                        </div>

                                        <div class="modal-footer">
                                            <button  class="btn btn-primary" type="submit">Search</button>
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
