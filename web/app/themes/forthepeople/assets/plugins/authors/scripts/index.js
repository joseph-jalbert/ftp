$(document).ready(function(){
	$('.full').dataTable({
        "sScrollY" : "1000px",
		"sDom" : "<'H'lfr>tS<'F'>",
		"bDeferRender": true,
		"bJQueryUI" : true, 
		"bPaginate": false,
		"fnDrawCallback": function() {
			$('#authorsTableBody').fadeIn(2000);
			var tableBodyHeight = $('#authorsTableBody').height();
			if (tableBodyHeight < 1000) {
				$('#tableSection div.dataTables_scrollBody').height($('#authorsTableBody').height()+ 1);
			}
		}
	});

});
function deleteAuthor(authorID){
	var agree=confirm("You are about to permanently delete this author. This action cannot be undone. Do you wish to continue? ");
	if (agree) {
		$.ajax({
		  url: "/plugins/authors/com/authors.cfc?method=authorDelete&returnFormat=json",
		  data: ({authorID : authorID, userID : user}),
		  dataType: "json",
		  success: function(returnvalue) {
			  $("#stat").html(returnvalue.message);
			  if (returnvalue.success == 1) {
				  $("#pageRow-"+authorID).remove();
				  $("#pageRow-"+authorID).fadeOut('slow', 0);
				  $("#contentDisplayTable").removeClass('full').addClass('full');
				  $("#stat").html(returnvalue.message);
				}
			}
		});
	}
}


