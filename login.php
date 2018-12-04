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
        $query = 'CREATE TABLE users (username varchar(255) NOT NULL, password varchar(255) NOT NULL, attempts tinyint DEFAULT 0, lockout datetime, PRIMARY KEY(username))';

        if (!mysqli_query($conn, $query))
        {
            echo mysqli_error($conn);
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $query = "SELECT * FROM users WHERE username = '" . $username . "'";

        if (mysqli_num_rows(mysqli_query($conn, $query)) == 1)
        {
            $row = mysqli_fetch_assoc(mysqli_query($conn, $query));
            $hash_password = $row['password'];
            $attempts = $row['attempts'];
            $lockout = $row['lockout'];

            $now = new DateTime(null, new DateTimeZone('Europe/Dublin'));
            $now->getTimezone();
            $now = $now->format('Y-m-d H:i:s');

            $minutes = 5;
            $difference = round((strtotime($now) - strtotime($lockout)) / (60 * $minutes), 1);

            # Correct Password
            if (password_verify($password, $hash_password))
            {
                if ($lockout == null)
                {
                    setcookie('auth', $username, time() + 3600);
                    header('location: private.php');
                }
                else
                {
                    if ($difference >= 1)
                    {
                        $query = "UPDATE users SET attempts = 0, lockout = null WHERE username = '" . $username . "'";
                        mysqli_query($conn, $query);

                        setcookie('auth', $username, time() + 3600);
                        header('location: private.php');
                    }
                    else
                    {
                        echo 'Locked out for 5 Minutes';
                    }
                }
            }
            else
            {
                if ($attempts < 3)
                {
                    $attempts = $attempts + 1;
                    $query = "UPDATE users SET attempts = " . $attempts . " WHERE username = '" . $username . "'";
                    mysqli_query($conn, $query);

                    echo 'Invalid Login Credentials';
                }
                else
                {
                    if ($lockout != null)
                    {
                        if ($difference >= 1)
                        {
                            $query = "UPDATE users SET attempts = 0, lockout = null WHERE username = '" . $username . "'";
                            mysqli_query($conn, $query);

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
                            echo 'Locked out for 5 Minutes';
                        }
                    }
                    else
                    {
                        $query = "UPDATE users SET lockout = '" . $now . "' WHERE username = '" . $username . "'";
                        mysqli_query($conn, $query);

                        echo 'Locked out for 5 Minutes';
                    }
                }
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
