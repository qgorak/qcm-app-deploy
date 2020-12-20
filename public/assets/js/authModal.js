$("#login").click(function() {
	$('#authmodal').modal('show');
	if (event && event.stopPropagation) event.stopPropagation();
                        if (event && event.preventDefault) event.preventDefault();
                        url = '/loginForm';
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
                        url = '/registerForm';
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
$("#reset").click(function() {
	$('#authmodal').modal('show');
	if (event && event.stopPropagation) event.stopPropagation();
                        if (event && event.preventDefault) event.preventDefault();
                        url = '/resetForm';
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