<?php
require 'config.php';
$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    // echo $_COOKIE;
    if(isset($_SESSION['id']) && isset($_COOKIE['user']) && $_SESSION['id'] == $_COOKIE['user']) {
        header("HTTP/1.1 200 OK");
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array('status' => "logged in",
                                'message' => "You are logged in",
                                'id' => $_SESSION['id']));
    } else {
        header("HTTP/1.1 200 OK");
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array('status' => "Not logged in",
                                'message' => "You are not logged in"));
    }
}
else {
    header("HTTP/1.1 405 Method Not Allowed");
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array('status' => 'Method Not Allowed',
                            'message' => 'This method not allowed.'));
}
?>