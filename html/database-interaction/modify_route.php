<?php

include "../session.php";
include "../../config.php";

if(isset($_REQUEST["modi_do"])) 
{ 
    $id= $_REQUEST["id"];
    $end = $_REQUEST["end"];
    $msg = $_REQUEST["msg"];
    $q="update blackip set end=?, msg = ? where id =?";
    $stmt=$mysqli->prepare($q);
    $stmt->bind_param("sss", $end, $msg, $id);
    $stmt->execute();
    $stmt->close();
}

RedirectTo("../index.php"); // Comes included from session.php

?>