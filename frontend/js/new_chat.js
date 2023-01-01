$(document).ready(function() {
    // let test = document.getElementsByName('number');
    // let test = $('input[name="number"]')
    // console.log(test[0].value);
    $('#add-member').click(function() {
        console.log("adsf");
        let area = $('<div></div>').attr('class', 'input-group mb-3');
        let new_member = $('<input>').attr('name', 'members')
                                      .attr('type', 'text')
                                      .attr('class', 'form-control')
                                      .attr('placeholder', 'Member\'s email')
                                      .attr('aria-label', 'Member\'s email')
                                      .attr('aria-describedby', 'basic-addon2');
        area.append(new_member);
        $('#room-info').append(area);
    });
    $('#new-chat').click(function() {
        let members = $('input[name="members"]');
        let members_list = [];
        for(let member of members) {
            console.log(member.value);
            members_list.push(member.value);
        }
        // console.log($('input[name="members"]'));
        $.ajax({
            url: '/DatabaseProj/backend/new_chat.php',
            method: 'post',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify({
                room_name: $('input[name="room-name"]').val(),
                members: members_list
            }),
            success: function(data) {
                // alert("success");
                console.log(data);
                // location.replace("chatroom.html");
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("error");
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    });
})