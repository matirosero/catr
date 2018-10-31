var msg = 'thingy';

function get_current_location() {
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(showLocation);
		console.log('get_current_location: ' + msg);
	} else {
		alert("Conseguite un navegador decente! Este no sirve!");
	}
}

function showLocation(position) {

   console.log('showLocation start: ' + msg);
   
   var latitude = position.coords.latitude;
   var longitude = position.coords.longitude;
   msg = "Latitude : " + latitude + " Longitude: " + longitude;
   $('#by_location').attr("href", "?location&lat="+latitude+"&long="+longitude);

   console.log('showLocation end: ' + msg);
   return msg;

/*
    x.innerHTML = "Latitude: " + position.coords.latitude + 
    "<br>Longitude: " + position.coords.longitude; 
*/
	
}


function get_search_criteria () {
	$('#main nav li a').on('click', function(){
		event.preventDefault();
		alert('hi');
	});
}

$(document).ready(function() {
	console.log('on ready 1: '+msg);
	get_current_location();
	console.log('on ready 2, after get_current_location: '+msg);
});