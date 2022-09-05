<?php 
    if (!isset($_SESSION)){
        session_start();
    }
    $_SESSION['user']['loggedin'] = False;
    $_SESSION['user']['name'] = "Guest";
    $_SESSION['user']['level'] = "Guest";
    header("Location:login.php?load_main=yes");
?>