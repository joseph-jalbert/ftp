$(document).ready(function () {
	
	$(".checkMaxLength").each(function (i) {
        $(this).charCount({
            allowed: $(this).attr('maxlength')
        });
    });
	
	$(".chzn-select").chosen({
		"search_contains": true
	});
	
	$("#videoDisplay").iButton({
		labelOn: "Show",
		labelOff: "Hide"
	});

	setParentList = function(){
		$.ajax({
			url: "/com/engine/content.cfc?method=SetParentDropdown&returnFormat=plain",
			dataType: "text",
			success: function(data) {
				$('#pageChooser').append(data);
				$('#dropdownLoader').remove();
				$("#pageChooser").trigger("liszt:updated");
			}
		});
	};
	
	setParentList();
	
	$("#videoURLPath").focus(function() { $(this).select(); } );

	submitForm = function() {	
		$('html, body').animate({scrollTop: 0}, 'slow');
		$("#stat").html('');
		$('#stat').show();	
		$('#editVideoForm').fadeTo('slow', 0.5);
		
		$.ajax({
			type: "POST",
			url: "/plugins/videos/com/videos.cfc?method=videosSet&returnFormat=json",
			data: $('#editVideoForm').serialize(),
			dataType: 'json',
			success: function(response){
				setTimeout(function(){  
						$('#editVideoForm').fadeTo('slow', 1);
						$("#stat").html(response.message);
					}, 1000); 
			},
			error: function(response) {
				errorHandler();
			}
		});
		
	return false; 
	};
	
	removeVideo = function(videoID) {	
		var agree = confirmSubmit();
		if (agree == true) {
			var userID = $('#userID').val();
			$('html, body').animate({scrollTop: $('#videoUploadManager').position().top}, 'slow');
			$("#stat2").html('');
			$('#stat2').show();	
			$('#videoUploadManager').fadeTo('slow', 0.5);
			
			$.ajax({
				type: "POST",
				url: "/plugins/videos/com/videos.cfc?method=videoDeleteFile&returnFormat=json",
				data: { videoID: +videoID, userID: +userID},
				dataType: 'json',
				success: function(response){
					setTimeout(function(){  
							$('#videoUploadManager').fadeTo('slow', 1);
							$("#stat2").html(response.message);
							if (response.success == 1) {
								setTimeout(function(){  
									location.replace('http://'+adminURL+'/plugins/videos/edit.cfm?videoID='+videoID);
								}, 2000); 
							}
						}, 1000); 
				},
				error: function(response) {
					errorHandler();
				}
			});
		};
	return false; 
	};
	
	confirmSubmit = function() {
		var agree = confirm("You are about to permanently delete this video file. This action cannot be undone. Do you wish to continue?");
		if (agree)
			return true ;
		else
			return false ;
	}
	
	errorHandler = function()
		{
			alert("An error occurred while processing your request. Please refresh and try again.");
		};
});
//end doc ready
