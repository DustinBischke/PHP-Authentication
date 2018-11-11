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

    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $password2 = $_POST['password2'];

        if ($password == $password2)
        {
            $query = "SELECT * FROM users WHERE username = '" . $username . "'";

            if (mysqli_num_rows(mysqli_query($conn, $query)) == 0)
            {
                $hash_password = password_hash($password, PASSWORD_DEFAULT);
                //$hash_password = hash('sha256', $password);
                $query = "INSERT INTO users (username, password) VALUES ('" . $username . "', '" . $hash_password . "')";

                mysqli_query($conn, $query);
                header("location: welcome.html");
            }
            else
            {
                echo 'User already exists';
            }
        }
        else
        {
            echo 'Passwords do not match';
        }
    }
?>

<html>
    <head>
        <title>Register</title>
    </head>
    <body>
        <h1>Register</h1>
        <form method="post">
            <label>Username</label>
            <input type="text" name="username" />
            <label>Password</label>
            <input type="password" name="password" />
            <input type="password" name="password2" />
            <input type="submit" value="Register" />
        </form>
        <a href="login.php">Login</a>
    </body>
</html>
