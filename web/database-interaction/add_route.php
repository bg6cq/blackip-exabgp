<?php

include "../session.php";
include "../../config.php";

if(isset($_REQUEST["add_do"])) 
{  
    //add new
    $prefix = $_REQUEST["prefix"];
    $len = $_REQUEST["len"];
    $next_hop = $_REQUEST["next_hop"];
    $other= $_REQUEST["other"];
    $day = intval($_REQUEST["day"]);
    $msg = $_REQUEST["msg"];

    $q = "insert into blackip (status,prefix,len,next_hop,other,start,end,msg) values ('adding',?,?,?,?,now(),date_add(now(),interval ? day),?)";
    $stmt=$mysqli->prepare($q);
    $stmt->bind_param("sissis",$prefix,$len,$next_hop,$other,$day,$msg);
    $stmt->execute();
    sleep(2);
}

// Redirects to index
header("Location: ../index.php");
die();

?>