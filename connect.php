<?php
    defined('access') || die('Direct access not permitted.');
    $link = mysqli_connect('localhost', 'root', '', 'pizzeria');
    if($link === false){
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
?>