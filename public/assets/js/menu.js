$('#show-menu').click(function(){
  if ( $('#second-menu').css('visibility') == 'hidden' )
	$('#second-menu').css({opacity: 0, visibility: "visible"}).animate({opacity: 1}, 200);
  else
    $("#second-menu").animate({opacity: 0}, 200, function(){
    $("#second-menu").css("visibility","hidden");
});
});