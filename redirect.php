<?php
session_start();
?>
<?php
if(isset($_GET['url'])){
	if(isset($_SESSION['username'])){
		header("Location: ".$_GET['url']);
	
	}
	else {
		header("Location: login.php");
	
	}
}

?>


