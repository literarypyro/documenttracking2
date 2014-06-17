<?php
function retrieveRecordsDb(){
	//Database for Records
	//$db=new mysqli('nea','records','123456','records');

	//$db=new mysqli('records','records','123456','records');
	//$db=new mysqli('sup-psilva','records','123456','records');
//	$db=new mysqli('sup-psilva','records_officer','123456','records');
	$db=new mysqli ( 'sduserver','records','123456','records');

	//$db=new mysqli('192.168.1.128','server_user','123456','records');
	return $db;
}

function retrieveUsersDb(){
	//Database for Users
	//$db2=new mysqli('nea','records','123456','user_management');

	//$db2=new mysqli('records','records','123456','user_management');
//	$db2=new mysqli('sup-psilva','records','123456','user_management');
//	$db2=new mysqli('sup-psilva','records_officer','123456','user_management');

	$db2=new mysqli('sduserver','records','123456','user_management');

	//$db2=new mysqli('192.168.1.128','server_user','123456','user_management');
	return $db2;
}


?>