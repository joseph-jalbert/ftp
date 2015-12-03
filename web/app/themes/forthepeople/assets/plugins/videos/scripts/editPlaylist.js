function doAddVideoToPlaylist(videoTitle,videoID){
	$('#sortWrapper ol.sortable').append('<li data-videoID="'+videoID+'" data-videoTitle="'+videoTitle+'" data-uid="'+genUID()+'">'+
	'<div class="portlet"><header><h2><span>'+videoTitle+'</span><a class="ui-icon ui-icon-minus fr clickIcon deletePlaylistItem"></a></h2></header></div></li>');
	
	createPortlet();
};

function doRestoreVideoToOptions(videoTitle,videoID){
	$('#availableList ul.available').append('<li style="display:block;"><a class="icon-button al" data-icon-primary="ui-icon-circle-plus" data-icon-only="false" data-videoTitle="'+videoTitle+'" data-videoID="'+videoID+'" style="display:block;">'+videoTitle+'</a></li>');
	$('#availableList ul li a').last().button({
					icons : {
						primary : $('#availableList ul li a').last().attr('data-icon-primary')
					}, 
					text : $(this).attr('data-icon-only') === 'true' ? false : true
		}).click(function(){
			doAddVideoToPlaylist(videoTitle,videoID);
			 $(this).parent().remove();
		});
};

function saveSiteMapSettings() {

	$(".siteMapSaveBtn").attr("disabled", "disabled").addClass('grey');
	$('html, body').animate({scrollTop:0}, 'slow');
	$("#stat").html('');
	$('#stat').show();
	$("#loader").show();
	$("#sortWrapper").fadeTo('slow', 0.5);	

	var videoPlaylistID = $('#videoPlaylistID').val();
	var userID = $('#userID').val();
	var videoPlaylistIdentifier = $('#videoPlaylistIdentifier').val();
	var videoPlaylistSiteMap = $('#videoPlaylistSiteMap').is(':checked');

		if(videoPlaylistSiteMap == false) {
			$(".siteMapURL").hide();
		} else {
			$(".siteMapURL").show();
		}

		$.ajax({
			type: "POST",
			url: "/plugins/videos/com/videos.cfc?method=setVideoSiteMapSettings&returnFormat=json",
			data: {videoPlaylistID: videoPlaylistID, videoPlaylistIdentifier: videoPlaylistIdentifier, videoPlaylistSiteMap: videoPlaylistSiteMap, userID: userID},
			dataType: 'json',
			success: function(response){
				setTimeout(function(){  
					$("#sortWrapper").fadeTo('slow', 1);
					$("#loader").hide();
					$("#stat").html(response.message);
					if (response.success == 1) {
						autoFadeStatMessage();
						$(".siteMapSaveBtn").removeAttr("disabled").removeClass('grey');
					}							
				}, 1000); 
			},
			error: function(response) {
				errorHandler();
			}
		});  

};


function doSavePlaylist(){
		$(".playlistSaveBtn").attr("disabled", "disabled").addClass('grey');
		$('html, body').animate({scrollTop:0}, 'slow');
		$("#stat").html('');
		$('#stat').show();
		$("#loader").show();
		$("#sortWrapper").fadeTo('slow', 0.5);	

		var videoPlaylistID = $('#videoPlaylistID').val();
		var videoPlaylistName = $('input[name="videoPlaylistName"]').val();
		var userID = $('#userID').val();
		var playlistArray = buildPlaylistToJSON();
	
		$.ajax({
			type: "POST",
			url: "/plugins/videos/com/videos.cfc?method=modifyPlaylist&returnFormat=json",
			data: {videoPlaylistID: videoPlaylistID, playlistArray: playlistArray, videoPlaylistName: videoPlaylistName, userID: userID},
			dataType: 'json',
			success: function(response){
				setTimeout(function(){  
					$("#sortWrapper").fadeTo('slow', 1);
					$("#loader").hide();
					$("#stat").html(response.message);
					if (response.success == 1) {
						autoFadeStatMessage();
						$(".playlistSaveBtn").removeAttr("disabled").removeClass('grey');
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

function genUID(){
    // always start with a letter (for DOM friendlyness)
    var idstr=String.fromCharCode(Math.floor((Math.random()*25)+65));
    do {                
        // between numbers and characters (48 is 0 and 90 is Z (42-48 = 90)
        var ascicode=Math.floor((Math.random()*42)+48);
        if (ascicode<58 || ascicode>64){
            // exclude all chars between : (58) and @ (64)
            idstr+=String.fromCharCode(ascicode);    
        }                
    } while (idstr.length<8);
    return (idstr);
};

function doPopulateUID(){
	$('[data-uid]').each(function(i){
		var UID = genUID();
		$(this).attr('data-uid', UID);
	});
};

//callbacks from navBuilder	
function builderSuccess(returnvalue){
	$('#stat').html(returnvalue.message); 
}
function builderFail(){
	alert("There was a problem building le Nav :( ");
}


function buildPlaylistToJSON(){

	var playlistArrayBuilder='';
	var parentList = $('ol.sortable > li').length;
	
$('ol.sortable > li').each(function(parentIndex, parent){
		parentIndex++;
		
		var videoID = $(this).attr('data-videoID');
		var videoTitle = $(this).attr('data-videoTitle');		

		playlistArrayBuilder = playlistArrayBuilder + '{'+
						  '"videoID":"'+ videoID +'",'+
						  '"videoTitle":"'+ videoTitle +'"}';		
						  
		if (parentIndex < parentList){
				playlistArrayBuilder = playlistArrayBuilder + ',';
		};
	
});//end sortable each loop

playlistArrayBuilder = '['+playlistArrayBuilder+']';

//return $.parseJSON(playlistArrayBuilder);
return playlistArrayBuilder;
	
}; //end buildPlaylistToJSON


$(document).ready(function(){
	
	$("#videoPlaylistSiteMap").iButton({
		labelOn: "Enable", 
		labelOff: "Disable"
	});


	doPopulateUID();

	$('ol.sortable').nestedSortable({
		disableNesting: 'no-nest',
		forcePlaceholderSize: true,
		handle: 'div',
		helper:	'clone',
		items: 'li',
		maxLevels: 1,
		opacity: .6,
		placeholder: 'placeholder',
		revert: 250,
		tabSize: 25,
		tolerance: 'pointer',
		toleranceElement: '> div'
	});
	
	$('#availableList a').click(function() {
		var videoTitle = $(this).attr('data-videoTitle'); 
		var videoID = $(this).attr('data-videoID');
		doAddVideoToPlaylist(videoTitle,videoID);
		$(this).parent().remove();
	});

	//live event handler for deleting playlist items
	$(document).on('click', '.deletePlaylistItem', function(e){
		var videoTitle = $(this).parents().eq(3).attr('data-videoTitle'); 
		var videoID = $(this).parents().eq(3).attr('data-videoID');
		doRestoreVideoToOptions(videoTitle,videoID);
		
		var targetPlaylistItem = $(this).parents().eq(3);
		$(targetPlaylistItem).remove();		
	}); 

	

			
});
//end doc ready














