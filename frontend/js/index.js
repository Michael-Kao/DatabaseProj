let register_button = $('<a></a>').addClass('btn btn-secondary')
                                    .attr('id', 'register-page')
                                    .attr('type', 'button')
                                    .attr('href', 'register.html')
                                    .text('Register');
let login_button = $('<a></a>').addClass('btn btn-secondary')
                                .attr('id', 'login-page')
                                .attr('type', 'button')
                                .attr('href', 'login.html')
                                .text('Login');
let logout_button = $('<button></button>').addClass('btn btn-secondary')
                                            .attr('onclick', 'logout()')
                                            .text('Logout');
let new_chat_button = $('<a></a>').addClass('btn btn-secondary')
                                  .attr('type', 'button')
                                  .attr('id', 'new-chat')
                                  .attr('href', 'new_chat.html')
                                  .text('New Chatroom');

function logout() {
    console.log("asdf");
    $.ajax({
        url: '/DatabaseProj/backend/logout.php',
        method: 'post',
        success: function(data) {
            // alert("success");
            location.reload();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert("error");
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
}

$(document).ready(function() {
    let $left_cntr = $('#home-container #left-side');
    let $right_cntr = $('#home-container #right-side');
    $.ajax({
        url: '/DatabaseProj/backend/get_session.php',
        method: 'get',
        success: function(data) {
            // alert("success");
            console.log(data);
            let session = document.cookie.split("; ");
            let session_value = '';
            try{
                for(let i of session){
                    if(i.split('=')[0] == 'user'){
                        session_value = i.split('=')[1];
                        break;
                    }
                }
            }
            catch(err) {
                console.log(err);
            }

            if(session_value != ''){
                $right_cntr.append(logout_button);
                $left_cntr.append(new_chat_button);
            }
            else{
                $right_cntr.append(register_button, login_button);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert("error");
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
});