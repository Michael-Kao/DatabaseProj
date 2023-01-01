<?php
require 'config.php';

class CreateChatRequest
{
    public $room_name;
    public $members;
}

include 'db.php';
include 'function.php';

$method = $_SERVER['REQUEST_METHOD'];

authorize();

if ($method == 'POST') {
    $request = json_decode(file_get_contents('php://input'));

    handle_error(!validate_data($request, new CreateChatRequest()), 400, 'Data is worng in the new chat request body.');

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

        handle_error(!$error, 500, 'Something worng when searching user.');

        array_push($members_id, $result[0]['Uuid']);
    }

    //create chatroom
    $room_id = getuuid();
    $query = ("insert into chatroom (id, Name, Status, CreateOn) values (?, ?, ?, ?)");
    $stmt = $db->prepare($query);
    $error = $stmt->execute(array($room_id, $room_name, true, $create_on));

    handle_error(!$error, 500, 'Something worng when creating chatroom.');

    //create chatroom member
    foreach ($members_id as $member_id) {
        $query = ("insert into participants (UserID, RoomID) values (?, ?)");
        $stmt = $db->prepare($query);
        $error = $stmt->execute(array($member_id, $room_id));

        handle_error(!$error, 500, 'Something worng when creating chatroom member.');
    }


    success_res(201, 'Create chatroom successfully.', array('id' => $room_id, 'name' => $room_name));
} else if ($method == 'GET') {
} else {
    handle_error(true, 405, 'Method not allowed.');
}
?>