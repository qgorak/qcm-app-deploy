

function GetData() {
    $.ajax({
        'url': '/notification/json',
        'method': 'GET',
        'timeout': 1000,
        'async': true
    }).done(function (data, textStatus, jqXHR) {
        if (!$.trim(data)) {
            $("#notificationCircle").css("visibility", "hidden");
        } else {
            $("#notificationCircle").css("visibility", "visible");
        }
    })
}
GetData();
var myInterval = setInterval(GetData, 8000)