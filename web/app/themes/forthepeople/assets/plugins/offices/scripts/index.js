$(document).ready(function(){
	$('.full').dataTable({
        "sScrollY" : "1000px",
		"sDom" : "<'H'lfr>tS<'F'>",
		"bDeferRender": true,
		"bJQueryUI" : true, 
		"bPaginate": false,
		"fnDrawCallback": function() {
			$('#officeTableBody').fadeIn(2000);
		}
			}).rowReordering({
				sURL:"/plugins/offices/com/offices.cfc?method=SetOfficeOrder&returnFormat=json",
				sRequestType: "GET",
				fnAlert: function(message) { 
					  alert("Error setting office order");
				}
	 });

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


