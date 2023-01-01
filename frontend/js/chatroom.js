$(document).ready(function() {
    const q_str = window.location.search;
    const params = new URLSearchParams(q_str);
    console.log(params.get('room_id'));
    $.ajax({
        url: `/DatabaseProj/backend/chatroom.php?room_id=${params.get('room_id')}`,
        method: 'get',
        success: function(data) {
            // alert("success");
            console.log(data);
            const room = data['room'];
            const room_name = room['Name'];
            const room_created_date = room['CreateOn'];
            $('#room-name').text(room_name);
            // return;
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert(errorThrown);

            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
});