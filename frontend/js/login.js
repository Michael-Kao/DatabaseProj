$(document).ready(function() {
    $('#login-button').click(function(){
        login($('#username').val(), $('#password').val())
    });
})

function login(username, password) {
    $.ajax({
        url: '/DatabaseProj/backend/login.php',
        type: 'post',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify({
            username: username,
            password: password
        }),
        success: function(data) {
            window.location.replace("/DatabaseProj/frontend/html/index.html");
            console.log(data);
        },
        error: function(data) {
            console.log(data);
        }
    })
}