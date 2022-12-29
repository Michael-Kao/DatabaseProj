<?php
require 'config.php';
if (isset($_POST['submit'])) {
    $usernameemail = $_POST['usernameemail'];
    $password = $_POST['password'];
    include 'db.php';
    $query = ("select * from user where (Username = ? or UserEmail = ?) or UserPassword = ?");
    $stmt = $db->prepare($query);
    $error = $stmt->execute(array($usernameemail, $usernameemail, $user_password));
    $result = $stmt->fetchAll();
    if (count($result) == 1) {
        $_SESSION['id'] = $result[0]['Uuid'];
        header("Location: index.php");
    } else {
        echo "<script> Incorrect username or password </script>";
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
        <form method="post" action="login.php" autocomplete="off">
            <h1>Login</h1>
            <div>
                <label for="usernameemail">Username or Email:</label>
                <input type="text" name="usernameemail" id="usernameemail">
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password">
            </div>
            <button type="submit" name="submit">Login</button>
        </form>
    </main>
</body>

</html>