jQuery(function($){
    $(document).scroll(function() {
        var y = $(this).scrollTop();
        if (y > 100) {
            $('#social-share-icons').removeClass('hidden');
        } else {
            $('#social-share-icons').addClass('hidden');
        }
    });

});