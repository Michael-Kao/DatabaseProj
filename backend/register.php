<?php
require 'config.php';
class RegisterRequest
{
    public $username;
    public $email;
    public $password;
    public $password2;
}

include "function.php";
include "db.php";

$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'POST') {
    $request = json_decode(file_get_contents('php://input'));

    handle_error(!validate_data($request, new RegisterRequest()), 400, 'Data is worng in the register request body.');

    $username = $request->username;
    $email = $request->email;
    $user_password = $request->password;
    $password2 = $request->password2;

    handle_error($user_password != $password2, 400, 'Password is not match.');

    $query = ("select * from user where UserName = ? or UserEmail = ? or UserPassword = ?");
    $stmt = $db->prepare($query);
    $error = $stmt->execute(array($username, $email, $user_password));
    $result = $stmt->fetchAll();

    handle_error(!$error, 500, 'Something worng when searching user.');
    handle_error(count($result) > 0, 400, 'Username, email or is already exist.');

    $uuid = getuuid();
    $query = ("insert into user (Uuid, UserName, UserEmail, UserPassword) values (?, ?, ?, ?)");
    $stmt = $db->prepare($query);
    $error = $stmt->execute(array($uuid, $username, $email, $user_password));

    handle_error(!$error, 500, 'Something worng when inserting user.');

    success_res(200, 'Register successfully.', array('id' => $uuid, 'username' => $username));
}
?>