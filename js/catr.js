var msg = 'thingy';

function get_current_location() {
	var latitude;
	var longitude;
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(show_location);
		//console.log('get_current_location hi: ' + msg);

	} else {
		alert("Conseguite un navegador decente! Este no sirve!");
	}
}

function show_location(position) {

   	console.log('showLocation start: ' + msg);
   
   	latitude = position.coords.latitude;
   	longitude = position.coords.longitude;
  	msg = "Latitude : " + latitude + " Longitude: " + longitude;
   	$('#by_location').attr("href", "?location&lat="+latitude+"&long="+longitude);

   
   	var hidden_fields = '<input type="hidden" name="latitude" id="latitude" value="' + latitude + '">\
   		<input type="hidden" name="longitude" id="longitude" value="' + longitude + '">';
   	
   	$('.disabled').removeClass('disabled');

   	select_radiobtn(hidden_fields);
   
   //console.log('showLocation end: ' + msg);

   
  

	
}

function select_radiobtn(hidden_fields) {
	$('input:radio[name=by]').on('change', function (){

	   if ($('input:radio[value=location]').is(':checked')) {
		   $('.hidden-fields').html(hidden_fields);
		   
	   } else {
		   $('.hidden-fields').html("");
	   }
	   
   });
/*
	$('input:radio[value=location]').on('change', function (){

	   if ($('.hidden-fields').html() == "") {
		   //console.log('nothing');
		   $('.hidden-fields').html(hidden_fields);
	   } 
	   
   });
   $('input:radio[value=recent]').on('change', function (){
	   //console.log('hi radio');
	   if ($('.hidden-fields').html() != "") {
		   //console.log('caca');
		   $('.hidden-fields').html("");
	   } 
	   
   });
*/
}




$(document).ready(function() {
	//console.log('on ready 1: '+msg);
	get_current_location();
	//console.log('on ready 2, after get_current_location: '+msg);
	
});