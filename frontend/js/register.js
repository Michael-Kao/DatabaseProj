$(document).ready(function() {
    $('#register-button').click(function() {
        register($('#username').val(), $('#email').val(), $('#password').val(), $('#password2').val());
    });
})


function register(username, email, password, password2) {
    $.ajax({
        url: '/DatabaseProj/backend/register.php',
        method: 'post',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify({
            username: username,
            email: email,
            password: password,
            password2: password2
        }),
        success: function(res) {
            location.replace("index.html");
            console.log(res);
        },
        error: function(res) {
            let status_code = res['status'];
            let response = res['responseJSON']['message'];
            alert(response);
            if (status_code == 401) {
                window.location.replace("/resbaseProj/frontend/html/login.html");
            }
            else {
                location.reload();
            }
        }
    })
}
