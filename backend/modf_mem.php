<?php
require 'config.php';

class AddMemberRequest
{
    public $members;
}

class DeleteMemberRequest
{
    public $members;
}
include 'db.php';
include 'function.php';

$method = $_SERVER['REQUEST_METHOD'];

authorize();

if ($method == 'POST') {
    $request = json_decode(file_get_contents('php://input'));

    handle_error(!validate_data($request, new AddMemberRequest()), 400, 'Data is worng in the add member request body.');

    $params = array();
    parse_str($_SERVER['QUERY_STRING'], $params);

    handle_error(!isset($params['room_id']), 400, 'Missing room_id.');

    $room_id = $params['room_id'];
    $members = $request->members;
    array_unique($members);


    //get user id
    $members_id = array();
    foreach ($members as $member_email) {
        $query = ("select Uuid from user where UserEmail= ?");
        $stmt = $db->prepare($query);
        $error = $stmt->execute(array($member_email));
        $result = $stmt->fetchAll();

        handle_error(!$error, 500, 'Something worng when searching user.');

        if(count($result) == 1) array_push($members_id, $result[0]['Uuid']);
    }

    //create chatroom member
    foreach ($members_id as $member_id) {
        $query = ("insert into participants (UserID, RoomID) values (?, ?)");
        $stmt = $db->prepare($query);
        $error = $stmt->execute(array($member_id, $room_id));

        handle_error(!$error, 500, 'Something worng when creating chatroom member.');
    }

    success_res(201, 'Add member successfully.', array('id' => $room_id));
} else if ($method == 'DELETE') {
    $request = json_decode(file_get_contents('php://input'));

    handle_error(!validate_data($request, new DeleteMemberRequest()), 400, 'Data is worng in the delete member request body.');

    $params = array();
    parse_str($_SERVER['QUERY_STRING'], $params);

    handle_error(!isset($params['room_id']), 400, 'Missing room_id.');

    $room_id = $params['room_id'];
    $members = $request->members;
    array_unique($members);

    $members_id = array();
    foreach ($members as $member_email) {
        $query = ("select Uuid from user where UserEmail= ?");
        $stmt = $db->prepare($query);
        $error = $stmt->execute(array($member_email));
        $result = $stmt->fetchAll();

        handle_error(!$error, 500, 'Something worng when searching user.');

        if(count($result) == 1) array_push($members_id, $result[0]['Uuid']);
    }

    //delete chatroom member
    foreach ($members_id as $member_id) {
        $query = ("delete from participants where UserID = ? and RoomID = ?");
        $stmt = $db->prepare($query);
        $error = $stmt->execute(array($member_id, $room_id));

        handle_error(!$error, 500, 'Something worng when deleting chatroom member.');
    }

    success_res(200, 'Delete member successfully.', array('members' => $members));
} else {
    handle_error(true, 405, 'Method not allowed.');
}
?>