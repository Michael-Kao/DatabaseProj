<?php
require 'config.php';

class UpdateUserInfoRequest
{
    public $username;
    public $old_password;
    public $new_password;
    public $new_password2;
}

include 'db.php';
include 'function.php';

$method = $_SERVER['REQUEST_METHOD'];
authorize();

if ($method == 'GET') {
    $user_id = $_SESSION['id'];

    $query = ("select * from user where Uuid = ?");
    $stmt = $db->prepare($query);
    $error = $stmt->execute(array($user_id));
    $result = $stmt->fetchAll();

    handle_error(!$error, 500, 'Something worng when searching user.');
    handle_error(count($result) != 1, 500, 'Something worng when searching user.');

    $user = $result[0];

    $user_info = array(
        'id' => $user['Uuid'],
        'email' => $user['UserEmail'],
        'username' => $user['UserName'],
        'room_count' => $user['RoomCount'],
    );

    success_res(200, 'Get user info successfully.', $user_info);
} else if ($method == 'PUT') {
    $request = json_decode(file_get_contents('php://input'));

    handle_error(!validate_data($request, new UpdateUserInfoRequest()), 400, 'Data is worng in the update user info request body.');

    $user_id = $_SESSION['id'];
    $username = $request->username;
    $old_password = $request->old_password;
    $new_password = $request->new_password;
    $new_password2 = $request->new_password2;

    handle_error($username == '', 400, 'Username cannot be empty.');
    handle_error($new_password == '', 400, 'New password cannot be empty.');
    handle_error($new_password2 == '', 400, 'New password2 cannot be empty.');
    handle_error($new_password != $new_password2, 400, 'New password and new password2 are not the same.');

    $query = ("update user set UserName = ?, UserPassword = ? where Uuid = ? and UserPassword = ?");
    $stmt = $db->prepare($query);
    $error = $stmt->execute(array($username, $new_password, $user_id, $old_password));

    handle_error(!$error, 500, 'Something worng when updating user.');

    $user_info = array(
        'id' => $user_id,
        'username' => $username,
    );

    success_res(200, 'Update user info successfully.', $user_info);
} else {
    handle_error(true, 405, 'Method not allowed.');
}
?>