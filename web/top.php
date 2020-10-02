<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"" />
<title>ExaBGP WEB</title>
</head>

<body bgcolor=#dddddd>
<a href=/ target=_blank>flow</a> <!-- Translated to english -->
<a href=index.php>Effective route</a> <!-- Translated to english -->
<a href=exp.php>Withdrawn route</a> <!-- Translated to english -->
<a href=intro.php>Introduction</a> <!-- Translated to english -->

<?php

$db_host = "localhost"; // Replace to your server host if needed
$db_user = "root"; // Replace to your desired database user
$db_passwd = ""; // Put your database user password here
$db_dbname = "blackip"; // You shudn't change this name

$mysqli = new mysqli($db_host, $db_user, $db_passwd, $db_dbname);
if(mysqli_connect_error()){
	echo mysqli_connect_error();
}
session_start();

function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

if ( isset($_SESSION["isadmin"]) && $_SESSION["isadmin"]) {
	echo "<a href=logout.php>logout</a> ";
}  
?>

Technical Support: james@ustc.edu.cn  

<?php 

echo "Your IP address:";

if (!empty($_SERVER['HTTP_X_REAL_IP'])) echo $_SERVER['HTTP_X_REAL_IP'];
else 
echo  $_SERVER["REMOTE_ADDR"];

echo " ";
$q="select TIMESTAMPDIFF(second, now(), tm) from lastrun";
$result = $mysqli->query($q);
$r=$result->fetch_array();
if($r[0]<=2)
{
    echo " <font color=green>ExaBGP is operating normally</font>";
}
else
{
    echo " <font color=red>ExaBGP is not running</font>";
}

?>
<hr>

