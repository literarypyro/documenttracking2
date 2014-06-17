<?php
function connectUserDb(){
	$db=new mysqli("localhost","root","","user_management");

	return $db;
}

function connectDb(){
	$db=new mysqli("localhost","root","","records");

	return $db;
}



?>