<?php
    header('Cache-Control: no-cache, must-revalidate');

    if(!isset($_COOKIE['auth']))
    {
        header('location: login.php');
    }
?>

<html>
    <head>
        <title>Private</title>
    </head>
    <body>
        <h1>Welcome</h1>
        <a href="logout.php">Logout</a>
    </body>
</html>
