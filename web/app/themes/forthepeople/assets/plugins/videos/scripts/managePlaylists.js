$(document).ready(function(){
	$('.full').dataTable({
		"bJQueryUI" : true, 
		"sPaginationType" : "full_numbers",
		"aoColumns": [ 
			/* ID */   null,
			/* Name */  null,
			/* Count */ null,
			/* Added */  null,
			/* Edit or Delete */    { "bSearchable": false,"bSortable": false}
		],
		"aaSorting": [[ 2, "asc" ]],
		"iDisplayLength": 50
	});
	
	submitForm = function() {
		$("#alert-playlistAdd").hide("slow");
		$('#modalAddPlaylist').fadeTo('slow', 0.8);
		setTimeout(function(){  
			$.ajax({
				type: "POST",
				url: "/plugins/videos/com/videos.cfc?method=addNewPlaylist&returnFormat=json",
				data: $('#addNewPlaylist').serialize(),
				dataType: 'json',
				success: function(response){
					$('#modalAddPlaylist').fadeTo('slow', 1);
					$("#alert-playlistAdd").show();
					$("#alert-playlistAdd").html(response.message);
					if (response.success == 1) {
						setTimeout(function(){  
							location.replace('http://' + adminURL + '/plugins/videos/editPlaylist.cfm?playlistID='+response.playlistID);
						}, 2000); 
					}
				},
				error: function(response) {
					errorHandler();
				}
			});
		}, 1500); 
		
	return false; 
	};
	
	deletePlaylist = function (videoPlaylistID){
		var agree=confirm("You are about to permanently delete this playlist. The videos will NOT be deleted. This action cannot be undone. Do you wish to continue? ");
		if (agree) {
			$.ajax({
			  url: "/plugins/videos/com/videos.cfc?method=playlistDelete&returnFormat=json",
			  data: ({videoPlaylistID : videoPlaylistID, userID : user}),
			  dataType: "json",
			  success: function(returnvalue) {
				  $("#stat").html(returnvalue.message);
				  if (returnvalue.success == 1) {
					  $("#pageRow-"+videoPlaylistID).remove();
					  $("#pageRow-"+videoPlaylistID).fadeOut('slow', 0);
					  $("#contentDisplayTable").removeClass('full').addClass('full');
					  $("#stat").html(returnvalue.message);
					}
				}
			});
		}
	}
	
});
//end doc ready
