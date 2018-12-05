<?php
    if(!isset($_COOKIE['auth']))
    {
        header('location: login.php');
    }

    $username = $_COOKIE['auth'];

    function preventxss($string)
    {
        $string = str_replace(array('<', '>', '&', '"', "'"), array('&lt;', '&gt;', '&amp;', '&quot;', '&apos;'), $string);

        return $string;
    }
?>

<html>
    <head>
        <title>Private</title>
    </head>
    <body>
        <h1>Welcome <?php echo preventxss($username);?></h1>
        <a href="change_password.php">Change Password</a>
        <a href="logout.php">Logout</a>
    </body>
</html>
