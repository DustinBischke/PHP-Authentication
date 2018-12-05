<?php
    require('config.php');

    if(!isset($_COOKIE['auth']))
    {
        header('location: login.php');
    }

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

    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $username = $_COOKIE['auth'];
        $password = $_POST['password'];
        $new_password = $_POST['new_password'];
        $new_password2 = $_POST['new_password2'];

        $query = "SELECT * FROM users WHERE username = '" . $username . "'";

        if (mysqli_num_rows(mysqli_query($conn, $query)) == 1)
        {
            $row = mysqli_fetch_assoc(mysqli_query($conn, $query));
            $hash_password = $row['password'];
            $salt = $row['salt'];

            $password = $password . $salt;

            if (password_verify($password, $hash_password))
            {
                if ($new_password == $new_password2)
                {
                    if (preg_match(PASSWORD_REGEX, $new_password))
                    {
                        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        $salt = '';

                        for ($i = 0; $i < 16; $i++)
                        {
                            $salt = $salt . $characters[rand(0, strlen($characters) - 1)];
                        }

                        $hash_password = password_hash($new_password . $salt, PASSWORD_DEFAULT);

                        $query = "UPDATE users SET password = '" . $hash_password . "', salt = '" . $salt . "' WHERE username = '" . $username . "'";

                        mysqli_query($conn, $query);
                        setcookie('auth', '', time() - 3600);
                        header('location: index.html');
                    }
                    else
                    {
                        echo 'New Password does not meet required password complexity';
                    }
                }
                else
                {
                    echo 'New Passwords do not match';
                }
            }
            else
            {
                echo 'Invalid Password';
            }
        }
    }
?>

<html>
    <head>
        <title>Change Password</title>
    </head>
    <body>
        <h1>Change Password</h1>
        <form method="post">
            <h2>Password</h2>
            <input type="password" name="password" placeholder="Password"/>
            <h2>New Password</h2>
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
            <input type="password" name="new_password" placeholder="New Password"/>
            <h3>Repeat New Password</h3>
            <input type="password" name="new_password2" placeholder="Repeat New Password"/>
        </br></br><input type="submit" value="Change Password" />
        </form>
    </body>
</html>
