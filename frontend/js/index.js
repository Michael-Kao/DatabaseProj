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
    let $container = $('#container');
    $.ajax({
        url: '/DatabaseProj/backend/get_session.php',
        method: 'get',
        success: function(data) {
            alert("success");
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

            let register_button = $('<a></a>').addClass('btn btn-secondary')
                                              .attr('type', 'button')
                                              .attr('href', 'register.html')
                                              .text('Register');
            let login_button = $('<a></a>').addClass('btn btn-secondary')
                                            .attr('type', 'button')
                                            .attr('href', 'login.html')
                                            .text('Login');
            let logout_button = $('<button></button>').addClass('btn btn-secondary')
                                                      .attr('onclick', 'logout()')
                                                      .text('Logout');
            if(session_value != ''){
                $container.append(logout_button);
            }
            else{
                $container.append(register_button, login_button);
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