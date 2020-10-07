<?php

// Authentication methods 'enum'
abstract class AuthenticationMethod
{
    const USTC_CAS = 0;
    const RADIUS = 1;
}

// *** CONFIGURATON SECTION ****

$authentication_method = AuthenticationMethod::RADIUS; // Values are USTC_CAS, RADIUS

// RADIUS parameters - leave it blank if you're not using RADIUS authentication
$radius_server_ip = "10.3.4.34";
$radius_shared_secret = "!@#k2!@#Telecom2018";

$routerip="210.45.230.89"; // Put your exabgp router IP address here
$db_host = "localhost"; // Replace to your server host if needed
$db_user = "blackip-exabgp"; // Replace to your desired database user
$db_passwd = "blackip-exabgp@k2telecom"; // Put your database user password here
$db_dbname = "blackip"; // You shudn't change this name

// *** CONFIGURATON SECTION ****

// Check if database is ok
$mysqli = new mysqli($db_host, $db_user, $db_passwd, $db_dbname);
if(mysqli_connect_error()){
	echo mysqli_connect_error();
}

?>