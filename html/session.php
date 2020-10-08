<?php
include "utils.php";

// Session validation
if(session_start())
{
    if(!isset($_SESSION["username"]))
    {
        RedirectTo("login.php");
    }
}
?>