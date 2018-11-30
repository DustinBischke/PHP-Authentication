<?php
    define('HOST', 'localhost');
    define('USER', 'root');
    define('PASS', '');
    define('DB', 'dustin');
    $conn = mysqli_connect(HOST, USER, PASS);
    define('USERNAME_REGEX', '/^[a-zA-Z0-9]{4,16}$/');
    define('PASSWORD_REGEX', '/^(?:(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\W))[a-zA-Z0-9\S]{8,128}$/');
?>
