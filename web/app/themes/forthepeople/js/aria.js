( function( $ ) {
    // bind a click event to the 'skip' link
    $(".skip-link").click(function(event){
        
        // strip the leading hash and declare
        // the content we're skipping to
        var skipTo="#"+this.href.split('#')[1];

        $(skipTo).focus(); // focus on the content container
    });
}) ( jQuery );