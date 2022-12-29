<?php
require 'config.php';
if(!empty($_POST) && $_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = $_POST["username"];
    $email = $_POST["email"];
    $user_password = $_POST["password"];
    $password2 = $_POST["password2"];

    if ($user_password != $password2) {
        echo "<script> alert('password not match'); </script>";
    } else {
        include "db.php";
        $query = ("select * from user where Username = ? or UserEmail = ? or UserPassword = ?");
        $stmt = $db->prepare($query);
        $error = $stmt->execute(array($username, $email, $user_password));
        $result = $stmt->fetchAll();
        if(count($result) > 0) {
            echo "<script> alert('username or email or password already exist'); </script>";
        } else {
            include "uuid.php";
            $uuid = getuuid();
            $query = ("insert into user (Uuid, Username, UserEmail, UserPassword) values (?, ?, ?, ?)");
            $stmt = $db->prepare($query);
            $error = $stmt->execute(array($uuid, $username, $email, $user_password));
            header("Location: login.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.phptutorial.net/app/css/style.css">
    <title>Chatroom</title>
</head>

<body>
    <main>
        <form method="post" action="register.php" autocomplete="off">
            <h1>Register</h1>
            <div>
                <label for="username">Username:</label>
                <input type="text" name="username" id="username">
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" name="email" id="email">
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password">
            </div>
            <div>
                <label for="password2">Password Again:</label>
                <input type="password" name="password2" id="password2">
            </div>
            <button type="submit" name="submit" href="login.php">Register</button>
            <footer>Already a member? <a href="login.php">Login here</a></footer>
        </form>
    </main>
</body>

</html>