$(document).ready(function(){
	$('.full').dataTable({
		"bJQueryUI" : true,
		"sPaginationType" : "full_numbers",
		"aoColumns": [
			/* ID */   null,
			/* Name */  null,
			/* Categories */ null,
			/* Added */  null,
			/* Edit or Delete */    { "bSearchable": false,"bSortable": false}
		],
		"aaSorting": [[ 2, "asc" ]],
		"iDisplayLength": 50
	});

	$("#tabouselCategories").tagit({
		triggerKeys: ['enter', 'comma', 'tab'],
		minLength: 2
	});

	$(".checkMaxLength").each(function (i) {
        $(this).charCount({
            allowed: $(this).attr('maxlength')
        });
    });

	submitForm = function() {
		$("#alert-tabouselAdd").hide("slow");
		$('#modalAddTabousel').fadeTo('slow', 0.8);
		setTimeout(function(){
			$.ajax({
				type: "POST",
				url: "/plugins/tabousel/com/tabousel.cfc?method=addNewTabousel&returnFormat=json",
				data: $('#addNewTabousel').serialize(),
				dataType: 'json',
				success: function(response){
					$('#modalAddTabousel').fadeTo('slow', 1);
					$("#alert-tabouselAdd").show();
					$("#alert-tabouselAdd").html(response.message);
					if (response.success == 1) {
						setTimeout(function(){
							location.replace('http://' + adminURL + '/plugins/tabousel/editTabousel.cfm?tabouselID='+response.tabouselID);
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

	deleteTabousel = function (tabouselID){
		var agree=confirm("You are about to permanently delete this tab carousel. The tab tiles will NOT be deleted. This action cannot be undone. Do you wish to continue? ");
		if (agree) {
			$.ajax({
			  url: "/plugins/tabousel/com/tabousel.cfc?method=tabouselDelete&returnFormat=json",
			  data: ({tabouselID : tabouselID, userID : user}),
			  dataType: "json",
			  success: function(returnvalue) {
				  $("#stat").html(returnvalue.message);
				  if (returnvalue.success == 1) {
					  $("#pageRow-"+tabouselID).remove();
					  $("#pageRow-"+tabouselID).fadeOut('slow', 0);
					  $("#contentDisplayTable").removeClass('full').addClass('full');
					  setTimeout(function(){
							$("#stat").hide("slow");
						}, 2500);
					}
				}
			});
		}
	}

});
//end doc ready
