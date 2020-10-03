<?php


session_start();

if(isset($_SESSION['isadmin']));
	$_SESSION['isadmin']=0;
	 
echo "you are logged out, please go <a href=index.php> here</a>.";
?>

