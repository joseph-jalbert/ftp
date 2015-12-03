$(document).ready(function(){
	$('.full').dataTable({
		"sScrollY" : "1000px",
		"sDom" : "<'H'fr>tS<'F'>",
		"bDeferRender": true,
		"bJQueryUI" : true,
		"bPaginate": false,
		"aoColumns": [
		/* Title */   null,
		/* URL */   null,
		/* Snippet */ null,
		/* Added */  null,
		/* Edit or Delete */    { "bSearchable": false,"bSortable": false}
		],
		"aaSorting": [[ 0, "asc" ]],
		"fnDrawCallback": function() {
			$('#tabouselTableBody').fadeIn(2000);
		}
    });

});
function deleteTabouselTile(tabouselTileID){
	var agree=confirm("You are about to permanently delete this carousel tile. It will be removed from all carousels that use it. This action cannot be undone. Do you wish to continue? ");
	if (agree) {
		$.ajax({
		  url: "/plugins/tabousel/com/tabousel.cfc?method=tabouselTileDelete&returnFormat=json",
		  data: ({tabouselTileID : tabouselTileID, userID : user}),
		  dataType: "json",
		  success: function(returnvalue) {
			  $("#stat").html(returnvalue.message);
			  if (returnvalue.success == 1) {
				  $("#pageRow-"+tabouselTileID).remove();
				  $("#pageRow-"+tabouselTileID).fadeOut('slow', 0);
				  $("#tabouselTableBody").removeClass('full').addClass('full');
				  $("#stat").html(returnvalue.message);
				  setTimeout(function(){
						$("#stat").hide("slow");
					}, 2500);
				}
			}
		});
	}
}
