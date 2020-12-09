$("#login").click(function() {
	$('#authmodal').modal('show');
	if (event && event.stopPropagation) event.stopPropagation();
                        if (event && event.preventDefault) event.preventDefault();
                        url = 'http://127.0.0.1:8090/loginForm';
                        var self = this;

                        $(this).addClass('loading');
                        $.ajax({
                            'url': url,
                            'method': 'GET',
                            'async': true
                        }).done(function(data, textStatus, jqXHR) {


                            $("#responseauth").html(data);
                            $(self).removeClass('loading');
                        });

                    });
$("#register").click(function() {
	$('#authmodal').modal('show');
	if (event && event.stopPropagation) event.stopPropagation();
                        if (event && event.preventDefault) event.preventDefault();
                        url = 'http://127.0.0.1:8090/registerForm';
                        var self = this;

                        $(this).addClass('loading');
                        $.ajax({
                            'url': url,
                            'method': 'GET',
                            'async': true
                        }).done(function(data, textStatus, jqXHR) {


                            $("#responseauth").html(data);
                            $(self).removeClass('loading');
                        });

                    });
