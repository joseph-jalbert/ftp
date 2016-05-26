( function( $ ) {
    // bind a click event to the 'skip' link
    $(".skip-link").click(function(event){
        // strip the leading hash and declare
        // the content we're skipping to
        var skipTo="#"+this.href.split('#')[1];

        $(skipTo).focus(); // focus on the content container
    });

    //set aria-visible to true when focused inside a dropdown menu
    var dropdown = $(".dropdown-menu");

    dropdown.focus(function(){
        $(this).attr('aria-hidden', 'false');
    });

    function focusCheck() {
        if(dropdown.find(":focus").length == 0){
            dropdown.attr('aria-hidden', 'true');
        }
    }
    dropdown.find("a").blur(function(){
        setTimeout(focusCheck, 200);
    })
}) ( jQuery );