<?php
    if(!isset($_COOKIE['auth']))
    {
        header('location: login.php');
    }

    setcookie('auth', '', time() - 3600);
    header('location: index.html');
?>
