<?php
    if(!isset($_COOKIE['auth']))
    {
        header('location: login.php');
    }

    $username = $_COOKIE['auth'];
?>

<html>
    <head>
        <title>Private</title>
    </head>
    <body>
        <h1>Welcome <?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8');?></h1>
        <a href="change_password.php">Change Password</a>
        <a href="logout.php">Logout</a>
    </body>
</html>
