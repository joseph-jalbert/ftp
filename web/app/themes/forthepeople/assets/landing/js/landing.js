jQuery(function($){
	
	$('input[lc-placeholder], textarea[lc-placeholder]').focus(function() {
	  var input = jQuery(this);
	  if (input.val() == input.attr('lc-placeholder')) {
		if (this.originalType) {
		  this.type = this.originalType;
		  delete this.originalType;
		}
		input.val('');
		input.removeClass('lc_form_placeholder');
	  }
	}).blur(function() {
	  var input = jQuery(this);
	  if (input.val() == '') {
		if (this.type == 'password') {
		  this.originalType = this.type;
		  this.type = 'text';
		}
		input.addClass('lc_form_placeholder');
		input.val(input.attr('lc-placeholder'));
	  }
	}).blur();
	
	// Set starting slide to 1
	var startSlide = 1;
	
	// Initialize Slides
	$('#slider').slides({
		preload: true,
		preloadImage: 'images/loading.gif',
		generatePagination: true,
		play: 5000,
		pause: 5000,
		hoverPause: true,
		// Get the starting slide
		start: startSlide
	});

	
});

