	$('.live-chat, #btn-livechat1').click(function(){
		launchEGain();
		$('#chat-model').hide();
	});
    fireModelChat = function(){
		setTimeout(function(){
			$('#chat-model').fadeIn('slow');
		}, 3500);
		
		$('#chat-model-close').click(function(){
			$('#chat-model').hide();
			$.cookie('chatModel', '1', { expires: 1 });
		});
		
    };
	// clear chat cookie  (TESTING ONLY )
	//$.cookie('chatModel', '1', { expires: 0 });

	if(! jQuery.browser.mobile){
		if ($.cookie("chatModel") != 1){
			fireModelChat();
		};
	}
	 var eglvchathandle = null;
	 launchEGain = function() {
		try {
			if (eglvchathandle != null && eglvchathandle.closed == false) {
				eglvchathandle.focus();
				return;
			}
		} catch (err) {}
	
		var refererName = "";
		refererName = encodeURIComponent(refererName);
		var refererurl = encodeURIComponent(document.location.href);
		var hashIndex = refererurl.lastIndexOf('#');
	
		if (hashIndex != -1) {
			refererurl = refererurl.substring(0, hashIndex);
		}
	
		var w = 400, h = 650, t = 0, l = 0;
	
		if (window.screen) {
			l = (window.screen.availWidth - w) * 98 / 100;
		}
	
		var eglvcaseid = (/eglvcaseid=[0-9]*/gi).exec(window.location.search);
		var params = "width=" + w + ",height=" + h + ",left=" + l + ",top=" + t + ",resizable=no,scrollbars=yes,toolbar=no";

		//URL of chat
		eglvchathandle = window.open('http://egain.forthepeople.com/system/web/view/live/templates/MorganMorgan/chat.html?entryPointId=' + entrypoint + '&referer=' + refererurl + '&eglvrefname=' + refererName + '&' + eglvcaseid, '', params)
	}	