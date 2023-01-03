$(document).ready(function() {
    $('#add-mem').click(function() {
        let area = $('<div></div>').attr('class', 'input-group mb-3');
        let new_member = $('<input>').attr('name', 'members')
                                      .attr('type', 'text')
                                      .attr('class', 'form-control')
                                      .attr('placeholder', 'Member\'s email')
                                      .attr('aria-label', 'Member\'s email')
                                      .attr('aria-describedby', 'basic-addon2');
        area.append(new_member);
        $('#modal-body').append(area);
    });
})