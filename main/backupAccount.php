<style type='text/css'>
#exception td {
background-color: white;
}
.container {
	margin-left:30%;
	float:left;
	width:60%;
}
</style>
<div class="container">
<div class="col-lg-8" >
<div class="well">


<form class="bs-example form-horizontal" action='submit.php' method=post >
<fieldset>
<legend>
BACK-UP ACCOUNT
</legend>

<div class='form-group'>
<div class='col-lg-10'>
	<label class='control-label'>
<?php
$db=retrieveUsersDb();
$status=backupStatus($db);
if($status=="true"){ echo "[Currently Enabled] Your account will be disabled in the next login"; } else { echo "[Currently Disabled] Your account is currently enabled"; }
?>
	</label>
</div>
</div>

<div class='form-group'>
	<label class='col-lg-4 control-label'>
	
	</label>


<div class='checkbox col-lg-5'>
<label>
<input type='checkbox' name='enableBackup' value="on">
Enable/Disable Back-up Account
</label>
</div>
</div>
<div class='form-group'>
	
	<label class='col-lg-4 control-label'>Confirm (Enter your Password):</label>
	<div class='col-lg-4'>
	
	<input class='form-control' type=password name='records_password' /><input type=hidden value='<?php echo $_SESSION['username']; ?>' name='records_username' />
	</div>
</div>	

<div class='form-group'>
	
	<input type='submit' class="btn btn-primary"   value='Confirm' />

</div>
</fieldset>
</form>
</div>
</div>
</div>
<?php

?>