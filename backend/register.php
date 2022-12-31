<?php
require 'config.php';
class RegisterRequest {
    public $username;
    public $email;
    public $password;
    public $password2;
}

include "function.php";
include "db.php";

$method = $_SERVER['REQUEST_METHOD'];
if($method == 'POST') {
    $request = json_decode(file_get_contents('php://input'));
    if(!validate_data($request, new RegisterRequest())) {
        header("HTTP/1.1 400 bad request");
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array('status' => 'Bad request',
                                'message' => 'Data is worng in the register request body.'));
        exit();
    }

    $username = $request->username;
    $email = $request->email;
    $user_password = $request->password;
    $password2 = $request->password2;

    if ($user_password != $password2) {
        header("HTTP/1.1 400 bad request");
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array('status' => 'Bad request',
                                'message' => 'Password not match.'));
 
    } else {
        $query = ("select * from user where UserName = ? or UserEmail = ? or UserPassword = ?");
        $stmt = $db->prepare($query);
        $error = $stmt->execute(array($username, $email, $user_password));
        $result = $stmt->fetchAll();
        if(count($result) > 0) {
            header("HTTP/1.1 400 bad request");
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(array('status' => 'Bad request',
                                    'message' => 'UserName or email or password already exist'));

        } else {
            $uuid = getuuid();
            $query = ("insert into user (Uuid, UserName, UserEmail, UserPassword) values (?, ?, ?, ?)");
            $stmt = $db->prepare($query);
            $error = $stmt->execute(array($uuid, $username, $email, $user_password));

            header("HTTP/1.1 200 OK");
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(array('id' => $uuid, 'username' => $username));
            exit();
        }
    }
}
?>