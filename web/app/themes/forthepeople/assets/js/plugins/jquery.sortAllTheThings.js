;(function ( $, window, document, undefined ) {

    var pluginName = "sortAllTheThings",
        defaults = {
            filterInput: '#pageFilter',
			sortList:'.filterList',
			placeholder:'filter...'
        };

    // The plugin constructor
    function Plugin( element, options ) {
        this.element = element;

        this.options = $.extend( {}, defaults, options );

        this._defaults = defaults;
        this._name = pluginName;

        this.init();
    }

    Plugin.prototype = {

        init: function(el, options) {
			
		  jQuery.expr[':'].Contains = function(a,i,m){
			  return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase())>=0;
		  };
		  
		  var filterInput = this.options.filterInput;
		  var sortList= this.options.sortList;
		  
		 // create and add the filter form to the header
			var form = $('<form>').attr({'class':'filterform','action':'#'}),
				input = $('<input>').attr({'class':'filterinput input-block-level','type':'text','placeholder': this.options.placeholder});
			
			$(form).append(input).appendTo(filterInput);
			
			
			//.append('<div class="input-prepend"><span class="add-on"><i class="icon-filter"></i></span>')
			
			$(input).change( function (options) {
				var filter = $(this).val();
				if(filter) {
				  // this finds all links in a list that contain the input,
				  // and hide the ones not containing the input while showing the ones that do
				  $(sortList).find("a:not(:Contains(" + filter + "))").parent().slideUp();
				  $(sortList).find("a:Contains(" + filter + ")").parent().slideDown();
				} else {
				  $(sortList).find("li").slideDown();
				}
				return false;
			  }).keyup( function () {
				// fire the above change event after every letter
				$(this).change();
			});
			  
        }//init
    };

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[pluginName] = function ( options ) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName, new Plugin( this, options ));
            }
        });
    };

})( jQuery, window, document );
