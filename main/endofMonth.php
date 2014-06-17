<?php
session_start();
?>
<style type='text/css'>
	.container {
		margin-left:30%;
		float:left;
		width:60%;
	}
</style>	
<?php
if(isset($_POST['records_username'])){
	$message="";
	if($_POST['records_username']==$_SESSION["username"]){
		$message="";	
		
		$db2=retrieveUsersDb();
		
		$message=verifyUser($db2,$_POST['records_username'],$_POST['records_password']);
		
		if($message=="Okay for access."){
			echo '<meta http-equiv="refresh" content="0;url=end of the month report.php" />';
		
		}
		else {
			echo $message;	
		}
	}
	else {
		$message="Error.  This is not your ID!";
		echo $message;
	}
}

else {
?>
<div class="container" >

<div class="col-lg-6" >
<div class="well">

<form class="bs-example form-horizontal"  action='receiveDocument.php?pp=6#EOMR' method='post'>
	<fieldset>
	
	
	<legend>Prepare End of the Month Report</legend>
	<div class="form-group">
		<label class="col-lg-4 control-label">Enter Your Username:</label>
		<div class="col-lg-6">

		<input type=text class="form-control" name='records_username' />
		
		</div>
	</div>
	<div class="form-group">
		<label class="col-lg-4 control-label">Password:</label>
		<div class="col-lg-6">
			<input class="form-control" type=password name='records_password' />
		</div>

	</div>
	<div class="form-group">

		<input class="btn btn-primary" type=submit value='Preview Report' />
	</div>
	</fieldset>
</form>
</div>
</div>
</div>
<?php
}
?>