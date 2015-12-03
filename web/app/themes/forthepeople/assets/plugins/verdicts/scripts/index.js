$(document).ready(function(){
	$('.full').dataTable({
        "sScrollY" : "1000px",
		"sDom" : "<'H'lfr>tS<'F'>",
		"bDeferRender": true,
		"bJQueryUI" : true, 
		"bPaginate": false,
		"aoColumns": [ 
			/* Amount */   {"sType":"currency"},
			/* PA */  null,
			/* Type */ null,
			/* Snippet */ null,
			/* State */ null,
			/* Added */  null,
			/* Edit or Delete */ { "bSearchable": false,"bSortable": false}
		],
		"aaSorting": [[ 5, "asc" ]],
		"fnDrawCallback": function() {
			$('#verdictTableBody').fadeIn(2000);
		}
     }).extend( $.fn.dataTableExt.oSort, {
		"currency-pre": function ( a ) {
			a = (a==="-") ? 0 : a.replace( /[^\d\-\.]/g, "" );
			return parseFloat( a );
		},
	 
		"currency-asc": function ( a, b ) {
			return a - b;
		},
	 
		"currency-desc": function ( a, b ) {
			return b - a;
		}
	});

});




function deleteVerdict(verdictID){
	var agree=confirm("You are about to permanently delete this verdict. This action cannot be undone. Do you wish to continue? ");
	if (agree) {
		$.ajax({
		  url: "/plugins/verdicts/com/verdicts.cfc?method=verdictDelete&returnFormat=json",
		  data: ({verdictID : verdictID, userID : user}),
		  dataType: "json",
		  success: function(returnvalue) {
			  $("#stat").html(returnvalue.message);
			  if (returnvalue.success == 1) {
				  $("#pageRow-"+verdictID).remove();
				  $("#pageRow-"+verdictID).fadeOut('slow', 0);
				  $("#contentDisplayTable").removeClass('full').addClass('full');
				  $("#stat").html(returnvalue.message);
				}
			}
		});
	}
}
