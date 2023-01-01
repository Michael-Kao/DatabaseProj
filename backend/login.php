<?php
require 'config.php';
include 'db.php';
include 'function.php';
class LoginRequest {
    public $username;
    public $password;
}

$method = $_SERVER['REQUEST_METHOD'];
if($method == 'POST') {
    $request = json_decode(file_get_contents('php://input'));

    handle_error(!validate_data($request, new LoginRequest()), 400, 'Data is worng in the login request body.');

    $user_name_or_email = $request->username;
    $user_password = $request->password;
    $query = ("select * from user where (UserName = ? or UserEmail = ?) and UserPassword = ?");
    $stmt = $db->prepare($query);
    $error = $stmt->execute(array($user_name_or_email, $user_name_or_email, $user_password));
    $result = $stmt->fetchAll();

    if (count($result) == 1) {
        $_SESSION['id'] = $result[0]['Uuid'];

        setcookie("user", $_SESSION['id'], time() + (86400 * 30), "/");
        success_res(200, 'Login successfully.', array('id' => $result[0]['Uuid'], 'name' => $result[0]['UserName']));
    } else {
        handle_error(true, 401, 'Username or password is wrong.');
    }
} else {
    handle_error(true, 405, 'Method not allowed.');
}
?>