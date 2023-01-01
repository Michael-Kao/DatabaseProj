<?php
require 'config.php';

include 'db.php';
include 'function.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    // echo 'create chatroom';
    if (isset($_SESSION['id']) && isset($_COOKIE['user']) && $_SESSION['id'] == $_COOKIE['user']) {

        $request = json_decode(file_get_contents('php://input'));
        $params = array();
        parse_str($_SERVER['QUERY_STRING'], $params);
        // var_dump($request);
        
        if(!isset($params['room_id'])){
            header('HTTP/1.1 400 Bad Request');
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(
                array(
                    'status' => 'Bad Request',
                    'message' => 'Bad Request'
                )
            );
            exit();
        }

        //check if the user is in the chatroom and get chatroom info
        $room_id = $params['room_id'];
        $user_id = $_COOKIE['user'];
        $query = ("select * from participants join chatroom on chatroom.Id = participants.RoomID where UserID=? and RoomID=?");
        $stmt = $db->prepare($query);
        $error = $stmt->execute(array($user_id, $room_id));
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

        if(count($result) != 1){
            header('HTTP/1.1 403 Forbidden');
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(
                array(
                    'status' => 'Forbidden',
                    'message' => 'Forbidden'
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
                'room' => $result[0]
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