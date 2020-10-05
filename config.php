<?php

$routerip="210.45.230.89"; // Put your exabgp router IP address here
$db_host = "localhost"; // Replace to your server host if needed
$db_user = "blackip-exabgp"; // Replace to your desired database user
$db_passwd = ""; // Put your database user password here
$db_dbname = "blackip"; // You shudn't change this name

$mysqli = new mysqli($db_host, $db_user, $db_passwd, $db_dbname);
if(mysqli_connect_error()){
	echo mysqli_connect_error();
}

?>