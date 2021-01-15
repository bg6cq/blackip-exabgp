<?php

include "../session.php";
include "../../config.php";

if(isset($_REQUEST["del"]))  
{   
    //del ip
    $id= intval($_REQUEST["id"]);
    $q="update blackip set end=now(),status='deleting' where id=?";
    $stmt=$mysqli->prepare($q);
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $stmt->close();
}

RedirectTo("../index.php"); // Comes included from session.php

?>