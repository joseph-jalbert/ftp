$(document).ready(function(){
	$('.full').dataTable({
		"sScrollY" : "1000px",
		"sDom" : "<'H'fr>tS<'F'>",
		"bDeferRender": true,
		"bJQueryUI" : true, 
		"bPaginate": false,
		"aoColumns": [ 
		/* Title */   null,
		/* Attorney */  null,
		/* PA */ null,
		/* Snippet */ null,
		/* Added */  null,
		/* Edit or Delete */    { "bSearchable": false,"bSortable": false}
		],
		"aaSorting": [[ 0, "asc" ]],
		"fnDrawCallback": function() {
			$('#videosTableBody').fadeIn(2000);
		}
    });

});
function deleteVideo(videoID){
	var agree=confirm("You are about to permanently delete this video. This action cannot be undone. Do you wish to continue? ");
	if (agree) {
		$.ajax({
		  url: "/plugins/videos/com/videos.cfc?method=videoDelete&returnFormat=json",
		  data: ({videoID : videoID, userID : user}),
		  dataType: "json",
		  success: function(returnvalue) {
			  $("#stat").html(returnvalue.message);
			  if (returnvalue.success == 1) {
				  $("#pageRow-"+videoID).remove();
				  $("#pageRow-"+videoID).fadeOut('slow', 0);
				  $("#contentDisplayTable").removeClass('full').addClass('full');
				  $("#stat").html(returnvalue.message);
				}
			}
		});
	}
}
