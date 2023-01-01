$(document).ready(function() {
    $.ajax({
        url: '/DatabaseProj/backend/new_chat.php',
        method: 'get',
        success: function(res) {
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert(errorThrown);

            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);


            if(jqXHR.status == 401) {
                location.replace("login.html");
            }
        }
    });

    $('#add-member').click(function() {
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
        $.ajax({
            url: '/DatabaseProj/backend/new_chat.php',
            method: 'post',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify({
                room_name: $('input[name="room-name"]').val(),
                members: members_list
            }),
            success: function(res) {
                // alert("success");
                console.log(res);
                location.replace("index.html");
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert(errorThrown);

                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);

                if(jqXHR.status == 401) {
                    location.replace("login.html");
                }
            }
        });
    });
})