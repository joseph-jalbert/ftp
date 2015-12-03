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
		$('#addVideoForm').fadeTo('slow', 0.5);
		
		$.ajax({
			type: "POST",
			url: "/plugins/videos/com/videos.cfc?method=videoAdd&returnFormat=json",
			data: $('#addVideoForm').serialize(),
			dataType: 'json',
			success: function(response){
				setTimeout(function(){  
						$('#addVideoForm').fadeTo('slow', 1);
						$("#stat").html(response.message);
						if (response.success == 1) {
							setTimeout(function(){  
								location.replace('http://'+adminURL+'/plugins/videos/edit.cfm?videoID='+response.videoID);
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
