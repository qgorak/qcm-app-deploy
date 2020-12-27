$("#joingroup").click(function() {
    if (event && event.stopPropagation) event.stopPropagation();
    if (event && event.preventDefault) event.preventDefault();
    url = 'group/joinform';
    var self = this;

    $(this).addClass('loading');
    $.ajax({
        'url': url,
        'method': 'GET',
        'async': true
    }).done(function(data, textStatus, jqXHR) {
        $('#joinModal').modal('show');
        $("#response-joinform").html(data);
        $(self).removeClass('loading');
    });
});
