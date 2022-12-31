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

    if(!validate_data($request, new LoginRequest())) {
        header("HTTP/1.1 400 bad request");
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array('status' => 'Bad request',
                                'message' => 'Data is worng in the login request body.'));
        exit();
    }

    $user_name_or_email = $request->username;
    $user_password = $request->password;
    $query = ("select * from user where (UserName = ? or UserEmail = ?) and UserPassword = ?");
    $stmt = $db->prepare($query);
    $error = $stmt->execute(array($user_name_or_email, $user_name_or_email, $user_password));
    $result = $stmt->fetchAll();
    if (count($result) == 1) {
        $_SESSION['id'] = $result[0]['Uuid'];
        header("HTTP/1.1 200 OK");
        header('Content-Type: application/json; charset=utf-8');
        setcookie("user", $_SESSION['id'], time() + (86400 * 30), "/");
        echo json_encode(array('id' => $result[0]['Uuid'], 'username' => $result[0]['UserName']));
    } else {
        header("HTTP/1.1 401 unauthorized");
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array('status' => "Unauthorized",
                                'message' => "Incorrect username or password"));
    }
    exit();
} else {
    header("HTTP/1.1 405 Method Not Allowed");
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array('status' => 'Method Not Allowed',
                            'message' => 'This method not allowed.'));
}
?>