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
        success: function(data) {
            window.location.replace("/DatabaseProj/frontend/html/index.html");
            console.log(data['responseJSON']);
        },
        error: function(data) {
            let response = data['responseJSON']['message'];
            alert(response);
        }
    })
}
