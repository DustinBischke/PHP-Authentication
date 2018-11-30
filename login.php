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

        $query = "SELECT password FROM users WHERE username = '" . $username . "'";

        if (mysqli_num_rows(mysqli_query($conn, $query)) == 1)
        {
            $row = mysqli_fetch_assoc(mysqli_query($conn, $query));
            $hash_password = $row['password'];

            if (password_verify($password, $hash_password))
            {
                setcookie('auth', $username, time() + 3600);
                header('location: private.php');
            }
            else
            {
                echo 'Invalid Login Credentials';
            }
        }
        else
        {
            echo 'Invalid Login Credentials';
        }
    }
?>

<html>
    <head>
        <title>Login</title>
    </head>
    <body>
        <h1>Login</h1>
        <form method="post">
            <h2>Username</h2>
            <input type="text" name="username" placeholder="Username"/>
            <h2>Password</h2>
            <input type="password" name="password" placeholder="Password"/>
            </br></br><input type="submit" value="Login" />
        </form>
        <a href="register.php">Register</a>
    </body>
</html>
