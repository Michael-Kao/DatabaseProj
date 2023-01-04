<?php
require 'config.php';

class MessageRequest {
    public $message;
}

include 'db.php';
include 'function.php';

$method = $_SERVER['REQUEST_METHOD'];

authorize();

if ($method == 'GET') {

    $request = json_decode(file_get_contents('php://input'));
    $params = array();
    parse_str($_SERVER['QUERY_STRING'], $params);

    handle_error(!isset($params['room_id']), 400, 'Missing room_id.');

    //check if the user is in the chatroom and get chatroom info
    $room_id = $params['room_id'];
    $user_id = $_COOKIE['user'];
    $query = ("select * from participants join room on room.Id = participants.RoomID where UserID=? and RoomID=?");
    $stmt = $db->prepare($query);
    $error = $stmt->execute(array($user_id, $room_id));
    $result = $stmt->fetchAll();

    handle_error(!$error, 500, 'Something worng when searching chatroom.');
    handle_error(count($result) != 1, 403, 'Forbidden.');
    $room = $result[0];

    $query = ("select count(*) as count from participants where RoomID=?");
    $stmt = $db->prepare($query);
    $error = $stmt->execute(array($room_id));
    $result = $stmt->fetchAll();

    $member_count = $result[0]['count'];

    $query = ("select * from Message, user where user.Uuid = Message.UserID and Message.RoomID=?");
    $stmt = $db->prepare($query);
    $error = $stmt->execute(array($room_id));
    $result = $stmt->fetchAll();

    handle_error(!$error, 500, 'Something worng when searching chatroom.');

    success_res(200, 'Get chatroom successfully.', array('room' => $room, 'count' => $member_count, 'messages' => $result));
} else if ($method == 'POST') {

    $request = json_decode(file_get_contents('php://input'));
    $params = array();
    parse_str($_SERVER['QUERY_STRING'], $params);

    handle_error(!isset($params['room_id']), 400, 'Missing room_id.');
    handle_error(!validate_data($request, new MessageRequest()), 400, 'Data is worng in the new chat request body.');

    //send message to chatroom
    $msg_id = getuuid();
    $room_id = $params['room_id'];
    $user_id = $_COOKIE['user'];
    $message = $request->message;
    $ipv4 = $_SERVER['REMOTE_ADDR'];
    $date = date('Y-m-d H:i:s');

    $query = ("insert into message (Id, RoomID, UserID, Message, Ipv4, Date) values (?, ?, ?, ?, ?, ?)");
    $stmt = $db->prepare($query);
    $error = $stmt->execute(array($msg_id, $room_id, $user_id, $message, $ipv4, $date));

    handle_error(!$error, 500, 'Something worng when sending message.');

    success_res(200, 'Send message successfully.', []);
} else {
    handle_error(true, 405, 'Method not allowed.');
}
?>