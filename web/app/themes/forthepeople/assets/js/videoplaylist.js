jQuery(function($){
/*	$(document).on('click','.videoplaylist', function(e){
		var videoClip = $(this).data('video');
		_V_('videoplayer-list').src(videoClip).play();
	});
*/
	
	$.each(_V_.players, function(index, value) {
	   var player = this.id;
		
		$(document).on('click','.videoplaylist', function(){
			var videoMeta = $(this).parentsUntil('li').parent().find('.meta').html();
			var videoClip = $(this).parentsUntil('li').parent().data('video');
			_V_(player).src(videoClip).play();
			$("html, body").animate({ scrollTop: $('#'+player).offset().top - 30 }, 1000);
			$('.video-meta').html(videoMeta);
			$('.video-meta button').removeClass('videoplaylist');
		}); 
		
    });//each
	
});//doc rdy