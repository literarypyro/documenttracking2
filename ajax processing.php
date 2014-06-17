<?php
require("db.php");
<?php
$db=connectDb();
$db2=connectUserDb();
if(isset($_GET['originating_officer'])){
	
	$division=$_GET['originating_officer'];	
	
	$n=0;
	
	
	$sql="select * from originating_officer where division='".$division."' limit 1";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;

	for($i=0;$i<$nm;$i++){
		$data["officer"][$n]["id"]=$row['id'];
		$data["officer"][$n]["name"]=$row['name'];
		$data["officer"][$n]["division"]=$row['division'];
		
		$n++;
	}

	$sql2="select * from users where deptCode='".$division."' order by lastName";
	$rs2=$db2->query($sql2);
	
	$nm2=$rs2->num_rows;
	for($i=0;$i<$nm2;$i++){
		$row2=$rs2->fetch_assoc();
		$data["officer"][$n]["id"]="OTHER";
		$data["officer"][$n]["name"]=$row2['firstName']." ".$row2['lastName'];
		$data["officer"][$n]["division"]=$division;
		
		$n++;
	}
	
	$data["record_count"]=$n;
	
	
	echo json_encode($officer);
	
}
?>