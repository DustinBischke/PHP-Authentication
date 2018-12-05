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
        $query = 'CREATE TABLE users (username varchar(32) NOT NULL, password varchar(255) NOT NULL, salt varchar(16), attempts tinyint DEFAULT 0, lockout datetime, PRIMARY KEY(username))';

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

        if (preg_match(USERNAME_REGEX, $username))
        {
            if ($password == $password2)
            {
                if (preg_match(PASSWORD_REGEX, $password))
                {
                    $query = "SELECT * FROM users WHERE username = '" . $username . "'";

                    if (mysqli_num_rows(mysqli_query($conn, $query)) == 0)
                    {
                        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        $salt = '';

                        for ($i = 0; $i < 16; $i++)
                        {
                            $salt = $salt . $characters[rand(0, strlen($characters) - 1)];
                        }

                        $hash_password = password_hash($password . $salt, PASSWORD_DEFAULT);
                        $query = "INSERT INTO users (username, password, salt) VALUES ('" . $username . "', '" . $hash_password . "', '" . $salt . "')";

                        mysqli_query($conn, $query);
                        setcookie('auth', $username, time() + 3600);
                        header('location: private.php');
                    }
                    else
                    {
                        echo 'User already exists';
                    }
                }
                else
                {
                    echo 'Password does not meet required password complexity';
                }
            }
            else
            {
                echo 'Passwords do not match';
            }
        }
        else
        {
            echo 'Invalid username';
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
            <h2>Username</h2>
            <ul>
                <li>Between 4 - 16 characters</li>
                <li>Must contain only Letters and Numbers</li>
            </ul>
            <input type="text" name="username" placeholder="Username" />
            <h2>Password</h2>
            <ul>
                <li>Between 8 - 128 characters</li>
                <li>Must contain at least 1 of each:
                    <ul>
                        <li>Lowercase Letter</li>
                        <li>Uppercase Letter</li>
                        <li>Number</li>
                        <li>Special Character (ie: !@#$%^&*)</li>
                    </ul>
                </li>
                <li>Cannot contain whitespace</li>
            </ul>
            <input type="password" name="password" placeholder="Password"/>
            <h3>Repeat Password</h3>
            <input type="password" name="password2" placeholder="Repeat Password"/>
            </br></br><input type="submit" value="Register" />
        </form>
        <a href="login.php">Login</a>
    </body>
</html>
