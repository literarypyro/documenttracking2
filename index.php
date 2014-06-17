<?php
session_start();
?>
<?php
if(isset($_SESSION['username'])){
	header("Location: main_page.php");

}
else {
	header("Location: login.php");


}