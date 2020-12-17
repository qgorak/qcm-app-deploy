function GetData(path) {
    $.ajax({
        'url': path,
        'method': 'GET',
        'async': true
    }).done(function (data) {	
        if (data=="false") {
            $("#notificationCircle").css("visibility", "hidden");
        } else {
            $("#notificationCircle").css("visibility", "visible");
        }
    })
}

GetData('/notification/json');
window.setInterval(function(){
  GetData('/notification/json');
}, 30000);