<?php
if($_GET['list']=="user"){
?>
	<table class='table table-striped table-hover' width=100%>
		<thead>
		<tr>
			<th colspan=7><h2>User Masterlist</h2></th>
		<tr>
			<th>Name</th>
			<th>Username</th>
			<th>Password</th>
			<th>Department</th>
			
		</tr>
		</thead>
		<tbody>
		<?php
		$db=retrieveUsersDb();
		$db2=retrieveRecordsDb();
		
		
		$rs=listUsers($db);
		$nm=$rs->num_rows;
		for($i=0;$i<$nm;$i++){
			$row=$rs->fetch_assoc();
		?>
		<tr>
			<td><?php echo $row['lastName'].", ".$row["firstName"]; ?></td>			
			<td><?php echo $row['username']; ?></td>			
			<td><?php echo $row['password']; ?></td>			
			<td><?php echo getDepartment($db2,$row['deptCode']); ?></td>			
		</tr>
		<?php
		}
		?>		
		</tbody>
	</table>
	
	<div class='col-lg-4'>
	<div class='well'>
	<form class="bs-example form-horizontal"  action='user_list.php?list=user' method=post>
	<table id='exception'>
	<legend>Manage Account</legend>
	
	<tr>
		<td><label class='control-label'>Name of User</label></td>
		<td>
			<select  class='form-control'  name='updateUser'>
	<?php
//		$db=retrieveUsersDb();
		$rs=listUsers($db);
		$nm=$rs->num_rows;

		for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
	?>
			<option value="<?php echo $row['username']; ?>"><?php echo strtoupper($row['lastName']).", ".$row['firstName']; ?></option>
	<?php
		}
	?>
			</select>
		</td>
	</tr>
	<tr>
		<td><label class='control-label'>Action to Take:</label></td>
		<td>
			<select class='form-control' name='userAction'>
			<option value='password'>Change Password</option>
			<option value='username'>Change Username</option>
			<option value='delete'>Delete Account</option>
			</select>
			<input class='form-control' type='text' name='updateText' />
		</td>
	</tr>
	<tr>
		<td><label class='control-label'>Confirm (Enter your Password):</label></td>
		<td><input  class='form-control'  type=password name='records_password' /><input type=hidden value='<?php echo $_POST['records_username']; ?>' name='records_username' /></td>
	</tr>	
<!--

<tr><td>Enter your password to confirm:</td><td><input type='password' name='passwordConfirm' /></td></tr>
-->
	<tr><td align=center colspan=3><input class='btn btn-primary' type='submit' value='Modify' /></td></tr>
</table>
</form>	
	</div>
	</div>
	
<?php
}

else if($_GET['list']=="div"){
?>

	<table class='table table-striped table-hover' width=100%>
		<thead>
		<tr>
			<th colspan=7><h2>Division Masterlist</h2></th>
		</tr>
		<tr>
			<th>Division Name</th>
			<th>Office Code</th>
			<th>Security Phrase</th>
			<th>No. of Users allowed</th>
		</tr>
		</thead>
		<tbody>
<?php

	$db=retrieveUsersDb();
	$db2=retrieveRecordsDb();
				
	$rs=listDivSettings($db);
	$nm=$rs->num_rows;
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		
?>
		<tr>
			<td><?php echo getDepartment($db2,$row['division_id']); ?></td>
			<td><?php echo $row['division_id']; ?></td>
			<td><?php echo $row['security_phrase']; ?></td>
			<td><?php echo $row['user_no']; ?></td>
		</tr>

<?php
	}
?>
	</tbody>
	</table>

<?php 
	echo "<div class='well col-lg-3'>";
	echo "<form action='user_list.php?list=div' method=post>";
	
	echo "<table>";
	echo "<tr>";
	echo "<th colspan=2>Manage Division</th>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>Update Division</td>";
	
	echo "<td>";

	$db=retrieveRecordsDb();
	$division="select * from department order by department_code";
	$rs=$db->query($division);
	$nm=$rs->num_rows;
	
	echo "<select class='form-control' name='department'>";
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		echo "<option value='".$row['department_code']."'>";
		echo $row['department_name'];	
		echo "</option>";
	
	}	
	echo "</select>";
	echo "<select class='form-control' name='modifyField'>";
	echo "<option value='security_phrase'>Change Security Phrase</option>";
	echo "<option value='user_no'>Change User Limit</option>";
	echo "</select>";
	echo "</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>Update To:</td>";
	echo "<td><input class='form-control' type=text name='updateField' /></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>Confirm (Enter your Password):</td>";
	echo "<td><input class='form-control' type=password name='records_password' /><input type=hidden value='".$_POST['records_username']."' name='records_username' /></td>";
	echo "</tr>";	
	echo "<tr><td colspan=2 align=center><input class='btn btn-primary' type=submit value='Update' /></td></tr>";
	echo "</table>";
	echo "<br>";
	echo "</form>";
	echo "</div>";
		echo "<form action='user_list.php?list=div' method=post>";
	
	echo "<div class='well col-lg-3'>";

	echo "<table>";
	echo "<tr>";
	echo "<th colspan=9><b>Add Division Settings</b></th>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>Division:</td>";
	echo "<td><select class='form-control' name='divisionAdd'>";
	$db=retrieveRecordsDb();
	$division="select * from department order by department_code";
	$rs=$db->query($division);
	$nm=$rs->num_rows;

	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		echo "<option value='".$row['department_code']."'>";
		echo $row['department_name'];	
		echo "</option>";
	
	}	
	echo "</select>";
	echo "</td>";
	
	
	echo "</tr>";
	echo "<tr>";
	echo "<td>Security Phrase</td>";
	echo "<td><input  class='form-control' type=text name='phraseAdd' /></td>";
	echo "</tr>";
		
	echo "<tr>";
	echo "<td>No. of Users in Division</td>";
	echo "<td><input  class='form-control' type=text name='limitAdd' /></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>Confirm (Enter your Password):</td>";
	echo "<td><input  class='form-control' type=password name='records_password' /><input type=hidden value='".$_POST['records_username']."' name='records_username' /></td>";
	echo "</tr>";	
	echo "<tr><td colspan=2 align=center><input class='btn btn-primary' type=submit value='Add' /></td></tr>";
	echo "</table>";
	echo "</form>";

	echo "</div>";	
	
	
}

else if($_GET['list']=="officer"){
?>

	<table class='table table-striped table-hover' width=100%>
		<thead>
		<tr>
			<th colspan=2><h2>Officer Masterlist</h2></th>
		</tr>
		<tr>
			<th>Officer Name</th>
			<th>Position</th>
			<th>Unique Signature</th>
		</tr>
		</thead>
		<tbody>
<?php
	$officerList="<select class='form-control' name='officerList'>";
	
	

	$db=retrieveRecordsDb();
	$sql="select * from originating_officer";			
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		$officerList.="<option value='".$row['id']."'>".$row['name']."</option>";	
?>
		<tr>
			<td><?php echo $row['name']; ?></td>
			<td><?php echo $row['position']; ?></td>
			<?php QRcode::png($row['signature'],"signature/".$row['signature'].".png", QR_ECLEVEL_L, 3); ?>
			<td><img src='signature/<?php echo$row['signature']; ?>.png' /></td>
		</tr>

<?php
	}
	$officerList.="</select>";

	?>
	</tbody>
	</table>
	<div class='well col-lg-4'>
	<form class="bs-example form-horizontal" action='user_list.php?list=officer' method=post>

	
	<legend>Manage Officer</legend>
	<table id='exception'>
	<tr>
		<td>Officer</td>
		<td>
		<?php
		echo $officerList;
		?>
		</td>
	</tr>
	<tr>
		<td>Action to Take:</td>
		<td>
			<select  class='form-control' name='officerAction'>
			<option value='name'>Change Officer</option>
			<option value='position'>Change Position</option>
			<option value='signature'>Customize Signature</option>
			<option value='delete'>Delete Account</option>
			</select>
			<input  class='form-control'  type='text' name='updateText' />
		</td>
	</tr>
	<tr>
		<td>Confirm (Enter your Password):</td>
		<td><input  class='form-control' type=password name='records_password' /><input type=hidden value='<?php echo $_POST['records_username']; ?>' name='records_username' /></td>
	</tr>	
	
	
<!--

<tr><td>Enter your password to confirm:</td><td><input type='password' name='passwordConfirm' /></td></tr>
-->
	<tr><td align=center colspan=3><input class='btn btn-primary' type='submit' value='Modify' /></td></tr>
</table>
</form>
</div>

<div class='well col-lg-3'>
<form class="bs-example form-horizontal"  action='user_list.php?list=officer' method=post>
<legend>Add Officer</legend>

<table>
<tr>
<td>Officer Name</td>
<td><input class='form-control'  type=text name='addOfficerName' /></td>
</tr>
<tr>
<td>Officer Position</td>
<td><input class='form-control'  type=text name='addOfficerPosition' /></td>
</tr>
<tr>
	<td>Confirm (Enter your Password):</td>
	<td><input class='form-control' type=password name='records_password' /><input type=hidden value='<?php echo $_POST['records_username']; ?>' name='records_username' /></td>
</tr>	
<tr><td align=center colspan=2><input class='btn btn-primary' type='submit' value='Add' /></td></tr>

</table>
</form>
</div>
<?php
}	
?>