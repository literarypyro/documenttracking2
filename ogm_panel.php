<?php
session_start();
?>
<?php
require("db.php");
require("functions/document functions.php");
require("functions/general functions.php");
require("functions/routing process.php");
require("functions/user functions.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="ThemeBucket">
  <link rel="shortcut icon" href="#" type="image/png">

  <title>Document Tracking System - Control Panel (OGM)</title>

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

        <!--notification menu end -->

        </div>

        <!-- header section end-->

		
        <!-- page heading start-->
        <div class="page-heading">
            <h3>
                Control Panel (OGM) <small>date pickers, datetime pickers, time pickers and color pickers</small>
            </h3>
            <ul class="breadcrumb">
                <li>
                    <a href="#">Other Options</a>
                </li>
                <li class="active"> OGM Page </li>
            </ul>
        </div>

        <div class="wrapper">
        <div class="row">
        <div class="col-sm-12">
        <section class="panel">
        <header class="panel-heading">
            Documents Needing Approval
            <span class="tools pull-right">
				<a class="fa fa-cogs"  data-toggle="modal" href="#myModal2"></a>

                <a href="javascript:;" class="fa fa-chevron-down"></a>
                <a href="javascript:;" class="fa fa-times"></a>
             </span>
        </header>
        <div class="panel-body">
        <div class="adv-table">
		
		
		
        <table  class="display table table-bordered table-striped" id="dynamic-table">
			<thead>
			<tr>
				<th>Document Date</th>
				<th>Subject</th>
				<th>Reference Number</th>
				<th>Latest Request/Action</th>
				<th>Originating Office</th>
				<th>Destination Office</th>
				<th>Status</th>
				<th>Action</th>
			</tr>
			</thead>
			<?php
				$db=connectDb();
				$sql="select * from document where status='FOR: GM APPROVAL'";
				
				$rs=$db->query($sql);
				$nm=$rs->num_rows;
	
	
	
			?>
			<tbody>
			<?php 
			for($i=0;$i<$nm;$i++){
				$row=$rs->fetch_assoc();
				$referenceNumber=calculateReferenceNumber($db,$row,adjustControlNumber($row['ref_id']));
				
				$sql2="select * from document_routing where reference_no='".$row['ref_id']."' order by request_date desc";
				$rs2=$db->query($sql2);
				$row2=$rs2->fetch_assoc();
				
				$sql3="select * from routing_targets where routing_id='".$row2['id']."'";
				$rs3=$db->query($sql3);
				$row3=$rs3->fetch_assoc();
				$action=getAction($db,$row3['action_id']);

			?>
			<tr>
				<td><?php echo $row['document_date']; ?>
				<a href='download.php?refId=<?php echo $row['ref_id']; ?>' target='_blank' class='pull-right'><i class="fa fa-file-text"></i></a>				
				</td>
				<td><?php echo $row['subject']; ?></td>
				<td><?php echo $referenceNumber; ?></td>
				<td><?php echo $action; ?></td>
				<td><?php echo $row['sending_office']; ?></td>
				<td><?php echo $row3['destination_office']; ?></td>
				<td><a style="color:red" href="document_history.php?view=<?php echo $row['ref_id']; ?>"><?php echo $row['status']; ?></a></td>
				<td>
				
			    <div class="btn-group">
					<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action <i class="fa fa-angle-down"></i>
					</button>
						<ul class="dropdown-menu pull-right">
                        <li><a href="#">Approve</a></li>
                        <li><a href="#">Reject</a></li>
                        <li><a href="#">Needing Clarification</a></li>
                        </ul>
                </div>					
				</td>
			</tr>
			<?php
			}
			?>
			<tfoot>
        <tr>
			<th>Document Date</th>
			<th>Subject</th>
			<th>Reference Number</th>
			<th>Latest Request/Action</th>
			<th>Originating Office</th>
			<th>Destination Office</th>
			<th>Status</th>
			<th>Action</th>
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

</body>
</html>
