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
		
		<?php require("notification menu.php"); ?>
		
        <!--notification menu end -->

        </div>

        <!-- header section end-->

		
        <!-- page heading start-->
        <div class="page-heading">
            <h3>
                Records Monitoring <small>date pickers, datetime pickers, time pickers and color pickers</small>
            </h3>
            <ul class="breadcrumb">
                <li>
                    <a href="#">Records Officer</a>
                </li>
                <li class="active"> Monitoring Panel</li>
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
		$db=connectDb();

		if(isset($_POST['active_documents'])){
			$department=getDepartment($db,$_POST['active_documents']);
			$rs=topActiveDocumentsDivision($db,$_POST['active_documents']);

		}	
		else {	
			$rs=topActiveDocuments($db);
				
		}	
			
			
			
		$nm=$rs->num_rows;
			
			
			
		?>	
        <div class="wrapper">
        <div class="row">
        <div class="col-sm-12">
        <section class="panel">
        <header class="panel-heading">
            Most Active Documents <?php if(isset($_POST['active_documents'])){ echo "for ".$department; } ?>
            <span class="tools pull-right">
				<a class="fa fa-cogs"  data-toggle="modal" href="#filterActive"></a>

                <a href="javascript:;" class="fa fa-chevron-down"></a>
                <a href="javascript:;" class="fa fa-times"></a>
             </span>
        </header>
        <div class="panel-body">
        <div class="adv-table">
		
		
		<?php

		
		
		?>	
		
        <table  class="display table table-bordered table-striped" id="active_dt" name='active_dt'>
			<thead>
			<tr>
			<th>Office Code</th>
			<th>Document Date</th>
			<th>Subject</th>
			<th>Reference Number</th>
			<th>Status</th>
			</tr>
			</thead>
			<tbody>
			<?php
				for($i=0;$i<$nm;$i++){
				$row=$rs->fetch_assoc();
			?>
			<tr>
				<td><?php echo $row['sending_office']; ?></td>
				<td><?php echo $row['document_date']; ?></td>
				<td><?php echo $row['subject']; ?>
				<a href='download.php?refId=<?php echo $row['ref_id']; ?>' target='_blank' class='pull-right'><i class="fa fa-file-text"></i></a>
				</td>
				<td><?php echo calculateReferenceNumber($db,$row,adjustControlNumber($row['ref_id'])); ?></td>
				<td><a href="document_history.php?view=<?php echo $row['ref_id']; ?>" ><?php echo $row['status']; ?></a></td>
			</tr>
			<?php
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
		<div class="modal fade" id="filterActive" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title">Filter Search</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form method='post' action="records_monitoring.php" class="form-horizontal ">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4"> Specify Division</label>
                                                    <div class="col-md-6">
														<select name='active_documents' id='active_documents'>	
													<?php				
													$sql="select * from department order by department_code";
													$rs=$db->query($sql);
													$nm=$rs->num_rows;
														for($i=0;$i<$nm;$i++){
														$row=$rs->fetch_assoc();
													?>		
														<option value='<?php echo $row['department_code']; ?>'><?php echo $row['department_name']; ?></option>
													<?php	
														}
													?>	
														</select>
                                                    </div>
                                                </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-primary" type="submit">Sort</button>
                                        </div>
											</form>
                                    </div>
                                </div>
                            </div>
		



        </section>

        <section class="panel">
        <header class="panel-heading">
            Recent Documents
            <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
                <a href="javascript:;" class="fa fa-times"></a>
             </span>
        </header>
        <div class="panel-body">
        <div class="adv-table">
		<?php
		$sql="select * from document order by receive_date desc";
		$rs=$db->query($sql);
		$nm=$rs->num_rows;
		
		?>
		
		
        <table  class="display table table-bordered table-striped" id="recent_dt">
			<thead>
			<tr>
			<th>Office Code</th>
			<th>Document Date</th>
			<th>Subject</th>
			<th>Reference Number</th>
			<th>Status</th>
			</tr>
			</thead>
			<tbody>
			<?php 
			for($i=0;$i<$nm;$i++){
				$row=$rs->fetch_assoc();
			?>
				<tr>
				<td><?php echo $row['sending_office']; ?></td>
				<td><?php echo $row['document_date']; ?></td>
				<td><?php echo $row['subject']; ?>
				<a href='download.php?refId=<?php echo $row['ref_id']; ?>' target='_blank' class='pull-right'><i class="fa fa-file-text"></i></a>
				
				
				
				</td>
				<td><?php echo calculateReferenceNumber($db,$row,adjustControlNumber($row['ref_id'])); ?></td>
				<td><a href="document_history.php?view=<?php echo $row['ref_id']; ?>" ><?php echo $row['status']; ?></a></td>
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

<!--common scripts for all pages-->
<script src="js/scripts.js"></script>


<!--file upload-->
<script type="text/javascript" src="js/bootstrap-fileupload.min.js"></script>
<!--icheck -->
<script src="js/iCheck/jquery.icheck.js"></script>
<script src="js/icheck-init.js"></script>



</body>
</html>
