$(document).ready(function () {
    const q_str = location.search;
    const params = new URLSearchParams(q_str);
    let $room_content = $('#chatroom-content');
    function get_chat_history() {
        $.ajax({
            url: `/DatabaseProj/backend/chatroom.php?room_id=${params.get('room_id')}`,
            method: 'get',
            success: function (res) {
                console.log(res);
                const room = res['data']['room'];
                const count = res['data']['count'];
                const room_name = room['Name'];
                $('#room-name').text(`${room_name}(${count})`);
                let messages = res['data']['messages'];
                messages.sort(function (a, b) {
                    return new Date(a['Date']) - new Date(b['Date']);
                });
                $('#chatroom-content').empty();
                $.each(messages, function (index, value) {
                    let msg_date, msg;
                    let cookie = document.cookie.split("; ");
                    let user_id = '';
                    try {
                        for (let i of cookie) {
                            if (i.split('=')[0] == 'user') {
                                user_id = i.split('=')[1];
                            }
                        }
                    } catch (e) {
                        console.log(e);
                    }
                    if (user_id == value['UserID']) {
                        msg_date = $('<small></small>').addClass('text-white text-end').text(value['Date']);
                        msg = $('<p></p>').addClass('text-white text-end').text(`${value['Message']}`);
                    }
                    else {
                        msg_date = $('<small></small>').addClass('text-white text-start').text(value['Date']);
                        msg = $('<p></p>').addClass('text-white text-start').text(`${value['UserName']}:${value['Message']}`);
                    }
                    $room_content.append(msg_date, msg);
                    // console.log(value);
                });
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);

                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    }

    get_chat_history();

    setInterval(function () {
        get_chat_history();
    }, 3000);

    $('#say').click(function () {
        const message = $('#message').val();
        let msg_date = $('<small></small>').addClass('text-white text-end').text(new Date().toLocaleString());
        $room_content.append(msg_date, message);
        $.ajax({
            url: `/DatabaseProj/backend/chatroom.php?room_id=${params.get('room_id')}`,
            method: 'post',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify({
                message: message
            }),
            success: function (res) {
                $('#message').val('');
                console.log(res);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);

                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);

                if (jqXHR.status == 401) {
                    window.location.replace("/DatabaseProj/frontend/html/login.html");
                }
            }
        });
    });

    $('#add-btn').click(function () {
        let members = $('input[name="members"]');
        let members_list = [];
        for(let member of members) {
            members_list.push(member.value);
        }
        $.ajax({
            url: `/DatabaseProj/backend/modf_mem.php?room_id=${params.get('room_id')}`,
            method: 'post',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify({
                members: members_list
            }),
            success: function (res) {
                console.log(res);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);

                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);

                if (jqXHR.status == 401) {
                    window.location.replace("/DatabaseProj/frontend/html/login.html");
                }
            }
        });
    });

    $('#remove-btn').click(function () {
        let members = $('input[name="members"]');
        let members_list = [];
        for(let member of members) {
            members_list.push(member.value);
        }
        $.ajax({
            url: `/DatabaseProj/backend/modf_mem.php?room_id=${params.get('room_id')}`,
            method: 'delete',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify({
                members: members_list
            }),
            success: function (res) {
                console.log(res);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);

                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);

                if (jqXHR.status == 401) {
                    window.location.replace("/DatabaseProj/frontend/html/login.html");
                }
            }
        });
    });
});