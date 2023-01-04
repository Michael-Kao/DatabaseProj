$(document).ready(function() {
    $.ajax({
        url: '/DatabaseProj/backend/user.php',
        method: 'get',
        success: function (res) {
            console.log(res);
            let user = res['data'];
            $('#old_username').text(`${user['username']}'s Profile`);
            $('#email').text(`Email: ${user['email']}`);
            $('#count').text(`Number of chatrooms: ${user['room_count']}`);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert(errorThrown);

            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);

            if (jqXHR.status == 401) {
                window.location.href = '/DatabaseProj/frontend/login.html';
            }
        }
    });

    $('#save').click(function () {
        let username = $('#new_username').val();
        let old_password = $('#old_password').val();
        let new_password = $('#new_password').val();
        let new_password2 = $('#new_password2').val();

        if (new_password != new_password2) {
            alert('New passwords do not match');
            return;
        }

        $.ajax({
            url: '/DatabaseProj/backend/user.php',
            method: 'put',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify({
                username: username,
                old_password: old_password,
                new_password: new_password,
                new_password2: new_password2
            }),
            success: function (res) {
                console.log(res);
                alert('Profile updated');
                location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);

                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    });
});