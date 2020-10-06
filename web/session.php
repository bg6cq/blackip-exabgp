<?php

// Session validation
if(session_start())
{
    if(!isset($_SESSION["username"]))
    {
        header("Location: login.php");
        die();
    }
}

?>