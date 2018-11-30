<?php
    require('config.php');

    if (mysqli_connect_errno())
    {
        echo mysqli_connect_error();
    }

    $query = 'CREATE DATABASE IF NOT EXISTS ' . DB;

    if (mysqli_query($conn, $query))
    {
        $query = 'USE ' . DB;
        mysqli_query($conn, $query);
    }
    else
    {
        echo mysqli_error($conn);
    }

    $query = 'SELECT * FROM users';

    if (empty(mysqli_query($conn, $query)))
    {
        $query = 'CREATE TABLE users (username varchar(255) NOT NULL, password varchar(255) NOT NULL, PRIMARY KEY(username))';

        if (!mysqli_query($conn, $query))
        {
            echo mysqli_error($conn);
        }
    }

    mysqli_close($conn);
?>
