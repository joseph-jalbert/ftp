
function doAddTileToCarousel(tileTitle,tileID,tileUUID){
	var lastWithOL = $('#sortWrapper ol.sortable li.nav-item:has(ol)').last();
	var last = $('#sortWrapper ol.sortable li.nav-item').last();
	if( lastWithOL.is(last)){
		$('#sortWrapper ol.sortable li.nav-item ol').last().append('<li data-tileID="'+tileID+'" data-tileTitle="'+tileTitle+'" data-tileUUID="'+tileUUID+'"> <div class="portlet"><header><h2><span>'+tileTitle+'</span><a class="ui-icon ui-icon-minus fr clickIcon deleteCarouselItem"></a></h2></header></div></li>');
	} else {
		$('#sortWrapper ol.sortable li.nav-item').last().append('<ol><li data-tileID="'+tileID+'" data-tileTitle="'+tileTitle+'" data-tileUUID="'+tileUUID+'"> <div class="portlet"><header><h2><span>'+tileTitle+'</span><a class="ui-icon ui-icon-minus fr clickIcon deleteCarouselItem"></a></h2></header></div></li></ol>');
	}

	createPortlet();
};

function doRestoreTileToOptions(tileTitle,tileID,tileUUID){
	$('#availableList ul.available').append('<li style="display:block;"><a class="icon-button al" data-icon-primary="ui-icon-circle-plus" data-icon-only="false" data-tileTitle="'+tileTitle+'" data-tileID="'+tileID+'" data-tileUUID="'+tileUUID+'" style="display:block;">'+tileTitle+'</a></li>');
	$('#availableList ul li a').last().button({
					icons : {
						primary : $('#availableList ul li a').last().attr('data-icon-primary')
					},
					text : $(this).attr('data-icon-only') === 'true' ? false : true
		}).click(function(){
			doAddTileToCarousel(tileTitle,tileID,tileUUID);
			 $(this).parent().remove();
		});
};

function submitForm(){
	$('html, body').animate({scrollTop:0}, 'slow');
	$("#stat").html('');
	$('#stat').show();
	$("#loader").show();
	$("#editTabouselForm").fadeTo('slow', 0.5);

	$.ajax({
		type: "POST",
		url: "/plugins/tabousel/com/tabousel.cfc?method=modifyTabousel&returnFormat=json",
		data: $('#editTabouselForm').serialize(),
		dataType: 'json',
		success: function(response){
			setTimeout(function(){
				$("#editTabouselForm").fadeTo('slow', 1);
				$("#loader").hide();
				$("#stat").html(response.message);
				if (response.success == 1) {
					setTimeout(function(){
						location.replace('http://' + adminURL + '/plugins/tabousel/editTabousel.cfm?tabouselID='+response.tabouselID);
					}, 2000);
				}
			}, 1000);
		},
		error: function(response) {
			errorHandler();
		}
	});
	return false;
};

function doSaveCarousel(){
	$(".carouselSaveBtn").attr("disabled", "disabled").addClass('grey');
	$('html, body').animate({scrollTop:0}, 'slow');
	$("#stat").html('');
	$('#stat').show();
	$("#loader").show();
	$("#sortWrapper").fadeTo('slow', 0.5);

	var tabouselID = $('#tabouselID').val();
	var userID = $('#userID').val();
	var carouselArray = buildCarouselToJSON();
	console.log(carouselArray);
	$.ajax({
		type: "POST",
		url: "/plugins/tabousel/com/tabousel.cfc?method=setTabouselTiles&returnFormat=json",
		data: {tabouselID: tabouselID, carouselArray: carouselArray, userID: userID},
		dataType: 'json',
		success: function(response){
			setTimeout(function(){
				$("#sortWrapper").fadeTo('slow', 1);
				$("#loader").hide();
				$("#stat").html(response.message);
				if (response.success == 1) {
					autoFadeStatMessage();
					$(".carouselSaveBtn").removeAttr("disabled").removeClass('grey');
				}
			}, 1000);
		},
		error: function(response) {
			errorHandler();
		}
	});
};

function errorHandler(){
	alert("An error occurred while processing the form. Please refresh and try again.");
};

//callbacks from navBuilder
function builderSuccess(returnvalue){
	$('#stat').html(returnvalue.message);
}
function builderFail(){
	alert("There was a problem building le Nav :( ");
}


function buildCarouselToJSON(){

	var carouselArrayBuilder='';
	var parentList = $('ol.sortable > li').length;

$('ol.sortable > li').each(function(parentIndex, parent){
		parentIndex++;

		var tileID = $(this).attr('data-tileID');
		var tileTitle = $(this).attr('data-tileTitle');

		carouselArrayBuilder = carouselArrayBuilder + '{'+
						  '"tileID":"'+ tileID +'",'+
						  '"tileTitle":"'+ tileTitle +'",'+
						  '"children":';

		var childrenList = $('ol.sortable > li ol li').length;

		if(childrenList == 0){
						var navChildArrayBuilder = '[]';
						console.log('no kids');
				}else{
						var navChildArrayBuilder = [];
						var tileUUID = $(this).attr('data-tileUUID');

				//child loop
				$('[data-tileUUID="'+tileUUID+'"] ol li').each(function(childIndex, child){
						childIndex++;
						var tileID = $(this).attr('data-tileID');
						var tileTitle = $(this).attr('data-tileTitle');

							navChildArrayBuilder.push({
								"tileID": tileID,
								"tileTitle": tileTitle
							});

				});// end child loop

			}; //end if has children

			carouselArrayBuilder = carouselArrayBuilder + JSON.stringify(navChildArrayBuilder) +'}';

		if (parentIndex < parentList){
				carouselArrayBuilder = carouselArrayBuilder + ',';
		};

});//end sortable each loop

carouselArrayBuilder = '['+carouselArrayBuilder+']';

//return $.parseJSON(carouselArrayBuilder);
return carouselArrayBuilder;

}; //end buildPlaylistToJSON


$(document).ready(function(){

	$('ol.sortable').nestedSortable({
		disableNesting: 'no-nest',
		forcePlaceholderSize: true,
		handle: 'div',
		helper:	'clone',
		protectRoot: true,
		items: 'li',
		maxLevels: 2,
		opacity: .6,
		placeholder: 'placeholder',
		revert: 250,
		tabSize: 25,
		tolerance: 'pointer',
		toleranceElement: '> div'
	});

	$('#availableList a').click(function() {
		var tileTitle = $(this).attr('data-tileTitle');
		var tileID = $(this).attr('data-tileID');
		var tileUUID = $(this).attr('data-tileUUID');
		doAddTileToCarousel(tileTitle,tileID,tileUUID);
		$(this).parent().remove();
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

	//live event handler for deleting carousel items
	$(document).on('click', '.deleteCarouselItem', function(e){
		var tileTitle = $(this).parents().eq(3).attr('data-tileTitle');
		var tileID = $(this).parents().eq(3).attr('data-tileID');
		var tileUUID = $(this).attr('data-tileUUID');
		doRestoreTileToOptions(tileTitle,tileID,tileUUID);

		var targetTabouselItem = $(this).parents().eq(3);
		$(targetTabouselItem).remove();
	});

});
//end doc ready
