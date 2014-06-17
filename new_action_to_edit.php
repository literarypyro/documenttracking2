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

  <title>Document Tracking System - Create a Document</title>

  <!--
    <link href="css/font-awesome.min.css" rel="stylesheet">
		-->
	<link href="css/jquery.stepy.css" rel="stylesheet">
	
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
  <link href="css/style-responsive.css" rel="stylesheet">
    <!--file upload-->
    <link rel="stylesheet" type="text/css" href="css/bootstrap-fileupload.min.css" />

 <link rel="stylesheet" type="text/css" href="css/bootstrap-modal.css" />
	
	
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
    <!-- left side end-->
    
    <!-- main content start-->
    <div class="main-content" >
        <div class="header-section">


        <!--
        <form class="searchform" action="index.html" method="post">
            <input type="text" class="form-control" name="keyword" placeholder="Search here..." />
        </form>
        -->

        <a class="toggle-btn"><i class="fa fa-bars"></i></a>

        <!--notification menu end -->
		<?php require("notification menu.php"); ?>

        </div>

        <!-- header section end-->

        <!-- page heading start-->
        <div class="page-heading">
            <h3>
                Create A New Document <small>date pickers, datetime pickers, time pickers and color pickers</small>
            </h3>
            <ul class="breadcrumb">
                <li>
                    <a href="#">New Action</a>
                </li>
                <li class="active"> Create A Document </li>
            </ul>
        </div>
        <!-- page heading end-->
        <!--toggle button start-->
        <!-- header section start-->

        <!--toggle button end-->

        <!--body wrapper start-->
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
				<div class="col-md-12">
				<section class='panel'>
				<div class='panel-body'>
				
                    <h4 class="fw-title">Document Creation</h4>
                    <div class="box-widget">
                        <div class="widget-head clearfix">
                            <div id="top_tabby" class="block-tabby pull-left">
                            </div>
                        </div>
                        <div class="widget-container">
                            <div class="widget-block">
                                <div class="widget-content box-padding">
                                    <form id="document_form" name='document_form'	class=" form-horizontal left-align form-well">
                                        <fieldset title="Step 1">
                                            <legend>Enter Document Details</legend>
                                            <div class="form-group">
                                                <label class="col-md-2 col-sm-2 control-label">Type of Document</label>
                                                <div class="col-md-6 col-sm-6">
												<select class="form-control" name='document_type' id='document_type'>
													<option value='MEMO'>Internal Document</option>
													<option value='ORD'>Office Order</option>
													<option value='IN'>Incoming (from outside MRT)</option>
													<option value='OUT'>Outgoing (to outside MRT)</option>
												</select>
                                                </div>
											</div>
                                            <div class="form-group">
                                                <label class="col-md-2 col-sm-2 control-label">Subject of Document</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input class='form-control' type='text' name='doc_subject' id='doc_subject' size=40 />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 col-sm-2 control-label">Classification of Document</label>
                                                <div class="col-md-6 col-sm-6">
                                                   
												<?php
												    $db=connectDb(); // retrieved from db_page
													retrieveClassListHTML2($db,"class_list",'classification',$_SESSION['department'])
												?>
                                                </div>

											</div>
                                            <div class="form-group">
                                                <label class="col-md-2 col-sm-2 control-label">Document Date</label>
                                                <div class="col-md-6 col-sm-6">
													<input size="16" type="text" value="<?php echo date("Y-m-d H:i"); ?>" readonly class="form_datetime form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 col-sm-2 control-label">Receive Date</label>
                                                <div class="col-md-6 col-sm-6">
													<input size="16" type="text" value="<?php echo date("Y-m-d H:i"); ?>" readonly class="form_datetime form-control">

                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2 col-sm-2 control-label">Originating Office</label>
                                                <div class="col-md-6 col-sm-6">
												<?php
												
												$db=connectDb(); // retrieved from db_page
												$departmentName=getDepartment($db,$_SESSION['division_code']); //retrieved from functions/general functions
												$sql="select * from originating_office where department_name='".$departmentName."'";
												$rs=$db->query($sql);
												$row=$rs->fetch_assoc();
												
												retrieveOfficeListHTML($db,$row['department_code'],'originating_office','origInput');  // retrieved from functions/form functions
												?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 col-sm-2 control-label">Originating Officer</label>
                                                <div class="col-md-6 col-sm-6">

												<?php
												$db=connectDb(); //retrieved from db_page

												$sql="select * from originating_officer";
												$rs=$db->query($sql);
												$nm=$rs->num_rows;
												?>
												<select class='form-control' id='originating_officer' name='originating_officer' onchange="checkAlternate(this,'OTHER','alterOfficer')">
													<?php
													for($i=0;$i<$nm;$i++){
														$row=$rs->fetch_assoc();
														?>
														<option data-division='<?php echo $row['division']; ?>' 
														<?php
														if($_SESSION['division_code']==$row['division']){ echo "selected"; } ?>
														value='<?php echo $row['id']; ?>'><?php echo $row['name']; ?></option>
														<?php
													}
													?>
													<option value='OTHER'>OTHER</option>
													<?php
													?>	
													</select>
													<input type='text' class='form-control' id='alterOfficer' name='alterOfficer' disabled=true />
                                                </div>
                                            </div>											
                                            <div class="form-group">
                                                <label class="col-md-2 col-sm-2 control-label">Security Level</label>
                                                <div class="col-md-6 col-sm-6">
													<select class='form-control' name='security'>
													<option value='unsecured'>Accessible to All Divisions</option>
													<option value='GMsecured'>GM Level</option>
													<option value='divSecured'>Division Level</option>
													</select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 col-sm-2 control-label">Upload Document Here</label>
                                                <div class="col-md-6 col-sm-6">
												
												<div class="fileupload fileupload-new" data-provides="fileupload">
														<span class="btn btn-default btn-file">
														<span class="fileupload-new"><i class="fa fa-paper-clip"></i> Select file</span>
														<span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
														<input type="file" class="default" id='document' name='document' />
														<input type='hidden' name="MAX_FILE_SIZE" value="4000000" />
														</span>
													<span class="fileupload-preview" style="margin-left:5px;"></span>
													<a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none; margin-left:5px;"></a>
												</div>
												</div>
												
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 col-sm-2 control-label">Reference Number</label>
                                                <div class="col-md-6 col-sm-6">
												<?php

												$reference_stamp=date("m.y");
												$db=connectDb();

												$sql="select max(id) from reference_increment";

												$rs=$db->query($sql);
												$nm=$rs->num_rows;
												if($nm>0){
													$row=$rs->fetch_assoc();
													$controlId=$row['id']*1+1;
												}
												else {
													$controlId=1;
												}

												?>
												<label class='control-label pull-left' ><span id='reference_stamp' name='reference_stamp'><?php echo $reference_stamp; ?></span>.<?php echo adjustControlNumber($controlId); ?>.<?php echo $_SESSION['division_code']; ?></label>

												</div>
                                            </div>
										</fieldset>
                                        <fieldset title="Step 2">
                                            <legend>Provide Routing Details</legend>
                                            <?php 
											$db=connectDb();
											?>

											
											<div class="form-group">
                                                <label class="col-md-2 col-sm-2 control-label">Officer Name</label>
                                                <div class="col-md-6 col-sm-6">
													<?php
													retrieveOfficerListHTML2($db,"from_name",$_SESSION['department']);  // retrieved from functions/form functions
													?>
                                                </div>
                                            </div>
											<div id='other_details' name='other_details' style='display:none'>
                                            <div class="form-group">
                                                <label class="col-md-2 col-sm-2 control-label">(Other) Name</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input type="text" id='alterOfficer' name='alterOfficer' placeholder="Name" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 col-sm-2 control-label">(Other) Position</label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input type="text" name='alterPosition' id='alterPosition' placeholder="Position" class="form-control">
                                                </div>
                                            </div>

											</div>
                                            <div class="form-group">
                                                <label class="col-md-2 col-sm-2 control-label">Destinations</label>
                                                <div class="col-md-6 col-sm-6">
													<!--
													<div class="btn-group">
													-->		
														<a class="btn btn-primary" data-toggle="modal" href="#classModal">Define List <i class="fa fa-plus"></i></a>
													<!--
													</div>
													-->
													<!-- Modal -->
													  <div class="modal fade" id="classModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
														<div class="modal-dialog" style='width:800px;'>
														  <div class="modal-content">
															<div class="modal-header">
															  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
															  <h4 class="modal-title">Select Destinations</h4>
															</div>
															<div class="modal-body">
															<table>
															<tr>
															
																	<td colspan=3 valign=top><input type='checkbox' name='stn_all' id='stn_all' onclick='selectAll(this)' /> ALL DIVISIONS</td>
																	<td>
																	
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
																	
																	<td valign=top><input id='stn_<?php echo $row['department_code']; ?>' name='stn_<?php echo $row['division_code']; ?>' class='division_unit' type='checkbox'  onclick='updateDestList("stn_<?php echo $row['department_code']; ?>","<?php echo $row['department_name']; ?>")' /> <?php echo $row['department_name']; ?></td>

																	<td style='display:none' id="label_officer_stn_<?php echo $row['department_code']; ?>" name="label_officer_stn_<?php echo $row['department_code']; ?>"><?php //retrieveOfficerListHTML2($db,"officer_stn_".$row['department_code'],$row['department_code']); ?>
																	
																	<a href='#' onclick="loadOfficerName('<?php echo $row['department_code']; ?>','officerModal_<?php echo $row['department_code']; ?>')" title='Officer Name'><i class='fa fa-group'></i></a>
																	<a href='#actionModal_<?php echo $row['department_code']; ?>'  data-toggle='modal' title='Action'><i class='fa fa-edit'></i></a>
																	<a href='#remarksModal_<?php echo $row['department_code']; ?>' data-toggle='modal' title='Additional Remarks'><i class='fa fa-comment'></i></a>
																	<a href='#uploadModal_<?php echo $row['department_code']; ?>' data-toggle='modal' title='Upload File (Optional)'><i class='fa fa-paperclip'></i></a>

																	</td>
																			
																	

																	<td  style='display:none;' name='label_action_stn_<?php echo $row['department_code']; ?>' id='label_action_stn_<?php echo $row['department_code']; ?>'>
																	<div class="modal fade" data-focus-on="input:first" id="remarksModal_<?php echo $row['department_code']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
																						<textarea class='form-control'></textarea>
																						
																					</div>
																				</div>
																			</div>
																			<div class="modal-footer">
																			   <!--
																			   <button data-dismiss="modal" class="btn btn-primary" type="submit">Close</button>
																				-->
																			   <button class="btn btn-primary" type="button" data-dismiss='modal'>Save</button>
																			</div>
																			</form>		
																			
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
																			<form action="#" method="post" class="form-horizontal ">
																			
																			<div class="modal-body">
																				<div class="form-group">
																					<label class="control-label col-md-2">Action</label>
																					<div class="col-md-6">
																						<select name='action_stn_<?php echo $row['department_code']; ?>' onchange="updateAction('action_stn_<?php echo $row['department_code']; ?>')" id='action_stn_<?php echo $row['department_code']; ?>'>	
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
																			   <button class="btn btn-primary" type="button"  data-dismiss='modal'>Save</button>
																			</div>
																			</form>		
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
																			<form action="#" method="post" class="form-horizontal ">
																			
																			<div class="modal-body">
																				<div class="form-group">
																					<label class="control-label col-md-4">Upload</label>
																					<div class="col-md-6">
																						<div class="fileupload fileupload-new" data-provides="fileupload">
																							<span class="btn btn-default btn-file">
																							<span class="fileupload-new"><i class="fa fa-paper-clip"></i> Select file</span>
																							<span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
																							<input type="file" class="default" id='routing_file' name='routing_file' />
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
																			   <button class="btn btn-primary" type="button"  data-dismiss='modal'>Save</button>
																			</div>
																			</form>		
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
																			<form action="#" method="post" class="form-horizontal ">
																			
																			<div class="modal-body">
																				<div class="form-group">
																					<label class="control-label col-md-4">Officer Name</label>
																					<div class="col-md-6">
																						<select name='officer_list_<?php echo $row['department_code']; ?>' id='officer_list_<?php echo $row['department_code']; ?>' onchange='changeOfficerName(this)'>
																						
																						</select>
																						
																					</div>
																				</div>
																			</div>
																			<div class="modal-footer">
																			   <!--
																			   <button data-dismiss="modal" class="btn btn-primary" type="submit">Close</button>
																				-->
																			   <button class="btn btn-primary" type="button"  data-dismiss='modal'>Save</button>
																			</div>
																			</form>		
																			
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
															
															<div class="modal-footer">
															<!--
																<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
															-->


															  <button type="button" data-dismiss="modal" class="btn btn-primary">Save changes</button>
															</div>
															</div><!-- /.modal-content -->
														</div><!-- /.modal-dialog -->
													  </div><!-- /.modal -->












													

												   <table id='destination_table' class="display table table-bordered table-striped" name='destination_table'>
														<tr>
															<th>Division/Unit</th>
															<th>Officer Name</th>
															<th>Action</th>								
														</tr>
													</table>
                                                </div>
										   </div>
                                            <div class="form-group" style='display:none'>
												<input type='text' name='target_list' id='target_list' />


										   </div>
											
                                        </fieldset>
                                        <fieldset title="Step 3">
                                            <legend>Verify Password to Submit</legend>
                                            <div class="form-group">


											<label class="col-md-2 col-sm-2 control-label">Enter Your User Password to Submit</label>
                                                <div class="col-md-6 col-sm-6">
												<input class='form-control' type=password id='passcode' name='passcode' onkeyup='verifyPassCode(this)' /><span id='passcode_verify' name='passcode_verify' data-passcode='<?php echo $_SESSION['passcode']; ?>'></span>
                                                </div>
                                            </div>
                                        </fieldset>
                                        <button type="submit" id='fin_button' name='fin_button' class="finish btn btn-info btn-extend" disabled> Finish!</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
				
				
				</div>
				</section>
                </div>

			</div>
      		
		
		
        </div>
        <!--body wrapper end-->

        <!--footer section start-->
        <footer>
            2014 &copy; AdminEx by ThemeBucket
        </footer>
        <!--footer section end-->


    </div>
    <!-- main content end-->
</section>
</div>
<!-- Placed js at the end of the document so the pages load faster -->


<script src="js/jquery-1.10.2.min.js"></script>
<script src="js/jquery-ui-1.9.2.custom.min.js"></script>
<script src="js/jquery-migrate-1.2.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>

<script src="js/modernizr.min.js"></script>
<script src="js/jquery.nicescroll.js"></script>
<!--pickers plugins-->
<script type="text/javascript" src="js/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="js/bootstrap-daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="js/bootstrap-daterangepicker/daterangepicker.js"></script>
<script type="text/javascript" src="js/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<script type="text/javascript" src="js/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="js/jquery.stepy.js"></script>


<!--pickers initialization-->
<script src="js/pickers-init.js"></script>

<!--file upload-->
<script type="text/javascript" src="js/bootstrap-fileupload.min.js"></script>

<script src="js/bootstrap-modal.js"></script>
<script src="js/bootstrap-modalmanager.js"></script>
 <script language='javascript' src="ajax.js"></script>

<!--common scripts for all pages-->
<script src="js/scripts.js"></script>
<script>
    /*=====STEPY WIZARD====*/
    $(function() {
        $('#default').stepy({
            backLabel: 'Previous',
            block: true,
            nextLabel: 'Next',
            titleClick: true,
            titleTarget: '.stepy-tab'
        });
    });
    /*=====STEPY WIZARD WITH VALIDATION====*/
    $(function() {
        $('#document_form').stepy({
            backLabel: 'Back',
            nextLabel: 'Next',
            errorImage: true,
            block: true,
            description: true,
            legend: false,
            titleClick: true,
            titleTarget: '#top_tabby',
            validate: true
        });
        $('#document_form').validate({
            errorPlacement: function(error, element) {
                $('#document_form div.stepy-error').append(error);
            },
            rules: {
              //  'name': 'required',
               // 'email': 'required',
				//'document':'required',
				//'doc_subject':'required',
				//'passcode':'required'
//				'target_list':'required'
            },
            messages: {
               /* 'name': {
                    required: 'Name field is required!'
                },
                'email': {
                   required: 'Email field is requerid!'
                },
				'doc_subject':{
					required: 'You must provide a Document subject'
				},
				'document':{
					required: 'You must upload a soft copy of the document.'
				},
				'passcode':{
					required: 'You must enter your password to continue.'
				}
				*/

/*				'target_list':{
					required: 'You must provide a destination to your document.'
				}
*/				
            }
        });
    });
	
	
</script>
<script language='javascript'>
var destination_count=0;


function updateDestList(division,division_name){
	
	if($('#'+division).prop('checked')==true){

		var url="ajax_processing.php?originating_officer="+division.replace("stn_","");
		
		makeajax(url,"generateList");	


		$("#label_officer_"+division).show();
		$("#label_action_"+division).show();
		
		var officer_name=$("#officer_list_"+division).find("option:selected").data('name');
		var action=$("#action_"+division).find("option:selected").data('action');

		$('#destination_table').append("<tr id='row_"+division+"' name='row_"+division+"'><td>"+division_name+"</td><td name='tag_officer_"+division+"' id='tag_officer_"+division+"'></td><td name='tag_action_"+division+"' id='tag_action_"+division+"'>"+action+"</td></tr>");	
	
		destination_count++;
	}
	else {
		$("#label_officer_"+division).hide();
		$("#label_action_"+division).hide();
		$('#row_'+division).remove();
		destination_count--;
	
	
	}
	updateDestinationCount();
}
function updateAction(action){
		var action_content=$("#"+action).find("option:selected").data('action');
	
		$('#tag_'+action).html(action_content);
}

function processChange(officer){
		//$('#alterOfficer').val($(this).find("option:selected").data('name'));
		//$('#alterPosition').val($(this).find("option:selected").data('position'));
		
		//$("#tag_officer_stn_"+$(officer).find("option:selected").data('row-division')).html($(officer).find("option:selected").data('name'));


}

function updateDestinationCount(){
	if(destination_count==0){
		$('#target_list').val(0);
	}
	else {
		$('#target_list').val(destination_count);
	}


}

function verifyPassCode(passElement){
	if(passElement==$('#passcode_verify').data('passcode')){
		$('#fin_button').prop('disabled','false');
	}
	else {
		$('#fin_button').prop('disabled','true');
	}


}

function generateList(responseJSON){
	
	var response=JSON.parse(responseJSON);	
	var report_title="";
	var division=response.division;
	report_title=response.report_title;
	var report_contents="";	
	
//	report_contents+="<select name='officer_"+division+"' id='officer_"+division+"'>";
	
	var division="";
	for(var n=0;n<response.record_count;n++){
		if(n==0){
			division=response.officer[n].division;
			
		}
		report_contents+="<option data-row-division='"+response.officer[n].division+"' data-division='"+response.officer[n].division+"' data-name='"+response.officer[n].name+"' value='"+response.officer[n].level+"'>"+response.officer[n].name+"</option>";
	}
	
	
//	report_contents+="</select>";
	
	$('#officer_list_'+division).html(report_contents);
	
	
	$('#tag_officer_stn_'+division).html(response.officer[0].name);	
	
	
}

function loadOfficerName(division){
	var url="ajax_processing.php?originating_officer="+division;

	
	$('#officerModal_'+division).modal('show');

}

function selectAll(element){
	if($(element).prop('checked')==true){
//		$('.division_bar').prop('class','division_bar col-md-6');

//		$('.division_unit').prop('checked',true);

//		$('.icon_bar').show();

		}
	else {
//		$('.division_bar').prop('class','division_bar col-md-10');

//		$('.division_unit').prop('checked',false);

//		$('.icon_bar').hide();
	
	}
	
}

function changeOfficerName(officer){

	$('#tag_officer_stn_'+$(officer).find("option:selected").data("division")).html($(officer).find("option:selected").data("name"));	


}
</script>
</body>
</html>
