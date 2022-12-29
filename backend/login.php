<?php
require 'config.php';
$request = json_decode(file_get_contents('php://input'));
$user_name_or_email = $request->username;
$user_password = $request->password;
include 'db.php';
$query = ("select * from user where (UserName = ? or UserEmail = ?) or UserPassword = ?");
$stmt = $db->prepare($query);
$error = $stmt->execute(array($user_name_or_email, $user_name_or_email, $user_password));
$result = $stmt->fetchAll();
if (count($result) == 1) {
    $_SESSION['id'] = $result[0]['Uuid'];
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array('id' => $result[0]['Uuid'], 'username' => $result[0]['UserName']));
} else {
    header("HTTP/1.1 401 unauthorized");
    echo "Incorrect username or password";
}
?>