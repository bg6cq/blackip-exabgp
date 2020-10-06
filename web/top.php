<?php

// All pages should include top.php in order to have session validation

include "../config.php";

if(session_start())
{
    if(!isset($_SESSION["username"]))
    {
        header("Location: login.php");
        die();
    }
}

function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"" />
<title>ExaBGP WEB</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="/wwwroot/css/site.css">

</head>

<body>

<!-- The sidebar -->
<div class="sidebar">
    <a href=intro.php><i class="fa fa-fw fa-info"></i> Introduction</a>
    <a href=index.php><i class="fa fa-fw fa-check"></i> Effective Route</a>
    <a href=exp.php><i class="fa fa-fw fa-times"></i> Withdrawn Route</a>
    <a href=help.php><i class="fa fa-fw fa-question"></i> Help</a>
    <a href=logout.php><i class="fa fa-fw fa-power-off"></i> Logoff</a>
</div>

<div class="main">

<?php 

echo " ";
$q="select TIMESTAMPDIFF(second, now(), tm) from lastrun";
$result = $mysqli->query($q);

if($result)
{
    $r=$result->fetch_array();
    if($r[0]<=2)
    {
        echo "<br>/<span class='exabgp-running'>[ExaBGP is operating normally]</span>";
    }
}
else
{
    echo "<br/><span class='exabgp-not-running'>[ExaBGP is not running]</span>";
}

echo " Your IP address: ";

if (!empty($_SERVER['HTTP_X_REAL_IP'])) echo $_SERVER['HTTP_X_REAL_IP'];
else 
echo  $_SERVER["REMOTE_ADDR"];

?>
<hr>

