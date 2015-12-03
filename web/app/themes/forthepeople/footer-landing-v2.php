<?php
/**
 * The template for displaying the landing page footer (v2).
 *
 * @package ForThePeople
 */
?>
</body>
<?php wp_footer(); ?>
<script type="text/javascript">
jQuery(function($) {
    $('.testimonials div:not(:first)').hide();
    $('.testimonials div').css('position', 'absolute');
    $('.testimonials div').css('top', '0px');
    var pause = false;
    
    function fadeNext() {
        $('.testimonials div').first().fadeOut().appendTo($('.testimonials'));
        $('.testimonials div').first().fadeIn();
    }
    
    var rotate = setInterval(fadeNext, 7000);
});
</script>
</html>
