<?php

function retrieveRecordsDb(){
//Database for Records
$db=new mysqli('localhost','root','123456','records');
//$db=new mysqli('192.168.1.128','server_user','123456','records');
return $db;
}

function retrieveUsersDb(){
//Database for Users
$db2=new mysqli('localhost','root','123456','user_management');
//$db2=new mysqli('192.168.1.128','server_user','123456','user_management');
return $db2;

}


?>