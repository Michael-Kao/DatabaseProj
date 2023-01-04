const register_button = $('<a></a>').addClass('btn btn-link text-white text-decoration-none')
    .attr('id', 'register-page')
    .attr('href', 'register.html')
    .text('Register');
const login_button = $('<a></a>').addClass('btn btn-link text-white text-decoration-none')
    .attr('id', 'login-page')
    .attr('href', 'login.html')
    .text('Login');
const logout_button = $('<a></a>').addClass('btn btn-link text-white text-decoration-none')
    .attr('onclick', 'logout()')
    .text('Logout');
const new_chat_button = $('<a></a>').addClass('btn btn-link text-white text-decoration-none')
    .attr('id', 'new-chat')
    .attr('href', 'new_chat.html')
    .text('New Chatroom');

const user_button = $('<a></a>').addClass('btn btn-link text-white text-decoration-none')
    .attr('id', 'user-page')
    .attr('href', 'user.html')
    .text('Profile');

function logout() {
    console.log("asdf");
    $.ajax({
        url: '/DatabaseProj/backend/logout.php',
        method: 'post',
        success: function (res) {
            location.reload();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert(errorThrown);

            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
}




$(document).ready(function () {
    const $left_cntr = $('#home-container #left-side');
    const $right_cntr = $('#home-container #right-side');
    const $room_list = $('#room-list');
    const $room_area = $('.list-group');
    function list_rooms() {
        $.ajax({
            url: '/DatabaseProj/backend/index.php',
            method: 'get',
            success: function (res) {
                $.each(res['room_list'], function (index, value) {
                    let room_item = $('<a></a>').addClass('list-group-item list-group-item-action')
                        .attr('aria-current', 'true')
                        .attr('href', `chatroom.html?room_id=${value['RoomID']}`);
                    let room = $('<div></div>').addClass('d-flex w-100 justify-content-between');
                    let room_name = $('<h5></h5>').addClass('mb-1').text(value['Name']);
                    let room_created_date = $('<small></small>').text(value['CreateOn']);
                    room.append(room_name, room_created_date);
                    room_item.append(room);
                    $room_area.append(room_item);
                });
                $room_list.append($room_area);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);

                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    }
    $.ajax({
        url: '/DatabaseProj/backend/get_session.php',
        method: 'get',
        success: function (res) {
            let session = document.cookie.split("; ");
            let session_value = '';
            try {
                for (let i of session) {
                    if (i.split('=')[0] == 'user') {
                        session_value = i.split('=')[1];
                        break;
                    }
                }
            }
            catch (err) {
                console.log(err);
            }

            if (session_value != '') {
                $right_cntr.append(logout_button);
                $left_cntr.append(new_chat_button);
                $left_cntr.append(user_button);
                list_rooms();
            }
            else {
                $right_cntr.append(register_button, login_button);
                $room_list.append($('<h1></h1>')
                    .attr('id', 'welcome-title')
                    .text('Welcome'),
                    $('<p></p>')
                        .attr('id', 'welcome-content')
                        .text('歡迎來到我們ㄉChat room'));
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert(errorThrown);

            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
});