<?php
require 'config.php';
$flag = "";
if (empty($_SESSION)) {
    $flag = '<form method="get">
                <input type="submit" name="login" value="login"/>
            </form>';
}
else{
    $flag = '<form method="post">
                <input type="submit" name="logout" value="logout"/>
            </form>';
}
if(isset($_GET['login'])){
    header("Location: login.php");
    exit();
}
else if(isset($_POST['logout'])){
    header('Location: logout.php');
    exit();
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
        <h1> Welcome </h1>
        <?php echo $flag; ?>
    </main>
</body>

</html>