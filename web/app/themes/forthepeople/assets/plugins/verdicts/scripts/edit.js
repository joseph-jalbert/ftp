$(document).ready(function () {
	
	$(".checkMaxLength").each(function (i) {
        $(this).charCount({
            allowed: $(this).attr('maxlength')
        });
    });
	
	$(".chzn-select").chosen({
		"search_contains": true
	});

	submitForm = function(moduleID) {	
		$('html, body').animate({scrollTop: 0}, 'slow');
		$("#stat").html('');
		$('#stat').show();	
		$('#editVerdictForm').fadeTo('slow', 0.5);
		
		$.ajax({
			type: "POST",
			url: "/plugins/verdicts/com/verdicts.cfc?method=verdictsSet&returnFormat=json",
			data: $('#editVerdictForm').serialize(),
			dataType: 'json',
			success: function(response){
				setTimeout(function(){  
						$('#editVerdictForm').fadeTo('slow', 1);
						$("#stat").html(response.message);
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
