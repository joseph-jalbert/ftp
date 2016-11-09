jQuery(document).ready(function ($) {
    $(document).on('click', '#more-button-wrapper .view-more', function() {
        $("div.entry-content").removeClass("article-hide");
        $('div.widgetWrap.aside').removeClass('widget-hide');
        $('div.hs-wrapper').removeClass('hsform-hide');
        $("a.blog-btn").remove();
    });

    $(document).on('click', '#more-button-wrapper .free-case-evaluation', function() {
        $('div.hs-wrapper').removeClass('hsform-hide');
        target = $('div.hs-wrapper');
        if (target.length) {
            var top = target.offset().top;
            jQuery('html,body').animate({
                scrollTop: top
            }, 500);
        }
        $('#more-button-wrapper .free-case-evaluation').remove();
    });

    if ( $('body.single-post').length > 0 ) {
        if ( $(document).width() < 990 ) {
            $('div.entry-content').addClass('article-hide');
            $('div.widgetWrap.aside').addClass('widget-hide');
            $('div.hs-wrapper').addClass('hsform-hide');
            $('div#col2.widget-area').prepend('<div id="more-button-wrapper" class="button-wrap"><a class="blog-btn view-more" >MORE</a><a href="#" class="blog-btn free-case-evaluation" >FREE CASE EVALUATION</a></div>');
        }
    }
});