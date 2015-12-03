$(document).ready(function(){
	$('.full').dataTable({
        "sScrollY" : "1000px",
		"sDom" : "<'H'lfr>tS<'F'>",
		"bDeferRender": true,
		"bJQueryUI" : true, 
		"bPaginate": false,
		"aoColumns": [ 
		/* Client */   null,
		/* Snippet */  null,
		/* State */ null,
		/* Added */  null,
		/* Edit or Delete */    { "bSearchable": false,"bSortable": false}
		],
		"aaSorting": [[ 3, "asc" ]],
		"fnDrawCallback": function() {
			$('#testimonialsTableBody').fadeIn(2000);
		}
	});

});
function deleteTestimonial(testimonialID){
	var agree=confirm("You are about to permanently delete this testimonial. This action cannot be undone. Do you wish to continue? ");
	if (agree) {
		$.ajax({
		  url: "/plugins/testimonials/com/testimonials.cfc?method=testimonialDelete&returnFormat=json",
		  data: ({testimonialID : testimonialID, userID : user}),
		  dataType: "json",
		  success: function(returnvalue) {
			  $("#stat").html(returnvalue.message);
			  if (returnvalue.success == 1) {
				  $("#pageRow-"+testimonialID).remove();
				  $("#pageRow-"+testimonialID).fadeOut('slow', 0);
				  $("#contentDisplayTable").removeClass('full').addClass('full');
				  $("#stat").html(returnvalue.message);
				}
			}
		});
	}
}
