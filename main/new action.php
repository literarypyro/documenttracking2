<style type='text/css'>
	.container {
		margin-left:30%;
		float:left;
		width:50%;
	}
</style>
<div class='container' >
<div class="col-lg-6">
<div class="well">
<form class="bs-example form-horizontal" action='submit.php' method='post'>
<fieldset>
	<legend>New Action</legend>
	<div class="form-group">
	<?php 
	if($_SESSION['department']=='OGM'){
	?>
	<label class="col-lg-4 control-label">
	Enter Action:
	</label>	
	<div class="col-lg-6">

	<select class="form-control" name='document_action'>
		<option value='REC'>Receive New Document</option>
		<option value='ISS'>Issue New Document</option>
	</select>


	</div>
	<?php	
	}
	else {
	?>
	<label class="col-lg-10">
	<?php
		echo "<input type=hidden name='document_action' value='ISS' />";
	?>
	</label>
	<?php
		}
	?>
	</div>
	<div class="form-group">
	<label class="col-lg-4 control-label">
	Type of Document
	</label>
	<div class="col-lg-6">
	<select class="form-control" name='document_type'>
		<option value='IN'>Incoming</option>
		<option value='MEMO'>Internal Document</option>
	<?php 
	if($_SESSION['department']=='REC'){
	?>
	<!--
		<option value='ORD'>Office Order</option>
	-->
	<?php
	}
	?>
		<option value='OUT'>Outgoing</option>
	</select>
	</div>
	</div>
	<div class="form-group">
	<input type=submit class="btn btn-primary" value='Process' />
	</div>
</fieldset>
</form>
</div>
</div>
</div>