jQuery(document).ready(function(){ 

	var randomValidation1 = getRandomInt (1000, 9999);
	
	$("#validationPlaceholder").html(randomValidation1);
	$(".JSvalidate2").val(randomValidation1);

});
