function datePicker(lang){
	var days=['S', 'M', 'T', 'W', 'T', 'F', 'S'];
    var months=['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    var monthsShort=['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
	var ampm=true;
	if(lang=='fr_FR'){
		var days=['D', 'L', 'M', 'M', 'J', 'V', 'S'];
    	var months=['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
    	var monthsShort=['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec'];
		var ampm=false;
	}
	var today = new Date();
	$('.ui.dropdown').dropdown();
	$('.ui.calendar').calendar();
	$('#rangestart').calendar({
		text:{
			days: days,
			months: months,
			monthsShort: monthsShort
		},
	    endCalendar: $('#rangeend'),
	    ampm: ampm,
	    minDate: new Date(today.getFullYear(), today.getMonth(), today.getDate())
	});
	$('#rangeend').calendar({
		text:{
			days: days,
			months: months,
			monthsShort: monthsShort
		},
	    startCalendar: $('#rangestart'),
	    ampm: ampm,
	    minDate: new Date(today.getFullYear(), today.getMonth(), today.getDate()),
	    maxDate: $("input[name=dated]").val()
	});
}