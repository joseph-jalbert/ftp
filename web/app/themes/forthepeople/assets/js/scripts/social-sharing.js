jQuery(function($){

    var limit = 100;

    var scroll = jQuery(document).scrollTop();
    if (scroll > limit) {
        show_social_icons();
    } else {
        hide_social_icons();
    }

    $(document).scroll(function() {
        var y = $(this).scrollTop();
        if (y > limit) {
            show_social_icons();
        } else {
            hide_social_icons();
        }
    });


    function show_social_icons() {
        $('#social-share-icons').removeClass('hidden');
    }

    function hide_social_icons() {
        $('#social-share-icons').addClass('hidden');
    }
});