<?php
function loginUser($db,$username){
	$sql="insert into log_history(username, time, action) values ('".$username."','".date("Y-m-d H:i:s")."','login')";
	$rs=$db->query($sql);

	$sql="insert into log_action(username, login) values ('".$username."','".date("Y-m-d H:i:s")."')";
	$rs=$db->query($sql);
	
	
}

function checkDepartmentAvailability($db,$department,$phrase){
	$sql="select * from division_management where division_id='".$department."'";
	$rs=$db->query($sql);
	$row=$rs->fetch_assoc();
	
	$limit=$row['user_no'];
	$default=$row['security_phrase'];
	$evaluation="";
	if($default==$phrase){
		$user_count=checkDepartmentLimit($db,$department);
		if($user_count==$limit){
			$evaluation="Error: Division may exceed user limit.";			
			
		}
		else {
			$evaluation="Okay to proceed";
		}
		
	
	}
	
	else {
		$evaluation="Error: Invalid Security Phrase.";
	}
	return $evaluation;	
}

function checkDepartmentLimit($db,$department){
	$sql="select * from users where deptCode='".$department."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	return $nm;

}

function addRecordsOfficer($db,$credentials){
	$limit=$checkDepartmentLimit;
	$role="";
	$active="";
	if($limit==0){
		$role="primary";
		$active="true";
	}
	else {
		$role="back-up";
		$active="false";
	}

	addUser($db,$credentials);
		
	$insert="insert into records_officer(username,role,active) values ('".$credentials[1]."','".$role."','".$active."')";	
	$update=$db->query($insert);
}

function addUser($db,$credentials){
	$insert="insert into users(username,password,firstName,lastName,deptCode) values ('".$credentials[1]."','".$credentials[2]."','".$credentials[3]."','".$credentials[4]."','".$credentials[0]."')";	
	$update=$db->query($insert);
}

function retrieveName($db,$username){
	$sql="select * from users where username='".$username."'";
	$rs=$db->query($sql);
	$row=$rs->fetch_assoc();
	$name=$row['firstName']." ".$row['lastName'];
	return $name;

}

function verifyUser($db,$username,$password){
	$sql="select * from users where username='".$username."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	$message="";
	
	if($nm>0){
		$row=$rs->fetch_assoc();
		$pass=$row['password'];
		if($pass==$password){
			$message="Okay for access.";
		}
		else {
			$message="Invalid password. Please verify this to proceed.";
		}
	}
	else {
		$message="Invalid ID. Please verify your username.";
	}
	
	return $message;
}

function recentlyLogged($db){
	$sql="select * from log_history inner join users on log_history.username=users.username order by time desc";
	$rs=$db->query($sql);
	return $rs;
	
}

function listUsers($db){
	$sql="select * from users order by lastName";
	$rs=$db->query($sql);
	return $rs;
}

function listDivSettings($db){
	$sql="select * from division_management order by division_id ";
	$rs=$db->query($sql);
	return $rs;
}

function backupStatus($db){
	$sql="select * from records_officer where role='back-up'";
	$rs=$db->query($sql);
	$row=$rs->fetch_assoc();
	$active=$row['active'];
	return $active;

}
function checkLog($user,$db){
	$sql="select * from log_action where username='".$user."' and logout is null";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	$nm=0;
	return $nm;

}
?>