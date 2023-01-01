$(document).ready(function () {
    const q_str = location.search;
    const params = new URLSearchParams(q_str);
    let $room_content = $('#chatroom-content');
    $.ajax({
        url: `/DatabaseProj/backend/chatroom.php?room_id=${params.get('room_id')}`,
        method: 'get',
        success: function (res) {
            console.log(res);
            const room = res['data']['room'];
            const room_name = room['Name'];
            const room_created_date = room['CreateOn'];
            $('#room-name').text(room_name);
            let messages = res['data']['messages'];
            messages.sort(function (a, b) {
                return new Date(a['Date']) - new Date(b['Date']) ;
            });
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
                if(user_id == value['UserID']){
                    msg_date = $('<small></small>').addClass('text-end').text(value['Date']);
                    msg = $('<p></p>').addClass('text-end').text(`${value['Message']}`);
                }
                else {
                    msg_date = $('<small></small>').addClass('text-start').text(value['Date']);
                    msg = $('<p></p>').addClass('text-start').text(`${value['UserName']}:${value['Message']}`);
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
    $('#say').click(function () {
        const message = $('#message').val();
        $.ajax({
            url: `/DatabaseProj/backend/chatroom.php?room_id=${params.get('room_id')}`,
            method: 'post',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify({
                message: message
            }),
            success: function (res) {
                console.log(res);
                location.reload();
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