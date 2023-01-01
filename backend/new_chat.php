<?php
require 'config.php';

class CreateChatRequest {
    public $room_name;
    public $members;
}

include 'db.php';
include 'function.php';

$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'POST') {
    // echo 'create chatroom';
    $request = json_decode(file_get_contents('php://input'));
    // var_dump($request);

    if (!validate_data($request, new CreateChatRequest())) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array('status' => 'Bad request',
                                'message' => 'Data is worng in the new chat request body.'));
        exit();
    }

    $room_name = $request->room_name;
    $members = $request->members;
    $create_on = date('Y-m-d H:i:s');
    array_unique($members);

    //get user id
    $members_id = array($_SESSION['id']);
    foreach ($members as $member_email) {
        $query = ("select Uuid from user where UserEmail= ?");
        $stmt = $db->prepare($query);
        $error = $stmt->execute(array($member_email));
        $result = $stmt->fetchAll();
        if (!$error) {
            header('HTTP/1.1 500 Internal Server Error');
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(array('status' => 'Internal Server Error',
                                    'message' => 'Something worng when searching user.'));
            echo $error;
            exit();
        }
        array_push($members_id, $result[0]['Uuid']);
    }
    //create chatroom
    $room_id = getuuid();
    $query = ("insert into chatroom (id, Name, Status, CreateOn) values (?, ?, ?, ?)");
    $stmt = $db->prepare($query);
    $error = $stmt->execute(array($room_id, $room_name, true, $create_on));

    if (!$error) {
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array('status' => 'Internal Server Error',
                                'message' => 'Something worng when inserting chatroom.'));
        echo $error;
        exit();
    }

    //create chatroom member
    foreach ($members_id as $member_id) {
        $query = ("insert into participants (UserID, RoomID) values (?, ?)");
        $stmt = $db->prepare($query);
        $error = $stmt->execute(array($member_id, $room_id));
        if(!$error) {
            header('HTTP/1.1 500 Internal Server Error');
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(array('status' => 'Internal Server Error',
                                    'message' => 'Something worng when adding memeber of chatroom.'));
            echo $error;
            exit();
        }
    }

    header('HTTP/1.1 201 created');
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array('id' => $room_id, 'name' => $room_name));
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array('status' => 'Method Not Allowed',
                            'message' => 'This method not allowed.'));
}

?>