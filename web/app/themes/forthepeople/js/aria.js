( function( $ ) {
    // bind a click event to the 'skip' link
    $(".skip-link").click(function(event){
        // strip the leading hash and declare
        // the content we're skipping to
        var skipTo="#"+this.href.split('#')[1];

        $(skipTo).focus(); // focus on the content container
    });

    //set aria-visible to true when focused inside a dropdown menu
    var $item = $(".dropdown-menu > li > a");

    $item.focus(function(){
        $(this).parents(".dropdown-menu").attr('aria-hidden', 'false');
        $(this).parents(".dropdown").attr('aria-expanded', 'true');

    });

    function focusCheck() {
        if($item.parents(".dropdown-menu").find("a:focus").length == 0){
            $item.parents(".dropdown-menu").attr('aria-hidden', 'true');
            $item.parents(".dropdown").attr('aria-expanded', 'false');
        }
    }
    $item.blur(function(){
        setTimeout(focusCheck, 0);
    })
}) ( jQuery );