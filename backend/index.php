<?php
require 'config.php';

include 'db.php';
include 'function.php';

$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'GET') {
    // echo 'create chatroom';
    if (isset($_SESSION['id']) && isset($_COOKIE['user']) && $_SESSION['id'] == $_COOKIE['user']) {

        $request = json_decode(file_get_contents('php://input'));
        // var_dump($request);
        
        $user_id = $_COOKIE['user'];
        $query = ("select * from chatroom join participants on chatroom.ID = participants.RoomID where UserID=?");
        $stmt = $db->prepare($query);
        $error = $stmt->execute(array($user_id));
        $result = $stmt->fetchAll();

        if (!$error) {
            header('HTTP/1.1 500 Internal Server Error');
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(
                array(
                    'status' => 'Internal Server Error',
                    'message' => 'Something worng when searching chatroom.'
                )
            );
            exit();
        }
        header('HTTP/1.1 200 OK');
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(
            array(
                'status' => 'OK',
                'message' => 'Get chatroom successfully.',
                'room_list' => $result
            )
        );
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(
        array(
            'status' => 'Method Not Allowed',
            'message' => 'Method Not Allowed'
        )
    );
    exit();
}
        

?>