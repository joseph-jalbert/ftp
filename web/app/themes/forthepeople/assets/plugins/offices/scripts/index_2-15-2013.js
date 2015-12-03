$(document).ready(function(){ 
	$('.full').dataTable({
                "bJQueryUI" : true, 
                "sPaginationType" : "full_numbers",
				"aoColumns": [ 
			/* City */   null,
			/* State */  null,
			/* Desc */ null,
			/* Order */    { "bSearchable": false,"bSortable": false},
			/* Added */  null,
			/* Edit or Delete */    { "bSearchable": false,"bSortable": false}
		],
				"aaSorting": [[ 0, "asc" ]],
				"iDisplayLength": 50
            }).rowReordering();

});
function deleteOffice(officeID){
	var agree=confirm("You are about to permanently delete this office. This action cannot be undone. Do you wish to continue? ");
	if (agree) {
		$.ajax({
		  url: "/plugins/offices/com/offices.cfc?method=officeDelete&returnFormat=json",
		  data: ({officeID : officeID, userID : user}),
		  dataType: "json",
		  success: function(returnvalue) {
			  $("#stat").html(returnvalue.message);
			  if (returnvalue.success == 1) {
				  $("#pageRow-"+officeID).remove();
				  $("#pageRow-"+officeID).fadeOut('slow', 0);
				  $("#contentDisplayTable").removeClass('full').addClass('full');
				  $("#stat").html(returnvalue.message);
				}
			}
		});
	}
}


