$(document).ready(function () {

	$(".checkMaxLength").each(function (i) {
        $(this).charCount({
            allowed: $(this).attr('maxlength')
        });
    });

	submitForm = function(moduleID) {
		$('html, body').animate({scrollTop: 0}, 'slow');
		$("#stat").html('');
		$('#stat').show();
		$('#addTileForm').fadeTo('slow', 0.5);

		$.ajax({
			type: "POST",
			url: "/plugins/tabousel/com/tabousel.cfc?method=tabouselTileAdd&returnFormat=json",
			data: $('#addTileForm').serialize(),
			dataType: 'json',
			success: function(response){
				setTimeout(function(){
						$('#addTileForm').fadeTo('slow', 1);
						$("#stat").html(response.message);
						if (response.success == 1) {
							setTimeout(function(){
								location.replace('http://'+adminURL+'/plugins/tabousel/edit.cfm?tabouselTileID='+response.tabouselTileID);
							}, 2500);
						}
					}, 1000);
			},
			error: function(response) {
				errorHandler();
			}
		});

	return false;
	};

	errorHandler = function()
		{
			alert("An error occurred while processing your request. Please refresh and try again.");
		};
});
//end doc ready
