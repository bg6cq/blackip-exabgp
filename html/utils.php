<?php

function RedirectTo($path) {
    header("Location: $path");
    die();
}

?>