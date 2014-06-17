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
		
			$rs=recentlyLogged($db2);
			$nm=$rs->num_rows;
			if($nm>0){
				
		?>
		
		
		
		
		
		<table class='table table-striped' width=100%>
		<thead>
		<tr>
		<th colspan=7><h2>Recently Logged Users</h2></th>
		<tr>
			<th>&nbsp;</th>
			<th>Username</th>
			<th>Division</th>
			<th>Login Time</th>
		</tr>
		</thead>
		<tbody>
		<?php
				for($i=0;$i<5;$i++){
				$row=$rs->fetch_assoc();	
		?>
		<tr>
			<td><?php echo $row['lastName'].", ".$row['firstName']; ?></td>
			<td><?php echo $row['username']; ?></td>
			<td><?php echo $row['deptCode']; ?></td>
			<td><?php echo $row['time']; ?></td>
		</tr>
		<?php
				}
			}
			else {
				echo "No User has logged on.";
			}
		?>
		</tbody>
		
		
		</tr>
		</table>
		<table class="bs-example form-horizontal" >
		<tr>
		<td class='col-lg-6'>
			<div class='well'>
			
			<form  class="bs-example form-horizontal" action='user_list.php?list=user' method='post'>
				<legend>Access List of Users</legend>
				<div class='form-group'>
				<label class='col-lg-4 control-label'>Enter Your Password:</label>
					<div class='col-lg-8'>
					<input class='form-control' type=password name='records_password' /><input type=hidden value='<?php echo $_POST['records_username']; ?>' name='records_username' />
					<input  class="btn btn-primary"  type=submit value='User Access' />
					</div>
				
				</div>
			</form>
			</div>
		</td>
		<td class='col-lg-6'>

			<div class='well'>		
		
			<form  class="bs-example form-horizontal" action='user_list.php?list=div' method='post'>
				<legend>Access Division Settings</legend>
				<div class='form-group'>
				<label class='col-lg-4 control-label'>Enter Your Password:</label>
					<div class='col-lg-8'>
					<input class='form-control'  type=password name='records_password' /><input type=hidden value='<?php echo $_POST['records_username']; ?>' name='records_username' />
					<input  class="btn btn-primary"  type=submit value='Division Access' />
					</div>
				</div>
			</form>
			</div>
		</td>
		</tr>
		<tr>
		<td class='col-lg-6'>
				<div class='well'>		
				
				<form  class="bs-example form-horizontal" action='user_list.php?list=officer' method='post'>
					<legend>Access Officer List</legend>
						<div class='form-group'>
						<label class='col-lg-4 control-label'>Enter Your Password:</label>
						<div class='col-lg-8'>
							<input class='form-control'  type=password name='records_password' /><input type=hidden value='<?php echo $_POST['records_username']; ?>' name='records_username' />
							<input  class="btn btn-primary"  type=submit value='Officer Access' />
						</div>
						</div>
				</form>
				

				</div>
		</td>
		</tr>
		
		
		</table>
<?php		
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
<div class="container">
<div class="col-lg-6" >
<div class="well">
<form class="bs-example form-horizontal" 'receiveDocument.php?ts=3#USERMANAGE' method='post'>
<fieldset>

	<legend>Access User Management</legend>
	<div class="form-group">

	<label class="col-lg-5 control-label">
	Enter Your Username:
	</label>	
	<div class="col-lg-6"><input class='form-control' type=text name='records_username' /></div>
	
	</div>
	<div class="form-group">
	<label class="col-lg-5 control-label">
	Password:
	</label>
	<div class="col-lg-6">	
	<input class='form-control'  type=password name='records_password' />
	</div>
	</div>
	<div class="form-group">

	<input class="btn btn-primary" type=submit value='Access' />
	</div>
</table>
</form>
</fieldset>
</div>
</div>
</div>
<?php
}
?>