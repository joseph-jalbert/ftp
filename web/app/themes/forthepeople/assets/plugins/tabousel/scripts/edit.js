$(document).ready(function () {

	$(".checkMaxLength").each(function (i) {
        $(this).charCount({
            allowed: $(this).attr('maxlength')
        });
    });

	submitForm = function() {
		$('html, body').animate({scrollTop: 0}, 'slow');
		$("#stat").html('');
		$('#stat').show();
		$('#editTileForm').fadeTo('slow', 0.5);

		$.ajax({
			type: "POST",
			url: "/plugins/tabousel/com/tabousel.cfc?method=tabTileSet&returnFormat=json",
			data: $('#editTileForm').serialize(),
			dataType: 'json',
			success: function(response){
				setTimeout(function(){
						$('#editTileForm').fadeTo('slow', 1);
						$("#stat").html(response.message);
						if (response.success == 1) {
							setTimeout(function(){
								$("#stat").hide("slow");
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
